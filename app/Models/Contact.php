<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Contact extends Authenticatable
{

    use HasFactory, Notifiable, HasApiTokens;


    protected $table = 'contacts';

    protected $guard = "contact";

    protected $fillable = [
        'customer_id', 'first_name', 'last_name', 'email', 'password', 'status'
    ];

    protected $hidden = ['password',  'remember_token'];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];

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
