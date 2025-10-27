<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'q_product_id',
        'sku',
        'p_name',
        'detail',
        'pur_price',
        'sell_price',
        'disc_type',
        'disc_amount',
        'qty',
        'image',
        'status',
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
