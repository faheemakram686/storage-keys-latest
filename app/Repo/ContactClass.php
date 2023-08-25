<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Estimate;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\ContactInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class ContactClass implements ContactInterface {


    public function saveContact($request)
    {
        $contact =new Contact();
        $contact->customer_id = $request->customer_id;
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->position = $request->position;
        $contact->email = $request->email;
        $contact->phone = $request->phone;

        if($request->password)
            $contact->password =  Hash::make($request->password);

        $contact->contact_type = $request->contact_type;
        $contact->status=$request->status;
        if($contact->save()){
            if($request->dont_welcome != 1)
            {
            $contact_email = Contact::find($contact->id);
            $email = [
                'greeting' => 'Hi '.$contact_email->first_name.' '.$contact_email->last_name.',',
                'body' => "Welcome to Storage Keys",
                'thanks' => 'Thank you this is from storage Keys',
                'actionText' => 'Visit Storage Keys',
                'actionURL' => url('/'),
                'id' => $contact_email->id,
            ];
            Notification::route('mail', $contact_email->email)->notify(new EstimateNotification($email));
            }
            if($request->set_password == 1)
            {
                $contact_email = Contact::find($contact->id);
                $passwordemail = [
                    'greeting' => 'Hi '.$contact_email->first_name.' '.$contact_email->last_name.',',
                    'body' => "Welcome to Storage Keys Please Set Your Password",
                    'thanks' => 'Thank you this is from storage Keys',
                    'actionText' => 'Set Password',
                    'actionURL' => url('contact-setpassword').'/'.$contact_email->id,
                    'id' => $contact_email->id,
                ];
                Notification::route('mail', $contact_email->email)->notify(new EmailNotification($passwordemail));
            }
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllContact()
    {
        $qry=Contact::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteContact($id)
    {
        $country=Contact::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;

    }

    public function editContact($id)
    {
        return $country=Contact::find($id);
    }

    public function updateContact($request)
    {
        $contact=Contact::find($request->id);
        $contact->customer_id = $request->edit_customer_id;
        $contact->first_name = $request->edit_first_name;
        $contact->last_name = $request->edit_last_name;
        $contact->position = $request->edit_position;
        $contact->email = $request->edit_email;
        $contact->phone = $request->edit_phone;
        $contact->password =  Hash::make($request->edit_password);
        $contact->contact_type = $request->edit_contact_type;
        $contact->status=$request->edit_status;
        $contact->save();
        return 1;
    }

    public function getCustomerContacts($request)
    {
        $qry=Contact::query();
        $qry=$qry->where('customer_id',$request);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;

    }
    public function getCustomerPrimaryContect($request)
    {
        $qry=Contact::with('customer');
        $qry=$qry->where('customer_id',$request);
        $qry=$qry->where('contact_type','primary');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->first();
        return $qry;

    }
    public function getPrimaryContect($request)
    {
        $qry=Contact::query();
        $qry=$qry->where('customer_id',$request);
        $qry=$qry->where('contact_type','primary');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->first();
        return $qry;

    }
}
