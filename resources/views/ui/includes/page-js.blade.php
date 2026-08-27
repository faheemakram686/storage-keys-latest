@php
    $path = trim(request()->path(), '/');
    if ($path === '') {
        $path = '/';
    }

    $scripts = ['reveal.js'];

    if ($path === '/') {
        $scripts[] = 'business-storage.js';
        $scripts[] = 'landing-gallery.js';
        $scripts[] = 'landing-reviews.js';
    } elseif ($path === 'business-storage') {
        $scripts[] = 'business-storage.js';
    } elseif (in_array($path, ['personal-storage', 'box-storage', 'appliance-storage', 'climate-controlled-storage'], true)) {
        $scripts[] = 'business-storage.js';
    } elseif ($path === 'warehouse-storage') {
        $scripts[] = 'warehouse-storage.js';
    } elseif ($path === 'furniture-storage') {
        $scripts[] = 'furniture-storage.js';
    } elseif ($path === 'residential-storage') {
        $scripts[] = 'residential-storage.js';
    } elseif (in_array($path, ['customer-login', 'customer/register', 'customer/forgot-password', 'admin/users/login'], true) || str_starts_with($path, 'customer/reset-password')) {
        $scripts[] = 'password-toggle.js';
    }

    $scripts = array_values(array_unique($scripts));
@endphp

@foreach($scripts as $file)
<script defer src="{{ asset('sk-assets/js/frontend/' . $file) }}"></script>
@endforeach
