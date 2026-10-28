<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    @include('ui.includes.google-tag-manager', ['deferTags' => true])
    @include('ui.includes.google-analytics', ['deferTags' => true])
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@hasSection('metaTitle')@yield('metaTitle')@else{{config('app.name')}} @yield('title')@endif</title>
    <meta name="description" content="@yield('metaDescription')">
    @hasSection('robots')
    <meta name="robots" content="@yield('robots')">
    @endif
    @include('ui.includes.canonical')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="google-site-verification" content="Zp2aIpnwIw7prJMAJuXbUNiN9hL5TWwC6qRXc-zIxik" />

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/x-icon" />
    @php $isHome = trim(request()->path(), '/') === ''; @endphp
    @include('ui.includes.header-assets')
    @yield('css')
    @include('ui.includes.page-css')
    @if($isHome)
    {{-- Home: critical + brand CSS are inlined so nothing blocks first paint; full sheets load async (FCP/LCP).
         Relative url()s in the sheets point at sk-assets/, so they are made absolute once inlined. --}}
    @php
        $inlineCss = fn (string $file) => str_replace(
            '../../assets/',
            asset('sk-assets/assets') . '/',
            (string) @file_get_contents(public_path('sk-assets/css/frontend/' . $file))
        );
    @endphp
    <style>{!! $inlineCss('home-critical.css') !!}</style>
    <link rel="preload" href="{{ asset('sk-assets/css/frontend/landing-page.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/landing-page.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/landing-page.css') }}"></noscript>
    <link rel="preload" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}"></noscript>
    <link rel="preload" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}"></noscript>
    <style>{!! $inlineCss('brand.css') !!}</style>
    @else
    {{-- Site chrome last so header/footer match on every page; brand system after it --}}
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/brand.css') }}">
    @endif
    @yield('headExtra')
</head>
<body class="{{ $isHome ? 'sk-is-home' : '' }}">
    @include('ui.includes.google-tag-manager-noscript')
    @include('ui.includes.header')
    @yield('content')
    @include('ui.includes.footer')
@yield('footerInsert')

@yield('javascriptWork')
</body>
</html>
