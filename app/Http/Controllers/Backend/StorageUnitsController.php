<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageUnitInterface;
use App\Repo\StorageTypeClass;
use App\Repo\StorageUnitLevelClass;
use App\Repo\StorageUnitSizeClass;
use App\Repo\WarehouseClass;
use Illuminate\Http\Request;

class StorageUnitsController extends Controller
{
    private $sUnit;
    public $warehouse;
    public $sType;
    public $sLevel;
    public $sSize;
    public function __construct(StorageUnitInterface $sUnit)
    {
        $this->warehouse= new WarehouseClass();
        $this->sType = new StorageTypeClass();
        $this->sLevel = new StorageUnitLevelClass();
        $this->sSize = new StorageUnitSizeClass();
       $this->sUnit = $sUnit;
    }

    public function index()
    {

        $data['wh']=$this->warehouse->getAllWh();
        $data['sl']=$this->sLevel->getStorageLevel();
        $data['ss']=$this->sSize->getStorageSize();
        $data['st']=$this->sType->getStorageType();
        return view('backend.storage-unit.index')->with(compact('data'));
    }
    public function saveStorageUnit(Request $request)
    {

        return $this->sUnit->saveStorageUnit($request);

    }

    public function getStorageUnit()
    {
        return $res=$this->sUnit->getStorageUnit();
    }

    public function deleteStorageUnit(Request $request)
    {
        $this->sUnit->deleteStorageUnit($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editStorageUnit(Request $request)
    {
        $data['wh']=$this->warehouse->getAllWh();
        $data['st']=$this->sType->getStorageType();
        $data['sl']=$this->sLevel->getStorageLevel();
        $data['ss']=$this->sSize->getStorageSize();
        $data['su']=$this->sUnit->editStorageUnit($request->id);
        return $data;
    }

    public function updateStorageUnit(Request $request)
    {
        $res=$this->sUnit->updateStorageUnit($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }



}
