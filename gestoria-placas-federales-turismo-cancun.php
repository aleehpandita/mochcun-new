<?php
require __DIR__ . '/app/bootstrap.php';

$desc = 'Gestoría para el proceso de adquisición de Placas Federales de turismo en la ciudad de Cancún. Requisitos y documentos solicitados por la SICT.';
$page = [
    'title' => 'Gestoría de Placas Federales de Turismo',
    'description' => $desc,
    'path' => '/gestoria-placas-federales-turismo-cancun/',
    'breadcrumb' => [['Placas federales de turismo', '/gestoria-placas-federales-turismo-cancun/']],
    'schema' => [schema_service('Gestoría de placas federales de turismo', '/gestoria-placas-federales-turismo-cancun/', $desc)],
];
page_start($page);
page_hero('Gestoría de placas federales de turismo', 'Te asesoramos en el trámite de placas federales para que tu empresa pueda utilizar sus vehículos en carretera federal.', [['Placas federales de turismo', '/gestoria-placas-federales-turismo-cancun/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <figure class="figure"><?= img('placas', 'Carretera federal', '(max-width: 960px) 100vw, 800px', ['loading' => 'eager']) ?></figure>
      <div class="block">
        <h2>Trámite de placas federales</h2>
        <div class="prose"><p>Para que tu empresa pueda utilizar vehículos de uso particular en carretera federal, es necesario este trámite.</p></div>
      </div>
      <div class="panel panel-red">
        <h3>Documentos solicitados por la SICT</h3>
        <ul class="checks">
          <li>Pre-registro. (Original y copia)</li>
          <li>Documento que ampara la inscripción en el Registro Federal de Contribuyentes (RFC) de la empresa solicitante. (Original y copia)</li>
          <li>Constancia de registro de la empresa arrendadora ante la SICT. (Original y copia)</li>
          <li>Factura, carta factura vigente, contrato de arrendamiento vigente o documento del Registro Nacional de Vehículos actualizado, de los vehículos. (Original y copia)</li>
          <li>Comprobante de domicilio con antigüedad no mayor a 3 meses (telefonía local, agua, luz o predial). (Original y copia)</li>
          <li>En el caso de unidades de procedencia extranjera, documento que acredite su legal importación, estancia o tenencia en el país. (Original y copia)</li>
          <li>Certificado de baja emisión de contaminantes vigente. (Original y copia) — <a href="/verificacion-emisiones-cancun/">lo hacemos aquí</a></li>
          <li>Póliza de seguro vigente con cobertura amplia. (Original y copia)</li>
          <li>Certificado de verificación físico-mecánica del vehículo, cuando aplique. (Original y copia) — <a href="/inspeccion-fisico-mecanica-cancun/">lo hacemos aquí</a></li>
          <li>Póliza de seguro de viajero vigente. (Original y copia)</li>
          <li>Del representante legal o apoderado legal, todos en original y copia:
            <ul>
              <li>Poder notarial otorgado ante fedatario público.</li>
              <li>Identificación oficial vigente (credencial para votar del INE, cédula profesional, cartilla militar o pasaporte).</li>
              <li>Documento que ampara la inscripción en el Registro Federal de Contribuyentes (RFC).</li>
              <li>Comprobante de domicilio con antigüedad no mayor a 3 meses (telefonía local, agua, luz o predial).</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band('¿Vas a emplacar unidades de turismo?', 'Te ayudamos con las verificaciones y la integración de tu expediente para la SICT.'); page_end($page); ?>
