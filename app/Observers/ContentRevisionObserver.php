<?php

namespace App\Observers;

use App\Services\Mcp\ContentTypes;
use App\Services\Mcp\Revisions;
use App\Services\Mcp\SiteCache;
use App\Services\Mcp\SlugRedirects;
use App\Services\Mcp\Versioning;
use Illuminate\Database\Eloquent\Model;

/**
 * Records a revision for every write to tracked content (App\Services\Mcp\ContentTypes),
 * from the MCP tools and from the admin panel alike.
 */
class ContentRevisionObserver
{
    public function updating(Model $model): void
    {
        Versioning::bump($model);
    }

    public function created(Model $model): void
    {
        Revisions::record($model, 'created', null, Revisions::snapshot($model));
    }

    public function updated(Model $model): void
    {
        $before = Revisions::snapshot($model, true);
        $after = Revisions::snapshot($model);

        $event = 'updated';
        $type = ContentTypes::typeOf($model);
        if ($type !== null) {
            $trash = ContentTypes::get($type)['trash'];
            if ($model->wasChanged($trash)) {
                $event = (int) $model->getAttribute($trash) === 1 ? 'trashed' : 'restored';
            }
        }

        Revisions::record($model, $event, $before, $after);
        SlugRedirects::handle($model);
    }

    public function deleted(Model $model): void
    {
        Revisions::record($model, 'purged', Revisions::snapshot($model, true), null);
    }

    /** Any content write makes cached audit results stale. */
    public function saved(Model $model): void
    {
        SiteCache::touch();
    }
}
