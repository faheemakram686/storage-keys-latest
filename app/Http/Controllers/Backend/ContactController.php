<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ContactPasswordToken;
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
        $contacts = $this->contact->getCustomerContacts($request->customer_id);

        return $contacts;
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
        $res = $this->contact->updateContact($request);

        if ($res instanceof \Illuminate\Http\JsonResponse) {
            return $res;
        }

        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function setPassword($token)
    {
        $contactId = ContactPasswordToken::decode($token);

        if (!$contactId || !Contact::where('id', $contactId)->where('is_deleted', 0)->exists()) {
            abort(404);
        }

        return view('auth.contact-setpassword', compact('token'));
    }

    public function savePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $contactId = ContactPasswordToken::decode($request->token);

        if (!$contactId) {
            return back()->withErrors(['password' => 'Invalid or expired password setup link.']);
        }

        $contact = $this->updateContactPassword($contactId, $request->password);

        if (!$contact) {
            return back()->withErrors(['password' => 'Contact not found.']);
        }

        return redirect()
            ->route('customer-profile', $contact->customer_id)
            ->with('status', 'Password set successfully.');
    }

    public function saveContactPassword(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|integer',
            'password' => 'required|confirmed|min:8',
        ]);

        $contact = $this->updateContactPassword($request->contact_id, $request->password);

        if (!$contact) {
            return response()->json(['success' => false, 'message' => 'Contact not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Password set successfully.']);
    }

    protected function updateContactPassword(int $contactId, string $password): ?Contact
    {
        $contact = Contact::where('id', $contactId)->where('is_deleted', 0)->first();

        if (!$contact) {
            return null;
        }

        $contact->password = Hash::make($password);
        $contact->status = 1;
        $contact->save();

        return $contact;
    }

    public function syncCustomerQuickbook()
    {
        return $this->contact->syncCustomerQuickbook();
    }



}
