{{--
    Sitewide business structured data (JSON-LD).
    - Every page: Organization + WebSite, so Google knows who runs the site.
    - Home, About and Contact: the two facilities as SelfStorage (a LocalBusiness subtype).
    The facility @ids match the ones in ui.partials.location-schema, so Google treats the
    entities on the home page and on /self-storage-dubai and /self-storage-sharjah as the same businesses.
    Facility details come from App\Support\LocationPages::facilities(), the same source as the location pages.
--}}
@php
    $base = 'https://storagekeys.com';
    $orgId = $base . '/#organization';

    $graph = [
        [
            '@type' => 'Organization',
            '@id' => $orgId,
            'name' => 'StorageKeys',
            'alternateName' => 'Storage Keys',
            'url' => $base,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $base . '/sk-assets/assets/images/frontend/front-logo.png',
            ],
            'email' => 'sales@storagekeys.com',
            'telephone' => '+971 56 501 8785',
            'contactPoint' => [
                [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer service',
                    'telephone' => '+971 56 501 8785',
                    'email' => 'sales@storagekeys.com',
                    'areaServed' => 'AE',
                    'availableLanguage' => ['English', 'Arabic'],
                ],
                [
                    '@type' => 'ContactPoint',
                    'contactType' => 'sales',
                    'telephone' => '800 5397',
                    'areaServed' => 'AE',
                ],
            ],
            'sameAs' => [
                'https://www.facebook.com/storagekeysllc',
                'https://www.linkedin.com/company/storage-keys-uae',
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $base . '/#website',
            'url' => $base,
            'name' => 'StorageKeys',
            'publisher' => ['@id' => $orgId],
            'inLanguage' => 'en',
        ],
    ];

    $showFacilities = ($isHome ?? false) || request()->is('about-us') || request()->is('contact-us');

    if ($showFacilities && class_exists(\App\Support\LocationPages::class)) {
        $facilityIds = [];
        foreach (\App\Support\LocationPages::facilities() as $key => $f) {
            $id = $base . '/#storagekeys-' . $key;
            $facilityIds[] = ['@id' => $id];
            $node = [
                '@type' => 'SelfStorage',
                '@id' => $id,
                'name' => 'StorageKeys ' . $f['city'],
                'url' => $base . '/' . \App\Support\LocationPages::cityPageSlug($key),
                'image' => $base . '/sk-assets/assets/images/frontend/front-logo.png',
                'telephone' => '+971 56 501 8785',
                'email' => 'sales@storagekeys.com',
                'address' => array_merge(['@type' => 'PostalAddress', 'addressCountry' => 'AE'], $f['schemaAddress']),
                'hasMap' => $f['directions'],
                'openingHoursSpecification' => [[
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '00:00',
                    'closes' => '23:59',
                ]],
                'areaServed' => array_map(function ($c) {
                    return ['@type' => 'City', 'name' => $c];
                }, $f['schemaAreas']),
                'parentOrganization' => ['@id' => $orgId],
            ];
            if (!empty($f['geo'])) {
                $node['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => $f['geo'][0], 'longitude' => $f['geo'][1]];
            }
            $graph[] = $node;
        }
        $graph[0]['subOrganization'] = $facilityIds;
    }
@endphp
<script type="application/ld+json">
{!! json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
