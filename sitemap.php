<?php
require __DIR__ . '/app/bootstrap.php';

header('Content-Type: application/xml; charset=UTF-8');
$pages = [
    'index.php' => '/',
    'inspeccion-fisico-mecanica-cancun.php' => '/inspeccion-fisico-mecanica-cancun/',
    'verificacion-emisiones-cancun.php' => '/verificacion-emisiones-cancun/',
    'gestoria-licencia-federal-cancun.php' => '/gestoria-licencia-federal-cancun/',
    'gestoria-placas-federales-turismo-cancun.php' => '/gestoria-placas-federales-turismo-cancun/',
    'tarifas-requisitos.php' => '/tarifas-requisitos/',
    'calendario-verificaciones-cancun.php' => '/calendario-verificaciones-cancun/',
    'medicina-preventiva-cancun.php' => '/medicina-preventiva-cancun/',
    'centro-capacitacion.php' => '/centro-capacitacion/',
    'verificadora-cancun.php' => '/verificadora-cancun/',
    'faqs.php' => '/faqs/',
    'normas-sct-verificacion.php' => '/normas-sct-verificacion/',
    'politicas.php' => '/politicas/',
    'contacto.php' => '/contacto/',
];
$urls = [];
foreach ($pages as $file => $path) {
    $urls[] = [url($path), date('Y-m-d', (int) filemtime(__DIR__ . '/' . $file))];
}
try {
    $posts = db()->query("SELECT slug, category, updated_at FROM posts WHERE status = 'publicado' AND published_at <= '" . now() . "' ORDER BY published_at DESC")->fetchAll();
} catch (Throwable $e) {
    $posts = [];
}
$latest = $posts ? max(array_map(fn ($p) => $p['updated_at'], $posts)) : date('Y-m-d', (int) filemtime(__DIR__ . '/blog/index.php'));
$urls[] = [url('/blog/'), date('Y-m-d', strtotime($latest))];
$cats = [];
foreach ($posts as $p) {
    $urls[] = [url('/blog/' . $p['slug'] . '/'), date('Y-m-d', strtotime($p['updated_at']))];
    $cats[$p['category']] = true;
}
foreach (array_keys($cats) as $c) {
    $urls[] = [url('/blog/categoria/' . $c . '/'), date('Y-m-d', strtotime($latest))];
}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$loc, $mod]) {
    echo '  <url><loc>' . e($loc) . '</loc><lastmod>' . $mod . "</lastmod></url>\n";
}
echo "</urlset>\n";
