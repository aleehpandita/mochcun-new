<?php
require __DIR__ . '/app/bootstrap.php';

$desc = 'Centro de Capacitación en Cancún: cursos de nuevo ingreso y refrendo para operadores del autotransporte federal en las categorías A, B, C, D, E y F. Empresa autorizada por la SICT y la EMA.';
$page = [
    'title' => 'Verificadora Federal de Cancún - Centro de Capacitación',
    'description' => $desc,
    'path' => '/centro-capacitacion/',
    'breadcrumb' => [['Centro de capacitación', '/centro-capacitacion/']],
    'schema' => [schema_service('Centro de capacitación para operadores', '/centro-capacitacion/', $desc)],
];
page_start($page);
page_hero('Centro de capacitación', 'Comprometidos con la seguridad de los operadores en las carreteras federales, ofrecemos cursos de nuevo ingreso o refrendo.', [['Centro de capacitación', '/centro-capacitacion/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <div class="block">
        <h2>Categorías</h2>
        <div class="prose"><p>Ofrecemos cursos de <strong>nuevo ingreso</strong> o <strong>refrendo</strong> en las siguientes categorías:</p></div>
        <div class="cat-grid">
          <div class="cat"><b aria-hidden="true">A</b><p>Pasaje y exclusivo de turismo.</p></div>
          <div class="cat"><b aria-hidden="true">B</b><p>Carga tráiler.</p></div>
          <div class="cat"><b aria-hidden="true">C</b><p>Carga de dos o tres ejes (rabón o tortón).</p></div>
          <div class="cat"><b aria-hidden="true">D</b><p>Chofer-guía de turistas.</p></div>
          <div class="cat"><b aria-hidden="true">E</b><p>Carga de materiales y residuos peligrosos.</p></div>
          <div class="cat"><b aria-hidden="true">F</b><p>Puertos y aeropuertos, taxi.</p></div>
        </div>
        <div class="prose"><p>La constancia de capacitación tiene una vigencia de dos años a partir de su fecha de expedición.</p></div>
      </div>
      <div class="two-col">
        <div class="panel panel-red">
          <h3>Requisitos de obtención</h3>
          <ul class="checks">
            <li>Identificación oficial (INE o pasaporte).</li>
            <li>Comprobante de domicilio (3 meses de antigüedad).</li>
            <li>CURP.</li>
            <li>Acta de nacimiento.</li>
          </ul>
        </div>
        <div class="panel panel-red">
          <h3>Requisitos de renovación</h3>
          <ul class="checks">
            <li>Identificación oficial (INE o pasaporte).</li>
            <li>Comprobante de domicilio (3 meses de antigüedad).</li>
            <li>Licencia federal anterior.</li>
            <li>Constancia anterior.</li>
            <li>CURP.</li>
            <li>Acta de nacimiento.</li>
          </ul>
        </div>
      </div>
      <a class="link-arrow" href="/gestoria-licencia-federal-cancun/">Requisitos para tramitar la licencia federal <?= icon('arrow', 18) ?></a>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band('¿Quieres inscribirte a un curso?', 'Pregúntanos por las próximas fechas y la documentación que necesitas.'); page_end($page); ?>
