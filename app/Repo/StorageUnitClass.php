<?php
namespace App\Repo;

use App\Models\Location;
use App\Models\StorageType;
use App\Models\StorageUnit;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\StorageUnitInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class StorageUnitClass implements StorageUnitInterface {

  public function saveStorageUnit($request)
  {


      $sy =new StorageUnit();
      $sy->storage_unit_name=$request->su_name;
      $sy->wh_id=$request->wh_id;
      $sy->stype_id=$request->st_id;
      $sy->slevel_id =$request->sl_id;
      $sy->ssize_id =$request->ss_id;
      $sy->width=$request->width;
      $sy->length=$request->length;
      $sy->height=$request->height;
      $sy->price=$request->price;
      $sy->location=$request->location;
      $sy->status=$request->status;
      if($sy->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }


    public function getStorageUnit()
    {
        // TODO: Implement getStorageUnit() method.
        $qry=StorageUnit::with('warehouse','warehouse.loc.city.country','storagetype','storagelevel','storagesize');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteStorageUnit($id)
    {
        // TODO: Implement deleteStorageUnit() method.
        $country=StorageUnit::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;

    }

    public function editStorageUnit($id)
    {
        // TODO: Implement editStorageUnit() method.
        return $country=StorageUnit::find($id);
    }

    public function updateStorageUnit($request)
    {
        // TODO: Implement updateStorageUnit() method.
        $sy=StorageUnit::find($request->id);
        $sy->storage_unit_name=$request->e_su_name;
        $sy->wh_id=$request->e_wh_id;
        $sy->stype_id=$request->e_st_id;
        $sy->slevel_id =$request->e_sl_id;
        $sy->ssize_id =$request->e_ss_id;
        $sy->width=$request->e_width;
        $sy->length=$request->e_length;
        $sy->height=$request->e_height;
        $sy->price=$request->e_price;
        $sy->location=$request->e_location;
        $sy->status=$request->e_status;
        $sy->save();
        return 1;
    }
    public function searchStorageUnit($request){

        $qry=StorageUnit::with('warehouse.loc','warehouse.loc.city.country','storagetype','storagelevel','storagesize');

        $qry=$qry->when($request->country_id, function ($query, $country_id) {
            return $query->whereRelation('warehouse.loc.city.country', 'id', $country_id);
        });
        $qry=$qry->when($request->city_id, function ($query, $city_id) {
            return $query->whereRelation('warehouse.loc.city', 'id', $city_id);
        });
        $qry=$qry->when($request->id, function ($query, $id) {
            return $query->whereRelation('warehouse.loc', 'loc_id', $id);
        });
        $qry=$qry->when($request->level, function ($query, $level) {
            return $query->whereRelationIn('storagelevel', 'id', $level);
        });
        $qry=$qry->when($request->sType, function ($query, $type) {
            return $query->whereRelationIn('storagetype', 'id', $type);
        });
        $qry=$qry->when($request->sSize, function ($query, $size) {
            return $query->whereRelation('storagesize', 'id', $size);
        });
        $qry=$qry->get();
        return $qry;

    }

    public  function  leadStorageUnit($id)
    {
        $qry = StorageUnit::with('warehouse.loc','warehouse.loc.city.country');
        $qry = $qry->where( 'id' , $id );
        $qry = $qry->get();
        return $qry;
    }


    public function getSunitWarehouseWise($request)
    {
//        $qry=StorageUnit::with('warehouse.loc','warehouse.loc.city.country','storagetype','storagelevel','storagesize');
//        $qry=$qry->when($request, function ($query, $warehouse_id) {
//            return $query->whereRelation('warehouse', 'id', $warehouse_id);
//        });
//        $qry=$qry->get();
//        return $qry;
        $qry=StorageUnit::query();
        $qry=$qry->where('wh_id',$request);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;

    }
}
