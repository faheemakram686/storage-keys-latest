@extends('ui.layouts.frontend')
@section('title', '| Art Storage')
@section('metaTitle', 'Art Storage in Dubai & Sharjah | StorageKeys')
@section('metaDescription', 'Climate-controlled art storage in Dubai and Sharjah for paintings, sculpture and antiques. Secure units, careful handling and flexible terms. Get a quote.')

@section('headExtra')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            'name' => 'Art Storage in Dubai and Sharjah for Paintings, Sculpture and Collections',
            'serviceType' => 'Art storage',
            'alternateName' => ['Fine art storage', 'Painting and artwork storage', 'Antique storage', 'Gallery and collector storage'],
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
            'url' => 'https://storagekeys.com/art-storage',
            'description' => 'Climate-controlled art storage in Dubai and Sharjah for paintings, sculpture and antiques. Secure units, careful handling and flexible terms. Get a quote.',
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'What temperature is best for storing art?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Stable conditions matter more than a single number. Art should be kept cool, dry, dark and away from sudden changes, which is why climate-controlled units are recommended for paintings and paper.']],
                ['@type' => 'Question', 'name' => 'Can I store paintings flat in storage?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Framed and stretched paintings are best stored upright, separated by padding. Only unframed works on paper should lie flat, in acid-free folders or boxes.']],
                ['@type' => 'Question', 'name' => 'Is art storage secure for valuable pieces?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. Units are monitored by 24/7 CCTV with coded access, and only people you authorise can enter. We also recommend confirming your insurance covers works in storage.']],
                ['@type' => 'Question', 'name' => 'Can you collect artwork from my home or gallery?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. Our team collects from homes, galleries and studios in Dubai and Sharjah, wrapping and transporting pieces upright and secured, then delivering them back when you need them.']],
            ],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://storagekeys.com'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Personal Storage', 'item' => 'https://storagekeys.com/personal-storage'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Art Storage', 'item' => 'https://storagekeys.com/art-storage'],
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
            <div class="lt-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/personal-storage') }}">Personal Storage</a> <i class="fas fa-chevron-right"></i> <span>Art Storage</span></div>
            <span class="sk-eyebrow sk-eyebrow--light">Collectors, Galleries &amp; Artists</span>
            <h1>Art Storage in Dubai and Sharjah <span>for Paintings, Sculpture and Collections</span></h1>
            <p class="lead">Climate-controlled art storage for paintings, prints, sculpture and antiques, with careful handling, secure 24/7 monitored units and flexible terms for private collectors, galleries, artists, designers and families across Dubai and Sharjah.</p>
            <div class="lt-hero-cta">
                <a href="#as-quote" class="sk-btn sk-btn-primary"><i class="fas fa-warehouse"></i> Get a Free Quote</a>
                <a href="https://wa.me/971565018785" class="sk-btn sk-btn-ghost"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
            <div class="lt-hero-badges">
                <span class="lt-hbadge"><i class="fas fa-palette"></i> Paintings</span>
                <span class="lt-hbadge"><i class="fas fa-image"></i> Prints &amp; paper</span>
                <span class="lt-hbadge"><i class="fas fa-monument"></i> Sculpture</span>
                <span class="lt-hbadge"><i class="fas fa-chess-rook"></i> Antiques</span>
            </div>
        </div>
    </section>

    <div class="lt-trust">
        <div class="sk-container">
            <div class="lt-trust-in">
                <div class="lt-trust-i"><i class="fas fa-snowflake"></i> Climate Controlled</div>
                <div class="lt-trust-i"><i class="fas fa-video"></i> 24/7 CCTV</div>
                <div class="lt-trust-i"><i class="fas fa-lock"></i> Coded Access</div>
                <div class="lt-trust-i"><i class="fas fa-hands"></i> Careful Handling</div>
                <div class="lt-trust-i"><i class="fas fa-calendar-alt"></i> Flexible Terms</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Art Storage Built Around the UAE Climate</h2>
                    <p>Artwork reacts to its surroundings. Canvas slackens and tightens as humidity changes, wooden frames and panels move, paper cockles, and pigments fade in strong light. In the UAE, where summer heat and coastal humidity swing sharply, a spare room or garage is rarely a safe place for pieces you care about.</p>
                    <p>StorageKeys provides art storage for private collectors, galleries, artists, interior designers and families across Dubai and Sharjah. Our Sharjah facility is monitored around the clock and the units are climate controlled, so works are kept away from heat, damp and daylight while they are not on display.</p>
                    <p>Art storage sits alongside our <a href="{{ url('/personal-storage') }}">storage for household belongings</a>, so a family can keep a painting collection and other valuables in one place.</p>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">What We Store</span>
                <h2>What Our Art Storage Clients Keep With Us</h2>
                <p>Every collection is different. These are the pieces we look after most often.</p>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Paintings &amp; Works on Canvas</h3><p>Oils, acrylics and mixed media, framed or unframed. Stored upright, separated and protected at the corners so nothing presses against the paint surface.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Prints, Photographs &amp; Works on Paper</h3><p>Limited editions, photographs, drawings and calligraphy. Paper is the most sensitive to humidity, so stable, dark conditions matter most here.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>Sculpture &amp; Objects</h3><p>Bronze, ceramic, glass, stone and wood pieces, kept in padded crates or boxes and positioned so they cannot tip or be knocked.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Antiques &amp; Heritage Pieces</h3><p>Inherited furniture, carpets, manuscripts and family heirlooms that need the same care as fine art.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Who Uses It</span>
                    <h2>Who Uses Art Storage in Dubai and Sharjah</h2>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-gem"></i></div><div><h4>Private Collectors</h4><p>Collections often outgrow wall space. Rotating works between home and storage keeps everything protected without selling pieces you want to keep.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-store-alt"></i></div><div><h4>Galleries &amp; Art Advisors</h4><p>Between exhibitions and fairs such as Art Dubai, unsold or incoming work needs a secure, stable home outside the gallery floor.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-paint-brush"></i></div><div><h4>Artists</h4><p>Finished canvases, archives and works awaiting shipment take up studio space quickly. A unit keeps the studio clear for new work.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-drafting-compass"></i></div><div><h4>Interior Designers &amp; Hospitality</h4><p>Pieces bought for a villa, hotel or office fit-out often arrive months before installation. Holding them in proper storage avoids damage on a busy site.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-home"></i></div><div><h4>Families &amp; Expats</h4><p>During a move, a renovation or a long stay abroad, paintings and heirlooms are safer in storage than left in an empty, unconditioned home.</p></div></li>
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
                    <h2>Why Conditions Matter More Than Square Metres</h2>
                    <p>For most collections, the bigger risk is change rather than theft. Rapid swings in temperature and humidity cause materials to expand and contract, which leads to cracking, flaking, warping and mould. Keeping works in <a href="{{ url('/climate-controlled-storage') }}">a unit that holds a steady, cool temperature</a> protects them far better than a room that is only cooled when someone is home.</p>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-snowflake"></i></div><div><h4>Climate-Controlled Units</h4><p>Kept cool and stable, away from direct sunlight, with works off the floor and away from walls.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-video"></i></div><div><h4>24/7 CCTV &amp; Coded Access</h4><p>Round-the-clock monitoring, with access only for you and the people you authorise.</p></div></li>
                    </ul>
                    <p style="font-size:13.5px;color:var(--sk-muted);margin:0;">For high-value collections, check that your art insurance covers off-site storage, and keep your own photographs and condition notes for each piece.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">How It Works</span>
                <h2>How Art Storage Works With StorageKeys</h2>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Share Your Inventory</h3><p>Tell us how many pieces you have, approximate sizes, whether they are framed or crated, and how long you plan to store them. We recommend a unit and send a quote.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Pack and Transport With Care</h3><p>Paintings travel upright and wrapped, sculpture is padded and secured, and nothing is stacked flat on a painted surface. Our <a href="{{ url('/moving-services') }}">collection and moving team</a> can handle transport from your home, gallery or studio.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>Store and Rotate</h3><p>Works are placed so each one can be reached without moving the rest. Collect pieces for display and return them whenever you like.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Review Over Time</h3><p>For long stays, visit periodically to check condition and update your records.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section lt-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Packing Advice</span>
                <h2>How to Prepare Artwork Before It Goes Into Storage</h2>
            </div>
            <div class="lt-tips sk-reveal">
                <div class="lt-tip"><i class="fas fa-layer-group"></i><h4>Wrap in layers, not plastic alone</h4><p>Acid-free tissue or glassine against the surface, then bubble wrap with bubbles facing outward, and corner protectors on frames. Never leave wrapped works in a parked car in summer.</p></div>
                <div class="lt-tip"><i class="fas fa-square"></i><h4>Protect glass</h4><p>Tape a cross of low-tack masking tape on glazed frames so that if the glass breaks, it does not scratch the work beneath.</p></div>
                <div class="lt-tip"><i class="fas fa-camera"></i><h4>Label and photograph</h4><p>Note the title, artist, size and condition on each package and keep matching photos.</p></div>
            </div>
            <p class="note sk-reveal">Our guide on <a href="{{ url('/blogs/what-can-and-cant-be-stored-in-a-storage-unit') }}" style="color:#fff;text-decoration:underline;">what needs preparing before it goes into a unit</a> covers related items such as frames with batteries or lighting.</p>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Short or Long Term</span>
                <h2>Short-Term and Long-Term Art Storage</h2>
                <p>Some works need a home for a few weeks, while a wall is repainted or a new frame is made. Others stay stored for years as part of a growing collection or an estate.</p>
            </div>
            <div class="lt-use sk-reveal">
                <div class="lt-usec"><div class="ic"><i class="fas fa-hourglass-half"></i></div><h4>Short Stays</h4><p>Renovations, exhibitions and moves usually need flexible month-to-month space with easy access to collect pieces as soon as the room is ready.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-calendar-check"></i></div><h4>Long Stays</h4><p>Collections, inheritances and works kept while living abroad benefit from <a href="{{ url('/long-term-storage') }}">a unit you can keep for years</a>, with periodic visits to check condition.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-expand-arrows-alt"></i></div><h4>Grow as You Go</h4><p>Change unit size as the collection grows, and move heavy antique pieces into <a href="{{ url('/furniture-storage') }}">space suited to larger furniture</a> if needed.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Areas We Serve</span>
                <h2>Art Storage for Collectors Across Dubai and Sharjah</h2>
                <p>Tell us about any pieces that need special handling, such as large canvases, heavy sculpture or fragile antiques, and we will plan the collection around them.</p>
            </div>
            <div class="lt-cover sk-reveal">
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Dubai</h4><p>Alserkal Avenue, Al Quoz, DIFC, Jumeirah, Emirates Hills, Palm Jumeirah and Dubai Marina. Our Sharjah facility gives Dubai collectors a secure option outside busy residential areas.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Sharjah</h4><p>Al Majaz, Al Khan, Al Nahda and Muwaileh, collecting from homes, galleries and studios.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-route"></i></div><h4>Planned Collections</h4><p>Large or valuable works collected at a time that suits you, with route, vehicle and wrapping planned in advance to limit exposure to summer heat on the road.</p></div>
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
                    <summary>What temperature is best for storing art?</summary>
                    <div class="a">Stable conditions matter more than a single number. Art should be kept cool, dry, dark and away from sudden changes, which is why climate-controlled units are recommended for paintings and paper.</div>
                </details>
                <details>
                    <summary>Can I store paintings flat in storage?</summary>
                    <div class="a">Framed and stretched paintings are best stored upright, separated by padding. Only unframed works on paper should lie flat, in acid-free folders or boxes.</div>
                </details>
                <details>
                    <summary>Is art storage secure for valuable pieces?</summary>
                    <div class="a">Yes. Units are monitored by 24/7 CCTV with coded access, and only people you authorise can enter. We also recommend confirming your insurance covers works in storage.</div>
                </details>
                <details>
                    <summary>Can you collect artwork from my home or gallery?</summary>
                    <div class="a">Yes. Our team collects from homes, galleries and studios in Dubai and Sharjah, wrapping and transporting pieces upright and secured, then delivering them back when you need them.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="sk-section lt-quote-wrap" id="as-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">Free Quote</span>
                    <h2>Keep Your Collection Safe Between Walls</h2>
                    <p>Tell us what you need to store and for how long. We will recommend the right climate-controlled unit and send a clear quote for art storage in Dubai or Sharjah.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'source' => 'art-storage',
                    'defaultStorage' => 'Art Storage',
                    'variant' => 'business',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'showStorageSelect' => false,
                    'storingOptions' => [
                        'Paintings & canvas' => 'Paintings & canvas',
                        'Prints, photographs & paper' => 'Prints, photographs & paper',
                        'Sculpture & objects' => 'Sculpture & objects',
                        'Antiques & heirlooms' => 'Antiques & heirlooms',
                        'Mixed collection' => 'Mixed collection',
                        'Other' => 'Other',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="lt-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#as-quote"><i class="fas fa-warehouse"></i> Quote</a>
    </div>

</div>
@endsection
