<?php

namespace App\Observers;

use App\Models\Customer;
use App\Services\ZapierService;

class CustomerObserver
{
    public function created(Customer $customer)
    {
        (new ZapierService())->sendCustomerCreated($customer);
    }
}

