<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function userRensonsible()
    {
        return $this->belongsTo(\App\Models\Core\Auth\User::class, 'user_id', 'id');
    }

    public function estimate()
    {
        return $this->belongsTo(Estimate::class, 'estimate_id', 'id');
    }



    public function contractTemplate()
    {
        return $this->belongsTo(ContractTemplate::class, 'template_id', 'id');
    }

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];
    public function barcode()
    {
        return $this->hasMany(BarcodeLabel::class, 'contract_id', 'id');
    }


    public function setIsSignedtatusAttribute($value)
    {
        if($value==0){
            $value=0;
        }
        if($value==1){
            $value=1;
        }
        $this->attributes['is_sign'] =$value;
    }

    public function getIsSignedAttribute($value)
    {
        if($value==1){
            $getVal='Signed';
        }
        if($value==0){
            $getVal='Not Signed';
        }
        return $getVal;
    }



    public function setStatusAttribute($value)
    {
        if($value==0){
            $value=0;
        }
        if($value==1){
            $value=1;
        }
        if($value==2){
            $value=2;
        }
        if($value==3){
            $value=3;
        }

        $this->attributes['status'] =$value;
    }

    public function getStatusAttribute($value)
    {
        if($value==3){
            $getVal='Approved';
        }
        if($value==2){
            $getVal='Approved Level 2';
        }
        if($value==1){
            $getVal='Approved Level 1';
        }
        if($value==0){
            $getVal='Not Approved';
        }
        return $getVal;
    }


}
