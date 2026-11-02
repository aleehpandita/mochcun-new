<?php
require __DIR__ . '/app/bootstrap.php';

$page = [
    'title' => 'Verificadora Federal de Cancún - Políticas',
    'description' => 'Políticas y objetivos de calidad de MOCHCUN, unidad de verificación aprobada por la SICT y acreditada por la EMA en Cancún.',
    'path' => '/politicas/',
    'breadcrumb' => [['Políticas de calidad', '/politicas/']],
];
page_start($page);
page_hero('Políticas de la empresa MOCHCUN', 'Ética, profesionalismo, confidencialidad y honestidad en cada verificación.', [['Políticas de calidad', '/politicas/']]);
?>
<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <div class="block">
        <h2>Políticas de la empresa</h2>
        <div class="prose">
          <p>Ofrecer servicios para verificación de Normas Oficiales Mexicanas mediante el funcionamiento eficiente y seguro de los instrumentos o equipos de medición, con la finalidad de verificar el funcionamiento y operación adecuada del vehículo o sus componentes, cumpliendo con los requisitos del cliente.</p>
          <p>En SERVICIOS DE AUTOTRANSPORTE MOCHCUN, S.A. DE C.V. estamos obligados a realizar nuestras funciones bajo un estricto apego a la normatividad que se especifica en los códigos de ética y conducta que intervienen en el proceso operativo de la unidad de verificación.</p>
          <p>Efectuamos métodos y procedimientos probados para prestar un servicio de calidad y funcionamiento eficaz, actuando siempre en los más estrictos principios de ética, profesionalismo, confidencialidad y honestidad en el desempeño de nuestras funciones.</p>
        </div>
      </div>
      <div class="panel panel-red">
        <h2 style="font-size:26px">Objetivos de calidad</h2>
        <ul class="checks">
          <li>Ofrecer servicios de verificación que garanticen la confiabilidad y operatividad de los vehículos, mediante procedimientos establecidos y que cumplan con los requisitos para la evaluación de la conformidad de las normas en aplicación, para que el trabajo realizado asegure la funcionalidad operativa de los vehículos.</li>
          <li>Mantener los procesos y procedimientos que se aplican para la verificación de los vehículos y optimizar la eficiencia y la confiabilidad en su revisión, con la finalidad de cumplir con el 100 % de validez de funcionalidad del vehículo verificado.</li>
          <li>Dictaminar el cumplimiento de requisitos de manera objetiva y confiable, para que sea canalizado al cliente y este tome las acciones necesarias para el cumplimiento de la norma a evaluar.</li>
          <li>Mantener una administración eficiente de los registros y dictámenes de verificación que permitan observar la veracidad, independencia, confiabilidad, imparcialidad y seguridad de la información generada por la unidad de verificación.</li>
        </ul>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
