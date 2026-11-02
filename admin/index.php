<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/_layout.php';

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');

$hasUsers = (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0;
$error = null;

// 1) Primera vez: crear la cuenta de administrador
if (!$hasUsers) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $pass = (string) ($_POST['password'] ?? '');
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Escribe tu nombre y un correo válido.';
        } elseif (mb_strlen($pass) < 10) {
            $error = 'La contraseña debe tener al menos 10 caracteres.';
        } elseif ($pass !== (string) ($_POST['password2'] ?? '')) {
            $error = 'Las contraseñas no coinciden.';
        } else {
            db()->prepare('INSERT INTO users (name, email, password_hash, created_at) VALUES (?,?,?,?)')
                ->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT), now()]);
            attempt_login($email, $pass);
            flash('Cuenta creada. Ya puedes escribir tu primer artículo.');
            redirect('/admin/');
        }
    }
    admin_head('Configuración inicial');
    ?>
  <div class="a-auth">
    <img src="/assets/img/logo-mochcun.svg" alt="MOCHCUN" width="190" height="72">
    <h1>Crea la cuenta de administrador</h1>
    <p class="a-muted">Esto solo aparece la primera vez. Después podrás agregar más usuarios.</p>
    <?php if ($error): ?><p class="a-alert a-alert-err"><?= e($error) ?></p><?php endif; ?>
    <form method="post" class="a-form">
      <?= csrf_field() ?>
      <label>Nombre<input name="name" required value="<?= e($_POST['name'] ?? '') ?>"></label>
      <label>Correo<input name="email" type="email" required autocomplete="username" value="<?= e($_POST['email'] ?? '') ?>"></label>
      <label>Contraseña (mínimo 10 caracteres)<input name="password" type="password" required minlength="10" autocomplete="new-password"></label>
      <label>Repite la contraseña<input name="password2" type="password" required minlength="10" autocomplete="new-password"></label>
      <button class="a-btn a-btn-red" type="submit">Crear cuenta</button>
    </form>
  </div>
<?php
    admin_foot();
    exit;
}

// 2) Inicio de sesión
$user = admin_user();
if (!$user) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        if (login_blocked((string) ($_SERVER['REMOTE_ADDR'] ?? ''))) {
            $error = 'Demasiados intentos. Espera 15 minutos.';
        } elseif (attempt_login((string) ($_POST['email'] ?? ''), (string) ($_POST['password'] ?? ''))) {
            redirect('/admin/');
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
    admin_head('Entrar');
    ?>
  <div class="a-auth">
    <img src="/assets/img/logo-mochcun.svg" alt="MOCHCUN" width="190" height="72">
    <h1>Panel del blog</h1>
    <?php if ($error): ?><p class="a-alert a-alert-err"><?= e($error) ?></p><?php endif; ?>
    <form method="post" class="a-form">
      <?= csrf_field() ?>
      <label>Correo<input name="email" type="email" required autocomplete="username" value="<?= e($_POST['email'] ?? '') ?>"></label>
      <label>Contraseña<input name="password" type="password" required autocomplete="current-password"></label>
      <button class="a-btn a-btn-red" type="submit">Entrar</button>
    </form>
  </div>
<?php
    admin_foot();
    exit;
}

// 3) Listado de artículos
$q = trim((string) ($_GET['q'] ?? ''));
$sql = 'SELECT id, slug, title, category, status, published_at, updated_at, cover FROM posts';
$params = [];
if ($q !== '') {
    $sql .= ' WHERE title LIKE ?';
    $params[] = '%' . $q . '%';
}
$sql .= ' ORDER BY COALESCE(published_at, created_at) DESC';
$st = db()->prepare($sql);
$st->execute($params);
$posts = $st->fetchAll();
$counts = db()->query("SELECT
    SUM(status = 'publicado' AND published_at <= '" . now() . "') AS live,
    SUM(status = 'publicado' AND published_at > '" . now() . "') AS scheduled,
    SUM(status != 'publicado') AS drafts FROM posts")->fetch();

admin_head('Artículos', $user);
?>
  <div class="a-head">
    <div>
      <h1>Artículos del blog</h1>
      <p class="a-muted"><?= (int) $counts['live'] ?> publicados · <?= (int) $counts['scheduled'] ?> programados · <?= (int) $counts['drafts'] ?> borradores</p>
    </div>
    <a class="a-btn a-btn-red" href="/admin/articulo.php">+ Nuevo artículo</a>
  </div>
  <form class="a-search" method="get"><input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar por título" aria-label="Buscar por título"><button class="a-btn" type="submit">Buscar</button></form>
<?php if (!$posts): ?>
  <div class="a-empty">
    <p><strong><?= $q ? 'No hay artículos con ese título.' : 'Todavía no hay artículos.' ?></strong></p>
    <?php if (!$q): ?><p class="a-muted">Escribe el primero: aparecerá en /blog/ y en la página de inicio.</p><a class="a-btn a-btn-red" href="/admin/articulo.php">Escribir artículo</a><?php endif; ?>
  </div>
<?php else: ?>
  <div class="a-table-wrap">
  <table class="a-table">
    <thead><tr><th>Artículo</th><th>Categoría</th><th>Estado</th><th>Fecha</th><th><span class="sr">Acciones</span></th></tr></thead>
    <tbody>
<?php foreach ($posts as $p): [$label, $cls] = status_label($p); $c = cover_urls((string) $p['cover']); ?>
      <tr>
        <td>
          <a class="a-post" href="/admin/articulo.php?id=<?= (int) $p['id'] ?>">
            <?php if ($c): ?><img src="<?= e($c['medium']) ?>" alt="" width="72" height="40" loading="lazy"><?php else: ?><span class="a-noimg"></span><?php endif; ?>
            <span><?= e($p['title']) ?><small>/blog/<?= e($p['slug']) ?>/</small></span>
          </a>
        </td>
        <td><?= e(category_name($p['category'])) ?></td>
        <td><span class="a-status a-status-<?= $cls ?>"><?= $label ?></span></td>
        <td><?= $p['published_at'] ? e(fecha_es($p['published_at'], false)) : '—' ?></td>
        <td class="a-actions"><a href="/admin/articulo.php?id=<?= (int) $p['id'] ?>">Editar</a> <a href="/blog/<?= e($p['slug']) ?>/" target="_blank" rel="noopener">Ver</a></td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  </div>
<?php endif; ?>
<?php admin_foot(); ?>
