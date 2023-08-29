<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cartItems = \Cart::getContent();
        return view('ui.pages.checkout')->with(compact('cartItems'));
    }
}
