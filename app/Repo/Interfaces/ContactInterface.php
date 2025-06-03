<?php
namespace App\Repo\Interfaces;

interface ContactInterface{
    public function saveContact($request);
    public function getAllContact();
    public function deleteContact($id);
    public function editContact($id);
    public function updateContact($request);

    public function getCustomerContacts($id);
    public function getPrimaryContect($id);
    public function syncCustomerQuickbook();

}
