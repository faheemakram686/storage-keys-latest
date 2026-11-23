<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One block of a DB page: raw HTML or a component (quote form, explore-more links, FAQ list).
 * Edited with the MCP section tools; App\Services\Mcp\Sections rebuilds pages.content from these.
 */
class PageSection extends Model
{
    protected $fillable = ['page_id', 'position', 'type', 'name', 'component', 'attrs', 'html', 'is_visible', 'is_deleted'];

    protected $casts = [
        'attrs' => 'array',
        'is_visible' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0);
    }
}
