<?php
/**
 * Router SOLO para desarrollo local (imita las reglas de .htaccess):
 *   php -S localhost:8000 tools/dev-router.php
 */
$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

if (preg_match('#^/(app|storage|tools)(/|$)#', $path)) {
    http_response_code(403);
    exit('Prohibido');
}
if ($path !== '/' && is_file($root . $path) && !str_ends_with($path, '.php')) {
    return false; // archivo estático
}
$map = [
    '#^/sitemap\.xml$#' => '/sitemap.php',
    '#^/blog/feed/?$#' => '/blog/feed.php',
    '#^/blog/categoria/([a-z0-9-]+)/?$#' => '/blog/index.php?categoria=$1',
    '#^/blog/([a-z0-9-]+)/?$#' => '/blog/articulo.php?slug=$1',
];
foreach ($map as $re => $target) {
    if (preg_match($re, $path)) {
        $t = preg_replace($re, $target, $path);
        [$file, $qs] = array_pad(explode('?', $t, 2), 2, '');
        parse_str($qs, $q);
        $_GET = array_merge($_GET, $q);
        chdir(dirname($root . $file));
        require $root . $file;
        return true;
    }
}
if (is_dir($root . $path) && is_file($root . rtrim($path, '/') . '/index.php')) {
    require $root . rtrim($path, '/') . '/index.php';
    return true;
}
if (str_ends_with($path, '.php') && is_file($root . $path)) {
    require $root . $path;
    return true;
}
$slug = trim($path, '/');
if (preg_match('#^[a-z0-9-]+$#', $slug) && is_file("$root/$slug.php")) {
    require "$root/$slug.php";
    return true;
}
http_response_code(404);
require "$root/404.php";
