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
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'stock' => $product->stock,
        ]);
    }
}
