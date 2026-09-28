<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

/**
 * Renders published CMS pages from DB.
 * Existing static Blade routes always win — this only handles unmatched slugs
 * that exist as published pages.
 */
class CmsPageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->first();

        if (!$page) {
            abort(404);
        }

        return view('ui.pages.cms-page', [
            'page' => $page,
        ]);
    }
}
