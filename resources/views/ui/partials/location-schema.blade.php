{{-- JSON-LD for a location page; built from the same $loc array as ui.partials.location-page. --}}
@php
    $pageUrl = 'https://storagekeys.com/' . $loc['slug'];
@endphp
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'SelfStorage',
            'name' => $loc['facilityName'],
            'url' => $pageUrl,
            'telephone' => '+971 56 501 8785',
            'email' => 'sales@storagekeys.com',
            'image' => 'https://storagekeys.com/sk-assets/assets/images/frontend/front-logo.png',
            'address' => array_merge(['@type' => 'PostalAddress', 'addressCountry' => 'AE'], $loc['schemaAddress']),
            'hasMap' => $loc['directions'],
            'openingHours' => 'Mo-Su 00:00-23:59',
            'areaServed' => array_map(fn ($c) => ['@type' => 'City', 'name' => $c], $loc['schemaAreas']),
            'parentOrganization' => [
                '@type' => 'Organization',
                'name' => 'StorageKeys',
                'url' => 'https://storagekeys.com',
            ],
        ],
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
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://storagekeys.com'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $loc['crumb'], 'item' => $pageUrl],
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
