<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\PaymentInterface;
use App\Repo\InvoiceClass;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $payment;
    private $invoice;

    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
        $this->invoice = new InvoiceClass();
    }

    public function savePayment(Request $request)
    {
        $invoice = $this->invoice->getInvoice($request->invoice_id);
        if($invoice[0]->type == 'contract'){
            $request->merge([
                "customer_id"=>$invoice[0]->customer_id,
                "contract_id"=>$invoice[0]->contract_id,
            ]);
        }
        if($invoice[0]->type == 'order'){
            $request->merge([
                "customer_id"=>$invoice[0]->customer_id,
                "order_id"=>$invoice[0]->order_id,
            ]);
        }

        $data = $this->payment->savePayment($request);
        $invoice = $this->invoice->getInvoice($request->invoice_id);
        if(($invoice[0]->grand_total - $request->amount_received) == 0)
        {
            $status = $this->invoice->changePaymentStatus($request->invoice_id,1);
        }
        return $data;
    }
    public function invoiceWisePayments(Request $request)
    {
        $data = $this->payment->invoiceWisePayments($request->invoice_id);
        return $data;
    }
    public function getCustomerPaymentsApi(Request $request)
    {
        $data = $this->payment->getCustomerAllPayments($request->customer_id);
        return $data;
    }
    public function editPayment(Request $request)
    {
        $data = $this->payment->editPayment($request->id);
        return $data;
    }
    public function updatePayment(Request $request)
    {
        $res = $this->payment->updatePayment($request);
        if($res){
            return response()->json(['success' => 'Record updated successfully'], 200);
        }

    }
    public function deletePayment(Request $request)
    {
        $res=$this->payment->deletePayment($request->id);
        if($res){
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }

    }





}
