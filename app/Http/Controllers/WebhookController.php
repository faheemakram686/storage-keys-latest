<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log or store the incoming data
        Log::info('Webhook received:', $request->all());

        // Example: access specific data
        // $customerName = $request->input('customer_name');

        // You can process or store it in the database here
        // Example:
        // Customer::create([
        //     'name' => $request->input('customer_name'),
        //     'email' => $request->input('email'),
        // ]);

        // Always respond with 200 OK to acknowledge receipt
        return response()->json(['status' => 'success', 'message' => 'Webhook received']);
    }
}
