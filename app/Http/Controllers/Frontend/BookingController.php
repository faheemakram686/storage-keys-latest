<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repo\AddonClass;
use App\Repo\CountryClass;
use App\Repo\StorageUnitClass;
use App\Repo\WarehouseClass;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    private $su;

    private  $addon;
    public function __construct()
    {
        $this->su = new StorageUnitClass();
        $this->addon = new AddonClass();
    }


    public function countrywise(Request $request)
    {
       $data['addon'] = $this->addon->getStorageUnitAddon();
       $data['su'] = $this->su->searchStorageUnit($request);
        return $data;
    }

    public function getSunitWarehouseWise(Request $request)
    {
        $warehouse_id=$request->warehouse_id;
        $res=$this->su->getSunitWarehouseWise($warehouse_id);
        return $res;

    }




}
