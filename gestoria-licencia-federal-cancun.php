<?php
require __DIR__ . '/app/bootstrap.php';

$desc = 'Gestoría para el proceso de adquisición y renovación de la Licencia Federal de Conductor en todos sus tipos en la ciudad de Cancún. Requisitos, categorías y vigencia.';
$cats = [
    ['A', 'Con esta letra estás autorizado a conducir vehículos destinados a la prestación del servicio de autotransporte de pasajeros (pasaje), al transporte privado de personas —excepto en el transporte de o hacia los puertos marítimos y aeropuertos federales— y/o turismo (excepto chofer-guía).'],
    ['B', 'Con esta letra estás autorizado a conducir vehículos destinados a la prestación del servicio de autotransporte de carga y transporte privado de carga, en todas sus modalidades, excepto materiales peligrosos, residuos y/o desechos.'],
    ['C', 'Con esta letra estás autorizado a conducir vehículos de dos o tres ejes (rabón o tortón) y para el transporte privado de carga en sus diferentes modalidades, excepto materiales peligrosos, residuos y/o desechos.'],
    ['D', 'Con esta letra estás autorizado a conducir vehículos del servicio de autotransporte federal de turismo en su modalidad de chofer-guía.'],
    ['E', 'Autoriza a conducir tractocamiones doblemente articulados y vehículos que transportan materiales, residuos, remanentes y desechos peligrosos. Se renueva cada dos años.'],
    ['F', 'Con esta letra estás autorizado a conducir vehículos destinados al servicio de autotransporte federal de pasajeros de o hacia puertos marítimos y aeropuertos federales.'],
];
$page = [
    'title' => 'Gestoría de Licencia Federal en Cancún',
    'description' => $desc,
    'path' => '/gestoria-licencia-federal-cancun/',
    'breadcrumb' => [['Gestoría de licencia federal', '/gestoria-licencia-federal-cancun/']],
    'schema' => [schema_service('Gestoría de licencia federal de conductor', '/gestoria-licencia-federal-cancun/', $desc)],
];
page_start($page);
page_hero('Gestoría de licencia federal en Cancún', 'Te asesoramos en el trámite de la licencia federal: qué requisitos necesitas, qué tipos existen y cuál debes elegir de acuerdo con tus necesidades.', [['Gestoría de licencia federal', '/gestoria-licencia-federal-cancun/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <figure class="figure"><?= img('licencia-carretera', 'Carretera federal al atardecer', '(max-width: 960px) 100vw, 800px', ['loading' => 'eager']) ?></figure>

      <div class="block">
        <h2>Expedición de licencia federal de conductor</h2>
        <div class="prose"><p>En Servicios de Autotransporte MOCHCUN te asesoramos para el trámite de la licencia federal: cuáles son los requisitos, qué tipos de licencia federal existen y cuál es la que debes elegir de acuerdo con tus necesidades. Para tramitarla en cualquiera de sus categorías deberás tener los siguientes documentos.</p></div>
        <div class="panel panel-red">
          <h3>Documentos solicitados por la SICT</h3>
          <ul class="checks">
            <li>Acta de nacimiento e identificación oficial (INE, cédula profesional o pasaporte vigente) que acredite ser mayor de 18 años. (Original y copia)</li>
            <li>Comprobante de domicilio con antigüedad no mayor a 3 meses: recibo de telefonía local, agua, luz o predial. (Original y copia)</li>
            <li>Constancia de aptitud psicofísica. Es un examen médico que solo puede realizarse en la SICT. (Original y copia)</li>
            <li>CURP (Clave Única de Registro de Población). <a href="https://www.gob.mx/curp/" target="_blank" rel="noopener">Descarga tu CURP en gob.mx</a>. (Original y copia)</li>
            <li>Constancia de capacitación vigente que corresponda a la categoría solicitada. La capacitación la podemos otorgar nosotros: <a href="/centro-capacitacion/">conoce nuestro centro de capacitación</a>. (Original y copia)</li>
            <li>Para obtener la categoría “D”, credencial vigente de guía de turistas general expedida por la Secretaría de Turismo (SECTUR). (Original y copia)</li>
            <li>Solicitud requisitada por personal de la SICT.</li>
          </ul>
        </div>
      </div>

      <div class="block">
        <h2>Renovación de la licencia federal</h2>
        <div class="prose"><p>Puedes renovar tu licencia federal de acuerdo con tu categoría: las licencias categorías “A”, “B”, “C”, “D” y “F” deben renovarse <strong>cada 4 años</strong> y la licencia categoría “E” <strong>cada 2 años</strong>.</p></div>
        <div class="panel panel-red">
          <h3>Documentos necesarios para la renovación</h3>
          <ul class="checks">
            <li>Solicitud requisitada por personal de la SICT.</li>
            <li>Constancia de aptitud psicofísica. Es un examen médico que solo puede realizarse en la SICT. (Original y copia)</li>
            <li>Constancia de capacitación vigente que corresponda a la categoría solicitada.</li>
            <li>Para obtener la categoría “E”, contar con licencia federal categoría “B” o “C” con una antigüedad de cuando menos dos años. (Original y copia)</li>
            <li>Para renovar la categoría “D”, credencial vigente de guía de turistas general expedida por la Secretaría de Turismo (SECTUR). (Original y copia)</li>
            <li>Comprobante de domicilio con antigüedad no mayor a 3 meses: recibo de telefonía local, agua, luz o predial. (Original y copia)</li>
          </ul>
        </div>
      </div>

      <div class="block">
        <h2>Categorías de la licencia federal</h2>
        <div class="prose"><p>Para tramitar tu licencia federal debes conocer sus categorías y elegir una letra. Si lo requieres, puedes tener dos o más letras en tu licencia; todo depende de tus necesidades.</p></div>
        <div class="cat-grid">
<?php foreach ($cats as [$l, $t]): ?>
          <div class="cat"><b aria-hidden="true"><?= e($l) ?></b><h3 style="font-size:19px">Categoría “<?= e($l) ?>”</h3><p><?= e($t) ?></p></div>
<?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band('¿Necesitas tramitar o renovar tu licencia federal?', 'Revisamos tus documentos y te decimos qué te falta antes de ir a la SICT.'); page_end($page); ?>
