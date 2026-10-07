@extends('ui.layouts.frontend')
@section('title', '| Office Storage')
@section('metaTitle', 'Office Storage in Dubai & Sharjah | StorageKeys')
@section('metaDescription', 'Secure office storage in Dubai and Sharjah for furniture, files and IT equipment. Climate-controlled units, collection and flexible terms. Get a quote.')

@section('headExtra')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            'name' => 'Office Storage in Dubai and Sharjah for Furniture, Files and IT Equipment',
            'serviceType' => 'Office storage',
            'alternateName' => ['Office furniture storage', 'Document and archive storage', 'IT equipment storage', 'Office relocation storage'],
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
            'url' => 'https://storagekeys.com/office-storage',
            'description' => 'Secure office storage in Dubai and Sharjah for furniture, files and IT equipment. Climate-controlled units, collection and flexible terms. Get a quote.',
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'What can I keep in office storage?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Office furniture, archived files, IT and AV equipment, and marketing or event materials. Hazardous, perishable and flammable items cannot be stored. Ask our team if you are unsure about anything.']],
                ['@type' => 'Question', 'name' => 'Is office storage secure enough for confidential files?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. Units are protected by 24/7 CCTV and coded access, and only staff you authorise can enter. Contracts, HR files and accounts are among the most common items companies keep long term.']],
                ['@type' => 'Question', 'name' => 'Can you collect furniture from our office?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. Our team can dismantle, wrap and collect furniture and boxes from your office in Dubai or Sharjah, then deliver everything back or to a new address when needed.']],
                ['@type' => 'Question', 'name' => 'How long can we keep items in office storage?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'As long as you need. Terms are flexible, from a few weeks during a refit to several years for archives, and you can change unit size as your plans change.']],
            ],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://storagekeys.com'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Business Storage', 'item' => 'https://storagekeys.com/business-storage'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Office Storage', 'item' => 'https://storagekeys.com/office-storage'],
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
            <div class="lt-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/business-storage') }}">Business Storage</a> <i class="fas fa-chevron-right"></i> <span>Office Storage</span></div>
            <span class="sk-eyebrow sk-eyebrow--light">Offices &amp; Corporate Teams</span>
            <h1>Office Storage in Dubai and Sharjah <span>for Furniture, Files and IT Equipment</span></h1>
            <p class="lead">Secure, climate-controlled office storage for furniture, archived files and spare IT equipment during moves, refits and downsizing, with collection from your office, flexible quote-based terms and access whenever your team needs it.</p>
            <div class="lt-hero-cta">
                <a href="#os-quote" class="sk-btn sk-btn-primary"><i class="fas fa-warehouse"></i> Get a Free Quote</a>
                <a href="https://wa.me/971565018785" class="sk-btn sk-btn-ghost"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
            <div class="lt-hero-badges">
                <span class="lt-hbadge"><i class="fas fa-chair"></i> Office furniture</span>
                <span class="lt-hbadge"><i class="fas fa-folder-open"></i> Files &amp; archives</span>
                <span class="lt-hbadge"><i class="fas fa-laptop"></i> IT &amp; AV</span>
                <span class="lt-hbadge"><i class="fas fa-bullhorn"></i> Event stock</span>
            </div>
        </div>
    </section>

    <div class="lt-trust">
        <div class="sk-container">
            <div class="lt-trust-in">
                <div class="lt-trust-i"><i class="fas fa-video"></i> 24/7 CCTV</div>
                <div class="lt-trust-i"><i class="fas fa-lock"></i> Coded Access</div>
                <div class="lt-trust-i"><i class="fas fa-snowflake"></i> Climate Controlled</div>
                <div class="lt-trust-i"><i class="fas fa-truck"></i> Collection Included</div>
                <div class="lt-trust-i"><i class="fas fa-calendar-alt"></i> Flexible Terms</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Office Storage That Frees Up Space You Are Paying For</h2>
                    <p>Office space in Dubai is priced by the square foot, so every corner taken up by spare desks, archive boxes or old monitors is costing the business money. Moving those items out of the workplace into a secure unit means the space you rent goes back to people and work.</p>
                    <p>StorageKeys provides office storage for companies across Dubai and Sharjah, from small teams in business centres to corporate offices in towers and free zones. Our facility in Sharjah is monitored around the clock, and the units are climate controlled, which matters for furniture, paper and electronics in the UAE summer.</p>
                    <p>It sits within our wider <a href="{{ url('/business-storage') }}">storage options for companies</a>, and suits anything that needs to stay safe and retrievable rather than in the way.</p>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">What We Store</span>
                <h2>What Offices Keep in Storage</h2>
                <p>Most office storage falls into four groups. Each has its own handling needs, and our team plans the unit around them.</p>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Office Furniture</h3><p>Desks, task chairs, meeting tables, partitions and reception furniture during a move, refit or downsizing. Pieces are wrapped and stacked so they come back ready to use.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Files &amp; Archives</h3><p>Contracts, HR files, accounts and records you must keep but rarely open. Labelled archive boxes kept off the floor stay dry, flat and easy to retrieve.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>IT &amp; AV Equipment</h3><p>Spare laptops, monitors, printers, servers and screens from hot-desking changes or upgrades. Cool, dry conditions protect electronics from heat damage.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Marketing &amp; Event Stock</h3><p>Exhibition stands, banners, brochures and promotional items between campaigns and trade shows such as GITEX and Arab Health.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">When Offices Use Storage</span>
                    <h2>Common Reasons Companies Need Office Storage</h2>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-people-carry"></i></div><div><h4>Office Relocation</h4><p>Moving between buildings rarely lines up perfectly. Storage holds furniture and files while the new space is fitted out, so the old lease can end on time.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-hammer"></i></div><div><h4>Refits &amp; Renovations</h4><p>A floor refurbishment can take weeks. Clearing the floor into a unit lets contractors work faster and protects furniture from dust and damage.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-compress-arrows-alt"></i></div><div><h4>Downsizing &amp; Hybrid Work</h4><p>Many teams have moved to fewer desks and more meeting space. Storing surplus furniture keeps your options open if headcount grows again, instead of selling it cheaply.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-file-invoice"></i></div><div><h4>Records You Must Keep</h4><p>UAE businesses hold accounting and tax records for years. Off-site storage keeps them organised without filling a room at head office.</p></div></li>
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
                    <span class="sk-eyebrow">Climate &amp; Security</span>
                    <h2>Built for UAE Conditions and Business Records</h2>
                    <p>An office storeroom or basement cage is often warm, damp or shared. In summer that is hard on wooden desks, upholstered chairs, laptops and paper. Our units are kept cool and dry, so items come out in the condition they went in. For sensitive equipment and archives, <a href="{{ url('/climate-controlled-storage') }}">temperature-stable units</a> are the safer choice for long stays.</p>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-video"></i></div><div><h4>24/7 CCTV &amp; Coded Access</h4><p>Round-the-clock monitoring and access control at the facility.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-user-shield"></i></div><div><h4>Authorised Staff Only</h4><p>You decide who in your company can visit the unit, and you can add or remove names as your team changes.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-archive"></i></div><div><h4>Clean, Dry &amp; Organised</h4><p>Well-lit storage areas, with archive boxes kept off the floor and stacked by label.</p></div></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">How It Works</span>
                <h2>How Office Storage Works With StorageKeys</h2>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Tell Us What You Are Storing</h3><p>Share a list or a few photos: number of desks and chairs, archive boxes, IT items and how long you need the space. We recommend a unit size and send a quote.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>We Collect and Pack</h3><p>Our team can disassemble furniture, wrap it, label archive boxes and move everything in one trip. If you are relocating at the same time, our <a href="{{ url('/moving-services') }}">office moving team</a> can handle both jobs together.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>We Store, You Access</h3><p>Items are placed so the things you need most stay near the front. Call ahead and we can have specific boxes ready, or visit the unit yourself.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Scale or Return</h3><p>Move to a bigger or smaller unit as your plans change, or book delivery to your new office when it is ready.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Why StorageKeys</span>
                <h2>Why Companies Choose Our Office Storage</h2>
                <p>Office storage only works if it is simple to manage. We keep terms flexible and quote-based, so you pay for the space you use rather than a long fixed lease.</p>
            </div>
            <div class="lt-use sk-reveal">
                <div class="lt-usec"><div class="ic"><i class="fas fa-file-signature"></i></div><h4>Flexible, Quote-Based Terms</h4><p>Pay for the space you use rather than a long fixed lease, and change unit size as your plans change.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-truck-moving"></i></div><h4>One Arrangement</h4><p>Collection and moving support can be included, so you are not coordinating a removals company, a storage provider and a cleaning crew on the day your lease ends.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-box"></i></div><h4>Start Small, Grow Later</h4><p>Teams that store mostly paperwork can start with <a href="{{ url('/box-storage') }}">space sized around archive boxes</a>. Companies holding stock as well can combine both in one plan.</p></div>
            </div>
            <p class="lt-hint sk-reveal">Before you decide, our guide on <a href="{{ url('/blogs/how-much-does-self-storage-cost-in-dubai') }}">what affects storage prices in Dubai</a> explains the factors behind a quote.</p>
        </div>
    </section>

    <section class="sk-section lt-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Before You Store</span>
                <h2>How to Prepare Office Items for Storage</h2>
                <p style="color:rgba(255,255,255,.8);">A little preparation saves time on collection day and makes retrieval much easier later.</p>
            </div>
            <div class="lt-tips sk-reveal">
                <div class="lt-tip"><i class="fas fa-laptop"></i><h4>Back up and wipe devices</h4><p>Back up data and log devices out of company accounts. Remove batteries from UPS units and keep cables and chargers bagged with each device.</p></div>
                <div class="lt-tip"><i class="fas fa-tags"></i><h4>Box and index your files</h4><p>Use strong archive boxes of the same size, label each with a number, department and date range, and keep a simple spreadsheet index.</p></div>
                <div class="lt-tip"><i class="fas fa-camera"></i><h4>Photograph furniture layouts</h4><p>If a floor is coming back after a refit, photos of the current layout and labelled parts make reassembly quicker.</p></div>
            </div>
            <p class="note sk-reveal">Our guide on <a href="{{ url('/blogs/how-to-pack-a-storage-unit-efficiently') }}" style="color:#fff;text-decoration:underline;">packing a unit so everything stays reachable</a> covers the rest.</p>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Areas We Serve</span>
                <h2>Office Storage for Dubai and Sharjah Businesses</h2>
                <p>Collections are scheduled around your working hours, so the move does not disrupt your team or your building's loading rules.</p>
            </div>
            <div class="lt-cover sk-reveal">
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Dubai</h4><p>Business Bay, DIFC, Downtown, Dubai Internet City, JLT, Al Quoz and Deira. Our Sharjah facility is a cost-effective alternative to storage inside expensive commercial districts.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Sharjah</h4><p>From Al Majaz and Al Nahda to the industrial areas and free zones.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-building"></i></div><h4>Towers &amp; Business Centres</h4><p>Most buildings need a gate pass or NOC before furniture can leave. Book the loading bay and service lift early, share the time slot with us, and we will work within it.</p></div>
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
                    <summary>What can I keep in office storage?</summary>
                    <div class="a">Office furniture, archived files, IT and AV equipment, and marketing or event materials. Hazardous, perishable and flammable items cannot be stored. Ask our team if you are unsure about anything.</div>
                </details>
                <details>
                    <summary>Is office storage secure enough for confidential files?</summary>
                    <div class="a">Yes. Units are protected by 24/7 CCTV and coded access, and only staff you authorise can enter. Contracts, HR files and accounts are among the most common items companies keep long term.</div>
                </details>
                <details>
                    <summary>Can you collect furniture from our office?</summary>
                    <div class="a">Yes. Our team can dismantle, wrap and collect furniture and boxes from your office in Dubai or Sharjah, then deliver everything back or to a new address when needed.</div>
                </details>
                <details>
                    <summary>How long can we keep items in office storage?</summary>
                    <div class="a">As long as you need. Terms are flexible, from a few weeks during a refit to several years for archives, and you can change unit size as your plans change.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="sk-section lt-quote-wrap" id="os-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">Free Quote</span>
                    <h2>Need Space Back in Your Office?</h2>
                    <p>Tell us what you need to store and for how long. We will recommend the right unit and send a clear quote for office storage in Dubai or Sharjah.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'source' => 'office-storage',
                    'defaultStorage' => 'Office Storage',
                    'variant' => 'business',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'showStorageSelect' => false,
                    'storingOptions' => [
                        'Office furniture' => 'Office furniture',
                        'Files & archives' => 'Files & archives',
                        'IT & AV equipment' => 'IT & AV equipment',
                        'Marketing & event stock' => 'Marketing & event stock',
                        'Mixed office items' => 'Mixed office items',
                        'Other' => 'Other',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="lt-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#os-quote"><i class="fas fa-warehouse"></i> Quote</a>
    </div>

</div>
@endsection
