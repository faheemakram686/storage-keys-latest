{{--
    Canonical URL for the current page.

    Always absolute to the live domain (config/app.php -> canonical_url) rather than
    to the requested host, so www / non-www, http / https, staging and localhost all
    resolve to the same single indexable URL. Query strings are dropped, so filtered
    and paginated variants (e.g. /shop?category=boxes&page=2) consolidate onto /shop.

    A page can override the whole URL with:  @section('canonical', 'https://...')
--}}
@php
    $canonicalPath = trim(request()->getPathInfo(), '/');
    $canonicalUrl  = rtrim(config('app.canonical_url'), '/') . ($canonicalPath === '' ? '/' : '/' . $canonicalPath);
@endphp
<link rel="canonical" href="@hasSection('canonical')@yield('canonical')@else{{ $canonicalUrl }}@endif">
