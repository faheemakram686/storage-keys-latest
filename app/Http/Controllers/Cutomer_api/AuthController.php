<?php

namespace App\Http\Controllers\Cutomer_api;

use App\Mail\Contact\ContactPasswordResetMail;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Core\Auth\Profile;

use App\Models\Core\Auth\User;
use App\Models\Customer;
use App\Models\Invoice;
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

            $validated = Validator::make($request->all(),
            [
                'first_name'    => 'required',
                'last_name'     => 'required',
                'company_name'  => 'required',
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


            DB::transaction(function () use ($request, $validated) {
                $customer = Customer::create([
                    'company_name' => $request->company_name,
                    'status'       => 1,
                ]);


                Contact::create([
                    'customer_id' => $customer->id,
                    'first_name'  => $request->first_name,
                    'last_name'   =>$request->last_name,
                    'email'       => $request->email,
                    'password'    => Hash::make($request->password),
                    'status'      => 1,
                ]);
            });

            return response()->json([
                            'status' => true,
                            'message' => ' Your Account Registered Successfully',], 200);
        
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

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

            if(!Auth::guard('contact')->attempt($request->only(['email', 'password']))){
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
}