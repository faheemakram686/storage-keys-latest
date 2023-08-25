<?php
namespace App\Repo\Interfaces;

interface CountryInterface{
    public function saveCountry($request);

    public function getAllCountry();
    public function deleteCountry($id);
    public function editCountry($id);
    public function updateCountry($request);


}
