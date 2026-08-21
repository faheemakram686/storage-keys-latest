{{-- Page-specific CSS (kept out of style.css to reduce render-blocking on every page) --}}
@php
    $path = request()->path();
@endphp

@if(request()->is('business-storage', 'storage-options'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/business-storage.css') }}">
@endif

@if(request()->is('warehouse-storage'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/warehouse-storage.css') }}">
@endif

@if(request()->is('furniture-storage'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/furniture-storage.css') }}">
@endif

@if(request()->is('climate-controlled-storage'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/climate-controlled-storage.css') }}">
@endif

@if(request()->is('about-us'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/about-us.css') }}">
@endif

@if(request()->is('storage-options'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/about-us.css') }}">
@endif

@if(request()->is('contact-us'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/contact-us.css') }}">
@endif

@if(request()->is('blogs', 'blogs/*') || request()->routeIs('blogDetails'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/blogs.css') }}">
@endif

@if(request()->is('privacy-policy', 'security-policy', 'support-policy', 'cookie-policy', 'terms-of-service', 'frequently-asked-questions'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/privacy-policy.css') }}">
@endif

@if(request()->is('booking', 'reservation'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/booking.css') }}">
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/booking-layout.css') }}">
@endif

@if(request()->is('shop', 'product-details', 'product-details/*'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/shop.css') }}">
@endif

@if(request()->is('cart', 'checkout'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/cart.css') }}">
@endif

@if(request()->is('moving-services', 'luggage-storage', 'car-storage'))
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/business-storage.css') }}">
@endif
