<?php

namespace App\Models;

use App\Traits\StatusChangeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    use StatusChangeTrait;

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];
    public function storageunit()
    {
        return $this->belongsTo(StorageUnit::class, 'su_id', 'id');
    }
    public function userresponsible()
    {
        return $this->belongsTo(User::class, 'user_res_id', 'id')->select(['id', 'first_name','last_name']);
    }
    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'status', 'id')->select(['id', 'title']);
    }
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source', 'id')->select(['id', 'title']);
    }
    public function termLength()
    {
        return $this->belongsTo(TermLength::class, 'price', 'id');
    }



//    public function setStatusAttribute($value)
//    {
//        if($value==1){
//            $value=1;
//        }
//        if($value==2){
//            $value=2;
//        }
//        if($value==3){
//            $value=3;
//        }
//        if($value==4){
//            $value=4;
//        }
//        if($value==5){
//            $value=5;
//        }
//        $this->attributes['status'] =$value;
//    }
//
//    public function getStatusAttribute($value)
//    {
//        if($value==1){
//            $getVal='OPEN - quoted';
//        }
//        if($value==2){
//            $getVal='OPEN - NotContacted';
//        }
//        if($value==3){
//            $getVal='OPEN - AttemptedContact';
//        }
//        if($value==4){
//            $getVal='OPEN - Contacted';
//        }
//        if($value==5){
//            $getVal='CLOSED - Disqualified';
//        }
//
//        return $getVal;
//    }
}
