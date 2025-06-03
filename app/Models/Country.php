<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

//    public function setDefault()
//    {
//        // Set all records to 0 except for the current one
//        $this->where('id', '!=', $this->id)->update(['is_default' => false]);
//
//        // Set the current record to 1
//        $this->is_default = true;
//        $this->save();
//    }

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
