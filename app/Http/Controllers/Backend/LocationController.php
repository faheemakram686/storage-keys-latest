<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Repo\Interfaces\CityInterface;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LocationInterface;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private $loc;
    public function __construct(LocationInterface $loc)
    {
        $this->loc = $loc;
    }
    public function index(CountryInterface $country)
    {
        $data['country']=$country->getAllCountry();
        return view('backend.location.index')->with(compact('data'));
    }
    //saveLocation

    public function saveLocation(LocationRequest $request)
    {
        $validated = $request->validated();
        return $this->loc->saveLocation($request);

    }

    public function getAllLocations()
    {
        return $this->loc->getAllLocations();

    }
    public function deleteLocation(Request $request)
    {
        $this->loc->deleteLoation($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editLocation(CountryInterface $country ,CityInterface $city,Request $request)
    {
        $data['city']=$city->getAllCity();
        $data['country']=$country->getAllCountry();
        $data['loc']=$this->loc->editLocation($request->id);
        return $data;
    }

    public function updateLocation(Request $request)
    {
        $res=$this->loc->updateLocation($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }


            public function getCityBaseLocation(Request $request)
            {
                $city_id=$request->city_id;
                $res=$this->loc->getCityBaseLocation($city_id);
                return $res;
            }
}
