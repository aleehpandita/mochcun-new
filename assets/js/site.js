/* MOCHCUN — JS mínimo: menú, submenús, buscador de calendario y copiar enlace. */
(function () {
  'use strict';
  var body = document.body;
  var nav = document.getElementById('menu');
  var openBtn = document.querySelector('[data-menu-open]');
  var closeBtn = document.querySelector('[data-menu-close]');

  function setMenu(open) {
    if (!nav) return;
    nav.classList.toggle('open', open);
    body.classList.toggle('menu-open', open);
    if (openBtn) openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (open && closeBtn) closeBtn.focus();
    if (!open && openBtn) openBtn.focus();
  }
  if (openBtn) openBtn.addEventListener('click', function () { setMenu(true); });
  if (closeBtn) closeBtn.addEventListener('click', function () { setMenu(false); });

  var subBtns = document.querySelectorAll('.nav-btn');
  function closeSubs(except) {
    subBtns.forEach(function (b) { if (b !== except) b.setAttribute('aria-expanded', 'false'); });
  }
  subBtns.forEach(function (btn) {
    btn.addEventListener('click', function (ev) {
      ev.stopPropagation();
      var open = btn.getAttribute('aria-expanded') === 'true';
      closeSubs(btn);
      btn.setAttribute('aria-expanded', open ? 'false' : 'true');
    });
  });
  document.addEventListener('click', function (ev) {
    if (!ev.target.closest('.has-sub')) closeSubs(null);
  });
  document.addEventListener('keydown', function (ev) {
    if (ev.key === 'Escape') {
      closeSubs(null);
      if (nav && nav.classList.contains('open')) setMenu(false);
    }
  });
  if (nav) {
    nav.addEventListener('click', function (ev) {
      var a = ev.target.closest('a');
      if (a && nav.classList.contains('open') && a.getAttribute('href').charAt(0) === '#') setMenu(false);
    });
  }

  /* Buscador: ¿en qué meses verifico? */
  var form = document.getElementById('plate-form');
  if (form) {
    var out = document.getElementById('plate-result');
    var rows = document.querySelectorAll('[data-digits]');
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var tipo = form.tipo.value;
      var placa = (form.placa.value || '').toUpperCase();
      var nums = placa.replace(/[^0-9]/g, '').split('');
      rows.forEach(function (r) { r.classList.remove('is-hit'); });
      if (!nums.length) { out.textContent = 'Escribe tu placa con números y letras, por ejemplo 21RA4Z3.'; return; }
      var d;
      if (tipo === 'federal') {
        if (nums.length < 3) { out.textContent = 'En placas federales se usa el tercer dígito numérico; tu placa tiene menos de tres números.'; return; }
        d = nums[2];
      } else {
        d = nums[nums.length - 1];
      }
      var hit = null;
      rows.forEach(function (r) { if (r.getAttribute('data-digits').split(',').indexOf(d) !== -1) hit = r; });
      if (!hit) return;
      hit.classList.add('is-hit');
      out.innerHTML = '';
      var t = document.createElement('span');
      t.textContent = 'Placa ' + placa + ' → dígito ';
      var s = document.createElement('strong');
      s.textContent = d;
      var t2 = document.createElement('span');
      t2.textContent = '. Tu físico-mecánica toca en: ';
      var m = document.createElement('strong');
      m.textContent = hit.getAttribute('data-months') + '.';
      out.appendChild(t); out.appendChild(s); out.appendChild(t2); out.appendChild(m);
    });
  }

  /* Copiar enlace del artículo */
  var copy = document.querySelector('[data-copy]');
  if (copy && navigator.clipboard) {
    copy.addEventListener('click', function () {
      navigator.clipboard.writeText(copy.getAttribute('data-copy')).then(function () {
        var label = copy.querySelector('span');
        if (label) { var old = label.textContent; label.textContent = 'Enlace copiado'; setTimeout(function () { label.textContent = old; }, 2000); }
      });
    });
  }
})();
