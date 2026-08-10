<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable;


    protected $table = 'customers';

    protected $fillable = ['email','customer_name','customer_type','password','q_customer_id','company_name','phone','status'];
    
    protected $appends = ['full_display_name'];

    protected $hidden = ['password',  'remember_token'];

    public function contact()
    {
        return $this->hasMany(Contact::class, 'customer_id', 'id');
    }

    public function primaryContact()
    {
        return $this->hasOne(Contact::class, 'customer_id', 'id')
            ->where('contact_type', 'primary')
            ->where('is_deleted', 0)
            ->orderBy('id');
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

    public function getFullDisplayNameAttribute()
    {
        $contactName = $this->primaryContact
            ? trim($this->primaryContact->first_name . ' ' . $this->primaryContact->last_name)
            : '';

        if ($this->customer_type == 'company') {
            $companyName = $this->company_name ?: $this->customer_name;

            if (!$companyName) {
                return $contactName ?: ($this->email ?: 'Customer #' . $this->id);
            }

            return $contactName ? $companyName . ' (' . $contactName . ')' : $companyName;
        }

        return $this->customer_name
            ?: ($this->company_name ?: ($contactName ?: ($this->email ?: 'Customer #' . $this->id)));
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
