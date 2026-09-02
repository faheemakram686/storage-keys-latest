@extends('ui.layouts.frontend')
@section('title', '| Home')
@section('metaTitle', 'Self Storage in UAE | Secure Storage Units - Storagekeys')
@section('metaDescription', 'Secure, climate-controlled self storage across the UAE. Flexible plans, 24/7 access, no hidden costs. Get a free quote today.')

@section('css')
{{-- LCP: responsive preload so mobile gets the smaller hero first --}}
<link rel="preload" as="image" type="image/webp" href="{{ asset('sk-assets/assets/images/frontend/landing-hero-960.webp') }}" media="(max-width: 767px)" fetchpriority="high">
<link rel="preload" as="image" type="image/webp" href="{{ asset('sk-assets/assets/images/frontend/landing-hero.webp') }}" media="(min-width: 768px)" fetchpriority="high">
<link rel="preconnect" href="https://d2mpatx37cqexb.cloudfront.net" crossorigin>
<link rel="dns-prefetch" href="https://d2mpatx37cqexb.cloudfront.net">
@endsection

@section('content')

<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="sk-hero">
        <picture class="sk-hero-media">
            <source
                media="(max-width: 767px)"
                srcset="{{ asset('sk-assets/assets/images/frontend/landing-hero-960.webp') }}"
                type="image/webp"
            >
            <source
                media="(max-width: 767px)"
                srcset="{{ asset('sk-assets/assets/images/frontend/landing-hero-960.jpg') }}"
                type="image/jpeg"
            >
            <source srcset="{{ asset('sk-assets/assets/images/frontend/landing-hero.webp') }}" type="image/webp">
            <img
                src="{{ asset('sk-assets/assets/images/frontend/landing-hero.jpg') }}"
                alt=""
                width="1600"
                height="750"
                fetchpriority="high"
                decoding="sync"
            >
        </picture>
        <div class="sk-container">
            <div class="sk-hero-grid">
                <div class="sk-hero-copy">
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Self Storage · Dubai · Sharjah · UAE</span>
                    <h1>Self Storage Solutions Across <span>Dubai, Sharjah &amp; the UAE</span></h1>
                    <p class="lead">Secure storage and flexible self storage units for homes and businesses across Dubai, Abu Dhabi and Sharjah — with flexible plans and 24/7 access.</p>
                    <div class="sk-hero-badges">
                        <span><i class="fas fa-shield-alt"></i> Secure &amp; CCTV Monitored</span>
                        <span><i class="fas fa-temperature-low"></i> Climate Controlled</span>
                        <span><i class="fas fa-clock"></i> 24/7 Access</span>
                        <span><i class="fas fa-sliders-h"></i> Flexible Plans</span>
                    </div>
                    <div class="sk-hero-cta">
                        <a href="{{ url('/booking') }}" class="sk-btn sk-btn-primary"><i class="fas fa-boxes"></i> Book Your Unit</a>
                        <a href="https://wa.me/971565018785" class="sk-btn sk-btn-ghost"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
                    </div>
                    <div class="sk-hero-chips">
                        <div class="sk-chip"><i class="fas fa-map-marker-alt"></i><div><div class="v">7 Emirates</div><div class="k">Covered</div></div></div>
                        <div class="sk-chip"><i class="fas fa-clock"></i><div><div class="v">24/7</div><div class="k">Unit access</div></div></div>
                        <div class="sk-chip"><i class="fas fa-temperature-low"></i><div><div class="v">Climate</div><div class="k">Controlled</div></div></div>
                        <div class="sk-chip"><i class="fas fa-tags"></i><div><div class="v">0</div><div class="k">Hidden costs</div></div></div>
                    </div>
                </div>

                <div class="sk-hero-form">
                    @include('ui.partials.inquiry-form', [
                        'variant' => 'hero',
                        'compact' => true,
                        'formClass' => 'sk-quote',
                        'fieldClass' => 'sk-field',
                        'rowClass' => 'sk-frow',
                        'title' => 'Get a Free Quote',
                        'subtitle' => 'Tell us what you need to store and we\'ll help you find the right space.',
                        'submitLabel' => 'Get My Free Quote',
                        'source' => 'landing-hero',
                        'noteHtml' => 'Or call us now: <a href="tel:+971565018785">+971 56 501 8785</a> · Toll Free: <a href="tel:8005397">800 5397</a>',
                        'storageOptions' => [
                            'Personal Storage' => 'Personal Storage',
                            'Business Storage' => 'Business Storage',
                            'Warehouse Storage' => 'Warehouse Storage',
                            'Climate Controlled Storage' => 'Climate Controlled Storage',
                            'Moving' => 'Moving / Temporary Storage',
                        ],
                    ])
                </div>
            </div>
        </div>
    </section>

    <!-- ============ USP STRIP ============ -->
    <div class="sk-usp">
        <div class="sk-container">
            <div class="sk-usp-inner">
                <div class="sk-usp-item">
                    <i class="fas fa-shield-alt"></i>
                    <span class="h4">Secure Storage</span>
                    <p>CCTV surveillance &amp; access control</p>
                </div>
                <div class="sk-usp-item">
                    <i class="fas fa-temperature-low"></i>
                    <span class="h4">Climate Controlled</span>
                    <p>Protects sensitive belongings</p>
                </div>
                <div class="sk-usp-item">
                    <i class="fas fa-clock"></i>
                    <span class="h4">24/7 Access</span>
                    <p>Reach your unit any time</p>
                </div>
                <div class="sk-usp-item">
                    <i class="fas fa-sliders-h"></i>
                    <span class="h4">Flexible Plans</span>
                    <p>Short-term &amp; long-term terms</p>
                </div>
                <div class="sk-usp-item">
                    <i class="fas fa-tags"></i>
                    <span class="h4">Transparent Pricing</span>
                    <p>No hidden costs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ INTRO / ABOUT ============ -->
    <section class="sk-section sk-about">
        <div class="sk-container">
            <div class="sk-about-grid">
                <div class="sk-about-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/corridor%20area-80kb.jpg') }}'); background-size:cover; background-position:center;">
                    <div class="sk-about-badge">
                        <div class="n">Dubai · Abu Dhabi · Sharjah</div>
                        <div class="l">Storage facilities across the UAE</div>
                    </div>
                </div>
                <div>
                    <span class="sk-eyebrow">About Storage Keys</span>
                    <h2>A Storage Company Built for the UAE Market</h2>
                    <p>Storage Keys provides secure self storage solutions across Dubai, Abu Dhabi and Sharjah for individuals and businesses looking for flexible space across the UAE. Whether you need a compact unit for personal belongings or a large warehouse for commercial inventory, we offer practical options designed around your requirements.</p>
                    <p>As one of the leading storage companies in Dubai, we understand that every customer has different needs. Some are households looking for a little extra room, while others are businesses managing stock across multiple locations. That is why we offer a wide range of personal, commercial and warehouse storage solutions, all under one roof — from local self storage for households to large-scale business inventory management, with a plan that fits your requirements and budget.</p>
                    <p>The UAE has grown into one of the busiest markets in the region for renting extra space, driven by a fast-growing population, a busy retail and e-commerce sector, and residents who move between cities for work. Our facilities are designed to meet that demand with secure access, temperature-controlled environments and flexible rental terms — whether you need storage for valuable belongings, a temporary space during a move, or a dedicated unit for years of business inventory.</p>
                    <a href="/about-us" class="sk-btn sk-btn-primary" style="margin-top:8px;"><i class="fas fa-boxes"></i> Reserve Your Space</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STATS COUNTER ============ -->
    <section class="sk-section sk-stats sk-reveal" style="padding:64px 0;">
        <div class="sk-container">
            <div class="sk-stats-grid">
                <div class="sk-stat">
                    <div class="n"><span class="sk-count" data-target="10">0</span><span class="plus">+</span></div>
                    <div class="l">Years of Experience</div>
                </div>
                <div class="sk-stat">
                    <div class="n"><span class="sk-count" data-target="5000">0</span><span class="plus">+</span></div>
                    <div class="l">Happy Customers</div>
                </div>
                <div class="sk-stat">
                    <div class="n"><span class="sk-count" data-target="100000">0</span><span class="plus">+</span></div>
                    <div class="l">Sq Ft of Storage</div>
                </div>
                <div class="sk-stat">
                    <div class="n"><span class="sk-count" data-target="7">0</span></div>
                    <div class="l">Emirates Covered</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ UNIT TYPES & SIZES ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Sizes</span>
                <h2>Storage Unit Types &amp; Sizes</h2>
                <p>Choosing the right storage unit is easier when you know roughly how much room you need. We offer a range of sizes to suit different situations — from a few boxes to a full household or business inventory.</p>
            </div>
            <div class="sk-sizes">
                <div class="sk-size">
                    <div class="ic"><i class="fas fa-box"></i></div>
                    <div class="tag">Small Units</div>
                    <span class="h3">A Few Boxes &amp; Extras</span>
                    <p>Ideal for boxes, documents, luggage, household items and seasonal belongings.</p>
                </div>
                <div class="sk-size">
                    <div class="ic"><i class="fas fa-couch"></i></div>
                    <div class="tag">Medium Units</div>
                    <span class="h3">1–2 Bedroom Apartment</span>
                    <p>Suited to the contents of a one or two-bedroom apartment, including beds, sofas and appliances.</p>
                </div>
                <div class="sk-size">
                    <div class="ic"><i class="fas fa-warehouse"></i></div>
                    <div class="tag">Large &amp; Warehouse</div>
                    <span class="h3">Full Home or Business</span>
                    <p>Designed for full households, bulk inventory, office equipment and commercial goods.</p>
                </div>
            </div>
            <div class="sk-size-note">
                <i class="fas fa-info-circle"></i> Not sure which size fits your needs? Our team can help you estimate the right option based on what you plan to store — so you only pay for the space you actually use.
            </div>
        </div>
    </section>

    <!-- ============ SIZE FINDER ============ -->
    <section class="sk-section" id="size-finder">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Size Finder</span>
                <h2>Not Sure What You Need? Find Your Unit Size</h2>
                <p>Answer three quick questions and we'll point you to the unit that fits what you're storing.</p>
            </div>
            <div class="sk-finder-intro">
                <span><i class="fas fa-check-circle"></i> Three questions</span>
                <span><i class="fas fa-check-circle"></i> No sign-up needed</span>
                <span><i class="fas fa-check-circle"></i> Answer in under a minute</span>
            </div>
            <div class="sk-finder sk-reveal" id="skFinder">
                <div class="sk-finder-top">
                    <div class="step">Step <b class="sf-stepnum">1</b> of 3</div>
                    <div class="sk-finder-bar"><i class="sf-progress" style="width:33%"></i></div>
                </div>

                <div class="sf-panel" data-step="1">
                    <span class="h3">What are you storing?</span>
                    <div class="sf-opts">
                        <button type="button" class="sf-opt" data-key="store" data-val="household"><span class="t">Household belongings</span><span class="d">Furniture, appliances, seasonal items</span></button>
                        <button type="button" class="sf-opt" data-key="store" data-val="documents"><span class="t">Documents &amp; boxes</span><span class="d">Paperwork, archives, luggage</span></button>
                        <button type="button" class="sf-opt" data-key="store" data-val="business"><span class="t">Business inventory</span><span class="d">Retail stock, equipment, files</span></button>
                        <button type="button" class="sf-opt" data-key="store" data-val="bulk"><span class="t">Bulk stock or equipment</span><span class="d">Pallets, commercial goods</span></button>
                    </div>
                </div>

                <div class="sf-panel" data-step="2" hidden="">
                    <span class="h3">Roughly how much space do you need?</span>
                    <div class="sf-opts">
                        <button type="button" class="sf-opt" data-key="size" data-val="boxes"><span class="t">A few boxes</span><span class="d">Fits in a car boot</span></button>
                        <button type="button" class="sf-opt" data-key="size" data-val="room"><span class="t">A room's worth</span><span class="d">Sofa, bed, boxes</span></button>
                        <button type="button" class="sf-opt" data-key="size" data-val="home"><span class="t">A 1–2 bedroom home</span><span class="d">Beds, sofas and appliances</span></button>
                        <button type="button" class="sf-opt" data-key="size" data-val="full"><span class="t">A full home or bulk stock</span><span class="d">Whole household or pallets</span></button>
                    </div>
                </div>

                <div class="sf-panel" data-step="3" hidden="">
                    <span class="h3">Which emirate?</span>
                    <div class="sf-opts">
                        <button type="button" class="sf-opt" data-key="loc" data-val="Dubai"><span class="t">Dubai</span><span class="d">Self storage &amp; business inventory</span></button>
                        <button type="button" class="sf-opt" data-key="loc" data-val="Abu Dhabi"><span class="t">Abu Dhabi</span><span class="d">Personal &amp; commercial storage</span></button>
                        <button type="button" class="sf-opt" data-key="loc" data-val="Sharjah"><span class="t">Sharjah</span><span class="d">Main facility — Plot # 4202</span></button>
                    </div>
                </div>

                <div class="sf-result" hidden="">
                    <div class="ic"><i class="fas fa-box-open"></i></div>
                    <div class="lead">Our recommendation</div>
                    <span class="h3 sf-rec-title">Medium Unit</span>
                    <p class="sf-rec-desc">Based on your answers, this size should comfortably fit what you're storing.</p>
                    <div class="btns">
                        <a href="{{ url('/booking') }}" class="sk-btn sk-btn-primary sf-rec-book"><i class="fas fa-boxes"></i> Check Availability</a>
                        <a href="https://wa.me/971565018785" class="sk-btn sk-btn-wa sf-rec-wa"><i class="fab fa-whatsapp"></i> Send to Our Team</a>
                    </div>
                </div>

                <div class="sk-finder-nav">
                    <button type="button" class="sf-back" hidden=""><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="sf-restart" hidden="">Start over</button>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ PRICING ============ -->
    <!-- <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Pricing</span>
                <h2>Simple, Transparent Pricing</h2>
                <p>Flexible monthly plans with no hidden costs. Starting prices are a guide — confirm the exact rate for your unit with our team.</p>
            </div>
            <div class="sk-price-grid">
                <div class="sk-price">
                    <span class="h3">Small Unit</span>
                    <div class="size">Boxes, documents &amp; seasonal items</div>
                    <div class="amount"><small>from AED</small> XXX<small>/mo</small></div>
                    <div class="per">Ideal for a few boxes</div>
                    <ul>
                        <li><i class="fas fa-check"></i> Secure, CCTV-monitored unit</li>
                        <li><i class="fas fa-check"></i> Climate controlled</li>
                        <li><i class="fas fa-check"></i> 24/7 access</li>
                        <li><i class="fas fa-check"></i> Flexible monthly term</li>
                    </ul>
                    <a href="{{ url('/contact-us') }}#ct-quote" class="sk-btn sk-btn-outline">Get a Quote</a>
                </div>
                <div class="sk-price featured">
                    <span class="tag">Most Popular</span>
                    <span class="h3">Medium Unit</span>
                    <div class="size">Contents of a 1–2 bedroom home</div>
                    <div class="amount"><small>from AED</small> XXX<small>/mo</small></div>
                    <div class="per">Beds, sofas &amp; appliances</div>
                    <ul>
                        <li><i class="fas fa-check"></i> Everything in Small</li>
                        <li><i class="fas fa-check"></i> More room for furniture</li>
                        <li><i class="fas fa-check"></i> Short &amp; long-term plans</li>
                        <li><i class="fas fa-check"></i> Friendly customer support</li>
                    </ul>
                    <a href="{{ url('/booking') }}" class="sk-btn sk-btn-primary">Book This Unit</a>
                </div>
                <div class="sk-price">
                    <span class="h3">Large / Warehouse</span>
                    <div class="size">Full home or business inventory</div>
                    <div class="amount"><small>from AED</small> XXX<small>/mo</small></div>
                    <div class="per">Bulk stock &amp; equipment</div>
                    <ul>
                        <li><i class="fas fa-check"></i> Everything in Medium</li>
                        <li><i class="fas fa-check"></i> Warehouse-scale space</li>
                        <li><i class="fas fa-check"></i> Scalable rental terms</li>
                        <li><i class="fas fa-check"></i> Business inventory support</li>
                    </ul>
                    <a href="{{ url('/contact-us') }}#ct-quote" class="sk-btn sk-btn-outline">Get a Quote</a>
                </div>
            </div>
            <div class="sk-size-note">
                <i class="fas fa-info-circle"></i> Prices vary by unit size, rental duration and location. Contact our team for an exact, no-obligation quote tailored to what you're storing.
            </div>
        </div>
    </section> -->

    <!-- ============ PROMO BANNER ============ -->
    <section style="padding:0 0 84px;">
        <div class="sk-container">
            <div class="sk-promo sk-reveal">
                <div class="pc">
                    <span class="tagn">Limited Time Offer</span>
                    <h2>Special Introductory Rates on New Bookings</h2>
                    <p>Reserve your unit this month and lock in our best price. Speak to our team for current offers on personal, business and warehouse storage.</p>
                </div>
                <div class="pc" style="display:flex; gap:14px; flex-wrap:wrap;">
                    <a href="/booking/" class="sk-btn sk-btn-primary"><i class="fas fa-tag"></i> Claim This Offer</a>
                    <a href="tel:+971565018785" class="sk-btn sk-btn-ghost"><i class="fas fa-phone"></i> Call Us</a>
                    <a href="tel:8005397" class="sk-btn sk-btn-ghost"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ OUR STORAGE SOLUTIONS ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Our Solutions</span>
                <h2>Storage Solutions for Every Need</h2>
                <p>We organise our services around how people and businesses actually use extra space, making it easy to find the option that matches your situation.</p>
            </div>
            <div class="sk-cards">
                <div class="sk-card">
                    <div class="sk-card-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/personal-storage-80kb.jpg') }}');"></div>
                    <div class="sk-card-body">
                        <div class="ic"><i class="fas fa-home"></i></div>
                        <span class="h3">Personal Storage</span>
                        <p>Ideal for furniture, documents, luggage and seasonal items. Perfect for short-term or long-term household needs at affordable rates.</p>
                        <a href="{{ url('/personal-storage') }}">Explore personal storage <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="sk-card">
                    <div class="sk-card-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/Business-storage-80kb.jpg') }}');"></div>
                    <div class="sk-card-body">
                        <div class="ic"><i class="fas fa-briefcase"></i></div>
                        <span class="h3">Business Storage</span>
                        <p>Designed for retailers, distributors and e-commerce businesses that need organised inventory solutions and regular access to their goods.</p>
                        <a href="{{ url('/business-storage') }}">Explore business storage <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="sk-card">
                    <div class="sk-card-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/warehouse-storage-80kb.jpg') }}');"></div>
                    <div class="sk-card-body">
                        <div class="ic"><i class="fas fa-warehouse"></i></div>
                        <span class="h3">Warehouse Storage</span>
                        <p>Flexible warehouse space for bulk inventory, equipment and commercial stock with scalable rental terms and easy access.</p>
                        <a href="{{ url('/warehouse-storage') }}">Explore warehouse storage <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="sk-card">
                    <div class="sk-card-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/Moving-Storage-80kb.jpg') }}');"></div>
                    <div class="sk-card-body">
                        <div class="ic"><i class="fas fa-truck"></i></div>
                        <span class="h3">Moving Storage</span>
                        <p>A convenient moving and temporary storage solution that helps simplify relocations for both households and businesses.</p>
                        <a href="{{ url('/moving-services') }}">Explore moving &amp; storage<i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ SECURITY HIGHLIGHT ============ -->
    <section class="sk-section" style="background:#fff;">
        <div class="sk-container">
            <div class="sk-secure-grid sk-reveal">
                <div class="sk-secure-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/peace-of-mind-80kb.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Peace of Mind</span>
                    <h2>Your Belongings, Fully Protected</h2>
                    <p>Security is at the heart of everything we do. Every unit is monitored and maintained to keep your items safe, clean and in the same condition you left them.</p>
                    <div class="sk-secure-list">
                        <div class="row-i">
                            <div class="ic"><i class="fas fa-video"></i></div>
                            <div><span class="h4">24/7 CCTV Surveillance</span><p>Facilities are monitored around the clock by security cameras.</p></div>
                        </div>
                        <div class="row-i">
                            <div class="ic"><i class="fas fa-fingerprint"></i></div>
                            <div><span class="h4">Controlled Access</span><p>Only authorised, registered customers can access the storage areas.</p></div>
                        </div>
                        <div class="row-i">
                            <div class="ic"><i class="fas fa-temperature-low"></i></div>
                            <div><span class="h4">Climate-Controlled Units</span><p>Temperature-controlled spaces protect items from heat and humidity.</p></div>
                        </div>
                        <div class="row-i">
                            <div class="ic"><i class="fas fa-fire-extinguisher"></i></div>
                            <div><span class="h4">Fire &amp; Safety Measures</span><p>Clean, well-maintained facilities built with safety in mind.</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHO CAN BENEFIT ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Who It's For</span>
                <h2>Who Can Benefit From Our Self Storage?</h2>
                <p>Self storage is no longer used only during house moves. Today, it serves a wide range of personal and commercial needs across the UAE.</p>
            </div>
            <div class="sk-benefits">
                <div class="sk-benefit"><i class="fas fa-home"></i><span>Homeowners looking to declutter their living spaces.</span></div>
                <div class="sk-benefit"><i class="fas fa-building"></i><span>Apartment residents needing additional room for belongings.</span></div>
                <div class="sk-benefit"><i class="fas fa-people-carry"></i><span>Families renovating or relocating their homes.</span></div>
                <div class="sk-benefit"><i class="fas fa-user-graduate"></i><span>Students requiring temporary space during semester breaks.</span></div>
                <div class="sk-benefit"><i class="fas fa-boxes"></i><span>Small and medium-sized businesses managing inventory.</span></div>
                <div class="sk-benefit"><i class="fas fa-shopping-cart"></i><span>Retailers and e-commerce sellers handling seasonal stock.</span></div>
                <div class="sk-benefit"><i class="fas fa-file-alt"></i><span>Corporate offices storing documents, furniture and equipment.</span></div>
                <div class="sk-benefit"><i class="fas fa-clock"></i><span>Anyone needing short-term or long-term flexible storage.</span></div>
            </div>
        </div>
    </section>

    <!-- ============ LOCATIONS ============ -->
    <section class="sk-section sk-locs" id="locations">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Our Facilities</span>
                <h2>Visit Us in Dubai &amp; Sharjah</h2>
                <p>Two secure StorageKeys facilities — find us on the map, get directions, or call before you arrive.</p>
            </div>

            <div class="sk-locs-grid sk-reveal">
                <article class="sk-loc-tile">
                    <div class="sk-loc-map">
                        <iframe
                            title="Storage Keys Dubai on Google Maps"
                            src="https://maps.google.com/maps?q=Storage%20Keys%2C%2010A%20Street%20-%20Al%20Qouz%20Ind.third%20-%20Al%20Quoz%20-%20Dubai&z=15&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                        ></iframe>
                    </div>
                    <div class="sk-loc-body">
                        <div class="sk-loc-top">
                            <span class="sk-loc-badge"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dubai</span>
                            <span class="sk-loc-pin">Al Quoz</span>
                        </div>
                        <span class="h3">Storage Keys — Dubai</span>
                        <p class="sk-loc-rating"><i class="fas fa-star" aria-hidden="true"></i> 4.9 / 5 — from real reviews shared publicly on <i class="fab fa-google" aria-hidden="true"></i> Google</p>
                        <p class="sk-loc-addr">10A Street, Al Quoz Industrial Area 3<br>Al Quoz, Dubai, United Arab Emirates</p>
                        <div class="sk-locfeat">
                            <span><i class="fas fa-clock" aria-hidden="true"></i> 24/7 access</span>
                            <span><i class="fas fa-video" aria-hidden="true"></i> CCTV</span>
                            <span><i class="fas fa-temperature-low" aria-hidden="true"></i> Climate controlled</span>
                        </div>
                        <div class="sk-loc-actions">
                            <a href="https://maps.app.goo.gl/cZhb4mLPGVsGcooA9" target="_blank" rel="noopener" class="sk-btn sk-btn-primary"><i class="fas fa-directions" aria-hidden="true"></i> Get Directions</a>
                            <a href="tel:+971565018785" class="sk-btn sk-btn-outline"><i class="fas fa-phone" aria-hidden="true"></i> Call</a>
                        </div>
                    </div>
                </article>

                <article class="sk-loc-tile">
                    <div class="sk-loc-map">
                        <iframe
                            title="Storage Keys Sharjah on Google Maps"
                            src="https://maps.google.com/maps?q=Storage%20Keys%2C%20Plot%20%23%204202%20-%20Sharjah%20-%20United%20Arab%20Emirates&z=15&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                        ></iframe>
                    </div>
                    <div class="sk-loc-body">
                        <div class="sk-loc-top">
                            <span class="sk-loc-badge"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Sharjah</span>
                            <span class="sk-loc-pin">Main facility</span>
                        </div>
                        <span class="h3">Storage Keys — Sharjah</span>
                        <p class="sk-loc-rating"><i class="fas fa-star" aria-hidden="true"></i> 4.9 / 5 — from real reviews shared publicly on <i class="fab fa-google" aria-hidden="true"></i> Google</p>
                        <p class="sk-loc-addr">Plot # 4202<br>Sharjah, United Arab Emirates</p>
                        <div class="sk-locfeat">
                            <span><i class="fas fa-clock" aria-hidden="true"></i> 24/7 access</span>
                            <span><i class="fas fa-video" aria-hidden="true"></i> CCTV</span>
                            <span><i class="fas fa-warehouse" aria-hidden="true"></i> Office &amp; storage</span>
                        </div>
                        <div class="sk-loc-actions">
                            <a href="https://maps.app.goo.gl/vvWVRRKCtBnups2k6" target="_blank" rel="noopener" class="sk-btn sk-btn-primary"><i class="fas fa-directions" aria-hidden="true"></i> Get Directions</a>
                            <a href="tel:+971565018785" class="sk-btn sk-btn-outline"><i class="fas fa-phone" aria-hidden="true"></i> Call</a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ============ FACILITIES GALLERY ============ -->
    <section class="sk-section sk-cover">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center; color:#ffcf9e;">Inside Our Facilities</span>
                <h2>A Closer Look</h2>
                <p>Clean, secure and well-organised spaces, built to keep your belongings in great condition.</p>
            </div>
            @php
                $facilityGallery = [
                    ['file' => 'inline.jpg', 'alt' => 'StorageKeys facility corridor'],
                    ['file' => 'inline-2.jpg', 'alt' => 'StorageKeys storage units'],
                    ['file' => 'inline-3.jpg', 'alt' => 'StorageKeys facility interior'],
                    ['file' => 'inline-4.jpg', 'alt' => 'StorageKeys warehouse aisle'],
                    ['file' => 'inline-5.jpg', 'alt' => 'StorageKeys climate-controlled units'],
                    ['file' => 'inline-6.jpg', 'alt' => 'StorageKeys secure storage hallway'],
                    ['file' => 'inline-7.jpg', 'alt' => 'StorageKeys facility walkway'],
                    ['file' => 'inline-8.jpg', 'alt' => 'StorageKeys storage unit doors'],
                    ['file' => 'inline-9.jpg', 'alt' => 'StorageKeys organised storage space'],
                ];
                $galleryBase = 'sk-assets/assets/images/frontend/landing-page/';
            @endphp
            <div class="sk-gallery-carousel sk-reveal" id="skGalleryCarousel" aria-roledescription="carousel" aria-label="Facility photos">
                <button type="button" class="sk-gal-btn sk-gal-prev" aria-label="Previous photos">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>
                <div class="sk-gal-viewport">
                    <div class="sk-gal-track">
                        @foreach ($facilityGallery as $i => $shot)
                            <div class="sk-gal-slide" role="group" aria-roledescription="slide" aria-label="Photo {{ $i + 1 }} of {{ count($facilityGallery) }}">
                                <div class="g" style="background-image:url('{{ asset($galleryBase . $shot['file']) }}');" role="img" aria-label="{{ $shot['alt'] }}"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="sk-gal-btn sk-gal-next" aria-label="Next photos">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
                <div class="sk-gal-dots" role="group" aria-label="Gallery slides"></div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE US ============ -->
    <section class="sk-section sk-why">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose Storage Keys</h2>
                <p>Finding the best storage company means choosing a partner that values security, convenience and customer care equally.</p>
            </div>
            <div class="sk-values">
                <div class="sk-value">
                    <div class="ic"><i class="fas fa-shield-alt"></i></div>
                    <span class="h4">Secure &amp; Climate-Controlled</span>
                    <p>Every unit is monitored with CCTV surveillance and kept in a climate-controlled environment for complete peace of mind.</p>
                </div>
                <div class="sk-value">
                    <div class="ic"><i class="fas fa-clock"></i></div>
                    <span class="h4">24/7 Access</span>
                    <p>Registered customers can reach their belongings around the clock, whenever it suits them.</p>
                </div>
                <div class="sk-value">
                    <div class="ic"><i class="fas fa-headset"></i></div>
                    <span class="h4">Friendly Support</span>
                    <p>A professional, responsive team is always on hand to help you choose and manage your space.</p>
                </div>
                <div class="sk-value">
                    <div class="ic"><i class="fas fa-sliders-h"></i></div>
                    <span class="h4">Flexible Plans</span>
                    <p>Short-term and long-term rental options that adapt to personal and business requirements.</p>
                </div>
                <div class="sk-value">
                    <div class="ic"><i class="fas fa-tags"></i></div>
                    <span class="h4">Transparent Pricing</span>
                    <p>Clear, honest pricing with no hidden costs — you always know what you're paying for.</p>
                </div>
                <div class="sk-value">
                    <div class="ic"><i class="fas fa-award"></i></div>
                    <span class="h4">Trusted Across the UAE</span>
                    <p>Relied on by households and businesses throughout Dubai, Abu Dhabi and Sharjah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ HOW BOOKING WORKS ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Simple Process</span>
                <h2>How Booking Works</h2>
                <p>Renting a storage unit with Storage Keys is designed to be simple — whether you need personal storage, business inventory support or warehouse facilities.</p>
            </div>
            <div class="sk-steps">
                <div class="sk-step">
                    <div class="num">1</div>
                    <span class="h3">Choose Your Unit</span>
                    <p>Select your preferred unit size and service type.</p>
                </div>
                <div class="sk-step">
                    <div class="num">2</div>
                    <span class="h3">Confirm Details</span>
                    <p>Confirm availability and pricing with our team.</p>
                </div>
                <div class="sk-step">
                    <div class="num">3</div>
                    <span class="h3">Complete Rental</span>
                    <p>Sign your rental agreement and provide the required identification.</p>
                </div>
                <div class="sk-step">
                    <div class="num">4</div>
                    <span class="h3">Move In</span>
                    <p>Move your belongings in and access your unit whenever you need it.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ TESTIMONIALS + LOGOS ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Customer Reviews</span>
                <h2>What Our Customers Say</h2>
            </div>
            <div class="sk-rating sk-reveal">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <div class="score"><b>4.9 / 5</b> — from real reviews shared publicly on <i class="fab fa-google"></i> Google</div>
            </div>
            @php
                // Reviews from Google Maps — Storage Keys Sharjah
                // https://www.google.com/maps/place/Storage+Keys+Sharjah/...
                $googleReviews = [
                    [
                        'name' => 'Danish Siddiqui',
                        'initials' => 'D',
                        'avatar_color' => '#4285f4',
                        'meta' => 'Local Guide · 33 reviews · 5 photos',
                        'when' => '5 years ago',
                        'stars' => 5,
                        'text' => 'This Company is by far the best Movers Company I have come across both with respect to the quality of the service and amazing PRICE. My experience started from the first time I called them and their Manager Mr. Saad gave a very good idea of how my stuff would be handled and the cost associated with that. Rest of the team was equally professional and made my relocation smooth.',
                        'owner' => 'Mr. Danish thanks for your positive feedback.',
                        'owner_when' => '5 years ago',
                    ],
                    [
                        'name' => 'Mohammad Ghassan',
                        'initials' => 'M',
                        'avatar_color' => '#ea4335',
                        'meta' => 'Local Guide · 79 reviews · 3 photos',
                        'when' => '3 years ago',
                        'stars' => 5,
                        'text' => 'The most well rounded facility after searching 20+ locations. The storage facility is clean, well rounded with flexibility. I can access it at any time and the staff were helpful throughout.',
                        'owner' => null,
                        'owner_when' => null,
                    ],
                    [
                        'name' => 'Nadeem Baig',
                        'initials' => 'N',
                        'avatar_color' => '#fbbc04',
                        'meta' => 'Local Guide',
                        'when' => 'Google review',
                        'stars' => 5,
                        'text' => 'Best place to store your commercial and household items. Climate controlled environment, CCTV camera and above of all you can access your storage space 24/7 with nice and welcoming staff.',
                        'owner' => null,
                        'owner_when' => null,
                    ],
                    [
                        'name' => 'Ashik S',
                        'initials' => 'A',
                        'avatar_color' => '#34a853',
                        'meta' => 'Local Guide',
                        'when' => 'Google review',
                        'stars' => 5,
                        'text' => 'I have been storing my business goods with them for over 6 months now. The staff at the location as well as the admin staffs are very friendly. The facility is well maintained and the location is great.',
                        'owner' => null,
                        'owner_when' => null,
                    ],
                    [
                        'name' => 'Muhammed Alshebli',
                        'initials' => 'M',
                        'avatar_color' => '#ea4335',
                        'meta' => 'Local Guide',
                        'when' => 'Google review',
                        'stars' => 5,
                        'text' => 'The arrangement of the storage rooms is wonderful and clean, and the place is equipped with public safety means, in addition to observing the implementation of sterilization and spraying of pesticides as a proactive protection against insects.',
                        'owner' => null,
                        'owner_when' => null,
                    ],
                    [
                        'name' => 'pooja nayal',
                        'initials' => 'P',
                        'avatar_color' => '#4285f4',
                        'meta' => 'Local Guide',
                        'when' => 'Google review',
                        'stars' => 5,
                        'text' => 'We took the warehouse for a long time and their services are very good, fast and very friendly yet professional staff. Thank you',
                        'owner' => null,
                        'owner_when' => null,
                    ],
                ];
                $googleReviewsUrl = 'https://www.google.com/maps/place/Storage+Keys+Sharjah/@25.353524,55.6316793,15z/data=!4m8!3m7!1s0x3ef5f31916a85fc9:0x2db4222312dfc94f!8m2!3d25.353524!4d55.6316793!9m1!1b1!16s%2Fg%2F11fkr38ldv?hl=en-US';
            @endphp
            <div class="sk-reviews-carousel sk-reveal" id="skReviewsCarousel" aria-roledescription="carousel" aria-label="Google customer reviews">
                <button type="button" class="sk-rev-btn sk-rev-prev" aria-label="Previous reviews">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>
                <div class="sk-rev-viewport">
                    <div class="sk-rev-track">
                        @foreach ($googleReviews as $i => $review)
                            <div class="sk-rev-slide" role="group" aria-roledescription="slide" aria-label="Review {{ $i + 1 }} of {{ count($googleReviews) }}">
                                <article class="sk-grev">
                                    <div class="sk-grev-head">
                                        <div class="sk-grev-av" style="background:{{ $review['avatar_color'] }};">{{ $review['initials'] }}</div>
                                        <div class="sk-grev-who">
                                            <div class="sk-grev-name">{{ $review['name'] }}</div>
                                            <div class="sk-grev-meta">{{ $review['meta'] }}</div>
                                        </div>
                                        <i class="fab fa-google sk-grev-g" aria-hidden="true"></i>
                                    </div>
                                    <div class="sk-grev-rating">
                                        <span class="sk-grev-stars" role="img" aria-label="{{ $review['stars'] }} out of 5 stars">
                                            @for ($s = 0; $s < $review['stars']; $s++)
                                                <i class="fas fa-star" aria-hidden="true"></i>
                                            @endfor
                                        </span>
                                        <span class="sk-grev-when">{{ $review['when'] }}</span>
                                    </div>
                                    <p class="sk-grev-text" data-full="{{ e($review['text']) }}">{{ $review['text'] }}</p>
                                    <button type="button" class="sk-grev-more" hidden>More</button>
                                    @if (!empty($review['owner']))
                                        <div class="sk-grev-owner">
                                            <div class="sk-grev-owner-title">Response from the owner <span>{{ $review['owner_when'] }}</span></div>
                                            <p>{{ $review['owner'] }}</p>
                                        </div>
                                    @endif
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="sk-rev-btn sk-rev-next" aria-label="Next reviews">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
                <div class="sk-rev-dots" role="group" aria-label="Review slides"></div>
            </div>
            <div class="sk-reviews-more">
                <a href="{{ $googleReviewsUrl }}" target="_blank" rel="noopener noreferrer" class="sk-btn sk-btn-outline">
                    <i class="fab fa-google" aria-hidden="true"></i> See all reviews on Google
                </a>
            </div>

            <div class="sk-logos sk-reveal">
                <div class="cap">Trusted by businesses across the UAE</div>
                <div class="sk-logos-grid">
                    <div class="sk-logo-item">
                        <img src="{{ asset('sk-assets/assets/images/frontend/logo/logo-1.png') }}" alt="Day Exchange" width="280" height="96" loading="lazy" decoding="async">
                    </div>
                    <div class="sk-logo-item">
                        <img src="{{ asset('sk-assets/assets/images/frontend/logo/logo-2.png') }}" alt="RAKBANK" width="280" height="96" loading="lazy" decoding="async">
                    </div>
                    <div class="sk-logo-item">
                        <img src="{{ asset('sk-assets/assets/images/frontend/logo/logo-3.png') }}" alt="NAS Neuron Health Services" width="280" height="96" loading="lazy" decoding="async">
                    </div>
                    <div class="sk-logo-item sk-logo-item--stack">
                        <img src="{{ asset('sk-assets/assets/images/frontend/logo/logo-4.png') }}" alt="Imperial Gas" width="200" height="96" loading="lazy" decoding="async">
                    </div>
                    <div class="sk-logo-item">
                        <img src="{{ asset('sk-assets/assets/images/frontend/logo/logo-5.png') }}" alt="Dar Exchange" width="280" height="96" loading="lazy" decoding="async">
                    </div>
                    <div class="sk-logo-item sk-logo-item--stack">
                        <img src="{{ asset('sk-assets/assets/images/frontend/logo/logo-6.png') }}" alt="Khan El Kaser" width="200" height="96" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ PACKING SUPPLIES / SHOP ============ -->
    <section class="sk-section" style="background:#fff;" id="sh-products">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Packing Supplies</span>
                <h2>Storage &amp; Packing Products</h2>
                <p>Choose the supplies you need, add them to your cart, and we will help you get ready for storage or moving.</p>
            </div>
            @if(isset($shopProducts) && $shopProducts->count())
            <div class="sh-grid sh-grid-2 sk-reveal">
                @foreach($shopProducts as $product)
                    @php
                        $img = $product->image
                            ? asset('storage/uploads/product-images/'.$product->image)
                            : asset('sk-assets/assets/images/frontend/product/1.png');
                        $sale = $product->sell_price - (($product->sell_price * $product->disc_amount) / 100);
                        $hasDisc = (float) $product->disc_amount > 0;
                    @endphp
                    <article class="sh-card">
                        <div class="sh-card-img" style="background-image:url('{{ $img }}');">
                            @if($hasDisc)
                                <span class="sh-badge">Sale</span>
                            @endif
                        </div>
                        <div class="sh-card-body">
                            <h3>{{ $product->p_name }}</h3>
                            <div class="sh-price">
                                <span>AED {{ number_format($sale, 2) }}</span>
                                @if($hasDisc)
                                    <del>AED {{ number_format($product->sell_price, 2) }}</del>
                                @endif
                            </div>
                            <form action="{{ route('cart.store') }}" method="POST" id="add_cart_{{ $product->id }}">
                                @csrf
                                <input type="hidden" value="{{ $product->id }}" name="id">
                                <input type="hidden" value="{{ $product->p_name }}" name="name">
                                <input type="hidden" value="{{ $sale }}" name="price">
                                <input type="hidden" value="{{ $product->image }}" name="image">
                                <input type="hidden" value="1" name="quantity">
                            </form>
                            <button type="submit" form="add_cart_{{ $product->id }}" class="sk-btn sk-btn-primary sh-add">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
            @else
            <div class="sk-supplies sk-supplies-2 sk-reveal">
                <div class="sk-supply">
                    <div class="img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing/inline-17.jpg') }}');"></div>
                    <div class="b"><span class="h3">Box</span><p>Sturdy storage boxes for packing belongings before they go into your unit.</p><a href="{{ url('/shop') }}">Shop now <i class="fas fa-arrow-right"></i></a></div>
                </div>
                <div class="sk-supply">
                    <div class="img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing/inline-19.jpg') }}');"></div>
                    <div class="b"><span class="h3">Tape</span><p>Heavy-duty packing tape to keep your boxes sealed and secure.</p><a href="{{ url('/shop') }}">Shop now <i class="fas fa-arrow-right"></i></a></div>
                </div>
            </div>
            @endif
            <div style="text-align:center; margin-top:34px;">
                <a href="{{ url('/shop') }}" class="sk-btn sk-btn-outline">Browse All Supplies <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- ============ STORAGE TIPS / BLOG ============ -->
    <!-- <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Storage Tips &amp; Guides</span>
                <h2>Advice to Help You Store Smarter</h2>
                <p>Practical tips on packing, choosing the right unit and getting the most from your storage space.</p>
            </div>
            <div class="sk-blog sk-reveal">
                <div class="sk-blogcard">
                    <div class="img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing/inline-21.jpg') }}');"></div>
                    <div class="b">
                        <div class="meta">Packing Tips</div>
                        <span class="h3">How to Pack Your Storage Unit Efficiently</span>
                        <p>Simple techniques to protect your belongings and make the most of every square foot.</p>
                        <a href="{{ url('/blogs') }}">Read packing tips on our blog <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="sk-blogcard">
                    <div class="img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing/inline-22.jpg') }}');"></div>
                    <div class="b">
                        <div class="meta">Choosing a Unit</div>
                        <span class="h3">What Size Storage Unit Do You Actually Need?</span>
                        <p>A quick guide to matching unit sizes to household and business items.</p>
                        <a href="{{ url('/blogs') }}">Read unit size guide on our blog <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="sk-blogcard">
                    <div class="img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing/inline-23.jpg') }}');"></div>
                    <div class="b">
                        <div class="meta">Business Storage</div>
                        <span class="h3">Using Self Storage for Inventory Management</span>
                        <p>How UAE retailers and e-commerce sellers use storage to scale flexibly.</p>
                        <a href="{{ url('/blogs') }}">Read inventory storage tips on our blog <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- ============ FAQ ============ -->
    <section class="sk-section" style="background:#fff;">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">FAQs</span>
                <h2>FAQs About Storage Units</h2>
                <p>Answers to the questions we're asked most often about renting storage in the UAE.</p>
            </div>
            <div class="sk-faq">
                <details open="">
                    <summary>How much does self storage cost in Dubai?</summary>
                    <div class="a">The cost depends on the unit size, rental duration and type of space required. We offer flexible plans to suit both personal and business requirements — contact our team for a tailored quote.</div>
                </details>
                <details>
                    <summary>Can I rent a storage unit for a short period?</summary>
                    <div class="a">Yes, we provide both short-term and long-term rental options, making our facilities suitable for temporary moves, renovations or seasonal requirements.</div>
                </details>
                <details>
                    <summary>What items can I store in a storage unit?</summary>
                    <div class="a">You can store furniture, documents, luggage, office equipment, household items and business inventory. Our team can also advise on suitable unit sizes based on your belongings.</div>
                </details>
                <details>
                    <summary>Is climate-controlled storage worth it in the UAE?</summary>
                    <div class="a">Yes, climate-controlled environments help protect sensitive items such as documents, electronics, artwork and wooden furniture from heat and humidity.</div>
                </details>
                <details>
                    <summary>Can businesses use storage units for inventory management?</summary>
                    <div class="a">Absolutely. Our business solutions are ideal for retailers, distributors and e-commerce businesses that require organised inventory storage and easy access.</div>
                </details>
                <details>
                    <summary>Can I access my unit at any time?</summary>
                    <div class="a">Yes, registered customers enjoy 24/7 access to their rented space across our storage facilities.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ CTA BAND ============ -->
    <section style="padding:0 0 84px;">
        <div class="sk-container">
            <div class="sk-cta">
                <div>
                    <h2>Get Started With Secure Storage Solutions Today</h2>
                    <p>Whether you need personal, business or warehouse storage, Storage Keys offers secure, flexible storage solutions across the UAE. Book your unit today and discover why customers trust us.</p>
                </div>
                <div class="btns">
                    <a href="/booking" class="sk-btn sk-btn-white"><i class="fas fa-boxes"></i> Book Your Storage Unit</a>
                    <a href="https://wa.me/971565018785" class="sk-btn sk-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE ACTION BAR ============ -->
    <div class="sk-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="{{ url('/booking') }}"><i class="fas fa-boxes"></i> Book</a>
    </div>

</div>

@endsection 