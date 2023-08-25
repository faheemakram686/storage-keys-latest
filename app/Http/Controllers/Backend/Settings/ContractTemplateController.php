<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\ContractTemplateInterface;
use Illuminate\Http\Request;

class ContractTemplateController extends Controller
{

    private $contract_temp;

    public function __construct(ContractTemplateInterface  $contract_temp)
    {
        $this->contract_temp = $contract_temp;
    }


    public function index()
    {
        return view('backend.settings.contract-templates.index');
    }



    public function saveContractTemplate(Request $request)
    {
        return $this->contract_temp->saveTemplate($request);
    }

    public function getContractTemplates()
    {
        return $data = $this->contract_temp->getAllTemplate();
    }
    public function getContractTemplate(Request $request)
    {
        return $data = $this->contract_temp->getTemplate($request->id);
    }

    public function editContractTemplate(Request $request)
    {

        $data= $this->contract_temp->editTemplate($request->id);
        return view('backend.settings.contract-templates.edit')->with(compact('data'));

    }
    public function updateContractTemplate(Request $request)
    {
        $res = $this->contract_temp->updateTemplate($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
    public function deleteContractTemplate(Request $request)
    {
        $this->contract_temp->deleteTemplate($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }




}
