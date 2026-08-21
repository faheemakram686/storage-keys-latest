@extends('ui.layouts.frontend')
@section('title', 'Box Storage in Dubai & Sharjah | Storage Keys')
@section('metaTitle', 'Secure Box Storage for Homes & Businesses | StorageKeys')
@section('metaDescription', 'Store packed belongings, household items, documents and business stock with flexible box storage in Dubai and Sharjah. Short- or long-term. Get a free quote.')
@section('content')


<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Box Storage</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Box Storage</span>
            <h1>Box Storage Solutions in <span>Dubai &amp; Sharjah</span></h1>
            <p class="lead">When you need a practical place to keep packed belongings, box storage provides a simple and flexible solution — without filling your home, office, or business premises with boxes.</p>
            <div class="ps-hero-cta">
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-box"></i> Get Box Storage Space</a>
                <a href="#ps-builder" class="sk-btn sk-btn-ghost"><i class="fas fa-list-check"></i> What Can You Store?</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-home"></i> Household Boxes</span>
                <span class="ps-hbadge"><i class="fas fa-briefcase"></i> Business Boxes</span>
                <span class="ps-hbadge"><i class="fas fa-calendar-check"></i> Short &amp; Long-Term</span>
                <span class="ps-hbadge"><i class="fas fa-truck"></i> Moves &amp; Renovations</span>
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
            <div class="ps-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Practical Box Storage for Packed Belongings</h2>
                    <p>When you need a practical place to keep packed belongings, box storage provides a simple and flexible solution. Instead of filling your home, office, or business premises with boxes, you can move them into a dedicated storage facility and keep your space organized.</p>
                    <p>StorageKeys provides secure box storage facilities for personal belongings, household items, business stock, documents, seasonal items, and other packed goods. Whether you need storage for a few boxes or have a larger quantity to store, you can choose a solution that fits your requirements.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Store a few boxes or larger packed collections</li>
                        <li><i class="fas fa-check-circle"></i> Suitable for homes and businesses</li>
                        <li><i class="fas fa-check-circle"></i> Short-term and long-term options</li>
                    </ul>
                </div>
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/Box.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ SOLUTIONS FOR DIFFERENT NEEDS ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Flexible Options</span>
                <h2>Box Storage Solutions for Different Needs</h2>
                <p>Our box storage solutions are suitable for customers who need additional space without committing to a large storage unit. You can store individual boxes, multiple cartons, or packed belongings that are not currently needed.</p>
            </div>
            <div class="ps-term sk-reveal">
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-home"></i></div>
                    <span class="h3">Household Box Storage</span>
                    <p>Moving home, renovating, travelling, or simply running out of space can leave you with boxes that need somewhere to go. Household box storage gives you a dedicated place to keep clothes, books, kitchen items, decorations, personal belongings, and other packed household goods.</p>
                </div>
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <span class="h3">Business Box Storage</span>
                    <p>Businesses can use box storage for documents, inventory, promotional materials, office supplies, packaging, and other items that do not need to occupy valuable workspace. It is also useful for businesses that experience seasonal changes in stock or require additional space temporarily.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHAT CAN YOU STORE (INTERACTIVE BUILDER) ============ -->
    <section class="sk-section" id="ps-builder">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Packed Belongings</span>
                <h2>What Can You Store in Boxes?</h2>
                <p>Box storage can accommodate a wide range of properly packed belongings. The type and quantity of items you can store will depend on their size, condition, and storage requirements. Tap what you plan to store and we’ll pass the list to our team.</p>
            </div>
            <div class="ps-items sk-reveal" id="psItems">
                <button type="button" class="ps-item" data-item="Household belongings and personal items"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-home"></i></div><span class="h4">Household &amp; Personal</span></button>
                <button type="button" class="ps-item" data-item="Books, files, and documents"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-book"></i></div><span class="h4">Books &amp; Documents</span></button>
                <button type="button" class="ps-item" data-item="Seasonal decorations"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-gift"></i></div><span class="h4">Seasonal Decorations</span></button>
                <button type="button" class="ps-item" data-item="Clothing and accessories"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-tshirt"></i></div><span class="h4">Clothing &amp; Accessories</span></button>
                <button type="button" class="ps-item" data-item="Office supplies"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-paperclip"></i></div><span class="h4">Office Supplies</span></button>
                <button type="button" class="ps-item" data-item="Business inventory and stock"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-boxes"></i></div><span class="h4">Business Inventory</span></button>
                <button type="button" class="ps-item" data-item="Packaging materials &amp; retail products"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-box-open"></i></div><span class="h4">Packaging &amp; Retail</span></button>
                <button type="button" class="ps-item" data-item="Small equipment and relocation items"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-tools"></i></div><span class="h4">Equipment &amp; Moves</span></button>
            </div>
            <div class="ps-cart sk-reveal">
                <div class="txt"><b><span id="psCount">0</span> item type(s)</b> selected<small id="psList">Tap the items above to build your list.</small></div>
                <a href="#ps-quote" class="sk-btn sk-btn-primary" id="psCartBtn"><i class="fas fa-paper-plane"></i> Get a Quote for These</a>
            </div>
            <p class="fs-facilities-note sk-reveal" style="margin-top:28px;">Properly packing and labelling your boxes can also make it easier to identify your belongings when you need them.</p>
        </div>
    </section>

    <!-- ============ PACKING CHECKLIST (INTERACTIVE) ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Be Prepared</span>
                <h2>Tips for Packing Boxes Before Storage</h2>
                <p>A little preparation keeps packed belongings organised and protected — and makes items easier to find later. Tick each step as you go.</p>
            </div>
            <div class="ps-check-wrap sk-reveal">
                <div class="ps-progress">
                    <span class="lbl"><span id="psDone">0</span> of 6 done</span>
                    <div class="bar"><i id="psBar" style="width:0%;"></i></div>
                </div>
                <ul class="ps-check" id="psCheck">
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Use sturdy boxes and quality packing tape for added protection.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Label every box clearly to identify its contents quickly.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Wrap fragile items individually with bubble wrap or packing paper.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Avoid overloading boxes to make lifting safer and prevent damage.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Group similar items together for easier retrieval later.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Keep a simple inventory of stored boxes and contents.</span></li>
                </ul>
                <div class="ps-check-done" id="psCheckDone"><i class="fas fa-circle-check"></i> Nicely done — your boxes are ready for storage! Need packing supplies? <a href="{{ url('/shop') }}" style="color:#1f9d57;text-decoration:underline;">Browse our shop</a>.</div>
            </div>
        </div>
    </section>

    <!-- ============ SECURE FACILITIES (NAVY SPLIT) ============ -->
    <section class="sk-section ps-feat">
        <div class="sk-container">
            <div class="ps-split sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Security First</span>
                    <h2>Secure Box Storage Facilities</h2>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;">Keeping your belongings in a dedicated box storage facility can be more practical than leaving packed boxes in an unused room, garage, office, or commercial space. StorageKeys provides a controlled storage environment designed to keep your belongings stored neatly and securely.</p>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;">Our facility is suitable for both personal and business storage requirements, giving you an alternative to keeping boxes in spaces that may no longer have enough room.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Secure access controls &amp; CCTV surveillance</li>
                        <li><i class="fas fa-check-circle"></i> Clean, organised storage environment</li>
                        <li><i class="fas fa-check-circle"></i> Suitable for personal and business boxes</li>
                        <li><i class="fas fa-check-circle"></i> Flexible access arrangements</li>
                    </ul>
                </div>
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_6.png') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ ORGANIZED + FLEXIBLE ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-term sk-reveal">
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-th-large"></i></div>
                    <span class="h3">Organized Storage Space</span>
                    <p>A dedicated storage area helps keep your boxes away from everyday household or workplace activity. This can be particularly useful when you have multiple boxes that need to remain stored for an extended period.</p>
                </div>
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-calendar-alt"></i></div>
                    <span class="h3">Flexible Short-Term or Long-Term</span>
                    <p>You may need box storage for a few weeks while moving, several months during a renovation, or longer for belongings that you do not currently use. With flexible options, you can select a solution based on how much you need to store and how long you need the space.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ MOVING & RENOVATION (SPLIT) ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ps-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Life Transitions</span>
                    <h2>Storage During Moving and Renovation</h2>
                    <p>Moving between homes or renovating an existing property can create a temporary need for extra space. Rather than keeping packed boxes around your home, you can move them into a dedicated storage facility until your space is ready.</p>
                    <a href="#ps-quote" class="sk-btn sk-btn-outline"><i class="fas fa-truck"></i> Store Boxes During Your Move</a>
                </div>
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_7.png') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ BUSINESS BOX STORAGE (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-split rev sk-reveal">
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_3.png') }}');"></div>
                <div>
                    <span class="sk-eyebrow">For Businesses</span>
                    <h2>Box Storage for Businesses</h2>
                    <p>Businesses often accumulate boxes containing inventory, records, supplies, and products. Keeping everything on-site can take up valuable office, retail, or operational space.</p>
                    <p>Business box storage provides an option for keeping these items outside your primary premises while maintaining a more organized workplace. It can also support businesses that need additional space during stock increases, office moves, seasonal demand, or expansion.</p>
                    <p>For businesses with larger storage requirements, StorageKeys can provide scalable solutions beyond individual boxes.</p>
                    <a href="#ps-quote" class="sk-btn sk-btn-outline"><i class="fas fa-briefcase"></i> Business Box Storage Quote</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose StorageKeys for Box Storage?</h2>
                <p>Choosing the right facility is important when you are storing belongings outside your home or business. StorageKeys offers storage solutions designed around different quantities, storage periods, and customer requirements.</p>
            </div>
            <div class="ps-why sk-reveal">
                <div class="ps-whyc"><div class="ic"><i class="fas fa-expand-arrows-alt"></i></div><span class="h4">Flexible Storage Options</span><p>Choose a solution based on how many boxes you have and how long you need the space.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-warehouse"></i></div><span class="h4">Dedicated Storage Space</span><p>Keep your boxes in one place instead of spreading them across different areas of your property.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-users"></i></div><span class="h4">Personal &amp; Business</span><p>Solutions for personal belongings and business stock, documents, and supplies.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-clock"></i></div><span class="h4">Short-Term &amp; Long-Term</span><p>Suitable for temporary moves and renovations, or ongoing personal and business use.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-th"></i></div><span class="h4">Organized Environment</span><p>A controlled facility designed to keep packed belongings stored neatly and securely.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-layer-group"></i></div><span class="h4">Scalable for Larger Quantities</span><p>When you outgrow a few boxes, we can provide scalable options for larger collections.</p></div>
            </div>
            <p class="fs-facilities-note sk-reveal">With dedicated storage space and options for both personal and business use, you can keep your boxes in one place instead of spreading them across different areas of your property.</p>
        </div>
    </section>

    <!-- ============ FIND THE RIGHT OPTION ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Right-Sized Space</span>
                <h2>Find the Right Box Storage Option</h2>
                <p>Whether you have a few boxes from a home move or a larger collection of business stock, choosing the right amount of storage space helps you avoid paying for unnecessary capacity.</p>
            </div>
            <div class="ps-help sk-reveal">
                <div>
                    <span class="h3">Not sure how much space you need?</span>
                    <p>StorageKeys can help you determine a suitable box storage solution based on the number and type of boxes you need to store. You can keep your belongings stored away while freeing up useful space at home, in the office, or on your business premises.</p>
                </div>
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-comments"></i> Help Me Choose</a>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="ps-quote" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ps-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#fff;">Get Box Storage Space</span>
                    <h2>Ready to Move Your Boxes Out?</h2>
                    <p>Ready to move your boxes out of your home or workplace? Contact StorageKeys to discuss your requirements and find a suitable storage solution for your belongings.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone-alt"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'variant' => 'simple',
                    'formClass' => 'sk-quote',
                    'fieldClass' => 'sk-field',
                    'title' => 'Request a Free Quote',
                    'submitLabel' => 'Request a Free Quote',
                    'source' => 'box-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Box Storage',
                    'showItemsField' => true,
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ps-quote"><i class="fas fa-box"></i> Quote</a>
    </div>

</div>
@endsection
