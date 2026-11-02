<?php
require __DIR__ . '/app/bootstrap.php';

$faqs = [
    ['¿A quién aplica la inspección físico-mecánica?', '<p>A vehículos en circulación o configuraciones vehiculares nuevas que se incorporen al servicio de autotransporte federal y transporte privado, incluidos los que deseen incrementar el peso de carga con respecto a lo permitido.</p>'],
    ['¿Cómo sé qué dígito es mi vehículo?', '<p>En autotransporte federal de carga, pasaje y turismo, autotransporte internacional, arrastre y salvamento corresponde el tercer carácter numérico, contando de izquierda a derecha. Ejemplo: en 658-AB-2 el dígito es el <strong>8</strong>. En placas estatales se toma el último dígito.</p>'],
    ['¿Qué vigencia tiene la licencia federal?', '<p>Las categorías “A”, “B”, “C”, “D” y “F” se renuevan cada cuatro años; la categoría “E”, cada dos años.</p>'],
    ['¿Cuándo se hace la verificación de emisiones?', '<p>En dos periodos al año: del 1 de enero al 30 de junio y del 1 de julio al 31 de diciembre, sin importar la terminación de la placa.</p>'],
];

$page = [
    'title' => 'Verificación de Emisiones Contaminantes en Cancún',
    'description' => 'Unidad de Verificación SERVICIOS DE AUTOTRANSPORTE MOCHCUN, S.A. DE C.V. está avalada por la EMA y la SICT. Los mejores precios en Cancún y la Riviera Maya.',
    'path' => '/',
    'preload_image' => '<link rel="preload" as="image" type="image/webp" imagesrcset="/assets/img/hero-edificio-640.webp 640w, /assets/img/hero-edificio-960.webp 960w, /assets/img/hero-edificio-1400.webp 1400w" imagesizes="(max-width: 1060px) 100vw, 50vw">',
    'schema' => [schema_faq($faqs, '/')],
];
$posts = safe_latest_posts(3);
page_start($page);
?>
<section class="hero">
  <div class="hero-text">
    <img class="hero-mark" src="/assets/img/marca.svg" alt="" width="1057" height="398" aria-hidden="true">
    <div class="hero-inner">
      <div class="hero-kicker"><span>UNIDAD DE INSPECCIÓN</span><span>AUTOTRANSPORTE FEDERAL</span></div>
      <h1>Verificación de emisiones y físico-mecánica en Cancún</h1>
      <p class="hero-lead">Pasaje, turismo, carga y transporte privado. Acreditados por la EMA y aprobados por la SICT para que tus unidades circulen en caminos y puentes federales.</p>
      <div class="hero-ctas">
        <a class="btn btn-red" href="<?= e(whatsapp_url('Hola, quiero agendar una verificación')) ?>" target="_blank" rel="noopener">Agenda por WhatsApp <?= icon('arrow', 18) ?></a>
        <a class="btn btn-outline-light" href="/tarifas-requisitos/">Tarifas y requisitos</a>
      </div>
    </div>
  </div>
  <div class="hero-photo">
    <?= img('hero-edificio', 'Instalaciones de MOCHCUN, verificadora federal en Cancún', '(max-width: 1060px) 100vw, 50vw', ['loading' => 'eager']) ?>
  </div>
</section>

<div class="wrap creds-wrap">
  <div class="creds">
    <div class="cred"><span class="eyebrow">Acreditación</span><strong>Entidad Mexicana de Acreditación</strong><span>EMA</span></div>
    <div class="cred"><span class="eyebrow">Aprobación</span><strong>Secretaría de Infraestructura, Comunicaciones y Transportes</strong><span>SICT</span></div>
    <div class="cred"><span class="eyebrow">Desde</span><strong>Octubre de 2008</strong><span>Unidad de verificación tipo “C”</span></div>
    <div class="cred"><span class="eyebrow">Cobertura</span><strong>Cancún y Riviera Maya</strong><span>Zona Industrial, Región 97</span></div>
  </div>
</div>

<section class="section" id="servicios">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <span class="eyebrow">Servicios</span>
        <h2>Los trámites de tu unidad, en un solo lugar</h2>
      </div>
      <p>Verificamos, te asesoramos y gestionamos lo que tu operación necesita para circular en carretera federal.</p>
    </div>
    <div class="grid-2">
      <a class="card card-dark" href="/inspeccion-fisico-mecanica-cancun/">
        <div class="card-img"><?= img('fisico-mecanica', 'Área de inspección físico-mecánica de MOCHCUN en Cancún', '(max-width: 760px) 100vw, 600px') ?></div>
        <div class="card-body">
          <span class="tag">NOM-068-SCT-2-2014</span>
          <h3>Inspección físico-mecánica</h3>
          <p>Revisamos las condiciones físicas y mecánicas de tu vehículo para que opere con seguridad en caminos y puentes de jurisdicción federal.</p>
          <span class="card-more">Ver requisitos <?= icon('arrow', 18, 'ico ico-arrow') ?></span>
        </div>
      </a>
      <a class="card card-dark" href="/verificacion-emisiones-cancun/">
        <div class="card-img"><?= img('emisiones', 'Técnico de MOCHCUN realizando la verificación de emisiones', '(max-width: 760px) 100vw, 600px') ?></div>
        <div class="card-body">
          <span class="tag">NOM-045 · NOM-047 · NOM-167 SEMARNAT</span>
          <h3>Verificación de emisiones contaminantes</h3>
          <p>Medimos las emisiones de unidades a gasolina y diésel conforme a las normas oficiales mexicanas vigentes.</p>
          <span class="card-more">Ver requisitos <?= icon('arrow', 18, 'ico ico-arrow') ?></span>
        </div>
      </a>
    </div>
    <div class="grid-4" style="margin-top:24px">
      <a class="card" href="/gestoria-licencia-federal-cancun/">
        <div class="card-img"><?= img('licencia-federal', 'Tractocamión en carretera federal', '(max-width: 760px) 100vw, 300px') ?></div>
        <div class="card-body"><span class="tag">Gestoría</span><h3>Licencia federal</h3><p>Asesoría y trámite para obtener o renovar tu licencia, en todas sus categorías.</p><span class="card-more">Más información <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div>
      </a>
      <a class="card" href="/gestoria-placas-federales-turismo-cancun/">
        <div class="card-img"><?= img('placas', 'Carretera federal', '(max-width: 760px) 100vw, 300px') ?></div>
        <div class="card-body"><span class="tag">Gestoría</span><h3>Placas federales de turismo</h3><p>Te asesoramos para que tu empresa use sus vehículos en carretera federal.</p><span class="card-more">Más información <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div>
      </a>
      <a class="card" href="/medicina-preventiva-cancun/">
        <div class="card-img"><?= img('medicina', 'Médico revisando un expediente', '(max-width: 760px) 100vw, 300px') ?></div>
        <div class="card-body"><span class="tag">Salud</span><h3>Medicina preventiva</h3><p>Examen psicofísico integral, toxicológico y de alcoholimetría.</p><span class="card-more">Más información <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div>
      </a>
      <a class="card" href="/centro-capacitacion/">
        <div class="card-img"><?= img('tecnico', 'Técnico de MOCHCUN revisando un vehículo', '(max-width: 760px) 100vw, 300px') ?></div>
        <div class="card-body"><span class="tag">Formación</span><h3>Centro de capacitación</h3><p>Cursos de nuevo ingreso y refrendo para operadores, categorías A a F.</p><span class="card-more">Más información <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div>
      </a>
    </div>
  </div>
</section>

<section class="section dark on-dark" id="calendario">
  <div class="wrap cal">
    <div class="cal-side">
      <span class="eyebrow">Calendario de verificación</span>
      <h2 class="h2">¿Cuándo le toca a tu unidad?</h2>
      <p>La físico-mecánica se programa según el dígito de la placa. Las emisiones se pueden hacer en cualquier fecha del periodo, sin importar la placa.</p>
      <div class="box-line">
        <strong>¿Qué dígito tomo?</strong>
        <span><strong>Placas federales:</strong> el tercer carácter numérico, de izquierda a derecha. En 658-AB-2, el dígito es el <strong>8</strong>.</span>
        <span><strong>Placas estatales:</strong> el último dígito.</span>
      </div>
      <a class="link-arrow" href="/calendario-verificaciones-cancun/">Ver calendario y buscar por placa <?= icon('arrow', 18) ?></a>
    </div>
    <div class="cal-main">
      <?php calendar_table(); ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap why">
    <div class="why-photo">
      <?= img('tecnico', 'Personal técnico de MOCHCUN durante una inspección', '(max-width: 960px) 100vw, 560px') ?>
      <div class="why-badge"><strong>2008</strong><span>verificando el autotransporte federal en Cancún</span></div>
    </div>
    <div class="why-text">
      <div style="display:flex;flex-direction:column;gap:14px">
        <span class="eyebrow">Mochcun · Centro de Verificación Vehicular Federal en Cancún</span>
        <h2 class="h2">Verificación oportuna, eficiente y en regla</h2>
        <p style="color:var(--ink-2)">La Unidad de Verificación SERVICIOS DE AUTOTRANSPORTE MOCHCUN, S.A. DE C.V. tiene como objetivo contribuir a traer mayor presencia de usuarios al mercado del autotransporte, por ser una empresa comprometida con la calidad y seguridad en el servicio que brinda: la verificación oportuna y eficiente de las condiciones físico-mecánicas del vehículo y la medición de los límites máximos de emisiones contaminantes, con una mejora continua en los procesos de verificación.</p>
      </div>
      <div class="values">
        <div class="value"><strong>Experiencia</strong><span>Nuestros clientes confían en nosotros y se mantienen con nosotros.</span></div>
        <div class="value"><strong>Profesionalismo</strong><span>Personal certificado, con las habilidades para cada servicio.</span></div>
        <div class="value"><strong>Atención</strong><span>Servicio personalizado para resolver tus dudas sobre cada trámite.</span></div>
        <div class="value"><strong>Eficiencia</strong><span>En tiempo y forma, para que cumplas con las normas.</span></div>
      </div>
      <div class="note-box">
        <div><strong>¿Gestor o flotilla?</strong><span>Manejamos convenios por volumen y seguimiento personalizado de tus unidades.</span></div>
        <a class="btn btn-dark btn-sm" href="<?= e(whatsapp_url('Hola, soy gestor/flotilla y quiero información de convenios')) ?>" target="_blank" rel="noopener">Hablemos</a>
      </div>
    </div>
  </div>
</section>

<?php if ($posts): ?>
<section class="section paper" id="blog">
  <div class="wrap">
    <div class="sec-head">
      <div><span class="eyebrow">Blog</span><h2>Guías para el transportista</h2></div>
      <a class="link-arrow" href="/blog/">Ver todos los artículos <?= icon('arrow', 18) ?></a>
    </div>
    <div class="grid-3">
<?php foreach ($posts as $post) echo post_card($post); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="wrap layout">
    <div style="flex:1 1 320px;min-width:0;display:flex;flex-direction:column;gap:16px">
      <span class="eyebrow">Preguntas frecuentes</span>
      <h2 class="h2">Lo que más nos preguntan</h2>
      <a class="link-arrow" href="/faqs/">Ver todas las preguntas <?= icon('arrow', 18) ?></a>
    </div>
    <div class="faq-list" style="flex:2 1 560px;min-width:0">
<?php foreach ($faqs as $i => [$q, $a]) echo faq_item($q, $a, $i === 0); ?>
    </div>
  </div>
</section>

<?php cta_band(); ?>
<?php page_end($page); ?>
