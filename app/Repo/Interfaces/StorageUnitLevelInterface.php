<?php
namespace App\Repo\Interfaces;

interface StorageUnitLevelInterface{
    public function saveStorageLevel($request);

    public function getStorageLevel();
    public function deleteStorageLevel($id);
    public function editStorageLevel($id);
    public function updateStorageLevel($request);
}
