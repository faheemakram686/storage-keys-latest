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
        $order->notes = $request->notes;
        $order->payment_method = $request->payment_method;
        $order->sub_amount = $request->total_amount;
        $order->total_amount = $request->due_date;
        $order->status = 1;
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
        $qry=Order::with('customer.contact');
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
    public function getCustomerOrderApi($customerid)
    {
        try {
            $qry=Invoice::with('invoiceItems','payments','customer','estimate.storageunit','contract');
            $qry=$qry->where('customer_id',$customerid);
            $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
            $qry=$qry->get();

            return response()->json([
                'Invoices' => $qry,
                'status' => true,
                'message' => 'Customer Invoices',
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
        $country=Invoice::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;

    }

    public function editOrder($id)
    {
        $qry=Invoice::with('customer.primaryContact','contract','invoiceItems');
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
        $invoice =Invoice::find($request->invoice_id);
        $invoice->customer_id = $request->customer_id;
        $invoice->contract_id = $request->contract_id;
        $invoice->estimate_id = 1;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->user_id = $request->sale_agent;
        $invoice->sub_total = $request->sub_total;
        $invoice->vat = $request->vat;
        $invoice->grand_total = $request->grand_total;
        $invoice->due_date = $request->due_date;
        $invoice->note = $request->note;
        $invoice->payment_method = $request->note;
        $invoice->status = $request->status;
        if($invoice->save()){
            if ($request->invoiceItems)
            {
                    $items = InvoiceItem::query();
                    $items = $items->where('invoice_id',$invoice->id);
                    $items->delete();

                for($i =0; $i < count($request->invoiceItems['id']);$i++)
                {
                    $invoiceItem = new InvoiceItem();
                    $invoiceItem->invoice_id  = $invoice->id;
                    $invoiceItem->item_id = $request->invoiceItems['id'][$i];
                    $invoiceItem->category = $request->invoiceItems['cat'][$i];
                    $invoiceItem->item_name = $request->invoiceItems['name'][$i];
                    $invoiceItem->quantity = $request->invoiceItems['qty'][$i];
                    $invoiceItem->unit = $request->invoiceItems['unit'][$i];
                    $invoiceItem->unit_price = (float)$request->invoiceItems['amount'][$i];
                    $invoiceItem->total_price = (float)$request->invoiceItems['total'][$i];
                    $invoiceItem->save();
                }
                return 1;

            }
        }
//        return 1;
    }

    public function getOrderItems($id)
    {
        $qry=InvoiceItem::query();
        $qry=$qry->where('invoice_id',$id);
//        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function changeStatus($id,$status)
    {
        $invoice =Invoice::find($id);
        $invoice->status = 1;
        $invoice->save();
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
