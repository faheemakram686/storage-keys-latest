<?php
namespace App\Repo\Interfaces;

interface LocationInterface{
    public function saveLocation($request);

    public function getAllLocations();
    public function deleteLoation($id);
    public function editLocation($id);
    public function updateLocation($request);
    public function getCityBaseLocation($request);
}
