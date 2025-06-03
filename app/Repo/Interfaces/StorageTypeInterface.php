<?php
namespace App\Repo\Interfaces;

interface StorageTypeInterface{
    public function saveStorageType($request);

    public function getStorageType();
    public function deleteStorageType($id);
    public function editStorageType($id);
    public function updateStorageType($request);
}
