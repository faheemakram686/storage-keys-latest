<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
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
use App\Services\NgeniusPaymentService;
use App\Services\ZapierService;
use Illuminate\Http\Request;
use PDF;
use function PHPUnit\Framework\lessThanOrEqual;

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
    private $paymentService;
    private $zapier;

    public function __construct(InvoiceInterface $invoice, NgeniusPaymentService $paymentService,ZapierService $zapier)
    {
        $this->invoice = $invoice;
        $this->zapier = $zapier;
        $this->customer = new CustomerClass();
        $this->estimate = new EstimateClass();
        $this->user = new UserClass();
        $this->contract_template = new ContractTemplateClass();
        $this->contact =  new ContactClass();
        $this->contract =  new ContractClass();
        $this->payment = new PaymentClass();
        $this->order = new OrderClass();
        $this->paymentService = $paymentService;
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
        return view('backend.invoice.invoice-print')->with(compact('data'));
    }
    public function payNowByCustomer($id)
    {
        $data['invoice'] = $this->invoice->getInvoice($id);
        if($data['invoice']['0'])
        {

            $invoiceId = $data['invoice'][0]->id ?? null;
            $grandTotal = $data['invoice'][0]->grand_total ?? 0;
            $customerRef = $data['invoice'][0]->customer ?? "";


            $response = $this->paymentService->createOrder($grandTotal, 'AED', 1,$customerRef);


            if (!$response || empty($response['_links']['payment']['href'])) {
                return back()->withErrors(['error' => 'Payment initiation failed. Please try again.']);
            }

            $this->invoice->updateInvoiceRef((object)[
                'id'          => $invoiceId,
                'invoice_ref' => $response['reference'] ?? null,
            ]);

            return redirect()->away($response['_links']['payment']['href']);
        }else{
                return back()->withErrors(['error' => 'There is issue in inovice. Please try again.']);
             }

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

    public function saveResponse(Request $request)
    {
        $ref = $request->query('ref'); // The order reference
        if (!$ref) {
            return redirect()-back()->with('error', 'Invalid payment reference');
        }
        // Update your local DB
        $invoice = Invoice::where('invoice_ref', $ref)->first();
        if ($invoice) {
            $invoice->payment_status = 1;
            $invoice->save();
            $inovicePayment = (object)[
                'invoice_ref'   => $ref,
                'invoice_id'    => $invoice->id,
                'customer_id'   => $invoice->customer_id,
                'contract_id'   => $invoice->contract_id,
                'order_id'      => $invoice->order_id,
                'payment_method' => 3,
                'payment_date'  => now(),
                'amount_received'=> $invoice->grand_total,
                'note'          => 'This invoice has been received via online payment method',
            ];
            $paymentResponse = $this->payment->savePayment($inovicePayment);
            $responseData = $paymentResponse->getData();
            $payment = $responseData->data;
            $payload = $this->payment->savePaymentToQuickbook($payment);
            if ($payload){
                $this->zapier->send('add_payment', $payload);
            }
        }
        // Redirect user with status
        if ($payment) {
            return redirect('invoice-to-customer/'.$invoice->id)->with('success', 'Payment successful!');
        } else {
            return redirect('invoice-to-customer/'.$invoice->id)->with('error', 'Payment failed or cancelled.');
        }

    }
}
