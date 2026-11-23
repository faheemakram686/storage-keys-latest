<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_deleted'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category_blog', 'blog_category_id', 'blog_id');
    }
}
