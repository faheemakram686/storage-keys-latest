<?php

namespace App\Observers;

use App\Models\Customer;
use App\Services\ZapierService;

class CustomerObserver
{
    protected $zapier;

    public function __construct(ZapierService $zapier)
    {
        $this->zapier = $zapier;
    }

    public function created(Customer $customer)
    {
        $this->zapier->send('customer', [
            'id' => $customer->id,
            'customer_name' => $customer->customer_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'status' => $customer->status,
            'created_at' => $customer->created_at,
        ]);
    }
}
