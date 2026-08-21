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
                    <span class="h4">Personal Storage</span>
                    <p>Secure personal storage units in Dubai and Sharjah for furniture, boxes, seasonal items and household belongings. Flexible short- or long-term space that frees up room at home without giving anything up.</p>
                    <a class="ab-svc-link" href="{{ url('/personal-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-couch"></i></div>
                    <span class="h4">Furniture Storage</span>
                    <p>Store sofas, beds, dining sets, wardrobes and office furniture securely during moves, renovations or decluttering. Flexible furniture storage units keep large pieces protected without overcrowding your home or workplace.</p>
                    <a class="ab-svc-link" href="{{ url('/furniture-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-box"></i></div>
                    <span class="h4">Box Storage</span>
                    <p>Keep packed belongings, documents, seasonal items and business stock in a dedicated facility instead of filling your home or office. Flexible box storage works for a few cartons or larger collections.</p>
                    <a class="ab-svc-link" href="{{ url('/box-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-blender"></i></div>
                    <span class="h4">Appliance Storage</span>
                    <p>Store refrigerators, washers, ovens and commercial appliances securely during moves, renovations or inventory overflow. Dedicated appliance storage frees valuable space at home, in a shop or on business premises.</p>
                    <a class="ab-svc-link" href="{{ url('/appliance-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <span class="h4">Business Storage</span>
                    <p>Affordable, scalable business storage for unused equipment, archived documents, excess stock and office furniture. Ideal for start-ups, SMEs and corporate teams that need space without wasting costly office floor area.</p>
                    <a class="ab-svc-link" href="{{ url('/business-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-warehouse"></i></div>
                    <span class="h4">Warehouse Storage</span>
                    <p>Cost-effective warehouse storage with scalable space, flexible leases and reliable security for growing businesses. Ideal for palletised goods, inventory overflow and commercial stock across Dubai and Sharjah.</p>
                    <a class="ab-svc-link" href="{{ url('/warehouse-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-snowflake"></i></div>
                    <span class="h4">Climate Controlled Storage</span>
                    <p>Protect furniture, electronics, documents and valuables from heat and humidity with climate-controlled units. Suitable for personal and business needs across Dubai, Sharjah and the wider UAE.</p>
                    <a class="ab-svc-link" href="{{ url('/climate-controlled-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-truck"></i></div>
                    <span class="h4">Moving</span>
                    <p>Professional movers and packers for local or international shifts — we dismantle, pack, load, move and reassemble your belongings carefully. A smooth handover from your current home or office to the next.</p>
                    <a class="ab-svc-link" href="{{ url('/moving-services') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-suitcase-rolling"></i></div>
                    <span class="h4">Luggage Storage</span>
                    <p>Store luggage safely between flights, hotel stays, moves or travel plans with flexible short-term options. Keep bags secure in Dubai and Sharjah without carrying them around all day.</p>
                    <a class="ab-svc-link" href="{{ url('/luggage-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-car"></i></div>
                    <span class="h4">Car Storage</span>
                    <p>Secure vehicle storage with flexible short- and long-term options for personal, classic or luxury cars. A professionally managed facility for vehicles that need a suitable place between uses.</p>
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
                        <a href="tel:8005397"><i class="fas fa-phone-alt"></i> Toll Free: 800 5397</a>
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
