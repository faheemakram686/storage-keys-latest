<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Repo\CustomerClass;
use App\Repo\Interfaces\ContactInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ContactController extends Controller
{

    private $contact;
    private $customer;

    public function __construct(ContactInterface $contact)
    {
        $this->contact = $contact;
        $this->customer = new CustomerClass();
    }

    public function saveContact(Request $request)
    {
        return $res = $this->contact->saveContact($request);
    }
    public function getContacts(Request $request)
    {
        return $res = $this->contact->getCustomerContacts($request->customer_id);
    }
    public function deleteContact(Request $request)
    {
        $this->contact->deleteContact($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editContact(Request $request)
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['contact']=$this->contact->editContact($request->id);
        return $data;
    }
    public function updateContact(Request $request)
    {
        $res=$this->contact->updateContact($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function setPassword($id)
    {
        return view('auth.contact-setpassword')->with(compact('id'));
    }
    public function savePassword(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
        Contact::whereId($request->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect(route('customer.login'));
    }

    public function syncCustomerQuickbook()
    {
        return $this->contact->syncCustomerQuickbook();
    }



}
