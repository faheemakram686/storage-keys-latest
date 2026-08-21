@extends('ui.layouts.frontend')
@section('title', '| Blogs')
@section('metaTitle', 'Storage Tips & Guides | Storage Keys')
@section('metaDescription', 'Practical storage tips, packing guides and advice to help you store smarter in Dubai, Sharjah and across the UAE.')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <span>Blogs</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Storage Tips &amp; Guides</span>
            <h1>Advice to Help You <span>Store Smarter</span></h1>
            <p class="lead">Practical tips on packing, choosing the right unit and getting the most from your storage space in Dubai, Sharjah and across the UAE.</p>
            <div class="ps-hero-cta">
                <a href="#bl-list" class="sk-btn sk-btn-primary"><i class="fas fa-newspaper"></i> Read Articles</a>
                <a href="{{ url('/contact-us') }}" class="sk-btn sk-btn-ghost"><i class="fas fa-file-invoice-dollar"></i> Get A Quote</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-box-open"></i> Packing tips</span>
                <span class="ps-hbadge"><i class="fas fa-ruler-combined"></i> Choosing a unit</span>
                <span class="ps-hbadge"><i class="fas fa-briefcase"></i> Business storage</span>
                <span class="ps-hbadge"><i class="fas fa-home"></i> Home storage</span>
            </div>
        </div>
    </section>

    <!-- ============ TRUST ============ -->
    <div class="ps-trust ct-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-lightbulb"></i> Practical guides</div>
                <div class="ps-trust-i"><i class="fas fa-boxes"></i> Packing advice</div>
                <div class="ps-trust-i"><i class="fas fa-warehouse"></i> Unit size help</div>
                <div class="ps-trust-i"><i class="fas fa-map-marker-alt"></i> UAE storage tips</div>
            </div>
        </div>
    </div>

    <!-- ============ LIST ============ -->
    <section class="sk-section" id="bl-list">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Our Blog</span>
                <h2>Latest Articles</h2>
                <p>Guides and updates from the StorageKeys team to help you plan storage, moving and packing with less hassle.</p>
            </div>

            @if($blogs->count())
                <div class="sk-blog sk-reveal">
                    @foreach($blogs as $blog)
                        <article class="sk-blogcard">
                            <a href="{{ route('blogDetails', $blog->slug) }}" class="img" style="background-image:url('{{ $blog->image_url }}');" aria-label="{{ $blog->title }}"></a>
                            <div class="b">
                                <div class="meta"><i class="far fa-calendar-alt"></i> {{ optional($blog->created_at)->format('F j, Y') }}</div>
                                <h3><a href="{{ route('blogDetails', $blog->slug) }}">{{ $blog->title }}</a></h3>
                                <p>{{ $blog->excerpt() }}</p>
                                <a href="{{ route('blogDetails', $blog->slug) }}">Read more about {{ $blog->title }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </article>
                    @endforeach
                </div>
                @if($blogs->hasPages())
                    <div class="bl-pagination">
                        {{ $blogs->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @else
                <div class="bl-empty">
                    <div class="ic"><i class="fas fa-newspaper"></i></div>
                    <h3>New articles coming soon</h3>
                    <p>We are preparing storage tips and guides. In the meantime, contact our team and we will help you find the right storage option.</p>
                    <a href="{{ url('/contact-us') }}" class="sk-btn sk-btn-primary"><i class="fas fa-envelope"></i> Contact Us</a>
                </div>
            @endif
        </div>
    </section>

    <!-- ============ QUOTE CTA ============ -->
    <section class="sk-section" id="bl-quote" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="svc-quote so-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Need help choosing storage?</h2>
                    <p>If you are planning a move, freeing up space at home or storing business inventory, StorageKeys can help you find a practical option in Sharjah, Dubai and across the UAE.</p>
                    <ul class="so-quote-points">
                        <li><i class="fas fa-check"></i> Personal, business and warehouse storage</li>
                        <li><i class="fas fa-check"></i> Climate-controlled units</li>
                        <li><i class="fas fa-check"></i> Luggage storage and car storage</li>
                        <li><i class="fas fa-check"></i> Moving and packing support</li>
                    </ul>
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
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request a Free Quote',
                    'subtitle' => 'Share a few details and we will get back to you shortly.',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'source' => 'blogs',
                ])
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#bl-quote"><i class="fas fa-box-open"></i> Quote</a>
    </div>

</div>
@endsection
