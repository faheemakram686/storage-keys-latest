<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repo\AppSettingsClass;
use App\Repo\ContactClass;
use App\Repo\ContractTemplateClass;
use App\Repo\CustomerClass;
use App\Repo\EstimateClass;
use App\Repo\Interfaces\ContractInterface;
use App\Repo\UserClass;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;
use PDF;
use Dompdf\Options;
use ArPHP\I18N\Arabic;

class ContractController extends Controller
{


    private $customer;
    private $estimate;
    private $contract;
    private  $user;
    private  $contract_template;
    private $contact ;
    private $appsettings ;

    public function __construct(ContractInterface $contract )
    {
        $this->contract = $contract;
        $this->customer = new CustomerClass();
        $this->estimate = new EstimateClass();
        $this->user = new UserClass();
        $this->contract_template = new ContractTemplateClass();
        $this->contact =  new ContactClass();
        $this->appsettings = new AppSettingsClass();
    }


    public function index()
    {
        return view('backend.contract.index');
    }


    public function createContract()
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['estimates'] = $this->estimate->getAllEstimate();
        return view('backend.contract.create')->with(compact('data'));
    }

    public function saveContract(Request $request)
    {
        return $this->contract->saveContract($request);
    }
    public function getAllContracts()
    {
        return $this->contract->getAllContract();
    }
    public function deleteContract(Request $request)
    {
        $this->contract->deleteContract($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }

    public function editContract($id)
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['estimates'] = $this->estimate->getAllEstimate();
        $data['contract']= $this->contract->editContract($id);
        return view('backend.contract.edit')->with(compact('data'));
    }

    public function updateContract(Request $request)
    {

        $res = $this->contract->updateContract($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function updateContractId(Request $request)
    {
        $res = $this->contract->updateContractId($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function detailContract($id)
    {
        $data['contract'] = $this->contract->getContract($id);
        $data['contract_template'] = $this->contract_template->getAllTemplate();
        $data['appSettings'] = $this->appsettings->getAppSettings();
        return view('backend.contract.show')->with(compact('data'));
    }
    public function contractEstimate(Request $request)
    {
        $data['contract'] = $this->contract->getContract($request->contract_id);
        return $data;
    }

    public function getCustomerContracts(Request $request)
    {
        return $this->contract->getCustomerContracts($request->customer_id);
    }
    public function getCustomerContractsApi(Request $request)
    {
        return $this->contract->getCustomerContractsApi($request->customer_id);
    }

    public function contractToCustomer(Request $request)
    {
        $data['contract'] = $this->contract->getContract($request->id);
        $variables = $data['contract'][0]->customer;

        if( $data['contract'][0]->contractTemplate){
        $templateContent = $data['contract'][0]->contractTemplate->temp_body;
        foreach ($variables->toArray() as $key => $value ) {
                $templateContent = str_replace('{{'.$key.'}}', $value, $templateContent);
        }
            $contact = $this->contact->getPrimaryContect($variables->id);
            foreach ($contact->toArray() as $key => $value ) {
                $templateContent = str_replace('{{contact.'.$key.'}}', $value, $templateContent);
            }
            $estimate = $data['contract'][0];
            $addonprice =$data['contract'][0]->estimate->estimateAddon->sum('price');
            $storagetotal = $data['contract'][0]->estimate->unit_price * $data['contract'][0]->estimate->termLength->term_period;
            $totelCost = $storagetotal - ($storagetotal * $data['contract'][0]->estimate->termLength->discount_percentage/100);
            $storage = $data['contract'][0]->estimate->storageunit;
                $templateContent = str_replace('{{unit_no}}', $storage->storage_unit_name, $templateContent);
                $templateContent = str_replace('{{storage_fee}}',$totelCost , $templateContent);
                $templateContent = str_replace('{{addon_fee}}',$addonprice , $templateContent);
        $data['contract'][0]->contractTemplate->temp_body = $templateContent;
        }
        return view('backend.contract.contract-customer-view')->with(compact('data'));
    }

    public function contractPdf(Request $request)
    {
        $data['contract'] = $this->contract->getContract($request->id);
        $variables = $data['contract'][0]->customer;
        if( $data['contract'][0]->contractTemplate){
            $templateContent = $data['contract'][0]->contractTemplate->temp_body;
            foreach ($variables->toArray() as $key => $value ) {
                $templateContent = str_replace('{{'.$key.'}}', $value, $templateContent);
            }
            $contact = $this->contact->getPrimaryContect($variables->id);
            foreach ($contact->toArray() as $key => $value ) {
                $templateContent = str_replace('{{contact.'.$key.'}}', $value, $templateContent);
            }
            $estimate = $data['contract'][0];
            $addonprice =$data['contract'][0]->estimate->estimateAddon->sum('price');
            $storagetotal = $data['contract'][0]->estimate->unit_price * $data['contract'][0]->estimate->termLength->term_period;
            $totelCost = $storagetotal - ($storagetotal * $data['contract'][0]->estimate->termLength->discount_percentage/100);
            $storage = $data['contract'][0]->estimate->storageunit;
            $templateContent = str_replace('{{unit_no}}', $storage->storage_unit_name, $templateContent);
            $templateContent = str_replace('{{storage_fee}}',$totelCost , $templateContent);
            $templateContent = str_replace('{{addon_fee}}',$addonprice , $templateContent);
            $data['contract'][0]->contractTemplate->temp_body = $templateContent;
        }
//        return view('backend.contract.contract-customer-pdf', compact('data'));
        $reportHtml = view('backend.contract.contract-customer-pdf', compact('data'))->render();
        $arabic = new Arabic();
        $p = $arabic->arIdentify($reportHtml);

        for ($i = count($p)-1; $i >= 0; $i-=2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i-1], $p[$i] - $p[$i-1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }

        $pdf = PDF::loadHTML($reportHtml);
       return $pdf->stream('contract.pdf');

//
//        $pdf = PDF::setOptions(['isHtml5ParserEnabled'=>true,'isPhpEnabled'=>true,'isFontSubsettingEnabled'=>true,'chroot' => base_path()])->loadView('backend.contract.contract-customer-pdf', compact('data'));
//        return $pdf->stream('contract.pdf');
    }

    public function signContract(Request $request)
    {
        $res = $this->contract->signContact($request);
        return response()->json(['success' => 'Contract signed successfully'], 200);
    }

    public function showTasks($request)
    {
        $data['contract'] = $this->contract->getContract($request);
        $data['user'] = $this->user->getUser();
        return view('backend.contract.tasks')->with(compact('data'));
    }

    public function showAttachments($request)
    {
        $data['contract'] = $this->contract->getContract($request);
        $data['user'] = $this->user->getUser();
        return view('backend.contract.attachments')->with(compact('data'));
    }
    public function showTemplates($request)
    {
        $data['contract'] = $this->contract->getContract($request);
        $data['users'] = $this->user->getUser();
        return view('backend.contract.templates')->with(compact('data'));
    }

    public function approveContract(Request $request)
    {
        return $this->contract->approveContract($request->id);
    }
    public function declineContract(Request $request)
    {
        return $this->contract->declineContract($request);
    }
    public function showReminders($request)
    {
        $data['contract']=$this->contract->getContract($request);
        $data['users']=$this->user->getUser();
        return view('backend.contract.reminders')->with(compact('data'));
    }
    public function showNotes($request)
    {
        $data['contract']=$this->contract->getContract($request);
        $data['users']=$this->user->getUser();
        return view('backend.contract.notes')->with(compact('data'));
    }

}
