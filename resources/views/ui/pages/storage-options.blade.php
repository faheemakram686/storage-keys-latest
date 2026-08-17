@extends('ui.layouts.frontend')
@section('title', '| Storage-Options')
@section('metaTitle', 'Storage Options | Storage Keys')
@section('metaDescription', 'Personal, business, warehouse, climate-controlled, luggage and car storage in Sharjah and Dubai, plus professional movers and packers.')
@section('content')

<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <span>Storage Options</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Storage Options</span>
            <h1>Storage <span>Options</span></h1>
            <p class="lead">Our individually lockable, climate-controlled personal storage units near you, in Sharjah and Dubai, plus affordable business storage, cost-effective warehousing, luggage and car storage, and professional movers and packers.</p>
            <div class="ps-hero-cta">
                <a href="#so-options" class="sk-btn sk-btn-primary"><i class="fas fa-boxes"></i> View Options</a>
                <a href="#so-quote" class="sk-btn sk-btn-ghost"><i class="fas fa-file-invoice-dollar"></i> Get A Quote</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-home"></i> Personal Storage</span>
                <span class="ps-hbadge"><i class="fas fa-briefcase"></i> Business Storage</span>
                <span class="ps-hbadge"><i class="fas fa-warehouse"></i> Warehouse</span>
                <span class="ps-hbadge"><i class="fas fa-snowflake"></i> Climate Controlled</span>
                <span class="ps-hbadge"><i class="fas fa-truck"></i> Moving</span>
                <span class="ps-hbadge"><i class="fas fa-suitcase-rolling"></i> Luggage</span>
                <span class="ps-hbadge"><i class="fas fa-car"></i> Car Storage</span>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="ps-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-temperature-low"></i> Climate-controlled</div>
                <div class="ps-trust-i"><i class="fas fa-map-marker-alt"></i> Sharjah and Dubai</div>
                <div class="ps-trust-i"><i class="fas fa-expand-arrows-alt"></i> Scalable space</div>
                <div class="ps-trust-i"><i class="fas fa-shield-alt"></i> Reliable security</div>
                <div class="ps-trust-i"><i class="fas fa-truck"></i> Professional movers</div>
            </div>
        </div>
    </div>

    <!-- ============ OPTIONS ============ -->
    <section class="sk-section" id="so-options">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Our Services</span>
                <h2>Storage Options</h2>
            </div>
            <div class="so-services sk-reveal">
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-home"></i></div>
                    <h4>Personal Storage</h4>
                    <p>Our individually lockable, climate-controlled personal storage units near you, in Sharjah and Dubai, are perfect for unused furniture you’re not ready to part with, family heirlooms you don’t have space for, books and clothes you use infrequently, or all the contents of your home when you’re shifting.</p>
                    <a class="ab-svc-link" href="{{ url('/personal-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <h4>Business Storage</h4>
                    <p>It makes little sense to use your expensive office space to store unused equipment and furniture, archived documents, or excess stock and accessories. At every growth stage, your UAE business can benefit from our affordable and scalable business storage units in Sharjah, with easy access and delivery. We offer start-up business storage, small business storage, and commercial storage units for rent for corporate companies.</p>
                    <a class="ab-svc-link" href="{{ url('/business-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-warehouse"></i></div>
                    <h4>Warehouse Storage</h4>
                    <p>With half of our 36,000 sq. ft. storage warehouse in Sharjah dedicated to businesses, we offer cost-effective warehousing solutions, with scalable space, flexible leases, reliable security and easy accessibility.</p>
                    <a class="ab-svc-link" href="{{ url('/warehouse-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-snowflake"></i></div>
                    <h4>Climate Controlled Storage</h4>
                    <p>Protect furniture, electronics, documents and valuable belongings from heat and humidity with secure climate-controlled storage units for personal and business needs across Dubai, Sharjah and the UAE.</p>
                    <a class="ab-svc-link" href="{{ url('/climate-controlled-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-truck"></i></div>
                    <h4>Moving</h4>
                    <p>Whether you’re shifting locally or internationally, our qualified team of professional movers and packers in Dubai will carefully dismantle, efficiently pack, load, move and accurately reassemble all your things in your new home or office space.</p>
                    <a class="ab-svc-link" href="{{ url('/moving-services') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-suitcase-rolling"></i></div>
                    <h4>Luggage Storage</h4>
                    <p>Store your luggage safely between flights, hotel stays, moves or travel plans with flexible luggage storage in Dubai and Abu Dhabi. Keep your bags secure without carrying them around all day.</p>
                    <a class="ab-svc-link" href="{{ url('/luggage-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-car"></i></div>
                    <h4>Car Storage</h4>
                    <p>Store your vehicle in a secure, professionally managed facility with flexible short-term and long-term options for personal vehicles, classic cars, luxury vehicles and extra cars that need a suitable place between uses.</p>
                    <a class="ab-svc-link" href="{{ url('/car-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="so-quote">
        <div class="sk-container">
            <div class="svc-quote so-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Find the Right Storage Option</h2>
                    <p>Whether you need space for household items, business inventory, luggage between trips, or a vehicle that is not in regular use, StorageKeys can help you choose a practical storage solution in Dubai, Sharjah and across the UAE.</p>
                    <p>Tell us what you need to store and how long you need it. Our team will recommend the most suitable option — with no unnecessary long-term commitment.</p>
                    <ul class="so-quote-points">
                        <li><i class="fas fa-check"></i> Personal, business and warehouse storage</li>
                        <li><i class="fas fa-check"></i> Climate-controlled units for sensitive items</li>
                        <li><i class="fas fa-check"></i> Luggage storage and car storage</li>
                        <li><i class="fas fa-check"></i> Moving and packing support</li>
                    </ul>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
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
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#so-quote"><i class="fas fa-box-open"></i> Quote</a>
    </div>

</div>
@endsection
