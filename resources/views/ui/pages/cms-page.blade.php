@extends('ui.layouts.frontend')
@section('title', '| ' . $page->title)
@section('metaTitle', $page->seoTitle())
@section('metaDescription', $page->seoDescription(160))

@section('content')
<div class="sk-home">

    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb">
                <a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i>
                <span>{{ \Illuminate\Support\Str::limit($page->title, 48) }}</span>
            </div>
            <h1>{{ $page->title }}</h1>
        </div>
    </section>

    <section class="sk-section">
        <div class="sk-container">
            <article class="bl-article sk-reveal" style="max-width:820px;margin:0 auto;">
                <div class="bl-prose">
                    {!! $page->content !!}
                </div>
            </article>
        </div>
    </section>

</div>
@endsection
