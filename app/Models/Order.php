<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
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
