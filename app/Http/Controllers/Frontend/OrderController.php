<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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


            // Create payment with Ngenius
            $response = $this->paymentService->createOrder($request->total_amount);
//            dd($response);
            if (!$response) {
                return back()->withErrors(['error' => 'Payment initiation failed. Please try again.']);
            }
            if($response['_links']['payment']['href']){
                 $this->order->saveOrder($request);
                return redirect()->away($response['_links']['payment']['href']);
            }


        }catch (\Exception $e){
            return back()->withErrors( $e->getMessage());
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

//        dd($this->tokenService->getAccessToken());

        // Fetch payment status from N-Genius API
        $url = env('NGENIUS_BASE_URL') . "/transactions/outlets/" . env('NGENIUS_OUTLET_REFERENCE') . "/orders/" . $ref;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->tokenService->getAccessToken(), // generate token
            'Content-Type: application/vnd.ni-payment.v2+json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($responseData, true);
//        dd($responseData);

        // Get latest payment state
        $state = $response['_embedded']['payment'][0]['state'] ?? 'UNKNOWN';

        // Update your local DB
//        $payment = Payment::where('order_reference', $ref)->first();
//        if ($payment) {
//            $payment->status = $state; // STARTED, SUCCESS, FAILED
//            $payment->save();
//        }

        // Redirect user with status
        if ($state === 'SUCCESS') {
            return redirect()->route('checkout')->with('success', 'Payment successful!');
        } else {
            return redirect()->route('checkout')->with('error', 'Payment failed or cancelled.');
        }
    }


}
