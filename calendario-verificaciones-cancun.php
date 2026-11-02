<?php
require __DIR__ . '/app/bootstrap.php';

$page = [
    'title' => 'Calendario de Verificación Federal - Verificadora Federal de Cancún',
    'description' => 'Calendario para la verificación federal físico-mecánica y de emisiones contaminantes en Cancún. Consulta en qué meses te toca según el dígito de tu placa federal o estatal.',
    'path' => '/calendario-verificaciones-cancun/',
    'breadcrumb' => [['Calendario', '/calendario-verificaciones-cancun/']],
];
page_start($page);
page_hero('Calendario de verificación', 'Busca en qué meses de este año debes realizar tu verificación físico-mecánica, dependiendo de la placa de tu vehículo.', [['Calendario', '/calendario-verificaciones-cancun/']]);
?>
<section class="section-sm paper">
  <div class="wrap">
    <div class="plate-tool">
      <h2 style="font-size:24px">¿En qué meses me toca la físico-mecánica?</h2>
      <form id="plate-form" class="plate-form" novalidate>
        <div class="field">
          <label for="tipo">Tipo de placa</label>
          <select id="tipo" name="tipo">
            <option value="federal">Federal</option>
            <option value="estatal">Estatal</option>
          </select>
        </div>
        <div class="field">
          <label for="placa">Tu placa</label>
          <input id="placa" name="placa" type="text" inputmode="text" autocomplete="off" placeholder="Ej. 21RA4Z3" maxlength="12">
        </div>
        <button class="btn btn-red" type="submit">Buscar meses</button>
      </form>
      <p id="plate-result" class="plate-result" aria-live="polite"></p>
    </div>
  </div>
</section>

<section class="section dark on-dark">
  <div class="wrap cal">
    <div class="cal-side">
      <span class="eyebrow">Esquema de verificación</span>
      <h2 class="h2">Inspección físico-mecánica (anual)</h2>
      <p>La primera se realiza dos años después del año del modelo del vehículo. Una vez realizada la primera verificación físico-mecánica, debe realizarse cada año dependiendo del dígito tomado de la placa del vehículo y del tipo de placa (federal o estatal). Aplica igual para diésel y gasolina.</p>
      <div class="box-line">
        <span><strong>Vehículos federales:</strong> se toma el tercer dígito numérico de la placa. Ejemplo: en la placa 21RA4Z3 el dígito es el <strong>4</strong>.</span>
        <span><strong>Vehículos estatales:</strong> se toma el último dígito numérico de la placa. Con la misma placa, 21RA4Z3, el dígito sería el <strong>3</strong>.</span>
      </div>
    </div>
    <div class="cal-main">
      <?php calendar_table(); ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap layout">
    <div class="main-col">
      <div class="block">
        <h2>Verificación de humo (emisiones): diésel y gasolina</h2>
        <div class="prose">
          <p>¿Es hora del chequeo de humos de tu vehículo? Cada vehículo registrado ante la SICT debe pasar una prueba de humos cada 6 meses. Se puede realizar en cualquier fecha de cada periodo, sin importar la numeración de la placa:</p>
          <ul class="checks">
            <li><strong>1er semestre:</strong>&nbsp;del 1 de enero al 30 de junio.</li>
            <li><strong>2º semestre:</strong>&nbsp;del 1 de julio al 31 de diciembre.</li>
          </ul>
        </div>
      </div>
      <div class="block">
        <h2>Otros tipos de placa</h2>
        <div class="prose">
          <p>Para los vehículos que prestan los servicios auxiliares de paquetería y mensajería corresponde el cuarto carácter numérico, contando de izquierda a derecha. Ejemplo: en 4683-ZP el dígito es el “3”.</p>
          <p>Para los vehículos con permiso de transporte privado con placas emitidas por entidades federativas corresponde el último carácter numérico. Ejemplos: ABC-1986, el dígito es el “6”; 5682-AA, el “2”; AA-86724, el “4”. Para las placas no consideradas en los casos anteriores se toma en cuenta el último carácter numérico.</p>
        </div>
      </div>
    </div>
    <?php service_sidebar(); ?>
  </div>
</section>
<?php cta_band('¿Ya te toca verificar?', 'Escríbenos con tu placa y te confirmamos el periodo y los requisitos.'); page_end($page); ?>
