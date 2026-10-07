@extends('ui.layouts.frontend')
@section('title', '| Pharmaceutical Storage')
@section('metaTitle', 'Pharmaceutical Storage in Dubai & Sharjah | StorageKeys')
@section('metaDescription', 'Licensed, climate-controlled pharmaceutical storage in Dubai and Sharjah for medicines and medical supplies. Secure, monitored units. Get a quote today.')

@section('headExtra')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            'name' => 'Pharmaceutical Storage in Dubai and Sharjah for Pharmacies, Clinics and Suppliers',
            'serviceType' => 'Pharmaceutical storage',
            'alternateName' => ['Medicine storage', 'Medical supplies storage', 'Pharmacy stock storage', 'Healthcare storage'],
            'provider' => [
                '@type' => 'Organization',
                'name' => 'StorageKeys',
                'url' => 'https://storagekeys.com',
                'telephone' => '+971 56 501 8785',
                'email' => 'sales@storagekeys.com',
            ],
            'areaServed' => [
                ['@type' => 'City', 'name' => 'Dubai'],
                ['@type' => 'City', 'name' => 'Sharjah'],
            ],
            'url' => 'https://storagekeys.com/pharmaceutical-storage',
            'description' => 'Licensed, climate-controlled pharmaceutical storage in Dubai and Sharjah for medicines and medical supplies. Secure, monitored units. Get a quote today.',
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Is your facility licensed for pharmaceutical storage?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. Our Sharjah facility holds the licence required for pharmaceutical storage in the UAE, and documentation is available to customers on request.']],
                ['@type' => 'Question', 'name' => 'Can you store refrigerated medicines?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Products that need refrigeration or freezing must be confirmed before booking. Tell us the required temperature range and we will advise honestly whether we can meet it.']],
                ['@type' => 'Question', 'name' => 'Who can access stored pharmaceutical stock?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Only staff you authorise. Units are protected by 24/7 CCTV and coded access, and you can update the list of authorised people at any time.']],
                ['@type' => 'Question', 'name' => 'What healthcare products cannot be stored?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Controlled drugs, radioactive materials, hazardous chemicals, flammable products and clinical waste need specialist facilities and cannot be stored with us.']],
            ],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://storagekeys.com'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Business Storage', 'item' => 'https://storagekeys.com/business-storage'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Pharmaceutical Storage', 'item' => 'https://storagekeys.com/pharmaceutical-storage'],
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')

<div class="sk-home">

    <section class="lt-hero">
        <div class="sk-container">
            <div class="lt-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/business-storage') }}">Business Storage</a> <i class="fas fa-chevron-right"></i> <span>Pharmaceutical Storage</span></div>
            <span class="sk-eyebrow sk-eyebrow--light">Pharmacies, Clinics &amp; Medical Suppliers</span>
            <h1>Pharmaceutical Storage in Dubai and Sharjah <span>for Pharmacies, Clinics and Suppliers</span></h1>
            <p class="lead">Licensed, climate-controlled pharmaceutical storage for medicines, medical supplies and healthcare products, with 24/7 monitoring, controlled access and flexible terms for pharmacies, clinics, medical suppliers, distributors and healthcare start-ups across Dubai and Sharjah.</p>
            <div class="lt-hero-cta">
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-warehouse"></i> Get a Free Quote</a>
                <a href="https://wa.me/971565018785" class="sk-btn sk-btn-ghost"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
            <div class="lt-hero-badges">
                <span class="lt-hbadge"><i class="fas fa-pills"></i> Pharmacy stock</span>
                <span class="lt-hbadge"><i class="fas fa-medkit"></i> Consumables</span>
                <span class="lt-hbadge"><i class="fas fa-stethoscope"></i> Medical devices</span>
                <span class="lt-hbadge"><i class="fas fa-folder-open"></i> Records</span>
            </div>
        </div>
    </section>

    <div class="lt-trust">
        <div class="sk-container">
            <div class="lt-trust-in">
                <div class="lt-trust-i"><i class="fas fa-certificate"></i> Licensed Facility</div>
                <div class="lt-trust-i"><i class="fas fa-snowflake"></i> Climate Controlled</div>
                <div class="lt-trust-i"><i class="fas fa-video"></i> 24/7 CCTV</div>
                <div class="lt-trust-i"><i class="fas fa-user-lock"></i> Controlled Access</div>
                <div class="lt-trust-i"><i class="fas fa-calendar-alt"></i> Flexible Terms</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Pharmaceutical Storage Built for Compliance and Control</h2>
                    <p>Medicines and medical supplies are some of the most regulated products in the UAE. They need stable conditions, controlled access and clear records, and a back room in a pharmacy or a shared warehouse corner often cannot provide all three.</p>
                    <p>StorageKeys provides pharmaceutical storage for pharmacies, clinics, medical centres, distributors and suppliers across Dubai and Sharjah. Our Sharjah facility holds the licence required for storing pharmaceutical products in the UAE, and documentation is available to customers on request.</p>
                    <p>It sits within our <a href="{{ url('/business-storage') }}">storage for companies across the UAE</a>, so healthcare businesses can keep regulated stock, records and equipment with one provider. Flexible, quote-based terms mean you pay for the space you use, not a long fixed lease.</p>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">What We Store</span>
                <h2>What Healthcare Businesses Keep With Us</h2>
                <p>Every product has its own storage requirements. Tell us what you hold and the conditions the manufacturer specifies, and we confirm the right arrangement before you book.</p>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Pharmacy Overflow Stock</h3><p>Seasonal and bulk stock for pharmacy groups, such as cold and flu lines ahead of winter, held off site until branches need it.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Medical Consumables</h3><p>Dressings, gloves, syringes, PPE and other consumables for clinics, medical centres and home healthcare providers.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>Medical Devices &amp; Equipment</h3><p>Diagnostic devices, mobility aids and clinic equipment between installations, refurbishments or tenders.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Healthcare Records &amp; Archives</h3><p>Patient files, batch records and regulatory documents that must be kept securely for set periods.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Who Uses It</span>
                    <h2>Who Needs Pharmaceutical Storage in the UAE</h2>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-prescription-bottle-alt"></i></div><div><h4>Pharmacy Groups</h4><p>Branch storerooms are small, and rent in malls and residential towers is high. Off-site stock lets each branch carry what it sells day to day.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-hospital"></i></div><div><h4>Clinics &amp; Medical Centres</h4><p>New branches, refurbishments and bulk purchasing all create a need for secure overflow space.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-truck-loading"></i></div><div><h4>Distributors &amp; Suppliers</h4><p>Companies importing into the UAE often need flexible space as tenders, contracts and seasonal demand change.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-rocket"></i></div><div><h4>Healthcare Start-Ups</h4><p>New clinics and home healthcare providers can hold stock and equipment properly without committing to a large warehouse lease.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-tooth"></i></div><div><h4>Veterinary &amp; Dental Practices</h4><p>Smaller practices often buy consumables in bulk to control costs, and need somewhere secure to keep the surplus.</p></div></li>
                    </ul>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="lt-split rev sk-reveal">
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Conditions &amp; Compliance</span>
                    <h2>Why Conditions and Records Matter</h2>
                    <p>Heat and humidity can reduce the effectiveness of medicines and damage packaging, and in the UAE summer that risk is constant. Products must be kept within the conditions printed on their labels and specified by the manufacturer. Our <a href="{{ url('/climate-controlled-storage') }}">temperature-stable, climate-controlled units</a> keep stock away from heat, damp and direct light.</p>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-certificate"></i></div><div><h4>Licensed for Pharmaceutical Storage</h4><p>Documentation available on request, in climate-controlled units away from heat and light.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-video"></i></div><div><h4>24/7 CCTV &amp; Coded Access</h4><p>Access limited to the staff you authorise.</p></div></li>
                    </ul>
                    <p style="font-size:13.5px;color:var(--sk-muted);margin:0;">If your products need specific temperature ranges, such as refrigerated or frozen storage, tell us before booking so we can confirm whether we can meet them.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">How It Works</span>
                <h2>How Pharmaceutical Storage Works With StorageKeys</h2>
                <p>Organise stock by product, batch and expiry date, so the oldest stock leaves first and nothing expires on a back shelf. A stock list that matches your unit layout makes audits much faster.</p>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Share Your Requirements</h3><p>Tell us the product types, storage conditions, quantities and how long you need space. We confirm suitability and send a quote.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Review Documentation</h3><p>Ask for the facility licence and any information your quality or compliance team needs before stock moves.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>Move Stock In</h3><p>Our <a href="{{ url('/moving-services') }}">collection and transport team</a> can collect from your pharmacy, clinic or supplier, or you can deliver yourself.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Access and Replenish</h3><p>Authorised staff can access stock to replenish branches, and you can adjust space as volumes change.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Records &amp; Archives</span>
                    <h2>Storing Healthcare Records Securely</h2>
                    <p>Pharmacies and clinics generate paperwork that must be kept for years: prescriptions, batch records, purchase invoices and patient files. These need to be secure, organised and retrievable when an inspector or auditor asks.</p>
                    <p>Archive boxes kept in <a href="{{ url('/box-storage') }}">space sized around archive boxes</a> stay dry and flat, and labelling by year and branch makes retrieval quick. Only staff you authorise can access them. Keep an index of what each box contains, so a single record can be found in minutes rather than hours.</p>
                    <p>For companies moving premises at the same time, <a href="{{ url('/office-storage') }}">storage for office furniture and files</a> can be arranged alongside your pharmaceutical storage.</p>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">What We Cannot Store</span>
                <h2>Products That Need Specialist Facilities</h2>
                <p>Some healthcare products need specialist handling that a general storage facility should not offer. Our team will tell you honestly whether your products are suitable, so you never put stock or your licence at risk.</p>
            </div>
            <div class="lt-use sk-reveal">
                <div class="lt-usec"><div class="ic"><i class="fas fa-ban"></i></div><h4>Not Stored With Us</h4><p>Controlled drugs, radioactive materials, hazardous chemicals, flammable products and clinical waste require specialist licensed facilities.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-thermometer-quarter"></i></div><h4>Confirm Before Booking</h4><p>Products that need continuous refrigeration or freezing must be confirmed with our team before booking.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-check-double"></i></div><h4>Store the Rest</h4><p>If part of your range is unsuitable, we can still store consumables, devices and records while specialist items stay with a specialist provider.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section lt-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Before You Store</span>
                <h2>Preparing Healthcare Stock for Storage</h2>
            </div>
            <div class="lt-tips sk-reveal">
                <div class="lt-tip"><i class="fas fa-clipboard-check"></i><h4>Check and separate</h4><p>Remove damaged, recalled or expired items before anything moves, and keep returns separate from saleable stock.</p></div>
                <div class="lt-tip"><i class="fas fa-box"></i><h4>Keep the packaging intact</h4><p>Store products in their original sealed cartons with batch numbers and expiry dates visible, and use sturdy outer boxes for loose items.</p></div>
                <div class="lt-tip"><i class="fas fa-clipboard-list"></i><h4>Update your records</h4><p>Record what is going into storage, where it is in the unit and who is authorised to access it, so your quality team has a clear trail from day one.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Areas We Serve</span>
                <h2>Pharmaceutical Storage Across Dubai and Sharjah</h2>
                <p>Collections and deliveries can be planned around pharmacy opening hours and clinic schedules, so stock moves without disrupting patients or customers.</p>
            </div>
            <div class="lt-cover sk-reveal">
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Dubai</h4><p>Dubai Healthcare City, Dubai Science Park, Al Qusais, Dubai Investments Park, Jumeirah, Al Barsha and Deira.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Sharjah</h4><p>Al Nahda, Al Majaz, Muwaileh and Industrial Areas. A cost-effective alternative to space in expensive commercial districts.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-network-wired"></i></div><h4>Multi-Branch Groups</h4><p>One central storage point in Sharjah can supply branches across both emirates, reducing the stock each branch keeps on its own shelves.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="lt-faq">
                <details open>
                    <summary>Is your facility licensed for pharmaceutical storage?</summary>
                    <div class="a">Yes. Our Sharjah facility holds the licence required for pharmaceutical storage in the UAE, and documentation is available to customers on request.</div>
                </details>
                <details>
                    <summary>Can you store refrigerated medicines?</summary>
                    <div class="a">Products that need refrigeration or freezing must be confirmed before booking. Tell us the required temperature range and we will advise honestly whether we can meet it.</div>
                </details>
                <details>
                    <summary>Who can access stored pharmaceutical stock?</summary>
                    <div class="a">Only staff you authorise. Units are protected by 24/7 CCTV and coded access, and you can update the list of authorised people at any time.</div>
                </details>
                <details>
                    <summary>What healthcare products cannot be stored?</summary>
                    <div class="a">Controlled drugs, radioactive materials, hazardous chemicals, flammable products and clinical waste need specialist facilities and cannot be stored with us.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="sk-section lt-quote-wrap" id="ps-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">Free Quote</span>
                    <h2>Need Compliant Space for Healthcare Stock?</h2>
                    <p>Tell us what you need to store and the conditions it requires. We will confirm suitability and send a clear quote for pharmaceutical storage in Dubai or Sharjah.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'source' => 'pharmaceutical-storage',
                    'defaultStorage' => 'Pharmaceutical Storage',
                    'variant' => 'business',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'showStorageSelect' => false,
                    'storingOptions' => [
                        'Pharmacy overflow stock' => 'Pharmacy overflow stock',
                        'Medical consumables' => 'Medical consumables',
                        'Medical devices & equipment' => 'Medical devices & equipment',
                        'Healthcare records & archives' => 'Healthcare records & archives',
                        'Mixed healthcare stock' => 'Mixed healthcare stock',
                        'Other' => 'Other',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="lt-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ps-quote"><i class="fas fa-warehouse"></i> Quote</a>
    </div>

</div>
@endsection
