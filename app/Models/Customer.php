<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable;


    protected $table = 'customers';

    protected $fillable = ['email',  'password','company_name', 'status'];

    protected $hidden = ['password',  'remember_token'];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'id', 'customer_id');
    }
    public function primaryContact()
    {
        return $this->contact()->where('contact_type','=', 'primary');
    }

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
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
