<?php

namespace App\Http\Controllers\Cutomer_api;

use App\Models\Contact;
use App\Models\Contract;
use App\Models\Core\Auth\Profile;

use App\Models\Core\Auth\User;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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