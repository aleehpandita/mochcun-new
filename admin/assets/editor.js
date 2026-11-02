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
          ['clean']
        ],
        handlers: { image: pickImage }
      }
    }
  });
  // Etiquetas en español para la barra
  var picker = document.querySelector('.ql-header .ql-picker-label');
  if (picker) picker.setAttribute('aria-label', 'Tipo de texto');

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
