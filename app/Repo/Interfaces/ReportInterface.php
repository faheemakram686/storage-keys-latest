<?php
namespace App\Repo\Interfaces;

interface ReportInterface{

    public function getWarehouseReport($request);
    public function getLeadReport($request);
    public function getEstimateReport($request);
    public function getContractReport($request);
    public function getInvoiceReport($request);
    public function getPaymentReport($request);
    public function getMoveInRequestReport($request);
    public function getMoveInReport($request);
    public function getMoveOutReport($request);


}
