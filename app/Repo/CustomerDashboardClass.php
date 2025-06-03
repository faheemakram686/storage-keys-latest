<?php
namespace App\Repo;
use App\Models\Addon;
use App\Models\Contract;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Product;
use App\Models\StorageUnit;
use App\Repo\Interfaces\AdminDashboardInterface;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\CustomerDashboardInterface;
use Illuminate\Http\Request;
use Auth;

class CustomerDashboardClass implements CustomerDashboardInterface {

    public function getEstimatesCount($id)
    {
        $estimates = Estimate::query();
        $estimates = $estimates->where('customer_id',$id);
        $estimates = $estimates->where('is_deleted',0);
        $estimates = $estimates->count();
        return $estimates;

    }
    public function getContractsCount($id)
    {
        $contracts = Contract::query();
        $contracts = $contracts->where('customer_id',$id);
        $contracts = $contracts->where('is_deleted',0);
        $contracts = $contracts->count();
        return $contracts;
    }
    public function getInvoicesCount($id)
    {
        $invoices = Invoice::query();
        $invoices = $invoices->where('customer_id',$id);
        $invoices = $invoices->where('is_deleted',0);
        $invoices = $invoices->count();
        return $invoices;
    }

}
