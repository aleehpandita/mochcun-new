<?php
require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/app/contact.php';

$state = contact_handle();   // procesa el envío (POST) y redirige; en GET regresa el estado
$a = config('address');
$page = [
    'title' => 'Verificadora Federal de Cancún - Contacto',
    'description' => 'Contacto de MOCHCUN, verificadora federal en Cancún: teléfono 998 361 0369, WhatsApp, correo info@mochcun.com y ubicación en la Zona Industrial, Región 97.',
    'path' => '/contacto/',
    'breadcrumb' => [['Contacto', '/contacto/']],
    'schema_page_type' => 'ContactPage',
];
page_start($page);
page_hero('Contacto', '¿Tienes alguna duda? ¿Quieres proponernos un tema? Cualquiera que sea tu pregunta o sugerencia, escríbenos y con gusto te atendemos.', [['Contacto', '/contacto/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <div class="block">
        <h2>Escríbenos</h2>
<?php if ($state['ok']): ?>
        <p class="alert alert-ok" role="status">Recibimos tu mensaje. Te responderemos lo antes posible. Si es urgente, escríbenos por WhatsApp.</p>
<?php elseif ($state['error']): ?>
        <p class="alert alert-err" role="alert"><?= e($state['error']) ?></p>
<?php endif; ?>
        <form class="form" method="post" action="/contacto/">
          <div class="field"><label for="c-nombre">Nombre</label><input id="c-nombre" name="nombre" type="text" required maxlength="120" autocomplete="name" value="<?= e($state['old']['nombre'] ?? '') ?>"></div>
          <div class="field"><label for="c-tel">Teléfono</label><input id="c-tel" name="telefono" type="tel" required maxlength="30" autocomplete="tel" value="<?= e($state['old']['telefono'] ?? '') ?>"></div>
          <div class="field"><label for="c-email">Correo electrónico</label><input id="c-email" name="email" type="email" required maxlength="160" autocomplete="email" value="<?= e($state['old']['email'] ?? '') ?>"></div>
          <div class="field"><label for="c-asunto">Asunto</label>
            <select id="c-asunto" name="asunto">
<?php foreach (['Verificación físico-mecánica', 'Verificación de emisiones', 'Licencia federal', 'Placas federales', 'Capacitación', 'Convenio para gestores o flotillas', 'Otro'] as $opt): ?>
              <option<?= (($state['old']['asunto'] ?? '') === $opt) ? ' selected' : '' ?>><?= e($opt) ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <div class="field full"><label for="c-msg">Mensaje</label><textarea id="c-msg" name="mensaje" required maxlength="4000"><?= e($state['old']['mensaje'] ?? '') ?></textarea></div>
          <div class="hp" aria-hidden="true"><label for="c-web">No llenar</label><input id="c-web" name="sitio_web" type="text" tabindex="-1" autocomplete="off"></div>
          <input type="hidden" name="t" value="<?= e(contact_token()) ?>">
          <div class="full"><button class="btn btn-red" type="submit">Enviar mensaje <?= icon('arrow', 18) ?></button></div>
        </form>
      </div>
      <div class="block">
        <h2>Ubicación</h2>
        <div class="prose"><p><?= e($a['street']) ?>, C.P. <?= e($a['postal']) ?>, <?= e($a['city']) ?>, <?= e($a['region']) ?>.</p></div>
        <iframe class="map" title="Mapa de ubicación de MOCHCUN" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= e(rawurlencode((string) config('maps_query'))) ?>&amp;output=embed"></iframe>
        <a class="link-arrow" href="<?= e(maps_url()) ?>" target="_blank" rel="noopener">Abrir en Google Maps <?= icon('arrow', 18) ?></a>
      </div>
    </div>
    <aside class="side-col" aria-label="Datos de contacto">
      <div class="cta-card" style="border:1px solid var(--line)">
        <div class="info-row"><?= icon('phone', 22) ?><div><strong>Teléfono</strong><a href="<?= e(tel_url()) ?>"><?= e(config('phone_display')) ?></a></div></div>
        <div class="info-row"><?= icon('whatsapp', 22) ?><div><strong>WhatsApp</strong><a href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener"><?= e(config('whatsapp_display')) ?></a></div></div>
        <div class="info-row"><?= icon('mail', 22) ?><div><strong>Correo</strong><a href="mailto:<?= e(config('email')) ?>"><?= e(config('email')) ?></a></div></div>
        <div class="info-row"><?= icon('clock', 22) ?><div><strong>Horario</strong><span><?= e(config('hours_display')) ?></span></div></div>
        <div class="info-row"><?= icon('pin', 22) ?><div><strong>Dirección</strong><span><?= e($a['street']) ?>, C.P. <?= e($a['postal']) ?>, <?= e($a['city']) ?>, Q. Roo</span></div></div>
        <div class="info-row"><?= icon('facebook', 22) ?><div><strong>Facebook</strong><a href="<?= e(config('facebook')) ?>" target="_blank" rel="noopener">Verificadora MOCHCUN</a></div></div>
        <div class="info-row"><?= icon('instagram', 22) ?><div><strong>Instagram</strong><a href="<?= e(config('instagram')) ?>" target="_blank" rel="noopener">@verificentromochcun</a></div></div>
      </div>
    </aside>
  </div>
</section>
<?php page_end($page); ?>
