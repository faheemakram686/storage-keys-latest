{{-- Page-specific CSS only (keeps homepage free of other pages' stylesheets) --}}
@php
    $path = trim(request()->path(), '/');
    if ($path === '') {
        $path = '/';
    }

    $css = [];

    if ($path === '/') {
        $css[] = 'landing-page.css';
    } elseif ($path === 'business-storage') {
        $css[] = 'business-storage.css';
    } elseif ($path === 'warehouse-storage') {
        $css[] = 'warehouse-storage.css';
    } elseif ($path === 'personal-storage' || $path === 'box-storage' || $path === 'appliance-storage') {
        $css[] = 'personal-storage.css';
    } elseif ($path === 'furniture-storage') {
        $css[] = 'personal-storage.css';
        $css[] = 'furniture-storage.css';
    } elseif ($path === 'residential-storage') {
        $css[] = 'residential-storage.css';
    } elseif ($path === 'climate-controlled-storage') {
        $css[] = 'climate-controlled-storage.css';
    } elseif (in_array($path, ['moving-services', 'luggage-storage', 'car-storage'], true)) {
        $css[] = 'business-storage.css';
    } elseif ($path === 'about-us') {
        $css[] = 'personal-storage.css';
        $css[] = 'business-storage.css';
        $css[] = 'about-us.css';
    } elseif ($path === 'contact-us') {
        $css[] = 'personal-storage.css';
        $css[] = 'business-storage.css';
        $css[] = 'about-us.css';
        $css[] = 'contact-us.css';
    } elseif ($path === 'storage-options') {
        $css[] = 'personal-storage.css';
        $css[] = 'business-storage.css';
        $css[] = 'about-us.css';
    } elseif ($path === 'blogs' || strpos($path, 'blogs/') === 0) {
        $css[] = 'personal-storage.css';
        $css[] = 'business-storage.css';
        $css[] = 'blogs.css';
    } elseif (in_array($path, [
        'privacy-policy',
        'security-policy',
        'support-policy',
        'cookie-policy',
        'terms-of-service',
        'frequently-asked-questions',
    ], true)) {
        $css[] = 'personal-storage.css';
        $css[] = 'privacy-policy.css';
    } elseif ($path === 'shop' || $path === 'product-details') {
        $css[] = 'personal-storage.css';
        $css[] = 'shop.css';
    } elseif ($path === 'cart' || $path === 'checkout') {
        $css[] = 'personal-storage.css';
        $css[] = 'cart.css';
    } elseif ($path === 'booking' || strpos($path, 'reservation/') === 0) {
        // booking.css + booking-layout.css come from @section('css') on the page
        $css[] = 'personal-storage.css';
    } else {
        // Safe fallback for any other frontend page using the shared service layout
        $css[] = 'personal-storage.css';
    }

    $css = array_values(array_unique($css));
@endphp

@foreach($css as $file)
<link rel="stylesheet" href="{{ asset('sk-assets/css/frontend/' . $file) }}">
@endforeach
