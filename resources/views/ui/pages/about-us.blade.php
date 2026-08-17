@extends('ui.layouts.frontend')
@section('title', '| About-Us')
@section('metaTitle', 'About Us | Storage Keys')
@section('metaDescription', 'We began operations as an LLC in the UAE in 2016. Premium moving and storage services in Sharjah at reduced cost.')
@section('content')

<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <span>About Us</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">About Us</span>
            <h1>Solutions For <span>your needs!</span></h1>
            <p class="lead">We began operations as an LLC. in the UAE in 2016. Our founders wanted to eliminate the sweat, cost, and hassle from the experience of moving and storage in UAE to make it feel like a breeze. They set out to build a storage company in Sharjah that offers premium services to clients at reduced cost.</p>
            <div class="ps-hero-cta">
                <a href="#ab-quote" class="sk-btn sk-btn-primary"><i class="fas fa-file-invoice-dollar"></i> Get A Quote</a>
                <a href="{{ url('booking') }}" class="sk-btn sk-btn-ghost"><i class="fas fa-boxes"></i> Looking for a secure storage?</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-temperature-low"></i> Climate-controlled</span>
                <span class="ps-hbadge"><i class="fas fa-clock"></i> 24/7 access</span>
                <span class="ps-hbadge"><i class="fas fa-tags"></i> Highly competitive rates</span>
                <span class="ps-hbadge"><i class="fas fa-shield-alt"></i> Automatic insurance coverage</span>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="ps-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-tags"></i> Highly competitive rates</div>
                <div class="ps-trust-i"><i class="fas fa-temperature-low"></i> Climate-controlled</div>
                <div class="ps-trust-i"><i class="fas fa-clock"></i> 24/7 access</div>
                <div class="ps-trust-i"><i class="fas fa-video"></i> IP security</div>
                <div class="ps-trust-i"><i class="fas fa-expand-arrows-alt"></i> Flexible lease options</div>
            </div>
        </div>
    </div>

    <!-- ============ ABOUT (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">About Us</span>
                    <h2>Solutions For your needs!</h2>
                    <p>We began operations as an LLC. in the UAE in 2016. Our founders wanted to eliminate the sweat, cost, and hassle from the experience of moving and storage in UAE to make it feel like a breeze. They set out to build a storage company in Sharjah that offers premium services to clients at reduced cost.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Highly competitive rates</li>
                        <li><i class="fas fa-check-circle"></i> Climate-controlled and spotless individual storage units</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 access to your pod</li>
                        <li><i class="fas fa-check-circle"></i> Accessible UAE warehouse via Emirates Bypass Road</li>
                        <li><i class="fas fa-check-circle"></i> State-of-the-art IP security and surveillance by fully-vetted professionals</li>
                        <li><i class="fas fa-check-circle"></i> Automatic and comprehensive insurance coverage</li>
                        <li><i class="fas fa-check-circle"></i> Flexible lease options and easily scalable storage solutions</li>
                        <li><i class="fas fa-check-circle"></i> Wide loading/unloading bays to accommodate items of all sizes</li>
                    </ul>
                </div>
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_7.png') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ OUR SERVICES ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Our Services</span>
                <h2>Storage Solution</h2>
            </div>
            <div class="ab-services sk-reveal">
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-home"></i></div>
                    <h4>Personal Storage</h4>
                    <p>over 1 million+ homes for sale available on the website, we can match you with a house you will want to call home.</p>
                    <a class="ab-svc-link" href="{{ url('/personal-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <h4>Business Storage</h4>
                    <p>over 1 million+ homes for sale available on the website, we can match you with a house you will want to call home.</p>
                    <a class="ab-svc-link" href="{{ url('/business-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-warehouse"></i></div>
                    <h4>Warehouse Storage</h4>
                    <p>over 1 million+ homes for sale available on the website, we can match you with a house you will want to call home.</p>
                    <a class="ab-svc-link" href="{{ url('/warehouse-storage') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ps-whyc">
                    <div class="ic"><i class="fas fa-truck"></i></div>
                    <h4>Moving</h4>
                    <p>over 1 million+ homes for sale available on the website, we can match you with a house you will want to call home.</p>
                    <a class="ab-svc-link" href="{{ url('/moving-services') }}">Service Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ TESTIMONIALS ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Client,s Testimonial</span>
                <h2>Client's Feedback</h2>
            </div>
            <div class="ab-reviews sk-reveal">
                <article class="sk-review">
                    <div class="qi">“</div>
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p>Best place to store your commercial and household items. Climate controlled environment, CCTV camera and above of all you can access your storage space 24/7 with nice and welcoming staff.</p>
                    <div class="who">
                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/1.jpg') }}" alt="Nadeem Baig">
                        <div>
                            <h4>Nadeem Baig</h4>
                            <span>Local Guide</span>
                        </div>
                    </div>
                </article>
                <article class="sk-review">
                    <div class="qi">“</div>
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p>I have been storing my business goods with them for over 6 months now. The staff at the location as well as the admin staffs are very friendly. The facility is well maintained and the location is great.</p>
                    <div class="who">
                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/2.jpg') }}" alt="Ashik S">
                        <div>
                            <h4>Ashik S</h4>
                            <span>Local Guide</span>
                        </div>
                    </div>
                </article>
                <article class="sk-review">
                    <div class="qi">“</div>
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p>The arrangement of the storage rooms is wonderful and clean, and the place is equipped with public safety means, in addition to observing the implementation of sterilization and spraying of pesticides as a proactive protection against insects.</p>
                    <div class="who">
                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/3.jpg') }}" alt="Muhammed Alshebli">
                        <div>
                            <h4>Muhammed Alshebli</h4>
                            <span>Local Guide</span>
                        </div>
                    </div>
                </article>
                <article class="sk-review">
                    <div class="qi">“</div>
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p>We took the warehouse for a long time and their services are very good, fast and very friendly yet professional staff. Thank you</p>
                    <div class="who">
                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/4.jpg') }}" alt="pooja nayal">
                        <div>
                            <h4>pooja nayal</h4>
                            <span>Local Guide</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="ab-quote">
        <div class="sk-container">
            <div class="svc-quote so-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Looking for a secure storage?</h2>
                    <p>Since 2016, StorageKeys has helped households and businesses across the UAE store their belongings with less cost, less hassle and more peace of mind. Tell us what you need to store and we will recommend a practical option from our Sharjah facility.</p>
                    <p>Whether you need extra space at home, room for business inventory, or help moving, our team can put together a storage solution that fits your needs.</p>
                    <ul class="so-quote-points">
                        <li><i class="fas fa-check"></i> Climate-controlled, individually lockable units</li>
                        <li><i class="fas fa-check"></i> 24/7 access with IP security and CCTV</li>
                        <li><i class="fas fa-check"></i> Flexible leases and competitive rates</li>
                        <li><i class="fas fa-check"></i> Automatic insurance coverage included</li>
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
                    'source' => 'about-us',
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ab-quote"><i class="fas fa-box-open"></i> Quote</a>
    </div>

</div>
@endsection
