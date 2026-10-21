<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\NgeniusPaymentReconciliationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(
        protected NgeniusPaymentReconciliationService $reconciliation
    ) {
    }

    public function checkout(Request $request)
    {
        if (Auth::getDefaultDriver() == 'contact') {
            if ($request->query('payment') === 'cancelled') {
                session()->now('error', 'Payment was cancelled. Please add the items to your cart and try again.');
            }

            $user = auth()->guard('contact')->user();
            if ($user && $user->customer_id) {
                try {
                    $settled = $this->reconciliation->reconcileCustomerShopOrders((int) $user->customer_id);
                    if ($settled > 0) {
                        session()->now(
                            'success',
                            $settled === 1
                                ? 'Your recent online payment was confirmed and the invoice is ready.'
                                : 'Your recent online payments were confirmed and invoices are ready.'
                        );
                    }
                } catch (\Throwable $e) {
                    Log::warning('Checkout reconcile failed', [
                        'customer_id' => $user->customer_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $cartItems = \Cart::getContent();
            return view('ui.pages.checkout')->with(compact('cartItems'));
        }

        return route('customer.login');
    }
}
