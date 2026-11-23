<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A change to website content. Written by App\Services\Mcp\Revisions (via App\Observers\ContentRevisionObserver).
 */
class ContentRevision extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'changed' => 'array',
        'before' => 'array',
        'after' => 'array',
    ];
}
