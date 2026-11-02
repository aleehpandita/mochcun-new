<?php
require __DIR__ . '/app/bootstrap.php';

$desc = 'Verificación de emisiones contaminantes en Cancún definida por las normas oficiales mexicanas NOM-045-SEMARNAT-2017, NOM-047-SEMARNAT-2014 y NOM-167-SEMARNAT-2017. Unidades a gasolina y diésel.';
$page = [
    'title' => 'Verificación de Emisiones Contaminantes en Cancún',
    'description' => $desc,
    'path' => '/verificacion-emisiones-cancun/',
    'breadcrumb' => [['Verificación de emisiones', '/verificacion-emisiones-cancun/']],
    'schema' => [schema_service('Verificación de emisiones contaminantes', '/verificacion-emisiones-cancun/', $desc)],
];
page_start($page);
page_hero('Verificación de emisiones contaminantes en Cancún', 'Verificación semestral de baja emisión de contaminantes para vehículos de autotransporte federal a gasolina y diésel.', [['Verificación de emisiones', '/verificacion-emisiones-cancun/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <figure class="figure"><?= img('emisiones-sonda', 'Técnico colocando la sonda de medición de emisiones', '(max-width: 960px) 100vw, 800px', ['loading' => 'eager']) ?></figure>

      <div class="block">
        <span class="nom">NOM-045-SEMARNAT-2017</span>
        <h2>Objetivo</h2>
        <div class="prose"><p>Establecer los lineamientos necesarios para la realización de las verificaciones de cumplimiento con la norma oficial mexicana NOM-045-SEMARNAT-2017.</p></div>
        <h3>Alcance</h3>
        <div class="prose"><p>El presente procedimiento de verificación es utilizado para llevar a cabo, a petición de parte, la verificación de aquellos clientes que deseen cumplir con las normas oficiales mexicanas aplicables a la misma.</p></div>
      </div>

      <div class="block">
        <span class="nom">NOM-047-SEMARNAT-2014 · NOM-167-SEMARNAT-2017</span>
        <h2>Objetivo</h2>
        <div class="prose"><p>Establecer los lineamientos necesarios para la realización de las verificaciones de cumplimiento con las normas oficiales mexicanas NOM-047-SEMARNAT-2014 y NOM-167-SEMARNAT-2017, relativas a los límites máximos permisibles de emisión de contaminantes y al procedimiento de medición para los vehículos automotores en circulación.</p></div>
        <h3>Alcance</h3>
        <div class="prose"><p>A petición de parte de aquellos clientes que deseen cumplir con las normas oficiales mexicanas aplicables, el personal calificado de la unidad de verificación es responsable de verificar el cabal cumplimiento del presente procedimiento, así como de asegurarse de que la verificación se realice conforme a los requisitos indicados en las normas y al sistema de calidad.</p></div>
      </div>

      <figure class="figure"><?= img('emisiones-equipo', 'Equipo de medición de emisiones de MOCHCUN', '(max-width: 960px) 100vw, 800px') ?></figure>

      <div class="block">
        <h2>Periodos de verificación</h2>
        <div class="prose"><p>La verificación de emisiones es semestral y se puede realizar en cualquier fecha del periodo, sin importar la terminación de la placa. Aplica igual para diésel y gasolina.</p></div>
        <div class="periods">
          <div class="period"><span class="tag">1er semestre</span><strong>1 de enero al 30 de junio</strong></div>
          <div class="period"><span class="tag">2º semestre</span><strong>1 de julio al 31 de diciembre</strong></div>
        </div>
        <a class="link-arrow" href="/tarifas-requisitos/">Ver tarifas y requisitos de emisiones <?= icon('arrow', 18) ?></a>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
