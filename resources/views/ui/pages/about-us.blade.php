@extends('ui.layouts.frontend')
@section('title', '| About-Us')
@section('metaTitle', 'About Us | Storage Keys')
@section('metaDescription', 'We began operations as an LLC in the UAE in 2016. Premium moving and storage services in Sharjah at reduced cost.')
@section('content')

<div class="sk-home ab-page">

    <section class="ps-hero ab-hero">
        <div class="sk-container">
            <h1><i class="fas fa-infinity" aria-hidden="true"></i> Solutions for your needs</h1>
            <p class="lead">We began operations as an LLC. in the UAE in 2016. Our founders wanted to eliminate the sweat, cost, and hassle from the experience of moving and storage in UAE to make it feel like a breeze. They set out to build a storage company in Sharjah that offers premium services to clients at reduced cost.</p>
        </div>
    </section>

    <section class="ab-stats">
        <div class="sk-container">
            <div class="ab-stats-grid">
                <div><div class="n">10+</div><div class="l">Years of Experience</div></div>
                <div><div class="n">5,000+</div><div class="l">Happy Customers</div></div>
                <div><div class="n">100,000+</div><div class="l">Sq. Ft. of Storage</div></div>
                <div><div class="n">7</div><div class="l">Emirates Covered</div></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="ab-provide">
                <div>
                    <h2>What we provide</h2>
                    <p>Our units cover a range of your necessities so we have you covered on all your needs.</p>
                    <ul class="ab-checks">
                        <li><i class="fas fa-check"></i> Highly competitive rates</li>
                        <li><i class="fas fa-check"></i> State-of-the-art IP security and surveillance by fully-vetted professionals</li>
                        <li><i class="fas fa-check"></i> Climate-controlled and spotless individual storage units</li>
                        <li><i class="fas fa-check"></i> Automatic and comprehensive insurance coverage</li>
                        <li><i class="fas fa-check"></i> 24/7 access to your pod</li>
                        <li><i class="fas fa-check"></i> Flexible lease options and easily scalable storage solutions</li>
                        <li><i class="fas fa-check"></i> Accessible UAE warehouse via Emirates Bypass Road</li>
                        <li><i class="fas fa-check"></i> Wide loading/unloading bays to accommodate items of all sizes</li>
                    </ul>
                </div>
                <div class="ab-photo" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_7.png') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ab-head">
                <h2>Our solutions for you</h2>
                <p>We organise our services around how people and businesses actually use extra space, making it easy to find the option that matches your situation.</p>
            </div>
            <div class="ab-solutions">
                <article class="ab-sol">
                    <div class="ab-sol-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/personal-storage-80kb.jpg') }}');"></div>
                    <div class="ab-sol-body">
                        <h3>Personal Storage</h3>
                        <p>Ideal for furniture, documents, luggage and seasonal items. Perfect for short-term or long-term household needs at affordable rates.</p>
                        <a href="{{ url('/personal-storage') }}">Explore personal storage <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <article class="ab-sol">
                    <div class="ab-sol-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/Business-storage-80kb.jpg') }}');"></div>
                    <div class="ab-sol-body">
                        <h3>Business Storage</h3>
                        <p>Designed for retailers, distributors and e-commerce businesses that need organised inventory solutions and regular access to their goods.</p>
                        <a href="{{ url('/business-storage') }}">Explore business storage <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <article class="ab-sol">
                    <div class="ab-sol-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/warehouse-storage-80kb.jpg') }}');"></div>
                    <div class="ab-sol-body">
                        <h3>Warehouse Storage</h3>
                        <p>Flexible warehouse space for bulk inventory, equipment and commercial stock with scalable rental terms and easy access.</p>
                        <a href="{{ url('/warehouse-storage') }}">Explore warehouse storage <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
                <article class="ab-sol">
                    <div class="ab-sol-img" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/landing-page/Moving-Storage-80kb.jpg') }}');"></div>
                    <div class="ab-sol-body">
                        <h3>Moving Storage</h3>
                        <p>A convenient moving and temporary storage solution that helps simplify relocations for both households and businesses.</p>
                        <a href="{{ url('/moving-services') }}">Explore moving storage <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="sk-section ab-said">
        <div class="sk-container">
            <div class="ab-head">
                <h2>What our customers have said about us</h2>
            </div>
            <div class="ab-said-grid">
                <article class="sk-review">
                    <div class="qi">“</div>
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p>Best place to store your commercial and household items. Climate controlled environment, CCTV camera and above of all you can access your storage space 24/7 with nice and welcoming staff.</p>
                    <div class="who">
                        <img src="{{ asset('sk-assets/assets/images/frontend/testimonial/1.jpg') }}" alt="Nadeem Baig">
                        <div>
                            <span class="h4">Nadeem Baig</span>
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
                            <span class="h4">Ashik S</span>
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
                            <span class="h4">Muhammed Alshebli</span>
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
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
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
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ab-quote"><i class="fas fa-box-open"></i> Quote</a>
    </div>

</div>
@endsection
