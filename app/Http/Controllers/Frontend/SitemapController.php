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
