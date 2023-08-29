<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>{{config('app.name')}} @yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/x-icon" />

    <!-- Font Icons css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/booking.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/responsive.css') }}">
    
    <link rel="stylesheet" href="{{ asset('sk-assets/css/custom.css') }}" />
    <!-- HEADER AREA START (header-5) -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/toastr.css') }}"/>
    
</head>
<body>
    @include('ui.includes.header2')
@yield('content')
@include('ui.includes.footer')
@yield('footerInsert')

@yield('javascriptWork')
</body>
</html>