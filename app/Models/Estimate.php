<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];

    public function storageunit()
    {
        return $this->belongsTo(StorageUnit::class, 'su_id', 'id');
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }
    public function estimateAddon()
    {
        return $this->hasMany(AddonPriceEstimate::class, 'estimate_id', 'id');
    }

    public function requireDocument()
    {
        return $this->belongsTo(RequireDocument::class, 'require_documents', 'id');
    }
    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template', 'id');
    }

    public function termLength()
    {
        return $this->belongsTo(TermLength::class, 'term_length', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function userResponsible()
    {
        return $this->belongsTo(\App\Models\Core\Auth\User::class, 'user_id', 'id');
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
