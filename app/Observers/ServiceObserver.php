<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\TermLength;
use App\Services\ZapierService;

class ServiceObserver
{
    protected $zapier;

    public function __construct(ZapierService $zapier)
    {
        $this->zapier = $zapier;
    }

    public function created(TermLength $termLength)
    {
        $this->zapier->send('service', [
            'id' => $termLength->id,
            'p_name' => $termLength->title,
            'detail' => $termLength->description,
            'status' => $termLength->status,
        ]);
    }
}
