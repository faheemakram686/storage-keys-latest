<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@hasSection('metaTitle')@yield('metaTitle')@else{{config('app.name')}} @yield('title')@endif</title>
    <meta name="description" content="@yield('metaDescription')">
    @include('ui.includes.canonical')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="google-site-verification" content="Zp2aIpnwIw7prJMAJuXbUNiN9hL5TWwC6qRXc-zIxik" />

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/x-icon" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"></noscript>

    @if(request()->is('/'))
    <link rel="preload" as="image" href="{{ asset('sk-assets/assets/images/frontend/landing-hero.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('sk-assets/assets/images/frontend/landing-hero.jpg') }}" type="image/jpeg">
    @endif

    @include('ui.includes.header')
    @yield('css')
    {{-- <style>
        .bdy-bg {
            background-image: url('sk-assets/assets/images/frontend/Logo Background.png');
        }
    </style> --}}
</head>
<body>
    {{-- <div class="bdy-bg"> --}}
        @yield('content')
        @include('ui.includes.footer')
    {{-- </div> --}}
@yield('footerInsert')

@yield('javascriptWork')
</body>
</html>