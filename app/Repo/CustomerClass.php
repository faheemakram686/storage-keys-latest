<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\CustomerInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CustomerClass implements CustomerInterface {

    protected  $customer_id=0;

    public function saveCustomer($request)
    {
        $customer=new Customer();
        $customer->company_name = $request->company_name;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->status=$request->status;
        if($customer->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }


    public function getAllCustomer()
    {
        $qry=Customer::leftJoin('contacts', 'customers.id', '=', 'contacts.customer_id');
        $qry=$qry->select('customers.*', 'contacts.first_name','contacts.last_name','contacts.email');
        $qry=$qry->where('customers.is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();

//            Customer::with('primaryContact');
//        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
//        $qry=$qry->get();
        return $qry;
    }

    public function deleteCustomer($id)
    {
        $customer=Customer::find($id);
        $customer->is_deleted=1;
        $customer->save();
        return 1;
    }

    public function editCustomer($id)
    {
        return $customer=Customer::find($id);
    }

    public function updateCustomer($request)
    {
        $customer=Customer::find($request->id);
        $customer->company_name = $request->edit_company_name;
        $customer->phone = $request->edit_phone;
        $customer->address = $request->edit_address;
        $customer->city = $request->edit_city;
        $customer->state = $request->edit_state;
        $customer->country = $request->edit_country;
        $customer->status=$request->edit_status;
        $customer->save();
        return 1;
    }

    public function getCustomer($id)
    {
        return $customer=Customer::find($id);
    }

    public function convertCustomer($request)
    {
        $comp_id = 0;
        DB::transaction(function() use ($request)
        {
        $customer=new Customer();
        $customer->company_name = $request->company_name;
        $customer->phone = $request->phone;
        $customer->status=$request->status;
        $customer->lead_id=$request->lead_id;
        if($customer->save()){
            $this->customer_id = $customer->id;
            $contact =new Contact();
            $contact->customer_id = $customer->id;
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->position = $request->position;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            if($request->password){
                $contact->password =  Hash::make($request->password);
            }
            $contact->contact_type = $request->contact_type;
            $contact->status=$request->status;
            if($contact->save()){
                $lead = Lead::find($request->lead_id);
                $lead->customer_id =  $customer->id;
                $lead->save();
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
                        'actionURL' => url('contact-setpassword').'/'.encrypt($contact_email->id),
                        'id' => $contact_email->id,
                    ];
                    Notification::route('mail', $contact_email->email)->notify(new EmailNotification($passwordemail));
                }

//                return response()->json(['success' => 'Record save successfully'], 200);
            }

        }

        });

        return response()->json(['success' => 'Record save successfully','customer_id' => $this->customer_id], 200);
    }

    public function isCustomer($id)
    {
        $qry=Lead::query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->where('id','=',$id);
        $qry=$qry->get();
        return $qry;

    }
    public function customerRegister($request)
    {
        DB::transaction(function() use ($request)
        {
            $customer=new Customer();
            $customer->company_name = $request->company_name;
            $customer->status=1;
            if($customer->save()){
                $contact =new Contact();
                $contact->customer_id = $customer->id;
                $contact->first_name = $request->first_name;
                $contact->last_name = $request->last_name;
                $contact->email = $request->email;
                $contact->password =  Hash::make($request->password);
                $contact->contact_type = 'primary';
                $contact->status=1;
                if($contact->save()){
                    return response()->json(['success' => 'Record save successfully','customer_id' => $this->customer_id], 200);
                }

            }

        });



    }
}
