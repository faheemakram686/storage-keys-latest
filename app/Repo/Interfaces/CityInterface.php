<?php
namespace App\Repo\Interfaces;

interface CityInterface{

    public function getAllCity();
    public function saveCity($request);
    public function deleteCity($id);

    public function editCity($id);
    public function updateCity($request);
    public function getCountryBaseCity($country_id);

}
