@extends('ui.layouts.frontend')
@section('title', '| Luggage Storage')
@section('metaTitle', 'Luggage Storage in Dubai & Abu Dhabi, UAE | Storagekeys')
@section('metaDescription', 'Store your luggage safely between flights, hotel stays, moves or travel plans with flexible luggage storage solutions in Dubai and Abu Dhabi.')

@section('content')
<div class="sk-home">
    <section class="svc-hero">
        <div class="sk-container">
            <div class="svc-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Luggage Storage</span></div>
            <div class="svc-hero-grid">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Luggage Storage</span>
                    <h1>Luggage Storage in <span>Dubai &amp; Abu Dhabi, UAE</span></h1>
                    <p class="lead">Store your luggage safely between flights, hotel stays, moves or travel plans with flexible luggage storage solutions in Dubai and Abu Dhabi. Keep your bags secure without carrying them around all day.</p>
                    <div class="svc-hero-cta">
                        <a href="#luggage-quote" class="sk-btn sk-btn-primary"><i class="fas fa-file-invoice-dollar"></i> Get a Free Quote</a>
                        <a href="tel:+971565018785" class="sk-btn sk-btn-ghost"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="tel:8005397" class="sk-btn sk-btn-ghost"><i class="fas fa-phone-alt"></i> Toll Free: 800 5397</a>
                    </div>
                </div>
                <div class="svc-hero-card">
                    <h3>Convenient luggage storage</h3>
                    <ul>
                        <li><i class="fas fa-hotel"></i> Store luggage after hotel checkout</li>
                        <li><i class="fas fa-door-open"></i> Keep bags before hotel check-in</li>
                        <li><i class="fas fa-plane-departure"></i> Store belongings between connecting flights</li>
                        <li><i class="fas fa-city"></i> Leave luggage while exploring the city</li>
                        <li><i class="fas fa-calendar-alt"></i> Short-term and long-term options</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="svc-trust">
        <div class="sk-container">
            <div class="svc-trust-in">
                <div class="svc-trust-i"><i class="fas fa-suitcase-rolling"></i> Suitcases & travel bags</div>
                <div class="svc-trust-i"><i class="fas fa-backpack"></i> Backpacks & duffel bags</div>
                <div class="svc-trust-i"><i class="fas fa-box-open"></i> Boxes & packed belongings</div>
                <div class="svc-trust-i"><i class="fas fa-shield-alt"></i> Secure arrangements</div>
                <div class="svc-trust-i"><i class="fas fa-clock"></i> Flexible periods</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="svc-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Convenient Luggage Storage for Travelers and Visitors</h2>
                    <p>Whether you have arrived early for a flight, checked out of your hotel, or have several hours before your next destination, carrying heavy bags can make your day more difficult. Our luggage storage service gives you a practical place to keep your belongings while you continue with your plans.</p>
                    <p>StorageKeys provides flexible options for short-term and longer storage requirements. From suitcases and travel bags to boxes and other personal belongings, your items can be stored in a dedicated luggage storage facility until you are ready to collect them.</p>
                    <h3>When Do You Need Luggage Storage?</h3>
                    <p>Luggage storage can be useful when your travel schedule does not match your accommodation or transportation arrangements. Our service can help when you need to:</p>
                    <div class="svc-pills">
                        <span>Store luggage after hotel checkout</span><span>Keep bags before hotel check-in</span><span>Store belongings between connecting flights</span><span>Leave luggage while exploring the city</span><span>Keep bags during a temporary stay</span><span>Store belongings during a move or relocation</span><span>Arrange longer-term storage while travelling</span>
                    </div>
                </div>
                <div class="svc-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="svc-vs sk-reveal">
                <div class="svc-vs-card good">
                    <h3><span class="dot"><i class="fas fa-check"></i></span> Luggage Storage in Dubai</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Convenient alternative to carrying bags throughout the city</li>
                        <li><i class="fas fa-check"></i> Storage option based on quantity and duration</li>
                        <li><i class="fas fa-check"></i> Suitable for short stays and extended travel arrangements</li>
                        <li><i class="fas fa-check"></i> Practical long term luggage storage Dubai solution</li>
                    </ul>
                </div>
                <div class="svc-vs-mid">&amp;</div>
                <div class="svc-vs-card good">
                    <h3><span class="dot"><i class="fas fa-check"></i></span> Abu Dhabi Luggage Storage</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Store belongings between accommodation and transport</li>
                        <li><i class="fas fa-check"></i> Short period and longer stay options</li>
                        <li><i class="fas fa-check"></i> Option based on your requirements</li>
                        <li><i class="fas fa-check"></i> Avoid carrying bags throughout your trip</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Storage Types</span>
                <h2>What Can You Store?</h2>
                <p>Our luggage and baggage storage options can accommodate common travel belongings and personal items, subject to storage guidelines.</p>
            </div>
            <div class="svc-cards sk-reveal">
                <div class="svc-card"><div class="ic"><i class="fas fa-suitcase-rolling"></i></div><div><h3>Suitcases and travel bags</h3></div></div>
                <div class="svc-card"><div class="ic"><i class="fas fa-backpack"></i></div><div><h3>Backpacks and duffel bags</h3></div></div>
                <div class="svc-card"><div class="ic"><i class="fas fa-box-open"></i></div><div><h3>Boxes and packed belongings</h3></div></div>
                <div class="svc-card"><div class="ic"><i class="fas fa-tshirt"></i></div><div><h3>Clothing and personal effects</h3></div></div>
                <div class="svc-card"><div class="ic"><i class="fas fa-hiking"></i></div><div><h3>Travel equipment</h3></div></div>
                <div class="svc-card"><div class="ic"><i class="fas fa-check-circle"></i></div><div><h3>Other permitted personal items</h3></div></div>
            </div>
            <p class="svc-vs-note">If you have larger quantities of luggage or belongings that go beyond typical travel bags, we can help identify a more suitable storage option.</p>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="svc-split rev sk-reveal">
                <div class="svc-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Flexible Baggage Storage</span>
                    <h2>Flexible Baggage Storage for Short or Extended Stays</h2>
                    <p>Not every traveler needs storage for the same amount of time. Some people only need a place for their bags for a few hours, while others may require storage for several weeks or months.</p>
                    <p>Our baggage storage solutions are designed to provide flexibility around your travel schedule. You can discuss your luggage quantity, storage period and collection requirements with our team before arranging your storage.</p>
                    <h3>Is Free Luggage Storage Available?</h3>
                    <p>If you are specifically searching for free luggage storage, availability depends on the service provider and storage arrangement. StorageKeys offers paid storage solutions designed around your luggage and storage requirements rather than relying on limited complimentary storage options.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section svc-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Why StorageKeys</span>
                <h2>Why Choose StorageKeys for Luggage Storage?</h2>
                <p class="svc-feat-lead">StorageKeys provides more than a place to leave your bags. Our storage solutions are designed for customers who want a straightforward way to keep their belongings stored while they travel, relocate or manage a temporary gap between plans.</p>
            </div>
            <div class="svc-feat-grid sk-reveal">
                <div class="svc-feat-i"><i class="fas fa-clock"></i><span class="h4">Flexible storage periods</span></div>
                <div class="svc-feat-i"><i class="fas fa-layer-group"></i><span class="h4">Suitable space for different luggage quantities</span></div>
                <div class="svc-feat-i"><i class="fas fa-shield-alt"></i><span class="h4">Secure storage arrangements</span></div>
                <div class="svc-feat-i"><i class="fas fa-hand-holding"></i><span class="h4">Convenient collection when required</span></div>
                <div class="svc-feat-i"><i class="fas fa-calendar-check"></i><span class="h4">Options for short-term and long-term needs</span></div>
                <div class="svc-feat-i"><i class="fas fa-users"></i><span class="h4">Support from a professional storage team</span></div>
            </div>
            <p class="svc-vs-note">Whether you need baggage storage Dubai options for a short trip or a longer storage arrangement, we can help you find a suitable solution.</p>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>Luggage Storage FAQs</h2>
            </div>
            <div class="svc-faq">
                <details open><summary>How long can I store my luggage?</summary><div class="a">Storage periods depend on your requirements. We can accommodate short-term stays as well as longer storage arrangements.</div></details>
                <details><summary>Can I store multiple suitcases?</summary><div class="a">Yes. You can store multiple suitcases or bags, subject to available space and storage requirements.</div></details>
                <details><summary>Do you offer long-term luggage storage?</summary><div class="a">Yes. StorageKeys can provide longer-term options for customers who need to store luggage and personal belongings for extended periods.</div></details>
                <details><summary>Can I store luggage during a hotel stay?</summary><div class="a">Yes. Luggage storage can be useful when you need to keep bags before check-in, after checkout or during a temporary gap.</div></details>
                <details><summary>Do you provide luggage collection?</summary><div class="a">Collection options depend on your requirements and the agreed storage arrangement. Contact our team to discuss your needs.</div></details>
            </div>
        </div>
    </section>

    <section class="sk-section" id="luggage-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Store Your Luggage Without the Hassle</h2>
                    <p>Need a place for your bags while you travel, move or wait between plans? StorageKeys offers flexible luggage storage solutions across the UAE.</p>
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
                    'source' => 'luggage-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Luggage Storage',
                    'storingOptions' => [
                        'Suitcases and travel bags' => 'Suitcases and travel bags',
                        'Backpacks and duffel bags' => 'Backpacks and duffel bags',
                        'Boxes and packed belongings' => 'Boxes and packed belongings',
                        'Clothing and personal effects' => 'Clothing and personal effects',
                        'Travel equipment' => 'Travel equipment',
                        'Other permitted personal items' => 'Other permitted personal items',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="svc-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#luggage-quote"><i class="fas fa-file-invoice-dollar"></i> Quote</a>
    </div>
</div>
@endsection
