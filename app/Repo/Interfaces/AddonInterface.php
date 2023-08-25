<?php
namespace App\Repo\Interfaces;

interface AddonInterface{

    public function getAllAddon();
    public function saveAddon($request);
    public function deleteAddon($id);

    public function editAddon($id);
    public function updateAddon($request);

}
