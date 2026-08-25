@extends('ui.layouts.frontend')
@section('title', 'Residential Storage in Dubai & Sharjah | StorageKeys')
@section('metaTitle', 'Residential Storage in Dubai & Sharjah | StorageKeys')
@section('metaDescription', 'Flexible residential storage in Dubai & Sharjah for furniture, appliances, boxes and household belongings. Ideal for moves, renovations, downsizing and more. Get a free quote.')
@section('content')

@php
    $mediaOverview = asset('sk-assets/assets/images/frontend/landing-hero.jpg');
    $mediaFacilities = asset('sk-assets/assets/images/frontend/landing-hero.jpg');
@endphp

<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="rs-hero">
        <div class="sk-container">
            <div class="rs-crumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <a href="{{ url('/storage-options') }}">Storage Solutions</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>Residential Storage</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Residential Storage</span>
            <h1>Residential Storage in <span>Dubai &amp; Sharjah</span></h1>
            <p class="lead">Flexible residential storage for furniture, appliances, boxes and household belongings — practical space for moves, renovations, relocations, downsizing, and homes that simply need additional room.</p>
            <div class="rs-hero-cta">
                <a href="#rs-quote" class="sk-btn sk-btn-primary"><i class="fas fa-home" aria-hidden="true"></i> Get a Free Quote</a>
                <a href="#rs-estimator" class="sk-btn sk-btn-ghost"><i class="fas fa-calculator" aria-hidden="true"></i> Estimate My Space</a>
            </div>
            <div class="rs-hero-badges">
                <span class="rs-hbadge"><i class="fas fa-truck" aria-hidden="true"></i> Moving</span>
                <span class="rs-hbadge"><i class="fas fa-paint-roller" aria-hidden="true"></i> Renovating</span>
                <span class="rs-hbadge"><i class="fas fa-compress" aria-hidden="true"></i> Downsizing</span>
                <span class="rs-hbadge"><i class="fas fa-people-carry" aria-hidden="true"></i> Relocating</span>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="rs-trust">
        <div class="sk-container">
            <div class="rs-trust-in">
                <div class="rs-trust-i"><i class="fas fa-couch" aria-hidden="true"></i> Furniture &amp; Appliances</div>
                <div class="rs-trust-i"><i class="fas fa-shield-alt" aria-hidden="true"></i> Secure &amp; CCTV</div>
                <div class="rs-trust-i"><i class="fas fa-expand-arrows-alt" aria-hidden="true"></i> Short &amp; Long Term</div>
                <div class="rs-trust-i"><i class="fas fa-key" aria-hidden="true"></i> Easy Access</div>
                <div class="rs-trust-i"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dubai &amp; Sharjah</div>
            </div>
        </div>
    </div>

    <!-- ============ OVERVIEW (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="rs-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Home Storage Solutions for Dubai &amp; Sharjah</h2>
                    <p>Homes can quickly become crowded when furniture, packed boxes, seasonal belongings and rarely used items take up valuable living space. Instead of filling spare rooms, balconies or garages with things you don't currently need, you can move them into dedicated storage.</p>
                    <p>StorageKeys provides home storage solutions for households in Dubai and Sharjah, with options suitable for different amounts of belongings and storage periods — from a few furniture pieces to the contents of an apartment or villa.</p>
                    <p>Our home storage Dubai solutions suit short-term and longer-term needs: a home move, renovation, relocation or downsizing — or simply creating additional space when your property no longer has room for everything you want to keep.</p>
                    <a href="#rs-situations" class="sk-btn sk-btn-outline" style="margin-top:6px;"><i class="fas fa-arrow-down" aria-hidden="true"></i> See When It Helps</a>
                </div>
                <div class="rs-media" style="background-image:url('{{ $mediaOverview }}');" role="img" aria-label="Residential storage overview"></div>
            </div>
        </div>
    </section>

    <!-- ============ SITUATIONS (FLIP CARDS) ============ -->
    <section class="sk-section" id="rs-situations" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Households Use It</span>
                <h2>Residential Storage for Different Household Requirements</h2>
                <p>Major changes to your home are easier to manage when belongings have somewhere to go. Tap a card to see how storage helps in each situation.</p>
            </div>
            <div class="rs-flips sk-reveal">
                <div class="rs-flip" tabindex="0" role="button" aria-label="Moving Between Properties">
                    <div class="rs-flip-inner">
                        <div class="rs-face rs-front">
                            <div class="ic"><i class="fas fa-people-carry" aria-hidden="true"></i></div>
                            <h3>Moving Between Properties</h3>
                            <div class="more">Tap to see how <i class="fas fa-arrow-right" aria-hidden="true"></i></div>
                        </div>
                        <div class="rs-face rs-back">
                            <h3>Moving Between Properties</h3>
                            <p>There's often a gap between leaving one property and moving into another. Storage keeps furniture, boxes, appliances and belongings safe until your new home is ready.</p>
                        </div>
                    </div>
                </div>
                <div class="rs-flip" tabindex="0" role="button" aria-label="Home Renovations">
                    <div class="rs-flip-inner">
                        <div class="rs-face rs-front">
                            <div class="ic"><i class="fas fa-paint-roller" aria-hidden="true"></i></div>
                            <h3>Home Renovations</h3>
                            <div class="more">Tap to see how <i class="fas fa-arrow-right" aria-hidden="true"></i></div>
                        </div>
                        <div class="rs-face rs-back">
                            <h3>Home Renovations</h3>
                            <p>Rooms often need clearing before construction, painting or flooring. Moving belongings into storage creates working space and keeps furniture away from renovation areas.</p>
                        </div>
                    </div>
                </div>
                <div class="rs-flip" tabindex="0" role="button" aria-label="Downsizing">
                    <div class="rs-flip-inner">
                        <div class="rs-face rs-front">
                            <div class="ic"><i class="fas fa-compress" aria-hidden="true"></i></div>
                            <h3>Downsizing</h3>
                            <div class="more">Tap to see how <i class="fas fa-arrow-right" aria-hidden="true"></i></div>
                        </div>
                        <div class="rs-face rs-back">
                            <h3>Downsizing</h3>
                            <p>A smaller apartment or villa can leave you with more than it holds. Storage lets you keep furniture and possessions while you decide what to use, move or keep elsewhere.</p>
                        </div>
                    </div>
                </div>
                <div class="rs-flip" tabindex="0" role="button" aria-label="Temporary Relocation">
                    <div class="rs-flip-inner">
                        <div class="rs-face rs-front">
                            <div class="ic"><i class="fas fa-plane-departure" aria-hidden="true"></i></div>
                            <h3>Temporary Relocation</h3>
                            <div class="more">Tap to see how <i class="fas fa-arrow-right" aria-hidden="true"></i></div>
                        </div>
                        <div class="rs-face rs-back">
                            <h3>Temporary Relocation</h3>
                            <p>Relocating within the UAE or moving abroad for a while? Residential storage is a convenient place for suitable household belongings until you return or settle in.</p>
                        </div>
                    </div>
                </div>
                <div class="rs-flip" tabindex="0" role="button" aria-label="Home Office Storage">
                    <div class="rs-flip-inner">
                        <div class="rs-face rs-front">
                            <div class="ic"><i class="fas fa-laptop" aria-hidden="true"></i></div>
                            <h3>Home Office Storage</h3>
                            <div class="more">Tap to see how <i class="fas fa-arrow-right" aria-hidden="true"></i></div>
                        </div>
                        <div class="rs-face rs-back">
                            <h3>Home Office Storage</h3>
                            <p>Working from home adds furniture, equipment, documents and supplies. Home office storage keeps suitable work-related belongings outside your main living areas.</p>
                        </div>
                    </div>
                </div>
                <div class="rs-flip" tabindex="0" role="button" aria-label="Seasonal Storage">
                    <div class="rs-flip-inner">
                        <div class="rs-face rs-front">
                            <div class="ic"><i class="fas fa-snowflake" aria-hidden="true"></i></div>
                            <h3>Seasonal Storage</h3>
                            <div class="more">Tap to see how <i class="fas fa-arrow-right" aria-hidden="true"></i></div>
                        </div>
                        <div class="rs-face rs-back">
                            <h3>Seasonal Storage</h3>
                            <p>Seasonal belongings occupy valuable space even when unused. Storing suitable seasonal household items separately helps keep your home organised all year round.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ SPACE ESTIMATOR ============ -->
    <section class="sk-section" id="rs-estimator">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Plan Your Space</span>
                <h2>How Much Residential Storage Do You Need?</h2>
                <p>The space you need depends on what you're storing — a few pieces need far less than a whole home. Add your rough quantities and we'll suggest an arrangement.</p>
            </div>
            <div class="rs-est sk-reveal">
                <div class="rs-steppers">
                    <div class="rs-step" data-w="3">
                        <div class="lab">
                            <i class="fas fa-couch" aria-hidden="true"></i>
                            <span>
                                <span class="t">Large furniture</span>
                                <span class="s">Sofas, beds, wardrobes, tables</span>
                            </span>
                        </div>
                        <div class="rs-ctrl">
                            <button type="button" data-d="-1" aria-label="Decrease furniture">−</button>
                            <span class="val">0</span>
                            <button type="button" data-d="1" aria-label="Increase furniture">+</button>
                        </div>
                    </div>
                    <div class="rs-step" data-w="0.5">
                        <div class="lab">
                            <i class="fas fa-box" aria-hidden="true"></i>
                            <span>
                                <span class="t">Boxes</span>
                                <span class="s">Packed moving &amp; storage boxes</span>
                            </span>
                        </div>
                        <div class="rs-ctrl">
                            <button type="button" data-d="-1" aria-label="Decrease boxes">−</button>
                            <span class="val">0</span>
                            <button type="button" data-d="1" aria-label="Increase boxes">+</button>
                        </div>
                    </div>
                    <div class="rs-step" data-w="2">
                        <div class="lab">
                            <i class="fas fa-blender" aria-hidden="true"></i>
                            <span>
                                <span class="t">Appliances</span>
                                <span class="s">Fridge, washer, cabinets, etc.</span>
                            </span>
                        </div>
                        <div class="rs-ctrl">
                            <button type="button" data-d="-1" aria-label="Decrease appliances">−</button>
                            <span class="val">0</span>
                            <button type="button" data-d="1" aria-label="Increase appliances">+</button>
                        </div>
                    </div>
                </div>
                <div class="rs-est-out">
                    <div class="lead">Suggested arrangement</div>
                    <h3 id="rsRec">Start adding items</h3>
                    <p id="rsRecDesc">Use the + buttons to add your furniture, boxes and appliances, and we’ll estimate the space you need.</p>
                    <div class="fill"><i id="rsFill" style="width:8%;" aria-hidden="true"></i></div>
                    <a href="#rs-quote" class="sk-btn sk-btn-primary" id="rsEstBtn"><i class="fas fa-paper-plane" aria-hidden="true"></i> Get a Quote</a>
                </div>
            </div>
            <p class="rs-store-note sk-reveal"><b>What can you store?</b> Sofas, beds, tables, chairs &amp; cabinets · household appliances &amp; suitable electronics · packed boxes &amp; personal belongings · home décor &amp; furnishings · seasonal items · household equipment · furniture removed during renovation. For a whole-home move, ask about <b>residential storage container rental</b>.</p>
        </div>
    </section>

    <!-- ============ FACILITIES ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="rs-split rev sk-reveal">
                <div class="rs-media" style="background-image:url('{{ $mediaFacilities }}');" role="img" aria-label="Residential storage facilities"></div>
                <div>
                    <span class="sk-eyebrow">Dedicated Facilities</span>
                    <h2>Residential Storage Facilities in Dubai &amp; Sharjah</h2>
                    <p>A dedicated facility lets you move suitable belongings out of your home — instead of relying on spare rooms, garages or other areas that could be used for everyday living. Our residential storage can be used for:</p>
                    <ul class="rs-points">
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Apartment &amp; villa moves</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Furniture during renovations</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Temporary relocation</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Downsizing &amp; home transitions</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Seasonal household belongings</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Extra space for crowded homes</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Home office storage needs</li>
                    </ul>
                    <p style="font-size:13.5px;color:var(--sk-muted);margin:0;">Tip: consider your actual belongings rather than just the size of your home — it helps you choose a more suitable option.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why StorageKeys</span>
                <h2>Why Choose StorageKeys for Residential Storage?</h2>
                <p>Practical storage for households that need more space without giving up belongings they may need later — supporting temporary situations like moves and renovations, as well as longer-term needs.</p>
            </div>
            <div class="rs-why sk-reveal">
                <div class="rs-whyc">
                    <div class="ic"><i class="fas fa-door-open" aria-hidden="true"></i></div>
                    <h4>Clear a Room</h4>
                    <p>Free up a spare room, garage or balcony by moving suitable belongings into dedicated storage.</p>
                </div>
                <div class="rs-whyc">
                    <div class="ic"><i class="fas fa-exchange-alt" aria-hidden="true"></i></div>
                    <h4>Store During a Transition</h4>
                    <p>Keep furniture safe during a move, renovation or property change until you're ready for it.</p>
                </div>
                <div class="rs-whyc">
                    <div class="ic"><i class="fas fa-home" aria-hidden="true"></i></div>
                    <h4>More Usable Space</h4>
                    <p>Create additional, usable space at home without parting with items you may want later.</p>
                </div>
                <div class="rs-whyc">
                    <div class="ic"><i class="fas fa-expand-arrows-alt" aria-hidden="true"></i></div>
                    <h4>Temporary or Long Term</h4>
                    <p>Flexible options for short-term situations and longer-term household storage alike.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">FAQs</span>
                <h2>FAQs About Residential Storage</h2>
            </div>
            <div class="rs-faq">
                <details open>
                    <summary>How much residential storage space do I need?</summary>
                    <div class="a">It depends on your belongings, furniture quantity and household size. A few items need less space than an entire apartment — try the space estimator above for a quick guide.</div>
                </details>
                <details>
                    <summary>Can I store furniture in residential storage units?</summary>
                    <div class="a">Yes. You can store suitable furniture — including sofas, beds, tables, chairs, cabinets and other household items — safely.</div>
                </details>
                <details>
                    <summary>Do you provide residential storage in Dubai and Sharjah?</summary>
                    <div class="a">Yes. StorageKeys provides residential storage solutions across Dubai and Sharjah, helping customers store household belongings conveniently and safely.</div>
                </details>
                <details>
                    <summary>Can I use residential storage during a home move?</summary>
                    <div class="a">Yes. Store furniture, appliances, boxes and other belongings during your move, making transitions between properties easier and more organised.</div>
                </details>
                <details>
                    <summary>Can I use residential storage long term?</summary>
                    <div class="a">Yes. Residential storage suits both temporary and long-term needs, offering flexible options for storing household belongings securely.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE ============ -->
    <section class="sk-section" id="rs-quote">
        <div class="sk-container">
            <div class="rs-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#fff;">Free Quote</span>
                    <h2>Make More Space at Home With StorageKeys</h2>
                    <p>Moving, renovating, downsizing, relocating or simply running out of room? We provide residential storage for households in Dubai and Sharjah — additional space for furniture, appliances, boxes and other belongings.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone" aria-hidden="true"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone" aria-hidden="true"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope" aria-hidden="true"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp" aria-hidden="true"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'variant' => 'simple',
                    'compact' => true,
                    'formClass' => 'sk-quote',
                    'fieldClass' => 'sk-field',
                    'title' => 'Request a Free Quote',
                    'submitLabel' => 'Request a Free Quote',
                    'source' => 'residential-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Residential Storage',
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="rs-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone" aria-hidden="true"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp</a>
        <a href="#rs-quote"><i class="fas fa-home" aria-hidden="true"></i> Quote</a>
    </div>

</div>
@endsection
