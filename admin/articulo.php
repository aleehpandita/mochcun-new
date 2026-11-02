<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/_layout.php';
require ROOT . '/app/sanitizer.php';
require ROOT . '/app/uploads.php';

$user = require_login();
$cats = config('blog_categories');
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$post = null;
if ($id) {
    $st = db()->prepare('SELECT * FROM posts WHERE id = ?');
    $st->execute([$id]);
    $post = $st->fetch();
    if (!$post) {
        flash('Ese artículo no existe.', 'err');
        redirect('/admin/');
    }
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    // Eliminar
    if (($_POST['accion'] ?? '') === 'eliminar' && $post) {
        upload_delete_cover((string) $post['cover']);
        db()->prepare('DELETE FROM posts WHERE id = ?')->execute([$post['id']]);
        flash('Artículo eliminado.');
        redirect('/admin/');
    }

    $title = trim((string) ($_POST['title'] ?? ''));
    $slug = slugify(trim((string) ($_POST['slug'] ?? '')) ?: $title);
    $category = (string) ($_POST['category'] ?? '');
    $status = ($_POST['status'] ?? '') === 'publicado' ? 'publicado' : 'borrador';
    $publishedInput = trim((string) ($_POST['published_at'] ?? ''));
    $seoTitle = trim((string) ($_POST['seo_title'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $coverAlt = trim((string) ($_POST['cover_alt'] ?? ''));
    $content = sanitize_post_html((string) ($_POST['content'] ?? ''));

    if ($title === '') {
        $errors[] = 'El título es obligatorio.';
    }
    if (!isset($cats[$category])) {
        $errors[] = 'Elige una categoría.';
    }
    if ($status === 'publicado' && trim(strip_tags($content)) === '') {
        $errors[] = 'No se puede publicar un artículo sin contenido.';
    }
    $dup = db()->prepare('SELECT COUNT(*) FROM posts WHERE slug = ? AND id != ?');
    $dup->execute([$slug, $post['id'] ?? 0]);
    if ((int) $dup->fetchColumn() > 0) {
        $errors[] = 'Ya existe otro artículo con la dirección /blog/' . $slug . '/. Cámbiala.';
    }
    $publishedAt = $post['published_at'] ?? null;
    if ($publishedInput !== '') {
        $ts = strtotime($publishedInput);
        if ($ts === false) {
            $errors[] = 'La fecha de publicación no es válida.';
        } else {
            $publishedAt = date('Y-m-d H:i:s', $ts);
        }
    }
    if ($status === 'publicado' && !$publishedAt) {
        $publishedAt = now();
    }

    $cover = (string) ($post['cover'] ?? '');
    if (!$errors && !empty($_FILES['cover']['name'])) {
        try {
            $newCover = upload_cover($_FILES['cover'], $slug);
            if ($cover !== '') {
                upload_delete_cover($cover);
            }
            $cover = $newCover;
        } catch (RuntimeException $e) {
            $errors[] = 'Portada: ' . $e->getMessage();
        }
    }
    if (!$errors && !empty($_POST['quitar_portada']) && $cover !== '') {
        upload_delete_cover($cover);
        $cover = '';
    }

    if (!$errors) {
        $data = [
            'slug' => $slug, 'title' => $title, 'seo_title' => mb_substr($seoTitle, 0, 120),
            'description' => mb_substr($description, 0, 300), 'category' => $category,
            'cover' => $cover, 'cover_alt' => mb_substr($coverAlt, 0, 200), 'content' => $content,
            'status' => $status, 'published_at' => $publishedAt, 'updated_at' => now(),
        ];
        if ($post) {
            // Si cambia la dirección de un artículo, la anterior redirige a la nueva
            if ($post['slug'] !== $slug) {
                db()->prepare('INSERT OR REPLACE INTO slug_redirects (old_slug, post_id) VALUES (?, ?)')->execute([$post['slug'], $post['id']]);
                db()->prepare('DELETE FROM slug_redirects WHERE old_slug = ?')->execute([$slug]);
            }
            $sets = implode(', ', array_map(fn ($k) => "$k = :$k", array_keys($data)));
            $st = db()->prepare("UPDATE posts SET $sets WHERE id = :id");
            $st->execute($data + ['id' => $post['id']]);
            $savedId = (int) $post['id'];
        } else {
            $data['created_at'] = now();
            $data['author_id'] = $user['id'];
            $cols = implode(', ', array_keys($data));
            $vals = implode(', ', array_map(fn ($k) => ":$k", array_keys($data)));
            db()->prepare("INSERT INTO posts ($cols) VALUES ($vals)")->execute($data);
            $savedId = (int) db()->lastInsertId();
        }
        $msg = $status === 'publicado'
            ? ($publishedAt > now() ? 'Guardado. Se publicará el ' . fecha_es($publishedAt) . '.' : 'Publicado. Ya está visible en el blog.')
            : 'Borrador guardado. No es visible al público.';
        flash($msg);
        redirect('/admin/articulo.php?id=' . $savedId);
    }
    // Con errores: se vuelve a mostrar lo que escribió
    $post = array_merge($post ?? [], [
        'title' => $title, 'slug' => $slug, 'category' => $category, 'status' => $status,
        'published_at' => $publishedAt, 'seo_title' => $seoTitle, 'description' => $description,
        'cover_alt' => $coverAlt, 'content' => $content, 'cover' => $cover,
    ]);
}

$p = $post ?? ['id' => 0, 'title' => '', 'slug' => '', 'category' => array_key_first($cats), 'status' => 'borrador', 'published_at' => null, 'seo_title' => '', 'description' => '', 'cover' => '', 'cover_alt' => '', 'content' => ''];
$c = cover_urls((string) $p['cover']);
$isLive = !empty($p['id']) && $p['status'] === 'publicado' && $p['published_at'] && $p['published_at'] <= now();
$dateValue = $p['published_at'] ? date('Y-m-d\TH:i', strtotime($p['published_at'])) : date('Y-m-d\TH:i');

admin_head($p['id'] ? 'Editar artículo' : 'Nuevo artículo', $user, '<link rel="stylesheet" href="' . e(asset_admin('vendor/quill.snow.css')) . '">');
?>
  <div class="a-head">
    <div>
      <a class="a-back" href="/admin/">← Artículos</a>
      <h1><?= $p['id'] ? 'Editar artículo' : 'Nuevo artículo' ?></h1>
    </div>
    <?php if ($p['id']): [$label, $cls] = status_label($p); ?>
      <div class="a-head-actions"><span class="a-status a-status-<?= $cls ?>"><?= $label ?></span><a class="a-btn" href="/blog/<?= e($p['slug']) ?>/" target="_blank" rel="noopener"><?= $isLive ? 'Ver en el sitio' : 'Vista previa' ?></a></div>
    <?php endif; ?>
  </div>
<?php if ($errors): ?>
  <div class="a-alert a-alert-err" role="alert"><?php foreach ($errors as $er) echo '<div>' . e($er) . '</div>'; ?></div>
<?php endif; ?>

  <form id="post-form" class="a-editor" method="post" enctype="multipart/form-data" action="/admin/articulo.php<?= $p['id'] ? '?id=' . (int) $p['id'] : '' ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
    <input type="hidden" name="content" id="content-input" value="<?= e($p['content']) ?>">

    <div class="a-col-main">
      <label class="a-title-field"><span class="sr">Título</span><input name="title" id="title" required maxlength="160" placeholder="Título del artículo" value="<?= e($p['title']) ?>"></label>
      <div class="a-slug">
        <span>mochcun.com/blog/</span><input name="slug" id="slug" value="<?= e($p['slug']) ?>" pattern="[a-z0-9\-]+" aria-label="Dirección del artículo" data-locked="<?= $p['id'] ? '1' : '0' ?>"><span>/</span>
      </div>
      <?php if ($isLive): ?><p class="a-hint">Este artículo ya está publicado. Si cambias la dirección, la anterior redirigirá a la nueva automáticamente.</p><?php endif; ?>

      <div class="a-card a-card-flush">
        <div id="editor"></div>
      </div>
      <p class="a-hint">Usa “Subtítulo” (H2) para dividir el artículo en secciones; ayuda a Google y a la lectura en celular. Para insertar una imagen, usa el botón de imagen de la barra.</p>
    </div>

    <aside class="a-col-side">
      <div class="a-card">
        <h2>Publicación</h2>
        <label>Estado
          <select name="status" id="status">
            <option value="borrador"<?= $p['status'] !== 'publicado' ? ' selected' : '' ?>>Borrador (no visible)</option>
            <option value="publicado"<?= $p['status'] === 'publicado' ? ' selected' : '' ?>>Publicado</option>
          </select>
        </label>
        <label>Fecha de publicación
          <input type="datetime-local" name="published_at" value="<?= e($dateValue) ?>">
        </label>
        <p class="a-hint">Si eliges una fecha futura, el artículo aparecerá solo ese día.</p>
        <label>Categoría
          <select name="category" required>
<?php foreach ($cats as $slug => $name): ?>
            <option value="<?= e($slug) ?>"<?= $p['category'] === $slug ? ' selected' : '' ?>><?= e($name) ?></option>
<?php endforeach; ?>
          </select>
        </label>
        <button class="a-btn a-btn-red a-btn-block" type="submit" id="save-btn"><?= $p['status'] === 'publicado' ? 'Guardar cambios' : 'Guardar' ?></button>
      </div>

      <div class="a-card">
        <h2>Imagen de portada</h2>
        <div class="a-cover" id="cover-preview"><?php if ($c): ?><img src="<?= e($c['medium']) ?>" alt=""><?php else: ?><span>Sin portada</span><?php endif; ?></div>
        <label class="a-file">Elegir imagen<input type="file" name="cover" id="cover" accept="image/jpeg,image/png,image/webp"></label>
        <p class="a-hint">Horizontal, idealmente de 1600 px o más de ancho. Se optimiza sola.</p>
        <label>Texto alternativo (qué muestra la imagen)<input name="cover_alt" maxlength="200" value="<?= e($p['cover_alt']) ?>" placeholder="Ej. Técnico revisando frenos de un autobús"></label>
        <?php if ($c): ?><label class="a-check"><input type="checkbox" name="quitar_portada" value="1"> Quitar portada</label><?php endif; ?>
      </div>

      <div class="a-card">
        <h2>Cómo se verá en Google</h2>
        <div class="a-serp" aria-hidden="true">
          <div class="a-serp-url">mochcun.com › blog › <span id="serp-slug"><?= e($p['slug'] ?: '...') ?></span></div>
          <div class="a-serp-title" id="serp-title"><?= e(($p['seo_title'] ?: $p['title'] ?: 'Título del artículo') . ' | MOCHCUN') ?></div>
          <div class="a-serp-desc" id="serp-desc"><?= e($p['description'] ?: 'Escribe una descripción…') ?></div>
        </div>
        <label>Título para Google <small class="a-count" data-for="seo_title" data-max="60"></small>
          <input name="seo_title" id="seo_title" maxlength="120" value="<?= e($p['seo_title']) ?>" placeholder="Opcional: si lo dejas vacío se usa el título">
        </label>
        <label>Descripción para Google <small class="a-count" data-for="description" data-max="160"></small>
          <textarea name="description" id="description" rows="4" maxlength="300" placeholder="Resume el artículo en 1 o 2 frases con las palabras que busca la gente (ej. licencia federal Cancún)."><?= e($p['description']) ?></textarea>
        </label>
      </div>

<?php if ($p['id']): ?>
      <div class="a-card a-danger">
        <h2>Eliminar</h2>
        <p class="a-hint">Se borra el artículo y su portada. No se puede deshacer.</p>
        <button class="a-btn a-btn-outline-red a-btn-block" type="submit" name="accion" value="eliminar" formnovalidate data-confirm="¿Eliminar este artículo definitivamente?">Eliminar artículo</button>
      </div>
<?php endif; ?>
    </aside>
  </form>
<?php
admin_foot('<script src="' . e(asset_admin('vendor/quill.js')) . '"></script>'
    . '<script>window.MOCHCUN_CSRF=' . json_encode(csrf_token()) . ';</script>'
    . '<script src="' . e(asset_admin('editor.js')) . '"></script>');
