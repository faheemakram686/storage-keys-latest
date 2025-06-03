<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        if(Auth::getDefaultDriver() == 'contact')
        {
            $cartItems = \Cart::getContent();
            return view('ui.pages.checkout')->with(compact('cartItems'));
        }else
        {
            return route('customer.login');
        }

    }
}
