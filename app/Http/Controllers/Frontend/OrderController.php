<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Order;
use App\Repo\Interfaces\OrderInterface;
use App\Services\NgeniusPaymentService;
use App\Services\NgeniusTokenService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $order;
    private $paymentService;
    private $tokenService;
    public function __construct(OrderInterface $order, NgeniusPaymentService  $paymentService,NgeniusTokenService $tokenService)
    {
        $this->order = $order;
        $this->paymentService = $paymentService;
        $this->tokenService = $tokenService;
    }

    public function save(Request $request)
    {
        try {


            $paymentMethod = $request->payment_method;

            if ($paymentMethod === 'online') {
                $customerRef = Customer::find($request->customer_id);

                $response = $this->paymentService->createOrder($request->total_amount,'AED',null,$customerRef);
                if (!$response || empty($response['_links']['payment']['href'])) {
                    return back()->withErrors(['error' => 'Payment initiation failed. Please try again.']);
                }
                if($response['_links']['payment']['href']){
                    $request->orderRef = $response['reference'];
                    $this->order->saveOrder($request);
                    return redirect()->away($response['_links']['payment']['href']);
                }
            } elseif ($paymentMethod === 'cod') {
                $request->orderRef = $request->note ?? "";
                $this->order->saveOrder($request);
                return redirect()->back()->with('success', 'Order placed successfully.');
            }

        }catch (\Exception $e){
            return back()->withErrors($e->getMessage());
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

    public function detailOrder($id)
    {
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


    public function handleRedirect(Request $request)
    {
        $ref = $request->query('ref'); // The order reference
        if (!$ref) {
            return redirect()->route('checkout')->with('error', 'Invalid payment reference');
        }
        // Update your local DB
        $payment = Order::where('notes', $ref)->first();
        if ($payment) {
            $payment->status = 1; // STARTED, SUCCESS, FAILED
            $payment->save();
        }

        // Redirect user with status
        if ($payment) {
            return redirect()->route('checkout')->with('success', 'Payment successful!');
        } else {
            return redirect()->route('checkout')->with('error', 'Payment failed or cancelled.');
        }
    }


}
