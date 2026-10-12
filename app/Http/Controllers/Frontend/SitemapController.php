<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SitemapBuilder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(SitemapBuilder $sitemap): Response
    {
        return response($sitemap->xml(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
