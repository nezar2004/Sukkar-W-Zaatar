const { randomBytes, createHash } = require('node:crypto');
const { initializeApp, cert } = require('firebase-admin/app');
const { getFirestore, FieldValue } = require('firebase-admin/firestore');
class HttpsError extends Error {
  constructor(code, message) {
    super(message);
    this.code = code;
  }
}

const credential = process.env.FIREBASE_SERVICE_ACCOUNT;
initializeApp(credential
  ? { credential: cert(JSON.parse(credential)) }
  : { projectId: process.env.GCLOUD_PROJECT });
const db = getFirestore();
const catalog = new Map(require('./catalog.json').map(item => [item.id, item]));
const orders = db.collection('bakeryOrders');
const inventory = db.collection('bakeryInventory');
const keys = db.collection('bakeryCheckoutKeys');
const tokens = db.collection('bakeryTracking');
const digest = value => createHash('sha256').update(value).digest('hex');
const stages = { New: 'Preparing', Preparing: 'Ready', Ready: 'Completed' };

function invalid(message) {
  throw new HttpsError('invalid-argument', message);
}

function staff(request) {
  const token = request.auth?.token;
  const staffEmail = process.env.BAKERY_STAFF_EMAIL;
  if (!staffEmail || token?.email_verified !== true || token.email !== staffEmail) {
    throw new HttpsError('permission-denied', 'Staff access is required.');
  }
}

function trackingSummary(order) {
  return { id: order.id, status: order.status, method: order.method };
}

exports.placeBakeryOrder = async request => {
  if (!request.auth) throw new HttpsError('unauthenticated', 'Please reconnect and try again.');
  const data = request.data || {};
  const { key, method, total } = data;
  const name = typeof data.name === 'string' ? data.name.trim() : '';
  const phone = typeof data.phone === 'string' ? data.phone.trim() : '';
  const address = method === 'delivery' && typeof data.address === 'string' ? data.address.trim() : '';
  if (typeof key !== 'string' || !/^[a-f0-9]{64}$/.test(key)) invalid('Invalid checkout reference.');
  if (!['delivery', 'pickup'].includes(method) || name.length < 2 || name.length > 80
      || !/^0[0-9]{9}$/.test(phone) || address.length > 220 || (method === 'delivery' && !address)) {
    invalid('Check your contact and fulfilment details.');
  }
  if (!Array.isArray(data.items) || !data.items.length || data.items.length > catalog.size) invalid('Check your bag.');
  const ids = new Set();
  const items = data.items.map(item => {
    const product = catalog.get(item?.id);
    const qty = item?.qty;
    if (!product || !Number.isInteger(qty) || qty < 1 || qty > product.qty || ids.has(product.id)) invalid('Check the quantities in your bag.');
    ids.add(product.id);
    return { id: product.id, name: product.name, qty, unitPrice: product.price };
  });
  const subtotal = items.reduce((sum, item) => sum + item.unitPrice * item.qty, 0);
  const fee = method === 'delivery' && subtotal < 2500 ? 200 : 0;
  const checkoutKey = keys.doc(digest(request.auth.uid + ':' + key));
  const orderRef = orders.doc();
  const token = randomBytes(32).toString('hex');
  const tokenRef = tokens.doc(digest(token));
  const rateRef = db.collection('bakeryCheckoutRate').doc(request.auth.uid);
  const stockRefs = items.map(item => inventory.doc(String(item.id)));

  return db.runTransaction(async transaction => {
    const previous = await transaction.get(checkoutKey);
    if (previous.exists) return previous.data();
    if (total !== subtotal + fee) throw new HttpsError('failed-precondition', 'The prices changed. Review your bag.');
    const [rate, ...snapshots] = await transaction.getAll(rateRef, ...stockRefs);
    if (rate.exists && Date.now() - rate.data().lastOrder < 15000) {
      throw new HttpsError('resource-exhausted', 'Please wait before placing another order.');
    }
    const balances = snapshots.map((snapshot, index) => {
      const item = items[index];
      const available = snapshot.exists ? snapshot.data().qty : catalog.get(item.id).qty;
      if (item.qty > available) throw new HttpsError('failed-precondition', item.name + ' has insufficient stock.');
      return available - item.qty;
    });
    const order = { id: orderRef.id, status: 'New', method, name, phone, address, items, subtotal, fee, total, createdAt: FieldValue.serverTimestamp() };
    const result = { ...trackingSummary(order), token };
    transaction.create(orderRef, order);
    transaction.create(tokenRef, { orderId: orderRef.id });
    transaction.create(checkoutKey, result);
    transaction.set(rateRef, { lastOrder: Date.now() });
    stockRefs.forEach((ref, index) => transaction.set(ref, { qty: balances[index] }));
    return result;
  });
};

exports.trackBakeryOrder = async request => {
  const token = request.data?.token;
  if (typeof token !== 'string' || !/^[a-f0-9]{64}$/.test(token)) invalid('Invalid tracking link.');
  const entry = await tokens.doc(digest(token)).get();
  if (!entry.exists) throw new HttpsError('not-found', 'Order not found.');
  const order = await orders.doc(entry.data().orderId).get();
  if (!order.exists) throw new HttpsError('not-found', 'Order not found.');
  return trackingSummary(order.data());
};

exports.listBakeryOrders = async request => {
  staff(request);
  const status = request.data?.status;
  if (status && !['New', 'Preparing', 'Ready', 'Completed'].includes(status)) invalid('Invalid status.');
  const query = status ? orders.where('status', '==', status) : orders;
  const snapshot = await query.limit(100).get();
  return snapshot.docs.map(doc => {
    const { createdAt, ...order } = doc.data();
    return { ...order, date: createdAt.toDate().toISOString() };
  }).sort((a, b) => b.date.localeCompare(a.date));
};

exports.advanceBakeryOrder = async request => {
  staff(request);
  const { id, current } = request.data || {};
  if (typeof id !== 'string' || !/^[a-zA-Z0-9]{20}$/.test(id) || !Object.hasOwn(stages, current)) invalid('Invalid order update.');
  const ref = orders.doc(id);
  return db.runTransaction(async transaction => {
    const doc = await transaction.get(ref);
    if (!doc.exists) throw new HttpsError('not-found', 'Order not found.');
    if (doc.data().status !== current) throw new HttpsError('failed-precondition', 'This order changed. Refresh the list.');
    transaction.update(ref, { status: stages[current] });
    return { status: stages[current] };
  });
};

exports.getBakeryInventory = async () => {
  const stocks = await inventory.get();
  return Object.fromEntries(stocks.docs.map(doc => [doc.id, doc.data().qty]));
};
