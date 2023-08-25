<?php
namespace App\Repo;

use App\Models\Location;
use App\Models\StorageType;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class StorageTypeClass implements StorageTypeInterface {
  public function saveStorageType($request)
  {

      $sy =new StorageType();
      $sy->name=$request->name;
      $sy->description=$request->description;
      $sy->status=$request->status;
      if($sy->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }

  public function getStorageType()
  {
      // TODO: Implement getAllCountry() method.
       $qry=StorageType::query();
       $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
       $qry=$qry->get();
       return $qry;
  }

  public function deleteStorageType($id)
  {
      // TODO: Implement deleteCountry() method.
      $country=StorageType::find($id);
      $country->is_deleted=1;
      $country->save();
      return 1;
  }

  public function editStorageType($id)
  {
      // TODO: Implement editCountry() method.
      return $country=StorageType::find($id);

  }

public function updateStorageType($request)
{
    // TODO: Implement updateCountry() method.
    $sy=StorageType::find($request->id);
    $sy->name=$request->e_name;
    $sy->description=$request->e_description;
    $sy->status=$request->e_status;
    $sy->save();
    return 1;
}
}
