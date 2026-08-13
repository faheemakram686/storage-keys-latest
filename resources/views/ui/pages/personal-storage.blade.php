
@extends('ui.layouts.frontend')
@section('title', 'Personal Storage Solutions in Dubai & Abu Dhabi | Storage Keys')
@section('metaTitle', 'Affordable Personal Storage Solutions in Dubai | StorageKeys')
@section('metaDescription', 'Store furniture, boxes and seasonal items with secure personal storage units across the UAE. Flexible terms, 24/7 access. Get a free quote.')
@section('content')


<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="/">Home</a> <i class="fas fa-chevron-right"></i> <a href="/">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Personal Storage</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Personal Storage</span>
            <h1>Personal Storage Solutions in <span>Dubai &amp; Abu Dhabi</span></h1>
            <p class="lead">Create more space at home with flexible personal storage in Dubai and Abu Dhabi. Whether you're moving, renovating or travelling, Storage Keys keeps your belongings safe, secure and easily accessible.</p>
            <div class="ps-hero-cta">
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-box-open"></i> Get a Free Quote</a>
                <a href="#ps-builder" class="sk-btn sk-btn-ghost"><i class="fas fa-list-check"></i> Build Your Storage List</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-truck"></i> Moving</span>
                <span class="ps-hbadge"><i class="fas fa-paint-roller"></i> Renovating</span>
                <span class="ps-hbadge"><i class="fas fa-plane"></i> Travelling</span>
                <span class="ps-hbadge"><i class="fas fa-couch"></i> Decluttering</span>
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
                    <h2>Personal Storage Solutions for Every Need</h2>
                    <p>No two storage requirements are the same. Whether you need space for a few boxes, large furniture or the contents of an entire home, our personal storage solutions provide a secure and flexible way to store your belongings for as long as you need.</p>
                    <p>Our self storage facilities are designed for individuals and families who want to free up valuable space without parting with their belongings — from household goods and furniture to items you only use occasionally — keeping your home organised while your possessions stay safe.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> A range of unit sizes for temporary or long-term needs</li>
                        <li><i class="fas fa-check-circle"></i> Flexible rental options — store for as long as you need</li>
                        <li><i class="fas fa-check-circle"></i> Free up space at home without giving anything up</li>
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
                <h2>When Do You Need Personal Storage?</h2>
                <p>Whether you're planning a move, renovating or simply reducing clutter, personal storage keeps your belongings protected while creating more room at home.</p>
            </div>
            <div class="ps-situations sk-reveal in">
                <span class="ps-sit"><i class="fas fa-truck"></i> Relocating</span>
                <span class="ps-sit"><i class="fas fa-paint-roller"></i> Renovating</span>
                <span class="ps-sit"><i class="fas fa-plane"></i> Travelling</span>
                <span class="ps-sit"><i class="fas fa-couch"></i> Decluttering</span>
                <span class="ps-sit"><i class="fas fa-compress-arrows-alt"></i> Downsizing</span>
            </div>
            <div class="ps-term sk-reveal in">
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-hourglass-half"></i></div>
                    <h3>Short-Term Personal Storage</h3>
                    <p>Ideal during life transitions. Relocating, renovating or travelling for a while? A temporary solution keeps household belongings safe until you're ready for them again — and bridges the gap between moving dates, so you can store furniture, appliances and personal items without feeling rushed.</p>
                </div>
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-calendar-check"></i></div>
                    <h3>Long-Term Personal Storage</h3>
                    <p>Perfect for belongings you don't need every day but still want to keep. Whether you live in a smaller apartment, have seasonal household items or simply need extra space, our flexible units are a reliable long-term solution that adapts to your changing lifestyle.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHAT CAN YOU STORE (INTERACTIVE BUILDER) ============ -->
    <section class="sk-section" id="ps-builder">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Build Your List</span>
                <h2>What Can You Store?</h2>
                <p>From a few boxes to the contents of an entire home. Tap what you're planning to store and we'll pass the list straight to our team for a quick quote.</p>
            </div>
            <div class="ps-items sk-reveal" id="psItems">
                <button type="button" class="ps-item" data-item="Furniture &amp; home décor"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-couch"></i></div><h4>Furniture &amp; Home Décor</h4></button>
                <button type="button" class="ps-item" data-item="Household goods &amp; appliances"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-blender"></i></div><h4>Household Goods &amp; Appliances</h4></button>
                <button type="button" class="ps-item" data-item="Documents &amp; personal records"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-folder-open"></i></div><h4>Documents &amp; Records</h4></button>
                <button type="button" class="ps-item" data-item="Luggage &amp; travel accessories"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-suitcase-rolling"></i></div><h4>Luggage &amp; Travel</h4></button>
                <button type="button" class="ps-item" data-item="Seasonal clothing &amp; decorations"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-tshirt"></i></div><h4>Seasonal Clothing &amp; Decor</h4></button>
                <button type="button" class="ps-item" data-item="Sports &amp; hobby equipment"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-bicycle"></i></div><h4>Sports &amp; Hobby Gear</h4></button>
                <button type="button" class="ps-item" data-item="Electronics &amp; valuables"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-tv"></i></div><h4>Electronics &amp; Valuables</h4></button>
                <button type="button" class="ps-item" data-item="Something else"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-ellipsis-h"></i></div><h4>Something Else</h4></button>
            </div>
            <div class="ps-cart sk-reveal">
                <div class="txt"><b><span id="psCount">0</span> item type(s)</b> selected<small id="psList">Tap the items above to build your list.</small></div>
                <a href="#ps-quote" class="sk-btn sk-btn-primary" id="psCartBtn"><i class="fas fa-paper-plane"></i> Get a Quote for These</a>
            </div>
        </div>
    </section>

    <!-- ============ PACKING CHECKLIST (INTERACTIVE) ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Be Prepared</span>
                <h2>Tips for Packing Items Before Storage</h2>
                <p>A little preparation keeps everything organised and protected — and makes items easy to find later. Tick each step as you go.</p>
            </div>
            <div class="ps-check-wrap sk-reveal">
                <div class="ps-progress">
                    <span class="lbl"><span id="psDone">0</span> of 6 done</span>
                    <div class="bar"><i id="psBar" style="width: 0%;"></i></div>
                </div>
                <ul class="ps-check" id="psCheck">
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Use sturdy boxes and quality packing tape for added protection.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Label every box clearly to identify its contents quickly.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Clean furniture before storage and use protective covers where possible.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Wrap fragile items individually with bubble wrap or packing paper.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Avoid overloading boxes to make lifting safer and prevent damage.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Keep a simple inventory of stored belongings for easier organisation.</span></li>
                </ul>
                <div class="ps-check-done" id="psCheckDone"><i class="fas fa-circle-check"></i> Nicely done — your belongings are ready for storage! Need packing supplies? <a href="#" style="color:#1f9d57;text-decoration:underline;">Browse our shop</a>.</div>
            </div>
        </div>
    </section>

    <!-- ============ UNITS FOR EVERY REQUIREMENT ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Unit Sizes</span>
                <h2>Self Storage Units for Every Requirement</h2>
                <p>Available in a variety of sizes — suitable for everything from a few personal belongings to the contents of an entire house, so you only pay for the space you need.</p>
            </div>
            <div class="ps-help sk-reveal">
                <div>
                    <h3>Not sure which size is right?</h3>
                    <p>Choosing the correct unit depends on the type and quantity of items you plan to store. Our team can recommend a practical, cost-effective option based on your requirements — and as your needs change, you can easily adjust your space without committing to more room than necessary.</p>
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
                    <h2>Safe &amp; Secure Self Storage Facilities</h2>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;">Choosing the right provider means knowing your belongings are stored in a clean, secure and professionally managed environment. Whether it's furniture, household goods, important documents or valuable personal items, we prioritise the safety of every item throughout its stay.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Secure access controls &amp; CCTV surveillance</li>
                        <li><i class="fas fa-check-circle"></i> Climate-controlled storage options</li>
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
                    <h2>Home Storage Solutions in Dubai</h2>
                    <p>Limited storage space is a common challenge for homeowners and apartment residents across Dubai. Instead of overcrowding your living space, our home storage solutions keep household belongings organised while making more room for everyday life.</p>
                    <p>Whether you're decluttering, renovating, relocating or storing seasonal items, our self storage units offer the flexibility to store your belongings for as long as you need — with convenient access and a range of unit sizes, creating extra space at home has never been easier.</p>
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
                <h2>Personal Storage in Dubai &amp; Abu Dhabi</h2>
                <p>Short-term storage during a move or long-term space for household belongings — our facilities are designed to suit different lifestyles, with on-demand services and flexible rental plans that keep your belongings safe and accessible.</p>
            </div>
            <div class="ps-cover sk-reveal">
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <div><h4>Dubai</h4><p>Flexible personal and home storage for households and apartment residents across Dubai — declutter, renovate, relocate or store seasonal items with ease.</p></div>
                </div>
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <div><h4>Abu Dhabi</h4><p>Secure, on-demand personal storage for customers in Abu Dhabi — short-term during a move or long-term for belongings you want to keep safe and accessible.</p></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose Us As Your Personal Storage Partner?</h2>
                <p>A storage provider focused on flexibility, security and customer convenience — from small personal units to larger household storage solutions.</p>
            </div>
            <div class="ps-why sk-reveal">
                <div class="ps-whyc"><div class="ic"><i class="fas fa-expand-arrows-alt"></i></div><h4>Flexible Rental Options</h4><p>Short-term or long-term plans that fit your schedule — you only pay for the storage space you actually need.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-ruler-combined"></i></div><h4>Multiple Unit Sizes</h4><p>From a few boxes to the contents of an entire home, units in different sizes to match your belongings and budget.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-shield-alt"></i></div><h4>Safe &amp; Secure Storage</h4><p>CCTV monitoring, controlled access and professionally managed premises help protect your belongings throughout their stay.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-temperature-low"></i></div><h4>Climate-Controlled Storage</h4><p>Sensitive items like furniture, electronics and documents benefit from conditions that minimise heat and humidity.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-key"></i></div><h4>Convenient Access</h4><p>Reach your belongings whenever you need them through flexible access arrangements that keep things hassle-free.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-headset"></i></div><h4>Professional Support</h4><p>Our experienced team helps you choose the right unit and ensures a smooth experience from start to finish.</p></div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">FAQs</span>
                <h2>FAQs About Personal Storage</h2>
            </div>
            <div class="ps-faq">
                <details open>
                    <summary>How much does personal storage cost in Dubai?</summary>
                    <div class="a">Pricing depends on your storage unit size and rental period. Contact our team for a personalised quote based on your storage requirements.</div>
                </details>
                <details>
                    <summary>What items can I store in a personal storage unit?</summary>
                    <div class="a">You can store furniture, household goods, luggage, documents, electronics, seasonal items and other personal belongings that comply with our storage guidelines.</div>
                </details>
                <details>
                    <summary>Is personal storage suitable for temporary use?</summary>
                    <div class="a">Yes. Our temporary storage solutions are ideal for moving, renovations, travelling or any short-term storage requirement.</div>
                </details>
                <details>
                    <summary>Are your self storage facilities secure?</summary>
                    <div class="a">Yes. Our facilities include CCTV surveillance, secure access controls and professionally managed storage environments for added peace of mind.</div>
                </details>
                <details>
                    <summary>Do you offer personal storage services in Abu Dhabi?</summary>
                    <div class="a">Yes. We provide flexible personal storage solutions for customers in Dubai, Abu Dhabi and across the UAE.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="ps-quote">
        <div class="sk-container">
            <div class="ps-quote sk-reveal">
                <h2>Get Started with Personal Storage Today</h2>
                <p>Need extra space? Tell us what you'd like to store and we'll find the right personal storage solution for your home — and send you a free, no-obligation quote.</p>
                <div class="btns">
                    <a href="#" class="sk-btn sk-btn-white" id="psQuoteBtn"><i class="fas fa-box-open"></i> Request a Free Quote</a>
                    <a href="https://wa.me/971565018785" class="sk-btn sk-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ps-quote"><i class="fas fa-box-open"></i> Quote</a>
    </div>

</div>
@endsection