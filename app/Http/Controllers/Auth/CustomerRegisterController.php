<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\CustomerInterface;
use Illuminate\Http\Request;
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







    }



}
