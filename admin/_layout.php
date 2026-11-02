<?php
declare(strict_types=1);

function admin_head(string $title, ?array $user = null, string $extraHead = ''): void
{
    $cur = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $unread = 0;
    if ($user) {
        try {
            $unread = (int) db()->query('SELECT COUNT(*) FROM messages WHERE read_at IS NULL')->fetchColumn();
        } catch (Throwable $e) {
        }
    }
    ?>
<!doctype html>
<html lang="es-MX">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> · Panel MOCHCUN</title>
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<?= $extraHead ?>
<link rel="stylesheet" href="<?= e(asset_admin('admin.css')) ?>">
</head>
<body>
<?php if ($user): ?>
<header class="a-top">
  <a class="a-brand" href="/admin/"><img src="/assets/img/logo-mochcun-blanco.svg" alt="MOCHCUN" width="120" height="45"><span>Panel</span></a>
  <nav class="a-nav" aria-label="Panel">
    <a href="/admin/"<?= $cur === 'index.php' ? ' aria-current="page"' : '' ?>>Artículos</a>
    <a href="/admin/articulo.php"<?= $cur === 'articulo.php' && empty($_GET['id']) ? ' aria-current="page"' : '' ?>>Nuevo artículo</a>
    <a href="/admin/mensajes.php"<?= $cur === 'mensajes.php' ? ' aria-current="page"' : '' ?>>Mensajes<?= $unread ? ' <b class="a-badge">' . $unread . '</b>' : '' ?></a>
    <a href="/admin/usuarios.php"<?= $cur === 'usuarios.php' ? ' aria-current="page"' : '' ?>>Usuarios</a>
  </nav>
  <div class="a-user">
    <a href="/" target="_blank" rel="noopener">Ver sitio</a>
    <span><?= e($user['name']) ?></span>
    <form method="post" action="/admin/salir.php"><?= csrf_field() ?><button type="submit">Salir</button></form>
  </div>
</header>
<?php endif; ?>
<main class="a-main">
<?php if ($f = flash()): ?>
  <p class="a-alert a-alert-<?= e($f[0]) ?>" role="status"><?= e($f[1]) ?></p>
<?php endif;
}

function admin_foot(string $scripts = ''): void
{
    ?>
</main>
<?= $scripts ?>
</body>
</html>
<?php
}

function asset_admin(string $file): string
{
    $path = ROOT . '/admin/assets/' . $file;
    return '/admin/assets/' . $file . '?v=' . (is_file($path) ? filemtime($path) : '1');
}

function status_label(array $p): array
{
    if ($p['status'] !== 'publicado') {
        return ['Borrador', 'draft'];
    }
    if ($p['published_at'] > now()) {
        return ['Programado', 'scheduled'];
    }
    return ['Publicado', 'live'];
}
