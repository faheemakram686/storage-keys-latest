<?php
namespace App\Repo;
use App\Models\Country;
use App\Models\Location;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LocationInterface;
use Illuminate\Http\Request;

class LocationClass implements LocationInterface {
  public function saveLocation($request)
  {
      // TODO: Implement saveCountry() method.


       $loc=new Location();
      $loc->loc_name=$request->loc_name;
      $loc->country_id=$request->country_id;
      $loc->city_id=$request->city_id;
      $loc->lat=$request->lat;
      $loc->lang=$request->lang;
      $loc->status=$request->status;
      $loc->is_default=$request->is_default;
      if($loc->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }

  public function getAllLocations()
  {
      // TODO: Implement getAllCountry() method.
       $qry=Location::with('city','country');
       $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
       $qry=$qry->get();
       return $qry;
  }

  public function deleteLoation($id)
  {
      // TODO: Implement deleteCountry() method.
      $country=Location::find($id);
      $country->is_deleted=1;
      $country->save();
      return 1;
  }

  public function editLocation($id)
  {
      // TODO: Implement editCountry() method.
      return $country=Location::find($id);

  }

public function updateLocation($request)
{
    // TODO: Implement updateCountry() method.
    $loc=Location::find($request->location_id);
    $loc->loc_name=$request->e_loc_name;
    $loc->country_id=$request->e_country_id;
    $loc->city_id=$request->e_city_id;
    $loc->lat=$request->e_lat;
    $loc->lang=$request->e_lang;
    $loc->status=$request->e_status;
    $loc->is_default=$request->edit_is_default;
    $loc->save();
    return 1;
}



    public function getCityBaseLocation($city_id)
    {
        $qry=Location::query();
        $qry=$qry->where('city_id',$city_id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }




}
