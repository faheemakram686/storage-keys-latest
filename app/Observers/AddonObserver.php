<?php

namespace App\Observers;

use App\Models\Addon;
use App\Models\Product;
use App\Models\TermLength;
use App\Services\ZapierService;

class AddonObserver
{
    protected $zapier;

    public function __construct(ZapierService $zapier)
    {
        $this->zapier = $zapier;
    }

    public function created(Addon $addon)
    {
        $this->zapier->send('service', [
            'id' => $addon->id,
            'p_name' => $addon->name,
            'detail' => $addon->category,
            'type' =>'Addon',
            'status' => $addon->status,
        ]);
    }
}
