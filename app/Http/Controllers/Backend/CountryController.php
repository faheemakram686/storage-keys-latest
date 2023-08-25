<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Repo\Interfaces\CountryInterface;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    private $country;

    public function __construct(CountryInterface $country)
    {
        $this->country = $country;
    }

    public function index()
    {
        return view('backend.country.index');
    }

    public function saveCountry(CountryRequest $request)
    {
        $validated = $request->validated();
        return $this->country->saveCountry($request);

    }


    public function getCountries(Request $request)
    {

        return $this->country->getAllCountry();

    }

    public function deleteCountry(Request $request)
    {
         $this->country->deleteCountry($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editCountry(Request $request)
    {
        $res=$this->country->editCountry($request->id);
        return $res;
    }

    public function updateCountry(Request $request)
    {
        $res=$this->country->updateCountry($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }





}
