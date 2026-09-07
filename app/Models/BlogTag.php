<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_tag_blog', 'blog_tag_id', 'blog_id');
    }
}
