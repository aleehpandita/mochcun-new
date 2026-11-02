document.addEventListener('click', function (ev) {
  var b = ev.target.closest('[data-confirm]');
  if (b && !window.confirm(b.getAttribute('data-confirm'))) ev.preventDefault();
});
