<?php
namespace App\Repo\Interfaces;

interface CustomerInterface{
    public function saveCustomer($request);
    public function customerRegister($request);
    public function convertCustomer($request);
    public function getAllCustomer();
    public function deleteCustomer($id);
    public function editCustomer($id);
    public function updateCustomer($request);
    public function getCustomer($id);
    public function isCustomer($id);
    public function syncCustomerQuickbook();
}
