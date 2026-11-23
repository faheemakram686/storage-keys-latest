<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A question and answer shown on one or more pages (page_slugs) by the faq_list component.
 */
class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'page_slugs', 'position', 'is_published', 'is_deleted'];

    protected $attributes = ['position' => 0, 'is_published' => false, 'is_deleted' => false];

    protected $casts = [
        'page_slugs' => 'array',
        'is_published' => 'boolean',
    ];
}
