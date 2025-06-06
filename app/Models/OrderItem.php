<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function productdetail()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
