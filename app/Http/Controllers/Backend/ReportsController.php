<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\ContractClass;
use App\Repo\CustomerClass;
use App\Repo\Interfaces\ReportInterface;
use App\Repo\LeadStatusClass;
use App\Repo\StorageTypeClass;
use App\Repo\StorageUnitLevelClass;
use App\Repo\StorageUnitSizeClass;
use App\Repo\UserClass;
use App\Repo\WarehouseClass;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    private $report;
    private $sUnit;

    public $warehouse;

    public $sType;
    public $sLevel;
    public $sSize;
    private $user;
    private $lead_status;
    private $customer;
    private $contract;

    public function __construct(ReportInterface $report)
    {
        $this->warehouse= new WarehouseClass();
        $this->sType = new StorageTypeClass();
        $this->sLevel = new StorageUnitLevelClass();
        $this->sSize = new StorageUnitSizeClass();
        $this->user = new UserClass();
        $this->customer = new CustomerClass();
        $this->lead_status = new LeadStatusClass();
        $this->contract =  new ContractClass();
        $this->report = $report;
    }

    public function viewWarehouseReport()
    {
        $data['wh']=$this->warehouse->getAllWh();
        $data['sl']=$this->sLevel->getStorageLevel();
        $data['ss']=$this->sSize->getStorageSize();
        $data['st']=$this->sType->getStorageType();
        return view('backend.reports.warehouse-report')->with(compact('data'));
    }

    public function getWarehouseReport(Request $request)
    {
        return $res = $this->report->getWarehouseReport($request);
    }
    public function viewLeadReport()
    {

        $data['user'] = $this->user->getUser();
        $data['status'] = $this->lead_status->getAllLeadStatus();
        return view('backend.reports.leads-report')->with(compact('data'));
    }

    public function getLeadReport(Request $request)
    {
        return $res = $this->report->getLeadReport($request);
    }

    public function viewEstimateReport()
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['user'] = $this->user->getUser();
        return view('backend.reports.estimate-report')->with(compact('data'));
    }

    public function getEstimateReport(Request $request)
    {
        return $res = $this->report->getEstimateReport($request);
    }

    public function viewContractReport()
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['user'] = $this->user->getUser();
        return view('backend.reports.contract-report')->with(compact('data'));
    }


  public function getContractReport(Request $request)
    {
        return $res = $this->report->getContractReport($request);
    }
    public function viewInvoiceReport()
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['user'] = $this->user->getUser();
        $data['contracts'] = $this->contract->getAllContract();
        return view('backend.reports.invoice-report')->with(compact('data'));
    }


  public function getInvoiceReport(Request $request)
    {
        return $res = $this->report->getInvoiceReport($request);
    }
    public function viewPaymentReport()
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['user'] = $this->user->getUser();
        $data['contracts'] = $this->contract->getAllContract();
        return view('backend.reports.payment-report')->with(compact('data'));
    }


  public function getPaymentReport(Request $request)
    {
        return $res = $this->report->getPaymentReport($request);
    }



}
