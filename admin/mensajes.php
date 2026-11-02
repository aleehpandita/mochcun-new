<?php
require __DIR__ . '/_auth.php';
require __DIR__ . '/_layout.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $mid = (int) ($_POST['id'] ?? 0);
    if (($_POST['accion'] ?? '') === 'eliminar') {
        db()->prepare('DELETE FROM messages WHERE id = ?')->execute([$mid]);
        flash('Mensaje eliminado.');
    } else {
        db()->prepare('UPDATE messages SET read_at = COALESCE(read_at, ?) WHERE id = ?')->execute([now(), $mid]);
    }
    redirect('/admin/mensajes.php');
}

$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC LIMIT 300')->fetchAll();
admin_head('Mensajes', $user);
?>
  <div class="a-head">
    <div><h1>Mensajes del formulario</h1><p class="a-muted">Lo que la gente envía desde la página de Contacto. También llega por correo a <?= e(config('mail_to')) ?>.</p></div>
  </div>
<?php if (!$messages): ?>
  <div class="a-empty"><p>Aún no hay mensajes.</p></div>
<?php endif; ?>
  <div class="a-msgs">
<?php foreach ($messages as $m): ?>
    <article class="a-card a-msg<?= $m['read_at'] ? '' : ' is-new' ?>">
      <header>
        <div><strong><?= e($m['name']) ?></strong> <?php if (!$m['read_at']): ?><span class="a-status a-status-live">Nuevo</span><?php endif; ?>
          <div class="a-muted"><?= e($m['subject']) ?> · <?= e(fecha_es($m['created_at'], false)) ?> <?= e(date('H:i', strtotime($m['created_at']))) ?><?= $m['mailed'] ? '' : ' · <b>no se pudo enviar por correo</b>' ?></div>
        </div>
        <div class="a-msg-actions">
          <a class="a-btn" href="https://wa.me/52<?= e(preg_replace('/\D/', '', (string) $m['phone'])) ?>" target="_blank" rel="noopener">WhatsApp</a>
          <a class="a-btn" href="mailto:<?= e($m['email']) ?>?subject=<?= e(rawurlencode('Re: ' . $m['subject'])) ?>">Responder</a>
          <?php if (!$m['read_at']): ?><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $m['id'] ?>"><button class="a-btn" type="submit">Marcar leído</button></form><?php endif; ?>
          <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $m['id'] ?>"><button class="a-btn a-btn-outline-red" type="submit" name="accion" value="eliminar" data-confirm="¿Eliminar este mensaje?">Eliminar</button></form>
        </div>
      </header>
      <p class="a-muted"><?= e($m['email']) ?> · <?= e($m['phone']) ?></p>
      <p class="a-msg-body"><?= nl2br(e($m['body'])) ?></p>
    </article>
<?php endforeach; ?>
  </div>
<?php admin_foot('<script src="' . e(asset_admin('panel.js')) . '"></script>'); ?>
