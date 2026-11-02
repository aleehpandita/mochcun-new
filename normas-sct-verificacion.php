<?php
require __DIR__ . '/app/bootstrap.php';

/* Los PDF viven en /documentos/ del servidor (no se suben al repositorio por su peso).
   Si cambias un archivo, conserva el mismo nombre para no romper enlaces ya indexados. */
$docs = [
    ['NOM-068-SCT-2-2014', 'Condiciones físico-mecánicas y de seguridad para la operación en caminos y puentes de jurisdicción federal.', '/documentos/NOM-068-SCT.pdf'],
    ['NOM-045-SEMARNAT-2017', 'Vehículos en circulación que usan diésel como combustible: límites de opacidad y procedimiento de prueba.', '/documentos/nom-045-semarnat.pdf'],
    ['NOM-047-SEMARNAT-2014', 'Equipo y procedimiento de medición para la verificación de los límites de emisión de vehículos en circulación.', '/documentos/nom-047-semarnat.pdf'],
    ['NOM-167-SEMARNAT-2017', 'Límites de emisión de contaminantes para vehículos a gasolina y diésel.', '/documentos/NOM-167-SEMARNAT.pdf'],
];
$page = [
    'title' => 'Verificadora Federal de Cancún - Normas SCT Verificación',
    'description' => 'Normas oficiales mexicanas para la verificación federal físico-mecánica y de emisiones contaminantes: NOM-068-SCT-2-2014, NOM-045-SEMARNAT-2017, NOM-047-SEMARNAT-2014 y NOM-167-SEMARNAT-2017.',
    'path' => '/normas-sct-verificacion/',
    'breadcrumb' => [['Normas SCT', '/normas-sct-verificacion/']],
];
page_start($page);
page_hero('Normas SCT para la verificación', 'Normas oficiales mexicanas que aplicamos en la inspección físico-mecánica y la verificación de emisiones. Descárgalas en PDF.', [['Normas SCT', '/normas-sct-verificacion/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <ul class="doc-list">
<?php foreach ($docs as [$name, $text, $href]): ?>
        <li><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= icon('file', 28) ?><div><strong><?= e($name) ?></strong><span><?= e($text) ?></span></div><?= icon('arrow', 20, 'ico ico-arrow') ?></a></li>
<?php endforeach; ?>
      </ul>
      <div class="two-col">
        <a class="card" href="/inspeccion-fisico-mecanica-cancun/"><div class="card-body"><span class="tag">NOM-068-SCT-2-2014</span><h3>Inspección físico-mecánica</h3><span class="card-more">Ver servicio <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div></a>
        <a class="card" href="/verificacion-emisiones-cancun/"><div class="card-body"><span class="tag">NOM-045 · 047 · 167</span><h3>Verificación de emisiones</h3><span class="card-more">Ver servicio <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div></a>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
