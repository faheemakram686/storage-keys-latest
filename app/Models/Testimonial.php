<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A real customer review supplied by the owner (never generated). Shown by the testimonials component;
 * never marked up as Review schema.
 */
class Testimonial extends Model
{
    protected $fillable = ['name', 'text', 'source', 'source_url', 'review_date', 'is_published', 'is_deleted'];

    protected $attributes = ['is_published' => false, 'is_deleted' => false];

    protected $casts = [
        'review_date' => 'date',
        'is_published' => 'boolean',
    ];
}
