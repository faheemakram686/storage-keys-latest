{{--
    Canonical URL for the current page.

    Always absolute HTTPS to the live domain (config/app.php -> canonical_url)
    rather than the requested host, so www / non-www, http / https, staging and
    localhost all resolve to one indexable URL. Query strings are dropped.

    Override with: @section('canonical', 'https://storagekeys.com/...')
--}}
@php
    $canonicalBase = rtrim((string) config('app.canonical_url', 'https://storagekeys.com'), '/');
    if (str_starts_with($canonicalBase, 'http://')) {
        $canonicalBase = 'https://' . substr($canonicalBase, 7);
    }
    if ($canonicalBase === '' || !preg_match('#^https?://#i', $canonicalBase)) {
        $canonicalBase = 'https://storagekeys.com';
    }

    $canonicalPath = trim(request()->getPathInfo(), '/');
    $canonicalUrl = $canonicalBase . ($canonicalPath === '' ? '/' : '/' . $canonicalPath);
@endphp
@hasSection('canonical')
    @php
        $override = trim((string) $__env->yieldContent('canonical'));
        if ($override !== '') {
            if (str_starts_with($override, 'http://')) {
                $override = 'https://' . substr($override, 7);
            } elseif (str_starts_with($override, '/')) {
                $override = $canonicalBase . $override;
            }
            $canonicalUrl = $override;
        }
    @endphp
@endhasSection
<link rel="canonical" href="{{ $canonicalUrl }}">
