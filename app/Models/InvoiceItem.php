<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'item_id',
        'category',
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
    ];
    public function productdetail()
    {
        return $this->belongsTo(Product::class, 'item_id', 'id');
    }
    public function termsdetail()
    {
        return $this->belongsTo(TermLength::class, 'item_id', 'id');
    }
    public function addOndetail()
    {
        return $this->belongsTo(Addon::class, 'item_id', 'id');
    }
}
