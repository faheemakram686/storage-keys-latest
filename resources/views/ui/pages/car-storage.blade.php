@extends('ui.layouts.frontend')
@section('title', '| Car Storage')
@section('metaTitle', 'Car Storage in Dubai & UAE | Storagekeys')
@section('metaDescription', 'Store your vehicle in a secure, professionally managed facility with flexible short-term and long-term car storage in Dubai and across the UAE.')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="svc-hero">
        <div class="sk-container">
            <div class="svc-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Car Storage</span></div>
            <div class="svc-hero-grid">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Car Storage</span>
                    <h1>Car Storage in <span>Dubai &amp; UAE</span></h1>
                    <p class="lead">Store your vehicle in a secure, professionally managed facility with flexible options for short-term and long-term needs. StorageKeys provides practical car storage solutions for personal vehicles, classic cars, luxury vehicles and extra vehicles that need a suitable place between uses.</p>
                    <div class="svc-hero-cta">
                        <a href="#car-quote" class="sk-btn sk-btn-primary"><i class="fas fa-file-invoice-dollar"></i> Get a Free Quote</a>
                        <a href="tel:+971565018785" class="sk-btn sk-btn-ghost"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="tel:8005397" class="sk-btn sk-btn-ghost"><i class="fas fa-phone-alt"></i> Toll Free: 800 5397</a>
                    </div>
                </div>
                <div class="svc-hero-card">
                    <h3>Practical vehicle storage</h3>
                    <ul>
                        <li><i class="fas fa-car"></i> Personal vehicles</li>
                        <li><i class="fas fa-car-side"></i> Classic cars</li>
                        <li><i class="fas fa-gem"></i> Luxury vehicles</li>
                        <li><i class="fas fa-parking"></i> Extra vehicles between uses</li>
                        <li><i class="fas fa-calendar-alt"></i> Short-term and long-term options</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ TRUST STRIP ============ -->
    <div class="svc-trust">
        <div class="sk-container">
            <div class="svc-trust-in">
                <div class="svc-trust-i"><i class="fas fa-shield-alt"></i> Professionally managed</div>
                <div class="svc-trust-i"><i class="fas fa-clock"></i> Short-term &amp; long-term</div>
                <div class="svc-trust-i"><i class="fas fa-car"></i> Personal vehicles</div>
                <div class="svc-trust-i"><i class="fas fa-gem"></i> Luxury &amp; classic</div>
                <div class="svc-trust-i"><i class="fas fa-map-marker-alt"></i> Dubai &amp; UAE</div>
            </div>
        </div>
    </div>

    <!-- ============ OVERVIEW ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="svc-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Reliable Car Storage for Different Vehicle Needs</h2>
                    <p>When a vehicle is not being used regularly, leaving it parked outside or in an unsuitable space can create unnecessary concerns. A dedicated car storage facility provides a more practical alternative, giving you a designated space while keeping your vehicle away from everyday parking limitations.</p>
                    <p>Our car storage service is suitable for vehicle owners who need additional space for a second car, are travelling for an extended period, are between properties, or simply want a suitable place to keep a vehicle that is not in regular use.</p>
                    <p>Whether you need long term car storage or a temporary arrangement, we can help you find a storage option suited to your vehicle and requirements.</p>
                    <div class="svc-pills">
                        <span>Second car</span><span>Extended travel</span><span>Between properties</span><span>Occasional-use vehicles</span><span>Long-term storage</span>
                    </div>
                </div>
                <div class="svc-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_5.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ OPTIONS ============ -->
    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Storage Options</span>
                <h2>Car Storage Options for Personal and Special Vehicles</h2>
                <p>Different vehicles can have different storage requirements. StorageKeys offers flexible car storage solutions for a range of personal and specialist vehicles.</p>
            </div>
            <div class="svc-cards sk-reveal">
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-warehouse"></i></div>
                    <div>
                        <h3>Car Self Storage</h3>
                        <p>Car self storage provides a dedicated space for your vehicle without requiring you to maintain additional parking space at home or at another property. It can be useful when you have extra car storage needs or want to keep a vehicle separate from your everyday parking arrangements.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-gem"></i></div>
                    <div>
                        <h3>Luxury and Classic Car Storage</h3>
                        <p>Special vehicles deserve appropriate storage conditions. Our luxury car storage Dubai options are designed for owners who want a more suitable environment for valuable vehicles when they are not in regular use.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-car-side"></i></div>
                    <div>
                        <h3>Classic car storage facility</h3>
                        <p>We also accommodate requirements for classic car storage, helping owners keep their vehicles in a dedicated storage environment rather than leaving them exposed to everyday outdoor conditions. For owners looking for a classic car storage facility, choosing a professionally managed space can provide greater convenience and peace of mind.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-th-large"></i></div>
                    <div>
                        <h3>Car Storage Units and Facility Space</h3>
                        <p>Our car storage units are intended to provide dedicated space for vehicles while making better use of available storage capacity. The right arrangement depends on the vehicle, the expected storage period and the level of protection required.</p>
                    </div>
                </div>
            </div>
            <p class="svc-vs-note text-center">A car storage facility can be particularly useful for people who do not have sufficient parking at home, are relocating, or need somewhere to keep a vehicle while it is temporarily not in use. For customers comparing car storage rental options, we focus on practical arrangements rather than requiring unnecessary long-term commitments.</p>
        </div>
    </section>

    <!-- ============ DUBAI & UAE ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="svc-split rev sk-reveal">
                <div class="svc-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Locations</span>
                    <h2>Car Storage in Dubai and Across the UAE</h2>
                    <p>If you are searching for car storage Dubai, StorageKeys provides storage solutions designed around the requirements of vehicle owners in the UAE. Our facilities offer an alternative to keeping unused vehicles in residential parking areas or exposed spaces.</p>
                    <p>Customers looking for car storage Abu Dhabi can also contact our team to discuss their requirements and available options across the UAE.</p>
                    <p>The suitable storage arrangement can depend on the size and type of vehicle, how long it needs to be stored and whether additional services are required. Our team can help you identify the most appropriate option before your vehicle is placed into storage.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section svc-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Why StorageKeys</span>
                <h2>Why Choose StorageKeys for Car Storage?</h2>
                <p class="svc-feat-lead">Choosing a dedicated storage facility gives you a practical way to manage a vehicle that does not need to be on the road every day. StorageKeys provides flexible storage arrangements for different vehicle requirements, with a focus on convenience, accessibility and professional facility management.</p>
            </div>
            <div class="svc-feat-grid sk-reveal">
                <div class="svc-feat-i"><i class="fas fa-parking"></i><h4>Need additional parking or extra car storage</h4></div>
                <div class="svc-feat-i"><i class="fas fa-plane"></i><h4>Are travelling or relocating for an extended period</h4></div>
                <div class="svc-feat-i"><i class="fas fa-calendar-check"></i><h4>Need long term car storage</h4></div>
                <div class="svc-feat-i"><i class="fas fa-car-side"></i><h4>Own a classic, luxury or occasional-use vehicle</h4></div>
                <div class="svc-feat-i"><i class="fas fa-home"></i><h4>Need temporary space between properties</h4></div>
                <div class="svc-feat-i"><i class="fas fa-map-marker-alt"></i><h4>Want to keep a vehicle separate from your regular parking area</h4></div>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="car-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Store Your Car With StorageKeys</h2>
                    <p>Whether you need short-term space or a longer-term arrangement, StorageKeys can help you find a practical car storage option for your vehicle. Contact our team to discuss your vehicle, storage period and requirements and find the right storage solution for you.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone-alt"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'variant' => 'business',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'source' => 'car-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Car Storage',
                    'storingOptions' => [
                        'Personal vehicle' => 'Personal vehicle',
                        'Second / extra car' => 'Second / extra car',
                        'Luxury car storage' => 'Luxury car storage',
                        'Classic car storage' => 'Classic car storage',
                        'Long-term car storage' => 'Long-term car storage',
                        'Temporary / between properties' => 'Temporary / between properties',
                    ],
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE ACTION BAR ============ -->
    <div class="svc-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#car-quote"><i class="fas fa-file-invoice-dollar"></i> Quote</a>
    </div>

</div>
@endsection
