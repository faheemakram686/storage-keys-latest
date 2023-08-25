<?php
namespace App\Repo;

use App\Models\Location;
use App\Models\Warehouse;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class WarehouseClass implements WarehouseInterface {
  public function saveWareHouse($request)
  {
      if(Warehouse::where('name',$request->name)->first()){
          return response()->json(['error' => 'Warehouse already exist'], 200);
      }
      $warehouse =new Warehouse();
      $warehouse->name=$request->name;
      $warehouse->loc_id=$request->loc_id;
      $warehouse->description=$request->description;
      $warehouse->status=$request->status;
      if($warehouse->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }

  public function getAllWh()
  {
      // TODO: Implement getAllCountry() method.
       $qry=Warehouse::with('loc','loc.city','loc.country');
       $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
       $qry=$qry->get();
       return $qry;
  }

  public function deleteWarehouse($id)
  {
      // TODO: Implement deleteCountry() method.
      $country=Warehouse::find($id);
      $country->is_deleted=1;
      $country->save();
      return 1;
  }

  public function editWh($id)
  {
      // TODO: Implement editCountry() method.
      return $country=Warehouse::find($id);


  }

public function updateWh($request)
{
    // TODO: Implement updateCountry() method.
    $warehouse=Warehouse::find($request->wh_id);
    $warehouse->name=$request->e_name;
    $warehouse->loc_id=$request->e_loc_id;
    $warehouse->description=$request->e_description;
    $warehouse->status=$request->e_status;
    $warehouse->save();
    return 1;
}

public function searchCountrywise($id)
{
     $qry=Warehouse::with('loc');

    $qry=$qry->when($id, function ($query, $id) {
        return $query->whereRelation('loc', 'country_id', $id);
    });

    $qry=$qry->get();
    return $qry;

}


    public function getLocBasewarehouse($request)
    {
        $qry=Warehouse::query();
        $qry=$qry->where('loc_id',$request);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
}
