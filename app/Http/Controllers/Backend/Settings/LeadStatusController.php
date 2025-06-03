<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\LeadStatusInterface;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{

    private $lead_status;

    public function __construct(LeadStatusInterface $lead_status)
    {
        $this->lead_status = $lead_status;
    }


    public function saveLeadStatus(Request $request)
    {
        return $this->lead_status->saveLeadStatus($request);
    }


    public function getLeadStatus(Request $request)
    {
        return $this->lead_status->getAllLeadStatus();

    }

    public function deleteLeadStatus(Request $request)
    {
        $this->lead_status->deleteLeadStatus($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editLeadStatus(Request $request)
    {
        $res=$this->lead_status->editLeadStatus($request->id);
        return $res;
    }

    public function updateLeadStatus(Request $request)
    {
        $res=$this->lead_status->updateLeadStatus($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }


    public function index()
    {
        return view('backend.settings.leadsettings.leadstatus.index');
    }

}
