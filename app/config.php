<?php
/**
 * Configuración general de mochcun.com
 * Cambia aquí los datos de contacto: se usan en todo el sitio y en los datos estructurados para Google.
 */
return [
    // Dominio canónico (sin diagonal final). Todas las variantes (www, http) redirigen aquí desde .htaccess.
    'site_url'   => 'https://mochcun.com',
    'site_name'  => 'MOCHCUN',
    'legal_name' => 'SERVICIOS DE AUTOTRANSPORTE MOCHCUN, S.A. DE C.V.',

    // Contacto
    'phone_display'    => '998 361 0369',
    'phone_e164'       => '+529983610369',
    'whatsapp_number'  => '529982424394',
    'whatsapp_display' => '998 242 4394',
    'whatsapp_text'    => 'Hola, me interesan los servicios de MOCHCUN',
    'email'            => 'info@mochcun.com',
    'hours_display'    => 'Lunes a viernes, 9:00 am a 5:00 pm',
    'address' => [
        'street'   => 'Av. Kinik Mza. 01 Lote 1-11/1-13, Región 97, Zona Industrial',
        'city'     => 'Cancún',
        'region'   => 'Quintana Roo',
        'postal'   => '77530',
        'country'  => 'MX',
    ],
    'maps_query' => 'Servicios de Autotransporte Mochcun, Cancún, Quintana Roo',
    'facebook'   => 'https://www.facebook.com/verificadora.emisiones.fisico.mecanica.cancun/',

    // Analítica (los mismos identificadores del sitio anterior)
    'gtm_id' => 'GTM-MNFQFQ7',
    // Déjalo vacío si Google Analytics ya se dispara desde Tag Manager, para no contar doble.
    'ga4_id' => '',

    // Correo del formulario de contacto
    'mail_to'   => 'info@mochcun.com',
    'mail_from' => 'info@mochcun.com',

    // Base de datos del blog y mensajes (SQLite, protegida por .htaccess)
    'db_path' => __DIR__ . '/../storage/mochcun.sqlite',

    // Blog
    'posts_per_page' => 9,
    'blog_categories' => [
        'licencia-federal' => 'Licencia federal',
        'fisico-mecanica'  => 'Físico-mecánica',
        'emisiones'        => 'Emisiones',
        'placas-federales' => 'Placas federales',
        'tramites-sict'    => 'Trámites SICT',
        'noticias'         => 'Noticias',
    ],

    // En producción debe ser false
    'debug' => false,
];
