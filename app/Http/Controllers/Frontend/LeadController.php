<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repo\AddonClass;
use App\Repo\CountryClass;
use App\Repo\CustomerClass;
use App\Repo\Interfaces\LeadInterface;
use App\Repo\LeadSourceClass;
use App\Repo\LeadStatusClass;
use App\Repo\StorageUnitClass;
use App\Repo\TermLengthClass;
use App\Repo\UserClass;
use Illuminate\Http\Request;

class LeadController extends Controller
{

    private $lead;
    private $addon;
    private  $country;
    private $user;
    private $lead_status;
    private $lead_source;

    private $term_length;

    private $su;
    private $customer;
    public function __construct(LeadInterface $lead )
    {
        $this->lead = $lead;
        $this->addon = new AddonClass();
        $this->country = new CountryClass();
        $this->user = new UserClass();
        $this->su = new StorageUnitClass();
        $this->customer = new CustomerClass();
        $this->lead_status = new LeadStatusClass();
        $this->lead_source = new LeadSourceClass();
        $this->term_length = new TermLengthClass();

    }

    public function index()
    {
        $data['lead']=$this->lead->getAllLead();
        $data['user'] = $this->user->getUser();
        return view('backend.leads.index')->with(compact('data'));
    }
    public function create()
    {
        $data['loc'] =  $this->country->getAllCountry();
        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['customer'] = $this->customer->getAllCustomer();
        $data['user'] = $this->user->getUser();
        $data['status'] = $this->lead_status->getAllLeadStatus();
        $data['source'] = $this->lead_source->getAllLeadSource();
        $data['term_length'] = $this->term_length->getAllTermLength();
        return view('backend.leads.create')->with(compact('data'));
    }
    public function createLeadCustomer($id)
    {
        $data['loc'] =  $this->country->getAllCountry();
        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['customer'] = $this->customer->getAllCustomer();
        $data['user'] = $this->user->getUser();
        $data['status'] = $this->lead_status->getAllLeadStatus();
        $data['source'] = $this->lead_source->getAllLeadSource();
        $data['term_length'] = $this->term_length->getAllTermLength();
        return view('backend.leads.create')->with(compact('data','id'));
    }


    public function saveLead(Request $request)
    {
        $res = $this->lead->saveLead($request);
       return $data = $res->getData(true); // Convert to array

      //  dd($data); // Now you can safely access 'status'
    }
    public function saveLeadBackend(Request $request)
    {
        return $res = $this->lead->saveLeadBackend($request);
    }
    public function changeStatus(Request $request)
    {
        return $res = $this->lead->changeStatus($request);
    }
    public function changeSource(Request $request)
    {

        return $res = $this->lead->changeSource($request);
    }
    public function changeAssignee(Request $request)
    {
        return $res = $this->lead->changeAssignee($request);
    }
    public function getLeads()
    {
        return $res=$this->lead->getAllLead();

    }
    public function getCustomerLeads(Request $request)
    {
        return $res=$this->lead->getCustomerLeads($request->customer_id);

    }
    public function viewLead($request)
    {
        $data['lead']=$this->lead->getLead($request);
        $su_id = null;
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = !empty($item['addon']) ? explode(',', $item['addon']) : [];
            $su_id = $item['su_id'];
        }
        $data['su'] = $su_id ? $this->su->leadStorageUnit($su_id) : collect([]);
        $data['status'] = $this->lead_status->getAllLeadStatus();
        $data['source'] = $this->lead_source->getAllLeadSource();
        $data['user'] = $this->user->getUser();

        return view('backend.leads.show')->with(compact('data'));
    }

    public function showLead( Request $request)
    {

        $data['lead'] = $this->lead->getLead($request->id);
        $su_id = null;
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = !empty($item['addon']) ? explode(',', $item['addon']) : [];
            $su_id = $item['su_id'];
        }
        $data['su'] = $su_id ? $this->su->leadStorageUnit($su_id) : collect([]);
        return $data;
    }
    public function editLead($id)
    {
        $data['loc'] =  $this->country->getAllCountry();
        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['lead'] = $this->lead->getLead($id);
        $su_id = null;
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = !empty($item['addon']) ? explode(',', $item['addon']) : [];
            $su_id = $item['su_id'];
        }
        $data['su'] = $su_id ? $this->su->leadStorageUnit($su_id) : collect([]);
        $data['user'] = $this->user->getUser();
        $data['status'] = $this->lead_status->getAllLeadStatus();
        $data['source'] = $this->lead_source->getAllLeadSource();
        $data['term_length'] = $this->term_length->getAllTermLength();
        return view('backend.leads.editlead')->with(compact('data'));

    }

    public function updateLead(Request $request)
    {
        $res=$this->lead->updateLead($request);
        if (!$res) {
            return response()->json(['error' => 'Lead not found'], 404);
        }
        return response()->json(['success' => 'Record updated successfully'], 200);
    }



    public function deleteLead(Request $request)
    {
        $this->lead->deleteLead($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function showTasks($request)
    {
        $data['lead'] = $this->lead->getLead($request);
        $data['user'] = $this->user->getUser();
        return view('backend.leads.tasks')->with(compact('data'));
    }
    public function showAttachments($request)
    {
        $data['lead'] = $this->lead->getLead($request);
        $data['user'] = $this->user->getUser();
        return view('backend.leads.attachments')->with(compact('data'));
    }
    public function showReminders($request)
    {
        $data['lead'] = $this->lead->getLead($request);
        $data['users'] = $this->user->getUser();
        return view('backend.leads.reminders')->with(compact('data'));
    }




}
