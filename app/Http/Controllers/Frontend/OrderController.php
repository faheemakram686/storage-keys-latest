<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Order;
use App\Repo\Interfaces\OrderInterface;
use App\Services\NgeniusPaymentService;
use App\Services\NgeniusTokenService;
use App\Services\ShopOrderSettlementService;
use App\Repo\CustomerClass;
use App\Repo\UserClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    private $order;
    private $paymentService;
    private $tokenService;
    private $settlementService;
    private $customer;
    private $user;
    public function __construct(
        OrderInterface $order,
        NgeniusPaymentService $paymentService,
        NgeniusTokenService $tokenService,
        ShopOrderSettlementService $settlementService
    ) {
        $this->order = $order;
        $this->paymentService = $paymentService;
        $this->tokenService = $tokenService;
        $this->settlementService = $settlementService;
        $this->customer = new CustomerClass();
        $this->user = new UserClass();
    }

    public function save(Request $request)
    {
        $order = null;

        try {
            $user = auth()->guard('contact')->user();
            if (!$user || !$user->customer_id) {
                return redirect()->route('customer.login');
            }

            $paymentMethod = (string) $request->input('payment_method');
            if (!in_array($paymentMethod, ['online', 'cod'], true)) {
                return back()->with('error', 'Please choose a payment method.');
            }

            $cartItems = \Cart::getContent();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty.');
            }

            $amount = (float) \Cart::getTotal();
            if ($amount <= 0) {
                return back()->with('error', 'Your cart total must be greater than zero.');
            }

            $request->merge([
                'customer_id' => $user->customer_id,
                'payment_method' => $paymentMethod,
                'total_amount' => $amount,
            ]);

            $order = $this->order->saveOrder($request);

            if ($paymentMethod === 'cod') {
                \Cart::clear();
                return redirect()->route('checkout')->with('success', 'Order placed successfully.');
            }

            $customerRef = Customer::with('primaryContact')->find($user->customer_id);
            if (!$customerRef) {
                $this->discardOrder($order);
                return back()->with('error', 'Customer details are missing for this order.');
            }

            $response = $this->paymentService->createOrder($amount, 'AED', null, $customerRef, [
                'include_saved_card' => true,
                'merchant_order_reference' => 'ORD' . $order->id,
                'cancel_url' => route('checkout', ['payment' => 'cancelled']),
            ]);

            $paymentUrl = $response['_links']['payment']['href'] ?? null;
            $reference = $response['reference'] ?? null;
            if (!$response || !$paymentUrl || !$reference) {
                $this->discardOrder($order);
                return back()->with('error', 'Payment initiation failed. Please try again.');
            }

            $order->gateway_reference = $reference;
            $order->save();
            \Cart::clear();

            return redirect()->away($paymentUrl);
        } catch (\Exception $e) {
            if ($order && !$order->gateway_reference) {
                $this->discardOrder($order);
            }
            Log::error('Checkout place order failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Could not place your order. Please try again.');
        }
    }
    public function index()
    {
        return view('backend.order.index');
    }
    public function getOrders()
    {
        return $this->order->getAllOrders();
    }
    public function deleteOrder(Request $request)
    {
        $this->order->deleteOrder($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }

    public function detailOrder($id)
    {
        $order = Order::query()->where('id', $id)->where('is_deleted', 0)->first();
        if ($order) {
            $this->settlementService->settleIfMissingInvoice($order);
        }

        $data['invoice'] = $this->order->getOrder($id);
        return view('backend.order.show')->with(compact('data'));
    }
    public function getOrder($id)
    {
         return $data['invoice'] = $this->order->getOrder($id);
        return view('backend.order.show')->with(compact('data'));

    }

    public function printOrder($id)
    {
        $data['invoice'] = $this->order->getOrder($id);
        return view('backend.order.order-print')->with(compact('data'));
    }

    public function getCustomerOrders(Request $request)
    {
        return $this->order->getCustomerOrder($request->customer_id);
    }
      public function getCustomerOrdersAPI(Request $request)
    {
        return $this->order->getCustomerOrderApi($request->customer_id);
    }
    public function getOrderProducts(Request $request)
    {
        return $this->order->getOrderProducts($request->order_id);
    }
    public function editOrder($id)
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['users'] = $this->user->getUser();
        $data['invoice'] = $this->order->editOrder($id);
        return view('backend.order.edit')->with(compact('data'));
    }
    public function updateOrder(Request $request)
    {
        $this->order->updateOrder($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
    public function getOrderItems(Request $request)
    {
        return $this->order->getOrderItems($request->id);
    }


    public function handleRedirect(Request $request)
    {
        $ref = (string) $request->query('ref', '');
        if ($ref === '') {
            return redirect()->route('checkout')->with('error', 'Invalid payment reference.');
        }

        $order = Order::query()
            ->where('gateway_reference', $ref)
            ->orWhere('notes', $ref)
            ->first();

        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Payment failed or cancelled.');
        }

        $gatewayOrder = $this->paymentService->getOrderStatus($ref);
        if (!$gatewayOrder || !$this->paymentService->isPaid($gatewayOrder)) {
            if ((int) $order->getRawOriginal('status') === 1) {
                \Cart::clear();
                return redirect()->route('checkout')->with('success', 'Payment successful! Your order has been placed.');
            }

            return redirect()->route('checkout')->with('error', 'Payment was not completed. Please add the items to your cart and try again.');
        }

        $result = $this->settlementService->settlePaidOrder($order, $gatewayOrder);
        if (empty($result['success'])) {
            Log::error('Shop checkout settlement failed on redirect', [
                'order_id' => $order->id,
                'reference' => $ref,
                'message' => $result['message'] ?? null,
            ]);
            return redirect()->route('checkout')->with('error', 'Payment was received but could not be recorded. Please contact support with reference ' . $ref . '.');
        }

        \Cart::clear();

        return redirect()->route('checkout')->with('success', 'Payment successful! Your order has been placed.');
    }

    private function discardOrder(Order $order): void
    {
        try {
            $order->orderItems()->delete();
            $order->delete();
        } catch (\Exception $e) {
            Log::error('Failed to discard unpaid checkout order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }


}
