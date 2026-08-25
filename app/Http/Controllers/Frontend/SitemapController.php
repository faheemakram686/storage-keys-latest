<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $base = rtrim((string) config('app.canonical_url'), '/');
        if (str_starts_with($base, 'http://')) {
            $base = 'https://' . substr($base, 7);
        }

        $staticPages = [
            ['loc' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => '/storage-options', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => '/personal-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/residential-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/furniture-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/box-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/appliance-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/business-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/warehouse-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/climate-controlled-storage', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/moving-services', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/luggage-storage', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/car-storage', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/shop', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => '/booking', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => '/blogs', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => '/about-us', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/contact-us', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/frequently-asked-questions', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => '/privacy-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/security-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/support-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/cookie-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/terms-of-service', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at', 'created_at']);

        return response()
            ->view('ui.sitemap', compact('base', 'staticPages', 'blogs'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
