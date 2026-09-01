@php
    $isHome = trim(request()->path(), '/') === '';
@endphp

@if($isHome)
    {{-- Home: fonts only here; page CSS loaded in layout (landing-page → site-chrome → icons) --}}
    @include('ui.includes.fonts-home')
@else
    @include('ui.includes.fonts')

<!-- Font Icons css -->
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/font-icons.css') }}">
@endif

@if(!$isHome)
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/plugins.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('sk-assets/css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('sk-assets/css/toastr.css') }}"/>
@endif
