<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\StorageUnitSizeInterface;
use App\Repo\MeasurementUnitClass;
use Illuminate\Http\Request;

class StorageUnitSizeController extends Controller
{
    private $stSize;
    public $mUnits;
    public function __construct(StorageUnitSizeInterface $stSize)
    {
        $this->mUnits = new MeasurementUnitClass();
        $this->stSize = $stSize;
    }

    public function index()
    {
        $data['wh']=$this->mUnits->getMeasurementUnit();
        return view('backend.storage-size.index')->with(compact('data'));
    }

    public function saveStorageSize(Request $request)
    {

        return $this->stSize->saveStorageSize($request);

    }

    public function getStorageSize()
    {
        return $res=$this->stSize->getStorageSize();
    }

    public function deleteStorageSize(Request $request)
    {
        $this->stSize->deleteStorageSize($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editStorageSize(Request $request)
    {
        $data['wh']=$this->mUnits->getMeasurementUnit();
        $data['st']=$this->stSize->editStorageSize($request->id);
        return $data;
    }

    public function updateStorageSize(Request $request)
    {
        $res=$this->stSize->updateStorageSize($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }


}
