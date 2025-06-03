<?php
namespace App\Repo;
use App\Models\City;
use App\Repo\Interfaces\CityInterface;
use Illuminate\Http\Request;

class CityClass implements CityInterface {

  public function getAllCity()
  {
      // TODO: Implement getAllCountry() method.
       $qry=City::with('country');
       $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
       $qry=$qry->get();
       return $qry;
  }

  public function saveCity($request)
  {
      // TODO: Implement saveCity() method.
      if(City::where('city_name',$request->city_name)->first()){
          return response()->json(['error' => 'city already exist'], 200);
      }
      $city=new City();
      $city->city_name=$request->city_name;
      $city->country_id=$request->country_id;
      $city->status=$request->status;
      $city->is_default=$request->is_default;
      if($city->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }

    public function deleteCity($id)
    {
        // TODO: Implement deleteCountry() method.
        $city=City::find($id);
        $city->is_deleted=1;
        $city->save();
        return 1;
    }
    public function editCity($id)
    {
        // TODO: Implement editCountry() method.
        return $country=City::find($id);

    }

    public function updateCity($request)
    {
        // TODO: Implement updateCountry() method.
        $country=City::find($request->city_id);
        $country->city_name=$request->edit_city_name;
        $country->country_id=$request->edit_country_id;
        $country->status=$request->edit_status;
        $country->is_default=$request->edit_is_defult;
        $country->save();
        return 1;
    }

    public function getCountryBaseCity($country_id)
    {
        // TODO: Implement getCountryBaseCity() method.

        $qry=City::query();
        $qry=$qry->where('country_id',$country_id);
        $qry=$qry->get();
        return $qry;
    }
}

