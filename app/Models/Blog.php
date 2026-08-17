<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;


    public function setStatusAttribute($value)
    {
        if($value==0){
            $value=0;
        }
        if($value==1){
            $value=1;
        }
        $this->attributes['status'] =$value;
    }

    public function getStatusAttribute($value)
    {
        if($value==1){
            $getVal='Active';
        }
        if($value==0){
            $getVal='In-Active';
        }
        return $getVal;
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->image) && $this->image !== 'empty') {
            return asset('storage/uploads/blog-images/' . $this->image);
        }

        return asset('sk-assets/assets/images/frontend/blog/Image_8.png');
    }

    public function excerpt($limit = 140)
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->description)));

        return \Illuminate\Support\Str::limit($text, $limit);
    }
}
