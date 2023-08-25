<?php
namespace App\Repo\Interfaces;

interface ContractInterface{
    public function saveContract($request);
    public function approveContract($id);
    public function getAllContract();
    public function deleteContract($id);
    public function editContract($id);
    public function getContract($id);
    public function updateContract($request);
    public function declineContract($request);
    public function signContact($request);
    public function updateContractId($request);
    public function getCustomerContracts($id);


}
