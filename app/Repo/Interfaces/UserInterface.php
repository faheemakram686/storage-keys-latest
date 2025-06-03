<?php
namespace App\Repo\Interfaces;

interface UserInterface{
    public function saveUser($request);

    public function getUser();
    public function deleteUser($id);
    public function editUser($id);
    public function updateUser($request);
    public function updateProfile($request);
    public function updatePassword($request);

}
