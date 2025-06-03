<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\StorageUnitLevelInterface;
use Illuminate\Http\Request;

class StorageUnitLevelController extends Controller
{
    private $sulevel;

    public function __construct(StorageUnitLevelInterface $sulevel)
    {
        $this->sulevel = $sulevel;
    }


    public function index()
    {
        return view('backend.storage-unit-level.index');
    }

    public function saveStorageLevel(Request $request)
    {
        return $this->sulevel->saveStorageLevel($request);
    }

    public function getStorageLevel()
    {
        return $res=$this->sulevel->getStorageLevel();
    }
    public function deleteStorageLevel(Request $request)
    {
        $this->sulevel->deleteStorageLevel($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editStorageLevel(Request $request)
    {
        $data['st']=$this->sulevel->editStorageLevel($request->id);
        return $data;
    }
    public function updateStorageLevel(Request $request)
    {
//        return $request->all();
        $res=$this->sulevel->updateStorageLevel($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
}
