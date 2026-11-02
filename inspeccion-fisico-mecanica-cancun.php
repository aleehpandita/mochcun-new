<?php
require __DIR__ . '/app/bootstrap.php';

$desc = 'Inspección Físico Mecánica para asegurarse de que un vehículo se encuentra en óptimas condiciones de funcionamiento mecánico, de acuerdo con lo establecido en la NOM-068-SCT-2-2014. Verificadora federal en Cancún.';
$page = [
    'title' => 'Verificadora Federal de Cancún - Inspección Físico y Mecánica',
    'description' => $desc,
    'path' => '/inspeccion-fisico-mecanica-cancun/',
    'breadcrumb' => [['Inspección físico-mecánica', '/inspeccion-fisico-mecanica-cancun/']],
    'schema' => [schema_service('Inspección físico-mecánica', '/inspeccion-fisico-mecanica-cancun/', $desc)],
];
page_start($page);
page_hero('Inspección físico-mecánica en Cancún', 'Verificación de condiciones físico-mecánicas y de seguridad para vehículos de autotransporte federal, conforme a la NOM-068-SCT-2-2014.', [['Inspección físico-mecánica', '/inspeccion-fisico-mecanica-cancun/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <figure class="figure"><?= img('fisico-mecanica', 'Área de inspección físico-mecánica de MOCHCUN', '(max-width: 960px) 100vw, 800px', ['loading' => 'eager']) ?></figure>
      <div class="block">
        <span class="nom">NOM-068-SCT-2-2014</span>
        <h2>Objetivo</h2>
        <div class="prose"><p>Establecer los criterios de verificación de condiciones físico-mecánicas, así como los criterios de aceptación y rechazo de vehículos de autotransporte, de conformidad con lo que señala la norma oficial mexicana NOM-068-SCT-2-2014.</p></div>
      </div>
      <div class="block">
        <h2>Alcance</h2>
        <div class="prose"><p>El presente procedimiento de verificación es aplicable a los vehículos del servicio de autotransporte federal de pasaje, turismo, carga y transporte privado, para llevar a cabo, a petición de parte, la verificación de condiciones físico-mecánicas y de seguridad para la operación en caminos y puentes de jurisdicción federal dentro de los Estados Unidos Mexicanos, de conformidad con la norma oficial mexicana NOM-068-SCT-2-2014.</p></div>
      </div>
      <div class="block">
        <h2>Requisitos</h2>
        <div class="two-col">
          <div class="panel panel-red">
            <h3>Primera vez</h3>
            <ul class="checks">
              <li>Dos años de gracia, siempre y cuando en el año en curso se cumplan los mismos.</li>
              <li>Recién emplacados: 60 días (resta dos años al año en curso; los modelos por debajo del resultado de la resta son los que tienen este plazo, consultar el DOF).</li>
              <li>Tarjeta de circulación completamente legible.</li>
            </ul>
          </div>
          <div class="panel panel-red">
            <h3>Renovación</h3>
            <ul class="checks">
              <li>Original del certificado de verificación inmediato anterior.</li>
              <li>Copia de la tarjeta de circulación completamente legible.</li>
            </ul>
          </div>
        </div>
      </div>
      <figure class="figure"><?= img('fisico-diagnostico', 'Diagnóstico de motor durante la inspección', '(max-width: 960px) 100vw, 800px') ?></figure>
      <div class="block">
        <h2>¿Cuándo me toca?</h2>
        <div class="prose"><p>La inspección físico-mecánica es anual y se programa según el dígito de la placa. En placas federales se toma el tercer dígito numérico; en placas estatales, el último.</p></div>
        <a class="btn btn-dark" href="/calendario-verificaciones-cancun/" style="align-self:flex-start">Consultar el calendario por placa <?= icon('arrow', 18) ?></a>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
