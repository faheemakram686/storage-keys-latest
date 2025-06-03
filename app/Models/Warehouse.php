<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;


    public function loc()
    {
        return $this->belongsTo(Location::class, 'loc_id', 'id')->select(['id', 'loc_name','city_id','country_id']);
    }
    public function storageUnit()
    {
        return $this->hasMany(StorageUnit::class, 'wh_id', 'id');
    }
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
