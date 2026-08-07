@extends('ui.layouts.frontend')
@section('title', '| Business Storage')


@section('content')

<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="wh-hero">
        <div class="sk-container">
            <div class="wh-crumb"><a href="#">Home</a> <i class="fas fa-chevron-right"></i> <a href="#">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Warehouse Storage</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Warehouse Storage</span>
            <h1>Warehouse Storage in <span>Dubai &amp; Sharjah, UAE</span></h1>
            <p class="lead">Flexible warehouse storage solutions for bulk inventory, palletised goods and commercial equipment. Secure, climate-controlled facilities with scalable rental terms for businesses across Dubai, Sharjah and the wider UAE.</p>
            <div class="wh-hero-cta">
                <a href="#warehouse-quote" class="sk-btn sk-btn-primary"><i class="fas fa-file-invoice-dollar"></i> Get Your Storage Quote</a>
                <a href="#estimator" class="sk-btn sk-btn-ghost"><i class="fas fa-sliders-h"></i> Estimate Your Space</a>
            </div>
            <div class="wh-hero-stats">
                <div class="wh-hstat"><div class="v">Pallets<span class="plus"> &amp; bulk</span></div><div class="k">Racking &amp; palletised goods</div></div>
                <div class="wh-hstat"><div class="v">Scalable</div><div class="k">Grow or shrink your space</div></div>
                <div class="wh-hstat"><div class="v">24/7</div><div class="k">CCTV &amp; controlled access</div></div>
            </div>
        </div>
    </section>

    <!-- ============ TRUST STRIP ============ -->
    <div class="wh-trust">
        <div class="sk-container">
            <div class="wh-trust-in">
                <div class="wh-trust-i"><i class="fas fa-pallet"></i> Palletised &amp; Bulk</div>
                <div class="wh-trust-i"><i class="fas fa-temperature-low"></i> Climate Controlled</div>
                <div class="wh-trust-i"><i class="fas fa-expand-arrows-alt"></i> Scalable Terms</div>
                <div class="wh-trust-i"><i class="fas fa-shield-alt"></i> 24/7 CCTV</div>
                <div class="wh-trust-i"><i class="fas fa-truck-moving"></i> Moving Support</div>
            </div>
        </div>
    </div>

    <!-- ============ OVERVIEW ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="wh-split sk-reveal in">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Warehouse Storage Solutions for Businesses Across the UAE</h2>
                    <p>As businesses grow, managing inventory becomes just as important as managing sales. Stock, equipment and incoming shipments can quickly occupy valuable office or retail space — and leasing an entire facility isn't always the right answer when you only need extra capacity for inventory or seasonal demand.</p>
                    <p>Storage Keys provides flexible warehouse storage for businesses that need secure, accessible and cost-effective space. Our facility in Sharjah supports businesses across Dubai, Sharjah and the wider UAE with scalable units that adapt to changing operational needs — extra capacity in busy seasons, or a long-term home for commercial inventory, without the commitment of a traditional warehouse lease.</p>
                    <p>We work closely with each client to size their unit correctly from the start, so businesses never end up paying for space they don't use.</p>
                    <div class="wh-pills">
                        <span>Retailers</span><span>Wholesalers</span><span>Manufacturers</span><span>Distributors</span><span>E-commerce</span>
                    </div>
                </div>
                <div class="wh-media" style="background-image:url('images/overview.jpg');"></div>
            </div>
        </div>
    </section>

    <!-- ============ WHAT IS WAREHOUSE STORAGE ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="wh-split rev sk-reveal in">
                <div class="wh-media" style="background-image:url('images/what-is.jpg');"></div>
                <div>
                    <span class="sk-eyebrow">The Basics</span>
                    <h2>What Is Warehouse Storage?</h2>
                    <p>Warehouse storage is a commercial storage solution for businesses that need more space than a standard unit — ideal for palletised goods, bulk inventory, commercial equipment and other business assets in a secure, organised environment.</p>
                    <p>Unlike renting a complete industrial warehouse, this kind of solution lets you lease only the space you require — greater flexibility while reducing operating costs, for growing companies and established organisations alike.</p>
                    <ul class="wh-points">
                        <li><i class="fas fa-check-circle"></i> Lease only the space you need — not a whole industrial warehouse.</li>
                        <li><i class="fas fa-check-circle"></i> Systematic organisation makes stock easy to receive, locate and distribute.</li>
                        <li><i class="fas fa-check-circle"></i> Flex space up or down with order volumes — a buffer against uncertainty.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ OUR SOLUTIONS ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">What We Offer</span>
                <h2>Our Warehouse Storage Solutions</h2>
                <p>Some businesses need space for large shipments, others need climate-controlled conditions or flexible capacity through the year. Our solutions support a wide range of commercial storage needs.</p>
            </div>
            <div class="wh-sol sk-reveal in">
                <div class="wh-solc"><div class="ic"><i class="fas fa-boxes"></i></div><div><h3>Bulk Inventory Storage</h3><p>Ideal for businesses managing high stock volumes — keep inventory organised, protected and easily accessible without occupying valuable commercial space.</p></div></div>
                <div class="wh-solc"><div class="ic"><i class="fas fa-pallet"></i></div><div><h3>Pallet Storage</h3><p>Secure units for palletised goods, helping businesses organise products efficiently while improving stock handling and day-to-day operations.</p></div></div>
                <div class="wh-solc"><div class="ic"><i class="fas fa-temperature-low"></i></div><div><h3>Climate-Controlled Storage</h3><p>Temperature-controlled space that helps protect electronics, cosmetics, documents and other products from heat and humidity.</p></div></div>
                <div class="wh-solc"><div class="ic"><i class="fas fa-calendar-alt"></i></div><div><h3>Temporary &amp; On-Demand Storage</h3><p>Flexible space for seasonal inventory, business relocations and project-based needs — without long-term commitments.</p></div></div>
            </div>
        </div>
    </section>

    <!-- ============ CAPACITY ESTIMATOR (INTERACTIVE) ============ -->
    <section class="sk-section" id="estimator" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Unit Types</span>
                <h2>Estimate the Warehouse Space You Need</h2>
                <p>Choosing the right unit means you never pay for unused capacity. Drag the slider to your approximate volume and we'll match it to a unit type.</p>
            </div>
            <div class="wh-est sk-reveal">
                <div class="wh-est-top">
                    <label for="whRange">How much do you need to store?</label>
                    <div class="wh-est-val"><span id="whPallets">6</span> <small>pallets (approx.)</small></div>
                </div>
                <input type="range" min="1" max="40" value="6" class="wh-range" id="whRange">
                <div class="wh-scale"><span>1</span><span>10</span><span>20</span><span>30</span><span>40+</span></div>
                <div class="wh-est-result">
                    <div>
                        <div class="lead">Recommended unit</div>
                        <h3 id="whType">Medium Storage Space</h3>
                        <p id="whDesc">A practical option for growing businesses storing palletised goods, retail inventory or business equipment.</p>
                    </div>
                    <a href="#warehouse-quote" class="sk-btn sk-btn-primary" id="whQuote"><i class="fas fa-file-invoice-dollar"></i> Quote This Size</a>
                </div>
                <div class="wh-types">
                    <div class="wh-type" data-min="1" data-max="3">
                        <div class="h"><i class="fas fa-box"></i><h4>Small Units</h4></div>
                        <span class="cap">1–3 pallets</span>
                        <p>Archived records, boxed inventory, office equipment and smaller commercial stock.</p>
                    </div>
                    <div class="wh-type active" data-min="4" data-max="10">
                        <div class="h"><i class="fas fa-boxes"></i><h4>Medium Space</h4></div>
                        <span class="cap">4–10 pallets</span>
                        <p>Palletised goods, retail inventory or business equipment for growing businesses.</p>
                    </div>
                    <div class="wh-type" data-min="11" data-max="25">
                        <div class="h"><i class="fas fa-warehouse"></i><h4>Large Units</h4></div>
                        <span class="cap">11–25 pallets</span>
                        <p>Substantial capacity for wholesalers, distributors and companies with bulk inventory.</p>
                    </div>
                    <div class="wh-type" data-min="26" data-max="99">
                        <div class="h"><i class="fas fa-cubes"></i><h4>Custom Solutions</h4></div>
                        <span class="cap">26+ pallets</span>
                        <p>Scalable space tailored to specialised inventory, changing requirements or unique needs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FACILITY ============ -->
    <section class="sk-section wh-feat">
        <div class="sk-container">
            <div class="wh-split sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Built for Operations</span>
                    <h2>Facilities Built for Efficient Operations</h2>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;">The right facility does more than provide space — it helps you organise inventory, improve accessibility and protect valuable goods throughout the storage period. Our layouts simplify receiving, storing and retrieving stock, and can accommodate racking to make better use of every square foot.</p>
                    <ul class="wh-points">
                        <li><i class="fas fa-check-circle"></i> Storage racks, industrial racking &amp; shelving for easy access</li>
                        <li><i class="fas fa-check-circle"></i> Organised layouts that simplify receiving, storing &amp; retrieval</li>
                        <li><i class="fas fa-check-circle"></i> Climate-controlled environments protect goods from heat &amp; humidity</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 CCTV surveillance across the facility</li>
                        <li><i class="fas fa-check-circle"></i> Controlled access for a secure storage environment</li>
                    </ul>
                </div>
                <div class="wh-media" style="background-image:url('images/facility.jpg');"></div>
            </div>
        </div>
    </section>

    <!-- ============ INDUSTRIES ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Who It's For</span>
                <h2>Industries That Benefit from Warehouse Storage</h2>
                <p>A practical solution across a wide range of industries — extra capacity to support growth without leasing an entire warehouse.</p>
            </div>
            <div class="wh-ind sk-reveal">
                <div class="wh-indc"><div class="ic"><i class="fas fa-store"></i></div><h4>Retail &amp; E-Commerce</h4><p>Manage growing inventory and prepare for seasonal demand without overcrowding stores.</p></div>
                <div class="wh-indc"><div class="ic"><i class="fas fa-ship"></i></div><h4>Importers &amp; Distributors</h4><p>Hold incoming shipments in organised facilities before delivery to customers or retail outlets.</p></div>
                <div class="wh-indc"><div class="ic"><i class="fas fa-industry"></i></div><h4>Manufacturers</h4><p>Extra space for raw materials, finished products and equipment as production scales.</p></div>
                <div class="wh-indc"><div class="ic"><i class="fas fa-hard-hat"></i></div><h4>Contractors</h4><p>Keep tools, machinery and project materials secure between jobs.</p></div>
                <div class="wh-indc"><div class="ic"><i class="fas fa-boxes"></i></div><h4>Wholesalers</h4><p>Substantial capacity for bulk inventory, ready to distribute when orders come in.</p></div>
                <div class="wh-indc"><div class="ic"><i class="fas fa-warehouse"></i></div><h4>Growing Businesses</h4><p>Flexible capacity that scales with company growth — pay only for what you use.</p></div>
            </div>
        </div>
    </section>

    <!-- ============ VS LEASING (INTERACTIVE TOGGLE) ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Compare</span>
                <h2>Warehouse Storage vs Traditional Warehouse Leasing</h2>
                <p>Warehouse storage sits in between overcrowded premises and a full building lease — the security and scale of a dedicated facility, without the fixed overheads and long contracts. Flip the switch to compare.</p>
            </div>
            <div class="wh-toggle sk-reveal">
                <div class="wh-switch" id="whSwitch">
                    <button type="button" data-v="lease">Full warehouse lease</button>
                    <button type="button" class="active win" data-v="unit">A Storage Keys unit</button>
                </div>
            </div>
            <div class="sk-reveal">
                <div class="wh-vspanel" data-v="lease">
                    <div class="wh-vscard lose">
                        <div class="tophd"><div class="ic"><i class="fas fa-building"></i></div><h3>Leasing a whole warehouse</h3></div>
                        <ul>
                            <li><i class="fas fa-times"></i> Long-term contracts and fixed overheads</li>
                            <li><i class="fas fa-times"></i> Maintenance responsibilities for the whole building</li>
                            <li><i class="fas fa-times"></i> Paying for space that may sit unused part of the year</li>
                            <li><i class="fas fa-times"></i> Overcommitting before your inventory needs are clear</li>
                        </ul>
                    </div>
                </div>
                <div class="wh-vspanel active" data-v="unit">
                    <div class="wh-vscard win">
                        <div class="tophd"><div class="ic"><i class="fas fa-check"></i></div><h3>A scalable Storage Keys unit</h3></div>
                        <ul>
                            <li><i class="fas fa-check"></i> Security and scale of a dedicated facility</li>
                            <li><i class="fas fa-check"></i> No fixed overheads, long contracts or maintenance</li>
                            <li><i class="fas fa-check"></i> Scale up or down with seasons, launches and growth</li>
                            <li><i class="fas fa-check"></i> Pay only for what you're actually using at any time</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose Storage Keys for Warehouse Storage?</h2>
                <p>More than available space — a team that understands how businesses manage inventory and adapts as your requirements change.</p>
            </div>
            <div class="wh-why sk-reveal">
                <div class="wh-whyc"><div class="ic"><i class="fas fa-expand-arrows-alt"></i></div><h4>Flexible Rental Terms</h4><p>Increase or reduce your units as inventory levels change — never locked into space you don't need.</p></div>
                <div class="wh-whyc"><div class="ic"><i class="fas fa-temperature-low"></i></div><h4>Climate-Controlled Facilities</h4><p>Protect valuable assets from heat and humidity while keeping operations running efficiently.</p></div>
                <div class="wh-whyc"><div class="ic"><i class="fas fa-sitemap"></i></div><h4>Organised Layouts</h4><p>Well-planned space and racking that keeps stock accessible and easy to manage.</p></div>
                <div class="wh-whyc"><div class="ic"><i class="fas fa-truck-moving"></i></div><h4>Professional Moving Support</h4><p>Help getting goods in and out, so storage stays a simple handover for your team.</p></div>
                <div class="wh-whyc"><div class="ic"><i class="fas fa-shield-alt"></i></div><h4>Secure Storage</h4><p>24/7 CCTV and controlled access protect commercial goods throughout the rental period.</p></div>
                <div class="wh-whyc"><div class="ic"><i class="fas fa-calendar-check"></i></div><h4>Short or Long Term</h4><p>Temporary overflow or long-term warehouse storage — practical options built around your business.</p></div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">FAQs</span>
                <h2>FAQs About Warehouse Storage</h2>
            </div>
            <div class="wh-faq">
                <details open>
                    <summary>What is warehouse storage used for?</summary>
                    <div class="a">It's ideal for businesses storing bulk inventory, palletised goods, commercial equipment and other large quantities of stock that require organised and secure storage space.</div>
                </details>
                <details>
                    <summary>Can I rent storage units for a short period?</summary>
                    <div class="a">Yes. We offer temporary storage solutions with flexible rental terms, allowing businesses to rent space for seasonal demand, relocation projects or short-term commercial requirements.</div>
                </details>
                <details>
                    <summary>Is your storage facility secure?</summary>
                    <div class="a">Yes. Our facilities include 24/7 CCTV surveillance, controlled access and climate-controlled environments to help protect inventory and commercial goods throughout the rental period.</div>
                </details>
                <details>
                    <summary>What types of businesses use warehouse storage?</summary>
                    <div class="a">Retailers, wholesalers, manufacturers, distributors, importers, contractors and e-commerce businesses all benefit from flexible storage solutions for inventory, equipment and commercial goods.</div>
                </details>
                <details>
                    <summary>Can I increase my storage space later?</summary>
                    <div class="a">Absolutely. As your inventory grows, your storage unit can be adjusted to match your changing business requirements, ensuring you only pay for the space you need.</div>
                </details>
                <details>
                    <summary>Do you provide warehouse storage in Dubai?</summary>
                    <div class="a">Yes. Our facility serves businesses across Dubai, Sharjah and the wider UAE with flexible storage solutions for both short-term and long-term requirements.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE ============ -->
    <section class="sk-section" id="warehouse-quote">
        <div class="sk-container">
            <div class="wh-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Get Your Storage Quote Today</h2>
                    <p>Looking for secure, flexible warehouse storage in Dubai or Sharjah? Whether you need a single unit or a larger custom arrangement, tell us what you need to store and our team will recommend the right solution for your business.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                <form class="wh-form" action="#" method="GET">
                    <h3>Request your free quote</h3>
                    <div class="wh-frow">
                        <div class="wh-field"><label>Company name</label><input type="text" name="company" placeholder="Your company"></div>
                        <div class="wh-field"><label>Contact name</label><input type="text" name="name" placeholder="Your name" required></div>
                    </div>
                    <div class="wh-frow">
                        <div class="wh-field"><label>Phone / WhatsApp</label><input type="tel" name="phone" placeholder="+971 __ ___ ____" required></div>
                        <div class="wh-field"><label>Email</label><input type="email" name="email" placeholder="you@company.com"></div>
                    </div>
                    <div class="wh-frow">
                        <div class="wh-field">
                            <label>What are you storing?</label>
                            <select name="storing">
                                <option value="bulk">Bulk inventory</option>
                                <option value="pallets">Palletised goods</option>
                                <option value="equipment">Commercial equipment</option>
                                <option value="climate">Climate-sensitive stock</option>
                                <option value="mixed">A mix of the above</option>
                            </select>
                        </div>
                        <div class="wh-field">
                            <label>Approx. volume</label>
                            <select name="volume" id="whFormVolume">
                                <option value="small">Small (1–3 pallets)</option>
                                <option value="medium" selected>Medium (4–10 pallets)</option>
                                <option value="large">Large (11–25 pallets)</option>
                                <option value="custom">Custom (26+ pallets)</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="sk-btn sk-btn-primary"><i class="fas fa-paper-plane"></i> Request Free Quote</button>
                </form>
            </div>
        </div>
    </section>
    
@endsection