<?php
namespace App\Repo\Interfaces;

interface StorageUnitInterface{
    public function saveStorageUnit($request);

    public function getStorageUnit();
    public function deleteStorageUnit($id);
    public function editStorageUnit($id);
    public function updateStorageUnit($request);
    public function searchStorageUnit($request);
    public function leadStorageUnit($request);

    public function getSunitWarehouseWise($request);




}
