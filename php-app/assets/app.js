'use strict';
const menuButton=document.querySelector('#mobile-toggle');const nav=document.querySelector('#main-nav');
if(menuButton&&nav){menuButton.addEventListener('click',()=>{const opened=nav.classList.toggle('open');menuButton.setAttribute('aria-expanded',String(opened));menuButton.textContent=opened?'✕':'☰';});}
document.querySelectorAll('form[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!confirm(form.dataset.confirm||'Are you sure?'))event.preventDefault();}));
document.querySelectorAll('input[type="number"]').forEach(input=>input.addEventListener('change',()=>{const min=Number(input.min||0),max=Number(input.max||1e9),value=Number(input.value);if(!Number.isFinite(value))return;input.value=String(Math.min(max,Math.max(min,value)));}));
