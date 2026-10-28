{{--
    Shared layout for every location page. $loc comes from App\Support\LocationPages::page().
    City pages list the city's services; service pages add a "What we store" block and link to
    the other services in the same city.
--}}
<div class="sk-home loc-page">

    <!-- ============ HERO ============ -->
    <section class="lt-hero loc-hero">
        <div class="sk-container">
            <div class="lt-crumb">
                <a href="{{ url('/') }}">Home</a>
                @foreach($loc['crumbs'] as [$crumbSlug, $crumbLabel])
                    <i class="fas fa-chevron-right"></i> <a href="{{ url('/' . $crumbSlug) }}">{{ $crumbLabel }}</a>
                @endforeach
                <i class="fas fa-chevron-right"></i> <span>{{ $loc['crumb'] }}</span>
            </div>
            <div class="loc-hero-grid">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">{{ $loc['eyebrow'] }}</span>
                    <h1>{{ $loc['h1'] }} <span>{{ $loc['h1Accent'] }}</span></h1>
                    <p class="lead">{{ $loc['lead'] }}</p>
                    <div class="lt-hero-cta">
                        <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-warehouse"></i> Get a Free Quote</a>
                        <a href="https://wa.me/971565018785" class="sk-btn sk-btn-ghost"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
                    </div>
                </div>
                <aside class="loc-card">
                    <div class="loc-card-top">
                        <span class="loc-card-badge"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $loc['city'] }}</span>
                        <span class="loc-card-open"><span class="dot" aria-hidden="true"></span> Open 24/7</span>
                    </div>
                    <span class="h3">{{ $loc['facilityName'] }}</span>
                    <p class="loc-card-rating"><i class="fas fa-star" aria-hidden="true"></i> 4.9 / 5 <span>on <i class="fab fa-google" aria-hidden="true"></i> Google reviews</span></p>
                    <ul class="loc-card-list">
                        <li><i class="fas fa-map-pin" aria-hidden="true"></i><span>{!! $loc['addressHtml'] !!}</span></li>
                        <li><i class="fas fa-phone" aria-hidden="true"></i><span><a href="tel:+971565018785">+971 56 501 8785</a> · Toll free <a href="tel:8005397">800 5397</a></span></li>
                        <li><i class="fas fa-envelope" aria-hidden="true"></i><span><a href="mailto:sales@storagekeys.com">sales@storagekeys.com</a></span></li>
                    </ul>
                    <div class="loc-card-actions">
                        <a href="{{ $loc['directions'] }}" target="_blank" rel="noopener" class="sk-btn sk-btn-primary"><i class="fas fa-directions" aria-hidden="true"></i> Directions</a>
                        <a href="tel:+971565018785" class="sk-btn sk-btn-outline"><i class="fas fa-phone" aria-hidden="true"></i> Call</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <!-- ============ STATS ============ -->
    <div class="loc-stats">
        <div class="sk-container">
            <div class="loc-stats-in">
                <div class="loc-stat"><strong>4.9<i class="fas fa-star" aria-hidden="true"></i></strong><span>Google rating</span></div>
                <div class="loc-stat"><strong>24/7</strong><span>Access &amp; CCTV</span></div>
                <div class="loc-stat"><strong>2016</strong><span>Serving the UAE since</span></div>
                <div class="loc-stat"><strong>2</strong><span>Facilities in Dubai &amp; Sharjah</span></div>
            </div>
        </div>
    </div>

    <!-- ============ OVERVIEW ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>{{ $loc['overviewTitle'] }}</h2>
                    @foreach($loc['overview'] as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                    <ul class="loc-ticks">
                        @foreach($loc['ticks'] as $tick)
                            <li><i class="fas fa-check" aria-hidden="true"></i> {{ $tick }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="loc-media-wrap">
                    <div class="lt-media" style="background-image:url('{{ asset($loc['image']) }}');"></div>
                    <div class="loc-media-tag"><i class="fas fa-shield-alt" aria-hidden="true"></i><div><strong>Secure &amp; monitored</strong><span>CCTV and controlled entry</span></div></div>
                </div>
            </div>
        </div>
    </section>

    @if(!empty($loc['items']))
    <!-- ============ WHAT WE STORE (service pages) ============ -->
    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">What We Store</span>
                <h2>{{ $loc['itemsTitle'] }}</h2>
                <p>{{ $loc['itemsIntro'] }}</p>
            </div>
            <div class="loc-items sk-reveal">
                @foreach($loc['items'] as [$icon, $title, $text])
                    <div class="loc-item">
                        <span class="ic"><i class="fas {{ $icon }}" aria-hidden="true"></i></span>
                        <div><span class="h4">{{ $title }}</span><p>{{ $text }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ============ SERVICES IN THIS CITY ============ -->
    <section class="sk-section {{ empty($loc['items']) ? 'sk-section--soft' : '' }}">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">{{ $loc['servicesEyebrow'] }}</span>
                <h2>{{ $loc['servicesTitle'] }}</h2>
                <p>{{ $loc['servicesIntro'] }}</p>
            </div>
            <div class="loc-services sk-reveal">
                @foreach($loc['services'] as [$slug, $label, $icon, $text])
                    <a href="{{ url('/' . $slug) }}" class="loc-svc">
                        <span class="ic"><i class="fas {{ $icon }}" aria-hidden="true"></i></span>
                        <span class="h4">{{ $label }}</span>
                        <span class="txt">{{ $text }}</span>
                        <span class="more">Learn more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============ MAP + GETTING HERE ============ -->
    <section class="sk-section {{ empty($loc['items']) ? '' : 'sk-section--soft' }}" id="loc-map">
        <div class="sk-container">
            <div class="loc-visit sk-reveal">
                <div class="loc-visit-map">
                    <iframe
                        title="{{ $loc['facilityName'] }} on Google Maps"
                        src="{{ $loc['mapEmbed'] }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="loc-visit-info">
                    <span class="sk-eyebrow">Visit Us</span>
                    <h2>{{ $loc['visitTitle'] }}</h2>
                    <p>{{ $loc['visitText'] }}</p>
                    <ul class="loc-visit-list">
                        <li><span class="ic"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><div><strong>Address</strong><span>{!! $loc['addressHtml'] !!}</span></div></li>
                        <li><span class="ic"><i class="fas fa-road" aria-hidden="true"></i></span><div><strong>Getting here</strong><span>{{ $loc['gettingHere'] }}</span></div></li>
                        <li><span class="ic"><i class="fas fa-clock" aria-hidden="true"></i></span><div><strong>Access</strong><span>24/7 for customers. Call ahead for viewings and first drop-offs.</span></div></li>
                    </ul>
                    <div class="loc-card-actions">
                        <a href="{{ $loc['directions'] }}" target="_blank" rel="noopener" class="sk-btn sk-btn-primary"><i class="fas fa-directions" aria-hidden="true"></i> Get Directions</a>
                        <a href="https://wa.me/971565018785" class="sk-btn sk-btn-outline"><i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ HOW IT WORKS ============ -->
    <section class="sk-section lt-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">How It Works</span>
                <h2>{{ $loc['h1'] }} in Four Simple Steps</h2>
            </div>
            <ol class="loc-steps sk-reveal">
                <li><span class="n">1</span><span class="h4">Tell us what you store</span><p>Share your items and dates by form, phone or WhatsApp.</p></li>
                <li><span class="n">2</span><span class="h4">Get the right space</span><p>We recommend a size and send a clear quote, with no long lease.</p></li>
                <li><span class="n">3</span><span class="h4">Drop off or we collect</span><p>Bring items yourself or let our <a href="{{ url('/moving-services') }}">moving team</a> collect them.</p></li>
                <li><span class="n">4</span><span class="h4">Access any time</span><p>Visit your space 24/7 and resize it as your needs change.</p></li>
            </ol>
        </div>
    </section>

    <!-- ============ WHY US ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Why StorageKeys</span>
                <h2>{{ $loc['whyTitle'] }}</h2>
            </div>
            <div class="loc-why sk-reveal">
                @foreach($loc['why'] as [$icon, $title, $text])
                    <div class="loc-why-i">
                        <span class="ic"><i class="fas {{ $icon }}" aria-hidden="true"></i></span>
                        <span class="h4">{{ $title }}</span>
                        <p>{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============ AREAS ============ -->
    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Areas We Serve</span>
                <h2>{{ $loc['areasTitle'] }}</h2>
                <p>We collect from and deliver to homes and offices across these areas and beyond.</p>
            </div>
            <div class="loc-areas sk-reveal">
                @foreach($loc['areas'] as $group => $places)
                    <div class="loc-area">
                        <span class="h4"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $group }}</span>
                        <div class="loc-chips">
                            @foreach($places as $place)
                                <span>{{ $place }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============ BOTH CITIES ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Our Locations</span>
                <h2>{{ $loc['compareTitle'] }}</h2>
                <p>{{ $loc['compareIntro'] }}</p>
            </div>
            <div class="loc-compare sk-reveal">
                @foreach($loc['compare'] as $item)
                    @php $isHere = $item['slug'] === $loc['slug']; @endphp
                    <div class="loc-cmp {{ $isHere ? 'is-here' : '' }}">
                        <div class="loc-cmp-top">
                            <span class="loc-card-badge"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $item['city'] }}</span>
                            @if($isHere)
                                <span class="loc-cmp-here">You are here</span>
                            @endif
                        </div>
                        <span class="h3">{{ $item['title'] }}</span>
                        <p>{{ $item['text'] }}</p>
                        @if($isHere)
                            <a href="#ps-quote" class="sk-btn sk-btn-primary">Get a Quote for {{ $item['city'] }}</a>
                        @else
                            <a href="{{ url('/' . $item['slug']) }}" class="sk-btn sk-btn-outline">{{ $item['cta'] }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('ui.partials.explore-more', ['page' => $loc['slug']])

    <!-- ============ FAQ ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="lt-faq">
                @foreach($loc['faqs'] as $i => [$q, $a])
                    <details {{ $i === 0 ? 'open' : '' }}>
                        <summary>{{ $q }}</summary>
                        <div class="a">{{ $a }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============ QUOTE ============ -->
    <section class="sk-section lt-quote-wrap" id="ps-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">Free Quote</span>
                    <h2>Need {{ $loc['h1'] }}?</h2>
                    <p>{{ $loc['quoteText'] }}</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'source' => $loc['slug'],
                    'variant' => 'simple',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'defaultStorage' => $loc['storageType'],
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
