<?php
require __DIR__ . '/app/bootstrap.php';

$desc = 'Medicina preventiva en Cancún: examen psicofísico integral, toxicológico y de alcoholimetría para operadores del autotransporte federal. Empresa autorizada por la SICT y la EMA.';
$page = [
    'title' => 'Medicina Preventiva en Cancún - MOCHCUN',
    'description' => $desc,
    'path' => '/medicina-preventiva-cancun/',
    'breadcrumb' => [['Medicina preventiva', '/medicina-preventiva-cancun/']],
    'schema' => [schema_service('Medicina preventiva', '/medicina-preventiva-cancun/', $desc)],
];
page_start($page);
page_hero('Medicina preventiva', 'Exámenes para operadores del autotransporte federal, relacionados con el trámite y refrendo de la licencia federal.', [['Medicina preventiva', '/medicina-preventiva-cancun/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <figure class="figure"><?= img('medicina', 'Médico revisando un expediente', '(max-width: 960px) 100vw, 800px', ['loading' => 'eager']) ?></figure>
      <div class="block">
        <h2>Unidad de medicina preventiva</h2>
        <ul class="checks">
          <li>Examen psicofísico integral (necesario para el trámite o refrendo de la licencia federal).</li>
        </ul>
      </div>
      <div class="panel panel-red">
        <h3>Requisitos</h3>
        <ul class="checks">
          <li>Identificación oficial (INE o pasaporte).</li>
          <li>Comprobante de domicilio (3 meses de antigüedad).</li>
          <li>Situación fiscal (RFC)</li>
          <li>CURP.</li>
          <li>Acta de nacimiento.</li>
        </ul>
      </div>
      <div class="block">
        <h2>Relacionado</h2>
        <div class="two-col">
          <a class="card" href="/gestoria-licencia-federal-cancun/"><div class="card-body"><span class="tag">Gestoría</span><h3>Licencia federal</h3><span class="card-more">Requisitos y categorías <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div></a>
          <a class="card" href="/centro-capacitacion/"><div class="card-body"><span class="tag">Formación</span><h3>Centro de capacitación</h3><span class="card-more">Cursos por categoría <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div></a>
        </div>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
