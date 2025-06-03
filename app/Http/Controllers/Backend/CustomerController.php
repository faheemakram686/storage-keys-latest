<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\EstimateClass;
use App\Repo\Interfaces\CustomerInterface;
use App\Repo\UserClass;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    private $customer;

    private $user;
    private $estimate;

    public function __construct(CustomerInterface $customer)
    {
        $this->customer=$customer;
        $this->user= new UserClass();
        $this->estimate= new EstimateClass();

    }

    public function index()
    {
        return view('backend.customer.index');
    }

    public function create()
    {
        return view('backend.customer.create');
    }

    public function saveCustomer(Request $request)
    {
        return $res = $this->customer->saveCustomer($request);
    }
    public function convertCustomer(Request $request)
    {
        return $res = $this->customer->convertCustomer($request);
    }

    public function getAllCustomer()
    {
         $customer = $this->customer->getAllCustomer();
         return $customer;
    }
    public function deleteCustomer(Request $request)
    {
        $this->customer->deleteCustomer($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function isCustomer(Request $request)
    {
        $data['customer']=$this->customer->isCustomer($request->id);
        return $data;

    }
    public function editCustomer(Request $request)
    {
        $data['customer']=$this->customer->editCustomer($request->id);
        return view('backend.customer.edit')->with(compact('data'));

    }
    public function updateCustomer(Request $request)
    {

        $res=$this->customer->updateCustomer($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function showCustomer($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        return view('backend.customer.show')->with(compact('data'));
    }
    public function showContacts($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        $data['customer_list']=$this->customer->getAllCustomer();
        return view('backend.customer.contacts')->with(compact('data'));
    }
    public function showLeads($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        $data['customer_list']=$this->customer->getAllCustomer();
        return view('backend.customer.leads')->with(compact('data'));
    }
    public function showEstimates($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        $data['estimate'] = $this->estimate->getAllEstimate();
        return view('backend.customer.estimates')->with(compact('data'));
    }
    public function showReminders($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        $data['users']=$this->user->getUser();
        return view('backend.customer.reminders')->with(compact('data'));
    }

    public function showTasks($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        $data['user'] = $this->user->getUser();
        return view('backend.customer.tasks')->with(compact('data'));
    }
    public function showAttachments($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        return view('backend.customer.attachments')->with(compact('data'));
    }
    public function showContracts($request)
    {
        $data['customer']=$this->customer->getCustomer($request);
        $data['estimate'] = $this->estimate->getAllEstimate();
        return view('backend.customer.contracts')->with(compact('data'));
    }

}
