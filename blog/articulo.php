<?php
require dirname(__DIR__) . '/app/bootstrap.php';

$slug = (string) ($_GET['slug'] ?? '');
if (!preg_match('/^[a-z0-9-]{1,100}$/', $slug)) {
    not_found();
}
$st = db()->prepare('SELECT p.*, u.name AS author FROM posts p LEFT JOIN users u ON u.id = p.author_id WHERE p.slug = ?');
$st->execute([$slug]);
$post = $st->fetch();

// Si el artículo cambió de dirección, la anterior redirige a la nueva (no se pierde el SEO)
if (!$post) {
    $r = db()->prepare('SELECT p.slug FROM slug_redirects r JOIN posts p ON p.id = r.post_id WHERE r.old_slug = ?');
    $r->execute([$slug]);
    $newSlug = $r->fetchColumn();
    if ($newSlug) {
        redirect('/blog/' . $newSlug . '/', 301);
    }
}

// Vista previa para usuarios del panel (borradores o programados)
$preview = false;
if ($post && ($post['status'] !== 'publicado' || $post['published_at'] > now())) {
    require ROOT . '/admin/_auth.php';
    if (!admin_user()) {
        not_found();
    }
    $preview = true;
}
if (!$post) {
    not_found();
}
// Una sola URL por artículo: con diagonal final
if (!str_ends_with(current_path(), '/')) {
    redirect('/blog/' . $post['slug'] . '/', 301);
}

$path = '/blog/' . $post['slug'] . '/';
$cover = cover_urls((string) $post['cover']);
$desc = $post['description'] ?: plain_excerpt((string) $post['content']);
$cat = (string) $post['category'];
$page = [
    'title' => ($post['seo_title'] ?: $post['title']) . ' | MOCHCUN',
    'og_title' => $post['title'],
    'description' => $desc,
    'path' => $path,
    'og_type' => 'article',
    'image' => $cover ? url($cover['og']) : null,
    'published' => $post['published_at'],
    'modified' => $post['updated_at'],
    'noindex' => $preview,
    'breadcrumb' => [['Blog', '/blog/'], [category_name($cat), '/blog/categoria/' . $cat . '/'], [$post['title'], $path]],
    'preload_image' => $cover ? '<link rel="preload" as="image" imagesrcset="' . e($cover['medium']) . ' 800w, ' . e($cover['large']) . ' 1600w" imagesizes="(max-width: 1080px) 100vw, 1080px" fetchpriority="high">' : null,
    'schema' => [[
        '@type' => 'BlogPosting',
        '@id' => url($path) . '#articulo',
        'headline' => $post['title'],
        'description' => $desc,
        'image' => $cover ? [url($cover['og']), url($cover['large'])] : [url('/assets/img/og-mochcun.jpg')],
        'datePublished' => date('c', strtotime((string) $post['published_at'])),
        'dateModified' => date('c', strtotime((string) $post['updated_at'])),
        'author' => ['@type' => 'Organization', 'name' => 'MOCHCUN', 'url' => url('/')],
        'publisher' => ['@id' => url('/#negocio')],
        'mainEntityOfPage' => ['@id' => url($path) . '#pagina'],
        'articleSection' => category_name($cat),
        'inLanguage' => 'es-MX',
        'wordCount' => str_word_count(strip_tags((string) $post['content']), 0, 'áéíóúüñÁÉÍÓÚÜÑ'),
    ]],
];
$rel = db()->prepare("SELECT * FROM posts WHERE status = 'publicado' AND published_at <= ? AND id != ? ORDER BY (category = ?) DESC, published_at DESC LIMIT 3");
$rel->execute([now(), $post['id'], $cat]);
$related = $rel->fetchAll();
$shareUrl = url($path);
page_start($page);
?>
<?php if ($preview): ?>
<div style="background:#FFF4CC;color:#5C4400;padding:12px 16px;text-align:center;font-weight:700">Vista previa: este artículo <?= $post['status'] === 'publicado' ? 'está programado y aún no es público' : 'es un borrador y no es público' ?>. <a href="/admin/articulo.php?id=<?= (int) $post['id'] ?>">Volver a editar</a></div>
<?php endif; ?>
<section class="page-hero">
  <img class="page-hero-mark" src="/assets/img/marca.svg" alt="" width="1057" height="398" aria-hidden="true">
  <div class="wrap">
    <nav aria-label="Migas de pan"><ol class="crumbs">
      <li><a href="/">Inicio</a></li>
      <li><a href="/blog/">Blog</a></li>
      <li><a href="/blog/categoria/<?= e($cat) ?>/"><?= e(category_name($cat)) ?></a></li>
    </ol></nav>
    <h1><?= e($post['title']) ?></h1>
    <p style="font-size:15px;color:#BDBDBD"><time datetime="<?= e(date('Y-m-d', strtotime((string) $post['published_at']))) ?>"><?= e(fecha_es((string) $post['published_at'])) ?></time> · <?= reading_minutes((string) $post['content']) ?> min de lectura</p>
  </div>
</section>
<?php if ($cover): ?>
<div class="wrap article-cover">
  <img src="<?= e($cover['large']) ?>" srcset="<?= e($cover['medium']) ?> 800w, <?= e($cover['large']) ?> 1600w" sizes="(max-width: 1080px) 100vw, 1080px" alt="<?= e($post['cover_alt'] ?: $post['title']) ?>" width="1600" height="900" fetchpriority="high">
</div>
<?php endif; ?>
<article class="section" style="padding-top:56px">
  <div class="wrap">
    <div class="article">
      <div class="article-body">
<?= $post['content'] /* HTML ya depurado al guardarse en el panel */ ?>
      </div>
      <div class="share">
        <span>Compartir:</span>
        <a href="https://wa.me/?text=<?= e(rawurlencode($post['title'] . ' ' . $shareUrl)) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 18) ?> WhatsApp</a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= e(rawurlencode($shareUrl)) ?>" target="_blank" rel="noopener"><?= icon('facebook', 18) ?> Facebook</a>
        <button type="button" data-copy="<?= e($shareUrl) ?>"><?= icon('link', 18) ?> <span>Copiar enlace</span></button>
      </div>
      <div class="side-cta" style="margin-top:40px">
        <h2>¿Necesitas verificar tus unidades?</h2>
        <p>En MOCHCUN hacemos la inspección físico-mecánica y la verificación de emisiones, y te ayudamos con tus trámites federales.</p>
        <div class="cta-btns">
          <a class="btn btn-red" href="<?= e(whatsapp_url('Hola, leí el artículo "' . $post['title'] . '" y quiero información')) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> Escríbenos por WhatsApp</a>
          <a class="btn btn-outline-light" href="/tarifas-requisitos/">Tarifas y requisitos</a>
        </div>
      </div>
    </div>
  </div>
</article>
<?php if ($related): ?>
<section class="section paper">
  <div class="wrap">
    <div class="sec-head"><div><span class="eyebrow">Sigue leyendo</span><h2>Artículos relacionados</h2></div><a class="link-arrow" href="/blog/">Ver todos <?= icon('arrow', 18) ?></a></div>
    <div class="grid-3">
<?php foreach ($related as $r) echo post_card($r), "\n"; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php page_end($page); ?>
