@extends('ui.layouts.frontend')
@section('title', '| Long Term Storage')
@section('metaTitle', 'Long Term Storage in Dubai & Sharjah | StorageKeys')
@section('metaDescription', 'Long term storage in Dubai and Sharjah for furniture, seasonal items, documents and business stock. Right-sized units for months or years. Get a free quote.')

@section('content')

<div class="sk-home">

    <section class="lt-hero">
        <div class="sk-container">
            <div class="lt-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Long Term Storage</span></div>
            <span class="sk-eyebrow sk-eyebrow--light">Long Term Storage</span>
            <h1>Long Term Storage in <span>Sharjah &amp; Dubai for Your Needs</span></h1>
            <p class="lead">Keep belongings you don't need every day in dedicated long term storage across Dubai and Sharjah. StorageKeys provides practical space for households, furniture, business stock and other suitable items — for extended periods.</p>
            <div class="lt-hero-cta">
                <a href="#lt-quote" class="sk-btn sk-btn-primary"><i class="fas fa-warehouse"></i> Get a Free Quote</a>
                <a href="#lt-sorter" class="sk-btn sk-btn-ghost"><i class="fas fa-arrows-alt-h"></i> Plan What to Store</a>
            </div>
            <div class="lt-hero-badges">
                <span class="lt-hbadge"><i class="fas fa-couch"></i> Furniture</span>
                <span class="lt-hbadge"><i class="fas fa-snowflake"></i> Seasonal</span>
                <span class="lt-hbadge"><i class="fas fa-boxes"></i> Business stock</span>
                <span class="lt-hbadge"><i class="fas fa-folder-open"></i> Documents</span>
            </div>
        </div>
    </section>

    <div class="lt-trust">
        <div class="sk-container">
            <div class="lt-trust-in">
                <div class="lt-trust-i"><i class="fas fa-calendar-alt"></i> Months or Years</div>
                <div class="lt-trust-i"><i class="fas fa-home"></i> Household &amp; Business</div>
                <div class="lt-trust-i"><i class="fas fa-couch"></i> Furniture to Boxes</div>
                <div class="lt-trust-i"><i class="fas fa-ruler-combined"></i> Right-Sized Units</div>
                <div class="lt-trust-i"><i class="fas fa-map-marker-alt"></i> Dubai · Sharjah · UAE</div>
            </div>
        </div>
    </div>

    <section class="sk-section">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Keep What Matters</span>
                    <h2>Long Term Storage for Belongings You Want to Keep</h2>
                    <p>Some belongings are worth keeping even when you don't have an immediate use for them. Furniture from a previous home, seasonal possessions, family belongings, business equipment and excess inventory can all occupy valuable space when kept on your premises.</p>
                    <p>Long term storage gives these items a dedicated place outside your everyday environment. Instead of repeatedly moving them around your home, office or warehouse, you keep them stored until you need them again.</p>
                    <p>It's particularly useful for belongings that are important to retain but don't justify permanent space in your current property — and an alternative when space is becoming limited but you're not ready to dispose of items you may need later. StorageKeys provides long-term options for personal and commercial requirements in Dubai and Sharjah, with services available across the UAE.</p>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">When It Makes Sense</span>
                <h2>When Does Long Term Storage Make Sense?</h2>
                <p>Often less about a single event and more about how you use your space. If certain belongings are rarely used but still have value, keeping them separately makes everyday areas easier to manage.</p>
            </div>
            <div class="lt-reasons sk-reveal">
                <div class="lt-reason"><div class="num">01</div><div><h3>Downsizing Without Giving Up Belongings</h3><p>Moving into a smaller apartment can leave you with furniture and possessions that no longer fit. Keep selected items while you decide what stays in your current home.</p></div></div>
                <div class="lt-reason"><div class="num">02</div><div><h3>Seasonal &amp; Occasional Items</h3><p>Things used only at particular times of year can occupy space for months. Storing suitable seasonal belongings leaves your home or office less crowded the rest of the year.</p></div></div>
                <div class="lt-reason"><div class="num">03</div><div><h3>Relocation or Extended Time Away</h3><p>Moving abroad, relocating within the UAE or spending an extended period elsewhere? Long-term space holds suitable belongings until your circumstances change.</p></div></div>
                <div class="lt-reason"><div class="num">04</div><div><h3>Business Space Constraints</h3><p>Inventory, equipment, files and supplies that aren't needed daily can be stored separately, keeping the main workplace focused on day-to-day operations.</p></div></div>
            </div>
        </div>
    </section>

    <section class="sk-section" id="lt-sorter">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Right-Size It</span>
                <h2>Long Term Storage Units for Different Needs</h2>
                <p>There's no single amount of space that works for everyone. Tap the belongings you'd move into long-term storage — it helps you (and us) picture the unit you actually need, rather than sizing by your home or office.</p>
            </div>
            <div class="lt-sort sk-reveal">
                <div class="lt-sort-grid">
                    <div class="lt-col" id="ltHome">
                        <h3><span class="lbl"><i class="fas fa-home"></i> Keeping at home</span> <span class="n" id="ltHomeN">8</span></h3>
                        <div class="lt-items" id="ltHomeItems">
                            <button type="button" class="lt-chip" data-v="Furniture from a previous home"><i class="fas fa-couch vic"></i> Furniture from a previous home <i class="fas fa-arrow-right act"></i></button>
                            <button type="button" class="lt-chip" data-v="Seasonal possessions"><i class="fas fa-snowflake vic"></i> Seasonal possessions <i class="fas fa-arrow-right act"></i></button>
                            <button type="button" class="lt-chip" data-v="Family belongings"><i class="fas fa-box vic"></i> Family belongings <i class="fas fa-arrow-right act"></i></button>
                            <button type="button" class="lt-chip" data-v="Business equipment"><i class="fas fa-toolbox vic"></i> Business equipment <i class="fas fa-arrow-right act"></i></button>
                            <button type="button" class="lt-chip" data-v="Excess inventory"><i class="fas fa-boxes vic"></i> Excess inventory <i class="fas fa-arrow-right act"></i></button>
                            <button type="button" class="lt-chip" data-v="Documents &amp; archives"><i class="fas fa-folder-open vic"></i> Documents &amp; archives <i class="fas fa-arrow-right act"></i></button>
                            <button type="button" class="lt-chip" data-v="Office furniture"><i class="fas fa-chair vic"></i> Office furniture <i class="fas fa-arrow-right act"></i></button>
                            <button type="button" class="lt-chip" data-v="Appliances"><i class="fas fa-blender vic"></i> Appliances <i class="fas fa-arrow-right act"></i></button>
                        </div>
                    </div>
                    <div class="lt-col store" id="ltStore">
                        <h3><span class="lbl"><i class="fas fa-warehouse"></i> Store long-term</span> <span class="n" id="ltStoreN">0</span></h3>
                        <div class="lt-items" id="ltStoreItems"><div class="lt-empty" id="ltEmpty">Tap items on the left to move them here.</div></div>
                    </div>
                </div>
                <div class="lt-sort-foot">
                    <div class="t"><b><span id="ltCount">0</span> item type(s)</b> for long-term storage<small id="ltList">A compact unit may suit a few boxes; furniture, appliances and inventory need more room.</small></div>
                    <a href="#lt-quote" class="sk-btn sk-btn-primary" id="ltSortBtn"><i class="fas fa-paper-plane"></i> Get a Quote for These</a>
                </div>
                <p class="lt-hint">Tip: plan around the belongings you expect to keep — it prevents paying for substantially more space than your items actually require.</p>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="lt-split rev sk-reveal">
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}');"></div>
                <div>
                    <span class="sk-eyebrow">Furniture</span>
                    <h2>Long Term Furniture Storage Without Giving Up Space</h2>
                    <p>Furniture can be hard to part with just because it isn't currently needed — a sofa from a previous home, bedroom furniture awaiting a future property, or office furnishings that no longer suit the workplace. Long-term furniture storage gives suitable pieces a dedicated place, without keeping them underfoot.</p>
                    <ul class="lt-points">
                        <li class="row-i"><div class="ic"><i class="fas fa-couch"></i></div><div><h4>Household Furniture</h4><p>Sofas, beds, dining furniture, wardrobes and tables kept separate while preserving the option to use them later.</p></div></li>
                        <li class="row-i"><div class="ic"><i class="fas fa-chair"></i></div><div><h4>Office &amp; Commercial Furniture</h4><p>Desks, chairs and shelving after a relocation or refurbishment — more practical than disposing of items that may be useful again.</p></div></li>
                    </ul>
                    <p style="font-size:13.5px;color:var(--sk-muted);margin:0;">Space depends on the number and dimensions of pieces — proper preparation matters when furniture stays stored for an extended period.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">For Business</span>
                <h2>Long Term Storage for Business Inventory</h2>
                <p>You don't always need all your stock or equipment on the shop floor or inside the main workplace. Long-term business storage adds capacity without every item staying in the primary operating location — separating storage from the space your team needs for normal activity.</p>
            </div>
            <div class="lt-use sk-reveal">
                <div class="lt-usec"><div class="ic"><i class="fas fa-store"></i></div><h4>Retail &amp; E-Commerce</h4><p>Keep suitable stock outside the main workplace until it's needed, so the floor stays clear for selling.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-building"></i></div><h4>Offices</h4><p>Store records, furniture or equipment that must be retained but isn't part of daily operations.</p></div>
                <div class="lt-usec"><div class="ic"><i class="fas fa-hammer"></i></div><h4>Contractors &amp; Projects</h4><p>Space for project-related materials and equipment that are part of ongoing work but not needed every day.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="lt-split sk-reveal">
                <div>
                    <span class="sk-eyebrow">Stay Organised</span>
                    <h2>Long Term Storage Boxes &amp; Organised Belongings</h2>
                    <p>Boxes are often the most practical way to group smaller possessions for an extended period — and clear labelling becomes increasingly useful when belongings are stored for months or longer. Grouping household items, documents, clothing and books into labelled boxes, rather than filling a room with loosely placed items, makes the whole arrangement much easier to understand.</p>
                    <p>Before storing boxes for an extended period, it helps to think about what you're likely to need first — pack and label frequently-needed items separately from belongings that may remain untouched for longer. And where appropriate, boxes can sit alongside furniture, appliances or other suitable items within the same storage arrangement.</p>
                </div>
                <div class="lt-media" style="background-image:url('{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_2.jpg') }}');"></div>
            </div>
        </div>
    </section>

    <section class="sk-section lt-feat">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center sk-eyebrow--light">Value Over Time</span>
                <h2>Finding Cost-Effective Long Term Storage</h2>
                <p style="color:rgba(255,255,255,.8);">When belongings stay stored for months or years, the cheapest option isn't always the most suitable if it gives you far more space than you need. Compare the overall arrangement — not just the advertised rate.</p>
            </div>
            <div class="lt-tips sk-reveal">
                <div class="lt-tip"><i class="fas fa-box"></i><h4>Pack into suitable boxes</h4><p>Boxing smaller belongings uses space efficiently and keeps everything grouped.</p></div>
                <div class="lt-tip"><i class="fas fa-object-group"></i><h4>Group similar items</h4><p>Grouping similar belongings makes the volume clearer and the unit easier to fill.</p></div>
                <div class="lt-tip"><i class="fas fa-ruler-combined"></i><h4>Assess the actual volume</h4><p>Judge by the real volume of your possessions to avoid unnecessary space requirements.</p></div>
            </div>
            <p class="note sk-reveal">Unit size, storage duration, access requirements and the type of belongings all affect suitability. For households and businesses alike, the objective is to pay for <b style="color:#fff;">useful capacity</b> — not simply the largest available option.</p>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">Where We Serve</span>
                <h2>Long Term Storage in Dubai, Sharjah &amp; Across the UAE</h2>
                <p>The right facility means enough space for your belongings without paying for more than you need — with options suited to both household and business requirements.</p>
            </div>
            <div class="lt-cover sk-reveal">
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Dubai</h4><p>Choose a solution based on the amount and type of belongings you need to keep for an extended period.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marker-alt"></i></div><h4>Sharjah</h4><p>Long term self storage for furniture, personal belongings, documents, equipment or business stock.</p></div>
                <div class="lt-covc"><div class="ic"><i class="fas fa-map-marked-alt"></i></div><h4>Abu Dhabi &amp; Wider UAE</h4><p>Serving customers across the UAE, including those looking for long term storage in Abu Dhabi.</p></div>
            </div>
        </div>
    </section>

    <section class="sk-section sk-section--soft">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow sk-eyebrow--center">FAQs</span>
                <h2>FAQs About Long Term Storage</h2>
            </div>
            <div class="lt-faq">
                <details open>
                    <summary>How long can I keep belongings in long term storage?</summary>
                    <div class="a">The storage period depends on your agreement and requirements. If you need to keep belongings for an extended period, discuss the expected duration with StorageKeys when arranging your storage.</div>
                </details>
                <details>
                    <summary>Is long term storage suitable for furniture?</summary>
                    <div class="a">Yes. Suitable sofas, beds, wardrobes, tables and other furniture can be stored for longer periods. The required space depends on the quantity and dimensions of the furniture.</div>
                </details>
                <details>
                    <summary>Can businesses use long term storage?</summary>
                    <div class="a">Yes. Businesses can store suitable inventory, equipment, documents, furniture and supplies when these items don't need to remain in their primary workplace.</div>
                </details>
                <details>
                    <summary>Is long term storage available in Dubai and Sharjah?</summary>
                    <div class="a">Yes. StorageKeys provides long-term storage options for customers in Dubai and Sharjah, with services also available across the wider UAE.</div>
                </details>
                <details>
                    <summary>How much space do I need for long term storage?</summary>
                    <div class="a">It depends on the type and quantity of your belongings. Providing an item list, approximate quantities or details about larger items can help determine a suitable storage unit.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="sk-section lt-quote-wrap" id="lt-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow sk-eyebrow--light">Free Quote</span>
                    <h2>Keep What Matters With Long Term Storage</h2>
                    <p>Tell us what you need to keep for months or longer — furniture, seasonal items, documents or business stock — and we'll recommend a right-sized long-term arrangement.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'source' => 'long-term-storage',
                    'defaultStorage' => 'Long Term Storage',
                    'variant' => 'business',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'showStorageSelect' => false,
                    'storingOptions' => [
                        'Furniture' => 'Furniture',
                        'Seasonal possessions' => 'Seasonal possessions',
                        'Family belongings' => 'Family belongings',
                        'Business equipment' => 'Business equipment',
                        'Excess inventory' => 'Excess inventory',
                        'Documents & archives' => 'Documents & archives',
                        'Office furniture' => 'Office furniture',
                        'Appliances' => 'Appliances',
                        'Household boxes' => 'Household boxes',
                        'Mixed personal & commercial' => 'Mixed personal & commercial',
                        'Other' => 'Other',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="lt-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#lt-quote"><i class="fas fa-warehouse"></i> Quote</a>
    </div>

</div>
@endsection
