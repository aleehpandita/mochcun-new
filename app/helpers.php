<?php
declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    return $GLOBALS['config'][$key] ?? $default;
}

/** Escapa texto para HTML. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** URL absoluta canónica a partir de una ruta ("/contacto/"). */
function url(string $path = '/'): string
{
    return rtrim((string) config('site_url'), '/') . '/' . ltrim($path, '/');
}

/** Ruta a un archivo de /assets con versión por fecha de modificación (caché larga segura). */
function asset(string $path): string
{
    $file = ROOT . '/assets/' . ltrim($path, '/');
    $v = is_file($file) ? (string) filemtime($file) : '1';
    return '/assets/' . ltrim($path, '/') . '?v=' . $v;
}

function whatsapp_url(?string $text = null): string
{
    return 'https://wa.me/' . config('whatsapp_number') . '?text=' . rawurlencode($text ?? (string) config('whatsapp_text'));
}

function tel_url(): string
{
    return 'tel:' . config('phone_e164');
}

function maps_url(): string
{
    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode((string) config('maps_query'));
}

function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return $path;
}

/**
 * Imagen responsiva del sitio (assets/img). Genera <picture> con WebP en varios anchos,
 * JPG de respaldo y width/height para evitar saltos de diseño.
 */
function img(string $name, string $alt, string $sizes = '100vw', array $attrs = []): string
{
    static $manifest = null;
    $manifest ??= require __DIR__ . '/images.php';
    $m = $manifest[$name] ?? null;
    if (!$m) {
        return '';
    }
    $widths = $m['w'];
    $srcset = implode(', ', array_map(fn ($w) => "/assets/img/{$name}-{$w}.webp {$w}w", $widths));
    $baseW = $widths[min(1, count($widths) - 1)];
    $baseH = (int) round($baseW * $m['r']);
    $lazy = $attrs['loading'] ?? 'lazy';
    unset($attrs['loading']);
    $extra = '';
    foreach ($attrs as $k => $v) {
        $extra .= ' ' . e($k) . '="' . e((string) $v) . '"';
    }
    $priority = $lazy === 'eager' ? ' fetchpriority="high"' : ' decoding="async"';
    return '<picture><source type="image/webp" srcset="' . e($srcset) . '" sizes="' . e($sizes) . '">'
        . '<img src="/assets/img/' . e($name) . '.jpg" alt="' . e($alt) . '" width="' . $baseW . '" height="' . $baseH . '" loading="' . e($lazy) . '"' . $priority . $extra . '></picture>';
}

/** Ícono SVG en línea (trazo), sin dependencias. */
function icon(string $name, int $size = 20, string $class = 'ico'): string
{
    $paths = [
        'whatsapp' => '<path d="M21 11.5a8.4 8.4 0 0 1-12.5 7.4L3 21l2.1-5.3A8.4 8.4 0 1 1 21 11.5z"/>',
        'phone'    => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 7l9 6 9-6"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'pin'      => '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'check'    => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close'    => '<path d="M6 6l12 12M18 6L6 18"/>',
        'chevron'  => '<path d="M6 9l6 6 6-6"/>',
        'file'     => '<path d="M14 3H6v18h12V7z"/><path d="M14 3v4h4M9 13h6M9 17h6"/>',
        'facebook' => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/>',
        'link'     => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
    ];
    $p = $paths[$name] ?? '';
    return '<svg class="' . e($class) . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p . '</svg>';
}

function slugify(string $text): string
{
    $text = mb_strtolower(trim($text));
    $map = ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n', 'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u'];
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');
    return substr($text, 0, 90) ?: 'articulo';
}

function fecha_es(string $date, bool $long = true): string
{
    $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $ts = strtotime($date) ?: time();
    $m = $meses[(int) date('n', $ts) - 1];
    return $long ? date('j', $ts) . ' de ' . $m . ' de ' . date('Y', $ts) : date('j', $ts) . ' ' . mb_substr($m, 0, 3) . ' ' . date('Y', $ts);
}

function reading_minutes(string $html): int
{
    $words = str_word_count(strip_tags($html), 0, 'áéíóúüñÁÉÍÓÚÜÑ');
    return max(1, (int) round($words / 200));
}

function plain_excerpt(string $html, int $len = 160): string
{
    $t = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8')) ?? '');
    if (mb_strlen($t) <= $len) {
        return $t;
    }
    $cut = mb_substr($t, 0, $len);
    $sp = mb_strrpos($cut, ' ');
    return rtrim(mb_substr($cut, 0, $sp ?: $len), ' ,.;:') . '…';
}

function redirect(string $to, int $code = 302): never
{
    header('Location: ' . $to, true, $code);
    exit;
}

function not_found(): never
{
    http_response_code(404);
    require ROOT . '/404.php';
    exit;
}

function category_name(string $slug): string
{
    return config('blog_categories')[$slug] ?? ucfirst(str_replace('-', ' ', $slug));
}
