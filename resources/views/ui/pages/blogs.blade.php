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
        </div>
    </section>

    <!-- ============ LIST ============ -->
    <section class="sk-section bl-list-section" id="bl-list">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Our Blog</span>
                <h2>Latest Articles</h2>
                <p>Guides and updates from the StorageKeys team to help you plan storage, moving and packing with less hassle.</p>
            </div>

            @if($blogs->count())
                @php
                    $showFeatured = $blogs->onFirstPage() && $blogs->count() > 1;
                    $featured = $showFeatured ? $blogs->first() : null;
                @endphp

                @if($featured)
                    <article class="bl-featured sk-reveal">
                        <a href="{{ route('blogDetails', $featured->slug) }}" class="bl-featured-media" aria-label="{{ $featured->title }}">
                            <img
                                src="{{ $featured->image_url }}"
                                alt="{{ $featured->title }}"
                                width="800"
                                height="520"
                                loading="eager"
                                decoding="async"
                            >
                        </a>
                        <div class="bl-featured-body">
                            <div class="bl-card-meta">
                                <time datetime="{{ optional($featured->created_at)->toDateString() }}">
                                    <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    {{ optional($featured->created_at)->format('F j, Y') }}
                                </time>
                                <span class="bl-card-tag">Featured</span>
                            </div>
                            <h3 class="bl-featured-title">
                                <a href="{{ route('blogDetails', $featured->slug) }}">{{ $featured->title }}</a>
                            </h3>
                            <p class="bl-featured-excerpt">{{ $featured->excerpt(220) }}</p>
                            <a href="{{ route('blogDetails', $featured->slug) }}" class="bl-card-more">
                                Read article <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                @endif

                <div class="bl-grid sk-reveal">
                    @foreach($blogs as $index => $blog)
                        @if($featured && $index === 0)
                            @continue
                        @endif
                        <article class="bl-card">
                            <a href="{{ route('blogDetails', $blog->slug) }}" class="bl-card-media" aria-label="{{ $blog->title }}">
                                <img
                                    src="{{ $blog->image_url }}"
                                    alt="{{ $blog->title }}"
                                    width="640"
                                    height="400"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>
                            <div class="bl-card-body">
                                <div class="bl-card-meta">
                                    <time datetime="{{ optional($blog->created_at)->toDateString() }}">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                        {{ optional($blog->created_at)->format('M j, Y') }}
                                    </time>
                                </div>
                                <h3 class="bl-card-title">
                                    <a href="{{ route('blogDetails', $blog->slug) }}">{{ $blog->title }}</a>
                                </h3>
                                <p class="bl-card-excerpt">{{ $blog->excerpt(125) }}</p>
                                <a href="{{ route('blogDetails', $blog->slug) }}" class="bl-card-more">
                                    Read more <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
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
                        <a href="tel:+971565018785"><i class="fas fa-phone"></i> +971 56 501 8785</a>
                        <a href="tel:8005397"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
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
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#bl-quote"><i class="fas fa-box-open"></i> Quote</a>
    </div>

</div>
@endsection
