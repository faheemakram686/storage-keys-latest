<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Repo\ContactClass;
use App\Repo\ContractClass;
use App\Repo\ContractTemplateClass;
use App\Repo\CustomerClass;
use App\Repo\EstimateClass;
use App\Repo\Interfaces\ContractInterface;
use App\Repo\Interfaces\InvoiceInterface;
use App\Repo\OrderClass;
use App\Repo\PaymentClass;
use App\Repo\UserClass;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    private $customer;
    private $estimate;
    private $invoice;
    private $contract;
    private  $user;
    private  $contract_template;
    private $contact ;
    private $payment;
    private $order;

    public function __construct(InvoiceInterface $invoice )
    {
        $this->invoice = $invoice;
        $this->customer = new CustomerClass();
        $this->estimate = new EstimateClass();
        $this->user = new UserClass();
        $this->contract_template = new ContractTemplateClass();
        $this->contact =  new ContactClass();
        $this->contract =  new ContractClass();
        $this->payment = new PaymentClass();
        $this->order = new OrderClass();
    }
    public function index()
    {
        return view('backend.invoice.index');
    }

    public function createInvoice()
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['contracts'] = $this->contract->getAllContract();
        $data['users'] = $this->user->getUser();
        return view('backend.invoice.create')->with(compact('data'));
    }
    public function createOrderInvoice($id)
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['users'] = $this->user->getUser();
        $data['order'] = $this->order->getOrder($id);

        return view('backend.invoice.create-order-invoice')->with(compact('data'));
    }
    public function saveInvoice(Request $request)
    {
        return $this->invoice->saveInvoice($request);
    }
    public function convertInvoice($id)
    {
        $data['contract'] = $this->contract->getContract($id);
        return $this->invoice->convertInvoice($data);
    }
    public function getAllInvoices()
    {
        $data = $this->invoice->getAllInvoices();
        return $data;
    }
    public function deleteInvoice(Request $request)
    {
        $this->invoice->deleteInvoice($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }
    public function editInvoice($id)
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['contracts'] = $this->contract->getAllContract();
        $data['users'] = $this->user->getUser();
        $data['invoice']= $this->invoice->editInvoice($id);
        return view('backend.invoice.edit')->with(compact('data'));
    }
    public function updateInvoice(Request $request)
    {
        $res = $this->invoice->updateInvoice($request);
       if($res){
           return response()->json(['success' => 'Record updated successfully'], 200);
       }

    }

    public function detailInvoice($id)
    {
        $data['invoice'] = $this->invoice->getInvoice($id);
        $data['payment'] = $this->payment->getPaymentSum($id);
        return view('backend.invoice.show')->with(compact('data'));
    }
    public function paymentInvoice($id)
    {
        $data['invoice'] = $this->invoice->getInvoice($id);
        $data['payment'] = $this->payment->getPaymentSum($id);
        return view('backend.invoice.invoice-payment')->with(compact('data'));
    }

    public function invoicePayments($id)
    {
        $data['invoice'] = $this->invoice->getInvoice($id);
        return view('backend.invoice.payments')->with(compact('data'));
    }
    public function getInvoiceItems(Request $request)
    {
        $data['invoiceItems'] = $this->invoice->getInvoiceItems($request->invoice_id);
        return $data;
    }
    public function printInvoice($id)
    {
        $data['invoice'] = $this->invoice->getInvoice($id);
        return view('backend.invoice.invoice-print')->with(compact('data'));
    }
    public function pdfInvoice($id)
    {
        $data['invoice'] = $this->invoice->getInvoice($id);
//        return view('backend.invoice.invoice-pdf')->with(compact('data'));
        $pdf = PDF::loadView('backend.invoice.invoice-pdf', compact('data'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream($data['invoice'][0]->invoice_no.'.pdf');
    }
    public function viewAsCustomerInvoice($id)
    {
        $data['invoice'] = $this->invoice->getInvoice($id);
        return view('backend.invoice.invoice-pdf')->with(compact('data'));
    }
    public function getCustomerInvoicesApi(Request $request)
    {
        return $this->invoice->getCustomerInvoicesApi($request->customer_id);
    }
    public function orderInvoice($id)
    {
        return $id;
        return $this->invoice->getCustomerInvoicesApi($request->customer_id);
    }
}
