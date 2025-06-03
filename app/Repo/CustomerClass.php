<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Notifications\Backend\CustomerWelcomeNotification;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\Backend\SetPasswordNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\CustomerInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\ReportService\ReportService;

class CustomerClass implements CustomerInterface {

    protected  $customer_id=0;


    public function saveCustomer($request)
    {
        $customer=new Customer();
        $customer->customer_type = $request->customer_type;
        if($request->customer_type == 'company')
        {
            $customer->company_name = $request->company_name;
            $customer->license_no = $request->license_no;
            $customer->vat = $request->vat;
        }
        if($request->customer_type == 'individual')
        {
            $customer->customer_id_card = $request->customer_id_card;
            $customer->passport_no = $request->passport_no;
            $customer->dob = $request->dob;
        }
        $customer->customer_name = $request->f_name .' '. $request->l_name ;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->home = $request->home;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->status=$request->status;
        $customer->created_by =auth()->id();
        if($customer->save()){
            $contact =new Contact();
            $contact->customer_id = $customer->id;
            $contact->first_name = $request->f_name;
            $contact->last_name = $request->l_name;
            $contact->position = "owner";
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->contact_type = 'primary';
            $contact->status=$request->status;
            if($contact->save()){
                return response()->json(['success' => 'Record save successfully'], 200);
            }

        }
    }


    public function getAllCustomer()
    {
//        $qry=Customer::leftJoin('contacts', 'customers.id', '=', 'contacts.customer_id');
//        $qry=$qry->select('customers.*', 'contacts.first_name','contacts.last_name','contacts.email');
//        $qry=$qry->where('customers.is_deleted',0)->orderBy('id','DESC');
//        $qry=$qry->get();

        $qry = Customer::with('primaryContact');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
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
        $customer->customer_type = $request->customer_type;
        if($request->customer_type == 'company')
        {
            $customer->company_name = $request->company_name;
            $customer->license_no = $request->license_no;
            $customer->vat = $request->vat;
        }
        if($request->customer_type == 'individual')
        {
            $customer->customer_id_card = $request->customer_id_card;
            $customer->passport_no = $request->passport_no;
            $customer->dob = $request->dob;
        }
        $customer->customer_name = $request->f_name ." ". $request->l_name ;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->home = $request->home;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->status=$request->status;
        $customer->save();
        return 1;
    }

    public function getCustomer($id)
    {
        return $customer=Customer::find($id);
    }

    public function convertCustomer($request)
    {

        $validator = Validator::make($request->all(), [
            'email'=>'required|email|unique:contacts',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors() ], 200);

        $comp_id = 0;
        DB::transaction(function() use ($request)
        {
        $customer=new Customer();
        $customer->customer_type = $request->lead_type;
        $customer->customer_name = $request->first_name.' '.$request->last_name;
        $customer->company_name = $request->company_name;
        $customer->license_no = $request->license_no;
        $customer->vat = $request->vat;
        $customer->customer_id_card = $request->customer_id_card;
        $customer->passport_no = $request->passport_no;
        $customer->dob = $request->dob;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
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
                    Notification::route('mail', $contact_email->email)->notify(new CustomerWelcomeNotification($email));
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
                    Notification::route('mail', $contact_email->email)->notify(new SetPasswordNotification($passwordemail));
                }

//                $refreshtoken = $this->refreshToken();
//                $config = config('quickbooks');
//                $dataService = DataService::Configure([
//                    'auth_mode' => 'oauth2',
//                    'ClientID' => $config['client_id'],
//                    'ClientSecret' => $config['client_secret'],
//                    'RedirectURI' => $config['redirect_uri'],
//                    'accessTokenKey' => $refreshtoken['access_token'],
//                    'refreshTokenKey' => $refreshtoken['refresh_token'],
//                    'QBORealmID' => $config['realm_id'],
//                    'baseUrl' => $config['base_url'],
//                ]);
//                $displayname =  $contact->first_name.' '.$contact->last_name;
//                $query = "SELECT * FROM Customer WHERE DisplayName = '{$displayname}'";
//                $customer = $dataService->Query($query);
//                if (isset($customer) && !empty($customer) && count($customer) > 0){
//                    $customer = $customer[0];
//                    $customer->Id = $customer->Id;
//                    $customer->GivenName = $displayname;
//                    $customer -> DisplayName = $displayname;
//                    $customer -> CompanyName = $request->company_name;
//                    $customer -> BusinessNumber = '1111111';
//                    $customer -> Mobile = $contact->phone;
//                    $customer -> PrimaryEmailAddr->Address = $contact->email;//$customer-PrimaryEmailAddr-Address;
//                    $customer -> PrimaryPhone->FreeFormNumber = $contact->phone;
//                    try {
//                        $result = $dataService->Update($customer);
////                    echo 'Successfully update';
//                    }catch (ServiceException $ex) {
//                        echo "Updation Error message: " . $ex->getMessage();
//                    }
//
//                }else{
//                    $customerdata = \QuickBooksOnline\API\Facades\Customer::create([
//                        "GivenName" => $displayname,
//                        "DisplayName" => $displayname,
//                        "CompanyName" => $request->company_name,
//                        "PrimaryEmailAddr" => [
//                            "Address" => $contact->email
//                        ],
//                        "BillAddr" => [
//                            "Line1" => "123 Main Street",
//                            "City" => "Mountain View",
//                            "Country" => "USA",
//                        ],
//                        "PrimaryPhone" => [
//                            "FreeFormNumber" => $contact->phone
//                        ]
//                    ]);
//                    try {
//                        $result = $dataService->Add($customerdata);
////                    echo 'Successfully added';
//                    } catch (ServiceException $ex) {
//                        echo "Error message: " . $ex->getMessage();
//                    }
//                }

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

    public function syncCustomerQuickbook()
    {
        // TODO: Implement syncCustomerQuickbook() method.
    }
}
