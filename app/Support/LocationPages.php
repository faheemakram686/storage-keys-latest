<?php

namespace App\Support;

/**
 * Content for the location pages: one page per city (/self-storage-dubai, /self-storage-sharjah)
 * and one page per service in each city (/personal-storage-dubai, /warehouse-storage-sharjah, ...).
 *
 * page($slug) returns the array rendered by resources/views/ui/pages/location.blade.php.
 */
class LocationPages
{
    public const CITIES = ['dubai', 'sharjah'];

    public static function slugs(): array
    {
        $slugs = [];
        foreach (self::CITIES as $city) {
            $slugs[] = self::cityPageSlug($city);
            foreach (array_keys(self::services()) as $service) {
                $slugs[] = self::serviceSlug($service, $city);
            }
        }

        return $slugs;
    }

    public static function cityPageSlug(string $city): string
    {
        return 'self-storage-' . $city;
    }

    public static function serviceSlug(string $service, string $city): string
    {
        return $service . '-' . $city;
    }

    /**
     * Header "Locations" menu: [city label => [[slug, label, icon], ...]].
     */
    public static function menu(): array
    {
        $menu = [];
        foreach (self::CITIES as $city) {
            $name = self::facilities()[$city]['city'];
            $links = [[self::cityPageSlug($city), 'Self Storage ' . $name, 'fa-th-large']];
            foreach (self::services() as $key => $service) {
                $label = $key === 'warehouse-storage' ? 'Warehouse ' . $name : $service['menuLabel'];
                $links[] = [self::serviceSlug($key, $city), $label, $service['icon']];
            }
            $menu[$name] = $links;
        }

        return $menu;
    }

    public static function page(string $slug): ?array
    {
        foreach (self::CITIES as $city) {
            if ($slug === self::cityPageSlug($city)) {
                return self::buildCityPage($city);
            }
            foreach (array_keys(self::services()) as $service) {
                if ($slug === self::serviceSlug($service, $city)) {
                    return self::buildServicePage($service, $city);
                }
            }
        }

        return null;
    }

    // ------------------------------------------------------------------
    // Builders
    // ------------------------------------------------------------------

    private static function buildCityPage(string $city): array
    {
        $facility = self::facilities()[$city];
        $copy = self::cityCopy()[$city];
        $slug = self::cityPageSlug($city);

        $services = [];
        foreach (self::services() as $key => $service) {
            $services[] = [self::serviceSlug($key, $city), $service['label'], $service['icon'], $service['cardText']];
        }

        return array_merge($facility, $copy, [
            'slug' => $slug,
            'crumbs' => [],
            'crumb' => 'Self Storage in ' . $facility['city'],
            'h1' => 'Self Storage in ' . $facility['city'],
            'storageType' => null,
            'servicesEyebrow' => 'Storage in ' . $facility['city'],
            'servicesTitle' => 'Storage Services in ' . $facility['city'],
            'servicesIntro' => 'Every service below is available from our ' . $facility['shortName'] . ' facility. Choose one to see how it works in ' . $facility['city'] . '.',
            'services' => $services,
            'quoteTitle' => 'Get a Free Storage Quote in ' . $facility['city'],
            'compareTitle' => 'Two Facilities, One Team',
            'compareIntro' => 'Choose the facility closest to you. Same security, same service and one point of contact for both.',
            'compare' => array_map(static fn ($c) => [
                'slug' => self::cityPageSlug($c),
                'city' => self::facilities()[$c]['city'],
                'title' => self::facilities()[$c]['facilityName'],
                'text' => self::facilities()[$c]['compareText'],
                'cta' => 'View ' . self::facilities()[$c]['city'] . ' Storage',
            ], self::CITIES),
        ]);
    }

    private static function buildServicePage(string $service, string $city): array
    {
        $facility = self::facilities()[$city];
        $base = self::services()[$service];
        $copy = self::serviceCopy()[$service][$city];
        $slug = self::serviceSlug($service, $city);

        $others = [[self::cityPageSlug($city), 'Self Storage ' . $facility['city'], 'fa-th-large', 'All storage options at our ' . $facility['shortName'] . ' facility.']];
        foreach (self::services() as $key => $other) {
            if ($key !== $service) {
                $others[] = [self::serviceSlug($key, $city), $other['label'], $other['icon'], $other['cardText']];
            }
        }

        return array_merge($facility, [
            'slug' => $slug,
            'crumbs' => [[self::cityPageSlug($city), 'Self Storage in ' . $facility['city']]],
            'crumb' => $base['label'] . ' in ' . $facility['city'],
            'eyebrow' => $base['label'] . ' · ' . $facility['shortName'],
            'h1' => $base['label'] . ' in ' . $facility['city'],
            'storageType' => $base['storageType'],
            'image' => $base['image'],
            'ticks' => $base['ticks'],
            'itemsTitle' => $base['itemsTitle'] . ' in ' . $facility['city'],
            'itemsIntro' => $base['itemsIntro'],
            'items' => $base['items'],
            'why' => $base['why'],
            'whyTitle' => 'Why Customers in ' . $facility['city'] . ' Choose StorageKeys',
            'servicesEyebrow' => 'More in ' . $facility['city'],
            'servicesTitle' => 'Other Storage Services in ' . $facility['city'],
            'servicesIntro' => 'Need to store something else? These services are available from the same facility.',
            'services' => $others,
            'compareTitle' => 'Available at Both Our Facilities',
            'compareIntro' => 'The same service is available at both of our facilities. Choose the one closest to you.',
            'compare' => array_map(static fn ($c) => [
                'slug' => self::serviceSlug($service, $c),
                'city' => self::facilities()[$c]['city'],
                'title' => $base['label'] . ' in ' . self::facilities()[$c]['city'],
                'text' => self::facilities()[$c]['compareText'],
                'cta' => 'View ' . $base['label'] . ' in ' . self::facilities()[$c]['city'],
            ], self::CITIES),
            'quoteTitle' => 'Get a Free ' . $base['label'] . ' Quote',
            'quoteText' => 'Tell us what you need to store and for how long. We will recommend the right space at our ' . $facility['shortName'] . ' facility and send a clear quote.',
        ], $copy);
    }

    // ------------------------------------------------------------------
    // Facilities (shared by every page in a city)
    // ------------------------------------------------------------------

    public static function facilities(): array
    {
        return [
            'dubai' => [
                'city' => 'Dubai',
                'shortName' => 'Al Quoz',
                'facilityName' => 'StorageKeys Dubai (Al Quoz)',
                'addressHtml' => '10A Street, Al Quoz Industrial Area 3<br>Al Quoz, Dubai, UAE',
                'schemaAddress' => ['streetAddress' => '10A Street, Al Quoz Industrial Area 3', 'addressLocality' => 'Al Quoz, Dubai', 'addressRegion' => 'Dubai'],
                'schemaAreas' => ['Dubai'],
                'directions' => 'https://maps.app.goo.gl/cZhb4mLPGVsGcooA9',
                'mapEmbed' => 'https://maps.google.com/maps?q=Storage%20Keys%2C%2010A%20Street%20-%20Al%20Qouz%20Ind.third%20-%20Al%20Quoz%20-%20Dubai&z=15&output=embed',
                'visitTitle' => 'Find Us in Al Quoz',
                'visitText' => 'Drop off your belongings yourself or book a collection. Call ahead and we will have your space ready when you arrive.',
                'gettingHere' => 'Minutes from Sheikh Zayed Road and Al Khail Road, with easy truck and van access for loading.',
                'areasTitle' => 'Storage Across Dubai',
                'areas' => [
                    'Central Dubai' => ['Al Quoz', 'Al Barsha', 'Downtown', 'Business Bay', 'DIFC', 'Jumeirah', 'Umm Suqeim'],
                    'New Dubai' => ['Dubai Marina', 'JLT', 'JBR', 'Palm Jumeirah', 'Dubai Hills', 'JVC', 'Arabian Ranches', 'Motor City'],
                    'Old Dubai & East' => ['Deira', 'Bur Dubai', 'Al Qusais', 'Mirdif', 'Silicon Oasis', 'International City'],
                ],
                'compareText' => 'Al Quoz, minutes from Sheikh Zayed Road. Best for Downtown, Al Barsha, Business Bay, Dubai Marina, JLT and New Dubai.',
            ],
            'sharjah' => [
                'city' => 'Sharjah',
                'shortName' => 'Sharjah',
                'facilityName' => 'StorageKeys Sharjah',
                'addressHtml' => 'Plot # 4202<br>Sharjah, UAE',
                'schemaAddress' => ['streetAddress' => 'Plot # 4202', 'addressLocality' => 'Sharjah', 'addressRegion' => 'Sharjah'],
                'schemaAreas' => ['Sharjah', 'Ajman'],
                'geo' => [25.353524, 55.6316793],
                'directions' => 'https://maps.app.goo.gl/vvWVRRKCtBnups2k6',
                'mapEmbed' => 'https://maps.google.com/maps?q=Storage%20Keys%2C%20Plot%20%23%204202%20-%20Sharjah%20-%20United%20Arab%20Emirates&z=15&output=embed',
                'visitTitle' => 'Find Us in Sharjah',
                'visitText' => 'Our team works from this facility, so you can view space, sign paperwork and drop off belongings in one visit. Call ahead and we will have everything ready.',
                'gettingHere' => 'Use the Get Directions button for turn-by-turn directions, or call us and we will guide you in.',
                'areasTitle' => 'Storage Across Sharjah and Ajman',
                'areas' => [
                    'Central Sharjah' => ['Al Majaz', 'Al Nahda', 'Al Taawun', 'Al Khan', 'Al Qasimia', 'Abu Shagara'],
                    'Wider Sharjah' => ['Muwaileh', 'University City', 'Al Rahmaniya', 'Aljada', 'Industrial Areas', 'Al Sajaa'],
                    'Ajman & Dubai Border' => ['Ajman', 'Al Qusais', 'Al Nahda Dubai', 'Muhaisnah', 'Mirdif'],
                ],
                'compareText' => 'Our main facility and office. Cost-effective for long-term storage, larger spaces and customers in Sharjah, Ajman and the Dubai border areas.',
            ],
        ];
    }

    // ------------------------------------------------------------------
    // City overview pages
    // ------------------------------------------------------------------

    private static function cityCopy(): array
    {
        return [
            'dubai' => [
                'metaTitle' => 'Self Storage in Dubai, Al Quoz | StorageKeys',
                'metaDescription' => 'Secure self storage in Al Quoz, Dubai for homes and businesses. 24/7 access, CCTV and climate-controlled units with flexible terms. Get a free quote today.',
                'eyebrow' => 'Al Quoz, Dubai',
                'h1Accent' => 'Secure Units in Al Quoz',
                'lead' => 'Self storage in Dubai at our Al Quoz facility: secure, climate-controlled storage units for households, expats and businesses, with 24/7 access, CCTV, collection from your door and flexible monthly terms.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/corridor area-80kb.jpg',
                'overviewTitle' => 'Storage Space in the Heart of Dubai',
                'overview' => [
                    'Self storage in Dubai solves a simple problem. Apartments rarely come with a storeroom, villas fill up, and commercial rents keep rising. A storage unit gives you extra storage space in Dubai without moving home or signing a bigger lease, whether you are between tenancies, renovating, downsizing or holding stock for a small business.',
                    'Our storage facility sits on 10A Street in Al Quoz Industrial Area 3, a short drive from Sheikh Zayed Road and Al Khail Road, so Downtown, Business Bay, Al Barsha, JLT and Dubai Marina are all within easy reach. Climate-controlled storage units protect furniture, electronics, documents and photos from the summer heat and humidity that damage belongings left in garages and on balconies.',
                    'Storage units in Dubai are sized to what you store, from a few boxes to the contents of a villa. Tell us your items and we will recommend a unit size and send a clear quote. Terms run month to month, so you can resize or move out when your plans change. If your items are valuable, check that your home contents insurance covers belongings kept in storage.',
                ],
                'ticks' => [
                    '24/7 access',
                    'CCTV & controlled entry',
                    'Climate-controlled units',
                    'Collection from your door',
                    'Flexible monthly terms',
                    'Units for homes & businesses',
                ],
                'whyTitle' => 'Why Dubai Residents and Businesses Choose Us',
                'why' => [
                    [
                        'fa-map-marked-alt',
                        'Central Location',
                        'Al Quoz is minutes from Sheikh Zayed Road, so drop-offs fit into a normal day.',
                    ],
                    ['fa-shield-alt', 'Secure Around the Clock', '24/7 CCTV and controlled entry. Only you and people you authorise get in.'],
                    [
                        'fa-snowflake',
                        'Built for the UAE Climate',
                        'Climate-controlled units keep furniture, electronics and documents safe in summer.',
                    ],
                    ['fa-sliders-h', 'Flexible, Clear Pricing', 'No long fixed lease. Pay for the space you use and resize any time.'],
                ],
                'quoteText' => 'Tell us what you need to store and for how long. We will recommend the right unit at our Al Quoz facility and send a clear quote.',
                'faqs' => [
                    [
                        'Where is your Dubai storage facility?',
                        'Our Dubai facility is on 10A Street in Al Quoz Industrial Area 3, close to Sheikh Zayed Road and Al Khail Road, so it is easy to reach from most of the city.',
                    ],
                    [
                        'Can I access my unit at any time?',
                        'Yes. The Dubai facility offers 24/7 access for customers, with CCTV and controlled entry protecting every unit.',
                    ],
                    [
                        'Do you collect items from homes and offices in Dubai?',
                        'Yes. Our moving team can collect from your home or office anywhere in Dubai and bring everything to your unit, or you can drop items off yourself.',
                    ],
                    [
                        'How long do I need to book storage for?',
                        'Terms are flexible. You can store for a few weeks during a move or renovation, or for months and years, and change your unit size as your needs change.',
                    ],
                    [
                        'What size storage unit do I need in Dubai?',
                        'It depends on what you store. A few boxes need very little space, while a one-bedroom flat or a full villa needs a larger unit. Share your list and we will recommend a size.',
                    ],
                    [
                        'How much does self storage in Dubai cost?',
                        'Self storage in Dubai is priced on unit size, how long you store and whether you need collection. Terms are monthly and quote-based, so ask for a free quote with your item list.',
                    ],
                ],
                'guide' => [
                    'eyebrow' => 'Good to Know',
                    'title' => 'Tips for Using Self Storage in Dubai',
                    'intro' => 'A few simple steps make self storage in Dubai easier, safer and cheaper. Here is what our team tells new customers in Al Quoz.',
                    'points' => [
                        [
                            'fa-list-ul',
                            'Make a List First',
                            'Write down everything you plan to store before you call us. A clear list helps us pick the right storage unit, so you do not pay for space you will not use.',
                        ],
                        [
                            'fa-sun',
                            'Think About the Summer',
                            'Dubai summers are hot and humid. Wood, leather, electronics and paper can crack, warp or grow mould. Ask for a climate-controlled unit for these items.',
                        ],
                        [
                            'fa-box',
                            'Pack in the Right Boxes',
                            'Use strong boxes of the same size so they stack well. Fill each box fully and write what is inside on the top and the side.',
                        ],
                        [
                            'fa-calendar-check',
                            'Plan Your Dates',
                            'Book collection a few days before you move out, especially at the end of the month when many leases end and movers are busy across Dubai.',
                        ],
                    ],
                ],
            ],
            'sharjah' => [
                'metaTitle' => 'Self Storage in Sharjah | StorageKeys',
                'metaDescription' => 'Affordable, secure self storage in Sharjah for homes and businesses. 24/7 access, CCTV, collection and flexible terms. Get a free quote today.',
                'eyebrow' => 'Sharjah · Main Facility',
                'h1Accent' => 'Secure, Affordable Units',
                'lead' => 'Self storage in Sharjah at our main facility: secure storage units for households, students, expats and businesses across Sharjah, Ajman and Dubai, with 24/7 access, CCTV, collection from your door and flexible monthly terms.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/peace-of-mind-80kb.jpg',
                'overviewTitle' => 'Our Main Storage Facility in Sharjah',
                'overview' => [
                    'If you are looking for storage space in Sharjah, our main facility and office at Plot # 4202 is where most of our storage, moving and customer service is run from. StorageKeys has served customers from Sharjah since 2016, and our team is on site for viewings, paperwork and drop-offs.',
                    'Self storage in Sharjah usually costs less than comparable space in central Dubai, which makes it a practical choice for long-term storage, larger units and businesses holding bulk stock. It is also close to home for the many families who live in Al Nahda, Al Majaz, Al Taawun and Muwaileh and work in Dubai.',
                    'Our storage units in Sharjah range from space for a few boxes to room for a full home, an office or cartons of stock. Tell us what you plan to store and we will suggest the right unit size and send a clear quote on monthly terms. Label your boxes, keep a simple list, and check that your insurance covers items held in storage.',
                    'Getting started is simple. Send us a message on WhatsApp or call with a rough list of your items. We will suggest a unit, share a quote and book a time for you to drop off, or for our team to collect. We will then agree a date that suits you.',
                ],
                'ticks' => [
                    '24/7 access',
                    'CCTV & controlled entry',
                    'Our main office on site',
                    'Collection from your door',
                    'Flexible monthly terms',
                    'Cost-effective larger units',
                ],
                'whyTitle' => 'Why Sharjah Residents and Businesses Choose Us',
                'why' => [
                    [
                        'fa-tags',
                        'Lower Cost Than Central Dubai',
                        'Larger units and long-term storage stay affordable without cutting security.',
                    ],
                    ['fa-shield-alt', 'Secure Around the Clock', '24/7 CCTV and controlled entry. Only you and people you authorise get in.'],
                    ['fa-headset', 'Main Office On Site', 'Questions, viewings and paperwork are handled in person by our team.'],
                    ['fa-award', 'Serving Since 2016', 'Years of helping UAE households and businesses move and store.'],
                ],
                'quoteText' => 'Tell us what you need to store and for how long. We will recommend the right unit at our Sharjah facility and send a clear quote.',
                'faqs' => [
                    [
                        'Where is your Sharjah storage facility?',
                        'Our main facility and office are at Plot # 4202 in Sharjah. Use the Get Directions button on this page or call us and we will guide you in.',
                    ],
                    [
                        'Do you offer cheap storage in Sharjah?',
                        'Prices are quote-based and depend on unit size and how long you store. Space in Sharjah generally costs less than in central Dubai, so it suits budget-conscious and long-term storage.',
                    ],
                    [
                        'Can I access my unit at any time?',
                        'Yes. The Sharjah facility offers 24/7 access for customers, with CCTV and controlled entry protecting every unit.',
                    ],
                    [
                        'Do you collect from homes and offices in Sharjah, Ajman and Dubai?',
                        'Yes. Our moving team collects from homes and offices across Sharjah, Ajman and Dubai and brings everything to your unit, or you can drop items off yourself.',
                    ],
                    [
                        'What size storage unit do I need in Sharjah?',
                        'Tell us your items or room count and we will recommend a size. You can move to a bigger or smaller unit later as your needs change.',
                    ],
                ],
                'guide' => [
                    'eyebrow' => 'Good to Know',
                    'title' => 'How to Get the Most From Storage in Sharjah',
                    'intro' => 'Storage in Sharjah works best when you plan a little first. These simple tips come from our team at the main facility.',
                    'points' => [
                        [
                            'fa-clipboard-list',
                            'Start With What You Have',
                            'Count your boxes and big items, or list the rooms you are clearing. This tells us which storage unit fits, and you only pay for that space.',
                        ],
                        [
                            'fa-hourglass-half',
                            'Long Stays Cost Less Here',
                            'If you will store for many months, Sharjah is often the better choice. Space here usually costs less than in central Dubai, so the savings add up over time.',
                        ],
                        [
                            'fa-user-friends',
                            'Name Who Can Visit',
                            'Tell us who else may visit your unit, such as family or staff. Only the people you name can get in, and you can change the list at any time.',
                        ],
                        [
                            'fa-route',
                            'Use One Trip',
                            'Our office is at the facility, so you can view a unit, sign the papers and drop off your things in a single visit. Call before you come.',
                        ],
                    ],
                ],
            ],
        ];
    }

    // ------------------------------------------------------------------
    // Services (shared by both cities)
    // ------------------------------------------------------------------

    public static function services(): array
    {
        return [
            'warehouse-storage' => [
                'label' => 'Warehouse Storage',
                'menuLabel' => 'Warehouse',
                'icon' => 'fa-warehouse',
                'storageType' => 'Warehouse Storage',
                'cardText' => 'Space for bulk stock, inventory and equipment without a long lease.',
                'image' => 'sk-assets/assets/images/frontend/web/Image_3.png',
                'ticks' => ['No long warehouse lease', 'Scale space up or down', '24/7 CCTV & controlled entry', 'Access for authorised staff', 'Collection & delivery available', 'Clear, quote-based pricing'],
                'itemsTitle' => 'What Businesses Store',
                'itemsIntro' => 'Tell us what you hold, how much and how often you need it, and we will match the space to your operation.',
                'items' => [
                    ['fa-boxes', 'Bulk Inventory', 'Overflow stock, slow-moving lines and buffer inventory kept off your shop floor.'],
                    ['fa-shopping-cart', 'E-commerce Stock', 'Product held close to customers, ready to pick and send out as orders arrive.'],
                    ['fa-tags', 'Seasonal & Retail Stock', 'Ramadan, DSF and year-end stock brought in early and stored until it is needed.'],
                    ['fa-tools', 'Tools & Equipment', 'Machinery, tools and spare parts between projects and contracts.'],
                    ['fa-archive', 'Archives & Records', 'Boxed files and records that must be kept but rarely opened.'],
                    ['fa-store', 'Exhibition & Event Material', 'Stands, displays and promotional material between shows and campaigns.'],
                ],
                'why' => [
                    ['fa-file-signature', 'No Long Lease', 'Use warehouse space on flexible terms instead of committing to years of rent.'],
                    ['fa-expand-arrows-alt', 'Grows With You', 'Add space ahead of peak season and give it back when stock levels drop.'],
                    ['fa-user-shield', 'Controlled Access', 'Only staff you authorise can reach your stock, under 24/7 CCTV.'],
                    ['fa-truck', 'Logistics Support', 'Our team can collect stock and deliver it where your business needs it.'],
                ],
            ],
            'personal-storage' => [
                'label' => 'Personal Storage',
                'menuLabel' => 'Personal Storage',
                'icon' => 'fa-user',
                'storageType' => 'Personal Storage',
                'cardText' => 'Belongings, boxes and seasonal items while you move, travel or declutter.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/inline-2.jpg',
                'ticks' => ['Units from a few boxes to a full home', '24/7 access', 'CCTV & controlled entry', 'Collection from your door', 'Flexible monthly terms', 'Change size any time'],
                'itemsTitle' => 'What People Store',
                'itemsIntro' => 'If it is taking up space at home, it can usually come to storage. Tell us what you have and we will suggest the right size.',
                'items' => [
                    ['fa-home', 'Household Belongings', 'Everyday items you do not need right now but want to keep.'],
                    ['fa-tree', 'Seasonal Items', 'Holiday decorations, winter clothes, camping and outdoor gear.'],
                    ['fa-bicycle', 'Sports & Hobby Gear', 'Bikes, golf clubs, diving kit and other bulky equipment.'],
                    ['fa-heart', 'Keepsakes & Documents', 'Photo albums, papers and sentimental items kept safe and dry.'],
                    ['fa-couch', 'Furniture & Appliances', 'Larger pieces during a move, renovation or downsize.'],
                    ['fa-graduation-cap', 'Student Belongings', 'Room contents over semester breaks and summer holidays.'],
                ],
                'why' => [
                    ['fa-ruler-combined', 'The Right Size', 'Pay for the space you need, from a few boxes to the contents of a villa.'],
                    ['fa-key', 'Your Own Space', 'A private unit that only you and people you authorise can open.'],
                    ['fa-clock', 'Access Any Time', 'Drop off or pick up 24/7, on your schedule.'],
                    ['fa-truck-moving', 'We Can Collect', 'Our moving team brings everything from your door to your unit.'],
                ],
            ],
            'furniture-storage' => [
                'label' => 'Furniture Storage',
                'menuLabel' => 'Furniture Storage',
                'icon' => 'fa-couch',
                'storageType' => 'Personal Storage',
                'cardText' => 'Sofas, beds and wardrobes kept safe during a move or renovation.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/corridor area-80kb.jpg',
                'ticks' => ['Space for single pieces or full homes', 'Careful handling', 'CCTV & controlled entry', 'Collection & delivery', 'Flexible monthly terms', '24/7 access'],
                'itemsTitle' => 'Furniture We Store',
                'itemsIntro' => 'From a single sofa to the contents of a villa. Tell us what you have and we will estimate the space you need.',
                'items' => [
                    ['fa-couch', 'Sofas & Armchairs', 'Living room furniture wrapped and stored upright or flat as it needs.'],
                    ['fa-bed', 'Beds & Mattresses', 'Bed frames, headboards and mattresses kept clean and covered.'],
                    ['fa-door-closed', 'Wardrobes & Cabinets', 'Large storage pieces, ideally dismantled for safer transport.'],
                    ['fa-utensils', 'Dining Sets', 'Tables and chairs, with legs removed where possible to save space.'],
                    ['fa-chair', 'Office Furniture', 'Desks, chairs and filing cabinets during office moves and fit-outs.'],
                    ['fa-gem', 'Antiques & Wooden Pieces', 'Delicate items that need extra care. Ask us about the best conditions.'],
                ],
                'why' => [
                    ['fa-hand-holding', 'Careful Handling', 'Our team wraps, lifts and loads furniture to avoid scratches and dents.'],
                    ['fa-hammer', 'Ideal for Renovations', 'Clear rooms quickly and bring everything back when the work is done.'],
                    ['fa-shield-alt', 'Secure Storage', 'CCTV and controlled entry protect every unit around the clock.'],
                    ['fa-calendar-alt', 'Flexible Terms', 'Store for a few weeks between homes or for months and years.'],
                ],
            ],
            'appliance-storage' => [
                'label' => 'Appliance Storage',
                'menuLabel' => 'Appliances Storage',
                'icon' => 'fa-blender',
                'storageType' => 'Personal Storage',
                'cardText' => 'Fridges, washers, cookers and electronics stored clean and dry.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/inline-2.jpg',
                'ticks' => ['Large & small appliances', 'Upright storage for fridges', 'CCTV & controlled entry', 'Collection & delivery', 'Flexible monthly terms', '24/7 access'],
                'itemsTitle' => 'Appliances We Store',
                'itemsIntro' => 'Clean, dry and disconnected appliances store well for months. Tell us what you have and we will plan the space.',
                'items' => [
                    ['fa-snowflake', 'Fridges & Freezers', 'Stored upright, defrosted and with doors slightly open to stay fresh.'],
                    ['fa-tshirt', 'Washers & Dryers', 'Drained fully before storage, with drums secured for transport.'],
                    ['fa-fire', 'Cookers & Ovens', 'Cleaned of grease and food so they come back ready to use.'],
                    ['fa-fan', 'AC Units & Fans', 'Portable and split units removed during moves or refurbishments.'],
                    ['fa-tv', 'TVs & Electronics', 'Screens and devices packed in original boxes or padded wrapping.'],
                    ['fa-blender', 'Small Appliances', 'Microwaves, coffee machines and kitchen gadgets boxed together.'],
                ],
                'why' => [
                    ['fa-people-carry', 'Heavy Lifting Handled', 'Our team moves bulky appliances safely, so you do not have to.'],
                    ['fa-tint-slash', 'Clean, Dry Space', 'Units are kept dry, which protects motors, seals and electronics.'],
                    ['fa-shield-alt', 'Secure Storage', 'CCTV and controlled entry protect every unit around the clock.'],
                    ['fa-calendar-alt', 'Flexible Terms', 'Store between tenancies, during renovations or while you are abroad.'],
                ],
            ],
            'luggage-storage' => [
                'label' => 'Luggage Storage',
                'menuLabel' => 'Luggage Storage',
                'icon' => 'fa-suitcase-rolling',
                'storageType' => 'Luggage Storage',
                'cardText' => 'Suitcases and travel gear between trips, stays or moves.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/peace-of-mind-80kb.jpg',
                'ticks' => ['A few bags or a full room', 'Short or long stays', 'CCTV & controlled entry', 'Collection available', 'Flexible terms', '24/7 access'],
                'itemsTitle' => 'What Travellers Store',
                'itemsIntro' => 'Whether it is one suitcase or everything from a flat, we keep it safe until you are back.',
                'items' => [
                    ['fa-suitcase', 'Suitcases', 'Bags of every size, kept safely while you travel or between stays.'],
                    ['fa-hiking', 'Backpacks & Travel Gear', 'Camping, trekking and travel kit you only need a few times a year.'],
                    ['fa-golf-ball', 'Golf & Sports Bags', 'Clubs, rackets and sports gear between seasons and trips.'],
                    ['fa-box-open', 'Relocation Boxes', 'Belongings waiting for your new home or a shipment abroad.'],
                    ['fa-graduation-cap', 'Student Luggage', 'Room contents over summer and semester breaks.'],
                    ['fa-plane-departure', 'Summer Abroad', 'Items left behind while you spend the hot months overseas.'],
                ],
                'why' => [
                    ['fa-hourglass-half', 'Short or Long Stays', 'Store for a few days, a whole summer or longer.'],
                    ['fa-lock', 'Safe and Private', 'Your bags stay in a secure space only you can access.'],
                    ['fa-clock', 'Access Any Time', 'Drop off or collect 24/7, around your flights.'],
                    ['fa-truck-moving', 'Collection Service', 'We can collect from your hotel, home or office.'],
                ],
            ],
            'box-storage' => [
                'label' => 'Box Storage',
                'menuLabel' => 'Box Storage',
                'icon' => 'fa-box',
                'storageType' => 'Personal Storage',
                'cardText' => 'Packed boxes, documents and seasonal items kept organised.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/inline-2.jpg',
                'ticks' => ['From a few boxes upwards', 'Organised & easy to find', 'CCTV & controlled entry', 'Collection available', 'Flexible monthly terms', '24/7 access'],
                'itemsTitle' => 'What People Keep in Boxes',
                'itemsIntro' => 'Boxes are the easiest way to store. Label them well and you can find anything in minutes.',
                'items' => [
                    ['fa-dolly', 'Moving Boxes', 'Packed belongings waiting between homes or for a new tenancy.'],
                    ['fa-file-alt', 'Documents & Archives', 'Personal papers and business records kept dry and secure.'],
                    ['fa-tshirt', 'Seasonal Clothes', 'Winter wear and travel clothes stored until you need them.'],
                    ['fa-book', 'Books & Keepsakes', 'Collections, albums and sentimental items you want to keep.'],
                    ['fa-boxes', 'Business Stock', 'Cartons of stock, samples and marketing material for small businesses.'],
                    ['fa-puzzle-piece', 'Toys & Baby Items', 'Outgrown items you are saving for later or for the next child.'],
                ],
                'why' => [
                    ['fa-ruler', 'Pay for What You Use', 'Small spaces for a handful of boxes, bigger ones when you need them.'],
                    ['fa-search', 'Easy to Find', 'Labelled, stacked boxes mean quick visits when you need one item.'],
                    ['fa-shield-alt', 'Secure Storage', 'CCTV and controlled entry protect every unit around the clock.'],
                    ['fa-truck-moving', 'We Can Collect', 'Our team collects boxes from your door and brings them back on request.'],
                ],
            ],
        ];
    }

    // ------------------------------------------------------------------
    // Service pages per city (unique copy for each page)
    // ------------------------------------------------------------------

    private static function serviceCopy(): array
    {
        return [
            'warehouse-storage' => [
                'dubai' => [
                    'metaTitle' => 'Warehouse Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Flexible warehouse storage in Al Quoz, Dubai for stock, inventory and equipment. No long lease, 24/7 CCTV and controlled access. Get a free quote today.',
                    'h1Accent' => 'Flexible Space in Al Quoz',
                    'lead' => 'Warehouse storage in Dubai without a long lease: flexible space in Al Quoz for retailers, e-commerce brands and trading companies that need room for stock, pallets and equipment.',
                    'overviewTitle' => 'Warehouse Space Close to Dubai’s Main Roads',
                    'overview' => [
                        'Warehouse storage in Dubai usually means a multi-year lease on a whole unit in an industrial area. For growing businesses whose stock rises and falls through the year, that is a poor fit. With StorageKeys you take the warehouse space you need now, on flexible terms, and adjust it as your inventory changes.',
                        'Our Al Quoz facility sits between Sheikh Zayed Road and Al Khail Road, with truck and van access for loading. That keeps distribution to Downtown, Business Bay, Al Barsha and New Dubai quick, whether you dispatch e-commerce orders every day or move stock to your shops before DSF and Ramadan.',
                        'Plan the space around how you hold stock: cartons by product line, goods on pallets, or tools and equipment between contracts. Tell us your volumes and how often your staff need access, and we will size the warehouse space and send a clear quote. Climate-controlled units are available for stock that does not cope well with heat.',
                        'Many of our Dubai business customers start small. A shop might store a few dozen cartons of seasonal stock, while a trading company keeps several pallets of slow-moving goods. As orders grow, they add space in the same facility, so their stock stays in one place and their staff do not need to learn a new location.',
                    ],
                    'faqs' => [
                        [
                            'Where is your Dubai warehouse storage?',
                            'At our Al Quoz facility on 10A Street, Al Quoz Industrial Area 3, close to Sheikh Zayed Road and Al Khail Road.',
                        ],
                        [
                            'Do I need to sign a long lease?',
                            'No. Warehouse storage is quote-based and flexible, so you can add or release space as stock levels change.',
                        ],
                        [
                            'Can my staff access the stock?',
                            'Yes. Staff you authorise can access your space 24/7, and you can update the list at any time.',
                        ],
                        [
                            'Can you collect stock from suppliers or our office?',
                            'Yes. Our team can collect from suppliers, offices or shops across Dubai and deliver stock where you need it.',
                        ],
                        [
                            'What can businesses keep in warehouse storage in Dubai?',
                            'Bulk inventory, e-commerce stock, seasonal retail stock, tools, equipment, archives and event material. Hazardous, flammable and perishable goods cannot be stored.',
                        ],
                        [
                            'Is warehouse storage in Dubai suitable for e-commerce?',
                            'Yes. Online sellers keep stock with us close to their customers, and the staff you authorise can access it 24/7 to pick and send out orders.',
                        ],
                    ],
                    'itemsIntro' => 'Dubai businesses use our Al Quoz space for stock, kit and records. Tell us what you hold and how often you need it.',
                    'items' => [
                        [
                            'fa-boxes',
                            'Fast-Moving Stock',
                            'Products you sell every week, kept close to Sheikh Zayed Road so drivers can collect them quickly.',
                        ],
                        [
                            'fa-shopping-cart',
                            'Online Store Inventory',
                            'Stock for your website orders, ready for your team to pick, pack and send out.',
                        ],
                        ['fa-tags', 'DSF and Ramadan Stock', 'Extra cartons for busy sale seasons, stored here until your shops need them.'],
                        ['fa-wrench', 'Contractor Equipment', 'Tools, machines and spare parts between jobs in Dubai.'],
                        ['fa-archive', 'Company Files', 'Boxed records and old files that you must keep but rarely open.'],
                        ['fa-store', 'Event and Exhibition Stands', 'Displays and banners kept safe between shows at Dubai venues.'],
                    ],
                    'why' => [
                        ['fa-road', 'Close to Main Roads', 'Al Quoz sits between Sheikh Zayed Road and Al Khail Road, so deliveries are quick.'],
                        ['fa-file-signature', 'No Long Lease', 'Rent space by the month, not for years.'],
                        ['fa-user-shield', 'Your Team Only', 'Only staff you name can reach your stock.'],
                        ['fa-snowflake', 'Cool Units Available', 'Climate-controlled space for stock that does not like heat.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Planning Warehouse Space in Dubai',
                        'intro' => 'Getting warehouse space right saves money every month. These points help Dubai businesses plan before they book.',
                        'points' => [
                            [
                                'fa-chart-line',
                                'Know Your Busy Months',
                                'Most Dubai shops sell more during DSF, Ramadan and year end. Book extra space a few weeks early, then reduce it when sales slow down.',
                            ],
                            [
                                'fa-pallet',
                                'Count Pallets or Cartons',
                                'Tell us roughly how many pallets or cartons you hold. This is the quickest way for us to size the space and give you a clear price.',
                            ],
                            [
                                'fa-id-badge',
                                'Register Your Staff',
                                'Give us the names of staff who will visit. We add them to the access list, and you can remove them when they leave.',
                            ],
                            [
                                'fa-ban',
                                'Check What Is Allowed',
                                'We cannot store hazardous, flammable or perishable goods. Ask us first if you are not sure about a product.',
                            ],
                        ],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Warehouse Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Cost-effective warehouse storage in Sharjah for bulk stock, inventory and equipment. Flexible terms, 24/7 CCTV and collection service. Get a free quote.',
                    'h1Accent' => 'Cost-Effective Space for Stock',
                    'lead' => 'Warehouse storage in Sharjah at our main facility: cost-effective space for trading companies, distributors and online sellers holding bulk stock, without a long warehouse lease.',
                    'overviewTitle' => 'Affordable Warehouse Space in Sharjah',
                    'overview' => [
                        'Sharjah has long been the base for UAE traders and distributors, because warehouse space in Sharjah costs less than in Dubai and the main roads into both emirates are close by. Warehouse storage in Sharjah with StorageKeys gives you that advantage without committing to a lease.',
                        'Keep bulk and slow-moving inventory at our main facility, use it as a distribution point for customers across Sharjah, Ajman and Dubai, and add space ahead of Ramadan, DSF or year-end peaks. Release it again when stock levels fall, so you are not paying for an empty warehouse in the quiet months.',
                        'Tell us how you hold stock, whether cartons, palletised goods or equipment, how much of it there is and how often your staff need to reach it. We will plan the space, register the staff you authorise and send a clear, quote-based price. Our team can also collect from suppliers and deliver to your customers.',
                        'Our Sharjah customers include importers, wholesalers and small online brands. Some keep a few cartons, others fill a large space with pallets and machinery. Whatever the size, you get the same secure facility, the same 24/7 access for your staff and one team to call when you need more room.',
                    ],
                    'faqs' => [
                        [
                            'Where is your Sharjah warehouse storage?',
                            'At our main facility and office, Plot # 4202 in Sharjah. Use the Get Directions button on this page to find us.',
                        ],
                        [
                            'Is warehouse storage cheaper in Sharjah than in Dubai?',
                            'Space in Sharjah generally costs less than in central Dubai, which makes it a cost-effective place to hold bulk and slow-moving stock.',
                        ],
                        [
                            'Can I increase space before a busy season?',
                            'Yes. Tell us your expected volumes and we will plan extra space ahead of Ramadan, DSF or year-end peaks.',
                        ],
                        ['Do you deliver stock to Dubai?', 'Yes. Our team can collect and deliver stock across Sharjah, Ajman and Dubai.'],
                        [
                            'What can I store in warehouse storage in Sharjah?',
                            'Bulk and slow-moving inventory, cartons of stock, tools, equipment, archives and exhibition material. Hazardous, flammable and perishable goods cannot be stored.',
                        ],
                        [
                            'Can my staff access stock outside office hours?',
                            'Yes. Staff you authorise can access your space 24/7, and you can add or remove names at any time.',
                        ],
                    ],
                    'itemsIntro' => 'Traders and distributors use our main Sharjah facility to hold bulk stock at a lower cost. Here is what they keep with us.',
                    'items' => [
                        ['fa-boxes', 'Bulk Trading Stock', 'Large orders from suppliers, stored here before they go out to customers.'],
                        ['fa-dolly', 'Slow-Moving Lines', 'Products that sell slowly and do not need to sit in your shop or office.'],
                        [
                            'fa-truck-loading',
                            'Stock for Three Emirates',
                            'Goods you send to customers in Sharjah, Ajman and Dubai from one place.',
                        ],
                        ['fa-industry', 'Spare Parts and Tools', 'Machine parts and tools kept until a job or order needs them.'],
                        ['fa-file-invoice', 'Business Records', 'Invoices, contracts and old files kept safe and in order.'],
                        ['fa-gift', 'Seasonal Goods', 'Ramadan, Eid and winter stock brought in early and kept until it sells.'],
                    ],
                    'why' => [
                        ['fa-tags', 'Lower Cost Space', 'Sharjah space usually costs less than space in central Dubai.'],
                        ['fa-expand-arrows-alt', 'Change Size Any Time', 'Add space before busy months and give it back after.'],
                        ['fa-headset', 'Office on Site', 'Our team is here to help you in person.'],
                        ['fa-truck', 'We Can Deliver', 'We collect from suppliers and deliver to your customers.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Using Sharjah as Your Stock Base',
                        'intro' => 'Many UAE businesses keep stock in Sharjah and sell across the emirates. Here is how to make it work well.',
                        'points' => [
                            [
                                'fa-map-marked-alt',
                                'Store Here, Sell Everywhere',
                                'Keep your main stock in Sharjah and send smaller loads to Dubai and Ajman when orders come in. This keeps your shop or office free of boxes.',
                            ],
                            [
                                'fa-calendar-alt',
                                'Plan Around Seasons',
                                'Tell us early if you expect more stock before Ramadan, Eid or year end. We will plan extra space so it is ready when your goods arrive.',
                            ],
                            [
                                'fa-clipboard-check',
                                'Keep a Simple Stock List',
                                'Write down what goes in and out each time. A clear list makes it easy to find products and helps you spot missing items fast.',
                            ],
                            [
                                'fa-hand-holding-usd',
                                'Pay Only for What You Use',
                                'Prices are quote-based and monthly. When stock goes down, move to a smaller space and your cost goes down too.',
                            ],
                        ],
                    ],
                ],
            ],
            'personal-storage' => [
                'dubai' => [
                    'metaTitle' => 'Personal Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Personal storage units in Al Quoz, Dubai for belongings, boxes and seasonal items. 24/7 access, CCTV and climate-controlled units. Get a free quote.',
                    'h1Accent' => 'Extra Space for Dubai Homes',
                    'lead' => 'Personal storage in Dubai at our Al Quoz facility: private, climate-controlled storage units for belongings, boxes and seasonal items, with 24/7 access and collection from your door.',
                    'overviewTitle' => 'More Room Without Moving Home',
                    'overview' => [
                        'Personal storage in Dubai gives you a spare room across town. Many apartments have no storeroom and villas fill up fast, so a private storage unit adds storage space and keeps your home clear while you declutter, renovate, travel for the summer or wait between tenancies.',
                        'Our Al Quoz facility is a short drive from Al Barsha, Jumeirah, Downtown and Dubai Marina. Climate-controlled storage protects wood, fabric, electronics and paperwork from the heat and humidity of a Dubai summer, and CCTV and controlled entry keep every unit private.',
                        'Unit sizes run from a few boxes to the contents of a villa, on monthly terms you can change at any time. Expats leaving for a posting abroad often keep a unit so they do not have to sell everything. Before you store, check that your contents insurance covers items held off site.',
                        'Most people who use personal storage in Dubai are not moving house. They simply want their home back. Clearing out a spare room, a maid’s room or a crowded balcony can make a flat feel bigger, and it can save you from renting a larger place just to hold things you rarely use.',
                    ],
                    'faqs' => [
                        [
                            'How close is your Dubai facility to me?',
                            'Our facility is in Al Quoz Industrial Area 3, minutes from Sheikh Zayed Road and Al Khail Road, so it is quick to reach from most Dubai communities.',
                        ],
                        [
                            'What size unit do I need?',
                            'It depends on what you store. Tell us your items or room count and we will recommend a size. You can change it later.',
                        ],
                        [
                            'Are the units climate controlled?',
                            'Climate-controlled units are available at our Dubai facility for items sensitive to heat and humidity.',
                        ],
                        [
                            'Can you collect my belongings?',
                            'Yes. Our moving team can collect from anywhere in Dubai, or you can drop off yourself 24/7.',
                        ],
                        [
                            'Is personal storage in Dubai a good option for expats?',
                            'Yes. Expats store belongings during relocations, summers abroad and postings overseas, then have them delivered to their next home when they return.',
                        ],
                        [
                            'Do I need insurance for items in storage?',
                            'Check that your home contents insurance covers belongings kept in storage, especially valuable items, before you move them in.',
                        ],
                    ],
                    'itemsIntro' => 'Dubai residents use personal storage for things they want to keep but have no room for at home.',
                    'items' => [
                        ['fa-home', 'Extra Home Items', 'Things from the spare room, cupboards or balcony that you do not need every day.'],
                        ['fa-plane', 'Belongings During Summer Travel', 'Items you leave behind while you spend the hot months abroad.'],
                        ['fa-bicycle', 'Sports Gear', 'Bikes, golf clubs, surfboards and diving kit that take up floor space.'],
                        ['fa-heart', 'Family Keepsakes', 'Photos, letters and special items kept cool and dry.'],
                        ['fa-couch', 'Furniture From a Move', 'Sofas and beds that do not fit in your new Dubai apartment.'],
                        ['fa-briefcase', 'Things From a Job Move', 'Belongings you keep while you work abroad for a while.'],
                    ],
                    'why' => [
                        ['fa-map-marker-alt', 'Easy to Reach', 'Al Quoz is close to Al Barsha, Jumeirah, Downtown and the Marina.'],
                        ['fa-snowflake', 'Cool and Dry', 'Climate-controlled units protect your things from summer heat.'],
                        ['fa-key', 'Private Unit', 'Only you and people you name can open it.'],
                        ['fa-truck-moving', 'We Collect', 'Our team picks up from your door anywhere in Dubai.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Before You Store Personal Items in Dubai',
                        'intro' => 'A little preparation keeps your belongings in good condition. Follow these easy steps before your things come to Al Quoz.',
                        'points' => [
                            [
                                'fa-broom',
                                'Clean Everything First',
                                'Wash and dry clothes, wipe furniture and empty food from containers. Clean items stay fresh and do not attract insects.',
                            ],
                            [
                                'fa-tag',
                                'Label Every Box',
                                'Write the room and the main items on each box. When you need one thing later, you will find it in minutes.',
                            ],
                            [
                                'fa-gem',
                                'Keep Valuables at Home',
                                'Jewellery, passports and important documents are safer with you. Store everyday things in the unit instead.',
                            ],
                            [
                                'fa-shield-alt',
                                'Check Your Insurance',
                                'Ask your insurance company if your home contents policy covers items in storage. If not, ask about adding them.',
                            ],
                        ],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Personal Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable personal storage in Sharjah for belongings, boxes and seasonal items. 24/7 access, CCTV and collection service. Get a free quote today.',
                    'h1Accent' => 'Affordable Space Near Home',
                    'lead' => 'Personal storage in Sharjah at our main facility: affordable, private storage units for families, students and expats across Sharjah and Ajman, with 24/7 access and collection from your door.',
                    'overviewTitle' => 'Personal Storage for Sharjah Families',
                    'overview' => [
                        'Personal storage in Sharjah helps when every cupboard in the flat is full. A private storage unit nearby gives you room for the things you want to keep but do not use every day, from seasonal clothes and children’s items to furniture during a move.',
                        'Our main facility serves Al Nahda, Al Majaz, Al Taawun, Muwaileh and University City, and storage space in Sharjah usually costs less than in Dubai, which helps if you are storing for months or years. Students keep room contents with us over semester breaks and the summer.',
                        'Unit sizes range from a few boxes to a full apartment, and terms run month to month, so you can declutter at your own pace and resize later. Pack in sturdy boxes, label them clearly, and check that your contents insurance covers belongings kept in storage.',
                        'Personal storage is also a good idea when family visits for a long stay, when a new baby arrives, or when you are getting a flat ready to rent out. You can move things out quickly, enjoy the extra room, and bring them back when life settles down again.',
                    ],
                    'faqs' => [
                        [
                            'Where is your Sharjah facility?',
                            'Our main facility and office are at Plot # 4202 in Sharjah. Use the Get Directions button on this page to find us.',
                        ],
                        [
                            'Is this a good option for students?',
                            'Yes. Students from University City and nearby areas store room contents with us over semester breaks and summer holidays.',
                        ],
                        [
                            'How much space will I need?',
                            'Tell us what you plan to store and we will recommend a size. You can move to a bigger or smaller unit later.',
                        ],
                        ['Do you collect from Ajman too?', 'Yes. Our team collects from homes across Sharjah, Ajman and Dubai.'],
                        [
                            'Can I store belongings for just one month?',
                            'Yes. Personal storage in Sharjah runs on monthly terms, so you can store for one month during a move or keep the unit for as long as you need.',
                        ],
                        [
                            'What can I not put in storage?',
                            'Flammable liquids, gas cylinders, perishable food, plants and anything illegal cannot be stored. Ask us if you are unsure about an item.',
                        ],
                    ],
                    'itemsIntro' => 'Families, students and expats in Sharjah keep these items with us to free up space at home.',
                    'items' => [
                        ['fa-child', 'Children’s Things', 'Toys, cots and clothes your children have outgrown but you want to keep.'],
                        ['fa-graduation-cap', 'Student Room Contents', 'Books, bedding and bags from University City over the summer.'],
                        ['fa-tree', 'Seasonal Items', 'Eid decorations, winter clothes and camping gear for next year.'],
                        ['fa-book', 'Books and Papers', 'Books, school records and family papers kept dry and in order.'],
                        ['fa-couch', 'Spare Furniture', 'Extra chairs, tables and beds that make a small flat feel crowded.'],
                        ['fa-suitcase', 'Belongings While Abroad', 'Your things kept safe while you travel or work outside the UAE.'],
                    ],
                    'why' => [
                        ['fa-tags', 'Affordable', 'Storage in Sharjah usually costs less than in Dubai.'],
                        ['fa-home', 'Near Your Home', 'Close to Al Nahda, Al Majaz, Al Taawun and Muwaileh.'],
                        ['fa-clock', 'Open Any Time', 'Visit your unit 24/7, even late at night.'],
                        ['fa-truck-moving', 'Collection From Your Door', 'We pick up across Sharjah, Ajman and Dubai.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Simple Tips for Personal Storage in Sharjah',
                        'intro' => 'These easy tips help Sharjah families and students store their things safely and find them again quickly.',
                        'points' => [
                            [
                                'fa-layer-group',
                                'Put Heavy Things at the Bottom',
                                'Place heavy boxes on the floor and light ones on top. This stops boxes from crushing and keeps the stack steady.',
                            ],
                            [
                                'fa-door-open',
                                'Leave a Path Inside',
                                'Keep a small path in the middle of your unit. You can then reach the back without moving everything out.',
                            ],
                            [
                                'fa-tint-slash',
                                'Keep Things Dry',
                                'Make sure clothes, bedding and furniture are fully dry before storage. Damp items can smell or grow mould.',
                            ],
                            [
                                'fa-sync-alt',
                                'Visit Now and Then',
                                'Check your unit every few months. Swap seasonal items, add new boxes and take home what you need.',
                            ],
                        ],
                    ],
                ],
            ],
            'furniture-storage' => [
                'dubai' => [
                    'metaTitle' => 'Furniture Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Furniture storage in Al Quoz, Dubai for sofas, beds and wardrobes during moves and renovations. Climate-controlled units and collection. Get a quote.',
                    'h1Accent' => 'Safe Space During Moves & Renovations',
                    'lead' => 'Furniture storage in Dubai at our Al Quoz facility: climate-controlled space for sofas, beds, wardrobes and dining sets during moves and renovations, with careful wrapping and collection across the city.',
                    'overviewTitle' => 'Furniture Storage for Dubai Moves and Fit-Outs',
                    'overview' => [
                        'Furniture storage in Dubai is often needed at short notice. Tenancies end, villa renovations overrun and the new apartment is smaller than the last one, leaving sofas, beds and wardrobes with nowhere to go.',
                        'Our Al Quoz facility keeps furniture safe until you are ready. Climate-controlled units protect wood, leather and fabric from the heat and humidity that warp and crack furniture left in a garage or on a balcony, and indoor storage avoids the dust and pests that settle on pieces kept outside.',
                        'Our team wraps, dismantles where needed and loads furniture from homes and offices anywhere in Dubai, from a single sofa to a full apartment or villa. Use breathable covers rather than plastic on mattresses and upholstery, and keep wooden pieces off the floor.',
                        'If you are moving within Dubai, furniture storage gives you time. You do not have to fit every sofa and table into a new home on the same day. Store what does not fit yet, settle in, then decide what to keep, sell or bring back later.',
                    ],
                    'faqs' => [
                        [
                            'Can you collect furniture from my home in Dubai?',
                            'Yes. Our team collects, wraps and loads furniture from homes and offices anywhere in Dubai.',
                        ],
                        [
                            'Is climate-controlled storage available?',
                            'Yes. Climate-controlled units at our Al Quoz facility help protect wooden and upholstered furniture from heat and humidity.',
                        ],
                        [
                            'Should I dismantle furniture before storage?',
                            'Where possible, yes. Removing legs and taking apart wardrobes and beds saves space and lowers the risk of damage.',
                        ],
                        [
                            'How long can I store furniture?',
                            'As long as you need, from a few weeks during a renovation to months or years between homes.',
                        ],
                        [
                            'How do I protect a mattress in storage?',
                            'Clean and dry it, use a breathable mattress cover rather than plastic, and store it flat or on its side as the maker recommends.',
                        ],
                        [
                            'Can you store the furniture from a whole villa?',
                            'Yes. Furniture storage in Dubai can be sized for a single room or a full villa. Share an inventory or room count and we will recommend the space.',
                        ],
                    ],
                    'itemsIntro' => 'Dubai homes and offices store all kinds of furniture with us during moves, fit-outs and renovations.',
                    'items' => [
                        ['fa-couch', 'Living Room Sofas', 'Sofas and armchairs wrapped and kept clean while your villa is painted or refitted.'],
                        ['fa-bed', 'Bedroom Sets', 'Beds, headboards and mattresses stored between tenancies.'],
                        ['fa-door-closed', 'Built-Out Wardrobes', 'Large wardrobes taken apart for safe transport and storage.'],
                        ['fa-utensils', 'Dining Tables', 'Tables and chairs kept safe while you move to a smaller flat.'],
                        ['fa-chair', 'Office Desks and Chairs', 'Furniture from an office move or fit-out in Business Bay or DIFC.'],
                        ['fa-gem', 'Fine Wooden Pieces', 'Antiques and wooden furniture that need cool, steady conditions.'],
                    ],
                    'why' => [
                        ['fa-snowflake', 'Protects From Heat', 'Climate-controlled units stop wood and leather from cracking.'],
                        ['fa-hand-holding', 'Careful Team', 'We wrap and lift each piece to avoid scratches.'],
                        ['fa-wrench', 'We Take Things Apart', 'Beds and wardrobes are dismantled to save space.'],
                        ['fa-calendar-alt', 'Store Short or Long', 'Keep furniture for weeks or for years.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Keeping Furniture Safe in Dubai Storage',
                        'intro' => 'Furniture is expensive to replace. These simple steps keep it in good shape while it is stored in Al Quoz.',
                        'points' => [
                            [
                                'fa-spray-can',
                                'Clean and Dry It',
                                'Wipe down wood, vacuum fabric and let everything dry fully. Dirt and moisture cause stains and mould in storage.',
                            ],
                            [
                                'fa-screwdriver',
                                'Take Big Pieces Apart',
                                'Remove legs from tables and take apart beds and wardrobes. Keep screws in a bag taped to the piece they came from.',
                            ],
                            [
                                'fa-bed',
                                'Use Breathable Covers',
                                'Cover sofas and mattresses with cloth or breathable covers, not plastic. Plastic can trap moisture in the Dubai humidity.',
                            ],
                            [
                                'fa-arrows-alt-v',
                                'Do Not Stack Heavy Items',
                                'Never put boxes on top of sofas or mattresses. Heavy weight can bend frames and flatten cushions over time.',
                            ],
                        ],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Furniture Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable furniture storage in Sharjah for sofas, beds and wardrobes during moves and renovations. Collection service and flexible terms. Get a quote.',
                    'h1Accent' => 'Affordable Space for Every Piece',
                    'lead' => 'Furniture storage in Sharjah at our main facility: affordable space for sofas, beds, wardrobes and dining sets during moves, renovations and time abroad, with careful handling and collection from your door.',
                    'overviewTitle' => 'Furniture Storage at Sharjah Prices',
                    'overview' => [
                        'Furniture storage in Sharjah makes sense for longer stays. Furniture takes up a lot of space, so storing a full home can get expensive, and the same space in Sharjah usually costs less than in central Dubai.',
                        'That makes our main facility a sensible choice between tenancies, during a villa renovation or while you are away from the UAE. Indoor storage keeps sofas, mattresses and wooden pieces away from the sun, dust and humidity that damage furniture left on a balcony or in a garage.',
                        'Our team wraps and loads furniture from homes and offices across Sharjah, Ajman and Dubai, and dismantles beds, wardrobes and dining tables where that saves space. Add pieces later or move to a bigger unit whenever you need to.',
                        'Many Sharjah families keep furniture with us when they move to a smaller flat but want to keep good pieces for a future villa. Others store furniture while a home is painted or the floors are replaced. In both cases, your furniture stays clean, dry and ready to come back.',
                    ],
                    'faqs' => [
                        [
                            'Do you collect furniture across Sharjah and Ajman?',
                            'Yes. Our team collects, wraps and loads furniture from homes and offices across Sharjah, Ajman and Dubai.',
                        ],
                        [
                            'Is Sharjah cheaper for storing a full home?',
                            'Space in Sharjah generally costs less than in central Dubai, which adds up when you are storing a lot of furniture for a long time.',
                        ],
                        [
                            'How should furniture be prepared?',
                            'Clean and dry everything, dismantle large pieces where possible and cover upholstery. Our team can help with wrapping.',
                        ],
                        ['Can I add more furniture later?', 'Yes. You can add items or move to a bigger unit at any time.'],
                        [
                            'How should I store a sofa?',
                            'Clean it, let it dry fully, cover it with a breathable sheet and store it upright on its feet. Do not stack heavy items on the cushions.',
                        ],
                        [
                            'Do you dismantle furniture before storage?',
                            'Yes. Our team can take apart beds, wardrobes and tables before transport, which saves space and lowers the risk of damage.',
                        ],
                    ],
                    'itemsIntro' => 'Sharjah families and offices store furniture with us when they move, renovate or travel.',
                    'items' => [
                        ['fa-couch', 'Full Home Furniture', 'Everything from a flat or villa while you wait for your next home.'],
                        ['fa-bed', 'Beds and Mattresses', 'Beds and mattresses kept clean, covered and off the floor.'],
                        ['fa-door-closed', 'Cupboards and Wardrobes', 'Big storage pieces taken apart to save space and cost.'],
                        ['fa-utensils', 'Majlis and Dining Sets', 'Majlis seating, dining tables and chairs kept together.'],
                        ['fa-chair', 'Shop and Office Furniture', 'Desks, shelves and chairs from a Sharjah shop or office.'],
                        ['fa-child', 'Children’s Furniture', 'Cots, small beds and desks kept for the next child.'],
                    ],
                    'why' => [
                        ['fa-tags', 'Lower Cost for Big Items', 'Large pieces take space, and space costs less in Sharjah.'],
                        ['fa-hand-holding', 'Careful Handling', 'Our team wraps and loads furniture with care.'],
                        ['fa-truck-moving', 'Pick-Up Across Three Emirates', 'We collect from Sharjah, Ajman and Dubai.'],
                        ['fa-plus-circle', 'Add More Later', 'Bring more pieces or move to a bigger unit any time.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Easy Steps Before Storing Furniture in Sharjah',
                        'intro' => 'Simple preparation means your furniture comes back looking the same as when it left. Here is what to do.',
                        'points' => [
                            [
                                'fa-camera',
                                'Take Photos',
                                'Take a quick photo of each piece before it leaves your home. You will have a clear record of its condition.',
                            ],
                            [
                                'fa-tape',
                                'Protect the Corners',
                                'Wrap table corners and chair legs with soft material. Corners are the parts most often damaged during a move.',
                            ],
                            [
                                'fa-couch',
                                'Store Sofas Upright',
                                'Keep sofas standing on their feet, not on their side or back. This keeps the frame and springs in good shape.',
                            ],
                            [
                                'fa-list-ol',
                                'Keep a Furniture List',
                                'List every item and the number of parts. When it comes back, you can check that everything has arrived.',
                            ],
                        ],
                    ],
                ],
            ],
            'appliance-storage' => [
                'dubai' => [
                    'metaTitle' => 'Appliance Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Appliance storage in Al Quoz, Dubai for fridges, washing machines, cookers and electronics. Secure units, collection and flexible terms. Get a quote.',
                    'h1Accent' => 'Fridges, Washers & Electronics',
                    'lead' => 'Appliance storage in Dubai at our Al Quoz facility: secure space for fridges, washing machines, cookers and electronics, with collection of heavy items and climate-controlled units for screens and devices.',
                    'overviewTitle' => 'Appliance Storage for Dubai Homes and Landlords',
                    'overview' => [
                        'Appliance storage in Dubai is common when you move into a furnished apartment, renovate a kitchen or let a property unfurnished. A fridge, washing machine or dishwasher is hard to keep in a small flat and costly to sell and buy again.',
                        'Our Al Quoz facility stores appliances safely until you need them, and our team handles the heavy lifting from homes, villas and offices across Dubai. Landlords use appliance storage between tenancies, and climate-controlled units help protect TVs and electronics from heat and humidity.',
                        'Prepare appliances properly: defrost and dry fridges and freezers, drain washing machines and dishwashers fully, and clean ovens of grease. Store fridges upright with the door slightly open, and keep remote controls and cables in a labelled bag.',
                        'Replacing a fridge, washing machine or oven costs a lot more than storing one for a few months. If you move into a furnished flat, or your new kitchen already has appliances, storage lets you keep your own for later. You can also store spare appliances for a holiday home or a rental property in Dubai.',
                    ],
                    'faqs' => [
                        [
                            'Can you move heavy appliances?',
                            'Yes. Our team collects and loads fridges, washers and cookers from homes and offices across Dubai.',
                        ],
                        [
                            'How should I prepare a fridge for storage?',
                            'Defrost it, clean and dry it fully, and leave the door slightly open in storage to prevent smells and mould.',
                        ],
                        [
                            'Can I store electronics with appliances?',
                            'Yes. TVs and electronics can go in climate-controlled units at our Dubai facility.',
                        ],
                        [
                            'Do landlords use appliance storage?',
                            'Yes. Landlords often store appliances between tenancies or when a unit is let unfurnished.',
                        ],
                        [
                            'How should I prepare a washing machine for storage?',
                            'Drain it fully, dry the drum and seals, and fit the transit bolts if you still have them so the drum does not move in transport.',
                        ],
                        ['Can I store a dishwasher?', 'Yes. Drain it, leave the door open to dry, and secure the hoses before collection.'],
                        [
                            'Can I store appliances for a long time?',
                            'Yes. Clean, dry and unplugged appliances can stay in storage for months or even years and work well when they come back.',
                        ],
                    ],
                    'itemsIntro' => 'Dubai tenants, landlords and companies store these appliances with us between moves and refits.',
                    'items' => [
                        ['fa-snowflake', 'American Fridges', 'Large double-door fridges stored upright and fully dried.'],
                        ['fa-tshirt', 'Washer-Dryers', 'Washing machines and dryers drained and secured for transport.'],
                        ['fa-fire', 'Built-In Ovens', 'Ovens and hobs removed during a kitchen renovation.'],
                        ['fa-wind', 'Portable AC Units', 'Portable air conditioners and fans kept for next summer.'],
                        ['fa-tv', 'Large TVs', 'Big screens packed in their boxes or padded wrapping.'],
                        ['fa-coffee', 'Kitchen Gadgets', 'Coffee machines, microwaves and blenders boxed together.'],
                    ],
                    'why' => [
                        ['fa-people-carry', 'We Do the Lifting', 'Our team moves heavy appliances safely.'],
                        ['fa-snowflake', 'Safe for Electronics', 'Climate-controlled units protect screens and devices.'],
                        ['fa-building', 'Good for Landlords', 'Store appliances between tenants with no fuss.'],
                        ['fa-clock', '24/7 Access', 'Collect your appliance whenever you are ready.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Getting Appliances Ready for Storage in Dubai',
                        'intro' => 'Appliances last longer in storage when they are cleaned and dried first. These steps take less than an hour.',
                        'points' => [
                            [
                                'fa-plug',
                                'Unplug a Day Early',
                                'Switch off and unplug fridges and freezers one day before collection. This gives them time to defrost and dry.',
                            ],
                            [
                                'fa-water',
                                'Remove All Water',
                                'Drain washing machines and dishwashers fully. Leftover water can cause bad smells and rust.',
                            ],
                            [
                                'fa-door-open',
                                'Leave Doors Open a Little',
                                'In storage, keep fridge, washer and dishwasher doors slightly open so air can move and mould cannot grow.',
                            ],
                            [
                                'fa-box-open',
                                'Bag the Small Parts',
                                'Put shelves, cables and remote controls in a labelled bag and tape it to the appliance.',
                            ],
                        ],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Appliance Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable appliance storage in Sharjah for fridges, washing machines and cookers. Secure units, collection service and flexible terms. Get a free quote.',
                    'h1Accent' => 'Safe Space for Big Appliances',
                    'lead' => 'Appliance storage in Sharjah at our main facility: affordable space for fridges, freezers, washing machines and cookers, with collection of heavy appliances from homes and businesses across Sharjah and Ajman.',
                    'overviewTitle' => 'Appliance Storage for Sharjah Moves',
                    'overview' => [
                        'Appliance storage in Sharjah helps when large appliances have nowhere to go: between moves, during a kitchen refit or when a rental is let unfurnished. Fridges, freezers and washing machines are awkward to keep in a small flat.',
                        'Our main facility gives them a dry, secure place to wait, at a lower cost than storage in central Dubai, and our team handles the heavy lifting. Landlords and property managers use appliance storage between tenancies across Sharjah and Ajman.',
                        'Defrost and dry fridges and freezers a day before collection, drain washing machines and dishwashers completely, and clean cookers of grease. Clean, dry and disconnected appliances can be stored for months or years and come back ready to use.',
                        'Selling good appliances quickly before a move usually means taking a low price, and buying new ones later costs much more. Storing them in Sharjah is often the cheaper choice. It works well for families between flats, for landlords between tenants and for small businesses that are changing offices.',
                    ],
                    'faqs' => [
                        [
                            'Can you collect appliances from Sharjah and Ajman?',
                            'Yes. Our team collects and loads appliances from homes and businesses across Sharjah, Ajman and Dubai.',
                        ],
                        [
                            'How should I prepare a washing machine?',
                            'Drain it completely, dry the drum and seals, and secure the drum for transport if your model allows it.',
                        ],
                        [
                            'Can I store appliances for a long time?',
                            'Yes. Clean, dry and disconnected appliances can be stored for months or years.',
                        ],
                        [
                            'Is storage cheaper in Sharjah?',
                            'Space in Sharjah generally costs less than in central Dubai, which helps for longer storage.',
                        ],
                        [
                            'How do I prepare a fridge for storage?',
                            'Defrost it a day early, clean and dry it fully, and leave the door slightly open in storage to prevent smells and mould.',
                        ],
                        [
                            'Do landlords use appliance storage in Sharjah?',
                            'Yes. Landlords and property managers store appliances between tenancies or when a property is let unfurnished.',
                        ],
                        [
                            'Can you store a fridge and a washing machine together?',
                            'Yes. Most customers store several appliances in one unit. We place fridges upright and leave space so doors can stay slightly open.',
                        ],
                    ],
                    'itemsIntro' => 'Sharjah families and property owners keep these appliances with us while they move or refit.',
                    'items' => [
                        ['fa-snowflake', 'Fridges and Freezers', 'Kept upright, clean and dry until your new kitchen is ready.'],
                        ['fa-tshirt', 'Washing Machines', 'Drained fully and stored with the drum held in place.'],
                        ['fa-fire', 'Gas and Electric Cookers', 'Cleaned of grease and stored ready to use again.'],
                        ['fa-wind', 'Window and Split AC Units', 'AC units removed from a flat during a move or refit.'],
                        ['fa-tv', 'TVs and Sound Systems', 'Screens and speakers wrapped and stored with their cables.'],
                        ['fa-blender', 'Small Kitchen Appliances', 'Kettles, mixers and toasters packed in one box.'],
                    ],
                    'why' => [
                        ['fa-tags', 'Lower Cost', 'Storing appliances in Sharjah usually costs less than in Dubai.'],
                        ['fa-people-carry', 'Heavy Lifting Done for You', 'We carry fridges and washers down the stairs.'],
                        ['fa-tint-slash', 'Dry Space', 'A dry unit protects motors, seals and wiring.'],
                        ['fa-building', 'Help for Property Owners', 'Keep appliances safe between tenants.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Simple Appliance Storage Tips for Sharjah Homes',
                        'intro' => 'Follow these easy steps and your appliances will work properly when they come out of storage.',
                        'points' => [
                            [
                                'fa-broom',
                                'Clean Inside and Out',
                                'Wipe fridges, ovens and washing machines with mild soap and dry them well. Food and dirt left inside cause smells.',
                            ],
                            [
                                'fa-arrow-up',
                                'Keep Fridges Standing',
                                'Always store and move fridges upright. Lying them flat can damage the cooling system.',
                            ],
                            [
                                'fa-tape',
                                'Tape the Doors Safely',
                                'Tape doors shut only for the journey. Once in storage, open them a little to let air in.',
                            ],
                            [
                                'fa-file-alt',
                                'Keep the Manuals',
                                'Put manuals and warranty cards in a bag with each appliance. You will need them when you set it up again.',
                            ],
                        ],
                    ],
                ],
            ],
            'luggage-storage' => [
                'dubai' => [
                    'metaTitle' => 'Luggage Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Secure luggage storage in Al Quoz, Dubai for suitcases and travel gear. Short or long stays, 24/7 access and collection. Get a free quote today.',
                    'h1Accent' => 'Store Your Bags While You Travel',
                    'lead' => 'Luggage storage in Dubai at our Al Quoz facility: secure space for suitcases and travel bags, from a few days to a whole summer, with 24/7 access around your flights and collection from your hotel or home.',
                    'overviewTitle' => 'Luggage Storage for Dubai Travellers',
                    'overview' => [
                        'Luggage storage in Dubai is useful more often than people expect. Residents leave for the summer, visitors move between hotels and apartments, and new arrivals wait for a flat to be ready.',
                        'Our Al Quoz facility gives your bags a secure home in the meantime. It is a short drive from Sheikh Zayed Road, so you can drop off on the way to Dubai International Airport (DXB), and 24/7 access means late-night flights are not a problem.',
                        'Short-term luggage storage suits a few days or weeks, and longer stays cover a whole summer or a posting abroad. Keep valuables and documents in your carry-on rather than your stored bags, and label every suitcase with your name and phone number.',
                        'Luggage storage also helps when friends or family visit Dubai for a long holiday. Their extra bags can stay with us, so your flat does not fill up with suitcases. When they are ready to fly home, we can bring the bags back or they can collect them at any time.',
                    ],
                    'faqs' => [
                        [
                            'Can I store luggage for just a few days?',
                            'Yes. Store for a few days, a few weeks or a whole summer. Tell us your dates and we will quote.',
                        ],
                        [
                            'Can I collect my bags late at night?',
                            'Yes. The Dubai facility offers 24/7 access, so you can collect around your flights.',
                        ],
                        ['Can you pick up luggage from my hotel?', 'Yes. Our team can collect from hotels, homes and offices across Dubai.'],
                        ['Is my luggage secure?', 'Your bags are kept in a private space protected by 24/7 CCTV and controlled entry.'],
                        [
                            'Can I drop off luggage on the way to the airport?',
                            'Yes. The Dubai facility is open to customers 24/7 and a short drive from Sheikh Zayed Road, so you can drop off before heading to DXB.',
                        ],
                        [
                            'Is short-term luggage storage available?',
                            'Yes. You can store bags for a few days or weeks. Tell us your dates and we will send a quote.',
                        ],
                    ],
                    'itemsIntro' => 'Dubai residents, visitors and new arrivals store bags with us for a few days or a few months.',
                    'items' => [
                        ['fa-suitcase', 'Suitcases Between Hotels', 'Bags kept safe while you move between hotels or short-stay apartments.'],
                        ['fa-plane-departure', 'Summer Holiday Luggage', 'Extra bags you do not take on your summer trip.'],
                        ['fa-key', 'Bags for New Arrivals', 'Luggage stored while you wait for your Dubai flat to be ready.'],
                        ['fa-golf-ball', 'Golf and Sports Bags', 'Golf clubs and sports bags between trips and seasons.'],
                        ['fa-box-open', 'Boxes Before Shipping', 'Packed boxes waiting for a shipment to your next country.'],
                        ['fa-hiking', 'Travel and Camping Kit', 'Backpacks, tents and travel gear you use a few times a year.'],
                    ],
                    'why' => [
                        ['fa-road', 'Near Sheikh Zayed Road', 'Easy to reach on the way to the airport.'],
                        ['fa-clock', 'Open 24/7', 'Drop off or collect around any flight time.'],
                        ['fa-lock', 'Safe and Private', 'Your bags stay in a secure, monitored space.'],
                        ['fa-truck-moving', 'Hotel Pick-Up', 'We collect from hotels and homes in Dubai.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Smart Luggage Storage Tips for Dubai',
                        'intro' => 'These simple tips help you store bags safely and travel lighter in and out of Dubai.',
                        'points' => [
                            [
                                'fa-passport',
                                'Keep Documents With You',
                                'Never leave passports, Emirates ID, cash or medicines in stored bags. Keep them in your hand luggage.',
                            ],
                            [
                                'fa-tag',
                                'Label Every Bag',
                                'Put your name and phone number on each bag. It makes drop-off and pick-up quick and easy.',
                            ],
                            [
                                'fa-tshirt',
                                'Pack Clothes in Sealed Bags',
                                'Put clothes in sealed or vacuum bags inside the suitcase. They stay clean and fresh for weeks.',
                            ],
                            [
                                'fa-calendar-alt',
                                'Share Your Dates',
                                'Tell us when you drop off and when you will be back. We will have your bags ready when you return.',
                            ],
                        ],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Luggage Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable luggage storage in Sharjah for suitcases, student belongings and travel gear. 24/7 access and collection service. Get a free quote today.',
                    'h1Accent' => 'For Students, Expats & Travellers',
                    'lead' => 'Luggage storage in Sharjah at our main facility: affordable space for suitcases, student belongings and travel gear, from a single bag to a full room, with 24/7 access and collection across Sharjah and Ajman.',
                    'overviewTitle' => 'Luggage Storage for Sharjah Students and Travellers',
                    'overview' => [
                        'Luggage storage in Sharjah is popular with students and travellers. Students at University City pack up every summer, and many residents leave the UAE for weeks at a time, so carrying everything home or paying rent on an empty room makes little sense.',
                        'Our main facility keeps suitcases, boxes and room contents safe until you return, at a lower cost than storage in central Dubai. Drop off before your flight from Sharjah International Airport, or let our team collect from your accommodation.',
                        'Store for a short break or the whole summer. Keep passports, medicines and valuables with you, pack clothes in sealed bags inside your suitcases, and label every item with your name and phone number.',
                        'Luggage storage is also handy for new residents in Sharjah. If you arrive before your flat is ready, you can keep your suitcases with us instead of paying for a hotel room just to hold your bags. When your keys arrive, we can deliver everything to your new home.',
                    ],
                    'faqs' => [
                        [
                            'Do students use your Sharjah storage?',
                            'Yes. Students from University City and nearby areas store their belongings with us over semester breaks and summer holidays.',
                        ],
                        [
                            'Can I store luggage for the whole summer?',
                            'Yes. Store for as long as you are away, and collect when you are back.',
                        ],
                        [
                            'Can you collect bags from my accommodation?',
                            'Yes. Our team collects from homes and student accommodation across Sharjah, Ajman and Dubai.',
                        ],
                        ['Can I drop off at any time?', 'Yes. The Sharjah facility offers 24/7 access for customers.'],
                        [
                            'Can I store bags for a short trip?',
                            'Yes. Short-term luggage storage in Sharjah suits a few days or weeks as well as a whole summer.',
                        ],
                        [
                            'What should I not leave in stored luggage?',
                            'Keep passports, cash, jewellery and medicines with you. Food, liquids that can leak and anything flammable should not go into storage.',
                        ],
                        [
                            'Can friends share one storage space?',
                            'Yes. Friends and classmates often share one space to split the cost. Just label every bag with the owner’s name.',
                        ],
                    ],
                    'itemsIntro' => 'Students, expats and travellers in Sharjah keep these items with us while they are away.',
                    'items' => [
                        ['fa-graduation-cap', 'University Room Contents', 'Bags, bedding and books from University City over the summer.'],
                        ['fa-suitcase', 'Spare Suitcases', 'Empty or extra suitcases that take up space in a small flat.'],
                        ['fa-plane', 'Bags During Long Trips', 'Luggage kept while you visit family abroad for weeks.'],
                        ['fa-box', 'Boxes From a Room Move', 'Boxes kept while you change rooms or flats in Sharjah.'],
                        ['fa-music', 'Hobby Items', 'Musical instruments, sports bags and hobby kit kept safe.'],
                        ['fa-briefcase', 'Work Travel Bags', 'Work bags and samples kept between business trips.'],
                    ],
                    'why' => [
                        ['fa-tags', 'Student-Friendly Prices', 'Storage in Sharjah usually costs less than in Dubai.'],
                        ['fa-university', 'Near University City', 'Easy for students to drop off before the summer.'],
                        ['fa-clock', 'Open 24/7', 'Drop off or collect at any time.'],
                        ['fa-truck-moving', 'We Collect', 'Pick-up from student housing and homes.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Luggage Storage Tips for Sharjah Students and Travellers',
                        'intro' => 'Planning ahead makes the end of term or a long trip much easier. Here are a few easy tips.',
                        'points' => [
                            [
                                'fa-calendar-alt',
                                'Book Before Exams End',
                                'The end of term is busy. Book your storage a week early so collection fits your plans.',
                            ],
                            [
                                'fa-users',
                                'Share With a Friend',
                                'Students can share one space to split the cost. Label each bag with the owner’s name.',
                            ],
                            [
                                'fa-ban',
                                'Leave Out Food and Liquids',
                                'Do not store food, drinks or anything that can leak or spoil. It can damage your other things.',
                            ],
                            [
                                'fa-mobile-alt',
                                'Keep Our Number Handy',
                                'Save our phone and WhatsApp number so you can arrange pick-up or delivery from wherever you are.',
                            ],
                        ],
                    ],
                ],
            ],
            'box-storage' => [
                'dubai' => [
                    'metaTitle' => 'Box Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Box storage in Al Quoz, Dubai for packed belongings, documents and seasonal items. Secure, climate-controlled units and collection. Get a free quote.',
                    'h1Accent' => 'Packed, Labelled & Secure',
                    'lead' => 'Box storage in Dubai at our Al Quoz facility: secure space for packed boxes, documents and seasonal items, from a handful of cartons to a full move, with climate-controlled units for papers and keepsakes.',
                    'overviewTitle' => 'Box Storage for Dubai Apartments and Offices',
                    'overview' => [
                        'Box storage in Dubai is the simplest way to free up an apartment or small office. Boxes of seasonal clothes, books, old paperwork and things you are not ready to unpack pile up quickly, and they are easy to store for as long as you need.',
                        'Store them at our Al Quoz facility and pay only for the space they take. Climate-controlled units help keep documents, archives and photos dry, because humidity softens cardboard and damages paper over a Dubai summer.',
                        'Use strong cardboard boxes of similar sizes, or plastic tubs for anything that must stay dry, fill them fully so they do not crush, and label each box on the top and one side. Keep a simple list of what is in each box so you can find one item quickly.',
                        'Box storage works for small businesses too. Online sellers, consultants and small offices often keep stock cartons, samples and old files with us instead of paying for a bigger office. You pay only for the space the boxes need, and you can add or remove boxes whenever you like.',
                    ],
                    'faqs' => [
                        [
                            'Can I store just a few boxes?',
                            'Yes. Small spaces suit a handful of boxes, and you can move to a bigger one if you need it.',
                        ],
                        [
                            'Is box storage good for documents?',
                            'Yes. Climate-controlled units at our Dubai facility help keep paperwork and photos dry and in good condition.',
                        ],
                        ['Can you collect boxes from my apartment?', 'Yes. Our team collects boxes from homes and offices across Dubai.'],
                        [
                            'How should I pack boxes for storage?',
                            'Use strong boxes of similar sizes, fill them fully so they do not crush, and label each one on the top and side.',
                        ],
                        [
                            'Should I use cardboard boxes or plastic tubs?',
                            'Cardboard is fine for most items in a climate-controlled unit. Use sealed plastic tubs for documents, photos and textiles that need extra protection.',
                        ],
                        [
                            'Can I store business archives?',
                            'Yes. Box storage in Dubai suits business records and archives, and the staff you authorise can access them 24/7.',
                        ],
                        [
                            'How many boxes fit in a small unit?',
                            'It depends on the box size and how high you stack. Tell us roughly how many boxes you have and we will suggest the right space.',
                        ],
                    ],
                    'itemsIntro' => 'Dubai apartment owners and small offices store these kinds of boxes with us.',
                    'items' => [
                        ['fa-dolly', 'Boxes Between Apartments', 'Packed boxes waiting while your next Dubai flat gets ready.'],
                        ['fa-file-alt', 'Office Paperwork', 'Old files, invoices and records from a Dubai office.'],
                        ['fa-tshirt', 'Winter and Travel Clothes', 'Clothes you only wear when you travel or in winter.'],
                        ['fa-book', 'Book Collections', 'Books kept dry in a climate-controlled unit.'],
                        ['fa-boxes', 'Online Shop Cartons', 'Stock cartons for a small online business.'],
                        ['fa-images', 'Photos and Albums', 'Family photos and albums kept cool and away from damp.'],
                    ],
                    'why' => [
                        ['fa-snowflake', 'Dry for Paper', 'Climate-controlled units stop boxes and papers from going soft.'],
                        ['fa-ruler', 'Small Spaces Available', 'Store just a handful of boxes if that is all you need.'],
                        ['fa-search', 'Easy to Find', 'Stack and label boxes so you can find one quickly.'],
                        ['fa-truck-moving', 'Door Pick-Up', 'We collect boxes from homes and offices in Dubai.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'How to Pack Boxes for Storage in Dubai',
                        'intro' => 'Good packing protects your things and saves space. These are the tips our Al Quoz team shares most often.',
                        'points' => [
                            [
                                'fa-box',
                                'Use the Same Box Sizes',
                                'Boxes of the same size stack neatly and take less space. Avoid very large boxes, which become too heavy to lift.',
                            ],
                            [
                                'fa-compress',
                                'Fill Boxes to the Top',
                                'Half-empty boxes can collapse when others sit on them. Fill gaps with paper or soft clothes.',
                            ],
                            [
                                'fa-pen',
                                'Write on Two Sides',
                                'Label the top and one side of each box. You can read the label even when boxes are stacked.',
                            ],
                            [
                                'fa-tint',
                                'Protect Papers From Damp',
                                'Put important papers and photos in sealed plastic tubs. Humidity can soften cardboard and spoil paper over a Dubai summer.',
                            ],
                        ],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Box Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable box storage in Sharjah for packed belongings, documents and business stock. Secure units, collection and flexible terms. Get a free quote.',
                    'h1Accent' => 'Affordable Space for Packed Belongings',
                    'lead' => 'Box storage in Sharjah at our main facility: affordable space for packed belongings, documents and small business stock, from a few cartons upwards, with collection from your door.',
                    'overviewTitle' => 'Box Storage for Sharjah Homes and Small Businesses',
                    'overview' => [
                        'Box storage in Sharjah is an easy way to free up space at home or in a small office. Boxes are simple to stack, quick to collect and inexpensive to keep for a long time.',
                        'Our main facility keeps packed belongings, archives and cartons of stock secure at a lower cost than central Dubai, so long-term box storage stays affordable. Small businesses keep samples, records and marketing material with us, and authorised staff can reach them.',
                        'Pack in strong cardboard boxes or plastic tubs, fill each one fully, and label it on the top and side. Put the boxes you need most at the front, and keep documents and photos in sealed tubs to protect them from humidity.',
                        'Boxes are also the cheapest way to store. They stack neatly and use the space well, so you can keep a lot of things in a small unit. This makes box storage in Sharjah a smart choice for students, small families and anyone who wants to store for a long time on a tight budget.',
                    ],
                    'faqs' => [
                        [
                            'Can small businesses store stock in boxes?',
                            'Yes. Many small businesses keep cartons of stock, samples and records with us, and authorised staff can access them.',
                        ],
                        [
                            'Can I store just a few boxes?',
                            'Yes. Small spaces suit a handful of boxes, and you can move to a bigger one later.',
                        ],
                        [
                            'Do you collect boxes from Ajman?',
                            'Yes. Our team collects from homes and offices across Sharjah, Ajman and Dubai.',
                        ],
                        [
                            'How do I find a box quickly?',
                            'Label every box on the top and side, keep a simple list, and put the boxes you need most at the front.',
                        ],
                        [
                            'Do you sell boxes and packing materials?',
                            'Yes. Boxes, tape and other packing materials are available from the StorageKeys shop.',
                        ],
                        [
                            'How long can boxes stay in storage?',
                            'As long as you need. Box storage in Sharjah runs on monthly terms, so you can keep boxes for a few months or several years.',
                        ],
                        [
                            'Can I add or take out boxes later?',
                            'Yes. You can visit at any time to add boxes or take some home, and change to a smaller or bigger space when you need to.',
                        ],
                    ],
                    'itemsIntro' => 'Sharjah homes, students and small businesses keep these boxes with us.',
                    'items' => [
                        ['fa-home', 'Household Boxes', 'Packed boxes from a move or a clear-out at home.'],
                        ['fa-file-invoice', 'Small Business Records', 'Invoices and files your business must keep for years.'],
                        ['fa-graduation-cap', 'Student Boxes', 'Books and room items from University City over the holidays.'],
                        ['fa-store', 'Shop Samples and Stock', 'Cartons of samples, stock and marketing material.'],
                        ['fa-tree', 'Festive Decorations', 'Eid and holiday decorations kept until next year.'],
                        ['fa-puzzle-piece', 'Toys and Baby Items', 'Outgrown toys and baby things saved for later.'],
                    ],
                    'why' => [
                        ['fa-tags', 'Budget-Friendly', 'Box storage in Sharjah usually costs less than in Dubai.'],
                        ['fa-box', 'Start Small', 'Store a few boxes and add more any time.'],
                        ['fa-user-check', 'Staff Access', 'Small businesses can register staff to reach their boxes.'],
                        ['fa-truck-moving', 'Pick-Up in Three Emirates', 'We collect from Sharjah, Ajman and Dubai.'],
                    ],
                    'guide' => [
                        'eyebrow' => 'Good to Know',
                        'title' => 'Simple Box Storage Tips for Sharjah',
                        'intro' => 'Use these easy tips to keep your boxes safe, tidy and simple to find at our main facility.',
                        'points' => [
                            [
                                'fa-sort-numeric-down',
                                'Number Your Boxes',
                                'Give every box a number and keep a list of what each one holds. Finding one item becomes quick and easy.',
                            ],
                            [
                                'fa-weight-hanging',
                                'Keep Boxes Light',
                                'Put heavy things like books in small boxes and light things in big boxes. This keeps every box easy to carry.',
                            ],
                            [
                                'fa-arrow-up',
                                'Stack With Care',
                                'Put the strongest boxes at the bottom and fragile ones on top. Mark fragile boxes clearly.',
                            ],
                            [
                                'fa-calendar-check',
                                'Review Once a Year',
                                'Look through your boxes each year. Throw away what you no longer need and save on space.',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
