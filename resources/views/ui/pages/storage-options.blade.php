@extends('ui.layouts.frontend')
@section('title', '| Storage-Options')
@section('metaTitle', 'Storage Options | Storage Keys')
@section('metaDescription', 'Personal, business, warehouse, climate-controlled, luggage and car storage in Sharjah and Dubai, plus professional movers and packers.')
@section('content')

@php
    $img = fn ($path) => asset('sk-assets/assets/images/frontend/' . $path);
    $options = [
        ['Personal Storage', 'personal-storage', 'landing-page/personal-storage-80kb.jpg', 'Secure units for furniture, boxes, seasonal items and household belongings, for a few weeks or several years.'],
        ['Business Storage', 'business-storage', 'landing-page/Business-storage-80kb.jpg', 'Scalable space for equipment, archived documents, excess stock and office furniture without paying for office floor area.'],
        ['Warehouse Storage', 'warehouse-storage', 'landing-page/warehouse-storage-80kb.jpg', 'Cost-effective warehousing for palletised goods, inventory overflow and commercial stock across Dubai and Sharjah.'],
        ['Climate Controlled Storage', 'climate-controlled-storage', 'landing-page/peace-of-mind-80kb.jpg', 'Protect furniture, electronics, documents and valuables from UAE heat and humidity.'],
        ['Residential Storage', 'residential-storage', 'landing-page/inline.jpg', 'Practical space for moves, renovations, relocations and homes that need extra room.'],
        ['Furniture Storage', 'furniture-storage', 'landing-page/inline-5.jpg', 'Keep sofas, beds, wardrobes and office furniture protected during moves or renovations.'],
        ['Box Storage', 'box-storage', 'Box.jpg', 'A dedicated place for packed boxes, documents and seasonal items — from a few cartons to large collections.'],
        ['Appliance Storage', 'appliance-storage', 'landing-page/inline-2.jpg', 'Store fridges, washers, ovens and commercial appliances safely while you move or renovate.'],
        ['Moving Services', 'moving-services', 'landing-page/Moving-Storage-80kb.jpg', 'Professional movers and packers who dismantle, pack, load, move and reassemble your belongings.'],
        ['Luggage Storage', 'luggage-storage', 'landing-page/inline-9.jpg', 'Keep bags secure between flights, hotel stays or moves without carrying them around.'],
        ['Car Storage', 'car-storage', 'landing-page/inline-8.jpg', 'Secure short- and long-term space for personal, classic or luxury cars.'],
        ['Vehicle Storage', 'vehicle-storage', 'landing-page/inline-6.jpg', 'Indoor or outdoor space for cars, motorcycles, vans and fleet vehicles.'],
        ['Short Term Storage', 'short-term-storage', 'landing-page/inline-7.jpg', 'Flexible space for weeks or months — between moves, renovations, travel or business peaks.'],
        ['Long Term Storage', 'long-term-storage', 'landing-page/inline-3.jpg', 'Right-sized units for months or years for furniture, documents and business stock.'],
    ];
    $storeItems = [
        ['Furniture & Home Decor', 'fa-couch'],
        ['Household Goods & Appliances', 'fa-blender'],
        ['Documents & Reports', 'fa-folder-open'],
        ['Luggage & Travel', 'fa-suitcase-rolling'],
        ['Seasonal Clothing & Decor', 'fa-tshirt'],
        ['Sports & Hobby Gear', 'fa-bicycle'],
        ['Electronics & Valuables', 'fa-desktop'],
    ];
@endphp

<div class="sk-home">

    <!-- ============ HERO (brand book storage page mockup) ============ -->
    <section class="ps-hero so-hero">
        <div class="sk-container">
            <h1>Flexible storage options for every need.</h1>
            <div class="so-hero-card">
                <p>Our individually lockable, climate-controlled self storage units near you, in Sharjah and Dubai, plus affordable business storage, cost-effective warehousing, luggage and car storage, and professional movers and packers.</p>
                <p>Whether you're moving, renovating or travelling, Storage Keys keeps your belongings safe, secure and easily accessible. Browse our storage options below or contact us for more information on our services.</p>
                <div class="btns">
                    <a href="{{ url('/booking') }}" class="sk-btn sk-btn-primary">Book now</a>
                    <a href="https://wa.me/971565018785" class="sk-btn sk-btn-primary">Chat on WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ OPTIONS ============ -->
    <section class="sk-section" id="so-options" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <h2>Which storage do you need?</h2>
                <p>Whether you're planning a move, growing a business or simply reducing clutter, there is a storage option that keeps your belongings protected while freeing up space.</p>
            </div>
            <div class="so-card-grid">
                @foreach($options as [$title, $slug, $photo, $text])
                    <a class="so-card" href="{{ url('/' . $slug) }}">
                        <div class="ph" style="background-image:url('{{ $img($photo) }}');"></div>
                        <div class="bd">
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                            <span class="sk-explore">Explore {{ strtolower($title) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============ WHAT CAN YOU STORE ============ -->
    <section class="sk-section" style="background:#fff;">
        <div class="sk-container">
            <div class="sk-section-head">
                <h2>What can you store?</h2>
                <p>From a few boxes to the contents of an entire home. Tap what you're planning to store and we'll pass the list straight to our team for a quick quote.</p>
            </div>
            <div class="so-store" id="so-store">
                @foreach($storeItems as [$label, $icon])
                    <button type="button" class="so-store-item" aria-pressed="false" data-item="{{ $label }}">
                        <span class="tile"><i class="fas {{ $icon }}" aria-hidden="true"></i></span>
                        <span>{!! str_replace(' &amp; ', '<br>&amp; ', e($label)) !!}</span>
                    </button>
                @endforeach
            </div>
            <p class="so-store-note" id="so-store-note" aria-live="polite"></p>

            <div class="sk-panel sk-panel--card">
                <div class="ph" style="background-image:url('{{ $img('landing-page/inline-4.jpg') }}');"></div>
                <div class="bd">
                    <h2>Not sure which size is right?</h2>
                    <p>Choosing the correct unit depends on the type and quantity of items you plan to store. Our team can recommend a practical, cost-effective option based on your requirements.</p>
                    <div class="btns">
                        <a href="{{ url('/booking') }}#bk-visual" class="sk-btn sk-btn-grape">Help me choose</a>
                        <a href="https://wa.me/971565018785" class="sk-btn sk-btn-ghost" id="so-wa">Chat on WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('ui.partials.explore-more', ['page' => 'storage-options'])

    <!-- ============ STATS ============ -->
    <div class="sk-statband">
        <div class="sk-container">
            <div class="sk-statband-in">
                <div><strong>10<span>+</span></strong>Years of Experience</div>
                <div><strong>5,000<span>+</span></strong>Happy Customers</div>
                <div><strong>100,000<span>+</span></strong>Sq. Ft. of Storage</div>
                <div><strong>7</strong>Emirates Covered</div>
            </div>
        </div>
    </div>

    <!-- ============ QUOTE ============ -->
    <section class="sk-section" id="so-quote">
        <div class="sk-container">
            <div class="svc-quote so-quote sk-reveal">
                <div>
                    <h2>Find the Right Storage Option</h2>
                    <p>Tell us what you need to store and how long you need it. Our team will recommend the most suitable option — with no unnecessary long-term commitment.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'variant' => 'simple',
                    'compact' => true,
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request a Free Quote',
                    'subtitle' => 'Share a few details and we will get back to you with a suitable storage option.',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'source' => 'storage-options',
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#so-quote"><i class="fas fa-box-open"></i> Quote</a>
    </div>

</div>
@endsection

@section('javascriptWork')
<script>
    (function () {
        var items = document.querySelectorAll('#so-store .so-store-item');
        var note = document.getElementById('so-store-note');
        var wa = document.getElementById('so-wa');
        var base = 'https://wa.me/971565018785';

        function update() {
            var picked = [];
            items.forEach(function (btn) {
                if (btn.getAttribute('aria-pressed') === 'true') picked.push(btn.getAttribute('data-item'));
            });
            if (!picked.length) {
                note.textContent = '';
                wa.href = base;
                return;
            }
            note.textContent = 'Selected: ' + picked.join(', ') + '. Tap "Chat on WhatsApp" to send this list to our team.';
            wa.href = base + '?text=' + encodeURIComponent('Hi Storage Keys, I would like a quote to store: ' + picked.join(', ') + '.');
        }

        items.forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.setAttribute('aria-pressed', btn.getAttribute('aria-pressed') === 'true' ? 'false' : 'true');
                update();
            });
        });
    })();
</script>
@endsection
