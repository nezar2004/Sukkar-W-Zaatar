import { firebaseConfig, requestOrderService, sendStaffLink, isStaffLink, signInStaff, signOutStaff } from './firebase-orders.js';

const byId = id => document.getElementById(id);
const status = byId('status');
const results = byId('results');
const token = new URLSearchParams(location.hash.slice(1)).get('track');
const stages = ['New', 'Preparing', 'Ready', 'Completed'];
function add(parent, tag, content) {
  const element = document.createElement(tag);
  element.textContent = content;
  parent.append(element);
  return element;
}
async function showTracking() {
  status.textContent = 'Loading order…';
  try {
    const order = await requestOrderService('trackBakeryOrder', { token });
    results.replaceChildren();
    add(results, 'h2', 'Order #' + order.id);
    const progress = add(results, 'ol', '');
    for (const stage of stages) {
      const label = stage === 'Ready' ? (order.method === 'pickup' ? 'Ready for pickup' : 'Ready for delivery') : stage;
      const item = add(progress, 'li', label);
      if (stage === order.status) item.setAttribute('aria-current', 'step');
    }
    status.textContent = '';
  } catch (error) { status.textContent = error.message; }
}

async function loadOrders() {
  status.textContent = 'Loading orders…';
  try {
    const orders = await requestOrderService('listBakeryOrders', { status: byId('filter').value });
    results.replaceChildren();
    for (const order of orders) {
      const card = add(results, 'article', '');
      add(card, 'h2', 'Order #' + order.id);
      add(card, 'p', order.status + ' · ' + order.method + ' · ' + (order.total / 100).toFixed(2) + ' JOD');
      add(card, 'p', [order.name, order.phone, order.address].filter(Boolean).join(' · '));
      add(card, 'p', order.items.map(item => item.qty + ' × ' + item.name).join(', '));
      const next = stages[stages.indexOf(order.status) + 1];
      if (!next) continue;
      const button = add(card, 'button', 'Mark as ' + next.toLowerCase());
      button.onclick = async () => {
        button.disabled = true;
        try {
          await requestOrderService('advanceBakeryOrder', { id: order.id, current: order.status });
          await loadOrders();
        } catch (error) {
          status.textContent = error.message;
          button.disabled = false;
        }
      };
    }
    status.textContent = orders.length ? '' : 'No orders in this status.';
  } catch (error) {
    results.replaceChildren();
    status.textContent = error.message;
  }
}

if (!firebaseConfig) status.textContent = 'Online ordering is not connected yet.';
else if (token) {
  byId('title').textContent = 'Track your order';
  byId('refresh').hidden = false;
  byId('refresh').onclick = showTracking;
  showTracking();
} else {
  const login = byId('login');
  const controls = byId('controls');
  const completing = await isStaffLink();
  if (completing) {
    login.elements.email.value = localStorage.getItem('sz-staff-email') || '';
    login.querySelector('button').textContent = 'Finish sign-in';
  }
  login.hidden = false;
  login.onsubmit = async event => {
    event.preventDefault();
    const submit = login.querySelector('button');
    submit.disabled = true;
    try {
      if (!completing) {
        await sendStaffLink(login.elements.email.value);
        status.textContent = 'Open the sign-in link sent to your email.';
        return;
      }
      await signInStaff(login.elements.email.value);
      login.reset();
      login.hidden = true;
      controls.hidden = false;
      await loadOrders();
    } catch (error) { status.textContent = error.message; }
    finally { submit.disabled = false; }
  };
  byId('filter').onchange = byId('staff-refresh').onclick = loadOrders;
  byId('staff-logout').onclick = async () => {
    try {
      await signOutStaff();
      results.replaceChildren();
      status.textContent = '';
      controls.hidden = true; location.reload();

    } catch (error) { status.textContent = error.message; }
  };
}
