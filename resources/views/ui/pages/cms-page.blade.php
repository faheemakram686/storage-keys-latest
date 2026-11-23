@extends('ui.layouts.frontend')
@section('title', '| ' . $page->title)
@section('metaTitle', $page->seoTitle())
@section('metaDescription', $page->seoDescription(160))

@section('headExtra')
    @isset($loc)
        {{-- CMS page standing in for a location page: keep its facility and breadcrumb schema. --}}
        @include('ui.partials.location-schema', ['loc' => $loc])
    @endisset
    @if($pageSchema = $page->schemaArray())
        <script type="application/ld+json">{!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endif
@endsection

@php
    // Explicit DB flag OR auto-detect full hero in content.
    $fullBleed = $page->shouldHideBanner();
@endphp

@section('content')
@if($fullBleed)
    {{-- Edge-to-edge like static service pages: no ps-hero, no sk-container, no bl-prose box. --}}
    <div class="sk-home sk-cms-page sk-cms-page--fullbleed">
        {!! \App\Services\Mcp\ContentRenderer::render($page->content, $page) !!}
    </div>
@else
    <div class="sk-home sk-cms-page">
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
                        {!! \App\Services\Mcp\ContentRenderer::render($page->content, $page) !!}
                    </div>
                </article>
            </div>
        </section>
    </div>
@endif
@endsection
