@extends('ui.layouts.frontend')
@section('title', 'Furniture Storage in Dubai & Abu Dhabi | Storage Keys')
@section('metaTitle', 'Secure Furniture Storage in Dubai & Abu Dhabi | StorageKeys')
@section('metaDescription', 'Store sofas, beds, tables and household furniture securely with flexible furniture storage units across the UAE. Climate options, 24/7 access. Get a free quote.')
@section('content')


<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Furniture Storage</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Furniture Storage</span>
            <h1>Furniture Storage Solutions in <span>Dubai &amp; Abu Dhabi</span></h1>
            <p class="lead">Need space for sofas, beds, dining sets or office furniture? Storage Keys provides secure furniture storage in Dubai and Abu Dhabi — ideal for moves, renovations, downsizing or seasonal furniture you want to keep safe and accessible.</p>
            <div class="ps-hero-cta">
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-couch"></i> Get a Free Quote</a>
                <a href="#ps-builder" class="sk-btn sk-btn-ghost"><i class="fas fa-list-check"></i> Build Your Furniture List</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-truck"></i> Moving</span>
                <span class="ps-hbadge"><i class="fas fa-paint-roller"></i> Renovating</span>
                <span class="ps-hbadge"><i class="fas fa-home"></i> Downsizing</span>
                <span class="ps-hbadge"><i class="fas fa-couch"></i> Extra Furniture</span>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="ps-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-shield-alt"></i> Secure &amp; CCTV</div>
                <div class="ps-trust-i"><i class="fas fa-temperature-low"></i> Climate Controlled</div>
                <div class="ps-trust-i"><i class="fas fa-expand-arrows-alt"></i> Flexible Terms</div>
                <div class="ps-trust-i"><i class="fas fa-key"></i> Convenient Access</div>
                <div class="ps-trust-i"><i class="fas fa-headset"></i> Friendly Support</div>
            </div>
        </div>
    </div>

    <!-- ============ OVERVIEW (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-split sk-reveal in">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Furniture Storage for Homes &amp; Offices</h2>
                    <p>Furniture often takes the most room — and it’s also the most valuable to protect. Whether you’re storing a few large pieces, a full living-room set or office desks and chairs, our furniture storage units give you clean, secure space without overcrowding your home or workplace.</p>
                    <p>From sofas and wardrobes to dining tables, mattresses and office furniture, Storage Keys helps you store items properly for the short or long term, with flexible unit sizes and access that suits your schedule.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Space for sofas, beds, tables, wardrobes and more</li>
                        <li><i class="fas fa-check-circle"></i> Short-term storage during moves or renovations</li>
                        <li><i class="fas fa-check-circle"></i> Long-term furniture storage when you need extra room</li>
                    </ul>
                </div>
                <div class="ps-media" style="background-image:url('images/overview.jpg');"></div>
            </div>
        </div>
    </section>

    <!-- ============ WHEN DO YOU NEED IT ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">The Right Time</span>
                <h2>When Do You Need Furniture Storage?</h2>
                <p>Furniture storage is useful whenever you need to free up rooms, protect pieces during works, or keep items you’re not ready to sell.</p>
            </div>
            <div class="ps-situations sk-reveal in">
                <span class="ps-sit"><i class="fas fa-truck"></i> Relocating</span>
                <span class="ps-sit"><i class="fas fa-paint-roller"></i> Renovating</span>
                <span class="ps-sit"><i class="fas fa-home"></i> Downsizing</span>
                <span class="ps-sit"><i class="fas fa-couch"></i> Extra Furniture</span>
                <span class="ps-sit"><i class="fas fa-briefcase"></i> Office Moves</span>
            </div>
            <div class="ps-term sk-reveal in">
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-hourglass-half"></i></div>
                    <span class="h3">Short-Term Furniture Storage</span>
                    <p>Ideal when you’re moving, renovating or waiting for a new property. Store sofas, beds, dining sets and appliances safely between dates — without rushing a sale or crowding temporary accommodation.</p>
                </div>
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-calendar-check"></i></div>
                    <span class="h3">Long-Term Furniture Storage</span>
                    <p>Perfect for furniture you still want to keep but don’t need every day — seasonal pieces, guest-room sets, spare office furniture or items for a future home. Flexible units adapt as your space needs change.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHAT CAN YOU STORE (INTERACTIVE BUILDER) ============ -->
    <section class="sk-section" id="ps-builder">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Build Your List</span>
                <h2>What Furniture Can You Store?</h2>
                <p>Tap the furniture types you plan to store and we’ll pass the list to our team for a quick, tailored quote.</p>
            </div>
            <div class="ps-items sk-reveal" id="psItems">
                <button type="button" class="ps-item" data-item="Sofas &amp; living room sets"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-couch"></i></div><span class="h4">Sofas &amp; Living Room</span></button>
                <button type="button" class="ps-item" data-item="Beds, mattresses &amp; bedroom furniture"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-bed"></i></div><span class="h4">Beds &amp; Bedroom</span></button>
                <button type="button" class="ps-item" data-item="Dining tables &amp; chairs"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-utensils"></i></div><span class="h4">Dining Tables &amp; Chairs</span></button>
                <button type="button" class="ps-item" data-item="Wardrobes &amp; cabinets"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-door-closed"></i></div><span class="h4">Wardrobes &amp; Cabinets</span></button>
                <button type="button" class="ps-item" data-item="Office desks &amp; chairs"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-briefcase"></i></div><span class="h4">Office Furniture</span></button>
                <button type="button" class="ps-item" data-item="Outdoor &amp; patio furniture"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-tree"></i></div><span class="h4">Outdoor Furniture</span></button>
                <button type="button" class="ps-item" data-item="Appliances with furniture move"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-blender"></i></div><span class="h4">Appliances</span></button>
                <button type="button" class="ps-item" data-item="Other furniture pieces"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-ellipsis-h"></i></div><span class="h4">Something Else</span></button>
            </div>
            <div class="ps-cart sk-reveal">
                <div class="txt"><b><span id="psCount">0</span> furniture type(s)</b> selected<small id="psList">Tap the items above to build your list.</small></div>
                <a href="#ps-quote" class="sk-btn sk-btn-primary" id="psCartBtn"><i class="fas fa-paper-plane"></i> Get a Quote for These</a>
            </div>
        </div>
    </section>

    <!-- ============ PACKING CHECKLIST (INTERACTIVE) ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Be Prepared</span>
                <h2>Tips for Preparing Furniture Before Storage</h2>
                <p>A little preparation protects finishes and makes move-in and move-out easier. Tick each step as you go.</p>
            </div>
            <div class="ps-check-wrap sk-reveal">
                <div class="ps-progress">
                    <span class="lbl"><span id="psDone">0</span> of 6 done</span>
                    <div class="bar"><i id="psBar" style="width: 0%;"></i></div>
                </div>
                <ul class="ps-check" id="psCheck">
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Clean and dry furniture thoroughly before storage.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Disassemble larger pieces where practical and keep fittings labelled.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Use protective covers, blankets or wrap for upholstery and wood surfaces.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Wrap glass, mirrors and fragile parts with bubble wrap or packing paper.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Store mattresses and sofas elevated off the floor where possible.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Keep a simple inventory of each furniture piece and its condition.</span></li>
                </ul>
                <div class="ps-check-done" id="psCheckDone"><i class="fas fa-circle-check"></i> Nicely done — your furniture is ready for storage! Need packing supplies? <a href="{{ url('/shop') }}" style="color:#1f9d57;text-decoration:underline;">Browse our shop</a>.</div>
            </div>
        </div>
    </section>

    <!-- ============ UNITS FOR EVERY REQUIREMENT ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Unit Sizes</span>
                <h2>Storage Units Sized for Furniture</h2>
                <p>From a few large pieces to a full home’s worth of furniture — choose a unit that fits what you’re storing so you only pay for the space you need.</p>
            </div>
            <div class="ps-help sk-reveal">
                <div>
                    <span class="h3">Not sure which size is right?</span>
                    <p>Sofas, wardrobes and dining sets need different footprints. Share what you’re storing and our team will recommend a practical unit — and you can adjust later if your furniture list changes.</p>
                </div>
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-comments"></i> Help Me Choose</a>
            </div>
        </div>
    </section>

    <!-- ============ SAFE & SECURE (NAVY SPLIT) ============ -->
    <section class="sk-section ps-feat">
        <div class="sk-container">
            <div class="ps-split sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Security First</span>
                    <h2>Safe &amp; Secure Furniture Storage Facilities</h2>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;">Furniture deserves a clean, dry and professionally managed environment. Whether it’s living-room sets, bedroom furniture or office pieces, we prioritise secure access and facility oversight throughout your rental.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Secure access controls &amp; CCTV surveillance</li>
                        <li><i class="fas fa-check-circle"></i> Climate-controlled options for wood and upholstery</li>
                        <li><i class="fas fa-check-circle"></i> Clean, well-maintained storage units</li>
                        <li><i class="fas fa-check-circle"></i> Flexible access arrangements</li>
                        <li><i class="fas fa-check-circle"></i> Professional customer support</li>
                    </ul>
                </div>
                <div class="ps-media" style="background-image:url('images/security.jpg');"></div>
            </div>
        </div>
    </section>

    <!-- ============ HOME STORAGE DUBAI (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-split rev sk-reveal">
                <div class="ps-media" style="background-image:url('images/home-storage.jpg');"></div>
                <div>
                    <span class="sk-eyebrow">At Home</span>
                    <h2>Free Up Space Without Selling Your Furniture</h2>
                    <p>Apartments and villas across Dubai often run out of room before you’re ready to part with quality furniture. Furniture storage lets you declutter living areas while keeping sofas, beds and tables safe for when you need them again.</p>
                    <p>Whether you’re renovating, relocating or simply creating more space, flexible furniture storage units make it easier to keep your home organised — with convenient access and unit sizes that match large pieces.</p>
                    <a href="#ps-quote" class="sk-btn sk-btn-outline"><i class="fas fa-home"></i> Free Up Space at Home</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ COVERAGE DUBAI & ABU DHABI ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Where We Serve</span>
                <h2>Furniture Storage in Dubai &amp; Abu Dhabi</h2>
                <p>Short-term storage during a move or long-term space for furniture you want to keep — facilities designed for households and businesses across Dubai, Abu Dhabi and the UAE.</p>
            </div>
            <div class="ps-cover sk-reveal">
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <div><span class="h4">Dubai</span><p>Flexible furniture storage for apartments and villas across Dubai — store sofas, beds and dining sets during moves, renovations or decluttering.</p></div>
                </div>
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <div><span class="h4">Abu Dhabi</span><p>Secure furniture storage for customers in Abu Dhabi — short-term between moves or long-term for pieces you want protected and accessible.</p></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose Us for Furniture Storage?</h2>
                <p>A storage partner focused on space for large pieces, security and flexible access — from a single sofa to a full home of furniture.</p>
            </div>
            <div class="ps-why sk-reveal">
                <div class="ps-whyc"><div class="ic"><i class="fas fa-expand-arrows-alt"></i></div><span class="h4">Flexible Rental Options</span><p>Short-term or long-term plans that fit your move or renovation timeline — pay for the furniture space you need.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-ruler-combined"></i></div><span class="h4">Multiple Unit Sizes</span><p>Units sized for bulky furniture, living-room sets or entire household contents without wasting unused space.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-shield-alt"></i></div><span class="h4">Safe &amp; Secure Storage</span><p>CCTV monitoring, controlled access and managed premises help protect furniture throughout its stay.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-temperature-low"></i></div><span class="h4">Climate-Controlled Options</span><p>Wood, upholstery and finishes benefit from conditions that reduce excessive heat and humidity.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-key"></i></div><span class="h4">Convenient Access</span><p>Reach your furniture when you need it through flexible access arrangements that keep things simple.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-headset"></i></div><span class="h4">Professional Support</span><p>Our team helps you choose the right unit size for sofas, beds and larger pieces from quote to move-in.</p></div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">FAQs</span>
                <h2>FAQs About Furniture Storage</h2>
            </div>
            <div class="ps-faq">
                <details open>
                    <summary>How much does furniture storage cost in Dubai?</summary>
                    <div class="a">Pricing depends on unit size and rental period. Tell us what furniture you’re storing and our team will provide a personalised quote.</div>
                </details>
                <details>
                    <summary>What furniture can I store?</summary>
                    <div class="a">You can typically store sofas, beds, mattresses, dining sets, wardrobes, cabinets, office desks and similar household or office furniture that complies with our storage guidelines.</div>
                </details>
                <details>
                    <summary>Is furniture storage suitable during a renovation or move?</summary>
                    <div class="a">Yes. Short-term furniture storage is ideal for renovations, relocating between properties or bridging gaps between move-out and move-in dates.</div>
                </details>
                <details>
                    <summary>Should I use climate-controlled storage for furniture?</summary>
                    <div class="a">Climate-controlled options are helpful for wood, leather and upholstered pieces in the UAE climate. Our team can advise based on what you’re storing.</div>
                </details>
                <details>
                    <summary>Do you offer furniture storage in Abu Dhabi?</summary>
                    <div class="a">Yes. We provide flexible furniture storage solutions for customers in Dubai, Abu Dhabi and across the UAE.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="ps-quote">
        <div class="sk-container">
            <div class="ps-quote sk-reveal">
                <div>
                    <h2>Get Started with Furniture Storage Today</h2>
                    <p>Tell us what furniture you need to store and we’ll recommend the right unit — with a free, no-obligation quote.</p>
                    <div class="btns">
                        <a href="https://wa.me/971565018785" class="sk-btn sk-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'variant' => 'simple',
                    'formClass' => 'sk-quote',
                    'fieldClass' => 'sk-field',
                    'title' => 'Request a Free Quote',
                    'submitLabel' => 'Request a Free Quote',
                    'source' => 'furniture-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Furniture Storage',
                    'showItemsField' => true,
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ps-quote"><i class="fas fa-couch"></i> Quote</a>
    </div>

</div>
@endsection
