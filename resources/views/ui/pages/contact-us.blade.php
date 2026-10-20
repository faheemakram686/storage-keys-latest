@extends('ui.layouts.frontend')
@section('title', '| Contact-Us')
@section('metaTitle', 'Contact Us | Storage Keys')
@section('metaDescription', 'Contact StorageKeys in Sharjah for personal, business, warehouse, luggage and car storage across Dubai and the UAE. Call, email or request a free quote.')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <span>Contact Us</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Contact Us</span>
            <h1>Get in touch with <span>StorageKeys</span></h1>
            <p class="lead">Need extra space, a moving quote or help choosing the right storage option? Our team in Sharjah is ready to help households and businesses across Dubai and the UAE.</p>
            <div class="ps-hero-cta">
                <a href="#ct-quote" class="sk-btn sk-btn-primary"><i class="fas fa-file-invoice-dollar"></i> Get A Quote</a>
                <a href="tel:+971565018785" class="sk-btn sk-btn-ghost"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                <a href="tel:8005397" class="sk-btn sk-btn-ghost"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-phone"></i> Call or WhatsApp</span>
                <span class="ps-hbadge"><i class="fas fa-envelope"></i> Email our team</span>
                <span class="ps-hbadge"><i class="fas fa-map-marker-alt"></i> Sharjah facility</span>
                <span class="ps-hbadge"><i class="fas fa-clock"></i> 24/7 access</span>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="ps-trust ct-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-phone"></i> +971 56 501 8785</div>
                <div class="ps-trust-i"><i class="fas fa-phone"></i> Toll Free: 800 5397</div>
                <div class="ps-trust-i"><i class="fas fa-envelope"></i> info@storagekeys.com</div>
                <div class="ps-trust-i"><i class="fab fa-whatsapp"></i> WhatsApp support</div>
                <div class="ps-trust-i"><i class="fas fa-map-marker-alt"></i> Sharjah, UAE</div>
            </div>
        </div>
    </div>

    <!-- ============ CONTACT CARDS ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Reach Us</span>
                <h2>How to Contact StorageKeys</h2>
                <p>Call, email or visit our facility. We can help you find a practical storage solution for personal items, business inventory, luggage or vehicles.</p>
            </div>
            <div class="ct-cards sk-reveal">
                <a class="ps-whyc ct-card" href="mailto:info@storagekeys.com">
                    <div class="ic"><i class="fas fa-envelope"></i></div>
                    <span class="h4">Email Address</span>
                    <p>info@storagekeys.com</p>
                    <span class="ab-svc-link">Send an email <i class="fas fa-arrow-right"></i></span>
                </a>
                <div class="ps-whyc ct-card">
                    <div class="ic"><i class="fas fa-phone"></i></div>
                    <span class="h4">Phone Number</span>
                    <p><a href="tel:+971565018785">+971 56 501 8785</a></p>
                    <p>Toll Free: <a href="tel:8005397">800 5397</a></p>
                    <a class="ab-svc-link" href="tel:+971565018785">Call our team <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc ct-card">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <span class="h4">Office Address</span>
                    <p>Storage Keys, Plot # 4202 — Sharjah — Dubai — United Arab Emirates</p>
                    <a class="ab-svc-link" href="#ct-map">View on map <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="ct-quote" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="svc-quote so-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Send us a message</h2>
                    <p>Tell us what you need to store and how we can help. Our team will get back to you with a suitable storage option for your home, business or move across Dubai, Sharjah and the UAE.</p>
                    <p>You can also call or message us directly if you would rather speak with someone first.</p>
                    <ul class="so-quote-points">
                        <li><i class="fas fa-check"></i> Personal, business and warehouse storage</li>
                        <li><i class="fas fa-check"></i> Climate-controlled units</li>
                        <li><i class="fas fa-check"></i> Luggage storage and car storage</li>
                        <li><i class="fas fa-check"></i> Moving and packing support</li>
                    </ul>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:info@storagekeys.com"><i class="fas fa-envelope"></i> info@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'variant' => 'simple',
                    'compact' => true,
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Get A Quote',
                    'subtitle' => 'Share a few details and we will get back to you shortly.',
                    'submitLabel' => 'Send Message',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'source' => 'contact-us',
                ])
            </div>
        </div>
    </section>

    <!-- ============ LOCATIONS ============ -->
    <section class="sk-section sk-locs" id="ct-map">
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

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ct-quote"><i class="fas fa-envelope"></i> Quote</a>
    </div>

</div>
@endsection
