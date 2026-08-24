@php
    $path = trim(request()->path(), '/');
    if ($path === '') {
        $path = '/';
    }

    $scripts = ['reveal.js'];

    if ($path === '/') {
        $scripts[] = 'business-storage.js';
    } elseif ($path === 'business-storage') {
        $scripts[] = 'business-storage.js';
    } elseif (in_array($path, ['personal-storage', 'box-storage', 'appliance-storage', 'climate-controlled-storage'], true)) {
        $scripts[] = 'business-storage.js';
    } elseif ($path === 'warehouse-storage') {
        $scripts[] = 'warehouse-storage.js';
    } elseif ($path === 'furniture-storage') {
        $scripts[] = 'furniture-storage.js';
    }

    $scripts = array_values(array_unique($scripts));
@endphp

@foreach($scripts as $file)
<script defer src="{{ asset('sk-assets/js/frontend/' . $file) }}"></script>
@endforeach
