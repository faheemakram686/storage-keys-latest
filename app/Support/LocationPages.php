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
            'eyebrow' => $base['label'] . ' — ' . $facility['shortName'],
            'h1' => $base['label'] . ' in ' . $facility['city'],
            'storageType' => $base['storageType'],
            'image' => $base['image'],
            'ticks' => $base['ticks'],
            'itemsTitle' => $base['itemsTitle'] . ' in ' . $facility['city'],
            'itemsIntro' => $base['itemsIntro'],
            'items' => $base['items'],
            'why' => $base['why'],
            'whyTitle' => 'Why Choose StorageKeys for ' . $base['label'] . ' in ' . $facility['city'],
            'servicesEyebrow' => 'More in ' . $facility['city'],
            'servicesTitle' => 'Other Storage Services in ' . $facility['city'],
            'servicesIntro' => 'Need to store something else? These services are available from the same facility.',
            'services' => $others,
            'compareTitle' => $base['label'] . ' in Dubai and Sharjah',
            'compareIntro' => 'The same service is available at both of our facilities. Choose the one closest to you.',
            'compare' => array_map(static fn ($c) => [
                'slug' => self::serviceSlug($service, $c),
                'city' => self::facilities()[$c]['city'],
                'title' => $base['label'] . ' in ' . self::facilities()[$c]['city'],
                'text' => self::facilities()[$c]['compareText'],
                'cta' => 'View ' . $base['label'] . ' in ' . self::facilities()[$c]['city'],
            ], self::CITIES),
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
                'facilityName' => 'Storage Keys — Dubai',
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
                'facilityName' => 'Storage Keys — Sharjah',
                'addressHtml' => 'Plot # 4202<br>Sharjah, UAE',
                'schemaAddress' => ['streetAddress' => 'Plot # 4202', 'addressLocality' => 'Sharjah', 'addressRegion' => 'Sharjah'],
                'schemaAreas' => ['Sharjah', 'Ajman'],
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
                'lead' => 'Secure, climate-controlled storage units in Al Quoz Industrial Area 3 for households, expats and businesses across Dubai, with 24/7 access, CCTV, collection from your door and flexible terms.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/corridor area-80kb.jpg',
                'overviewTitle' => 'Storage Space in the Heart of Dubai',
                'overview' => [
                    'Dubai homes are getting smaller and commercial rents keep rising, so finding room for furniture, seasonal items, stock or archives is a constant challenge. A storage unit gives you that space without moving house or signing a bigger lease.',
                    'Our Dubai facility sits on 10A Street in Al Quoz Industrial Area 3, a short drive from Sheikh Zayed Road and Al Khail Road, within easy reach of Downtown, Business Bay, Al Barsha, JLT and Dubai Marina.',
                ],
                'ticks' => ['24/7 access', 'CCTV & controlled entry', 'Climate-controlled units', 'Collection from your door', 'Flexible monthly terms', 'Units for homes & businesses'],
                'whyTitle' => 'Why Dubai Residents and Businesses Choose Us',
                'why' => [
                    ['fa-map-marked-alt', 'Central Location', 'Al Quoz is minutes from Sheikh Zayed Road, so drop-offs fit into a normal day.'],
                    ['fa-shield-alt', 'Secure Around the Clock', '24/7 CCTV and controlled entry. Only you and people you authorise get in.'],
                    ['fa-snowflake', 'Built for the UAE Climate', 'Climate-controlled units keep furniture, electronics and documents safe in summer.'],
                    ['fa-sliders-h', 'Flexible, Clear Pricing', 'No long fixed lease. Pay for the space you use and resize any time.'],
                ],
                'quoteText' => 'Tell us what you need to store and for how long. We will recommend the right unit at our Al Quoz facility and send a clear quote.',
                'faqs' => [
                    ['Where is your Dubai storage facility?', 'Our Dubai facility is on 10A Street in Al Quoz Industrial Area 3, close to Sheikh Zayed Road and Al Khail Road, so it is easy to reach from most of the city.'],
                    ['Can I access my unit at any time?', 'Yes. The Dubai facility offers 24/7 access for customers, with CCTV and controlled entry protecting every unit.'],
                    ['Do you collect items from homes and offices in Dubai?', 'Yes. Our moving team can collect from your home or office anywhere in Dubai and bring everything to your unit, or you can drop items off yourself.'],
                    ['How long do I need to book storage for?', 'Terms are flexible. You can store for a few weeks during a move or renovation, or for months and years, and change your unit size as your needs change.'],
                ],
            ],
            'sharjah' => [
                'metaTitle' => 'Self Storage in Sharjah | StorageKeys',
                'metaDescription' => 'Affordable, secure self storage in Sharjah for homes and businesses. 24/7 access, CCTV, collection service and flexible terms at our main facility. Get a free quote.',
                'eyebrow' => 'Sharjah — Main Facility',
                'h1Accent' => 'Secure, Affordable Units',
                'lead' => 'Secure storage units at our main Sharjah facility for households, students, expats and businesses across Sharjah, Ajman and Dubai, with 24/7 access, CCTV, collection from your door and flexible terms.',
                'image' => 'sk-assets/assets/images/frontend/landing-page/peace-of-mind-80kb.jpg',
                'overviewTitle' => 'Our Main Storage Facility in Sharjah',
                'overview' => [
                    'StorageKeys has served customers from Sharjah since 2016. Our main facility and office at Plot # 4202 is where most of our storage, moving and customer service is run from.',
                    'Space in Sharjah generally costs less than in central Dubai, so it is a practical choice for long-term storage, larger units and businesses holding bulk stock, and close to home for the many families who live in Sharjah and work in Dubai.',
                ],
                'ticks' => ['24/7 access', 'CCTV & controlled entry', 'Our main office on site', 'Collection from your door', 'Flexible monthly terms', 'Cost-effective larger units'],
                'whyTitle' => 'Why Sharjah Residents and Businesses Choose Us',
                'why' => [
                    ['fa-tags', 'Lower Cost Than Central Dubai', 'Larger units and long-term storage stay affordable without cutting security.'],
                    ['fa-shield-alt', 'Secure Around the Clock', '24/7 CCTV and controlled entry. Only you and people you authorise get in.'],
                    ['fa-headset', 'Main Office On Site', 'Questions, viewings and paperwork are handled in person by our team.'],
                    ['fa-award', 'Serving Since 2016', 'Years of helping UAE households and businesses move and store.'],
                ],
                'quoteText' => 'Tell us what you need to store and for how long. We will recommend the right unit at our Sharjah facility and send a clear quote.',
                'faqs' => [
                    ['Where is your Sharjah storage facility?', 'Our main facility and office are at Plot # 4202 in Sharjah. Use the Get Directions button on this page or call us and we will guide you in.'],
                    ['Is storage in Sharjah cheaper than in Dubai?', 'Space in Sharjah generally costs less than in central Dubai, which makes our Sharjah facility a cost-effective choice for long-term storage, bulk stock and larger units.'],
                    ['Can I access my unit at any time?', 'Yes. The Sharjah facility offers 24/7 access for customers, with CCTV and controlled entry protecting every unit.'],
                    ['Do you collect from homes and offices in Sharjah, Ajman and Dubai?', 'Yes. Our moving team collects from homes and offices across Sharjah, Ajman and Dubai and brings everything to your unit, or you can drop items off yourself.'],
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
                    'lead' => 'Warehouse space in Al Quoz for Dubai retailers, e-commerce brands and trading companies that need room for stock without the cost and commitment of a long warehouse lease.',
                    'overviewTitle' => 'Warehouse Space Close to Dubai’s Main Roads',
                    'overview' => [
                        'Warehouse rents in Dubai’s industrial areas are high, and most leases tie you in for years. For growing businesses with stock levels that rise and fall through the year, that is a poor fit.',
                        'Our Al Quoz facility sits between Sheikh Zayed Road and Al Khail Road, so stock can reach customers in Downtown, Business Bay, Al Barsha and New Dubai quickly. Take the space you need now and adjust it as your business changes.',
                    ],
                    'faqs' => [
                        ['Where is your Dubai warehouse storage?', 'At our Al Quoz facility on 10A Street, Al Quoz Industrial Area 3, close to Sheikh Zayed Road and Al Khail Road.'],
                        ['Do I need to sign a long lease?', 'No. Warehouse storage is quote-based and flexible, so you can add or release space as stock levels change.'],
                        ['Can my staff access the stock?', 'Yes. Staff you authorise can access your space 24/7, and you can update the list at any time.'],
                        ['Can you collect stock from suppliers or our office?', 'Yes. Our team can collect from suppliers, offices or shops across Dubai and deliver stock where you need it.'],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Warehouse Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Cost-effective warehouse storage in Sharjah for bulk stock, inventory and equipment. Flexible terms, 24/7 CCTV and collection service. Get a free quote.',
                    'h1Accent' => 'Cost-Effective Space for Stock',
                    'lead' => 'Warehouse space at our main Sharjah facility for trading companies, distributors and online sellers who need room for bulk stock at a lower cost than central Dubai.',
                    'overviewTitle' => 'Affordable Warehouse Space in Sharjah',
                    'overview' => [
                        'Sharjah has long been the base for UAE traders and distributors, because space costs less than in Dubai and the roads into both emirates are close by.',
                        'Our main facility gives you that advantage without a warehouse lease. Keep bulk and slow-moving stock in Sharjah, supply customers across Sharjah, Ajman and Dubai, and scale your space with the seasons.',
                    ],
                    'faqs' => [
                        ['Where is your Sharjah warehouse storage?', 'At our main facility and office, Plot # 4202 in Sharjah. Use the Get Directions button on this page to find us.'],
                        ['Is warehouse storage cheaper in Sharjah than in Dubai?', 'Space in Sharjah generally costs less than in central Dubai, which makes it a cost-effective place to hold bulk and slow-moving stock.'],
                        ['Can I increase space before a busy season?', 'Yes. Tell us your expected volumes and we will plan extra space ahead of Ramadan, DSF or year-end peaks.'],
                        ['Do you deliver stock to Dubai?', 'Yes. Our team can collect and deliver stock across Sharjah, Ajman and Dubai.'],
                    ],
                ],
            ],
            'personal-storage' => [
                'dubai' => [
                    'metaTitle' => 'Personal Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Personal storage units in Al Quoz, Dubai for belongings, boxes and seasonal items. 24/7 access, CCTV and climate-controlled units. Get a free quote.',
                    'h1Accent' => 'Extra Space for Dubai Homes',
                    'lead' => 'Private storage units in Al Quoz for Dubai residents who need extra space for belongings, seasonal items and furniture, with 24/7 access and collection from your door.',
                    'overviewTitle' => 'More Room Without Moving Home',
                    'overview' => [
                        'Many Dubai apartments have no storeroom, and villas fill up fast. A personal storage unit gives you a spare room across town, so your home stays clear and liveable.',
                        'Our Al Quoz facility is a short drive from Al Barsha, Jumeirah, Downtown and the Marina, and climate-controlled units protect belongings from the summer heat.',
                    ],
                    'faqs' => [
                        ['How close is your Dubai facility to me?', 'Our facility is in Al Quoz Industrial Area 3, minutes from Sheikh Zayed Road and Al Khail Road, so it is quick to reach from most Dubai communities.'],
                        ['What size unit do I need?', 'It depends on what you store. Tell us your items or room count and we will recommend a size. You can change it later.'],
                        ['Are the units climate controlled?', 'Climate-controlled units are available at our Dubai facility for items sensitive to heat and humidity.'],
                        ['Can you collect my belongings?', 'Yes. Our moving team can collect from anywhere in Dubai, or you can drop off yourself 24/7.'],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Personal Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable personal storage in Sharjah for belongings, boxes and seasonal items. 24/7 access, CCTV and collection service. Get a free quote today.',
                    'h1Accent' => 'Affordable Space Near Home',
                    'lead' => 'Affordable personal storage units at our main Sharjah facility for families, students and expats across Sharjah and Ajman, with 24/7 access and collection from your door.',
                    'overviewTitle' => 'Personal Storage for Sharjah Families',
                    'overview' => [
                        'Sharjah families often live in busy apartments where every cupboard is full. A storage unit nearby gives you room for the things you want to keep but do not use every day.',
                        'Our main facility serves Al Nahda, Al Majaz, Al Taawun, Muwaileh and University City, and storage in Sharjah usually costs less than in Dubai, which helps if you are storing for a long time.',
                    ],
                    'faqs' => [
                        ['Where is your Sharjah facility?', 'Our main facility and office are at Plot # 4202 in Sharjah. Use the Get Directions button on this page to find us.'],
                        ['Is this a good option for students?', 'Yes. Students from University City and nearby areas store room contents with us over semester breaks and summer holidays.'],
                        ['How much space will I need?', 'Tell us what you plan to store and we will recommend a size. You can move to a bigger or smaller unit later.'],
                        ['Do you collect from Ajman too?', 'Yes. Our team collects from homes across Sharjah, Ajman and Dubai.'],
                    ],
                ],
            ],
            'furniture-storage' => [
                'dubai' => [
                    'metaTitle' => 'Furniture Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Furniture storage in Al Quoz, Dubai for sofas, beds and wardrobes during moves and renovations. Climate-controlled units and collection. Get a quote.',
                    'h1Accent' => 'Safe Space During Moves & Renovations',
                    'lead' => 'Secure furniture storage in Al Quoz for Dubai homes and offices, with climate-controlled units, careful handling and collection from anywhere in the city.',
                    'overviewTitle' => 'Furniture Storage for Dubai Moves and Fit-Outs',
                    'overview' => [
                        'Dubai tenancies change often, and renovations, villa refurbishments and gaps between homes all leave furniture with nowhere to go.',
                        'Our Al Quoz facility keeps it safe until you are ready. Climate-controlled units protect wood, leather and fabric from the heat and humidity that can warp and crack furniture in a hot garage or balcony.',
                    ],
                    'faqs' => [
                        ['Can you collect furniture from my home in Dubai?', 'Yes. Our team collects, wraps and loads furniture from homes and offices anywhere in Dubai.'],
                        ['Is climate-controlled storage available?', 'Yes. Climate-controlled units at our Al Quoz facility help protect wooden and upholstered furniture from heat and humidity.'],
                        ['Should I dismantle furniture before storage?', 'Where possible, yes. Removing legs and taking apart wardrobes and beds saves space and lowers the risk of damage.'],
                        ['How long can I store furniture?', 'As long as you need, from a few weeks during a renovation to months or years between homes.'],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Furniture Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable furniture storage in Sharjah for sofas, beds and wardrobes during moves and renovations. Collection service and flexible terms. Get a quote.',
                    'h1Accent' => 'Affordable Space for Every Piece',
                    'lead' => 'Affordable furniture storage at our main Sharjah facility for households and offices across Sharjah, Ajman and Dubai, with careful handling and collection from your door.',
                    'overviewTitle' => 'Furniture Storage at Sharjah Prices',
                    'overview' => [
                        'Furniture takes up a lot of space, so storing a full home can get expensive. In Sharjah, the same space usually costs less than in central Dubai.',
                        'That makes our main facility a sensible choice for longer storage, such as between tenancies, during a villa renovation or while you are away from the UAE for a while.',
                    ],
                    'faqs' => [
                        ['Do you collect furniture across Sharjah and Ajman?', 'Yes. Our team collects, wraps and loads furniture from homes and offices across Sharjah, Ajman and Dubai.'],
                        ['Is Sharjah cheaper for storing a full home?', 'Space in Sharjah generally costs less than in central Dubai, which adds up when you are storing a lot of furniture for a long time.'],
                        ['How should furniture be prepared?', 'Clean and dry everything, dismantle large pieces where possible and cover upholstery. Our team can help with wrapping.'],
                        ['Can I add more furniture later?', 'Yes. You can add items or move to a bigger unit at any time.'],
                    ],
                ],
            ],
            'appliance-storage' => [
                'dubai' => [
                    'metaTitle' => 'Appliance Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Appliance storage in Al Quoz, Dubai for fridges, washing machines, cookers and electronics. Secure units, collection and flexible terms. Get a quote.',
                    'h1Accent' => 'Fridges, Washers & Electronics',
                    'lead' => 'Secure appliance storage in Al Quoz for Dubai homes, landlords and businesses, with collection of heavy appliances and climate-controlled units for electronics.',
                    'overviewTitle' => 'Appliance Storage for Dubai Homes and Landlords',
                    'overview' => [
                        'Moving into a furnished apartment, renovating a kitchen or replacing appliances in a rental unit often leaves a fridge or washer with nowhere to go.',
                        'Our Al Quoz facility stores them safely until you need them again. Climate-controlled units help protect electronics and TV screens from heat and humidity.',
                    ],
                    'faqs' => [
                        ['Can you move heavy appliances?', 'Yes. Our team collects and loads fridges, washers and cookers from homes and offices across Dubai.'],
                        ['How should I prepare a fridge for storage?', 'Defrost it, clean and dry it fully, and leave the door slightly open in storage to prevent smells and mould.'],
                        ['Can I store electronics with appliances?', 'Yes. TVs and electronics can go in climate-controlled units at our Dubai facility.'],
                        ['Do landlords use appliance storage?', 'Yes. Landlords often store appliances between tenancies or when a unit is let unfurnished.'],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Appliance Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable appliance storage in Sharjah for fridges, washing machines and cookers. Secure units, collection service and flexible terms. Get a free quote.',
                    'h1Accent' => 'Safe Space for Big Appliances',
                    'lead' => 'Affordable appliance storage at our main Sharjah facility for families, landlords and businesses across Sharjah and Ajman, with collection of heavy items from your door.',
                    'overviewTitle' => 'Appliance Storage for Sharjah Moves',
                    'overview' => [
                        'Large appliances are awkward to keep in a small flat, especially between moves or while a kitchen is being redone.',
                        'Our Sharjah facility gives them a dry, secure place to wait, at a lower cost than storage in central Dubai, and our team handles the heavy lifting.',
                    ],
                    'faqs' => [
                        ['Can you collect appliances from Sharjah and Ajman?', 'Yes. Our team collects and loads appliances from homes and businesses across Sharjah, Ajman and Dubai.'],
                        ['How should I prepare a washing machine?', 'Drain it completely, dry the drum and seals, and secure the drum for transport if your model allows it.'],
                        ['Can I store appliances for a long time?', 'Yes. Clean, dry and disconnected appliances can be stored for months or years.'],
                        ['Is storage cheaper in Sharjah?', 'Space in Sharjah generally costs less than in central Dubai, which helps for longer storage.'],
                    ],
                ],
            ],
            'luggage-storage' => [
                'dubai' => [
                    'metaTitle' => 'Luggage Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Secure luggage storage in Al Quoz, Dubai for suitcases and travel gear. Short or long stays, 24/7 access and collection. Get a free quote today.',
                    'h1Accent' => 'Store Your Bags While You Travel',
                    'lead' => 'Secure luggage storage in Al Quoz for residents, visitors and students in Dubai, from a single suitcase to everything you own while you are away.',
                    'overviewTitle' => 'Luggage Storage for Dubai Travellers',
                    'overview' => [
                        'Dubai is always on the move. Residents travel for the summer, visitors stay between hotels and apartments, and new arrivals wait for a flat to be ready.',
                        'Our Al Quoz facility gives your bags a secure home in the meantime, with 24/7 access so you can drop off or collect around your flight times.',
                    ],
                    'faqs' => [
                        ['Can I store luggage for just a few days?', 'Yes. Store for a few days, a few weeks or a whole summer. Tell us your dates and we will quote.'],
                        ['Can I collect my bags late at night?', 'Yes. The Dubai facility offers 24/7 access, so you can collect around your flights.'],
                        ['Can you pick up luggage from my hotel?', 'Yes. Our team can collect from hotels, homes and offices across Dubai.'],
                        ['Is my luggage secure?', 'Your bags are kept in a private space protected by 24/7 CCTV and controlled entry.'],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Luggage Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable luggage storage in Sharjah for suitcases, student belongings and travel gear. 24/7 access and collection service. Get a free quote today.',
                    'h1Accent' => 'For Students, Expats & Travellers',
                    'lead' => 'Affordable luggage storage at our main Sharjah facility for students, expats and travellers across Sharjah and Ajman, from a single suitcase to a full room.',
                    'overviewTitle' => 'Luggage Storage for Sharjah Students and Travellers',
                    'overview' => [
                        'Students at University City pack up every summer, and many Sharjah residents leave the UAE for weeks at a time. Carrying everything home or paying for an empty room makes little sense.',
                        'Our Sharjah facility keeps suitcases, boxes and room contents safe until you return, at a lower cost than storage in central Dubai.',
                    ],
                    'faqs' => [
                        ['Do students use your Sharjah storage?', 'Yes. Students from University City and nearby areas store their belongings with us over semester breaks and summer holidays.'],
                        ['Can I store luggage for the whole summer?', 'Yes. Store for as long as you are away, and collect when you are back.'],
                        ['Can you collect bags from my accommodation?', 'Yes. Our team collects from homes and student accommodation across Sharjah, Ajman and Dubai.'],
                        ['Can I drop off at any time?', 'Yes. The Sharjah facility offers 24/7 access for customers.'],
                    ],
                ],
            ],
            'box-storage' => [
                'dubai' => [
                    'metaTitle' => 'Box Storage in Dubai, Al Quoz | StorageKeys',
                    'metaDescription' => 'Box storage in Al Quoz, Dubai for packed belongings, documents and seasonal items. Secure, climate-controlled units and collection. Get a free quote.',
                    'h1Accent' => 'Packed, Labelled & Secure',
                    'lead' => 'Box storage in Al Quoz for Dubai households and small businesses, from a handful of boxes to a full move, with climate-controlled units for papers and keepsakes.',
                    'overviewTitle' => 'Box Storage for Dubai Apartments and Offices',
                    'overview' => [
                        'Boxes pile up quickly in a Dubai apartment, whether they hold seasonal clothes, books, old paperwork or things you are not ready to unpack.',
                        'Store them at our Al Quoz facility and pay only for the space they take. Climate-controlled units help keep documents and photos dry and protected from the heat.',
                    ],
                    'faqs' => [
                        ['Can I store just a few boxes?', 'Yes. Small spaces suit a handful of boxes, and you can move to a bigger one if you need it.'],
                        ['Is box storage good for documents?', 'Yes. Climate-controlled units at our Dubai facility help keep paperwork and photos dry and in good condition.'],
                        ['Can you collect boxes from my apartment?', 'Yes. Our team collects boxes from homes and offices across Dubai.'],
                        ['How should I pack boxes for storage?', 'Use strong boxes of similar sizes, fill them fully so they do not crush, and label each one on the top and side.'],
                    ],
                ],
                'sharjah' => [
                    'metaTitle' => 'Box Storage in Sharjah | StorageKeys',
                    'metaDescription' => 'Affordable box storage in Sharjah for packed belongings, documents and business stock. Secure units, collection and flexible terms. Get a free quote.',
                    'h1Accent' => 'Affordable Space for Packed Belongings',
                    'lead' => 'Affordable box storage at our main Sharjah facility for families, students and small businesses across Sharjah and Ajman, with collection from your door.',
                    'overviewTitle' => 'Box Storage for Sharjah Homes and Small Businesses',
                    'overview' => [
                        'Boxes are the simplest way to free up space at home or in a small office, and they are easy to store for a long time.',
                        'Our Sharjah facility keeps packed belongings, records and small business stock secure at a lower cost than central Dubai, so long-term box storage stays affordable.',
                    ],
                    'faqs' => [
                        ['Can small businesses store stock in boxes?', 'Yes. Many small businesses keep cartons of stock, samples and records with us, and authorised staff can access them.'],
                        ['Can I store just a few boxes?', 'Yes. Small spaces suit a handful of boxes, and you can move to a bigger one later.'],
                        ['Do you collect boxes from Ajman?', 'Yes. Our team collects from homes and offices across Sharjah, Ajman and Dubai.'],
                        ['How do I find a box quickly?', 'Label every box on the top and side, keep a simple list, and put the boxes you need most at the front.'],
                    ],
                ],
            ],
        ];
    }
}
