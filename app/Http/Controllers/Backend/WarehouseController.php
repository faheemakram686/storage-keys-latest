<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Repo\Interfaces\LocationInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{


    private $warehouse;

    public function __construct(WarehouseInterface $warehouse)
    {
        $this->warehouse=$warehouse;
    }

    public function index(LocationInterface $loc)
    {
        $data['loc']=$loc->getAllLocations();
        return view('backend.warehouse.index')->with(compact('data'));
    }
    public function saveWarehouse(Request $request)
    {
        $this->warehouse->saveWareHouse($request);
        return response()->json(['success' => 'Record save successfully'], 200);

    }
    public function getAllWareHouse()
    {
        return $res=$this->warehouse->getAllWh();

    }
    public function deleteWh(Request $request)
    {
        $this->warehouse->deleteWarehouse($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editWh(LocationInterface $loc ,Request $request)
    {
       $data['loc']=$loc->getAllLocations();
        $data['wh']=$this->warehouse->editWh($request->id);
        return $data;
    }

    public function updateWh(Request $request)
    {

        $res=$this->warehouse->updateWh($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
    public function getLocBaseWarehouse(Request $request)
    {
        $loc_id =$request->loc_id;
        $res = $this->warehouse->getLocBasewarehouse($loc_id);
        return $res;

    }


}
