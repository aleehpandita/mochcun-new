<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/_layout.php';

$user = require_login();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $accion = (string) ($_POST['accion'] ?? '');
    if ($accion === 'crear') {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $pass = (string) ($_POST['password'] ?? '');
        $exists = db()->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $exists->execute([$email]);
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Escribe nombre y un correo válido.';
        } elseif (mb_strlen($pass) < 10) {
            $error = 'La contraseña debe tener al menos 10 caracteres.';
        } elseif ((int) $exists->fetchColumn() > 0) {
            $error = 'Ya existe un usuario con ese correo.';
        } else {
            db()->prepare('INSERT INTO users (name, email, password_hash, created_at) VALUES (?,?,?,?)')->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT), now()]);
            flash('Usuario agregado. Compártele su correo y contraseña por un medio seguro.');
            redirect('/admin/usuarios.php');
        }
    } elseif ($accion === 'password') {
        $st = db()->prepare('SELECT password_hash FROM users WHERE id = ?');
        $st->execute([$user['id']]);
        $new = (string) ($_POST['new'] ?? '');
        if (!password_verify((string) ($_POST['current'] ?? ''), (string) $st->fetchColumn())) {
            $error = 'Tu contraseña actual no es correcta.';
        } elseif (mb_strlen($new) < 10) {
            $error = 'La nueva contraseña debe tener al menos 10 caracteres.';
        } else {
            db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);
            flash('Contraseña actualizada.');
            redirect('/admin/usuarios.php');
        }
    } elseif ($accion === 'eliminar') {
        $uid = (int) ($_POST['id'] ?? 0);
        if ($uid === (int) $user['id']) {
            $error = 'No puedes eliminar tu propio usuario.';
        } else {
            db()->prepare('DELETE FROM users WHERE id = ?')->execute([$uid]);
            flash('Usuario eliminado.');
            redirect('/admin/usuarios.php');
        }
    }
}

$users = db()->query('SELECT id, name, email, created_at, last_login_at FROM users ORDER BY name')->fetchAll();
admin_head('Usuarios', $user);
?>
  <div class="a-head"><div><h1>Usuarios del panel</h1><p class="a-muted">Quienes pueden escribir y publicar en el blog.</p></div></div>
<?php if ($error): ?><p class="a-alert a-alert-err" role="alert"><?= e($error) ?></p><?php endif; ?>
  <div class="a-grid">
    <div class="a-card">
      <h2>Usuarios</h2>
      <ul class="a-users">
<?php foreach ($users as $u): ?>
        <li>
          <div><strong><?= e($u['name']) ?></strong><span class="a-muted"><?= e($u['email']) ?> · último acceso: <?= $u['last_login_at'] ? e(fecha_es($u['last_login_at'], false)) : 'nunca' ?></span></div>
          <?php if ((int) $u['id'] !== (int) $user['id']): ?>
          <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $u['id'] ?>"><button class="a-btn a-btn-outline-red" type="submit" name="accion" value="eliminar" data-confirm="¿Eliminar a <?= e($u['name']) ?>?">Eliminar</button></form>
          <?php else: ?><span class="a-status a-status-live">Tú</span><?php endif; ?>
        </li>
<?php endforeach; ?>
      </ul>
    </div>
    <div class="a-card">
      <h2>Agregar usuario</h2>
      <form method="post" class="a-form"><?= csrf_field() ?><input type="hidden" name="accion" value="crear">
        <label>Nombre<input name="name" required></label>
        <label>Correo<input name="email" type="email" required autocomplete="off"></label>
        <label>Contraseña (mínimo 10 caracteres)<input name="password" type="password" minlength="10" required autocomplete="new-password"></label>
        <button class="a-btn a-btn-red" type="submit">Agregar</button>
      </form>
    </div>
    <div class="a-card">
      <h2>Cambiar mi contraseña</h2>
      <form method="post" class="a-form"><?= csrf_field() ?><input type="hidden" name="accion" value="password">
        <label>Contraseña actual<input name="current" type="password" required autocomplete="current-password"></label>
        <label>Nueva contraseña<input name="new" type="password" minlength="10" required autocomplete="new-password"></label>
        <button class="a-btn" type="submit">Actualizar</button>
      </form>
    </div>
  </div>
<?php admin_foot('<script src="' . e(asset_admin('panel.js')) . '"></script>'); ?>
