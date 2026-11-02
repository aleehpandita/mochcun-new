<?php
require __DIR__ . '/app/bootstrap.php';

$page = [
    'title' => 'Verificadora Federal de Cancún - Tarifas y requisitos',
    'description' => 'Tarifas y requisitos para la verificación federal de emisiones contaminantes y físico-mecánica en Cancún. Empresa autorizada por la SICT y acreditada por la EMA.',
    'path' => '/tarifas-requisitos/',
    'breadcrumb' => [['Tarifas y requisitos', '/tarifas-requisitos/']],
];
page_start($page);
page_hero('Tarifas y requisitos para emisión de contaminantes y físico-mecánicas', 'Precios con IVA incluido. Te ofrecemos precios competitivos en verificaciones en Cancún.', [['Tarifas y requisitos', '/tarifas-requisitos/']]);
?>
<section class="section">
  <div class="wrap">
    <div class="grid-2">
      <article class="price-card" id="emisiones">
        <div class="price-top">
          <span class="tag" style="color:var(--red-soft)">Frecuencia semestral</span>
          <h2>Baja emisión de contaminantes</h2>
          <p class="price">$544.38 <small>cualquier vehículo · IVA incluido</small></p>
        </div>
        <div class="price-body">
          <p style="color:var(--ink-2)">Garantiza la buena conservación del medio ambiente y de los vehículos de autotransporte. La prueba está definida por las normas oficiales mexicanas <span class="nom">NOM-167-SEMARNAT-2017</span> (diésel y gasolina), <span class="nom">NOM-045-SEMARNAT-2017</span> y <span class="nom">NOM-047-SEMARNAT-2014</span>.</p>
          <div class="block" style="gap:12px">
            <h3>Emplacamiento <span style="font-weight:500;font-size:15px;color:var(--muted)">(1 juego de copias completamente legibles)</span></h3>
            <ul class="checks">
              <li>RFC completo de la persona física o moral con el que se emplacará el vehículo.</li>
              <li>Factura a nombre de la persona física o moral.</li>
              <li>Contrato de arrendamiento completo (en caso de haber).</li>
              <li>Si el vehículo se adquirió a crédito, carta factura y contrato de arrendamiento.</li>
              <li>Vehículo diésel: dos años de gracia a partir de su año de fabricación.</li>
              <li>Copia completamente legible de la tarjeta de circulación federal.</li>
            </ul>
          </div>
          <div class="block" style="gap:12px">
            <h3>Renovación</h3>
            <ul class="checks">
              <li>Copia completamente legible de la tarjeta de circulación federal.</li>
              <li>Original del certificado inmediato anterior de baja emisión de contaminantes.</li>
            </ul>
          </div>
        </div>
      </article>

      <article class="price-card" id="fisico-mecanica">
        <div class="price-top">
          <span class="tag" style="color:var(--red-soft)">Frecuencia anual</span>
          <h2>Condiciones físico-mecánicas</h2>
          <p class="price">$1,060 <small>IVA incluido</small></p>
          <p style="font-size:15px">Vagonetas, sedanes, dolly, tractocamiones, cajas, camiones y autobuses: el mismo precio aplica para todo vehículo, desde un sedán hasta un remolque de seis ejes.</p>
        </div>
        <div class="price-body">
          <p style="color:var(--ink-2)">La inspección físico-mecánica es el procedimiento para asegurarse de que un vehículo o remolque se encuentra en óptimas condiciones de funcionamiento mecánico, de acuerdo con lo establecido en la <span class="nom">NOM-068-SCT-2-2014</span>. Se revisan varios sistemas, como acoplamiento, escape, dirección, frenos, iluminación, suspensión y rodamiento. Al terminar se entrega un dictamen de verificación y un engomado que la avala.</p>
          <p style="color:var(--ink-2)">No contar con la verificación se sanciona con 100 días de salario mínimo, que deben pagarse ante la SICT, según el artículo 14 del Reglamento sobre el peso, dimensiones y capacidad de los vehículos de autotransporte que transitan en los caminos y puentes de jurisdicción federal.</p>
          <div class="block" style="gap:12px">
            <h3>Primera vez</h3>
            <ul class="checks">
              <li>Dos años de gracia, siempre y cuando en el año en curso se cumplan los mismos.</li>
              <li>Recién emplacados: 60 días (resta dos años al año en curso; los modelos por debajo del resultado de la resta son los que tienen este plazo, consultar el DOF).</li>
              <li>Tarjeta de circulación completamente legible.</li>
            </ul>
          </div>
          <div class="block" style="gap:12px">
            <h3>Renovación</h3>
            <ul class="checks">
              <li>Original del certificado de verificación inmediato anterior.</li>
              <li>Copia de la tarjeta de circulación completamente legible.</li>
            </ul>
          </div>
        </div>
      </article>
    </div>
    <div class="note-box" style="margin-top:32px">
      <div><strong>¿Tienes varias unidades o eres gestor?</strong><span>Pregúntanos por convenios por volumen y seguimiento personalizado.</span></div>
      <a class="btn btn-red btn-sm" href="<?= e(whatsapp_url('Hola, quiero información de tarifas por volumen')) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 18) ?> Cotizar por WhatsApp</a>
    </div>
  </div>
</section>
<?php cta_band(); page_end($page); ?>
