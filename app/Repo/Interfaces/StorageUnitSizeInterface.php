<?php
namespace App\Repo\Interfaces;

interface StorageUnitSizeInterface{
    public function saveStorageSize($request);

    public function getStorageSize();
    public function deleteStorageSize($id);
    public function editStorageSize($id);
    public function updateStorageSize($request);
}
