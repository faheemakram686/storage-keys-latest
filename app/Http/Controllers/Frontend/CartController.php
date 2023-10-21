<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cartList()
    {
        $cartItems = \Cart::getContent();
//         dd($cartItems);
        return view('ui.pages.cart', compact('cartItems'));
    }


    public function addToCart(Request $request)
    {

        \Cart::add([
            'id' =>$request->id,
            'name' =>$request->name,
            'price' =>$request->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'image' => $request->image,
            )
        ]);

        session()->flash('success', 'Product is Added to Cart Successfully !');

        return redirect()->back();
    }

    public function updateCart(Request $request)
    {
//        return $request->all();
        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        session()->flash('success', 'Item Cart is Updated Successfully !');

        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        session()->flash('success', 'Item Cart Remove Successfully !');

        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        \Cart::clear();

        session()->flash('success', 'All Item Cart Clear Successfully !');

        return redirect()->route('cart.list');
    }

    public function applyCoupon(Request $request) {
        $couponCode = $request->input('coupon_code');
        $coupon = Coupon::where('code', $couponCode)->first();

        if ($coupon) {
            // Calculate discount and update cart total
            $discount = $coupon->amount; // You might have different types of discounts
            \Cart::applyDiscount($discount);
            return redirect()->back()->with('success', 'Coupon applied successfully.');
        } else {
            return redirect()->back()->with('error', 'Invalid coupon code.');
        }
    }

}
