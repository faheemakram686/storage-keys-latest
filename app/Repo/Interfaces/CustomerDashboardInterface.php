<?php
namespace App\Repo\Interfaces;

interface CustomerDashboardInterface{

    public function getEstimatesCount($id);
    public function getContractsCount($id);
    public function getInvoicesCount($id);


}
