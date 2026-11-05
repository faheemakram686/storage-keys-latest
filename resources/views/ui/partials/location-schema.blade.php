{{-- JSON-LD for a location page; built from the same $loc array as ui.partials.location-page. --}}
@php
    $pageUrl = 'https://storagekeys.com/' . $loc['slug'];
    $cityKey = strtolower($loc['city']);
    $crumbList = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://storagekeys.com']];
    foreach ($loc['crumbs'] as [$crumbSlug, $crumbLabel]) {
        $crumbList[] = ['@type' => 'ListItem', 'position' => count($crumbList) + 1, 'name' => $crumbLabel, 'item' => 'https://storagekeys.com/' . $crumbSlug];
    }
    $crumbList[] = ['@type' => 'ListItem', 'position' => count($crumbList) + 1, 'name' => $loc['crumb'], 'item' => $pageUrl];
    $facilityNode = [
        '@type' => 'SelfStorage',
        '@id' => 'https://storagekeys.com/#storagekeys-' . $cityKey,
        'name' => 'StorageKeys ' . $loc['city'],
        'url' => 'https://storagekeys.com/self-storage-' . $cityKey,
        'telephone' => '+971 56 501 8785',
        'email' => 'sales@storagekeys.com',
        'image' => 'https://storagekeys.com/sk-assets/assets/images/frontend/front-logo.png',
        'address' => array_merge(['@type' => 'PostalAddress', 'addressCountry' => 'AE'], $loc['schemaAddress']),
        'hasMap' => $loc['directions'],
        'openingHours' => 'Mo-Su 00:00-23:59',
        'areaServed' => array_map(function ($c) { return ['@type' => 'City', 'name' => $c]; }, $loc['schemaAreas']),
        'parentOrganization' => ['@id' => 'https://storagekeys.com/#organization'],
    ];
    if (!empty($loc['geo'])) {
        $facilityNode['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => $loc['geo'][0], 'longitude' => $loc['geo'][1]];
    }
@endphp
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        $facilityNode,
        [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
            ], $loc['faqs']),
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $crumbList,
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
