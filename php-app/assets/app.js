'use strict';
const menuButton=document.querySelector('#mobile-toggle');const nav=document.querySelector('#main-nav');
if(menuButton&&nav){menuButton.addEventListener('click',()=>{const opened=nav.classList.toggle('open');menuButton.setAttribute('aria-expanded',String(opened));menuButton.textContent=opened?'✕':'☰';});}
document.querySelectorAll('form[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!confirm(form.dataset.confirm||'Are you sure?'))event.preventDefault();}));
document.querySelectorAll('input[type="number"]').forEach(input=>input.addEventListener('change',()=>{const min=Number(input.min||0),max=Number(input.max||1e9),value=Number(input.value);if(!Number.isFinite(value))return;input.value=String(Math.min(max,Math.max(min,value)));}));

const checkoutSummary = document.querySelector('#checkout-summary');
if (checkoutSummary) {
  const checkout = document.querySelector('.checkout-form');
  const address = checkout.elements.address;
  const subtotal = Number(checkoutSummary.dataset.subtotal);
  const delivery = Number(checkoutSummary.dataset.fee);
  function updateCheckoutTotal() {
    const pickup = checkout.elements.payment_method.value === 'pickup';
    const fee = pickup ? 0 : delivery;
    address.required = !pickup;
    address.disabled = pickup;
    document.querySelector('#checkout-fee').textContent = (fee / 100).toFixed(2) + ' JOD';
    document.querySelector('#checkout-total').textContent = ((subtotal + fee) / 100).toFixed(2) + ' JOD';
  }
  checkout.addEventListener('change', updateCheckoutTotal);
  updateCheckoutTotal();
}
