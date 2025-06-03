<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\InsuranceInterface;
use Illuminate\Http\Request;

class InsurancesController extends Controller
{
    private $insurence;

    public function __construct(InsuranceInterface $insurence)
    {

        $this->insurence = $insurence;
    }


    public function index()
    {
        return view('backend.insurance.index');
    }
    public function saveInsurance(Request  $request)
    {
        return $this->insurence->saveInsurance($request);
    }

    public function getInsurance()
    {
        return $res=$this->insurence->getInsurance();
    }
    public function deleteInsurance(Request $request)
    {
        $this->insurence->deleteInsurance($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editInsurance(Request $request)
    {
        $data['st']=$this->insurence->editInsurance($request->id);
        return $data;
    }
    public function updateInsurance(Request $request)
    {
        $res=$this->insurence->updateInsurance($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

}
