<?php

namespace App\Http\Controllers\Cutomer_api;

use App\Mail\Contact\ContactPasswordResetMail;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Core\Auth\Profile;

use App\Models\Core\Auth\User;
use App\Models\Customer;
use App\Models\CustomerAccountDeletion;
use App\Models\Invoice;
use App\Services\Auth\CustomerOtpService;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $otpService;

    public function __construct(CustomerOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function dashboardApi(Request $request)
    {
        try {

            $customer = Customer::with('primaryContact')->find($request->id);
            $contract = Contract::query();
            $contract = $contract->where('customer_id',$request->id);
            $contract = $contract->where('is_deleted',0);
            $data['contract'] = $contract->latest('id')->first();

            $invoice = Invoice::query();
            $invoice = $invoice->where('customer_id',$request->id);
            $invoice = $invoice->where('is_deleted',0);
            $data['invoice'] = $invoice->latest('id')->first();


            return response()->json([
                'data' => $data,
                'status' => true,
                'message' => 'Customer data',
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

    public function getCustomer(Request $request)
    {
        try {

            $customer = Customer::with('primaryContact')->find($request->id);
            return response()->json([
                'data' => $customer,
                'status' => true,
                'message' => 'Customer data',
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }
    public function createUser(Request $request)
    {

        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'first_name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status_id' =>1,
            ]);

            return response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'Customer Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

     public function registerCustomer(Request $request)
    {
        try {
            // Honeypot: bots often fill hidden "website" fields.
            if (filled($request->input('website'))) {
                return response()->json([
                    'status' => true,
                    'message' => ' Your Account Registered Successfully',
                ], 200);
            }

            $validated = Validator::make($request->all(),
            [
                'first_name'    => 'required|string|max:100',
                'last_name'     => 'required|string|max:100',
                'company_name'  => 'nullable|string|max:150',
                'customer_type' => 'nullable|in:individual,company',
                'account_type'  => 'nullable|in:individual,company',
                'email'         => 'required|email|unique:contacts,email',
                'password'      => 'required|confirmed|min:8',
            ]);
              if($validated->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validated->errors()
                ], 401);
            }

            $data = $validated->validated();
            $customerType = $data['account_type']
                ?? $data['customer_type']
                ?? (!empty($data['company_name']) ? 'company' : 'individual');
            $firstName = trim($data['first_name']);
            $lastName = trim($data['last_name']);
            $companyName = $customerType === 'company' ? trim((string) ($data['company_name'] ?? '')) : null;

            // Reject obvious random bot names (e.g. mMh4wNZqyD) with no vowels / spaces.
            foreach ([$firstName, $lastName, (string) $companyName] as $label) {
                if ($label !== '' && $this->looksLikeBotName($label)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => ['first_name' => ['Please enter a valid name.']],
                    ], 401);
                }
            }

            // Prepare registration data
            $registrationData = [
                'customer_type' => $customerType,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'company_name' => $companyName,
                'email' => $data['email'],
                'password' => $data['password']
            ];

            // Create pending registration and generate OTP
            $result = $this->otpService->createPendingRegistration($registrationData);
            
            // Send OTP email
            $customerName = trim($firstName . ' ' . $lastName);
            $this->otpService->sendOtp($data['email'], $result['otp'], $customerName);
            
            // Mask email for response
            $maskedEmail = $this->otpService->maskEmail($data['email']);

            return response()->json([
                'status' => true,
                'verification_token' => $result['verification_token'],
                'email_masked' => $maskedEmail,
                'expires_in' => 60,
                'message' => 'OTP has been sent to your email address.'
            ], 200);
        
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }

    /**
     * Heuristic for gibberish auto-generated names (random alphanumeric strings).
     */
    private function looksLikeBotName(string $value): bool
    {
        $value = trim($value);
        if (strlen($value) < 6) {
            return false;
        }

        // No spaces and no vowels = likely random ID.
        if (!preg_match('/\s/u', $value) && !preg_match('/[aeiouAEIOU]/u', $value)) {
            return true;
        }

        // Mixed-case alphanumeric blob with digits and no space (e.g. mMh4wNZqyD).
        if (
            strlen($value) >= 8
            && !preg_match('/\s/u', $value)
            && preg_match('/^[A-Za-z0-9]+$/u', $value)
            && preg_match('/[A-Z]/u', $value)
            && preg_match('/[a-z]/u', $value)
            && preg_match('/[0-9]/u', $value)
        ) {
            return true;
        }

        return false;
    }


    /**
     * Mobile API: send a password reset email to a customer.
     * Reuses the web reset flow - the emailed link opens the existing
     * customer reset-password web page.
     */
    public function forgotPasswordCustomer(Request $request, PasswordResetService $passwordResetService)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors(),
                ], 401);
            }

            $contact = $passwordResetService->findContactByEmail($request->email);

            // Always return a generic success to avoid leaking which emails exist.
            if (!$contact) {
                return response()->json([
                    'status' => true,
                    'message' => 'If the email exists, a password reset link has been sent.',
                ], 200);
            }

            $token = $passwordResetService->createToken($contact->email);

            $resetUrl = URL::signedRoute('customer.password.reset', [
                'token' => $token,
                'email' => $contact->email,
            ]);

            Mail::to($contact)->send(new ContactPasswordResetMail($contact, $resetUrl));

            return response()->json([
                'status' => true,
                'message' => 'If the email exists, a password reset link has been sent.',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function loginCustomer(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::guard('contact')->attempt($request->only(['email', 'password']) + ['is_deleted' => 0])){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = Contact::with('customer')->where('email', $request->email)->first();

            return response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'Customer Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

    /**
     * Mobile API: reasons the app can show on the delete account screen.
     */
    public function deleteAccountReasons()
    {
        $reasons = collect(CustomerAccountDeletion::REASONS)
            ->map(fn ($label, $key) => ['key' => $key, 'label' => $label])
            ->values();

        return response()->json([
            'data' => $reasons,
            'status' => true,
            'message' => 'Account deletion reasons',
        ], 200);
    }

    /**
     * Mobile API: the logged-in customer deletes their own account.
     * The contact is flagged is_deleted (records such as contracts and
     * invoices are kept). Deleting the primary contact closes the whole
     * customer account. All API tokens of the affected contacts are revoked.
     */
    public function deleteAccount(Request $request)
    {
        try {
            $contact = $request->user();

            if (!$contact instanceof Contact || $contact->is_deleted) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'password' => ['required', 'string'],
                'reason'   => ['required', 'in:' . implode(',', array_keys(CustomerAccountDeletion::REASONS))],
                'comments' => ['required_if:reason,other', 'nullable', 'string', 'max:1000'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            if (!Hash::check($request->password, $contact->getAuthPassword())) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => ['password' => ['The password is incorrect.']],
                ], 422);
            }

            DB::transaction(function () use ($request, $contact) {
                CustomerAccountDeletion::create([
                    'customer_id' => $contact->customer_id,
                    'contact_id'  => $contact->id,
                    'email'       => $contact->email,
                    'reason'      => $request->reason,
                    'comments'    => $request->comments,
                    'ip_address'  => $request->ip(),
                    'user_agent'  => substr((string) $request->userAgent(), 0, 255),
                ]);

                $contactIds = [$contact->id];

                if ($contact->contact_type === 'primary') {
                    $customer = Customer::find($contact->customer_id);
                    if ($customer) {
                        $customer->is_deleted = 1;
                        $customer->status = 0;
                        $customer->save();
                    }

                    $contactIds = Contact::where('customer_id', $contact->customer_id)->pluck('id')->all();
                }

                Contact::whereIn('id', $contactIds)->update(['is_deleted' => 1, 'status' => 0]);

                DB::table('personal_access_tokens')
                    ->where('tokenable_type', Contact::class)
                    ->whereIn('tokenable_id', $contactIds)
                    ->delete();
            });

            return response()->json([
                'status' => true,
                'message' => 'Your account has been deleted successfully.',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify OTP and create customer account (Mobile API)
     */
    public function verifyCustomerOtp(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'verification_token' => 'required|string',
                'otp' => 'required|digits:4',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validated->errors()
                ], 401);
            }

            $data = $validated->validated();
            
            // Verify OTP
            $registrationData = $this->otpService->verifyOtp($data['verification_token'], $data['otp']);

            if (!$registrationData) {
                // Check if token still exists to provide better error message
                $pending = $this->otpService->getPendingRegistration($data['verification_token']);
                
                if (!$pending) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Registration session not found. Please register again.'
                    ], 404);
                }
                
                if ($pending->isExpired()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'OTP code has expired. Please request a new one.',
                        'error_code' => 'OTP_EXPIRED'
                    ], 401);
                }
                
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP code. Please check and try again.',
                    'error_code' => 'OTP_INVALID'
                ], 401);
            }

            // Create customer account
            $customer = null;
            $contact = null;
            
            DB::transaction(function () use ($registrationData, &$customer, &$contact) {
                $customer = Customer::create([
                    'customer_type' => $registrationData['customer_type'],
                    'customer_name' => trim($registrationData['first_name'] . ' ' . $registrationData['last_name']),
                    'company_name' => $registrationData['company_name'],
                    'status'       => 1, // Active - OTP verified
                ]);

                $contact = Contact::create([
                    'customer_id' => $customer->id,
                    'first_name'  => $registrationData['first_name'],
                    'last_name'   => $registrationData['last_name'],
                    'email'       => $registrationData['email'],
                    'password'    => Hash::make($registrationData['password']),
                    'status'      => 1, // Active - OTP verified
                    'contact_type' => 'primary',
                ]);
            });

            // Delete pending registration
            $this->otpService->deletePendingRegistration($data['verification_token']);

            // Load customer relationship for response
            $contact->load('customer');

            return response()->json([
                'user' => $contact,
                'status' => true,
                'message' => 'Account created successfully. You can now login.',
                'token' => $contact->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Resend OTP (Mobile API)
     */
    public function resendCustomerOtp(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'verification_token' => 'required|string',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validated->errors()
                ], 401);
            }

            $token = $validated->validated()['verification_token'];

            // Check rate limit
            if (!$this->otpService->canResendOtp($token)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please wait before requesting another OTP.',
                    'retry_after' => 60
                ], 429);
            }

            // Resend OTP
            $result = $this->otpService->resendOtp($token);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid verification token or unable to resend OTP.'
                ], 400);
            }

            // Send new OTP email
            $this->otpService->sendOtp($result['email'], $result['otp'], $result['customer_name']);

            return response()->json([
                'status' => true,
                'expires_in' => 60,
                'message' => 'New OTP has been sent to your email.'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
