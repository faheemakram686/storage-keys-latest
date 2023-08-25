<?php
namespace App\Repo\Interfaces;

interface InsuranceInterface{
    public function saveInsurance($request);

    public function getInsurance();
    public function deleteInsurance($id);
    public function editInsurance($id);
    public function updateInsurance($request);
}
