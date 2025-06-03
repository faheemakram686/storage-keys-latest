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