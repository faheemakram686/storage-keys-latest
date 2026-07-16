<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $appends = ['hashid'];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];

    public function getHashidAttribute(): string
    {
        return hashid_encode((int) $this->id);
    }

    public function storageunit()
    {
        return $this->belongsTo(StorageUnit::class, 'su_id', 'id');
    }

    public function estimateStorageUnits()
    {
        return $this->hasMany(EstimateStorageUnit::class, 'estimate_id', 'id');
    }

    public function storageUnits()
    {
        return $this->belongsToMany(StorageUnit::class, 'estimate_storage_units', 'estimate_id', 'storage_unit_id')
            ->withPivot('unit_price')
            ->withTimestamps();
    }

    /**
     * Sum of discounted storage cost across all units for the estimate term.
     */
    public function totalStorageCost(): float
    {
        $term = $this->termLength;
        $period = $term ? (float) $term->term_period : 1;
        $discount = $term ? (float) $term->discount_percentage : 0;

        $units = $this->relationLoaded('estimateStorageUnits')
            ? $this->estimateStorageUnits
            : $this->estimateStorageUnits()->get();

        if ($units->isEmpty()) {
            $price = (float) ($this->unit_price ?? 0);
            $storageTotal = $price * $period;
            return $storageTotal - ($storageTotal * $discount / 100);
        }

        $total = 0.0;
        foreach ($units as $row) {
            $price = (float) ($row->unit_price ?? 0);
            $storageTotal = $price * $period;
            $total += $storageTotal - ($storageTotal * $discount / 100);
        }

        return $total;
    }

    public function insuranceFee(): float
    {
        return (float) ($this->insurance_amount ?? 0);
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class, 'insurance_id', 'id');
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
