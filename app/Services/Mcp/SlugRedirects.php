<?php

namespace App\Services\Mcp;

use App\Models\Blog;
use App\Models\Page;
use App\Models\UrlRedirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Changing the slug of a published page or post adds a 301 from the old URL (via ApplyUrlRedirects),
 * whichever tool or screen made the change.
 */
class SlugRedirects
{
    public static function handle(Model $model): void
    {
        if (!$model->wasChanged('slug') || (int) $model->getRawOriginal('status') !== 1 || !Schema::hasTable('url_redirects')) {
            return;
        }
        $prefix = $model instanceof Blog ? '/blogs/' : ($model instanceof Page ? '/' : null);
        $oldSlug = (string) ($model->getOriginal('slug') ?? '');
        if ($prefix === null || $oldSlug === '') {
            return;
        }

        $from = UrlRedirect::normalizePath($prefix . $oldSlug);
        $to = UrlRedirect::normalizePath($prefix . $model->slug);

        // Moving back to an old URL: drop the redirect that would now loop.
        UrlRedirect::query()->where('from_path', $to)->update(['is_active' => false]);
        // Older redirects that pointed at the old URL now go straight to the new one.
        UrlRedirect::query()->where('to_url', $from)->update(['to_url' => $to]);

        UrlRedirect::query()->updateOrCreate(['from_path' => $from], ['to_url' => $to, 'status_code' => 301, 'is_active' => true]);
    }
}
