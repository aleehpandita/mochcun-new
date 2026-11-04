/* Editor de artículos del panel MOCHCUN (Quill 2). */
(function () {
  'use strict';
  var form = document.getElementById('post-form');
  if (!form || !window.Quill) return;

  var input = document.getElementById('content-input');
  var quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Escribe aquí el artículo…',
    modules: {
      toolbar: {
        container: [
          [{ header: [2, 3, false] }],
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['blockquote', 'link', 'image'],
          ['reflow', 'clean']
        ],
        handlers: { image: pickImage, reflow: reflowSelection }
      }
    }
  });
  // Etiquetas en español para la barra
  var picker = document.querySelector('.ql-header .ql-picker-label');
  if (picker) picker.setAttribute('aria-label', 'Tipo de texto');

  var reflowBtn = document.querySelector('.ql-reflow');
  if (reflowBtn) {
    reflowBtn.textContent = 'Unir renglones';
    reflowBtn.title = 'Une en párrafos el texto que quedó cortado renglón por renglón (por ejemplo, copiado de un PDF). Selecciona el texto primero.';
    reflowBtn.classList.add('ql-reflow-text');
  }

  /* ---------- Texto pegado desde PDF: un salto de línea por renglón ---------- */
  var END = /[.:!?…»"”)]$/;
  function esc(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }
  function isListLine(l) { return /^([-–•·*▪●]|\d{1,2}[.)])\s+/.test(l); }
  // Convierte texto con saltos por renglón en párrafos HTML.
  // Las líneas cortas sin punto final que inician bloque (títulos) quedan como párrafo propio.
  function reflowText(text) {
    var lines = text.replace(/\r\n?/g, '\n').replace(/\u00A0/g, ' ').split('\n').map(function (l) { return l.replace(/\s+/g, ' ').trim(); });
    var paras = [], buf = '';
    function flush() { if (buf) { paras.push(buf); buf = ''; } }
    for (var i = 0; i < lines.length; i++) {
      var l = lines[i];
      if (!l) { flush(); continue; }
      if (isListLine(l)) { flush(); paras.push(l); continue; }
      var startsBlock = !buf;
      var next = lines[i + 1] || '';
      var looksTitle = startsBlock && !END.test(l) && l.length <= 100 && /^[A-ZÁÉÍÓÚÑ¿¡0-9]/.test(next) && !/,$/.test(l);
      if (looksTitle) { paras.push(l); continue; }
      if (!buf) { buf = l; }
      else if (/[A-Za-zÁÉÍÓÚáéíóúñÑ]-$/.test(buf)) { buf += l; }          // palabra-cortada
      else { buf += ' ' + l; }
      if (END.test(l) && /^[A-ZÁÉÍÓÚÑ¿¡]/.test(next)) { flush(); }       // fin de párrafo
    }
    flush();
    return paras.map(function (p) { return '<p>' + esc(p) + '</p>'; }).join('');
  }
  // ¿Parece texto cortado por renglones? (muchas líneas que no terminan en punto)
  function looksWrapped(text) {
    var ls = text.split(/\r?\n/).filter(function (l) { return l.trim(); });
    if (ls.length < 4) return false;
    var cut = ls.filter(function (l) { return !END.test(l.trim()); }).length;
    return cut / ls.length > 0.4;
  }
  quill.root.addEventListener('paste', function (ev) {
    var cd = ev.clipboardData;
    if (!cd) return;
    var html = cd.getData('text/html');
    var text = cd.getData('text/plain');
    // Si viene con formato (Word, Google Docs, otra página web) lo dejamos a Quill.
    if ((html && !/<br\s*\/?>\s*<br|^\s*$/i.test(html) && /<(p|h\d|li)[\s>]/i.test(html)) || !text || !looksWrapped(text)) return;
    ev.preventDefault();
    ev.stopPropagation();
    var range = quill.getSelection(true);
    if (range.length) quill.deleteText(range.index, range.length, 'user');
    quill.clipboard.dangerouslyPasteHTML(range.index, reflowText(text), 'user');
  }, true);
  // Botón "Unir renglones": arregla texto que ya se pegó así.
  function reflowSelection() {
    var range = quill.getSelection(true);
    var r = range && range.length ? range : { index: 0, length: quill.getLength() };
    var text = quill.getText(r.index, r.length);
    if (!text.trim()) return;
    if (!range.length && !confirm('No seleccionaste texto. ¿Unir los renglones de todo el artículo? Se quitarán los formatos (subtítulos, negritas).')) return;
    quill.deleteText(r.index, r.length, 'user');
    quill.clipboard.dangerouslyPasteHTML(r.index, reflowText(text), 'user');
  }

  if (input.value) {
    quill.clipboard.dangerouslyPasteHTML(input.value, 'silent');
  }
  var dirty = false;
  quill.on('text-change', function () { dirty = true; });
  form.addEventListener('input', function () { dirty = true; });

  function pickImage() {
    var f = document.createElement('input');
    f.type = 'file';
    f.accept = 'image/jpeg,image/png,image/webp';
    f.onchange = function () {
      if (!f.files || !f.files[0]) return;
      var range = quill.getSelection(true);
      var fd = new FormData();
      fd.append('image', f.files[0]);
      fd.append('csrf', window.MOCHCUN_CSRF);
      quill.insertText(range.index, 'Subiendo imagen…', { italic: true }, 'user');
      fetch('/admin/subir.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          quill.deleteText(range.index, 'Subiendo imagen…'.length, 'user');
          if (data.url) {
            quill.insertEmbed(range.index, 'image', data.url, 'user');
            quill.setSelection(range.index + 1, 0);
          } else {
            alert(data.error || 'No se pudo subir la imagen.');
          }
        })
        .catch(function () {
          quill.deleteText(range.index, 'Subiendo imagen…'.length, 'user');
          alert('No se pudo subir la imagen. Revisa tu conexión.');
        });
    };
    f.click();
  }

  form.addEventListener('submit', function (ev) {
    var btn = ev.submitter;
    if (btn && btn.hasAttribute('data-confirm') && !confirm(btn.getAttribute('data-confirm'))) {
      ev.preventDefault();
      return;
    }
    var html = quill.getLength() > 1 ? quill.getSemanticHTML() : '';
    input.value = html.replace(/&nbsp;/g, ' ');
    dirty = false;
  });
  window.addEventListener('beforeunload', function (ev) {
    if (dirty) { ev.preventDefault(); ev.returnValue = ''; }
  });

  /* Dirección automática a partir del título (solo en artículos nuevos) */
  var title = document.getElementById('title');
  var slug = document.getElementById('slug');
  var slugTouched = slug.getAttribute('data-locked') === '1' && slug.value !== '';
  function slugify(t) {
    return t.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '')
      .replace(/ñ/g, 'n').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 90);
  }
  slug.addEventListener('input', function () { slugTouched = true; slug.value = slug.value.toLowerCase().replace(/[^a-z0-9-]/g, '-'); updateSerp(); });
  title.addEventListener('input', function () { if (!slugTouched) slug.value = slugify(title.value); updateSerp(); });

  /* Vista previa de Google y contadores */
  var seoTitle = document.getElementById('seo_title');
  var desc = document.getElementById('description');
  function updateSerp() {
    document.getElementById('serp-title').textContent = (seoTitle.value || title.value || 'Título del artículo') + ' | MOCHCUN';
    document.getElementById('serp-slug').textContent = slug.value || '...';
    document.getElementById('serp-desc').textContent = desc.value || 'Escribe una descripción…';
    document.querySelectorAll('.a-count').forEach(function (c) {
      var el = document.getElementById(c.getAttribute('data-for'));
      var max = +c.getAttribute('data-max');
      var n = el.value.length;
      c.textContent = n + ' / ' + max;
      c.classList.toggle('is-over', n > max);
    });
  }
  [seoTitle, desc].forEach(function (el) { el.addEventListener('input', updateSerp); });
  updateSerp();

  /* Vista previa de la portada */
  var cover = document.getElementById('cover');
  cover.addEventListener('change', function () {
    var box = document.getElementById('cover-preview');
    if (!cover.files || !cover.files[0]) return;
    var img = document.createElement('img');
    img.alt = '';
    img.src = URL.createObjectURL(cover.files[0]);
    box.innerHTML = '';
    box.appendChild(img);
  });
})();
