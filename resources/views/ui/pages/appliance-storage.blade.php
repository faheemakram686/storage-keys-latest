@extends('ui.layouts.frontend')
@section('title', 'Appliance Storage in Dubai & Sharjah | Storage Keys')
@section('metaTitle', 'Secure Appliance Storage for Homes & Businesses | StorageKeys')
@section('metaDescription', 'Store refrigerators, washers, ovens and commercial appliances with flexible appliance storage in Dubai and Sharjah. Short- or long-term. Get a free quote.')
@section('content')


<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Appliance Storage</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Appliance Storage</span>
            <h1>Appliance Storage for <span>Homes &amp; Businesses</span></h1>
            <p class="lead">Appliances can take up significant space when you are moving, renovating, downsizing, replacing equipment, or managing excess inventory. StorageKeys provides flexible appliance storage outside your current premises.</p>
            <div class="ps-hero-cta">
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-blender"></i> Store Your Appliances</a>
                <a href="#ps-builder" class="sk-btn sk-btn-ghost"><i class="fas fa-list-check"></i> What Can Be Stored?</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-home"></i> Household Appliances</span>
                <span class="ps-hbadge"><i class="fas fa-store"></i> Retail &amp; Business</span>
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
                    <h2>Flexible Appliance Storage Outside Your Premises</h2>
                    <p>Appliances can take up significant space when you are moving, renovating, downsizing, replacing equipment, or managing excess inventory. StorageKeys provides flexible appliance storage for households, retailers, property owners, and businesses that need a practical place to keep appliances outside their current premises.</p>
                    <p>Our storage solutions are suitable for individual appliances as well as larger quantities of household or commercial equipment. With flexible storage periods and secure facilities, you can keep your appliances protected and accessible without using valuable space at home, in an office, shop, or warehouse.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Individual appliances or larger quantities</li>
                        <li><i class="fas fa-check-circle"></i> Suitable for homes, retailers and businesses</li>
                        <li><i class="fas fa-check-circle"></i> Flexible storage periods</li>
                    </ul>
                </div>
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_5.png') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ HOMES AND BUSINESSES ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Who It's For</span>
                <h2>Appliance Storage for Homes and Businesses</h2>
                <p>Dedicated space for household equipment during life transitions, and for retailers and businesses managing excess inventory.</p>
            </div>
            <div class="ps-term sk-reveal">
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-home"></i></div>
                    <span class="h3">Storage During Home Moves and Renovations</span>
                    <p>Moving between properties or renovating your home can leave appliances without a suitable place to stay. Instead of moving everything repeatedly or keeping large appliances in inconvenient areas, dedicated storage provides a more practical option.</p>
                    <p>You can store refrigerators, washing machines, ovens, dishwashers, air conditioners, and other household appliances until your new space is ready.</p>
                </div>
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-store"></i></div>
                    <span class="h3">Storage for Businesses and Retailers</span>
                    <p>Businesses may also require additional space for appliances that are waiting to be delivered, installed, sold, or redistributed. Appliance storage facilities can help retailers, suppliers, property managers, and other businesses manage excess equipment without committing to additional commercial premises.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHAT CAN BE STORED (INTERACTIVE BUILDER) ============ -->
    <section class="sk-section" id="ps-builder">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Equipment Types</span>
                <h2>What Appliances Can Be Stored?</h2>
                <p>From everyday household equipment to commercial appliances — tap what you plan to store and we’ll pass the list to our team for a tailored quote.</p>
            </div>
            <div class="ps-items sk-reveal" id="psItems">
                <button type="button" class="ps-item" data-item="Refrigerators &amp; freezers"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-snowflake"></i></div><span class="h4">Refrigerators &amp; Freezers</span></button>
                <button type="button" class="ps-item" data-item="Washing machines &amp; dryers"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-tint"></i></div><span class="h4">Washers &amp; Dryers</span></button>
                <button type="button" class="ps-item" data-item="Ovens, dishwashers &amp; microwaves"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-utensils"></i></div><span class="h4">Ovens &amp; Dishwashers</span></button>
                <button type="button" class="ps-item" data-item="Air conditioners"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-thermometer-half"></i></div><span class="h4">Air Conditioners</span></button>
                <button type="button" class="ps-item" data-item="Smaller household appliances"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-blender"></i></div><span class="h4">Smaller Appliances</span></button>
                <button type="button" class="ps-item" data-item="Commercial appliances"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-industry"></i></div><span class="h4">Commercial Appliances</span></button>
                <button type="button" class="ps-item" data-item="Retail / inventory overflow"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-boxes"></i></div><span class="h4">Retail Inventory</span></button>
                <button type="button" class="ps-item" data-item="Other large appliances"><span class="chk"><i class="fas fa-check"></i></span><div class="ic"><i class="fas fa-ellipsis-h"></i></div><span class="h4">Something Else</span></button>
            </div>
            <div class="ps-cart sk-reveal">
                <div class="txt"><b><span id="psCount">0</span> appliance type(s)</b> selected<small id="psList">Tap the items above to build your list.</small></div>
                <a href="#ps-quote" class="sk-btn sk-btn-primary" id="psCartBtn"><i class="fas fa-paper-plane"></i> Get a Quote for These</a>
            </div>
            <div class="ps-cover sk-reveal" style="margin-top:40px;">
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-home"></i></div>
                    <div>
                        <span class="h4">Household Appliances</span>
                        <p>Refrigerators, freezers, washing machines, dryers, dishwashers, ovens, microwaves, and other larger appliances — plus smaller household appliances when extra space is required.</p>
                    </div>
                </div>
                <div class="ps-covc">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <div>
                        <span class="h4">Commercial and Large Appliances</span>
                        <p>Businesses can store larger quantities of equipment or commercial appliances for temporary or longer-term needs. Our team can help identify a suitable arrangement based on size, quantity, and type.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ SHORT / LONG TERM ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Unit Options</span>
                <h2>Appliance Storage Units for Different Needs</h2>
                <p>Choose short-term space for a limited period, or longer-term options when appliances need to remain stored for several months or more.</p>
            </div>
            <div class="ps-term sk-reveal">
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-hourglass-half"></i></div>
                    <span class="h3">Short-Term Appliance Storage</span>
                    <p>Useful when appliances only need to be kept for a limited period — home renovations, relocations, property staging, temporary inventory overflow, or delays between delivery and installation.</p>
                </div>
                <div class="ps-termc">
                    <div class="ic"><i class="fas fa-calendar-check"></i></div>
                    <span class="h3">Long-Term Appliance Storage</span>
                    <p>If appliances need to remain stored for several months or longer, StorageKeys provides flexible options based on the amount of space required — useful for businesses holding additional inventory or households storing appliances between property changes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ PREPARING APPLIANCES ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Be Prepared</span>
                <h2>Preparing Appliances for Storage</h2>
                <p>Proper preparation helps keep appliances in suitable condition while they are stored. Tick each step as you go.</p>
            </div>
            <div class="ps-check-wrap sk-reveal">
                <div class="ps-progress">
                    <span class="lbl"><span id="psDone">0</span> of 6 done</span>
                    <div class="bar"><i id="psBar" style="width:0%;"></i></div>
                </div>
                <ul class="ps-check" id="psCheck">
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Clean and dry appliances thoroughly before storage.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Disconnect power and water connections as required.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Follow manufacturer recommendations for storage preparation.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Secure doors, cables, shelves and removable parts.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Pay special attention to refrigerators, freezers, washers and dishwashers.</span></li>
                    <li><span class="box"><i class="fas fa-check"></i></span><span class="t">Protect finishes and reduce damage risk during handling and movement.</span></li>
                </ul>
                <div class="ps-check-done" id="psCheckDone"><i class="fas fa-circle-check"></i> Nicely done — your appliances are ready for storage! Need packing supplies? <a href="{{ url('/shop') }}" style="color:#1f9d57;text-decoration:underline;">Browse our shop</a>.</div>
            </div>
            <p class="fs-facilities-note sk-reveal" style="margin-top:28px;">Refrigerators, freezers, washing machines, and dishwashers may require particular preparation because of their internal components and water connections.</p>
        </div>
    </section>

    <!-- ============ SECURE FACILITIES (NAVY SPLIT) ============ -->
    <section class="sk-section ps-feat">
        <div class="sk-container">
            <div class="ps-split sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Secure Facilities</span>
                    <h2>Secure Appliance Storage Facilities</h2>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;"><strong style="color:#fff;">Space for Different Appliance Sizes</strong> — Appliances vary considerably in size. Whether you need space for a few household appliances or a larger collection of commercial equipment, the required storage area can be assessed according to your items.</p>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;"><strong style="color:#fff;">Suitable Storage Environment</strong> — Keeping appliances in an appropriate environment is important when they are not being used. StorageKeys provides a dedicated space away from the everyday activity and limited space of a home, office, retail outlet, or workshop.</p>
                    <p style="color:rgba(255,255,255,.82);font-size:15px;line-height:1.8;">Our appliance storage facilities are designed to provide a practical alternative to keeping bulky equipment in unsuitable areas.</p>
                    <ul class="ps-points">
                        <li><i class="fas fa-check-circle"></i> Secure access controls &amp; CCTV surveillance</li>
                        <li><i class="fas fa-check-circle"></i> Space assessed to your appliance sizes</li>
                        <li><i class="fas fa-check-circle"></i> Clean, dedicated storage environment</li>
                        <li><i class="fas fa-check-circle"></i> Flexible access arrangements</li>
                    </ul>
                </div>
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_6.png') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ FLEXIBLE SOLUTIONS ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-split rev sk-reveal">
                <div class="ps-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/web/Image_4.png') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Adapt as You Go</span>
                    <h2>Flexible Appliance Storage Solutions</h2>
                    <p>Storage needs can change over time. You may initially require space for a few appliances and later need additional room, or you may only need storage until a renovation, move, or delivery is completed.</p>
                    <p>StorageKeys offers flexible appliance storage solutions based on your requirements rather than requiring you to maintain unused space. You can discuss the quantity and approximate size of your appliances with our team to determine a suitable storage arrangement.</p>
                    <p>For businesses, this can also provide additional capacity without the cost and commitment associated with expanding a commercial premises or warehouse.</p>
                    <a href="#ps-quote" class="sk-btn sk-btn-outline"><i class="fas fa-comments"></i> Discuss Your Requirements</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Why Storage Keys</span>
                <h2>Why Choose StorageKeys for Appliance Storage?</h2>
                <p>StorageKeys provides a straightforward way to store appliances when your existing property or business premises does not have enough room. Our solutions are suitable for both individual customers and businesses looking for additional storage capacity.</p>
            </div>
            <div class="ps-why sk-reveal">
                <div class="ps-whyc"><div class="ic"><i class="fas fa-expand"></i></div><span class="h4">Practical Storage for Bulky Items</span><p>Large appliances can be difficult to accommodate in homes and commercial spaces. Dedicated storage keeps bulky equipment in one location while freeing up usable space at your property.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-clock"></i></div><span class="h4">Flexible Storage Periods</span><p>Whether you need temporary appliance storage during a move or longer-term space for business inventory, requirements can be discussed based on duration and volume.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-headset"></i></div><span class="h4">Convenient Storage Support</span><p>From determining the space you need to arranging your storage, our team can help make the process straightforward.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-shield-alt"></i></div><span class="h4">Secure Facilities</span><p>Keep appliances protected in a dedicated environment instead of unsuitable rooms, garages or workshops.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-home"></i></div><span class="h4">Homes &amp; Residences</span><p>Ideal during moves, renovations, downsizing or when replacing household equipment.</p></div>
                <div class="ps-whyc"><div class="ic"><i class="fas fa-store"></i></div><span class="h4">Retailers &amp; Businesses</span><p>Manage excess inventory waiting to be delivered, installed, sold or redistributed.</p></div>
            </div>
        </div>
    </section>

    <!-- ============ FIND / HELP ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="ps-help sk-reveal">
                <div>
                    <span class="h3">Store Your Appliances with StorageKeys</span>
                    <p>Whether you are moving, renovating, managing excess inventory, or simply need more room, StorageKeys offers flexible appliance storage for different residential and business requirements. Speak with our team to find the right appliance storage unit for your items.</p>
                </div>
                <a href="#ps-quote" class="sk-btn sk-btn-primary"><i class="fas fa-comments"></i> Get a Suitable Option</a>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="ps-quote" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ps-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#fff;">Free Quote</span>
                    <h2>Need Space for Your Appliances?</h2>
                    <p>Contact StorageKeys today to discuss your storage requirements and get a suitable storage option for your household or commercial appliances.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone-alt"></i> Toll Free: 800 5397</a>
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
                    'source' => 'appliance-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Appliance Storage',
                    'showItemsField' => true,
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#ps-quote"><i class="fas fa-blender"></i> Quote</a>
    </div>

</div>
@endsection
