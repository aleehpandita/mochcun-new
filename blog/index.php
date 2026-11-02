<?php
require dirname(__DIR__) . '/app/bootstrap.php';

$cats = config('blog_categories');
$cat = isset($_GET['categoria']) ? (string) $_GET['categoria'] : null;
if ($cat !== null && !isset($cats[$cat])) {
    not_found();
}
$per = (int) config('posts_per_page');
$pageNum = max(1, (int) ($_GET['pagina'] ?? 1));
try {
    $total = count_published($cat);
    $posts = published_posts($per, ($pageNum - 1) * $per, $cat);
} catch (Throwable $e) {
    error_log('[mochcun] blog: ' . $e->getMessage());
    $total = 0;
    $posts = [];
}
$pages = max(1, (int) ceil($total / $per));
if ($pageNum > $pages) {
    not_found();
}
$base = $cat ? '/blog/categoria/' . $cat . '/' : '/blog/';
$path = $base . ($pageNum > 1 ? '?pagina=' . $pageNum : '');
$h1 = $cat ? $cats[$cat] : 'Blog: guías para el transportista';
$page = [
    'title' => ($cat ? $cats[$cat] . ' - Blog MOCHCUN' : 'Blog MOCHCUN - Guías de verificación y trámites federales en Cancún') . ($pageNum > 1 ? ' (página ' . $pageNum . ')' : ''),
    'description' => $cat
        ? 'Artículos de MOCHCUN sobre ' . mb_strtolower($cats[$cat]) . ': requisitos, fechas y consejos para el autotransporte federal en Cancún.'
        : 'Guías prácticas sobre verificación físico-mecánica, emisiones contaminantes, licencia federal, placas y trámites ante la SICT para transportistas de Cancún y la Riviera Maya.',
    'path' => $path,
    'breadcrumb' => $cat ? [['Blog', '/blog/'], [$cats[$cat], $base]] : [['Blog', '/blog/']],
    'schema_page_type' => 'CollectionPage',
    'prev' => $pageNum > 1 ? $base . ($pageNum > 2 ? '?pagina=' . ($pageNum - 1) : '') : null,
    'next' => $pageNum < $pages ? $base . '?pagina=' . ($pageNum + 1) : null,
];
page_start($page);
page_hero($h1, $cat ? 'Artículos de la categoría ' . $cats[$cat] . '.' : 'Requisitos, fechas, cambios en las normas y consejos para que tus unidades estén siempre en regla.', $page['breadcrumb']);
?>
<section class="section">
  <div class="wrap">
    <nav class="chips" aria-label="Categorías">
      <a class="chip<?= $cat ? '' : ' is-on' ?>" href="/blog/">Todos</a>
<?php foreach ($cats as $slug => $name): ?>
      <a class="chip<?= $cat === $slug ? ' is-on' : '' ?>" href="/blog/categoria/<?= e($slug) ?>/"><?= e($name) ?></a>
<?php endforeach; ?>
    </nav>
<?php if (!$posts): ?>
    <div class="empty">Muy pronto publicaremos aquí nuestros primeros artículos.</div>
<?php else: ?>
    <div class="grid-3">
<?php foreach ($posts as $i => $post) echo post_card($post, $i < 3), "\n"; ?>
    </div>
<?php if ($pages > 1): ?>
    <nav class="pager" aria-label="Paginación">
<?php for ($i = 1; $i <= $pages; $i++):
        $href = $base . ($i > 1 ? '?pagina=' . $i : ''); ?>
      <?php if ($i === $pageNum): ?><span class="is-on" aria-current="page"><?= $i ?></span><?php else: ?><a href="<?= e($href) ?>"><?= $i ?></a><?php endif; ?>
<?php endfor; ?>
    </nav>
<?php endif; ?>
<?php endif; ?>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
