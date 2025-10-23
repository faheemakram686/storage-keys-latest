<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;

class WebhookController extends Controller
{
   public function handleCustomerResponse(Request $request)
    {
        Log::info('Webhook received:', $request->all());

        $data = $request->all();

        // Find the customer based on the original ID
        $customer = Customer::where('id', $data['original_id'])->first();

        if ($customer) {
            // Update the customer attributes
            $customer->update([
                'q_customer_id' => $data['quickbooks_customer_id'] ?? null,
                'email' => $data['email'] ?? $customer->email,
                'customer_name' => $data['quickbooks_customer_name'] ?? null,
                'status' => 1 ?? $customer->status,
            ]);

            return response()->json(['message' => 'Customer updated successfully']);
        }

        return response()->json(['error' => 'Customer not found'], 404);
    }
    public function handleProductResponse(Request $request)
    {
        Log::info('Webhook received:', $request->all());

        $data = $request->all();

        // Find the customer based on the original ID
        $product = Product::where('id', $data['original_id'])->first();

        if ($product) {
            // Update the customer attributes
            $product->update([
                'q_product_id' => $data['quickbook_product_id'] ?? null,
                'status' => $data['status']=='success'? 1 : 0,
            ]);

            return response()->json(['message' => 'Product updated successfully']);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }
}
