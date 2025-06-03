<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();

        if ($lastInvoice) {
            $lastInvoiceNumber = (int)substr($lastInvoice->invoice_no, -4);
            $newInvoiceNumber = $lastInvoiceNumber + 1;
        } else {
            $newInvoiceNumber = 1;
        }

        return 'INV-' . str_pad($newInvoiceNumber, 4, '0', STR_PAD_LEFT);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function userResponsible()
    {
        return $this->belongsTo(\App\Models\Core\Auth\User::class, 'user_id', 'id');
    }
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function estimate()
    {
        return $this->belongsTo(Estimate::class, 'estimate_id', 'id');
    }
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }


    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];


    public function setPaymentStatusAttribute($value)
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
        $this->attributes['payment_status'] =$value;
    }

    public function getPaymentStatusAttribute($value)
    {
        if($value==1){
            $getVal='PAID';
        }
        if($value==2){
            $getVal='PARTIALLY PAID';
        }
        if($value==0){
            $getVal='UNPAID';
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
