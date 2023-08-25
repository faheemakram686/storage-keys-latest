<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->select(['id', 'name']);
    }

    public function setDefault()
    {
        // Set all records to 0 except for the current one
        $this->where('id', '!=', $this->id)->update(['is_defult' => 0]);

        // Set the current record to 1
        $this->is_defult = 1;
        $this->save();
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


    public function setIsDefultAttribute($value)
    {
        if($value==0){
            $value=0;
        }
        if($value==1){
            $value=1;
        }
        $this->attributes['is_defult'] =$value;
    }

    public function getIsDefultAttribute($value)
    {
        if($value==1){
            $getVal='Default';
        }
        if($value==0){
            $getVal='Not Default';
        }
        return $getVal;
    }

}
