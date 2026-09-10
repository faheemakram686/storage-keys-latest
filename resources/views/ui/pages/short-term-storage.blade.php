@extends('ui.layouts.frontend')
@section('title', '| Short Term Storage')
@section('metaTitle', 'Short Term Storage in Dubai & Sharjah | StorageKeys')
@section('metaDescription', 'Need storage for weeks or months? StorageKeys offers short term storage in Dubai and Sharjah for moves, renovations, travel and business overflow. Get a free quote.')

@section('content')
<div class="sk-home">

    <section class="st-hero">
        <div class="sk-container">
            <div class="st-crumb">
                <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i>
                <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i>
                <span>Short Term Storage</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Short Term Storage</span>
            <h1>Short Term Storage in <span>Dubai &amp; Sharjah</span></h1>
            <p class="lead">Need storage for a limited period? StorageKeys provides short term storage in Dubai and Sharjah for belongings that need a temporary place between moves, renovations, travel plans, property changes or business requirements — with services available across the UAE.</p>
            <div class="st-hero-cta">
                <a href="#st-quote" class="sk-btn sk-btn-primary"><i class="fas fa-stopwatch"></i> Get a Free Quote</a>
                <a href="#st-planner" class="sk-btn sk-btn-ghost"><i class="fas fa-sliders-h"></i> Plan Your Period</a>
            </div>
            <div class="st-hero-badges">
                <span class="st-hbadge"><i class="fas fa-exchange-alt"></i> Between moves</span>
                <span class="st-hbadge"><i class="fas fa-paint-roller"></i> Renovation</span>
                <span class="st-hbadge"><i class="fas fa-plane"></i> Travel</span>
                <span class="st-hbadge"><i class="fas fa-store"></i> Business overflow</span>
            </div>
        </div>
    </section>

    <div class="st-trust">
        <div class="sk-container">
            <div class="st-trust-in">
                <div class="st-trust-i"><i class="fas fa-calendar-week"></i> Weeks or Months</div>
                <div class="st-trust-i"><i class="fas fa-bolt"></i> On-Demand &amp; Flexible</div>
                <div class="st-trust-i"><i class="fas fa-boxes"></i> Boxes to Full Homes</div>
                <div class="st-trust-i"><i class="fas fa-map-marker-alt"></i> Dubai · Sharjah · UAE</div>
                <div class="st-trust-i"><i class="fas fa-box-open"></i> Packing Supplies</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="st-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Temporary Needs</span>
                    <h2>Short Term Storage for Temporary Needs</h2>
                    <p>Not every storage requirement is permanent. Sometimes you simply need your belongings out of the way until a particular situation is resolved.</p>
                    <p>You may have moved out of a property before your new home is ready, started renovation work, arranged an extended trip, or found yourself with more business stock than your current premises can hold. In these situations, renting additional permanent space may not make sense.</p>
                    <p>Our short term storage service provides a practical option for these temporary gaps — keep suitable belongings outside your home, office or other premises until you're ready to use them again. It's particularly useful when the requirement is measured in <b>weeks or months</b>, giving individuals a simple way to keep belongings outside their usual living space while waiting for a move, renovation or travel period to end.</p>
                </div>
                <div class="st-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Common Situations</span>
                <h2>When Do You Need Temporary Storage in Dubai?</h2>
                <p>A temporary requirement can arise from a specific event, a change of plans or simply a short-lived lack of space — somewhere suitable for belongings without making storage a permanent part of your routine.</p>
            </div>
            <div class="st-when sk-reveal">
                <div class="st-wc">
                    <div class="ic"><i class="fas fa-exchange-alt"></i></div>
                    <div>
                        <h3>Between Moving Dates</h3>
                        <p>Moving dates don't always align. When you leave your current property before the next is available, temporary storage bridges the gap for furniture, boxes and possessions — without moving everything into a temporary living space.</p>
                    </div>
                </div>
                <div class="st-wc">
                    <div class="ic"><i class="fas fa-paint-roller"></i></div>
                    <div>
                        <h3>During Renovation Work</h3>
                        <p>Renovations often mean clearing rooms. Keeping items in a storage facility creates more usable working space and reduces the amount of belongings around an active renovation.</p>
                    </div>
                </div>
                <div class="st-wc">
                    <div class="ic"><i class="fas fa-plane-departure"></i></div>
                    <div>
                        <h3>Travel &amp; Temporary Relocation</h3>
                        <p>Extended travel or a temporary stay elsewhere can leave you with belongings that don't need to remain in your living space. Storage provides an alternative place for suitable possessions while you're away.</p>
                    </div>
                </div>
                <div class="st-wc">
                    <div class="ic"><i class="fas fa-store"></i></div>
                    <div>
                        <h3>Temporary Business Requirements</h3>
                        <p>Businesses can need extra space for a limited period — inventory, equipment, supplies or packaging — when available workplace space is temporarily insufficient.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section" id="st-planner">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Plan It</span>
                <h2>Temporary Storage Units for Different Requirements</h2>
                <p>The space you need depends on what you have and how long you need it — a few cartons and suitcases are very different from a furnished home or commercial stock. Set your period and what you're storing for a quick suggestion.</p>
            </div>
            <div class="st-plan sk-reveal">
                <div class="q"><i class="fas fa-box"></i> What are you storing?</div>
                <div class="st-types" id="stTypes">
                    <button type="button" class="st-type active" data-k="compact">
                        <i class="fas fa-suitcase-rolling"></i>
                        <span class="t">A few boxes &amp; luggage</span>
                        <span class="s">Boxes, documents, clothing</span>
                    </button>
                    <button type="button" class="st-type" data-k="home">
                        <i class="fas fa-couch"></i>
                        <span class="t">A furnished home</span>
                        <span class="s">Furniture, appliances, cartons</span>
                    </button>
                    <button type="button" class="st-type" data-k="commercial">
                        <i class="fas fa-pallet"></i>
                        <span class="t">Business stock</span>
                        <span class="s">Inventory, equipment, supplies</span>
                    </button>
                </div>
                <div class="q"><i class="fas fa-calendar-alt"></i> How long do you need it?</div>
                <div class="st-timewrap">
                    <div class="st-timehead"><span class="big" id="stPeriod">6–12+ months</span></div>
                    <input type="range" min="1" max="52" value="12" class="st-range" id="stRange" aria-label="Storage duration in weeks">
                    <div class="st-zones"><span>Days</span><span>Weeks</span><span>1–3 months</span><span>6+ months</span></div>
                </div>
                <div class="st-out">
                    <div>
                        <div class="lead">Suggested short-term arrangement</div>
                        <p id="stPlanText"><b>About 3 months</b> · a compact short-term unit or luggage storage, on a flexible monthly arrangement.</p>
                    </div>
                    <a href="#st-quote" class="sk-btn sk-btn-primary" id="stPlanBtn"><i class="fas fa-paper-plane"></i> Get This Quote</a>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="st-split rev sk-reveal">
                <div class="st-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Flexible</span>
                    <h2>On-Demand Storage When Your Plans Change</h2>
                    <p>Not every requirement can be planned months ahead. Moving schedules change, property work runs longer than expected, and businesses can see sudden increases in stock or equipment.</p>
                    <p>On-demand storage gives you additional space when these situations arise — instead of treating storage as a permanent expansion of your home or workplace, you use it to manage a particular period when your existing space isn't enough.</p>
                    <p style="font-size:14px;color:var(--sk-muted);margin:0;">Not sure of the exact end date? That's fine — your requirement can be discussed around your circumstances rather than a fixed, one-size-fits-all plan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Smaller Requirements</span>
                <h2>Temporary Storage Boxes &amp; Short Term Luggage Storage</h2>
                <p>Smaller requirements often involve items that pack into boxes or travel bags. A little preparation keeps belongings organised — especially when several categories are stored together.</p>
            </div>
            <div class="st-bl sk-reveal">
                <div class="st-blc">
                    <div class="ic"><i class="fas fa-box-open"></i></div>
                    <h3>Temporary Storage Boxes</h3>
                    <p>Ideal for organising smaller belongings — books, clothing, documents and household items. Labelling boxes before storage makes it easy to identify contents later, especially over several weeks or months. StorageKeys also provides packing supplies through its shop to prepare belongings before storage.</p>
                    <a href="{{ url('/shop') }}">Browse packing supplies <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="st-blc">
                    <div class="ic"><i class="fas fa-suitcase"></i></div>
                    <h3>Short Term Luggage Storage</h3>
                    <p>Useful when there's a gap between accommodation, travel arrangements or stages of a trip. Suitcases, travel bags and other suitable personal belongings can be kept separately rather than carried between locations. Have more than luggage? Discuss the full requirement and we'll suggest a larger temporary arrangement.</p>
                    <a href="{{ url('/luggage-storage') }}">Learn about luggage storage <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section st-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center" style="color:#ffcf9e;">Simple Process</span>
                <h2>How Short-Term Storage Works</h2>
                <p style="color:rgba(255,255,255,.8);">Rather than assuming everyone needs the same thing, we match the arrangement to your belongings and your period. Follow the steps:</p>
            </div>
            <div class="st-track sk-reveal">
                <div class="st-nodes" id="stNodes">
                    <div class="prog" id="stProg" style="width:0%;"></div>
                    <div class="st-node active" data-i="0"><div class="dot">1</div><div class="lab">Tell us your need</div></div>
                    <div class="st-node" data-i="1"><div class="dot">2</div><div class="lab">We suggest an arrangement</div></div>
                    <div class="st-node" data-i="2"><div class="dot">3</div><div class="lab">Store for your period</div></div>
                    <div class="st-node" data-i="3"><div class="dot">4</div><div class="lab">Collect when ready</div></div>
                </div>
                <div class="st-panel" id="stPanel">
                    <div class="ic"><i class="fas fa-comments"></i></div>
                    <div>
                        <h3>Tell us your need</h3>
                        <p>Share what you'd like to store, roughly how much, and your expected dates. Providing these details helps us understand the requirement first.</p>
                    </div>
                </div>
                <div class="st-track-nav">
                    <button type="button" id="stPrev" style="visibility:hidden;"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" id="stNext">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Where We Serve</span>
                <h2>Temporary Storage in Dubai, Sharjah &amp; Across the UAE</h2>
                <p>We primarily serve Dubai and Sharjah, with services available across the UAE — suitable whether you're searching "short term storage near me" or arranging storage from another location.</p>
            </div>
            <div class="st-cover sk-reveal">
                <div class="st-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <h4>Dubai</h4>
                    <p>Temporary storage to manage belongings during moves, renovations, travel, property preparation and short-term business requirements.</p>
                </div>
                <div class="st-covc">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <h4>Sharjah</h4>
                    <p>Additional space for household belongings, luggage, inventory and other suitable items, for as long as you need it.</p>
                </div>
                <div class="st-covc">
                    <div class="ic"><i class="fas fa-map-marked-alt"></i></div>
                    <h4>Across the UAE</h4>
                    <p>Arranging from elsewhere — including short term storage in Abu Dhabi? Contact us and we'll discuss availability and the right arrangement.</p>
                </div>
            </div>
            <div class="st-cover-note sk-reveal">
                <i class="fas fa-lightbulb"></i>
                Consider more than location: the type and quantity of belongings, the storage period and the overall arrangement should match your needs. Tell us <b>what</b> you're storing, <b>how much</b> space it needs and roughly <b>how long</b> — and we'll recommend an option suited to the actual requirement rather than generic space.
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Who It's For</span>
                <h2>Temporary Storage for Personal &amp; Commercial Needs</h2>
                <p>Short-term storage isn't limited to household belongings — the same need for extra space arises across personal and commercial situations. The purpose isn't to replace your home or premises, but to add space when your usual arrangements temporarily can't hold everything.</p>
            </div>
            <div class="st-pc sk-reveal">
                <div class="st-pcc p">
                    <div class="ic"><i class="fas fa-user"></i></div>
                    <h3>For Individuals</h3>
                    <p>Somewhere to keep furniture, appliances, boxes, luggage and other possessions during a temporary change — moving between properties, clearing a room, travelling, or waiting for a new home.</p>
                </div>
                <div class="st-pcc c">
                    <div class="ic"><i class="fas fa-briefcase"></i></div>
                    <h3>For Businesses</h3>
                    <p>Help when inventory, equipment or supplies temporarily exceed your premises — extra room for incoming stock, space during an office change, or somewhere to keep project equipment outside the workplace for a period.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>FAQs About Short Term Storage</h2>
            </div>
            <div class="st-faq">
                <details open>
                    <summary>How long can I use short term storage?</summary>
                    <div class="a">The suitable period depends on your individual requirement and arrangement with StorageKeys. Share your expected dates when contacting the team so the appropriate option can be discussed.</div>
                </details>
                <details>
                    <summary>Can I store furniture for a short period?</summary>
                    <div class="a">Yes. Furniture can be stored temporarily when you're moving, renovating, travelling or waiting for another property to become available. The space required depends on the size and quantity of items.</div>
                </details>
                <details>
                    <summary>Is temporary storage available for businesses?</summary>
                    <div class="a">Yes. Suitable inventory, equipment, packaging, supplies and other commercial belongings can be considered for temporary storage when a business needs additional space for a limited period.</div>
                </details>
                <details>
                    <summary>Do you provide short term storage outside Dubai and Sharjah?</summary>
                    <div class="a">Yes. Dubai and Sharjah are the main service locations, while StorageKeys provides services across the UAE. Customers elsewhere can contact the team to discuss requirements and availability.</div>
                </details>
                <details>
                    <summary>How do I know which temporary storage unit I need?</summary>
                    <div class="a">The best option depends on the type and quantity of belongings you need to store. Providing an item list or an estimate of what you have helps the StorageKeys team determine an appropriate arrangement.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="sk-section st-quote-wrap" id="st-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Store on Your Timeline With StorageKeys</h2>
                    <p>Whether it's a few weeks between moves or a few months through a renovation, tell us what you need to store and roughly how long — and we'll recommend a short-term arrangement suited to you.</p>
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
                    'source' => 'short-term-storage',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Short Term Storage',
                    'storingOptions' => [
                        'Boxes and luggage' => 'Boxes and luggage',
                        'Furniture and household items' => 'Furniture and household items',
                        'Business stock / equipment' => 'Business stock / equipment',
                        'Full home contents' => 'Full home contents',
                        'Other temporary storage' => 'Other temporary storage',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="st-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#st-quote"><i class="fas fa-stopwatch"></i> Quote</a>
    </div>

</div>
@endsection
