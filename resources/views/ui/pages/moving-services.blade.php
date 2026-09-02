@extends('ui.layouts.frontend')
@section('title', 'Moving & Storage Services')
@section('metaTitle', 'Moving & Storage Services in the UAE - Storagekeys')
@section('metaDescription', 'Reliable moving and storage services across the UAE, from packing and transport to secure short or long-term storage. Get a free quote today!')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="svc-hero">
        <div class="sk-container">
            <div class="svc-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Moving Services</span></div>
            <div class="svc-hero-grid">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Moving &amp; Storage</span>
                    <h1>Moving &amp; Storage Services in <span>Dubai</span></h1>
                    <p class="lead">Professional moving and storage services across Dubai and the UAE, with flexible transportation, packing, and temporary storage solutions for homes and businesses.</p>
                    <div class="svc-hero-cta">
                        <a href="#moving-quote" class="sk-btn sk-btn-primary"><i class="fas fa-file-invoice-dollar"></i> Get a Free Quote</a>
                        <a href="tel:+971565018785" class="sk-btn sk-btn-ghost"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397" class="sk-btn sk-btn-ghost"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                    </div>
                </div>
                <div class="svc-hero-card">
                    <span class="h3">Moving and storage together</span>
                    <ul>
                        <li><i class="fas fa-box-open"></i> Packing, loading and transportation</li>
                        <li><i class="fas fa-warehouse"></i> Temporary or longer-term storage</li>
                        <li><i class="fas fa-home"></i> Homes and businesses</li>
                        <li><i class="fas fa-clock"></i> Move out now, deliver when you’re ready</li>
                        <li><i class="fas fa-handshake"></i> One provider for moving and storage</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="svc-trust">
        <div class="sk-container">
            <div class="svc-trust-in">
                <div class="svc-trust-i"><i class="fas fa-truck"></i> Home &amp; Office Moves</div>
                <div class="svc-trust-i"><i class="fas fa-box"></i> Packing Support</div>
                <div class="svc-trust-i"><i class="fas fa-warehouse"></i> Temporary Storage</div>
                <div class="svc-trust-i"><i class="fas fa-shield-alt"></i> Secure Facilities</div>
                <div class="svc-trust-i"><i class="fas fa-calendar-check"></i> Flexible Schedules</div>
            </div>
        </div>
    </div>

    <!-- ============ OVERVIEW ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="svc-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Overview</span>
                    <h2>Moving and Storage Solutions for Smooth Transitions</h2>
                    <p>Moving doesn't always happen between two ready-to-use properties. You may need to leave your current home before your new one is available, relocate an office while another location is being prepared, or keep your belongings somewhere secure during renovations. StorageKeys combines moving services with storage facilities so you can manage these situations without coordinating separate providers.</p>
                    <p>Our team can assist with packing, loading, transportation, storage, and delivery according to your moving schedule. If you need temporary storage for a few days or longer-term space while your plans develop, we provide flexible storage units to accommodate your requirements. This means you can move out when necessary and arrange delivery when you're actually ready to receive your belongings.</p>
                    <p>For customers searching for moving companies in Dubai, the advantage of choosing StorageKeys is having moving and storage available through the same provider. Instead of arranging a moving company and then finding a separate storage facility, you can coordinate both requirements together. Whether you're planning a residential move, office relocation, or longer-distance move, our services are designed around the practical needs of your transition.</p>
                </div>
                <div class="svc-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_5.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ OUR SERVICES ============ -->
    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">What We Offer</span>
                <h2>Our Moving &amp; Storage Services</h2>
                <p>StorageKeys provides a combination of moving and storage services for residential and commercial customers. From professional packing and furniture transportation to temporary storage and final delivery, our services can be arranged according to the size, timing, and requirements of your relocation.</p>
            </div>
            <div class="svc-cards sk-reveal">
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-home"></i></div>
                    <div>
                        <span class="h3">Home Moving</span>
                        <p>Our home moving services cover apartments, villas, and other residential properties. We can help pack, load, transport, and deliver household belongings while providing storage units when your new home isn't ready.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <div>
                        <span class="h3">Office Relocation</span>
                        <p>Relocating an office requires careful handling of furniture, equipment, documents, and other business belongings. Our office relocation services help businesses move efficiently while using our storage facilities when additional space is required.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-box-open"></i></div>
                    <div>
                        <span class="h3">Packing &amp; Moving Services</span>
                        <p>Our packing and moving services combine careful preparation with transportation. We use suitable packing materials and handling methods to help protect furniture, appliances, electronics, documents, and other belongings during the move.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-warehouse"></i></div>
                    <div>
                        <span class="h3">Moving Services &amp; Temporary Storage</span>
                        <p>Our moving services can be combined with temporary storage when your moving dates don't align. Your belongings can be transported to our storage facility and kept in secure storage units until you're ready for final delivery.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ COMPLETE SOLUTION ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="svc-split rev sk-reveal">
                <div class="svc-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">One Provider</span>
                    <h2>A Complete Moving Solution From Start to Finish</h2>
                    <p>Arranging a relocation through several different providers can make the process unnecessarily complicated. You may need to coordinate a moving company for transportation, another provider for packing, and a separate storage facility if your new property isn't ready. When schedules change, keeping everything coordinated becomes even more difficult.</p>
                    <p>StorageKeys brings these requirements together through a combined moving and storage service. Depending on your needs, our team can prepare your belongings, arrange transportation, move them into storage, and deliver them to your new location when you're ready. Having these services available through one provider gives you greater flexibility throughout the relocation process.</p>
                    <p>This approach is particularly useful when you're moving out before your new property is available. Instead of keeping furniture and boxes in temporary accommodation or rushing your move around a fixed date, you can place your belongings in temporary storage and arrange delivery once your new space is ready. The same approach can work for businesses relocating between offices or moving in stages.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FLEXIBLE STORAGE ============ -->
    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="svc-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Flexible Units</span>
                    <h2>Flexible Storage Units for Your Moving Needs</h2>
                    <p>A change in your moving schedule shouldn't mean having to change your entire plan. Delayed handovers, renovation work, temporary accommodation, and phased office relocations can all create a period when your belongings need somewhere else to stay.</p>
                    <p>Our storage units provide a practical option during these transitions. You can use temporary storage while waiting for your new home or office, or choose a longer storage period if your relocation timeline is uncertain. Your belongings remain at our storage facility until you're ready to arrange the next stage of your move.</p>
                    <p>For customers who have already arranged transportation, storage can also be added when required. This makes our storage facilities suitable for people who need a place between two properties as well as those looking for longer-term storage during a wider relocation.</p>
                </div>
                <div class="svc-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <!-- ============ RESIDENTIAL & COMMERCIAL ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Who It's For</span>
                <h2>Moving &amp; Storage for Residential and Commercial Customers</h2>
                <p>Our services are suitable for both personal and business relocations — homes, offices, and commercial premises.</p>
            </div>
            <div class="svc-vs sk-reveal">
                <div class="svc-vs-card good">
                    <span class="h3"><span class="dot"><i class="fas fa-home"></i></span> Residential Moves</span>
                    <p style="margin:0 0 14px;font-size:14.5px;color:var(--sk-muted);line-height:1.7;">For homeowners and tenants, our services can cover the practical stages of moving household furniture and personal belongings. If your new property is delayed or you need additional time before moving in, temporary storage gives you more flexibility without having to keep everything at your current location.</p>
                    <ul>
                        <li><i class="fas fa-check"></i> Apartments, villas and other homes</li>
                        <li><i class="fas fa-check"></i> Household furniture and personal belongings</li>
                        <li><i class="fas fa-check"></i> Temporary storage between properties</li>
                    </ul>
                </div>
                <div class="svc-vs-mid">&amp;</div>
                <div class="svc-vs-card good">
                    <span class="h3"><span class="dot"><i class="fas fa-briefcase"></i></span> Commercial Moves</span>
                    <p style="margin:0 0 14px;font-size:14.5px;color:var(--sk-muted);line-height:1.7;">Businesses can also combine moving and storage when relocating offices, retail spaces, or other commercial premises. Furniture, workstations, equipment, documents, and business belongings can be transported and placed in storage units until the new location is ready.</p>
                    <ul>
                        <li><i class="fas fa-check"></i> Office, retail and commercial premises</li>
                        <li><i class="fas fa-check"></i> Furniture, equipment and documents</li>
                        <li><i class="fas fa-check"></i> Useful when vacating before the next space is ready</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE ============ -->
    <section class="sk-section svc-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Why StorageKeys</span>
                <h2>Why Choose StorageKeys for Moving &amp; Storage?</h2>
                <p class="svc-feat-lead">Choosing one provider for moving and storage can make your relocation easier to coordinate. Instead of communicating with separate companies for transportation and storage, you have one team managing the services around your moving requirements.</p>
            </div>
            <div class="svc-feat-grid sk-reveal">
                <div class="svc-feat-i"><i class="fas fa-handshake"></i><span class="h4">One coordinated team</span><p>Moving and storage managed together around your schedule.</p></div>
                <div class="svc-feat-i"><i class="fas fa-sliders-h"></i><span class="h4">Flexible options</span><p>Transportation only, or packing, moving and temporary storage together.</p></div>
                <div class="svc-feat-i"><i class="fas fa-warehouse"></i><span class="h4">Secure storage units</span><p>Keep belongings safe when your timeline changes.</p></div>
                <div class="svc-feat-i"><i class="fas fa-calendar-alt"></i><span class="h4">Move around your schedule</span><p>Deliver when your next property or office is actually ready.</p></div>
                <div class="svc-feat-i"><i class="fas fa-home"></i><span class="h4">Homes &amp; businesses</span><p>Residential and commercial relocations across the UAE.</p></div>
                <div class="svc-feat-i"><i class="fas fa-map-marker-alt"></i><span class="h4">Dubai moving companies alternative</span><p>Combine professional moving with storage in one service.</p></div>
            </div>
            <p class="svc-vs-note">From the initial collection through transportation, storage, and final delivery, your relocation can be managed through one coordinated service.</p>
        </div>
    </section>

    <!-- ============ HOW IT WORKS ============ -->
    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Process</span>
                <h2>How Our Moving &amp; Storage Service Works</h2>
                <p>Our process is designed to keep your relocation straightforward, whether you need moving services alone or a complete moving and storage solution.</p>
            </div>
            <div class="svc-cards sk-reveal">
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-comments"></i></div>
                    <div>
                        <span class="h3">1. Share Your Requirements</span>
                        <p>Tell us about your current location, destination, belongings, preferred moving date, and whether you require temporary or longer-term storage.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <span class="h3">2. Plan Your Service</span>
                        <p>Our team helps determine the moving, packing, transportation, and storage services required for your relocation.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-box"></i></div>
                    <div>
                        <span class="h3">3. Pack &amp; Collect</span>
                        <p>Your belongings are prepared and packed where required before being collected and transported by our moving team.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-truck-moving"></i></div>
                    <div>
                        <span class="h3">4. Move or Store</span>
                        <p>Items can be delivered directly to your new property or transferred to our storage facility if you need additional time.</p>
                    </div>
                </div>
                <div class="svc-card">
                    <div class="ic"><i class="fas fa-truck"></i></div>
                    <div>
                        <span class="h3">5. Arrange Final Delivery</span>
                        <p>When your new home, office, or other destination is ready, we arrange delivery of your stored belongings according to your schedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="svc-faq">
                <details open>
                    <summary>Do you provide moving and storage services in Dubai?</summary>
                    <div class="a">Yes. StorageKeys provides moving services and flexible storage solutions for residential and commercial customers across Dubai and the UAE.</div>
                </details>
                <details>
                    <summary>Can I use temporary storage during my move?</summary>
                    <div class="a">Yes. You can use our storage units when there's a gap between moving out and moving into your new property.</div>
                </details>
                <details>
                    <summary>What do your moving services include?</summary>
                    <div class="a">Our moving services can include packing, loading, transportation, unloading, and delivery based on your relocation requirements.</div>
                </details>
                <details>
                    <summary>Do you provide packing and moving services?</summary>
                    <div class="a">Yes. Our team can handle packing and transportation together, helping simplify your relocation through one coordinated service.</div>
                </details>
                <details>
                    <summary>Can businesses use your moving and storage services?</summary>
                    <div class="a">Yes. Businesses can use our relocation, moving, and storage services for office furniture, equipment, documents, inventory, and other belongings.</div>
                </details>
                <details>
                    <summary>How long can I use your storage facilities?</summary>
                    <div class="a">We offer flexible storage options for both short-term and longer-term requirements, depending on your belongings and storage needs.</div>
                </details>
            </div>
        </div>
    </section>

    <!-- ============ QUOTE ============ -->
    <section class="sk-section" id="moving-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Plan Your Move With StorageKeys</h2>
                    <p>Need moving and storage in one place? Contact StorageKeys for a flexible solution tailored to your schedule.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
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
                    'source' => 'moving-services',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Moving',
                    'storingOptions' => [
                        'Home moving' => 'Home moving',
                        'Office relocation' => 'Office relocation',
                        'Packing & moving' => 'Packing & moving',
                        'Moving & temporary storage' => 'Moving & temporary storage',
                        'A mix of the above' => 'A mix of the above',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="svc-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#moving-quote"><i class="fas fa-file-invoice-dollar"></i> Quote</a>
    </div>
</div>
@endsection
