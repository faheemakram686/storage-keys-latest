@extends('ui.layouts.frontend')
@section('title', '| Self Storage in Sharjah')
@section('metaTitle', 'Self Storage in Sharjah | StorageKeys')
@section('metaDescription', 'Affordable, secure self storage in Sharjah for homes and businesses. 24/7 access, CCTV, collection service and flexible terms at our main facility. Get a free quote.')

@php
    $loc = [
        'slug' => 'self-storage-sharjah',
        'city' => 'Sharjah',
        'crumb' => 'Self Storage in Sharjah',
        'eyebrow' => 'Sharjah — Main Facility',
        'h1' => 'Self Storage in Sharjah',
        'h1Accent' => 'Secure, Affordable Units',
        'lead' => 'Secure storage units at our main Sharjah facility for households, students, expats and businesses across Sharjah, Ajman and Dubai, with 24/7 access, CCTV, collection from your door and flexible terms.',
        'facilityName' => 'Storage Keys — Sharjah',
        'addressHtml' => 'Plot # 4202<br>Sharjah, UAE',
        'schemaAddress' => ['streetAddress' => 'Plot # 4202', 'addressLocality' => 'Sharjah', 'addressRegion' => 'Sharjah'],
        'schemaAreas' => ['Sharjah', 'Ajman'],
        'directions' => 'https://maps.app.goo.gl/vvWVRRKCtBnups2k6',
        'mapEmbed' => 'https://maps.google.com/maps?q=Storage%20Keys%2C%20Plot%20%23%204202%20-%20Sharjah%20-%20United%20Arab%20Emirates&z=15&output=embed',
        'image' => 'sk-assets/assets/images/frontend/landing-page/peace-of-mind-80kb.jpg',
        'overviewTitle' => 'Our Main Storage Facility in Sharjah',
        'overview' => [
            'StorageKeys has served customers from Sharjah since 2016. Our main facility and office at Plot # 4202 is where most of our storage, moving and customer service is run from.',
            'Space in Sharjah generally costs less than in central Dubai, so it is a practical choice for long-term storage, larger units and businesses holding bulk stock, and close to home for the many families who live in Sharjah and work in Dubai.',
        ],
        'ticks' => ['24/7 access', 'CCTV & controlled entry', 'Our main office on site', 'Collection from your door', 'Flexible monthly terms', 'Cost-effective larger units'],
        'servicesIntro' => 'One facility, many uses. Every service below is available from our Sharjah units.',
        'services' => [
            ['personal-storage', 'Personal Storage', 'fa-user', 'Belongings, boxes and seasonal items while you move, travel or declutter.'],
            ['residential-storage', 'Residential Storage', 'fa-building', 'The contents of a flat or villa between tenancies or during a move.'],
            ['business-storage', 'Business Storage', 'fa-briefcase', 'Stock, archives and equipment without a long warehouse lease.'],
            ['warehouse-storage', 'Warehouse Storage', 'fa-warehouse', 'Bulk inventory and distribution space for the Northern Emirates.'],
            ['equipment-storage', 'Equipment Storage', 'fa-wrench', 'Tools, machinery and site equipment between projects.'],
            ['long-term-storage', 'Long Term Storage', 'fa-calendar-alt', "Months or years of storage at Sharjah's lower monthly cost."],
            ['car-storage', 'Car Storage', 'fa-car', 'Secure parking for your car while you are away for the summer.'],
            ['moving-services', 'Moving Services', 'fa-truck-moving', 'Collection from Sharjah, Ajman or Dubai, straight to your unit.'],
        ],
        'visitTitle' => 'Find Us in Sharjah',
        'visitText' => 'Our team works from this facility, so you can view units, sign paperwork and drop off belongings in one visit. Call ahead and we will have your unit ready.',
        'gettingHere' => 'Use the Get Directions button for turn-by-turn directions, or call us and we will guide you in.',
        'whyTitle' => 'Why Sharjah Residents and Businesses Choose Us',
        'why' => [
            ['fa-tags', 'Lower Cost Than Central Dubai', 'Larger units and long-term storage stay affordable without cutting security.'],
            ['fa-shield-alt', 'Secure Around the Clock', '24/7 CCTV and controlled entry. Only you and people you authorise get in.'],
            ['fa-headset', 'Main Office On Site', 'Questions, viewings and paperwork are handled in person by our team.'],
            ['fa-award', 'Serving Since 2016', 'Years of helping UAE households and businesses move and store.'],
        ],
        'areasTitle' => 'Storage Across Sharjah and Ajman',
        'areas' => [
            'Central Sharjah' => ['Al Majaz', 'Al Nahda', 'Al Taawun', 'Al Khan', 'Al Qasimia', 'Abu Shagara'],
            'Wider Sharjah' => ['Muwaileh', 'University City', 'Al Rahmaniya', 'Aljada', 'Industrial Areas', 'Al Sajaa'],
            'Ajman & Dubai Border' => ['Ajman', 'Al Qusais', 'Al Nahda Dubai', 'Muhaisnah', 'Mirdif'],
        ],
        'quoteText' => 'Tell us what you need to store and for how long. We will recommend the right unit at our Sharjah facility and send a clear quote.',
        'faqs' => [
            ['Where is your Sharjah storage facility?', 'Our main facility and office are at Plot # 4202 in Sharjah. Use the Get Directions button on this page or call us and we will guide you in.'],
            ['Is storage in Sharjah cheaper than in Dubai?', 'Space in Sharjah generally costs less than in central Dubai, which makes our Sharjah facility a cost-effective choice for long-term storage, bulk stock and larger units.'],
            ['Can I access my unit at any time?', 'Yes. The Sharjah facility offers 24/7 access for customers, with CCTV and controlled entry protecting every unit.'],
            ['Do you collect from homes and offices in Sharjah, Ajman and Dubai?', 'Yes. Our moving team collects from homes and offices across Sharjah, Ajman and Dubai and brings everything to your unit, or you can drop items off yourself.'],
        ],
    ];
@endphp

@section('headExtra')
    @include('ui.partials.location-schema', ['loc' => $loc])
@endsection

@section('content')
    @include('ui.partials.location-page', ['loc' => $loc])
@endsection
