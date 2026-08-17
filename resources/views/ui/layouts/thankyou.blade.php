<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@hasSection('metaTitle')@yield('metaTitle')@else Thank You | {{ config('app.name') }}@endif</title>
    <meta name="description" content="@yield('metaDescription')">
    <meta name="robots" content="noindex, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('ui.includes.canonical')
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/x-icon" />
    @include('ui.includes.header')
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/thank-you.css') }}">
</head>
<body class="sk-thanks-body">
    @yield('content')
    <footer class="sk-thanks-foot">
        <div class="sk-thanks-foot-in">
            <span>© {{ date('Y') }} Storage Keys</span>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/booking') }}">Book a unit</a>
            <a href="tel:+971565018785">+971 56 501 8785</a>
        </div>
    </footer>
</body>
</html>
