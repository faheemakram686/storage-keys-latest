<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;
    public function remind()
    {
        return $this->belongsTo(User::class, 'reminder_to', 'id')->select(['id', 'first_name','last_name']);
    }

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00',
        'reminder_date'  => 'datetime:Y-m-d H:00',
    ];

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
}
