<?php
namespace App\Repo;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Repo\Interfaces\ContactInterface;

use App\Repo\Interfaces\ContractInterface;
use App\Repo\Interfaces\InvoiceInterface;
use App\Repo\Interfaces\PaymentInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Telescope\Console\PauseCommand;

class PaymentClass implements PaymentInterface {


    public function savePayment($request)
    {
        $payment =new Payment();
        $payment->customer_id =$request->customer_id;

        if($request->contract_id)
            $payment->contract_id =$request->contract_id;

        if($request->order_id)
            $payment->order_id =$request->order_id;

        $payment->invoice_id =$request->invoice_id;
        $payment->amount_received=$request->amount_received;
        $payment->payment_method=$request->payment_mode;
        $payment->payment_date=$request->payment_date;
        $payment->status=1;
        $payment->note=$request->note;
        if($payment->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getPayment()
    {
        // TODO: Implement getPayment() method.
    }
    public function getPaymentSum($id)
    {
          $qry = Payment::where('invoice_id', $id)->where('is_deleted',0)->sum('amount_received');
          return $qry;
    }

    public function deletePayment($id)
    {
        $country=Payment::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editPayment($id)
    {
      return  $res = Payment::find($id);
    }

    public function updatePayment($request)
    {
        $payment=Payment::find($request->id);
        $payment->invoice_id =$request->invoice_id;
        $payment->amount_received=$request->amount_received;
        $payment->payment_method=$request->payment_mode;
        $payment->payment_date=$request->payment_date;
        $payment->status=1;
        $payment->note=$request->note;
        $payment->save();
        return 1;
    }

    public function invoiceWisePayments($id)
    {
       $qry = Payment::with('invoice');
       $qry = $qry->where('invoice_id',$id);
       $qry = $qry->where('is_deleted',0);
       $qry = $qry->get();
       return  $qry;
    }

    public function getCustomerAllPayments($id)
    {
        try {
            $qry=Payment::query();
            $qry=$qry->where('customer_id',$id);
            $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
            $qry=$qry->get();

            return response()->json([
                'Payments' => $qry,
                'status' => true,
                'message' => 'Customer Payments',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }
}
