<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarcodeLabel extends Model
{
    use HasFactory;
    public function moverequest()
    {
        return $this->belongsTo(MoveInRequest::class, 'request_id', 'id');
    }

    public function getStatusAttribute($value)
    {
        if($value==2){
            $getVal='Moved-Out';
        }
        if($value==1){
            $getVal='Moved';
        }
        if($value==0){
            $getVal='Pending';
        }
        return $getVal;
    }
}
