@extends('ui.layouts.frontend')
@section('title', '| Boat Storage')
@section('metaTitle', 'Boat Storage in Dubai & Sharjah | StorageKeys')
@section('metaDescription', 'Secure boat storage in Dubai and Sharjah for boats, small yachts, jet skis and marine gear. 24/7 CCTV, coded access and flexible terms. Get a quote today.')

@section('headExtra')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            'name' => 'Boat Storage in Dubai and Sharjah for Boats, Small Yachts and Jet Skis',
            'serviceType' => 'Boat storage',
            'alternateName' => ['Yacht storage', 'Jet ski storage', 'Marine storage', 'Boat trailer storage'],
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
            'url' => 'https://storagekeys.com/boat-storage',
            'description' => 'Secure boat storage in Dubai and Sharjah for boats, small yachts, jet skis and marine gear. 24/7 CCTV, coded access and flexible terms. Get a quote today.',
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Can I store a boat on its trailer?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. Boats and jet skis are stored on their road trailers. Tell us the total length and height on the trailer so we can confirm the right space before you book.']],
                ['@type' => 'Question', 'name' => 'Do I need to drain the fuel before boat storage?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Agree the fuel level with our team before you arrive, and remove gas bottles and batteries. Clean, dry and safely prepared boats can then be stored.']],
                ['@type' => 'Question', 'name' => 'Can I store a jet ski with you?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. Jet skis on single or double trailers can be stored, along with wetsuits, life jackets and other gear kept in a separate unit if you prefer.']],
                ['@type' => 'Question', 'name' => 'How long can I keep my boat in storage?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'As long as you need. Many owners store for the summer months only, while others store for a year or more while working abroad.']],
            ],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://storagekeys.com'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Vehicle Storage', 'item' => 'https://storagekeys.com/vehicle-storage'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Boat and Yacht Storage', 'item' => 'https://storagekeys.com/boat-storage'],
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
            <div class="lt-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/vehicle-storage') }}">Vehicle Storage</a> <i class="fas fa-chevron-right"></i> <span>Boat &amp; Yacht Storage</span></div>
            <span class="sk-eyebrow sk-eyebrow--light">Boat, Yacht &amp; Jet Ski Owners</span>
            <h1>Boat Storage in Dubai and Sharjah <span>for Boats, Small Yachts and Jet Skis</span></h1>
            <p class="lead">Secure boat storage for leisure boats, small yachts, jet skis and marine gear, with 24/7 monitoring, coded access and flexible terms for summer breaks, travel and the months between seasons in Dubai and Sharjah.</p>
            <div class="lt-hero-cta">
                <a href="#bs-quote" class="sk-btn sk-btn-primary"><i class="fas fa-warehouse"></i> Get a Free Quote</a>
                <a href="https://wa.me/971565018785" class="sk-btn sk-btn-ghost"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
            <div class="lt-hero-badges">
                <span class="lt-hbadge"><i class="fas fa-ship"></i> Boats on trailers</span>
                <span class="lt-hbadge"><i class="fas fa-tint"></i> Jet skis</span>
                <span class="lt-hbadge"><i class="fas fa-anchor"></i> Small yachts</span>
                <span class="lt-hbadge"><i class="fas fa-life-ring"></i> Marine gear</span>
            </div>
        </div>
    </section>

    <div class="lt-trust">
        <div class="sk-container">
            <div class="lt-trust-in">
                <div class="lt-trust-i"><i class="fas fa-video"></i> 24/7 CCTV</div>
                <div class="lt-trust-i"><i class="fas fa-lock"></i> Coded Access</div>
                <div class="lt-trust-i"><i class="fas fa-ship"></i> Boats &amp; Jet Skis</div>
                <div class="lt-trust-i"><i class="fas fa-life-ring"></i> Marine Gear</div>
                <div class="lt-trust-i"><i class="fas fa-calendar-alt"></i> Flexible Terms</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Boat Storage Away From Sun, Salt and Crowded Berths</h2>
                    <p>Owning a boat in the UAE is a pleasure in winter and a problem in summer. Months of strong sun fade gelcoat and upholstery, salt air corrodes fittings, and a boat left on a driveway or in a villa garden can break community rules and attract attention from the wrong people.</p>
                    <p>StorageKeys provides boat storage for owners of leisure boats, small yachts, RIBs and jet skis across Dubai and Sharjah. Our Sharjah facility is monitored around the clock with coded access, giving your boat and its equipment a secure place to wait out the hot months or your time away.</p>
                    <p>It sits within our <a href="{{ url('/vehicle-storage') }}">storage for vehicles of every kind</a>, so one provider can look after the boat, the trailer and the family car together.</p>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">What We Store</span>
                <h2>What Our Boat Storage Customers Keep With Us</h2>
                <p>Space depends on the length and height of your boat on its trailer, so we confirm the right option before you book. These are the most common requests.</p>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Boats on Trailers</h3><p>Fishing boats, speedboats, RIBs and day cruisers stored on their road trailers, ready to tow out when the season starts.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Jet Skis</h3><p>Personal watercraft on single or double trailers, often stored together with wetsuits, life jackets and tow ropes.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>Small Yachts &amp; Tenders</h3><p>Smaller yachts and tenders that can travel by road, along with covers, fenders and spare parts.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Marine Gear</h3><p>Outboard motors, fishing equipment, paddleboards, kayaks, diving kit and safety equipment that needs a cool, dry home.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Who Uses It</span>
                    <h2>Who Needs Boat Storage in the UAE</h2>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-plane-departure"></i></div><div><h4>Summer Travellers</h4><p>Many owners leave the UAE in July and August. Storing the boat avoids months of sun damage and the worry of leaving it unattended.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-home"></i></div><div><h4>Villa &amp; Apartment Residents</h4><p>Most communities restrict boats and trailers in driveways and car parks. Off-site storage keeps you within the rules.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-anchor"></i></div><div><h4>Owners Between Berths</h4><p>Marina berths in Dubai can be hard to secure. Storage fills the gap while you wait for a space or change marinas.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-globe"></i></div><div><h4>Expats Relocating</h4><p>If you are leaving for a posting abroad but plan to return, storing the boat is often better than selling it quickly at a low price.</p></div></li>
                    </ul>
                    <p style="font-size:13.5px;color:var(--sk-muted);margin:0;">Fishing enthusiasts who head out mainly in the cooler months also use boat storage between trips, without paying for a year-round berth.</p>
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
                    <span class="sk-eyebrow">Security &amp; Conditions</span>
                    <h2>Protecting Your Boat Between Trips</h2>
                    <p>A boat and its electronics, engines and gear represent a large investment. Our facility is monitored by CCTV around the clock and access is controlled by code, so only you and the people you authorise can reach it.</p>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-ruler-combined"></i></div><div><h4>Space Planned Around Your Boat</h4><p>Planned around its length and height, with gear stored alongside the boat or in a separate unit.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-snowflake"></i></div><div><h4>Gear Kept Cool &amp; Dry</h4><p>Cushions, sails and electronics are best kept in <a href="{{ url('/climate-controlled-storage') }}">a cool, dry unit</a> rather than aboard, where heat and humidity break down fabrics, foam and circuit boards.</p></div></li>
                    </ul>
                    <p style="font-size:13.5px;color:var(--sk-muted);margin:0;">Take photos of the hull, engine hours and equipment before storage, so you have a clear record of condition when the season starts again.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">How It Works</span>
                <h2>How Boat Storage Works With StorageKeys</h2>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Share Your Boat Details</h3><p>Tell us the make, length and height on the trailer, whether it is a boat or jet ski, and how long you need storage. We confirm the space and send a quote.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Prepare It for Storage</h3><p>Follow the preparation steps below so the boat is clean, dry and safe to store.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>Bring It In</h3><p>Tow the boat to our facility at an agreed time, and we will position it so it is secure and easy to reach.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Collect When the Season Starts</h3><p>Give us notice before your first trip, and the boat will be ready to tow out. Visits to collect gear or check on the boat can be arranged whenever you need them.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section lt-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Preparation</span>
                <h2>How to Prepare a Boat or Jet Ski for Storage</h2>
            </div>
            <div class="lt-tips sk-reveal">
                <div class="lt-tip"><i class="fas fa-tint"></i><h4>Flush and clean</h4><p>Flush engines with fresh water, wash salt off the hull and fittings, and let everything dry fully before covering. Salt left on metal is where corrosion starts.</p></div>
                <div class="lt-tip"><i class="fas fa-gas-pump"></i><h4>Fuel, batteries and gas</h4><p>Agree the fuel level with our team before arrival, disconnect or remove batteries, and take any gas bottles off board.</p></div>
                <div class="lt-tip"><i class="fas fa-shield-alt"></i><h4>Cover and check the trailer</h4><p>Use a breathable, fitted cover, inflate trailer tyres, and remove valuables, electronics and anything that could hold moisture.</p></div>
            </div>
            <p class="note sk-reveal">Our guide on <a href="{{ url('/blogs/what-can-and-cant-be-stored-in-a-storage-unit') }}" style="color:#fff;text-decoration:underline;">what can and cannot go into storage</a> explains the safety rules behind this.</p>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Short or Long Term</span>
                <h2>Seasonal and Long-Term Boat Storage</h2>
                <p>Most owners in the UAE use boat storage seasonally, from late spring to early autumn. Others store for a year or more while working abroad.</p>
            </div>
            <div class="lt-use sk-reveal">
                <div class="lt-usec"><div class="ic"><i class="fas fa-sun"></i></div><h4>Seasonal Storage</h4><p>For the summer months, <a href="{{ url('/short-term-storage') }}">storage booked month to month</a> lets you bring the boat out as soon as the weather turns.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-calendar-check"></i></div><h4>Longer Stays</h4><p>Plan a mid-term visit to check the battery, tyres and cover, and keep a record of the boat's condition.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-car"></i></div><h4>Leaving a Car Too?</h4><p>Our <a href="{{ url('/car-storage') }}">space for cars while you travel</a> can be arranged at the same time.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Areas We Serve</span>
                <h2>Boat Storage for Owners Across Dubai and Sharjah</h2>
                <p>Tell us where you usually launch and how often you plan to use the boat, and we will suggest the most practical storage arrangement.</p>
            </div>
            <div class="lt-cover sk-reveal">
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Dubai</h4><p>Owners who launch from Dubai Marina, Dubai Harbour, Jumeirah, Umm Suqeim, Palm Jumeirah and Dubai Creek Harbour.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Sharjah</h4><p>Owners around Al Khan, Al Layyeh and Al Heera. Our Sharjah facility is a practical base for owners in both emirates.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-route"></i></div><h4>East Coast &amp; Musandam Trips</h4><p>For occasional longer trips, the boat stays secure between outings without tying up a berth all year.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="lt-faq">
                <details open>
                    <summary>Can I store a boat on its trailer?</summary>
                    <div class="a">Yes. Boats and jet skis are stored on their road trailers. Tell us the total length and height on the trailer so we can confirm the right space before you book.</div>
                </details>
                <details>
                    <summary>Do I need to drain the fuel before boat storage?</summary>
                    <div class="a">Agree the fuel level with our team before you arrive, and remove gas bottles and batteries. Clean, dry and safely prepared boats can then be stored.</div>
                </details>
                <details>
                    <summary>Can I store a jet ski with you?</summary>
                    <div class="a">Yes. Jet skis on single or double trailers can be stored, along with wetsuits, life jackets and other gear kept in a separate unit if you prefer.</div>
                </details>
                <details>
                    <summary>How long can I keep my boat in storage?</summary>
                    <div class="a">As long as you need. Many owners store for the summer months only, while others store for a year or more while working abroad.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="sk-section lt-quote-wrap" id="bs-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">Free Quote</span>
                    <h2>Ready to Store Your Boat for the Season?</h2>
                    <p>Tell us about your boat and how long you need space. We will confirm the right option and send a clear quote for boat storage in Dubai or Sharjah.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'source' => 'boat-storage',
                    'defaultStorage' => 'Boat Storage',
                    'variant' => 'business',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'showStorageSelect' => false,
                    'storingOptions' => [
                        'Boat on trailer' => 'Boat on trailer',
                        'Jet ski' => 'Jet ski',
                        'Small yacht or tender' => 'Small yacht or tender',
                        'Marine gear' => 'Marine gear',
                        'Boat and gear' => 'Boat and gear',
                        'Other' => 'Other',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="lt-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#bs-quote"><i class="fas fa-warehouse"></i> Quote</a>
    </div>

</div>
@endsection
