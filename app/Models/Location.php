<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
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

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->select(['id', 'city_name','country_id']);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->select(['id', 'name']);
    }

    public function setIsDefaultAttribute($value)
    {
        if($value==false){
            $value=false;
        }
        if($value==true){
            $value=true;
        }
        $this->attributes['is_default'] =$value;
    }

    public function getIsDefaultAttribute($value)
    {
        if($value==true){
            $getVal='Default';
        }
        if($value==false){
            $getVal='Not Default';
        }
        return $getVal;
    }




}
