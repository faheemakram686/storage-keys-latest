<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\LeadSourceInterface;
use Illuminate\Http\Request;

class LeadSourceController extends Controller
{

    private $lead_source;

    public function __construct(LeadSourceInterface $lead_source)
    {
        $this->lead_source = $lead_source;
    }
    public function index()
    {
        return view('backend.settings.leadsettings.leadsource.index');
    }


    public function saveLeadSource(Request $request)
    {
        return $this->lead_source->saveLeadSource($request);
    }


    public function getLeadSource(Request $request)
    {
        return $this->lead_source->getAllLeadSource();

    }

    public function deleteLeadSource(Request $request)
    {
        $this->lead_source->deleteLeadSource($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editLeadSource(Request $request)
    {
        $res=$this->lead_source->editLeadSource($request->id);
        return $res;
    }

    public function updateLeadSource(Request $request)
    {
        $res=$this->lead_source->updateLeadSource($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }



}
