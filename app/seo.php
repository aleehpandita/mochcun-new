<?php
declare(strict_types=1);

/** Nodo JSON-LD del negocio (se reutiliza en todas las páginas por su @id). */
function schema_business(): array
{
    $a = config('address');
    return [
        '@type' => ['AutomotiveBusiness', 'LocalBusiness'],
        '@id' => url('/#negocio'),
        'name' => 'MOCHCUN - Verificadora Federal en Cancún',
        'legalName' => config('legal_name'),
        'alternateName' => ['Servicios de Autotransporte Mochcun', 'Verificentro Mochcun'],
        'description' => 'Unidad de verificación acreditada por la EMA y aprobada por la SICT: inspección físico-mecánica y verificación de emisiones contaminantes para autotransporte federal en Cancún y la Riviera Maya.',
        'url' => url('/'),
        'logo' => url('/assets/img/icon-512.png'),
        'image' => url('/assets/img/og-mochcun.jpg'),
        'telephone' => config('phone_e164'),
        'email' => config('email'),
        'foundingDate' => '2008-10',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $a['street'],
            'addressLocality' => $a['city'],
            'addressRegion' => $a['region'],
            'postalCode' => $a['postal'],
            'addressCountry' => $a['country'],
        ],
        'openingHoursSpecification' => [[
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '09:00',
            'closes' => '17:00',
        ]],
        'areaServed' => [
            ['@type' => 'City', 'name' => 'Cancún'],
            ['@type' => 'Place', 'name' => 'Riviera Maya'],
            ['@type' => 'State', 'name' => 'Quintana Roo'],
        ],
        'contactPoint' => [[
            '@type' => 'ContactPoint',
            'telephone' => config('phone_e164'),
            'contactType' => 'customer service',
            'availableLanguage' => 'es',
        ]],
        'sameAs' => [config('facebook'), config('instagram')],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Servicios',
            'itemListElement' => array_map(fn ($s) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $s[0], 'url' => url($s[1])]], services_list()),
        ],
    ];
}

function services_list(): array
{
    return [
        ['Inspección físico-mecánica', '/inspeccion-fisico-mecanica-cancun/'],
        ['Verificación de emisiones contaminantes', '/verificacion-emisiones-cancun/'],
        ['Medicina preventiva', '/medicina-preventiva-cancun/'],
        ['Gestoría de licencia federal', '/gestoria-licencia-federal-cancun/'],
        ['Gestoría de placas federales de turismo', '/gestoria-placas-federales-turismo-cancun/'],
        ['Centro de capacitación', '/centro-capacitacion/'],
    ];
}

/** Imprime las etiquetas <head> de SEO de la página. */
function seo_head(array $p): void
{
    $canonical = url($p['path'] ?? current_path());
    $title = $p['title'];
    $desc = $p['description'] ?? '';
    $image = $p['image'] ?? url('/assets/img/og-mochcun.jpg');
    $type = $p['og_type'] ?? 'website';

    echo '<title>' . e($title) . "</title>\n";
    if ($desc !== '') {
        echo '<meta name="description" content="' . e($desc) . "\">\n";
    }
    echo '<meta name="robots" content="' . (!empty($p['noindex']) ? 'noindex,follow' : 'index,follow,max-image-preview:large') . "\">\n";
    echo '<link rel="canonical" href="' . e($canonical) . "\">\n";
    if (!empty($p['prev'])) {
        echo '<link rel="prev" href="' . e(url($p['prev'])) . "\">\n";
    }
    if (!empty($p['next'])) {
        echo '<link rel="next" href="' . e(url($p['next'])) . "\">\n";
    }
    echo '<meta property="og:locale" content="es_MX">' . "\n";
    echo '<meta property="og:site_name" content="MOCHCUN Verificadora Federal en Cancún">' . "\n";
    echo '<meta property="og:type" content="' . e($type) . "\">\n";
    echo '<meta property="og:title" content="' . e($p['og_title'] ?? $title) . "\">\n";
    if ($desc !== '') {
        echo '<meta property="og:description" content="' . e($desc) . "\">\n";
    }
    echo '<meta property="og:url" content="' . e($canonical) . "\">\n";
    echo '<meta property="og:image" content="' . e($image) . "\">\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    if (!empty($p['published'])) {
        echo '<meta property="article:published_time" content="' . e(date('c', strtotime($p['published']))) . "\">\n";
    }
    if (!empty($p['modified'])) {
        echo '<meta property="article:modified_time" content="' . e(date('c', strtotime($p['modified']))) . "\">\n";
    }

    // JSON-LD
    $graph = [
        [
            '@type' => 'WebSite',
            '@id' => url('/#sitio'),
            'url' => url('/'),
            'name' => 'MOCHCUN',
            'inLanguage' => 'es-MX',
            'publisher' => ['@id' => url('/#negocio')],
        ],
        schema_business(),
        [
            '@type' => $p['schema_page_type'] ?? 'WebPage',
            '@id' => $canonical . '#pagina',
            'url' => $canonical,
            'name' => $title,
            'description' => $desc,
            'inLanguage' => 'es-MX',
            'isPartOf' => ['@id' => url('/#sitio')],
            'about' => ['@id' => url('/#negocio')],
        ],
    ];
    if (!empty($p['breadcrumb'])) {
        $items = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => url('/')]];
        $i = 2;
        foreach ($p['breadcrumb'] as [$name, $path]) {
            $items[] = ['@type' => 'ListItem', 'position' => $i++, 'name' => $name, 'item' => url($path)];
        }
        $graph[] = ['@type' => 'BreadcrumbList', '@id' => $canonical . '#migas', 'itemListElement' => $items];
    }
    foreach ($p['schema'] ?? [] as $node) {
        $graph[] = $node;
    }
    $json = json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
    echo '<script type="application/ld+json">' . $json . "</script>\n";
}

/** Nodo Service para páginas de servicio. */
function schema_service(string $name, string $path, string $description): array
{
    return [
        '@type' => 'Service',
        '@id' => url($path) . '#servicio',
        'name' => $name,
        'description' => $description,
        'url' => url($path),
        'provider' => ['@id' => url('/#negocio')],
        'areaServed' => ['@type' => 'City', 'name' => 'Cancún'],
    ];
}

/** Nodo FAQPage a partir de [[pregunta, respuesta_html], ...]. */
function schema_faq(array $faqs, string $path): array
{
    return [
        '@type' => 'FAQPage',
        '@id' => url($path) . '#preguntas',
        'mainEntity' => array_map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f[0],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($f[1], '<p><ul><ol><li><strong><a><br>'))],
        ], $faqs),
    ];
}
