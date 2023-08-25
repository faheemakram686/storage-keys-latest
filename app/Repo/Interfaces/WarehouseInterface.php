<?php
namespace App\Repo\Interfaces;

interface WarehouseInterface{
    public function saveWareHouse($request);

    public function getAllWh();
    public function deleteWarehouse($id);


    public function editWh($id);
    public function updateWh($request);

    public function searchCountrywise($id);

    public function getLocBasewarehouse($request);

}
