@extends('ui.layouts.frontend')
@section('title', 'Furniture Storage')
@section('metaTitle', 'Furniture Storage in UAE for Homes & Businesses - StorageKeys')
@section('metaDescription', 'Make room with furniture storage in the UAE for sofas, beds, tables and more. Keep your belongings protected during moves or renovations. Get a quote!')
@section('content')


<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Furniture Storage</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Furniture Storage</span>
            <h1>Furniture Storage in <span>Dubai &amp; Sharjah</span></h1>
            <p class="lead">Flexible furniture storage for homes and businesses, available for short- and long-term needs.</p>
            <div class="ps-hero-cta">
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-couch"></i> Get a Free Quote</a>
                <a href="#estimator" class="sk-btn sk-btn-ghost"><i class="fas fa-sliders-h"></i> Estimate Your Space</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-home"></i> Homes &amp; Businesses</span>
                <span class="ps-hbadge"><i class="fas fa-calendar-check"></i> Short &amp; Long-Term</span>
                <span class="ps-hbadge"><i class="fas fa-wrench"></i> Dismantling Support</span>
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
                    <h2>Furniture Self Storage for Homes &amp; Businesses</h2>
                    <p>Finding space for furniture can be difficult when you are moving, renovating, downsizing, relocating, or changing your business premises. Large items such as sofas, beds, wardrobes, dining tables, desks, and cabinets can quickly occupy rooms that you need for other purposes.</p>
                    <p>StorageKeys provides furniture self storage in Dubai and Sharjah for customers who need practical space without giving up useful furniture. Our furniture storage units can be used for individual items, the contents of selected rooms, or larger collections depending on your requirements.</p>
                    <p>Whether you need temporary furniture storage during a home renovation or long term furniture storage for items you rarely use, you can choose a storage arrangement based on the amount of furniture you need to keep.</p>
                </div>
                <div class="ps-media" style="background-image:url('images/overview.jpg');"></div>
            </div>
        </div>
    </section>

    <!-- ============ FURNITURE SPACE ESTIMATOR ============ -->
    <section class="sk-section tool-section" id="estimator" style="background:var(--sk-soft);">
        <div class="sk-container wrap">
            <div class="sk-section-head tool-head">
                <span class="sk-eyebrow" style="justify-content:center;">Furniture Storage Units for Different Items</span>
                <h2>Estimate Your Furniture Storage Space</h2>
                <p>Furniture varies considerably in size, shape, and quantity, so the amount of space required will depend on what you plan to store. Tap the counters below to add what you're storing — we'll recommend a unit size as you go.</p>
            </div>

            <div class="estimator sk-reveal">
                <div class="cat-list" id="catList">
                    <div class="cat-row" data-weight="14">
                        <div class="cat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 13v5h16v-5M4 13a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3M6 10V8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"></path></svg></div>
                        <div class="cat-info">
                            <span class="h4">Sofas &amp; Seating</span>
                            <p>Sofas, armchairs, recliners and other seating cleared during a move, renovation or property transition.</p>
                        </div>
                        <div class="stepper">
                            <button type="button" class="step-btn" data-action="dec" aria-label="Decrease">−</button>
                            <span class="step-count">0</span>
                            <button type="button" class="step-btn" data-action="inc" aria-label="Increase">+</button>
                        </div>
                    </div>

                    <div class="cat-row" data-weight="10">
                        <div class="cat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="10" width="18" height="8" rx="1"></rect><path d="M5 10V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v3M3 18v2M21 18v2"></path></svg></div>
                        <div class="cat-info">
                            <span class="h4">Beds &amp; Mattresses</span>
                            <p>Beds, mattresses, headboards and related bedroom furnishings, useful during renovations or relocations.</p>
                        </div>
                        <div class="stepper">
                            <button type="button" class="step-btn" data-action="dec" aria-label="Decrease">−</button>
                            <span class="step-count">0</span>
                            <button type="button" class="step-btn" data-action="inc" aria-label="Increase">+</button>
                        </div>
                    </div>

                    <div class="cat-row" data-weight="12">
                        <div class="cat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="1"></rect><path d="M12 3v18M4 8h16M4 15h16"></path></svg></div>
                        <div class="cat-info">
                            <span class="h4">Wardrobes &amp; Cabinets</span>
                            <p>Wardrobes, dressers, cupboards and cabinets — bulky pieces that take up substantial room at home.</p>
                        </div>
                        <div class="stepper">
                            <button type="button" class="step-btn" data-action="dec" aria-label="Decrease">−</button>
                            <span class="step-count">0</span>
                            <button type="button" class="step-btn" data-action="inc" aria-label="Increase">+</button>
                        </div>
                    </div>

                    <div class="cat-row" data-weight="8">
                        <div class="cat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20V9m18 11V9M3 9h18M6 20v-6h12v6"></path></svg></div>
                        <div class="cat-info">
                            <span class="h4">Tables &amp; Desks</span>
                            <p>Dining tables, coffee tables, side tables and work desks — can often be dismantled to save space.</p>
                        </div>
                        <div class="stepper">
                            <button type="button" class="step-btn" data-action="dec" aria-label="Decrease">−</button>
                            <span class="step-count">0</span>
                            <button type="button" class="step-btn" data-action="inc" aria-label="Increase">+</button>
                        </div>
                    </div>

                    <div class="cat-row" data-weight="9">
                        <div class="cat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="12" rx="1"></rect><path d="M8 20h8M12 16v4"></path></svg></div>
                        <div class="cat-info">
                            <span class="h4">Office Furniture</span>
                            <p>Desks, office chairs, filing cabinets, shelving and reception furniture during a relocation or refit.</p>
                        </div>
                        <div class="stepper">
                            <button type="button" class="step-btn" data-action="dec" aria-label="Decrease">−</button>
                            <span class="step-count">0</span>
                            <button type="button" class="step-btn" data-action="inc" aria-label="Increase">+</button>
                        </div>
                    </div>

                    <div class="cat-row" data-weight="9">
                        <div class="cat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="9" width="16" height="6" rx="1"></rect><path d="M6 15v4M18 15v4M6 9V6h12v3"></path></svg></div>
                        <div class="cat-info">
                            <span class="h4">Dining Furniture</span>
                            <p>Dining tables, chairs and sideboards, stored separately or alongside other household furniture.</p>
                        </div>
                        <div class="stepper">
                            <button type="button" class="step-btn" data-action="dec" aria-label="Decrease">−</button>
                            <span class="step-count">0</span>
                            <button type="button" class="step-btn" data-action="inc" aria-label="Increase">+</button>
                        </div>
                    </div>
                </div>

                <div class="estimate-panel">
                    <span class="sk-eyebrow">Recommended unit</span>
                    <div class="unit-visual">
                        <div class="unit-fill" id="unitFill"></div>
                        <div class="unit-label">Unit occupancy</div>
                    </div>
                    <div class="rec-size" id="recSize">Add your items</div>
                    <p class="rec-desc" id="recDesc">Tap a counter on the left to start building your estimate — the fill shows roughly how much of a unit your furniture would take up.</p>
                    <a href="#ps-quote" class="sk-btn sk-btn-primary" id="fsQuoteBtn">Get a Quote for This Size</a>
                    <p class="item-total" id="itemTotal">0 items selected</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ MOVING & RENOVATION ============ -->
    <section class="sk-section fs-moving">
        <div class="sk-container">
            <div class="ps-split sk-reveal">
            <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_7.png') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Moving &amp; Renovation</span>
                    <h2>Storage for Furniture During Moving &amp; Renovation</h2>
                    <p>Furniture often needs to be moved before the rest of a property transition is complete. You may be leaving your current home before the new one is ready, renovating several rooms, or preparing a property for sale.</p>
                    <p>Furniture moving and storage provides a practical option in these situations. Instead of moving large items directly into a temporary property, they can be placed in storage until your next space is ready.</p>
                    <p class="fs-useful-label"><strong>Furniture storage can be useful when:</strong></p>
                    <div class="fs-use-grid">
                        <span class="fs-use"><i class="fas fa-check"></i> Moving between homes</span>
                        <span class="fs-use"><i class="fas fa-check"></i> Renovating or refurbishing a property</span>
                        <span class="fs-use"><i class="fas fa-check"></i> Downsizing to a smaller home</span>
                        <span class="fs-use"><i class="fas fa-check"></i> Preparing a property for sale or rent</span>
                        <span class="fs-use"><i class="fas fa-check"></i> Relocating temporarily</span>
                        <span class="fs-use"><i class="fas fa-check"></i> Clearing space for interior work</span>
                        <span class="fs-use"><i class="fas fa-check"></i> Moving or renovating an office</span>
                    </div>
                    <p>For shorter projects, temporary furniture storage gives you somewhere to keep furniture while work is underway. For customers who need space beyond a renovation or move, longer-term options can provide a suitable arrangement.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ============ PLAN YOUR SPACE (INTERACTIVE CHECKLIST) ============ -->
    <section class="sk-section fs-plan" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="fs-plan-grid sk-reveal">
                <div class="fs-plan-copy">
                    <span class="sk-eyebrow">Plan Your Space</span>
                    <h2>How Much Furniture Storage Space Do You Need?</h2>
                    <p>The right unit depends on the overall volume of your furniture rather than simply the number of pieces. A large sectional sofa, wardrobe, and dining table can require considerable space even when you only have a few individual items.</p>
                    <p>Before selecting one of our furniture storage units, work through the checklist — each point helps narrow down the right size.</p>
                    <p class="fs-plan-callout">If you're unsure how much furniture storage space you need, StorageKeys can help you assess your requirements based on the type and quantity of furniture you plan to store.</p>
                </div>
                <div class="fs-plan-check">
                    <div class="ps-progress">
                        <div class="bar"><i id="psBar" style="width:0%;"></i></div>
                        <span class="lbl"><span id="psDone">0</span> of 5 considered</span>
                    </div>
                    <ul class="ps-check" id="psCheck">
                        <li><span class="box"><i class="fas fa-check"></i></span><span class="t">The dimensions of your largest furniture pieces</span></li>
                        <li><span class="box"><i class="fas fa-check"></i></span><span class="t">The number of beds, wardrobes, sofas, tables, and cabinets</span></li>
                        <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Whether larger items can be dismantled</span></li>
                        <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Whether boxes or other belongings will be stored with the furniture</span></li>
                        <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Whether you need to access specific items while they're stored</span></li>
                    </ul>
                    <p class="fs-plan-foot mt-4">Dismantling suitable furniture can make better use of the available space, while keeping items organised can make future access easier.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHERE WE SERVE (SPLIT) ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-split rev sk-reveal">
                <div class="ps-media" style="background-image:url('images/home-storage.jpg');"></div>
                <div>
                    <span class="sk-eyebrow">Where We Serve</span>
                    <h2>Furniture Storage Facilities in Dubai &amp; Sharjah</h2>
                    <p>When comparing furniture storage companies in Dubai, the unit size is only one part of the decision. The facility should also provide an appropriate environment for the type and quantity of furniture you plan to store.</p>
                    <p>StorageKeys offers storage solutions for customers looking for furniture storage facilities in Dubai and Sharjah. Our locations make it easier for households and businesses to create additional space without renting a larger property solely to accommodate furniture.</p>
                    <p>If you are comparing furniture storage companies in Dubai, consider the amount of furniture you have, how long it needs to remain stored, and how much space you actually require.</p>
                    <p>The same applies when looking for furniture storage solutions in Sharjah or Dubai. Choosing a unit based on your actual inventory can help you avoid paying for unnecessary capacity.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FACILITIES FOR HOMES & BUSINESSES ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Facilities for Every Customer</span>
                <h2>Furniture Storage Facilities for Homes &amp; Businesses</h2>
                <p>StorageKeys provides furniture storage facilities for households and businesses that need additional space for furniture that is temporarily or no longer required on their premises. This can be useful during property changes, office moves, renovations, downsizing, or periods when extra furniture needs to be kept elsewhere.</p>
            </div>
            <div class="ps-cover sk-reveal">
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-home"></i></div>
                    <div>
                        <span class="h4">For Homeowners</span>
                        <p>Furniture storage facilities can provide space for sofas, beds, wardrobes, dining sets, and other household items between properties or while a home is being changed.</p>
                    </div>
                </div>
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <div>
                        <span class="h4">For Businesses</span>
                        <p>Businesses can use the space for surplus desks, chairs, cabinets, shelving, and other office storage furniture without occupying valuable workspace.</p>
                    </div>
                </div>
            </div>
            <p class="sk-reveal text-center mt-4">With flexible storage for furniture, StorageKeys offers a practical way to keep usable items available without allowing them to take over your home, office, or commercial premises.</p>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section">
        <div class="sk-container ">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose StorageKeys for Furniture Storage?</h2>
                <p>StorageKeys provides flexible furniture self storage for customers who need to create space without permanently giving up furniture they may need later. Our storage solutions are suitable for:</p>
            </div>
            <div class="ps-why sk-reveal">
                <div class="ps-whyc"><div class="ic"><i class="fas fa-couch"></i></div><span class="h4">Household Furniture</span><p>Sofas, beds, wardrobes and dining sets during moves and renovations.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-bed"></i></div><span class="h4">Bedroom &amp; Living Room Pieces</span><p>Bedroom and living room furniture kept safe until you need it again.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-briefcase"></i></div><span class="h4">Office &amp; Commercial</span><p>Office and commercial furnishings during relocations or refits.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-hourglass-half"></i></div><span class="h4">Temporary Requirements</span><p>Short-term storage that bridges the gap between moving dates.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-calendar-check"></i></div><span class="h4">Longer-Term Storage</span><p>Reliable space for furniture you don't need every day but want to keep.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-box-open"></i></div><span class="h4">Surplus Furniture</span><p>A home for items that are not currently in use, without giving them up.</p></div>
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
                    <summary>What is furniture self storage?</summary>
                    <div class="a">Furniture self storage allows you to keep household or commercial furniture in a dedicated unit instead of occupying space in your home, office, or another property. It can be used for both short- and long-term storage requirements.</div>
                </details>
                <details>
                    <summary>How much does furniture storage in Dubai cost?</summary>
                    <div class="a">The furniture storage cost depends mainly on the amount of space required and how long you need the unit. The quantity and size of your furniture will determine which storage option is most suitable.</div>
                </details>
                <details>
                    <summary>What size furniture storage unit do I need?</summary>
                    <div class="a">The required unit size depends on the dimensions and quantity of your furniture. Sofas, wardrobes, beds, dining tables, and cabinets should all be considered when estimating how much space you need.</div>
                </details>
                <details>
                    <summary>Can I store furniture during a home renovation or move?</summary>
                    <div class="a">Yes. Furniture storage can provide temporary space while your home is being renovated or while you are between properties. Your furniture can remain stored until your new or renovated space is ready.</div>
                </details>
                <details>
                    <summary>Can I use furniture storage for short-term and long-term needs?</summary>
                    <div class="a">Yes. Temporary furniture storage can be suitable for moves, renovations, and short projects, while long term furniture storage can provide space for furniture that you do not currently need but want to keep.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="ps-quote">
        <div class="sk-container">
            <div class="ps-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#fff;">Free Quote</span>
                    <h2>Get a Free Furniture Storage Quote Today</h2>
                    <p>Tell us what you need to store and we'll recommend the right unit size — with dismantling and moving support available if you need it.</p>
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
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ps-quote"><i class="fas fa-couch"></i> Quote</a>
    </div>

</div>
@endsection
