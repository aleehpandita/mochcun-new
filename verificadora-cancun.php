<?php
require __DIR__ . '/app/bootstrap.php';

$page = [
    'title' => 'Nosotros - MOCHCUN Verificadora Federal en Cancún',
    'description' => 'MOCHCUN, verificadora federal en Cancún desde 2008: inspección físico-mecánica (NOM-068-SCT-2-2014) y verificación de emisiones (NOM-045, NOM-047 y NOM-167-SEMARNAT). Acreditada por la EMA y aprobada por la SICT.',
    'path' => '/verificadora-cancun/',
    'breadcrumb' => [['Nosotros', '/verificadora-cancun/']],
    'schema_page_type' => 'AboutPage',
];
page_start($page);
page_hero('MOCHCUN, verificadora federal en Cancún', 'Unidad de verificación tipo “C” en operación desde octubre de 2008, acreditada por la Entidad Mexicana de Acreditación (EMA) y aprobada por la SICT.', [['Nosotros', '/verificadora-cancun/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <div class="block">
        <span class="eyebrow">Nosotros</span>
        <h2>Vehículos en óptimas condiciones para el autotransporte federal</h2>
        <div class="prose">
          <p>Dada la necesidad de contar con vehículos que se encuentren en óptimas condiciones para prestar el servicio de autotransporte de pasaje, turismo, carga y transporte privado en los caminos y puentes de jurisdicción federal, la SCT (hoy SICT) y la SEMARNAT han publicado requisitos y procedimientos para la inspección y vigilancia de las especificaciones normativas en lo que se refiere a las revisiones físico-mecánicas de las unidades vehiculares del autotransporte de pasaje y turismo. Asimismo, se establecen procedimientos técnicos para comparar los valores máximos de opacidad, a lo que se suma la búsqueda de alternativas para optimizar los recursos humanos y materiales de las empresas del autotransporte.</p>
          <p>Estas condiciones permiten realizar un plan para lograr una disminución y/o control de las emisiones contaminantes a la atmósfera, ya que los vehículos del autotransporte las generan permanentemente. Esto nos da una visión clara: <strong>entre mejor sea supervisado y revisado el mantenimiento preventivo de las unidades, mayor será la eficiencia en la operación de los vehículos y, como resultado, menor la generación de emisiones al medio ambiente.</strong></p>
        </div>
      </div>
      <figure class="figure"><?= img('fisico-mecanica', 'Área de inspección de MOCHCUN en Cancún', '(max-width: 960px) 100vw, 800px') ?></figure>
      <div class="block">
        <h2>Objetivos</h2>
        <div class="prose">
          <p>La Unidad de Verificación SERVICIOS DE AUTOTRANSPORTE MOCHCUN, S.A. DE C.V. tiene como objetivo contribuir a traer mayor presencia de usuarios al mercado del autotransporte, por ser una empresa comprometida con la calidad y seguridad en el servicio que brinda en cuanto a la verificación oportuna y eficiente en la revisión de las condiciones físico-mecánicas del vehículo y la medición de los límites máximos en las emisiones contaminantes, reflejándose así su compromiso en materia ambiental; seguido por una mejora continua en los procesos de verificación que busca la funcionalidad y rentabilidad de los mismos.</p>
        </div>
        <div class="panel panel-red">
          <strong>Normas que verificamos</strong>
          <ul class="checks">
            <li><span><span class="nom">NOM-068-SCT-2-2014</span> — Condiciones físico-mecánicas y de seguridad.</span></li>
            <li><span><span class="nom">NOM-045-SEMARNAT-2017</span> — Opacidad de vehículos a diésel.</span></li>
            <li><span><span class="nom">NOM-047-SEMARNAT-2014</span> — Medición de emisiones de vehículos a gasolina y combustibles alternos.</span></li>
            <li><span><span class="nom">NOM-167-SEMARNAT-2017</span> — Límites de emisión para vehículos a gasolina y diésel.</span></li>
          </ul>
          <a class="link-arrow" href="/normas-sct-verificacion/">Consultar las normas <?= icon('arrow', 18) ?></a>
        </div>
      </div>
      <div class="block">
        <h2>Servicios profesionales</h2>
        <blockquote class="prose" style="margin:0;border-left:4px solid var(--red);padding-left:22px;font-size:20px;color:var(--ink)">“Trabajar con responsabilidad para contribuir a mejorar el medio ambiente en el que vivimos, garantizar que los vehículos cumplan con los requisitos de seguridad que permitan su operación eficiente en las carreteras nacionales mediante el uso correcto, eficiente y oportuno de la verificación”.</blockquote>
        <div class="two-col">
          <a class="card" href="/verificacion-emisiones-cancun/"><div class="card-body"><span class="tag">Servicio</span><h3>Baja emisión de contaminantes</h3><span class="card-more">Ver más <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div></a>
          <a class="card" href="/inspeccion-fisico-mecanica-cancun/"><div class="card-body"><span class="tag">Servicio</span><h3>Condiciones físico-mecánicas</h3><span class="card-more">Ver más <?= icon('arrow', 16, 'ico ico-arrow') ?></span></div></a>
        </div>
      </div>
      <div class="block">
        <h2>Por qué elegirnos</h2>
        <div class="values">
          <div class="value"><strong>Experiencia</strong><span>Fundamentamos nuestra experiencia en el mercado en la satisfacción de nuestros clientes y su confianza para mantenerse con nosotros.</span></div>
          <div class="value"><strong>Profesionalismo</strong><span>Nuestro personal está certificado y cuenta con las habilidades para ajustarse a cada servicio.</span></div>
          <div class="value"><strong>Atención</strong><span>Todos nuestros empleados te proporcionarán un servicio completamente personalizado para resolver tus dudas y aclaraciones sobre tu servicio.</span></div>
          <div class="value"><strong>Eficiencia</strong><span>Garantizamos que nuestro servicio será satisfactorio para ti, en el tiempo y la forma requeridos para cumplir con las normas necesarias.</span></div>
        </div>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
