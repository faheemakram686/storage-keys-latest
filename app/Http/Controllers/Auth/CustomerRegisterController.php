<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Repo\Interfaces\CustomerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerRegisterController extends Controller
{

    private $customer;

    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    public function create()
    {
        return view('ui.pages.customer.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'=>'required',
            'company_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:contacts',
            'password' => 'required|confirmed|min:8',
        ]);
        if ($validator->fails())
            return $validator->errors();

        DB::transaction(function() use ($request)
        {
            $customer=new Customer();
            $customer->company_name = $request->company_name;
            $customer->status=1;
            if($customer->save()){
                $this->customer_id = $customer->id;
                $contact =new Contact();
                $contact->customer_id = $customer->id;
                $contact->first_name = $request->first_name;
                $contact->last_name = $request->last_name;
                $contact->email = $request->email;
                if($request->password){
                    $contact->password =  Hash::make($request->password);
                }
                $contact->status=1;
                if($contact->save()){
                    return redirect(route('customer.login'));
                }

            }

        });








    }



}
