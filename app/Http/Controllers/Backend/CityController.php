<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Repo\Interfaces\CityInterface;
use App\Repo\Interfaces\CountryInterface;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private $city;
    public function __construct(CityInterface $city)
    {
        $this->city = $city;
    }
    public function index(CountryInterface $country)
    {
       $data['country']=$country->getAllCountry();
        return view('backend.city.index')->with(compact('data'));
    }

    public function getAllCity()
    {
        return $res=$this->city->getAllCity();
    }

    public function saveCity(CityRequest $request)
    {
        $validated = $request->validated();
        return $this->city->saveCity($request);

    }

    public function deleteCity(Request $request)
    {
        $this->city->deleteCity($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editCity(CountryInterface $country ,Request $request)
    {
        $data['country']=$country->getAllCountry();
        $data['city']=$this->city->editCity($request->id);
        return $data;
    }

    public function updateCity(Request $request)
    {
        $res=$this->city->updateCity($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    //getCountryBaseCity
    public function getCountryBaseCity(Request $request)
    {
        $country_id=$request->country_id;
        $res=$this->city->getCountryBaseCity($country_id);
        return $res;
    }

}
