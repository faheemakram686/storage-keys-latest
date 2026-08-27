@extends('ui.layouts.frontend')
@section('title', '| ' . $blog->title)
@section('metaTitle', $blog->title . ' | Storage Keys')
@section('metaDescription', $blog->excerpt(160))

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb">
                <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i>
                <a href="{{ url('/blogs') }}">Blogs</a> <i class="fas fa-chevron-right"></i>
                <span>{{ \Illuminate\Support\Str::limit($blog->title, 42) }}</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Blog</span>
            <h1>{{ $blog->title }}</h1>
            <p class="lead">{{ optional($blog->created_at)->format('F j, Y') }} · StorageKeys</p>
        </div>
    </section>

    <!-- ============ ARTICLE ============ -->
    <section class="sk-section">
        <div class="sk-container">
            <div class="bl-layout sk-reveal">
                <article class="bl-article">
                    <div class="bl-cover" style="background-image:url('{{ $blog->image_url }}');"></div>
                    <div class="bl-meta">
                        <span><i class="far fa-calendar-alt"></i> {{ optional($blog->created_at)->format('F j, Y') }}</span>
                        <span><i class="far fa-user"></i> StorageKeys</span>
                    </div>
                    <div class="bl-prose">
                        {!! $blog->description !!}
                    </div>
                </article>

                <aside class="bl-side">
                    @if($recent->count())
                        <div class="bl-widget">
                            <span class="h4">Recent Articles</span>
                            <ul class="bl-recent">
                                @foreach($recent as $item)
                                    <li>
                                        <a href="{{ route('blogDetails', $item->slug) }}" class="thumb" style="background-image:url('{{ $item->image_url }}');"></a>
                                        <div>
                                            <a href="{{ route('blogDetails', $item->slug) }}">{{ $item->title }}</a>
                                            <span>{{ optional($item->created_at)->format('M j, Y') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="bl-widget bl-help">
                        <span class="h4">Need storage help?</span>
                        <p>Tell us what you need to store and our team will recommend a practical option.</p>
                        <a href="tel:+971565018785" class="sk-btn sk-btn-primary"><i class="fas fa-phone"></i> Call Us</a>
                        <a href="tel:8005397" class="sk-btn sk-btn-ghost"><i class="fas fa-phone"></i> Toll Free: 800 5397</a>
                        <a href="{{ url('/contact-us') }}" class="ab-svc-link">Contact Us <i class="fas fa-arrow-right"></i></a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <!-- ============ STICKY MOBILE BAR ============ -->
    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="{{ url('/contact-us') }}"><i class="fas fa-envelope"></i> Quote</a>
    </div>

</div>
@endsection
