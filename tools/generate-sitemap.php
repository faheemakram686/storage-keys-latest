<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$base = rtrim((string) config('app.canonical_url'), '/');
if (str_starts_with($base, 'http://')) {
    $base = 'https://' . substr($base, 7);
}

$staticPages = [
    '/',
    '/storage-options',
    '/personal-storage',
    '/residential-storage',
    '/furniture-storage',
    '/box-storage',
    '/appliance-storage',
    '/business-storage',
    '/warehouse-storage',
    '/climate-controlled-storage',
    '/moving-services',
    '/luggage-storage',
    '/car-storage',
    '/shop',
    '/booking',
    '/blogs',
    '/about-us',
    '/contact-us',
    '/frequently-asked-questions',
    '/privacy-policy',
    '/security-policy',
    '/support-policy',
    '/cookie-policy',
    '/terms-of-service',
];

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($staticPages as $page) {
    $loc = $base . ($page === '/' ? '/' : $page);
    $xml .= "  <url>\n";
    $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    $xml .= "  </url>\n";
}

try {
    $blogs = App\Models\Blog::query()
        ->where('is_deleted', 0)
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->orderByDesc('updated_at')
        ->get(['slug', 'updated_at', 'created_at']);

    foreach ($blogs as $blog) {
        $loc = $base . '/blogs/' . $blog->slug;
        $lastmod = optional($blog->updated_at ?? $blog->created_at)->format('Y-m-d');
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
        if ($lastmod) {
            $xml .= '    <lastmod>' . $lastmod . "</lastmod>\n";
        }
        $xml .= "  </url>\n";
    }
} catch (Throwable $e) {
    fwrite(STDERR, 'Blog fetch skipped: ' . $e->getMessage() . PHP_EOL);
}

$xml .= '</urlset>' . "\n";

$out = __DIR__ . '/../public/sitemap.xml';
file_put_contents($out, $xml);
echo "Wrote {$out}\n";
