<?php
namespace App\Repo\Interfaces;

interface RoleInterface{
    public function saveRole($request);

    public function getRole();
    public function deleteRole($id);
    public function editRole($id);
    public function updateRole($request);
}
