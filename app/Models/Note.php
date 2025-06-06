<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id', 'first_name','last_name']);
    }
    protected $casts = [

        'created_at' => 'datetime:d M, Y H:00',
    ];
}
