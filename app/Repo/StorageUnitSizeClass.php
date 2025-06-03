<?php
namespace App\Repo;

use App\Models\Location;
use App\Models\StorageType;
use App\Models\StorageUnitSize;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\StorageUnitSizeInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class StorageUnitSizeClass implements StorageUnitSizeInterface {

    public function saveStorageSize($request)
    {
        $sy =new StorageUnitSize();
        $sy->unit_type_name=$request->u_name;
        $sy->measurement_unit=$request->m_unit;
        $sy->width=$request->width;
        $sy->length=$request->length;
        $sy->height=$request->height;
        $sy->no_of_units=$request->no_unit;
        $sy->status=$request->status;
        if($sy->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getStorageSize()
    {
        $qry=StorageUnitSize::with('mUnit');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteStorageSize($id)
    {
        $country=StorageUnitSize::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editStorageSize($id)
    {
        return $country=StorageUnitSize::find($id);
    }

    public function updateStorageSize($request)
    {
        $sy=StorageUnitSize::find($request->id);
        $sy->unit_type_name=$request->e_u_name;
        $sy->measurement_unit=$request->e_m_unit;
        $sy->width=$request->e_width;
        $sy->length=$request->e_length;
        $sy->height=$request->e_height;
        $sy->no_of_units=$request->e_no_unit;
        $sy->status=$request->e_status;
        $sy->save();
        return 1;
    }
}
