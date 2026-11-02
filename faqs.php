<?php
require __DIR__ . '/app/bootstrap.php';

$calRows = '';
foreach (calendar_rows() as [, $label, $months]) {
    $calRows .= '<tr><td style="padding:8px 12px;border-bottom:1px solid var(--line);font-family:var(--mono)">' . e($label) . '</td><td style="padding:8px 12px;border-bottom:1px solid var(--line)">' . e($months) . '</td></tr>';
}
$calTable = '<table><thead><tr><th style="text-align:left;padding:8px 12px;border-bottom:2px solid var(--ink)">Dígito</th><th style="text-align:left;padding:8px 12px;border-bottom:2px solid var(--ink)">Periodo anual (físico-mecánica)</th></tr></thead><tbody>' . $calRows
    . '<tr><td style="padding:8px 12px;font-family:var(--mono)">DOLLY</td><td style="padding:8px 12px">Sin placas: de enero a diciembre</td></tr></tbody></table>';

$groups = [
    'Inspección y verificación' => [
        ['¿A quién aplica la inspección físico-mecánica?', '<p>A vehículos en circulación o configuraciones vehiculares nuevas, o que se incorporen al servicio de autotransporte federal y transporte privado, que deseen incrementar el peso de carga con respecto a lo permitido, a partir de la entrada en vigor de la norma.</p>'],
        ['¿Cuándo debo realizar mi verificación?', '<p>La inspección físico-mecánica se realiza una vez al año, según el dígito de la placa:</p>' . $calTable . '<p>La verificación de emisiones se hace cada semestre: del 1 de enero al 30 de junio y del 1 de julio al 31 de diciembre. Consulta el <a href="/calendario-verificaciones-cancun/">calendario y el buscador por placa</a>.</p>'],
        ['¿Cómo saber qué dígito es mi vehículo?', '<p>Para los vehículos de autotransporte federal de carga, pasaje y turismo, autotransporte internacional de pasajeros, turismo y carga, así como los de arrastre y salvamento, corresponde el tercer carácter numérico, contando de izquierda a derecha. Ejemplo: 658-AB-2, el dígito será el “8”.</p><p>Para los vehículos que prestan los servicios auxiliares de paquetería y mensajería corresponde el cuarto carácter numérico. Ejemplo: 4683-ZP, el dígito será el “3”.</p><p>Para los vehículos con permiso de transporte privado con placas emitidas por entidades federativas corresponde el último carácter numérico. Ejemplos: ABC-1986, el dígito será el “6”; 5682-AA, el “2”; AA-86724, el “4”.</p><p>Para las placas no consideradas en los incisos anteriores se toma en cuenta el último carácter numérico, contando de izquierda a derecha.</p>'],
        ['¿Cuándo un vehículo no puede obtener su verificación?', '<p>Cuando no cubre sus verificaciones anteriores y/o cuando el vehículo no pasa la prueba de emisiones.</p>'],
        ['¿Cuándo se debe hacer la verificación de emisiones contaminantes?', '<p><strong>Primera vez:</strong> cuando quieres sacar tus placas federales es necesario tener antes tus verificaciones.</p><p><strong>Renovación:</strong> los periodos para verificar son de enero a junio y de julio a diciembre.</p>'],
        ['Requisitos para realizar la inspección y la verificación', '<ul><li>Copia de la tarjeta de circulación.</li><li>Original del certificado anterior.</li><li>Unidad a verificar.</li></ul><p>Consulta los requisitos completos en <a href="/tarifas-requisitos/">tarifas y requisitos</a>.</p>'],
        ['¿Cuánto puedo cargar de más?', '<p>El peso bruto vehicular máximo autorizado para los vehículos y configuraciones vehiculares se podrá incrementar en 1.5 toneladas en cada eje motriz y 1.0 tonelada en cada eje de carga, exclusivamente cuando circulen por caminos tipo ET y A, siempre y cuando cumplan con todas y cada una de las especificaciones técnicas, disposiciones de seguridad y de control (inspección físico-mecánica, verificación de contaminantes, motor electrónico, freno auxiliar de motor, convertidor con doble cadena, sistema antibloqueo para frenos y suspensión de aire).</p>'],
    ],
    'Precios' => [
        ['¿Cuál es el precio de la verificación de emisiones contaminantes?', '<p>$544.38 pesos mexicanos (IVA incluido).</p>'],
        ['¿Cuál es el precio de la verificación físico-mecánica?', '<p>$1,060.00 pesos mexicanos (IVA incluido).</p>'],
        ['¿El precio de la verificación físico-mecánica varía dependiendo del vehículo?', '<p>No, el mismo precio aplica para todo vehículo que requiera la verificación, desde un sedán hasta un remolque de seis ejes.</p>'],
    ],
    'Licencia federal' => [
        ['¿Cuándo es necesaria una licencia federal?', '<p>Para los vehículos que circulan en carretera federal. Con la finalidad de ofrecer un mejor servicio a los operadores, la SICT establece reglas de carácter general para que los trámites de expedición, renovación, duplicado, así como cambio de categoría o categoría adicional de la Licencia Federal de Conductor, se realicen a través de medios de comunicación electrónica. Las licencias se diferencian por categorías, desde la “A” hasta la “F”.</p>'],
        ['¿Qué vigencia tiene la licencia federal?', '<p>La Licencia Federal de Conductor categorías “A”, “B”, “C”, “D” y “F”, una vez expedida, deberá renovarse cada cuatro años; la categoría “E”, cada dos años.</p>'],
        ['¿Para qué me sirve la licencia categoría “A”?', '<p>Autoriza a conducir vehículos destinados a la prestación del servicio de autotransporte federal de pasajeros (excepto en la modalidad de transporte de o hacia puertos marítimos y aeropuertos federales) y de turismo (excepto en la modalidad de chofer-guía), y para el transporte privado de personas.</p>'],
        ['¿Para qué me sirve la licencia categoría “B”?', '<p>Para conducir vehículos destinados a la prestación del servicio de autotransporte federal de carga y para el transporte privado de carga, en sus diferentes modalidades, excepto los doblemente articulados y los que transportan materiales, residuos, remanentes y desechos peligrosos.</p>'],
        ['¿Para qué me sirve la licencia categoría “C”?', '<p>Para conducir vehículos de dos o tres ejes (rabón o tortón) destinados a la prestación del servicio de autotransporte federal de carga en sus diferentes modalidades, y para el transporte privado de carga, excepto los que transportan materiales, residuos, remanentes y desechos peligrosos.</p>'],
        ['¿Para qué me sirve la licencia categoría “D”?', '<p>Para conducir vehículos del servicio de autotransporte federal de turismo en su modalidad de chofer-guía.</p>'],
        ['¿Para qué me sirve la licencia categoría “E”?', '<p>Autoriza a conducir tractocamiones doblemente articulados (TSR y TSS) en todas sus variantes, destinados a la prestación del servicio de autotransporte federal de carga general y al transporte privado de carga general, así como vehículos destinados al servicio de carga especializada que transporta materiales, residuos, remanentes y desechos peligrosos, y al transporte privado de los mismos.</p>'],
        ['¿Para qué me sirve la licencia categoría “F”?', '<p>Para conducir vehículos destinados a la prestación del servicio de autotransporte federal de pasajeros de o hacia puertos marítimos y aeropuertos federales.</p>'],
        ['¿Cuál es la vigencia de la constancia de capacitación?', '<p>La vigencia es de dos años a partir de la fecha de expedición de la misma. <a href="/centro-capacitacion/">Ver cursos de capacitación</a>.</p>'],
    ],
    'Examen médico' => [
        ['¿Dónde puedo sacar mi certificado médico para la licencia federal?', '<p>El examen médico (constancia de aptitud psicofísica) para la licencia federal lo realiza la SICT en sus unidades médicas.</p>'],
        ['¿Cómo puedo hacer una cita para el examen médico?', '<p>Los exámenes médicos se agendan directamente con la SICT. Para hacer una cita ingresa a: <a href="https://ts.sct.gob.mx/cis/" target="_blank" rel="noopener">ts.sct.gob.mx/cis</a>.</p>'],
        ['¿Puedo reprobar el examen médico?', '<p>Sí, si no cumples con los requisitos mínimos para pasar el examen. Uno de los motivos por los que más se rechaza es la obesidad.</p>'],
    ],
];

$all = [];
foreach ($groups as $items) {
    foreach ($items as $f) {
        $all[] = $f;
    }
}
$page = [
    'title' => 'Verificadora Federal de Cancún - Faqs',
    'description' => 'Preguntas frecuentes sobre la verificación federal de emisiones contaminantes y físico-mecánica, la licencia federal y el examen médico. Empresa autorizada por la SICT y acreditada por la EMA.',
    'path' => '/faqs/',
    'breadcrumb' => [['Preguntas frecuentes', '/faqs/']],
    'schema' => [schema_faq($all, '/faqs/')],
];
page_start($page);
page_hero('Preguntas frecuentes', 'Verificación, precios, licencia federal y examen médico: las dudas más comunes de los transportistas.', [['Preguntas frecuentes', '/faqs/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
<?php foreach ($groups as $title => $items): ?>
      <div class="block">
        <h2><?= e($title) ?></h2>
        <div class="faq-list">
<?php foreach ($items as [$q, $a]) echo faq_item($q, $a), "\n"; ?>
        </div>
      </div>
<?php endforeach; ?>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band('¿No encontraste tu respuesta?', 'Escríbenos por WhatsApp y te respondemos.'); page_end($page); ?>
