<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\WarehouseInterface;
use App\Repo\WarehouseClass;
use Illuminate\Http\Request;

class StorageTypeController extends Controller
{
    private $stType;
    public $warehouse;
    public function __construct(StorageTypeInterface $stType)
    {
        $this->warehouse=new WarehouseClass();
        $this->stType = $stType;
    }
    public function index()
    {

         $data['wh']=$this->warehouse->getAllWh();

        return view('backend.storage-type.index')->with(compact('data'));
    }
    public function saveStorageType(Request $request)
    {
        return $this->stType->saveStorageType($request);

    }

    public function getStorageType()
    {
        return $res=$this->stType->getStorageType();
    }

    public function deleteStorageType(Request $request)
    {
        $this->stType->deleteStorageType($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editStorageType(Request $request)
    {
        $data['wh']=$this->warehouse->getAllWh();
        $data['st']=$this->stType->editStorageType($request->id);
        return $data;
    }

    public function updateStorageType(Request $request)
    {
        $res=$this->stType->updateStorageType($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
}
