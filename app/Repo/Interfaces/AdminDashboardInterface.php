<?php
namespace App\Repo\Interfaces;

interface AdminDashboardInterface{
    public function getLeadsCount();
    public function getCustomersCount();
    public function getEstimatesCount();
    public function getContractCount();
    public function getProductsCount();
    public function getAddonsCount();
    public function getStorageUnitsCount();

}
