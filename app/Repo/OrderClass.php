<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repo\Interfaces\ContactInterface;

use App\Repo\Interfaces\ContractInterface;
use App\Repo\Interfaces\InvoiceInterface;
use App\Repo\Interfaces\OrderInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrderClass implements OrderInterface {


    public function saveOrder($request)
    {

        $order =new Order();
        $order->customer_id  = $request->customer_id;
        $order->notes = $request->orderRef;
        $order->payment_method = $request->payment_method;
        $order->sub_amount = $request->total_amount;
        $order->total_amount = $request->due_date;
        $order->status = 0;
        if($order->save()){
            $cartItems = \Cart::getContent();
                if ($cartItems)
                {
                   foreach ($cartItems as $item)
                    {
                             $orderItem = new OrderItem();
                             $orderItem->order_id  = $order->id;
                             $orderItem->product_id = $item->id;
                             $orderItem->qty = $item->quantity;
                             $orderItem->price = $item->price;
                             $orderItem->total = $item->price * $item->quantity;
                             $orderItem->status = 1;
                             $orderItem->save();
                    }
                    \Cart::clear();
                }
            }
            return response()->json(['success' => 'Record save successfully'], 200);
    }
    public function convertOrder($request)
    {

        $invoiceNumber = Invoice::generateInvoiceNumber();
        $invoice =new Invoice();
        $invoice->customer_id = $request['contract'][0]->customer_id;
        $invoice->estimate_id = $request['contract'][0]->estimate_id;
        $invoice->contract_id = $request['contract'][0]->id;
        $invoice->invoice_date = $request['contract'][0]->start_date;
        $invoice->invoice_no = $invoiceNumber;
        $invoice->due_date = $request['contract'][0]->end_date;
        $invoice->note = $request['contract'][0]->description;
        $invoice->status = 1;
        if($invoice->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllOrders()
    {
        $qry=Order::with('customer.primaryContact');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getCustomerOrder($customerid)
    {
        $qry=Order::with('customer.contact');
        $qry=$qry->where('customer_id',$customerid);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getCustomerOrders($customerid)
    {
        $qry=Order::with('customer.contact','orderItems');
        $qry=$qry->where('customer_id',$customerid);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getCustomerOrderApi($customerid)
    {
        try {
            $qry=Order::with('customer.contact');
            $qry=$qry->where('customer_id',$customerid);
            $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
            $qry=$qry->get();

            return response()->json([
                'Orders' => $qry,
                'status' => true,
                'message' => 'Customer Orders',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }

    }
    public function deleteOrder($id)
    {
        $order=Order::find($id);
        $order->is_deleted=1;
        $order->save();
        return 1;

    }

    public function editOrder($id)
    {
        $qry=Order::with('customer.contact','orderItems');
        $qry=$qry->where('id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getOrder($id)
    {
        $qry=Order::with('orderItems.productdetail','customer.contact');
        $qry=$qry->where('id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function updateOrder($request)
    {
        $order =Order::find($request->order_id);
        $order->customer_id = $request->customer_id;
        $order->notes = $request->note;
        $order->payment_method = $request->payment_method;
        $order->sub_amount = $request->sub_total;
        $order->total_amount = $request->grand_total;
        $order->status = $request->status;
        if($order->save()){
            if ($request->invoiceItems)
            {
                    $items = OrderItem::query();
                    $items = $items->where('order_id',$order->id);
                    $items->delete();

                for($i =0; $i < count($request->invoiceItems['id']);$i++)
                {
                    $orderItem = new OrderItem();
                    $orderItem->order_id  = $order->id;
                    $orderItem->product_id = $request->invoiceItems['id'][$i];
                    $orderItem->qty = $request->invoiceItems['qty'][$i];
                    $orderItem->price = (float)$request->invoiceItems['amount'][$i];
                    $orderItem->total = (float)$request->invoiceItems['total'][$i];
                    $orderItem->status = 1;
                    $orderItem->save();
                }
                return 1;

            }
        }
    }

    public function getOrderItems($id)
    {
        $qry=OrderItem::query();
        $qry=$qry->where('order_id',$id);
        $qry=$qry->get();
        return $qry;
    }
    public function changeStatus($id,$status)
    {
        $order =Order::find($id);
        $order->status = $status;
        $order->save();
        return 1;
    }


    public function getOrderProducts($id)
    {
        $qry=OrderItem::with('productdetail');
        $qry=$qry->where('order_id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
}
