@extends('ui.layouts.frontend')
@section('title', '| Vehicle Storage')
@section('metaTitle', 'Vehicle Storage in Dubai, Sharjah & the UAE | StorageKeys')
@section('metaDescription', 'Store cars, motorcycles, vans and fleet vehicles with StorageKeys. Indoor or outdoor vehicle storage in Dubai, Sharjah and across the UAE. Get a free quote.')

@section('content')
<div class="sk-home">

    <section class="vh-hero">
        <div class="sk-container">
            <div class="vh-crumb">
                <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i>
                <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i>
                <span>Vehicle Storage</span>
            </div>
            <span class="sk-eyebrow sk-eyebrow--light">Vehicle Storage</span>
            <h1>Vehicle Storage in <span>Dubai, Sharjah &amp; the UAE</span></h1>
            <p class="lead">Store cars, motorcycles, vans and other vehicles in a practical, secure space. Choose suitable vehicle storage options in Dubai, Sharjah or anywhere across the UAE — based on your vehicle and your requirements.</p>
            <div class="vh-hero-cta">
                <a href="#vh-quote" class="sk-btn sk-btn-primary"><i class="fas fa-car"></i> Get a Free Quote</a>
                <a href="#vh-config" class="sk-btn sk-btn-ghost"><i class="fas fa-sliders-h"></i> Configure My Space</a>
            </div>
            <div class="vh-hero-badges">
                <span class="vh-hbadge"><i class="fas fa-car"></i> Cars</span>
                <span class="vh-hbadge"><i class="fas fa-motorcycle"></i> Motorcycles</span>
                <span class="vh-hbadge"><i class="fas fa-shuttle-van"></i> Vans</span>
                <span class="vh-hbadge"><i class="fas fa-truck"></i> Fleet &amp; commercial</span>
            </div>
        </div>
    </section>

    <div class="vh-trust">
        <div class="sk-container">
            <div class="vh-trust-in">
                <div class="vh-trust-i"><i class="fas fa-car-side"></i> Cars to Vans</div>
                <div class="vh-trust-i"><i class="fas fa-warehouse"></i> Indoor or Outdoor</div>
                <div class="vh-trust-i"><i class="fas fa-calendar-alt"></i> Short or Long Term</div>
                <div class="vh-trust-i"><i class="fas fa-layer-group"></i> Single or Multiple</div>
                <div class="vh-trust-i"><i class="fas fa-map-marker-alt"></i> Dubai · Sharjah · UAE</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="vh-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Personal &amp; Business</span>
                    <h2>Practical Vehicle Storage for Personal &amp; Business Use</h2>
                    <p>StorageKeys provides vehicle storage solutions for individuals and businesses that need a suitable place to keep vehicles outside their usual parking areas — designed around the type of vehicle, the space required and the expected storage period.</p>
                    <p>Whether you need vehicle self storage for a personal vehicle or additional space for business vehicles, we can help you identify an appropriate arrangement — for vehicles that are temporarily unused, kept aside for future use, or no longer required on a daily basis.</p>
                    <p>For businesses, that means space for company cars, vans, motorcycles, delivery vehicles and other fleet vehicles. For individuals, it's a practical option when residential parking is limited or a vehicle needs to remain stored for an extended period.</p>
                </div>
                <div class="vh-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft" id="vh-config">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Options for Different Requirements</span>
                <h2>Configure Your Vehicle Storage</h2>
                <p>The right arrangement depends on more than the vehicle itself — dimensions, number of vehicles, expected period and preferred environment all matter. Pick your options and see a suggested arrangement.</p>
            </div>
            <div class="vh-cfg sk-reveal">
                <div>
                    <div class="q"><i class="fas fa-car"></i> Which vehicle?</div>
                    <div class="vh-typegrid" id="vhTypes">
                        <button type="button" class="vh-tp active" data-k="car"><i class="fas fa-car"></i><span>Car</span></button>
                        <button type="button" class="vh-tp" data-k="moto"><i class="fas fa-motorcycle"></i><span>Motorcycle</span></button>
                        <button type="button" class="vh-tp" data-k="van"><i class="fas fa-shuttle-van"></i><span>Van</span></button>
                        <button type="button" class="vh-tp" data-k="commercial"><i class="fas fa-truck"></i><span>Commercial</span></button>
                        <button type="button" class="vh-tp" data-k="larger"><i class="fas fa-truck-moving"></i><span>Larger vehicle</span></button>
                        <button type="button" class="vh-tp" data-k="multiple"><i class="fas fa-layer-group"></i><span>Multiple</span></button>
                    </div>
                    <div class="q"><i class="fas fa-warehouse"></i> Environment</div>
                    <div class="vh-seg" id="vhEnv">
                        <button type="button" class="active" data-k="indoor"><i class="fas fa-box"></i> Indoor (enclosed)</button>
                        <button type="button" data-k="outdoor"><i class="fas fa-cloud-sun"></i> Outdoor (open)</button>
                    </div>
                    <div class="q"><i class="fas fa-calendar-alt"></i> Storage period</div>
                    <div class="vh-seg" id="vhDur">
                        <button type="button" class="active" data-k="short"><i class="fas fa-bolt"></i> Short-term</button>
                        <button type="button" data-k="long"><i class="fas fa-hourglass-half"></i> Long-term</button>
                    </div>
                </div>
                <div class="vh-visual">
                    <div class="vh-bay enclosed" id="vhBay">
                        <i class="fas fa-car veh-ic" id="vhVehIc"></i>
                        <div class="vh-foot" id="vhFoot" style="width:52%"></div>
                    </div>
                    <p class="cap" id="vhCap"><b>A standard car space away from everyday parking.</b></p>
                </div>
                <div class="vh-cfg-out">
                    <div>
                        <div class="lead">Suggested arrangement</div>
                        <p id="vhOut">A <b>standard</b> vehicle storage space, <b>enclosed (indoor)</b>, for <b>short-term</b> storage. A designated place to keep an unused or additional car away from residential and commercial parking. Share your vehicle details for an exact match.</p>
                    </div>
                    <a href="#vh-quote" class="sk-btn sk-btn-primary" id="vhCfgBtn"><i class="fas fa-paper-plane"></i> Get This Quote</a>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Choose the Environment</span>
                <h2>Indoor &amp; Outdoor Vehicle Storage</h2>
                <p>The right environment depends on the vehicle type, expected period, available space and your priorities. Drag the handle to compare the two.</p>
            </div>
            <div class="vh-cmp sk-reveal" id="vhCmp">
                <div class="vh-cmp-panel vh-out">
                    <i class="fas fa-cloud-sun pic"></i>
                    <h3>Outdoor / Open</h3>
                    <p>A practical alternative when an enclosed unit isn't required — the priority is dedicated space rather than an enclosure.</p>
                    <div class="tags"><span>Open bay</span><span>Dedicated space</span><span>Cost-effective</span></div>
                </div>
                <div class="vh-in-wrap" id="vhInWrap">
                    <div class="vh-cmp-panel vh-in">
                        <i class="fas fa-warehouse pic"></i>
                        <h3>Indoor / Enclosed</h3>
                        <p>An enclosed environment for when it's preferred — particularly relevant for keeping a vehicle stored over an extended period.</p>
                        <div class="tags"><span>Enclosed</span><span>Long-term friendly</span><span>Extra protection</span></div>
                    </div>
                </div>
                <div class="vh-handle" id="vhHandle"><span class="grip"><i class="fas fa-arrows-alt-h"></i></span></div>
                <input type="range" min="0" max="100" value="50" id="vhCmpRange" aria-label="Compare indoor and outdoor storage">
            </div>
            <div class="vh-cmp-labels"><span><i class="fas fa-warehouse"></i> Indoor</span><span>Outdoor <i class="fas fa-cloud-sun"></i></span></div>
            <p style="text-align:center;max-width:720px;margin:26px auto 0;color:var(--sk-muted);font-size:14px;">When comparing vehicle storage facilities, weigh the environment alongside available space, location, storage period and overall suitability.</p>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="vh-split rev sk-reveal">
                <div class="vh-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">How Long?</span>
                    <h2>Short-Term &amp; Long-Term Vehicle Storage</h2>
                    <p>Requirements can range from a short period to several months or longer — we accommodate different periods depending on availability and the type of vehicle involved.</p>
                    <div class="vh-term">
                        <div class="vh-term-i">
                            <div class="ic"><i class="fas fa-hourglass-half"></i></div>
                            <div>
                                <h4>Long-Term Storage</h4>
                                <p>Useful when a vehicle isn't needed for everyday use but still needs a designated place — reserve or additional fleet vehicles, or personal vehicles temporarily not in use.</p>
                            </div>
                        </div>
                        <div class="vh-term-i">
                            <div class="ic"><i class="fas fa-bolt"></i></div>
                            <div>
                                <h4>Short-Term Storage</h4>
                                <p>Helps avoid keeping a vehicle in an unsuitable parking spot while you manage a move, temporary relocation or another change in circumstances.</p>
                            </div>
                        </div>
                    </div>
                    <p style="font-size:13.5px;color:var(--sk-muted);margin-top:8px;">The storage period is an important factor when choosing between units — worth discussing before confirming an arrangement.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Extra Space</span>
                <h2>Vehicle Storage Containers &amp; Additional Space</h2>
                <p>Some owners also need room for equipment, accessories, tools or other belongings associated with their vehicles — keeping these in a dedicated area makes vehicle management easier.</p>
            </div>
            <div class="vh-cards sk-reveal">
                <div class="vh-card">
                    <div class="ic"><i class="fas fa-box"></i></div>
                    <div>
                        <h3>Vehicle Storage Containers</h3>
                        <p>Additional enclosed space for suitable belongings — a practical option when you need somewhere to keep related items rather than placing everything inside the vehicle.</p>
                    </div>
                </div>
                <div class="vh-card">
                    <div class="ic"><i class="fas fa-toolbox"></i></div>
                    <div>
                        <h3>Vehicle Storage Box</h3>
                        <p>Useful for smaller vehicle-related items — tools, accessories and equipment — that need to remain organised separately from the vehicle itself.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="vh-split rev sk-reveal">
                <div class="vh-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Right-Sized Value</span>
                    <h2>Affordable Vehicle Storage Without Excess Space</h2>
                    <p>Costs vary by vehicle size, storage type, location, duration and the amount of space required — choosing an appropriately sized arrangement helps prevent unnecessary expenditure.</p>
                    <ul class="vh-flist">
                        <li><i class="fas fa-ruler-combined"></i> Vehicle size</li>
                        <li><i class="fas fa-warehouse"></i> Storage type</li>
                        <li><i class="fas fa-map-marker-alt"></i> Location</li>
                        <li><i class="fas fa-calendar-alt"></i> Duration</li>
                        <li><i class="fas fa-vector-square"></i> Space required</li>
                    </ul>
                    <p style="font-size:14px;color:var(--sk-muted);margin:0;">Searching for cheap vehicle storage? Discuss your requirements and compare suitable options — but a lower price shouldn't be the only consideration. The environment, available space, accessibility, duration and suitability all matter. A practical solution provides the space you actually need, without an unsuitable or unnecessarily large arrangement.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Near You</span>
                <h2>Vehicle Storage Near You Across the UAE</h2>
                <p>A strong focus on Dubai and Sharjah, with solutions across the UAE. If you're searching "vehicle storage near me," location is only one part — the space should also suit your vehicle and your intended period.</p>
            </div>
            <div class="vh-cover sk-reveal">
                <div class="vh-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <h4>Dubai</h4>
                    <p>Discuss your vehicle type and required duration and we'll guide you toward the relevant option.</p>
                </div>
                <div class="vh-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <h4>Sharjah</h4>
                    <p>Dedicated vehicle storage arrangements matched to your vehicle and storage period.</p>
                </div>
                <div class="vh-covc">
                    <div class="ic"><i class="fas fa-map-marked-alt"></i></div>
                    <h4>Abu Dhabi &amp; Wider UAE</h4>
                    <p>Also serving customers looking for vehicle storage in Abu Dhabi and other locations — availability can depend on the specific requirement.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Around Your Requirements</span>
                <h2>Facilities Designed Around What You Store</h2>
                <p>More than a basic place to leave a vehicle — we understand what you need to store, then identify an arrangement that makes sense for the vehicle, period and available space. Our solutions can support:</p>
            </div>
            <div class="vh-supports sk-reveal">
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Personal vehicles &amp; additional household vehicles</span></div>
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Motorcycles &amp; compact vehicles</span></div>
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Vans &amp; commercial vehicles</span></div>
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Individual &amp; multiple-vehicle requirements</span></div>
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Short-term &amp; long-term storage</span></div>
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Indoor &amp; outdoor storage requirements</span></div>
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Additional space for vehicle-related belongings</span></div>
                <div class="vh-sup"><i class="fas fa-check-circle"></i><span>Customers across Dubai, Sharjah, Abu Dhabi &amp; the UAE</span></div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>FAQs About Vehicle Storage</h2>
            </div>
            <div class="vh-faq">
                <details open>
                    <summary>What types of vehicles can I store with StorageKeys?</summary>
                    <div class="a">Storage options may accommodate cars, motorcycles, vans and other vehicles, depending on their size and requirements. Contact our team with your vehicle details to discuss suitable options.</div>
                </details>
                <details>
                    <summary>Is indoor vehicle storage available?</summary>
                    <div class="a">Indoor vehicle storage may be available depending on the vehicle and required space. StorageKeys can advise whether an enclosed option is suitable for your specific requirements.</div>
                </details>
                <details>
                    <summary>Can I store a vehicle for several months?</summary>
                    <div class="a">Yes. StorageKeys can discuss longer-term arrangements based on your vehicle, required space and storage period. Available options may vary depending on location and requirements.</div>
                </details>
                <details>
                    <summary>Does StorageKeys provide vehicle storage outside Dubai?</summary>
                    <div class="a">Yes. Dubai and Sharjah are key service areas, while StorageKeys also provides storage solutions across the UAE, including support for customers looking for vehicle storage in other locations.</div>
                </details>
                <details>
                    <summary>How do I choose the right vehicle storage option?</summary>
                    <div class="a">Consider your vehicle size, storage duration, preferred environment and additional space requirements. StorageKeys can help identify an option that suits your circumstances.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="sk-section vh-quote-wrap" id="vh-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">Free Quote</span>
                    <h2>Find the Right Space for Your Vehicle</h2>
                    <p>Tell us your vehicle type, preferred environment and how long you need it — and we'll match you with a straightforward vehicle storage arrangement that fits your actual requirements.</p>
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
                    'source' => 'vehicle-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Vehicle Storage',
                    'storingOptions' => [
                        'Car' => 'Car',
                        'Motorcycle' => 'Motorcycle',
                        'Van' => 'Van',
                        'Commercial / fleet vehicle' => 'Commercial / fleet vehicle',
                        'Multiple vehicles' => 'Multiple vehicles',
                        'Other vehicle storage' => 'Other vehicle storage',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="vh-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#vh-quote"><i class="fas fa-car"></i> Quote</a>
    </div>

</div>
@endsection
