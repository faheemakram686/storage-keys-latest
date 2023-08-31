<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Repo\ContactClass;
use App\Repo\ContractClass;
use App\Repo\CustomerDashboardClass;
use App\Repo\EstimateClass;
use App\Repo\Interfaces\CustomerDashboardInterface;
use App\Repo\InvoiceClass;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerHomeController extends Controller
{
    private $estimate;
    private $contract;
    private $invoice;
    private $dashboard;
    public function __construct(CustomerDashboardInterface $dashboard)
    {
        $this->estimate = new EstimateClass();
        $this->contract = new ContractClass();
        $this->invoice = new InvoiceClass();
        $this->dashboard = $dashboard;
    }

    public function index()
    {
        $customer_id = Auth::user()->customer_id;
        $data['estimateCount'] = $this->dashboard->getEstimatesCount($customer_id);
        $data['contractCount'] = $this->dashboard->getContractsCount($customer_id);
        $data['invoiceCount'] = $this->dashboard->getInvoicesCount($customer_id);
        $data['estimate'] = $this->estimate->getCustomerEstimates($customer_id);
        $data['contract'] = $this->contract->getCustomerContracts($customer_id);
        $data['invoice'] = $this->invoice->getCustomerInvoices($customer_id);

        return view('ui.pages.customer.account')->with(compact('data'));
    }

    public function updateProfile(Request $request)
    {
        if($request->current_password){
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', new MatchOldPassword()],
                'password' => ['required'],
                'password_confirmation' => ['same:password'],
            ]);
            if ($validator->fails())
              return redirect()->back()->withErrors($validator->errors());
        }

        $contact=Contact::find(Auth::user()->id);
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->email = $request->email;

        if($request->password)
            $contact->password =  Hash::make($request->password);

        if($contact->save()){
            Auth::guard()->login($contact);
            return redirect()->back()->withSuccess(['Record Updated successfully']);
        }else
        {
            return redirect()->back()->withErrors(['Someting Wrong']);
        }




    }

    public function resetPassword(Request $request)
    {
//        return $request->all();
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'current_password' => ['required', new MatchOldPassword()],
                    'password' => ['required'],
                    'password_confirmation' => ['same:password'],
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $contact=Contact::find(Auth::user()->id);
            $contact->password =  Hash::make($request->password);
            if($contact->save()){
                return response()->json([
                    'user' => $contact,
                    'status' => true,
                    'message' => 'Password rest Successfully',
                ], 200);
            }else
            {
                return response()->json([
                    'user' => $contact,
                    'status' => true,
                    'message' => 'Password not rest Successfully',
                ], 200);
            }

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }


    public function updateProfileApi(Request $request)
    {
//        return $request->all();
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'id' => ['required'],
                    'first_name' => ['required'],
                    'last_name' => ['required'],
                    'email' => ['required'],
                    'phone' => ['required'],
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $contact=Contact::find(Auth::user()->id);
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            if($contact->save()){
                return response()->json([
                    'user' => $contact,
                    'status' => true,
                    'message' => 'Profile updated Successfully',
                ], 200);
            }else
            {
                return response()->json([
                    'user' => $contact,
                    'status' => true,
                    'message' => 'Profile not updated Successfully',
                ], 200);
            }

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

}
