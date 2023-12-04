<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Lead;
use App\Notifications\Backend\CustomerWelcomeNotification;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\Backend\SetPasswordNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\ContactInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\ReportService\ReportService;

class ContactClass implements ContactInterface {


    public function saveContact($request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email|unique:contacts',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 200);

        $mycontract='';
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
            $mycontract = $contact;
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

            $refreshtoken = $this->refreshToken();
            $config = config('quickbooks');
            $dataService = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => $config['client_id'],
                'ClientSecret' => $config['client_secret'],
                'RedirectURI' => $config['redirect_uri'],
                'accessTokenKey' => $refreshtoken['access_token'],
                'refreshTokenKey' => $refreshtoken['refresh_token'],
                'QBORealmID' => $config['realm_id'],
                'baseUrl' => $config['base_url'],
            ]);
            $displayname =  $contact->first_name.' '.$contact->last_name;
            $query = "SELECT * FROM Customer WHERE DisplayName = '{$displayname}'";
            $customer = $dataService->Query($query);

            if (isset($customer) && !empty($customer) && count($customer) > 0){
                $customer = $customer[0];
                $customer->Id = $customer->Id;
                $customer->GivenName = $displayname;
                $customer -> DisplayName = $displayname;
                $customer -> CompanyName = 'Mait';
                $customer -> BusinessNumber = '1111111';
                $customer -> Mobile = $contact->phone;
                $customer -> PrimaryEmailAddr->Address = $contact->email;//$customer-PrimaryEmailAddr-Address;
                $customer -> PrimaryPhone->FreeFormNumber = $contact->phone;
                try {

                    $result = $dataService->Update($customer);
//                    echo 'Successfully update';
                }catch (ServiceException $ex) {
                    echo "Updation Error message: " . $ex->getMessage();
                }

            }else{
                $mycustomer = \QuickBooksOnline\API\Facades\Customer::create([
                    "GivenName" => $displayname,
                    "DisplayName" => $displayname,
                    "CompanyName" => "Test",
                    "PrimaryEmailAddr" => [
                        "Address" => $contact->email
                    ],
                    "BillAddr" => [
                        "Line1" => "123 Main Street",
                        "City" => "Mountain View",
                        "Country" => "USA",
                    ],
                    "PrimaryPhone" => [
                        "FreeFormNumber" => $contact->phone
                    ]
                ]);
                try {
                    $result = $dataService->Add($mycustomer);

                    $customer=Customer::find($mycontract->customer_id);
                    $customer->q_customer_id = $result-> Id;
                    if($customer->save()){
                        $contact=Contact::find($mycontract->id);
                        $contact->q_customer_id = $result-> Id;
                        $contact->save();
                    }
                    return response()->json(['success' => "Data Inserted Successfully"], 200);
                } catch (ServiceException $ex) {
                    return response()->json(['errors' => $ex->getMessage()], 200);
                }
            }

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

    public function syncCustomerQuickbook()
    {
        $refreshtoken = $this->refreshToken();
        $config = config('quickbooks');
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'accessTokenKey' => $refreshtoken['access_token'],
            'refreshTokenKey' => $refreshtoken['refresh_token'],
            'QBORealmID' => $config['realm_id'],
            'baseUrl' => $config['base_url'],
        ]);
        $query = "SELECT * FROM Customer";
        $q_customers = $dataService->Query($query);
        if(isset($q_customers))
        {
            DB::transaction(function() use ($q_customers)
            {
                foreach ($q_customers as $q_customer)
                {
                    $arr = explode(' ',trim($q_customer -> DisplayName));
                    if(!Customer::where('q_customer_id',$q_customer->Id)->first())
                    {

                        $customer=new Customer();
                        $customer->q_customer_id = $q_customer->Id;
                        $customer->company_name = $q_customer->CompanyName;
//                if(isset($q_customer->BillAddr->Line1))
//                    $customer->state = $q_customer['BillAddr']['Line1'];
//                $customer->city = $q_customer->BillAddr->City;
//                $customer->country = $q_customer->BillAddr->Country;
//                $customer->phone = $q_customer -> Mobile;
                        $customer->status=1;
                        if($customer->save()){
                            $this->customer_id = $customer->id;
                            $contact =new Contact();
                            $contact->customer_id = $customer->id;
                            $contact->q_customer_id = $q_customer->Id;

                            $contact->first_name =  $arr[0];
                            $contact->last_name =  $arr[1];
                            $contact->email = $q_customer->PrimaryEmailAddr->Address;
                            $contact->contact_type = 'primary';
                            $contact->status=1;
                            if($contact->save()){

                            }
                        }
                    }
                }
            });
        }
        $qry=Contact::with('customer');
        $qry=$qry->where('contact_type','primary');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();

        foreach ($qry as $contact)
        {
            $displayname = $contact->first_name.' '.$contact->last_name;
            $query = "SELECT * FROM Customer WHERE DisplayName = '{$displayname}'";
            $customer = $dataService->Query($query);

            if (!isset($customer) && empty($customer)) {
                $mycustomer = \QuickBooksOnline\API\Facades\Customer::create([
                    "GivenName" => $contact->first_name.' '.$contact->last_name,
//                    "DisplayName" => $contact->first_name.' '.$contact->last_name,
                    "CompanyName" => $contact->customer->company_name,
                    "PrimaryEmailAddr" => [
                        "Address" => $contact->email
                    ],
                    "BillAddr" => [
                        "Line1" => $contact->customer->state,
                        "City" => $contact->customer->city,
                        "Country" => $contact->customer->country,
                    ],
                    "PrimaryPhone" => [
                        "FreeFormNumber" => $contact->phone
                    ]
                ]);
                $resultObj = $dataService->Add($mycustomer);
                if(isset($resultObj))
                {
                    $customer=Customer::find($contact->customer_id);
                    $customer->q_customer_id = $resultObj-> Id;
                    if($customer->save()){
                        $mycontact=Contact::find($contact->id);
                        $mycontact->q_customer_id = $resultObj-> Id;
                        $mycontact->save();
                    }
                }
            }


        }
        $error = $dataService->getLastError();
        if ($error) {
//            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
//            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
//            echo "The Response message is: " . $error->getResponseBody() . "\n";
            return response()->json(['errors' => $error->getResponseBody()], $error->getHttpStatusCode());
        }else {
            return response()->json(['success' => "Data Inserted Successfully"], 200);
        }
    }

    public function refreshToken(){
        $config = config('quickbooks');
        $oauth2LoginHelper = new OAuth2LoginHelper($config['client_id'],$config['client_secret']);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($config['refresh_token']);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        return [
            'access_token'=>$accessTokenValue,
            'refresh_token'=>$refreshTokenValue
        ];
    }



}
