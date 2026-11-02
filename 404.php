<?php
if (!defined('ROOT')) {
    require __DIR__ . '/app/bootstrap.php';
}
http_response_code(404);
$page = ['title' => 'Página no encontrada - MOCHCUN', 'description' => '', 'path' => '/404/', 'noindex' => true];
page_start($page);
page_hero('No encontramos esta página', 'Puede que la dirección haya cambiado. Estas son las secciones principales del sitio.', [['Página no encontrada', '/404/']]);
?>
<section class="section-sm"><div class="wrap grid-3">
<?php foreach (services_list() as [$name, $path]): ?>
  <a class="card" href="<?= e($path) ?>"><div class="card-body"><h3><?= e($name) ?></h3><span class="card-more">Ir <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div></a>
<?php endforeach; ?>
</div></section>
<?php cta_band(); page_end($page);
