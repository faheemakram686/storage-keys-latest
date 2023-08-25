<?php
namespace App\Repo;

use App\Models\Location;
use App\Models\StorageType;
use App\Models\StorageUnitLevel;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\StorageUnitLevelInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class StorageUnitLevelClass implements StorageUnitLevelInterface {

    public function saveStorageLevel($request)
    {
        // TODO: Implement saveStorageLevel() method.
        $sy =new StorageUnitLevel();
        $sy->name=$request->name;
        $sy->description=$request->description;
        $sy->status=$request->status;
        if($sy->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getStorageLevel()
    {
        // TODO: Implement getStorageLevel() method.
        $qry=StorageUnitLevel::query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteStorageLevel($id)
    {
        // TODO: Implement deleteStorageLevel() method.
        $country=StorageUnitLevel::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editStorageLevel($id)
    {
        // TODO: Implement editStorageLevel() method.
        return $country=StorageUnitLevel::find($id);
    }

    public function updateStorageLevel($request)
    {
        // TODO: Implement updateStorageLevel() method.
        $sy=StorageUnitLevel::find($request->id);
        $sy->name = $request->e_name;
        $sy->description=$request->e_description;
        $sy->status=$request->e_status;
        $sy->save();
        return 1;
    }
}
