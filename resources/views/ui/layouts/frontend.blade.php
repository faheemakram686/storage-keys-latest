<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
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
    {{-- Home: small critical CSS inline path, full sheets load async (FCP/LCP) --}}
    <link rel="preload" href="{{ asset('sk-assets/css/frontend/home-critical.css') }}" as="style" fetchpriority="high">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/home-critical.css') }}">
    <link rel="preload" href="{{ asset('sk-assets/css/frontend/landing-page.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/landing-page.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/landing-page.css') }}"></noscript>
    <link rel="preload" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}"></noscript>
    <link rel="preload" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}"></noscript>
    @else
    {{-- Site chrome last so header/footer match on every page --}}
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/site-chrome.css') }}">
    @endif
</head>
<body>
    @include('ui.includes.header')
    @yield('content')
    @include('ui.includes.footer')
@yield('footerInsert')

@yield('javascriptWork')
</body>
</html>
