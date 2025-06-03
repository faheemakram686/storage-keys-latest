<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/customer/dashboard';
    private $student;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:contact')->except('logout');
    }


    public function guard()
    {
        return Auth::guard('contact');
    }
    public function customerLoginForm()
    {
        return view('ui.pages.customer.login');
    }

    public function customerLogin(LoginRequest $request)
    {
        $request->authenticateCustomer();

        $request->session()->regenerate();

        return redirect()->intended('/customer/dashboard');
    }
}
