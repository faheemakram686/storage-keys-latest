{{--
    "Explore More" internal-linking block, placed above the FAQ on service and industry pages.
    Usage: @include('ui.partials.explore-more', ['page' => 'personal-storage'])
    Edit $map to change which services and guides each page links to.
--}}
@php
    $services = [
        'personal-storage'           => 'Personal Storage',
        'residential-storage'        => 'Residential Storage',
        'furniture-storage'          => 'Furniture Storage',
        'box-storage'                => 'Box Storage',
        'appliance-storage'          => 'Appliance Storage',
        'business-storage'           => 'Business Storage',
        'warehouse-storage'          => 'Warehouse Storage',
        'climate-controlled-storage' => 'Climate-Controlled Storage',
        'moving-services'            => 'Moving Services',
        'luggage-storage'            => 'Luggage Storage',
        'car-storage'                => 'Car Storage',
        'short-term-storage'         => 'Short Term Storage',
        'vehicle-storage'            => 'Vehicle Storage',
        'long-term-storage'          => 'Long Term Storage',
        'office-storage'             => 'Office Storage',
        'equipment-storage'          => 'Equipment Storage',
        'construction-storage'       => 'Construction Storage',
        'pharmaceutical-storage'     => 'Pharmaceutical Storage',
        'art-storage'                => 'Art Storage',
        'boat-storage'               => 'Boat Storage',
    ];

    $guides = [
        'what-size-storage-unit-do-you-need'            => 'What Size Storage Unit Do You Actually Need?',
        'how-much-does-self-storage-cost-in-dubai'      => 'How Much Does Self Storage Cost in Dubai?',
        'apartment-storage-dubai'                       => 'Storage for Apartment Residents With No Storage Room',
        'downsizing-in-dubai-what-to-keep-sell-or-store' => 'Downsizing in Dubai: What to Keep, Sell, or Store',
        'furniture-storage-during-renovation'           => 'Storing Furniture During a Home Renovation',
        'how-to-pack-a-storage-unit-efficiently'        => 'How to Pack a Storage Unit Efficiently',
        'storage-for-expats-uae'                        => 'Storage for Expats Moving to or Leaving the UAE',
        'document-storage-dubai'                        => 'How to Store Books, Documents and Paperwork Safely',
        'seasonal-storage-uae'                          => 'Seasonal Clothing and Item Storage in the UAE',
        'what-can-and-cant-be-stored-in-a-storage-unit' => "What Can and Can't Be Stored in a Self Storage Unit?",
        'self-storage-vs-garage-or-spare-room'          => 'Self Storage vs Your Garage or Spare Room',
        'warehouse-vs-storage-unit'                     => 'Warehouse vs Storage Unit: What Does Your Business Need?',
        'ecommerce-storage-dubai'                       => 'E-commerce Stock Storage in the UAE Without a Long Warehouse Lease',
        'retail-stock-storage-dubai'                    => 'Retail Stock Storage in Dubai Before DSF and Year-End Sales',
        'climate-controlled-storage-worth-it'           => 'Is Climate-Controlled Storage Worth It for Personal Items?',
        'storing-fragile-items'                         => 'Storing Sentimental and Fragile Items Long Term',
        'student-storage-dubai'                         => 'Student Storage in Dubai Over Semester Breaks',
        'short-term-vs-long-term-storage'               => 'Short-Term vs Long-Term Storage: Which Do You Need?',
        'secure-storage-dubai'                          => 'Secure Storage in Dubai: How Security and 24/7 Access Work',
        'choose-best-storage-company-dubai'             => 'How to Choose a Self Storage Company in Dubai or Sharjah',
        'sports-equipment-storage'                      => 'Storing Sports Equipment and Bulky Gear',
    ];

    $industries = ['office-storage', 'equipment-storage', 'construction-storage', 'pharmaceutical-storage', 'art-storage', 'boat-storage'];

    $map = [
        'personal-storage'           => [['residential-storage', 'furniture-storage', 'box-storage', 'climate-controlled-storage', 'short-term-storage', 'long-term-storage'], ['what-size-storage-unit-do-you-need', 'how-much-does-self-storage-cost-in-dubai', 'apartment-storage-dubai']],
        'residential-storage'        => [['personal-storage', 'furniture-storage', 'appliance-storage', 'box-storage', 'moving-services', 'long-term-storage'], ['downsizing-in-dubai-what-to-keep-sell-or-store', 'furniture-storage-during-renovation', 'apartment-storage-dubai']],
        'furniture-storage'          => [['residential-storage', 'appliance-storage', 'box-storage', 'moving-services', 'climate-controlled-storage', 'long-term-storage'], ['furniture-storage-during-renovation', 'how-to-pack-a-storage-unit-efficiently', 'storage-for-expats-uae']],
        'box-storage'                => [['personal-storage', 'furniture-storage', 'office-storage', 'business-storage', 'short-term-storage', 'long-term-storage'], ['how-to-pack-a-storage-unit-efficiently', 'document-storage-dubai', 'seasonal-storage-uae']],
        'appliance-storage'          => [['furniture-storage', 'residential-storage', 'box-storage', 'moving-services', 'climate-controlled-storage', 'short-term-storage'], ['what-can-and-cant-be-stored-in-a-storage-unit', 'how-to-pack-a-storage-unit-efficiently', 'self-storage-vs-garage-or-spare-room']],
        'business-storage'           => [['warehouse-storage', 'office-storage', 'equipment-storage', 'pharmaceutical-storage', 'construction-storage', 'climate-controlled-storage'], ['warehouse-vs-storage-unit', 'ecommerce-storage-dubai', 'retail-stock-storage-dubai']],
        'warehouse-storage'          => [['business-storage', 'office-storage', 'equipment-storage', 'construction-storage', 'pharmaceutical-storage', 'long-term-storage'], ['warehouse-vs-storage-unit', 'ecommerce-storage-dubai', 'retail-stock-storage-dubai']],
        'climate-controlled-storage' => [['art-storage', 'pharmaceutical-storage', 'furniture-storage', 'office-storage', 'personal-storage', 'long-term-storage'], ['climate-controlled-storage-worth-it', 'storing-fragile-items', 'document-storage-dubai']],
        'moving-services'            => [['furniture-storage', 'residential-storage', 'short-term-storage', 'long-term-storage', 'box-storage', 'office-storage'], ['storage-for-expats-uae', 'downsizing-in-dubai-what-to-keep-sell-or-store', 'how-to-pack-a-storage-unit-efficiently']],
        'luggage-storage'            => [['short-term-storage', 'personal-storage', 'box-storage', 'moving-services', 'long-term-storage', 'car-storage'], ['storage-for-expats-uae', 'student-storage-dubai', 'short-term-vs-long-term-storage']],
        'car-storage'                => [['vehicle-storage', 'boat-storage', 'long-term-storage', 'short-term-storage', 'luggage-storage', 'moving-services'], ['secure-storage-dubai', 'what-can-and-cant-be-stored-in-a-storage-unit', 'short-term-vs-long-term-storage']],
        'short-term-storage'         => [['long-term-storage', 'luggage-storage', 'furniture-storage', 'moving-services', 'personal-storage', 'business-storage'], ['short-term-vs-long-term-storage', 'student-storage-dubai', 'furniture-storage-during-renovation']],
        'vehicle-storage'            => [['car-storage', 'boat-storage', 'equipment-storage', 'construction-storage', 'long-term-storage', 'short-term-storage'], ['secure-storage-dubai', 'short-term-vs-long-term-storage', 'choose-best-storage-company-dubai']],
        'long-term-storage'          => [['short-term-storage', 'climate-controlled-storage', 'furniture-storage', 'art-storage', 'personal-storage', 'business-storage'], ['short-term-vs-long-term-storage', 'storing-fragile-items', 'climate-controlled-storage-worth-it']],
        'office-storage'             => [['business-storage', 'warehouse-storage', 'equipment-storage', 'box-storage', 'moving-services', 'climate-controlled-storage'], ['document-storage-dubai', 'warehouse-vs-storage-unit', 'secure-storage-dubai']],
        'equipment-storage'          => [['construction-storage', 'business-storage', 'warehouse-storage', 'office-storage', 'vehicle-storage', 'climate-controlled-storage'], ['sports-equipment-storage', 'warehouse-vs-storage-unit', 'secure-storage-dubai']],
        'construction-storage'       => [['equipment-storage', 'warehouse-storage', 'business-storage', 'vehicle-storage', 'office-storage', 'long-term-storage'], ['warehouse-vs-storage-unit', 'what-can-and-cant-be-stored-in-a-storage-unit', 'secure-storage-dubai']],
        'pharmaceutical-storage'     => [['climate-controlled-storage', 'business-storage', 'warehouse-storage', 'office-storage', 'equipment-storage', 'long-term-storage'], ['climate-controlled-storage-worth-it', 'secure-storage-dubai', 'choose-best-storage-company-dubai']],
        'art-storage'                => [['climate-controlled-storage', 'furniture-storage', 'long-term-storage', 'personal-storage', 'moving-services', 'boat-storage'], ['storing-fragile-items', 'climate-controlled-storage-worth-it', 'secure-storage-dubai']],
        'boat-storage'               => [['vehicle-storage', 'car-storage', 'equipment-storage', 'long-term-storage', 'short-term-storage', 'art-storage'], ['secure-storage-dubai', 'what-can-and-cant-be-stored-in-a-storage-unit', 'sports-equipment-storage']],
    ];

    $page = $page ?? null;
    $showIndustries = $page === 'storage-options';
    [$relatedServices, $relatedGuides] = $map[$page] ?? [[], []];
@endphp

@if($showIndustries || $relatedServices || $relatedGuides)
<!-- ============ EXPLORE MORE ============ -->
<section class="sk-section sk-related">
    <div class="sk-container">
        <div class="sk-section-head">
            <span class="sk-eyebrow sk-eyebrow--center">Explore More</span>
            <h2>{{ $showIndustries ? 'Industries We Serve' : 'Related Storage Services and Guides' }}</h2>
        </div>

        @if($showIndustries)
            <div class="sk-related-chips">
                @foreach($industries as $slug)
                    <a href="{{ url('/' . $slug) }}" class="sk-related-chip">{{ $services[$slug] }}</a>
                @endforeach
            </div>
        @endif

        @if($relatedServices)
            <span class="h3 sk-related-title">Related Storage Services</span>
            <div class="sk-related-chips">
                @foreach($relatedServices as $slug)
                    <a href="{{ url('/' . $slug) }}" class="sk-related-chip">{{ $services[$slug] }}</a>
                @endforeach
            </div>
        @endif

        @if($relatedGuides)
            <span class="h3 sk-related-title">Related Guides</span>
            <div class="sk-related-guides">
                @foreach($relatedGuides as $slug)
                    <a href="{{ url('/blogs/' . $slug) }}" class="sk-related-guide">
                        <span>{{ $guides[$slug] }}</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
