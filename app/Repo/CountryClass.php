<?php
namespace App\Repo;
use App\Models\Country;
use App\Repo\Interfaces\CountryInterface;
use Illuminate\Http\Request;

class CountryClass implements CountryInterface {

  public function saveCountry($request)
  {
      // TODO: Implement saveCountry() method.

      if(Country::where('name',$request->country_name)->first()){
          return response()->json(['error' => 'country already exist'], 200);
      }
       $country=new Country();
      $country->name=$request->country_name;
      $country->status=$request->status;
      $country->is_default=$request->edit_is_default;
      if($country->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }

  public function getAllCountry()
  {

       $qry=Country::Query();
       $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
       $qry=$qry->get();
       return $qry;
  }

  public function deleteCountry($id)
  {
      // TODO: Implement deleteCountry() method.
      $country=Country::find($id);
      $country->is_deleted=1;
      $country->save();
      return 1;
  }

  public function editCountry($id)
  {
      // TODO: Implement editCountry() method.
      return $country=Country::find($id);

  }

public function updateCountry($request)
{
    $country=Country::find($request->country_id);
    $country->name=$request->edit_country_name;
    $country->status=$request->edit_status;
    $country->is_default=$request->edit_is_default;
    $country->save();
    return 1;
}
}
