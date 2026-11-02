<?php
declare(strict_types=1);

/** Menú principal. Las rutas son las mismas del sitio anterior. */
function nav_items(): array
{
    return [
        ['Servicios', null, [
            ['Inspección físico-mecánica', '/inspeccion-fisico-mecanica-cancun/'],
            ['Verificación de emisiones', '/verificacion-emisiones-cancun/'],
            ['Medicina preventiva', '/medicina-preventiva-cancun/'],
            ['Centro de capacitación', '/centro-capacitacion/'],
        ]],
        ['Gestoría', null, [
            ['Licencia federal', '/gestoria-licencia-federal-cancun/'],
            ['Placas federales de turismo', '/gestoria-placas-federales-turismo-cancun/'],
        ]],
        ['Tarifas y requisitos', '/tarifas-requisitos/'],
        ['Calendario', '/calendario-verificaciones-cancun/'],
        ['Blog', '/blog/'],
        ['Nosotros', '/verificadora-cancun/'],
        ['Contacto', '/contacto/'],
    ];
}

function is_current(string $path): bool
{
    $cur = rtrim(current_path(), '/') . '/';
    if ($path === '/blog/') {
        return str_starts_with($cur, '/blog/');
    }
    return $cur === $path;
}

function page_start(array $p): void
{
    $css = (string) @file_get_contents(ROOT . '/assets/css/site.css');
    $gtm = (string) config('gtm_id');
    $ga = (string) config('ga4_id');
    header('Content-Type: text/html; charset=UTF-8');
    ?>
<!doctype html>
<html lang="es-MX">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php seo_head($p); ?>
<link rel="preload" href="/assets/fonts/archivo-var.woff2" as="font" type="font/woff2" crossorigin>
<?php if (!empty($p['preload_image'])) echo $p['preload_image'] . "\n"; ?>
<link rel="icon" href="/favicon.ico" sizes="48x48">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#111111">
<link rel="alternate" type="application/rss+xml" title="Blog MOCHCUN" href="<?= e(url('/blog/feed/')) ?>">
<style><?= $css ?></style>
<?php if ($gtm !== '' || $ga !== ''): ?>
<script>
window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}
(function(){var done=false;function load(){if(done)return;done=true;
<?php if ($gtm !== ''): ?>dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'});var s=document.createElement('script');s.async=true;s.src='https://www.googletagmanager.com/gtm.js?id=<?= e($gtm) ?>';document.head.appendChild(s);<?php endif; ?>
<?php if ($ga !== ''): ?>var g=document.createElement('script');g.async=true;g.src='https://www.googletagmanager.com/gtag/js?id=<?= e($ga) ?>';document.head.appendChild(g);gtag('js',new Date());gtag('config','<?= e($ga) ?>');<?php endif; ?>
}
['scroll','pointerdown','keydown','touchstart'].forEach(function(t){addEventListener(t,load,{once:true,passive:true});});
addEventListener('load',function(){setTimeout(load,2500);});})();
</script>
<?php endif; ?>
</head>
<body>
<a class="skip" href="#contenido">Saltar al contenido</a>
<div class="topbar">
  <div class="wrap">
    <span class="topbar-l">Centro de Verificación Vehicular en Cancún y Riviera Maya</span>
    <span class="topbar-r">
      <a href="mailto:<?= e(config('email')) ?>"><?= e(config('email')) ?></a>
      <span class="hide-sm">Lun – Vie: 9:00 am a 5:00 pm</span>
    </span>
  </div>
</div>
<header class="site-header">
  <div class="wrap header-in">
    <a class="brand" href="/" aria-label="MOCHCUN, ir al inicio"><img src="/assets/img/logo-mochcun.svg" width="222" height="84" alt="Servicios de Autotransporte MOCHCUN"></a>
    <nav class="nav" id="menu" aria-label="Principal">
      <button type="button" class="menu-toggle nav-close" data-menu-close aria-label="Cerrar menú"><?= icon('close', 24) ?></button>
      <ul class="nav-list">
<?php foreach (nav_items() as $i => $item):
        [$label, $href] = $item;
        $children = $item[2] ?? null;
        if ($children):
            $cur = (bool) array_filter($children, fn ($c) => is_current($c[1])); ?>
        <li class="has-sub">
          <button type="button" class="nav-btn<?= $cur ? ' is-current' : '' ?>" aria-expanded="false" aria-controls="sub-<?= $i ?>"><?= e($label) ?> <?= icon('chevron', 16) ?></button>
          <ul class="sub" id="sub-<?= $i ?>">
<?php foreach ($children as [$cl, $ch]): ?>
            <li><a href="<?= e($ch) ?>"<?= is_current($ch) ? ' aria-current="page"' : '' ?>><?= e($cl) ?></a></li>
<?php endforeach; ?>
          </ul>
        </li>
<?php else: ?>
        <li><a class="nav-link" href="<?= e($href) ?>"<?= is_current($href) ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
<?php endif; endforeach; ?>
      </ul>
      <div class="nav-mobile-extra">
        <a class="btn btn-red" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> Escríbenos por WhatsApp</a>
        <a class="btn btn-outline" href="<?= e(tel_url()) ?>"><?= icon('phone') ?> Llamar al <?= e(config('phone_display')) ?></a>
      </div>
    </nav>
    <div class="header-actions">
      <a class="header-phone" href="<?= e(tel_url()) ?>"><small>Llámanos</small><strong><?= e(config('phone_display')) ?></strong></a>
      <a class="btn btn-red btn-sm" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 18) ?> WhatsApp</a>
      <button type="button" class="menu-toggle" data-menu-open aria-expanded="false" aria-controls="menu" aria-label="Abrir menú"><?= icon('menu', 24) ?></button>
    </div>
  </div>
</header>
<main id="contenido">
<?php
}

function page_end(array $p = []): void
{
    $year = date('Y');
    ?>
</main>
<footer class="footer">
  <div class="wrap footer-grid">
    <div class="footer-brand">
      <img src="/assets/img/logo-mochcun-blanco.svg" width="222" height="84" alt="Servicios de Autotransporte MOCHCUN" loading="lazy">
      <p>Unidad de verificación tipo “C” en operación desde octubre de 2008, acreditada por la Entidad Mexicana de Acreditación (EMA) y aprobada por la SICT.</p>
    </div>
    <div>
      <h2>SERVICIOS</h2>
      <ul>
        <li><a href="/inspeccion-fisico-mecanica-cancun/">Inspección físico-mecánica</a></li>
        <li><a href="/verificacion-emisiones-cancun/">Verificación de emisiones</a></li>
        <li><a href="/medicina-preventiva-cancun/">Medicina preventiva</a></li>
        <li><a href="/centro-capacitacion/">Centro de capacitación</a></li>
      </ul>
    </div>
    <div>
      <h2>GESTORÍA E INFORMACIÓN</h2>
      <ul>
        <li><a href="/gestoria-licencia-federal-cancun/">Licencia federal</a></li>
        <li><a href="/gestoria-placas-federales-turismo-cancun/">Placas federales de turismo</a></li>
        <li><a href="/tarifas-requisitos/">Tarifas y requisitos</a></li>
        <li><a href="/calendario-verificaciones-cancun/">Calendario de verificación</a></li>
        <li><a href="/normas-sct-verificacion/">Normas SCT</a></li>
      </ul>
    </div>
    <div>
      <h2>MOCHCUN</h2>
      <ul>
        <li><a href="/verificadora-cancun/">Nosotros</a></li>
        <li><a href="/politicas/">Políticas de calidad</a></li>
        <li><a href="/faqs/">Preguntas frecuentes</a></li>
        <li><a href="/blog/">Blog</a></li>
        <li><a href="/contacto/">Contacto</a></li>
        <li><a href="<?= e(config('facebook')) ?>" target="_blank" rel="noopener">Facebook</a></li>
      </ul>
    </div>
  </div>
  <div class="wrap footer-bottom">
    <span>© <?= $year ?> <?= e(config('legal_name')) ?></span>
    <span><?= e(config('address')['city']) ?>, <?= e(config('address')['region']) ?></span>
  </div>
</footer>
<a class="wa-float" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp"><?= icon('whatsapp', 28) ?></a>
<script src="<?= e(asset('js/site.js')) ?>" defer></script>
<?php if (!empty($p['scripts'])) echo $p['scripts']; ?>
</body>
</html>
<?php
}

/** Encabezado de página interna con migas de pan y H1. */
function page_hero(string $h1, string $intro, array $crumbs): void
{
    ?>
<section class="page-hero">
  <img class="page-hero-mark" src="/assets/img/marca.svg" alt="" width="1057" height="398" aria-hidden="true">
  <div class="wrap">
    <nav aria-label="Migas de pan"><ol class="crumbs">
      <li><a href="/">Inicio</a></li>
<?php foreach ($crumbs as $i => [$name, $path]): ?>
      <li><?php if ($i === count($crumbs) - 1): ?><span aria-current="page"><?= e($name) ?></span><?php else: ?><a href="<?= e($path) ?>"><?= e($name) ?></a><?php endif; ?></li>
<?php endforeach; ?>
    </ol></nav>
    <h1><?= e($h1) ?></h1>
<?php if ($intro !== ''): ?>
    <p><?= e($intro) ?></p>
<?php endif; ?>
  </div>
</section>
<?php
}

/** Barra lateral de páginas de servicio. */
function service_sidebar(): void
{
    $links = [
        ['Inspección físico-mecánica', '/inspeccion-fisico-mecanica-cancun/'],
        ['Verificación de emisiones', '/verificacion-emisiones-cancun/'],
        ['Medicina preventiva', '/medicina-preventiva-cancun/'],
        ['Licencia federal', '/gestoria-licencia-federal-cancun/'],
        ['Placas federales de turismo', '/gestoria-placas-federales-turismo-cancun/'],
        ['Centro de capacitación', '/centro-capacitacion/'],
        ['Tarifas y requisitos', '/tarifas-requisitos/'],
    ];
    ?>
<aside class="side-col" aria-label="Más servicios">
  <div class="side-box">
    <h2>Servicios</h2>
    <ul class="side-nav">
<?php foreach ($links as [$l, $h]): ?>
      <li><a href="<?= e($h) ?>"<?= is_current($h) ? ' aria-current="page"' : '' ?>><?= e($l) ?> <?= icon('arrow', 16, 'ico ico-arrow') ?></a></li>
<?php endforeach; ?>
    </ul>
  </div>
  <div class="side-cta">
    <h2>¿Dudas con tu trámite?</h2>
    <p>Te decimos qué documentos necesitas y cuándo puedes venir.</p>
    <a class="btn btn-red" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> WhatsApp</a>
    <a class="btn btn-outline-light" href="<?= e(tel_url()) ?>"><?= icon('phone') ?> <?= e(config('phone_display')) ?></a>
  </div>
</aside>
<?php
}

/** Banda roja de contacto que cierra la mayoría de las páginas. */
function cta_band(string $title = '¿Tienes unidades por verificar?', string $text = 'Escríbenos y te decimos qué necesitas, cuánto cuesta y cuándo puedes venir.'): void
{
    $a = config('address');
    ?>
<section class="cta-band" id="contacto-rapido">
  <div class="wrap cta-in">
    <div class="cta-text">
      <h2><?= e($title) ?></h2>
      <p><?= e($text) ?></p>
      <div class="cta-btns">
        <a class="btn btn-dark" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> Escríbenos por WhatsApp</a>
        <a class="btn btn-white" href="<?= e(tel_url()) ?>"><?= icon('phone') ?> Llamar al <?= e(config('phone_display')) ?></a>
      </div>
    </div>
    <div class="cta-card">
      <div class="info-row"><?= icon('pin', 22) ?><div><strong>Dirección</strong><span><?= e($a['street']) ?>, C.P. <?= e($a['postal']) ?>, <?= e($a['city']) ?>, Q. Roo</span><a href="<?= e(maps_url()) ?>" target="_blank" rel="noopener">Cómo llegar</a></div></div>
      <div class="info-row"><?= icon('clock', 22) ?><div><strong>Horario</strong><span><?= e(config('hours_display')) ?></span></div></div>
      <div class="info-row"><?= icon('mail', 22) ?><div><strong>Correo</strong><a href="mailto:<?= e(config('email')) ?>"><?= e(config('email')) ?></a></div></div>
      <div class="info-row"><?= icon('whatsapp', 22) ?><div><strong>WhatsApp</strong><a href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener"><?= e(config('whatsapp_display')) ?></a></div></div>
    </div>
  </div>
</section>
<?php
}

/** Pregunta frecuente con <details> (abre y cierra sin JavaScript). */
function faq_item(string $q, string $answerHtml, bool $open = false): string
{
    return '<details class="faq"' . ($open ? ' open' : '') . '><summary>' . e($q) . '</summary><div class="faq-a">' . $answerHtml . '</div></details>';
}

/** Tabla del calendario físico-mecánico (se usa en inicio, calendario y FAQ). */
function calendar_rows(): array
{
    return [
        ['5,6', '5 · 6', 'Enero, febrero, marzo y abril'],
        ['7,8', '7 · 8', 'Marzo, abril, mayo y junio'],
        ['3,4', '3 · 4', 'Mayo, junio, julio y agosto'],
        ['1,2', '1 · 2', 'Julio, agosto, septiembre y octubre'],
        ['9,0', '9 · 0', 'Septiembre, octubre, noviembre y diciembre'],
    ];
}

function calendar_table(): void
{
    ?>
<div class="cal-table">
  <div class="cal-head"><strong>Físico-mecánica · periodo anual</strong><span>POR DÍGITO DE PLACA</span></div>
<?php foreach (calendar_rows() as [$digits, $label, $months]): ?>
  <div class="cal-row" data-digits="<?= e($digits) ?>" data-months="<?= e(mb_strtolower($months)) ?>"><b><?= e($label) ?></b><span><?= e($months) ?></span></div>
<?php endforeach; ?>
  <div class="cal-row"><b style="font-size:14px">DOLLY</b><span>Sin placas: de enero a diciembre</span></div>
</div>
<div class="periods">
  <div class="period"><span class="tag">Emisiones · 1er periodo</span><strong>1 de enero al 30 de junio</strong></div>
  <div class="period"><span class="tag">Emisiones · 2º periodo</span><strong>1 de julio al 31 de diciembre</strong></div>
</div>
<?php
}

/** Tarjeta de artículo del blog. */
function post_card(array $post, bool $eager = false): string
{
    $c = cover_urls((string) $post['cover']);
    $img = $c
        ? '<div class="card-img"><img src="' . e($c['medium']) . '" alt="' . e($post['cover_alt'] ?: $post['title']) . '" width="800" height="450" loading="' . ($eager ? 'eager' : 'lazy') . '" decoding="async"></div>'
        : '<div class="card-img" style="display:flex;align-items:center;justify-content:center;background:var(--ink)"><img src="/assets/img/marca.svg" alt="" width="1057" height="398" style="width:55%;height:auto;object-fit:contain"></div>';
    return '<a class="card post-card" href="/blog/' . e($post['slug']) . '/">' . $img
        . '<div class="card-body"><span class="tag">' . e(category_name($post['category'])) . '</span>'
        . '<h3>' . e($post['title']) . '</h3>'
        . '<span class="post-meta">' . e(fecha_es((string) $post['published_at'])) . ' · ' . reading_minutes((string) $post['content']) . ' min de lectura</span></div></a>';
}
