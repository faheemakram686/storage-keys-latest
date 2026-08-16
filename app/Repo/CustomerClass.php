<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Notifications\Backend\CustomerWelcomeNotification;
use App\Notifications\Backend\SetPasswordNotification;
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
        if (!$customer) {
            return response()->json(['errors' => 'Customer not found.'], 404);
        }

        $email = trim((string) $request->email);
        $emailTaken = Contact::whereRaw('LOWER(email) = ?', [strtolower($email)])
            ->where('is_deleted', 0)
            ->where('customer_id', '!=', $customer->id)
            ->exists();

        if ($emailTaken) {
            return response()->json(['errors' => ['email' => ['This email is already used by another contact.']]], 200);
        }

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
        $customer->email = $email;
        $customer->mobile = $request->mobile;
        $customer->home = $request->home;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->status=$request->status;
        $customer->save();

        // Keep the login contact in sync — otherwise email changes only on customers
        // and a second primary contact often gets created for the new email.
        $primaryContact = Contact::where('customer_id', $customer->id)
            ->where('contact_type', 'primary')
            ->where('is_deleted', 0)
            ->orderBy('id')
            ->first();

        if ($primaryContact) {
            Contact::where('customer_id', $customer->id)
                ->where('contact_type', 'primary')
                ->where('is_deleted', 0)
                ->where('id', '!=', $primaryContact->id)
                ->update(['contact_type' => 'general']);

            // If another contact on this customer already has the new email, soft-delete it
            // so the primary can take ownership of that address.
            Contact::where('customer_id', $customer->id)
                ->where('id', '!=', $primaryContact->id)
                ->whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->where('is_deleted', 0)
                ->update(['is_deleted' => 1]);

            $primaryContact->first_name = $request->f_name;
            $primaryContact->last_name = $request->l_name;
            $primaryContact->email = $email;
            $primaryContact->phone = $request->phone;
            $primaryContact->status = $request->status;
            $primaryContact->save();
        }

        return 1;
    }

    public function getCustomer($id)
    {
        return $customer=Customer::find($id);
    }

    public function convertCustomer($request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $lead = Lead::find($request->lead_id);
        $existingCustomerId = $lead && $lead->customer_id ? (int) $lead->customer_id : 0;

        $emailQuery = Contact::whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->where('is_deleted', 0);
        if ($existingCustomerId > 0) {
            $emailQuery->where('customer_id', '!=', $existingCustomerId);
        }
        if ($emailQuery->exists()) {
            return response()->json(['errors' => ['email' => ['The email has already been taken.']]], 200);
        }

        $emailTemplate = new EmailTemplateClass();
        if ($request->dont_welcome != 1) {
            $welcomeTemplate = $emailTemplate->getCustomerEmailTemplate('Customer_welcome_email');
            if (empty($welcomeTemplate) || empty($welcomeTemplate[0]) || empty($welcomeTemplate[0]->temp_body)) {
                return response()->json([
                    'errors' => 'Welcome email template (Customer_welcome_email) not found. Please configure email templates and try again.',
                ], 422);
            }
        }
        if ($request->set_password == 1) {
            $passwordTemplate = $emailTemplate->getCustomerEmailTemplate('Customer_set_password_email');
            if (empty($passwordTemplate) || empty($passwordTemplate[0]) || empty($passwordTemplate[0]->temp_body)) {
                return response()->json([
                    'errors' => 'Set password email template (Customer_set_password_email) not found. Please configure email templates and try again.',
                ], 422);
            }
        }

        DB::transaction(function() use ($request, $existingCustomerId, $emailTemplate)
        {
        if ($existingCustomerId > 0) {
            $customer = Customer::find($existingCustomerId);
            if (!$customer) {
                $customer = new Customer();
            }
        } else {
            $customer = new Customer();
        }

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

            $contact = Contact::where('customer_id', $customer->id)
                ->where('contact_type', 'primary')
                ->where('is_deleted', 0)
                ->orderBy('id')
                ->first();

            if (!$contact) {
                $contact = new Contact();
                $contact->customer_id = $customer->id;
                $contact->contact_type = $request->contact_type ?: 'primary';
            } else {
                Contact::where('customer_id', $customer->id)
                    ->where('contact_type', 'primary')
                    ->where('is_deleted', 0)
                    ->where('id', '!=', $contact->id)
                    ->update(['contact_type' => 'general']);
            }

            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->position = $request->position;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            if($request->password){
                $contact->password =  Hash::make($request->password);
            }
            if (!$contact->exists) {
                $contact->contact_type = $request->contact_type ?: 'primary';
            }
            $contact->status=$request->status;
            if($contact->save()){

                $lead = Lead::find($request->lead_id);
                $lead->customer_id =  $customer->id;
                $lead->save();

                $altEmail = trim((string) ($lead->alt_contact_email ?? ''));
                $altName = trim((string) ($lead->alt_contact_name ?? ''));
                $altMobile = trim((string) ($lead->alt_contact_mobile ?? ''));
                if ($altEmail !== '' && strcasecmp($altEmail, (string) $request->email) !== 0) {
                    $emailExists = Contact::where('email', $altEmail)->exists();
                    if (!$emailExists) {
                        $nameParts = preg_split('/\s+/', $altName, 2);
                        $altContact = new Contact();
                        $altContact->customer_id = $customer->id;
                        $altContact->first_name = $nameParts[0] ?? $altName;
                        $altContact->last_name = $nameParts[1] ?? '';
                        $altContact->email = $altEmail;
                        $altContact->phone = $altMobile !== '' ? $altMobile : null;
                        $altContact->contact_type = 'general';
                        $altContact->status = $request->status;
                        $altContact->save();
                    }
                }

                if($request->dont_welcome != 1)
                {
                    $contact_email = Contact::find($contact->id);
                    $email = $emailTemplate->buildCustomerEmailPayload(
                        $contact_email,
                        'Customer_welcome_email',
                        'Visit Storage Keys',
                        url('/')
                    );
                    Notification::route('mail', $contact_email->email)->notify(new CustomerWelcomeNotification($email));
                }
                if($request->set_password == 1)
                {
                    $contact_email = Contact::find($contact->id);
                    $setPasswordUrl = \App\Helpers\ContactPasswordToken::url($contact_email->id);
                    $passwordemail = $emailTemplate->buildCustomerEmailPayload(
                        $contact_email,
                        'Customer_set_password_email',
                        'Set Password',
                        $setPasswordUrl,
                        ['set_password_url' => $setPasswordUrl]
                    );
                    Notification::route('mail', $contact_email->email)->notify(new SetPasswordNotification($passwordemail));
                }

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

    public function toggleInvoiceReminders($id, $enabled)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['errors' => 'Customer not found.'], 404);
        }

        $customer->invoice_reminders_enabled = (int) $enabled ? 1 : 0;
        $customer->save();

        return $customer->invoice_reminders_enabled;
    }
}
