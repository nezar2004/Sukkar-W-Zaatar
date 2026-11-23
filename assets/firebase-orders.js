import { firebaseConfig, ordersApiUrl } from './firebase-config.js?v=1';

let services;
async function connect() {
  if (!firebaseConfig) throw new Error('Online ordering is not connected yet.');
  if (!services) {
    services = Promise.all([
      import('https://www.gstatic.com/firebasejs/11.10.0/firebase-app.js'),
      import('https://www.gstatic.com/firebasejs/11.10.0/firebase-auth.js'),

    ]).then(([app, auth]) => {
      const instance = app.initializeApp(firebaseConfig);
      return { auth, login: auth.getAuth(instance) };
    }).catch(error => { services = undefined; throw error; });
  }
  return services;
}

export async function requestOrderService(name, data = {}, guest = false) {
  const client = await connect();
  if (guest) {
    await client.login.authStateReady();
    if (!client.login.currentUser) await client.auth.signInAnonymously(client.login);
  }
  await client.login.authStateReady();
  const token = await client.login.currentUser?.getIdToken();
  const response = await fetch(ordersApiUrl + '/api/' + name, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', ...(token ? { Authorization: 'Bearer ' + token } : {}) },
    body: JSON.stringify(data),
    signal: AbortSignal.timeout(90000),
  });
  const body = await response.json();
  if (!response.ok) throw new Error(body.error || 'Could not complete the request.');
  return body.result;
}

export async function sendStaffLink(email) {
  const client = await connect();
  const url = new URL('firebase-orders.html', location.href).href;
  await client.auth.sendSignInLinkToEmail(client.login, email, { url, handleCodeInApp: true });
  localStorage.setItem('sz-staff-email', email);
}

export async function isStaffLink() {
  const client = await connect();
  return client.auth.isSignInWithEmailLink(client.login, location.href);
}

export async function signInStaff(email) {
  const client = await connect();
  await client.auth.signInWithEmailLink(client.login, email, location.href);
  try { await requestOrderService('listBakeryOrders', { status: 'New' }); }
  catch (error) { await client.auth.signOut(client.login); throw error; }
  localStorage.removeItem('sz-staff-email');
  history.replaceState(null, '', location.pathname);
}
export async function signOutStaff() {
  const client = await connect();
  await client.auth.signOut(client.login);
}

export { firebaseConfig };
let pendingOrder;
export async function submitOrder(details) {
  const payload = JSON.stringify(details);
  if (pendingOrder?.payload !== payload) {
    const key = Array.from(crypto.getRandomValues(new Uint8Array(32)), byte => byte.toString(16).padStart(2, '0')).join('');
    pendingOrder = { payload, key };
  }
  const result = await requestOrderService('placeBakeryOrder', { ...details, key: pendingOrder.key }, true);
  pendingOrder = undefined;
  return result;
}
