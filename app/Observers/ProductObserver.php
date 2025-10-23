<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ZapierService;

class ProductObserver
{
    protected $zapier;

    public function __construct(ZapierService $zapier)
    {
        $this->zapier = $zapier;
    }

    public function created(Product $product)
    {
        $this->zapier->send('product', [
            'id' => $product->id,
            'p_name' => $product->p_name,
            'detail' => $product->detail,
            'pur_price' => $product->pur_price,
            'sell_price' => $product->sell_price,
            'disc_type' => $product->disc_type,
            'disc_amount' => $product->disc_amount,
            'qty' => $product->qty,
            'image' => $product->image,
            'status' => $product->status,
        ]);
    }
}
