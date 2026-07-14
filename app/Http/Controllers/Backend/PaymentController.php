<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\PaymentInterface;
use App\Repo\InvoiceClass;
use App\Services\ZapierService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $payment;
    private $invoice;
    private $zapier;

    public function __construct(PaymentInterface $payment,ZapierService $zapier)
    {
        $this->payment = $payment;
        $this->zapier = $zapier;
        $this->invoice = new InvoiceClass();
    }

    public function savePayment(Request $request)
    {
        $invoice = $this->invoice->getInvoice($request->invoice_id);
        if (!$invoice) {
            return response()->json(['error' => 'No invoice found for testing.'], 404);
        }


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
        $payload = $this->payment->savePaymentToQuickbook($request);
        if ($payload) {
            $this->zapier->send('add_payment', $payload);
        }
        $this->invoice->syncPaymentStatus($request->invoice_id);

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
        $payment = $this->payment->editPayment($request->id);
        $res = $this->payment->updatePayment($request);
        if ($res) {
            if ($payment && $payment->invoice_id) {
                $this->invoice->syncPaymentStatus($payment->invoice_id);
            }
            return response()->json(['success' => 'Record updated successfully'], 200);
        }

    }
    public function deletePayment(Request $request)
    {
        $payment = $this->payment->editPayment($request->id);
        $res=$this->payment->deletePayment($request->id);
        if($res){
            if ($payment && $payment->invoice_id) {
                $this->invoice->syncPaymentStatus($payment->invoice_id);
            }
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }

    }





}
