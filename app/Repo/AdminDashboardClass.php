<?php
namespace App\Repo;
use App\Models\Addon;
use App\Models\Contract;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Lead;
use App\Models\Product;
use App\Models\StorageUnit;
use App\Repo\Interfaces\AdminDashboardInterface;
use App\Repo\Interfaces\CountryInterface;
use Illuminate\Http\Request;

class AdminDashboardClass implements AdminDashboardInterface {


    public function getLeadsCount()
    {
        $leads = Lead::query();
        $leads = $leads->where('is_deleted',0);
        $leads = $leads->count();
        return $leads;
    }

    public function getCustomersCount()
    {
        $customers = Customer::query();
        $customers = $customers->where('is_deleted',0);
        $customers = $customers->count();
        return $customers;

    }

    public function getEstimatesCount()
    {
        $estimates = Estimate::query();
        $estimates = $estimates->where('is_deleted',0);
        $estimates = $estimates->count();
        return $estimates;

    }

    public function getContractCount()
    {
        $contracts = Contract::query();
        $contracts = $contracts->where('is_deleted',0);
        $contracts = $contracts->count();
        return $contracts;
    }

    public function getProductsCount()
    {
        $products = Product::query();
        $products = $products->where('is_deleted',0);
        $products = $products->count();
        return $products;
    }

    public function getAddonsCount()
    {
        $addons = Addon::query();
        $addons = $addons->where('is_deleted',0);
        $addons = $addons->count();
        return $addons;

    }

    public function getStorageUnitsCount()
    {
        $storageunits = StorageUnit::query();
        $storageunits = $storageunits->where('is_deleted',0);
        $storageunits = $storageunits->count();
        return $storageunits;
    }

    public function getLeadStat()
    {
        $leads = Lead::with('leadStatus')->get();
//        $leads = $leads->where('is_deleted',0);
        return $leads;
    }
}
