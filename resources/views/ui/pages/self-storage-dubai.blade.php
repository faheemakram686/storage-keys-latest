@extends('ui.layouts.frontend')
@section('title', '| Self Storage in Dubai')
@section('metaTitle', 'Self Storage in Dubai, Al Quoz | StorageKeys')
@section('metaDescription', 'Secure self storage in Al Quoz, Dubai for homes and businesses. 24/7 access, CCTV and climate-controlled units with flexible terms. Get a free quote today.')

@php
    $loc = [
        'slug' => 'self-storage-dubai',
        'city' => 'Dubai',
        'crumb' => 'Self Storage in Dubai',
        'eyebrow' => 'Al Quoz, Dubai',
        'h1' => 'Self Storage in Dubai',
        'h1Accent' => 'Secure Units in Al Quoz',
        'lead' => 'Secure, climate-controlled storage units in Al Quoz Industrial Area 3 for households, expats and businesses across Dubai, with 24/7 access, CCTV, collection from your door and flexible terms.',
        'facilityName' => 'Storage Keys — Dubai',
        'addressHtml' => '10A Street, Al Quoz Industrial Area 3<br>Al Quoz, Dubai, UAE',
        'schemaAddress' => ['streetAddress' => '10A Street, Al Quoz Industrial Area 3', 'addressLocality' => 'Al Quoz, Dubai', 'addressRegion' => 'Dubai'],
        'schemaAreas' => ['Dubai'],
        'directions' => 'https://maps.app.goo.gl/cZhb4mLPGVsGcooA9',
        'mapEmbed' => 'https://maps.google.com/maps?q=Storage%20Keys%2C%2010A%20Street%20-%20Al%20Qouz%20Ind.third%20-%20Al%20Quoz%20-%20Dubai&z=15&output=embed',
        'image' => 'sk-assets/assets/images/frontend/landing-page/corridor area-80kb.jpg',
        'overviewTitle' => 'Storage Space in the Heart of Dubai',
        'overview' => [
            'Dubai homes are getting smaller and commercial rents keep rising, so finding room for furniture, seasonal items, stock or archives is a constant challenge. A storage unit gives you that space without moving house or signing a bigger lease.',
            'Our Dubai facility sits on 10A Street in Al Quoz Industrial Area 3, a short drive from Sheikh Zayed Road and Al Khail Road, within easy reach of Downtown, Business Bay, Al Barsha, JLT and Dubai Marina.',
        ],
        'ticks' => ['24/7 access', 'CCTV & controlled entry', 'Climate-controlled units', 'Collection from your door', 'Flexible monthly terms', 'Units for homes & businesses'],
        'servicesIntro' => 'One facility, many uses. Every service below is available from our Al Quoz units.',
        'services' => [
            ['personal-storage', 'Personal Storage', 'fa-user', 'Belongings, boxes and seasonal items while you move, travel or declutter.'],
            ['furniture-storage', 'Furniture Storage', 'fa-couch', 'Sofas, beds and wardrobes kept safe during a move or renovation.'],
            ['business-storage', 'Business Storage', 'fa-briefcase', 'Stock, archives and equipment without a long warehouse lease.'],
            ['office-storage', 'Office Storage', 'fa-chair', 'Office furniture and files during relocations and fit-outs.'],
            ['car-storage', 'Car Storage', 'fa-car', 'Secure parking for your car while you are away for the summer.'],
            ['luggage-storage', 'Luggage Storage', 'fa-suitcase-rolling', 'Suitcases and travel gear between trips or stays in Dubai.'],
            ['climate-controlled-storage', 'Climate-Controlled', 'fa-snowflake', 'Protection from heat and humidity for art, electronics and wood.'],
            ['moving-services', 'Moving Services', 'fa-truck-moving', 'Collection from anywhere in Dubai, straight to your unit.'],
        ],
        'visitTitle' => 'Find Us in Al Quoz',
        'visitText' => 'Drop off your belongings yourself or book a collection. Call ahead and we will have your unit ready when you arrive.',
        'gettingHere' => 'Minutes from Sheikh Zayed Road and Al Khail Road, with easy truck and van access for loading.',
        'whyTitle' => 'Why Dubai Residents and Businesses Choose Us',
        'why' => [
            ['fa-map-marked-alt', 'Central Location', 'Al Quoz is minutes from Sheikh Zayed Road, so drop-offs fit into a normal day.'],
            ['fa-shield-alt', 'Secure Around the Clock', '24/7 CCTV and controlled entry. Only you and people you authorise get in.'],
            ['fa-snowflake', 'Built for the UAE Climate', 'Climate-controlled units keep furniture, electronics and documents safe in summer.'],
            ['fa-sliders-h', 'Flexible, Clear Pricing', 'No long fixed lease. Pay for the space you use and resize any time.'],
        ],
        'areasTitle' => 'Storage Across Dubai',
        'areas' => [
            'Central Dubai' => ['Al Quoz', 'Al Barsha', 'Downtown', 'Business Bay', 'DIFC', 'Jumeirah', 'Umm Suqeim'],
            'New Dubai' => ['Dubai Marina', 'JLT', 'JBR', 'Palm Jumeirah', 'Dubai Hills', 'JVC', 'Arabian Ranches', 'Motor City'],
            'Old Dubai & East' => ['Deira', 'Bur Dubai', 'Al Qusais', 'Mirdif', 'Silicon Oasis', 'International City'],
        ],
        'quoteText' => 'Tell us what you need to store and for how long. We will recommend the right unit at our Al Quoz facility and send a clear quote.',
        'faqs' => [
            ['Where is your Dubai storage facility?', 'Our Dubai facility is on 10A Street in Al Quoz Industrial Area 3, close to Sheikh Zayed Road and Al Khail Road, so it is easy to reach from most of the city.'],
            ['Can I access my unit at any time?', 'Yes. The Dubai facility offers 24/7 access for customers, with CCTV and controlled entry protecting every unit.'],
            ['Do you collect items from homes and offices in Dubai?', 'Yes. Our moving team can collect from your home or office anywhere in Dubai and bring everything to your unit, or you can drop items off yourself.'],
            ['How long do I need to book storage for?', 'Terms are flexible. You can store for a few weeks during a move or renovation, or for months and years, and change your unit size as your needs change.'],
        ],
    ];
@endphp

@section('headExtra')
    @include('ui.partials.location-schema', ['loc' => $loc])
@endsection

@section('content')
    @include('ui.partials.location-page', ['loc' => $loc])
@endsection
