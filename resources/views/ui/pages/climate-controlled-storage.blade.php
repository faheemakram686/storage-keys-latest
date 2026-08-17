@extends('ui.layouts.frontend')
@section('title', 'Climate-Controlled Storage in Dubai & Sharjah, UAE | Storage Keys')
@section('metaTitle', 'Climate-Controlled Storage Solutions in UAE | StorageKeys')
@section('metaDescription', 'Protect documents, electronics and furniture from heat and humidity with climate-controlled storage across the UAE. Book your storage unit now!')
@section('content')

<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="cc-hero">
        <div class="sk-container">
            <div class="cc-crumb"><a href="#">Home</a> <i class="fas fa-chevron-right"></i> <a href="#">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Climate-Controlled Storage</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Climate-Controlled Storage</span>
            <h1>Climate-Controlled Storage in <span>Dubai &amp; Sharjah, UAE</span></h1>
            <p class="lead">Protect furniture, electronics, documents and valuable belongings with secure climate-controlled storage. Flexible, temperature-controlled units for personal and business needs across Dubai, Sharjah and the UAE.</p>
            <div class="cc-hero-cta">
                <a href="#cc-quote" class="sk-btn sk-btn-primary"><i class="fas fa-snowflake"></i> Get a Free Quote</a>
                <a href="#cc-sim" class="sk-btn sk-btn-ghost"><i class="fas fa-temperature-low"></i> See the Difference</a>
            </div>
            <div class="cc-hero-badges">
                <span class="cc-hbadge"><i class="fas fa-couch"></i> Furniture</span>
                <span class="cc-hbadge"><i class="fas fa-laptop"></i> Electronics</span>
                <span class="cc-hbadge"><i class="fas fa-folder-open"></i> Documents</span>
                <span class="cc-hbadge"><i class="fas fa-gem"></i> Valuables</span>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="cc-trust">
        <div class="sk-container">
            <div class="cc-trust-in">
                <div class="cc-trust-i"><i class="fas fa-temperature-low"></i> Temperature Controlled</div>
                <div class="cc-trust-i"><i class="fas fa-tint"></i> Humidity Managed</div>
                <div class="cc-trust-i"><i class="fas fa-video"></i> 24/7 CCTV</div>
                <div class="cc-trust-i"><i class="fas fa-expand-arrows-alt"></i> Flexible Terms</div>
                <div class="cc-trust-i"><i class="fas fa-broom"></i> Clean &amp; Maintained</div>
            </div>
        </div>
    </div>

    <!-- ============ OVERVIEW (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="cc-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Climate-Controlled Storage for Your Most Valuable Belongings</h2>
                    <p>Not everything can be stored in a standard unit. Prolonged exposure to high temperatures and humidity can gradually affect furniture, electronics, important documents and other valuables — particularly in the UAE's climate. Climate-controlled storage helps minimise these risks by maintaining a stable environment throughout the storage period.</p>
                    <p>From household furniture and family keepsakes to archived business records and commercial inventory, our climate-controlled facility supports a wide range of needs. Combined with flexible rental terms, temporary solutions and on-demand options, you can store with confidence for as long as you need.</p>
                    <ul class="cc-points">
                        <li><i class="fas fa-check-circle"></i> A stable environment that protects sensitive items</li>
                        <li><i class="fas fa-check-circle"></i> Short-term protection during a move or long-term preservation</li>
                        <li><i class="fas fa-check-circle"></i> Flexible, on-demand terms for personal &amp; business needs</li>
                    </ul>
                </div>
                <div class="cc-media" style="background-image:url('images/overview.jpg');"></div>
            </div>
        </div>
    </section>

    <!-- ============ WHAT CAN BE STORED (RISK EXPLORER) ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Sensitive Items</span>
                <h2>What Can Be Stored in Our Climate-Controlled Units?</h2>
                <p>Certain belongings are affected by changes in temperature or humidity over time. Tap an item to see the risk in a standard space — and how a controlled environment protects it.</p>
            </div>
            <div class="cc-explorer">
                <div class="cc-items" id="ccItems">
                    <button type="button" class="cc-it active" data-k="wood"><i class="fas fa-couch"></i> Wood &amp; Antiques</button>
                    <button type="button" class="cc-it" data-k="elec"><i class="fas fa-laptop"></i> Electronics</button>
                    <button type="button" class="cc-it" data-k="docs"><i class="fas fa-folder-open"></i> Documents &amp; Archives</button>
                    <button type="button" class="cc-it" data-k="art"><i class="fas fa-palette"></i> Artwork &amp; Photos</button>
                    <button type="button" class="cc-it" data-k="music"><i class="fas fa-guitar"></i> Instruments</button>
                    <button type="button" class="cc-it" data-k="stock"><i class="fas fa-boxes"></i> Business Inventory</button>
                </div>
                <div class="cc-detail sk-reveal" id="ccDetail">
                    <div class="ic" id="ccIc"><i class="fas fa-couch"></i></div>
                    <div>
                        <h3 id="ccTitle">Wooden Furniture, Antiques &amp; Home Décor</h3>
                        <div class="cc-line risk"><i class="fas fa-triangle-exclamation"></i><span><b>The risk:</b> <span id="ccRisk">Wood can expand, contract or warp when exposed to excessive heat and moisture.</span></span></div>
                        <div class="cc-line prot"><i class="fas fa-shield-alt"></i><span><b>How we protect it:</b> <span id="ccProt">Stable temperature and humidity keep timber and joints from cracking or warping.</span></span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ SOLUTIONS ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Tailored Solutions</span>
                <h2>Climate-Controlled Storage Solutions</h2>
                <p>Household furniture during a renovation, or long-term temperature-controlled storage for valuable inventory and records — our solutions meet a wide range of personal and commercial needs.</p>
            </div>
            <div class="cc-sol sk-reveal">
                <div class="cc-solc"><div class="ic"><i class="fas fa-home"></i></div><h3>Personal Belongings</h3><p>Protect furniture, clothing, artwork, luggage and family valuables in a clean, climate-controlled unit built for long-term preservation.</p></div>
                <div class="cc-solc"><div class="ic"><i class="fas fa-boxes"></i></div><h3>Business Inventory</h3><p>Store sensitive inventory, electronics, premium products and archived records in temperature-controlled storage that helps maintain product quality.</p></div>
                <div class="cc-solc"><div class="ic"><i class="fas fa-folder-open"></i></div><h3>Documents &amp; Archives</h3><p>Protect contracts, financial records and important paperwork from excessive heat and humidity with secure climate-controlled self storage.</p></div>
                <div class="cc-solc"><div class="ic"><i class="fas fa-laptop"></i></div><h3>Electronics &amp; Delicate Items</h3><p>Air-conditioned storage safeguards electronics, instruments, collectibles and other delicate belongings that need stable conditions.</p></div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CLIMATE-CONTROLLED (SIMULATOR) ============ -->
    <section class="sk-section cc-feat" id="cc-sim">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;color:#ffcf9e;">See the Difference</span>
                <h2>Why Choose Climate-Controlled Storage?</h2>
                <p style="color:rgba(255,255,255,.8);">The UAE's climate is tough on wood, leather, paper, fabric and electronics — especially over long periods. See how a standard unit compares to a climate-controlled one, then push it to a summer's day.</p>
            </div>
            <div class="cc-sim sk-reveal">
                <div class="cc-sim-toggle" id="ccSimToggle">
                    <span>Outside conditions:</span>
                    <button type="button" class="active" data-mode="mild">Comfortable day</button>
                    <button type="button" data-mode="summer">UAE summer day ☀️</button>
                </div>
                <div class="cc-sim-grid">
                    <div class="cc-unit standard">
                        <h3><span class="ic"><i class="fas fa-box"></i></span> Standard Unit</h3>
                        <div class="cc-meter"><label>Temperature <b class="cc-t">32°C</b></label><div class="track"><i class="cc-tb cc-bar-warn" style="width:64%"></i></div></div>
                        <div class="cc-meter"><label>Humidity <b class="cc-h">60%</b></label><div class="track"><i class="cc-hb cc-bar-warn" style="width:60%"></i></div></div>
                        <div class="cc-status warn" id="ccStdStatus"><i class="fas fa-triangle-exclamation"></i> Warm &amp; humid — long-term risk to sensitive items</div>
                    </div>
                    <div class="cc-unit controlled">
                        <h3><span class="ic"><i class="fas fa-snowflake"></i></span> Climate-Controlled Unit</h3>
                        <div class="cc-meter"><label>Temperature <b>21°C</b></label><div class="track"><i class="cc-bar-ok" style="width:42%"></i></div></div>
                        <div class="cc-meter"><label>Humidity <b>45%</b></label><div class="track"><i class="cc-bar-ok" style="width:45%"></i></div></div>
                        <div class="cc-status ok"><i class="fas fa-shield-alt"></i> Stable &amp; protected all year round</div>
                    </div>
                </div>
                <p style="text-align:center;color:rgba(255,255,255,.7);font-size:13.5px;margin:24px auto 0;max-width:640px;">Even small environmental changes add up over time — a controlled environment offers greater peace of mind than standard storage alone, especially for long-term storage.</p>
            </div>
        </div>
    </section>

    <!-- ============ FACILITY (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="cc-split rev sk-reveal">
                <div class="cc-media" style="background-image:url('images/facility.jpg');"></div>
                <div>
                    <span class="sk-eyebrow">Our Facility</span>
                    <h2>Our Climate-Controlled Storage Facility</h2>
                    <p>Designed to provide consistent protection for temperature-sensitive belongings throughout the year. Every unit is maintained in a clean, air-conditioned environment where controlled temperature and humidity reduce the effects of the UAE's extreme climate.</p>
                    <ul class="cc-points">
                        <li><i class="fas fa-check-circle"></i> Controlled temperature &amp; humidity in every unit</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 CCTV surveillance &amp; controlled access</li>
                        <li><i class="fas fa-check-circle"></i> Clean, professionally maintained storage units</li>
                        <li><i class="fas fa-check-circle"></i> Flexible, temporary &amp; long-term rental plans</li>
                        <li><i class="fas fa-check-circle"></i> Easy access whenever you need your belongings</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY STORAGE KEYS ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose Storage Keys?</h2>
                <p>More than available space — we combine climate-controlled storage with secure facilities, flexible terms and dedicated support to make storing valuable belongings simple and reliable.</p>
            </div>
            <div class="cc-why sk-reveal">
                <div class="cc-whyc"><div class="ic"><i class="fas fa-snowflake"></i></div><h4>True Climate Control</h4><p>Controlled temperature and humidity protect wood, leather, paper, fabric and electronics year-round.</p></div>
                <div class="cc-whyc"><div class="ic"><i class="fas fa-shield-alt"></i></div><h4>Secure Facilities</h4><p>24/7 CCTV surveillance, controlled access and professionally maintained units keep belongings safe.</p></div>
                <div class="cc-whyc"><div class="ic"><i class="fas fa-expand-arrows-alt"></i></div><h4>Flexible Rental Terms</h4><p>Temporary storage during a move, on-demand space or long-term protection — you choose the term.</p></div>
                <div class="cc-whyc"><div class="ic"><i class="fas fa-user-check"></i></div><h4>Dedicated Support</h4><p>Our team helps you choose the right unit for your sensitive items and requirements.</p></div>
                <div class="cc-whyc"><div class="ic"><i class="fas fa-key"></i></div><h4>Convenient Access</h4><p>Reach your belongings easily whenever you need them, with a smooth storage experience.</p></div>
                <div class="cc-whyc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Across Dubai &amp; Sharjah</h4><p>Dependable climate-controlled storage for customers throughout Dubai, Sharjah and the UAE.</p></div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">FAQs</span>
                <h2>FAQs About Climate-Controlled Storage</h2>
            </div>
            <div class="cc-faq">
                <details open>
                    <summary>What is the difference between standard storage and climate-controlled storage?</summary>
                    <div class="a">Climate-controlled storage maintains stable temperature and humidity levels, helping protect sensitive belongings such as furniture, electronics, documents and artwork from heat and moisture.</div>
                </details>
                <details>
                    <summary>What items should be stored in climate-controlled units?</summary>
                    <div class="a">Furniture, electronics, business records, photographs, artwork, musical instruments, collectibles and other temperature-sensitive belongings are ideal for climate-controlled storage.</div>
                </details>
                <details>
                    <summary>Are your climate-controlled storage units secure?</summary>
                    <div class="a">Yes. Our facility features 24/7 CCTV surveillance, controlled access and secure storage units to help protect your belongings throughout the rental period.</div>
                </details>
                <details>
                    <summary>Can I rent climate-controlled storage for a short period?</summary>
                    <div class="a">Yes. We offer flexible temporary storage solutions for both short-term and long-term requirements, allowing you to rent a unit for as long as needed.</div>
                </details>
                <details>
                    <summary>Do you provide climate-controlled storage in Dubai?</summary>
                    <div class="a">Yes. We provide climate-controlled storage solutions for customers across Dubai, Sharjah and the UAE through our secure, temperature-controlled facility.</div>
                </details>
                <details>
                    <summary>Is climate-controlled storage suitable for businesses?</summary>
                    <div class="a">Absolutely. Businesses use our temperature-controlled units to protect documents, electronics, premium inventory and other sensitive commercial assets.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE ============ -->
    <section class="sk-section" id="cc-quote">
        <div class="sk-container">
            <div class="cc-quote sk-reveal">
                <div class="in">
                    <h2>Get a Free Climate-Controlled Storage Quote Today</h2>
                    <p>Protect your valuable belongings with secure climate-controlled storage from Storage Keys. Contact our team today for a free quote and the right temperature-controlled solution for your needs.</p>
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
                    'source' => 'climate-controlled-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Climate Controlled Storage',
                ])
            </div>
        </div>
    </section>

</div>

@endsection