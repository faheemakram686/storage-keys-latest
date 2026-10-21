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
use App\Models\InvoicePaymentAttempt;
use App\Services\InvoiceNgeniusPaymentService;
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
    private $invoicePaymentService;
    private $zapier;

    public function __construct(
        InvoiceInterface $invoice,
        NgeniusPaymentService $paymentService,
        InvoiceNgeniusPaymentService $invoicePaymentService,
        ZapierService $zapier
    ) {
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
        $this->invoicePaymentService = $invoicePaymentService;
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
        return response()->json($this->invoice->getAllInvoices());
    }

    public function getCustomerInvoices(Request $request)
    {
        return $this->invoice->getCustomerInvoices($request->customer_id);
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
        $data['payment'] = $this->payment->getPaymentSum($id);
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
    public function payNowByCustomer(Request $request, $id)
    {
        $invoices = $this->invoice->getInvoice($id);
        $invoice = $invoices[0] ?? null;

        if (!$invoice) {
            return redirect()
                ->to(url('invoice-to-customer/' . hashid_encode($id)))
                ->with('error', 'Invoice not found. Please try again.');
        }

        $invoice->loadMissing(['customer.primaryContact']);
        $invoice->load(array_keys(Invoice::paymentStatusRelations()));
        $invoice->syncPaymentStatus();

        if ($invoice->isFullyPaid()) {
            return redirect()
                ->to(url('invoice-to-customer/' . hashid_encode($invoice->id)))
                ->with('success', 'This invoice is already paid.');
        }

        $balance = $invoice->balanceAmount();
        if ($balance <= 0) {
            return redirect()
                ->to(url('invoice-to-customer/' . hashid_encode($invoice->id)))
                ->with('error', 'No outstanding balance to pay.');
        }

        if (!$invoice->customer) {
            return redirect()
                ->to(url('invoice-to-customer/' . hashid_encode($invoice->id)))
                ->with('error', 'Customer details are missing for this invoice.');
        }

        $saveCard = $request->boolean('save_card');
        $invoiceUrl = url('invoice-to-customer/' . hashid_encode($invoice->id));
        $response = $this->paymentService->createOrder($balance, 'AED', 1, $invoice->customer, [
            'merchant_order_reference' => $this->paymentService->uniqueMerchantOrderReference(
                $invoice->invoice_no,
                (int) $invoice->id
            ),
            'cancel_url' => $invoiceUrl,
            'include_saved_card' => $saveCard,
        ]);

        if (!$response || empty($response['_links']['payment']['href'])) {
            return redirect()
                ->to($invoiceUrl)
                ->with('error', 'Payment initiation failed. Please try again.');
        }

        $orderReference = (string) ($response['reference'] ?? '');
        if ($orderReference === '') {
            return redirect()
                ->to($invoiceUrl)
                ->with('error', 'Payment initiation failed. Please try again.');
        }

        $this->invoicePaymentService->recordAttempt(
            $invoice,
            $orderReference,
            $balance,
            InvoicePaymentAttempt::SOURCE_HOSTED,
            $saveCard
        );

        return redirect()->away($response['_links']['payment']['href']);
    }

    public function getCustomerInvoicesApi(Request $request)
    {
        return $this->invoice->getCustomerInvoicesApi($request->customer_id);
    }

    /**
     * Mobile API: initiate an Ngenius payment for a customer's invoice.
     * Verifies invoice ownership, creates the order and returns the hosted
     * payment URL as JSON (no redirect) for the Flutter app to open.
     */
    public function payInvoiceApi(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer',
            'save_card' => 'sometimes|boolean',
        ]);

        $customerId = optional($request->user())->customer_id;
        if (!$customerId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $invoice = Invoice::where('id', $request->invoice_id)
            ->where('customer_id', $customerId)
            ->where('is_deleted', 0)
            ->first();

        if (!$invoice) {
            return response()->json(['status' => false, 'message' => 'Invoice not found'], 404);
        }

        $invoice->load(array_keys(Invoice::paymentStatusRelations()));
        $invoice->syncPaymentStatus();

        if ($invoice->isFullyPaid()) {
            return response()->json(['status' => false, 'message' => 'Invoice already paid'], 422);
        }

        $balance = $invoice->balanceAmount();
        if ($balance <= 0) {
            return response()->json(['status' => false, 'message' => 'No outstanding balance to pay'], 422);
        }

        $invoice->loadMissing(['customer.primaryContact']);
        if (!$invoice->customer) {
            return response()->json(['status' => false, 'message' => 'Customer details are missing for this invoice'], 422);
        }

        $saveCard = $request->boolean('save_card');
        $response = $this->paymentService->createOrder($balance, 'AED', 1, $invoice->customer, [
            'merchant_order_reference' => $this->paymentService->uniqueMerchantOrderReference(
                $invoice->invoice_no,
                (int) $invoice->id
            ),
            'cancel_url' => url('invoice-to-customer/' . hashid_encode($invoice->id)),
            'include_saved_card' => $saveCard,
        ]);

        if (!$response || empty($response['_links']['payment']['href'])) {
            return response()->json(['status' => false, 'message' => 'Payment initiation failed. Please try again.'], 502);
        }

        $orderReference = (string) ($response['reference'] ?? '');
        if ($orderReference === '') {
            return response()->json(['status' => false, 'message' => 'Payment initiation failed. Please try again.'], 502);
        }

        $this->invoicePaymentService->recordAttempt(
            $invoice,
            $orderReference,
            $balance,
            InvoicePaymentAttempt::SOURCE_MOBILE,
            $saveCard
        );

        return response()->json([
            'status'      => true,
            'message'     => 'Payment initiated',
            'payment_url' => $response['_links']['payment']['href'],
            'reference'   => $orderReference,
            'invoice_id'  => $invoice->id,
            'amount'      => $balance,
            'currency'    => 'AED',
            'save_card'   => $saveCard,
        ], 200);
    }

    /**
     * Mobile API: verify an invoice payment against Ngenius and finalize it.
     * This is the source of truth - it queries the gateway order status and
     * only marks the invoice paid when Ngenius confirms the payment.
     */
    public function verifyPaymentApi(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer',
        ]);

        $customerId = optional($request->user())->customer_id;
        if (!$customerId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $invoice = Invoice::where('id', $request->invoice_id)
            ->where('customer_id', $customerId)
            ->where('is_deleted', 0)
            ->first();

        if (!$invoice || !$invoice->invoice_ref) {
            return response()->json(['status' => false, 'message' => 'Invoice not found'], 404);
        }

        $result = $this->invoicePaymentService->confirmByReference(
            $invoice,
            $invoice->invoice_ref,
            'Paid via mobile app (N-Genius, verified)'
        );

        if (!empty($result['success']) && ($result['message'] ?? '') === 'Payment already recorded') {
            return response()->json([
                'status'     => true,
                'message'    => 'Payment already recorded',
                'invoice_id' => $invoice->id,
            ], 200);
        }

        if (($result['message'] ?? '') === 'Unable to verify payment') {
            return response()->json(['status' => false, 'message' => 'Unable to verify payment. Please try again.'], 502);
        }

        if (empty($result['success'])) {
            $order = $this->paymentService->getOrderStatus($invoice->invoice_ref);
            return response()->json([
                'status'        => false,
                'message'       => 'Payment not completed',
                'payment_state' => $order ? $this->paymentService->extractPaymentState($order) : null,
            ], 402);
        }

        return response()->json([
            'status'     => true,
            'message'    => 'Payment verified successfully',
            'invoice_id' => $invoice->id,
            'amount'     => $result['amount'],
        ], 200);
    }
    public function orderInvoice($id)
    {
        return $id;
        return $this->invoice->getCustomerInvoicesApi($request->customer_id);
    }

    public function saveResponse(Request $request)
    {
        $ref = (string) $request->query('ref', '');
        if ($ref === '') {
            return redirect('/')
                ->with('error', 'Invalid payment reference');
        }

        $resolved = $this->invoicePaymentService->resolveByOrderReference($ref);
        $invoice = $resolved['invoice'];
        if (!$invoice) {
            return redirect('/')
                ->with('error', 'Invalid payment reference');
        }

        $invoiceUrl = url('invoice-to-customer/' . hashid_encode($invoice->id));

        $result = $this->invoicePaymentService->confirmByReference(
            $invoice,
            $ref,
            'This invoice has been received via online payment method'
        );

        if (!empty($result['success'])) {
            return redirect()->to($invoiceUrl)->with('success', 'Payment successful!');
        }

        if (($result['status'] ?? '') === 'pending'
            || $this->paymentService->isPendingState($result['payment_state'] ?? null)
            || ($result['message'] ?? '') === 'Unable to verify payment') {
            return view('backend.invoice.payment-processing', [
                'ref' => $ref,
                'invoiceUrl' => $invoiceUrl,
                'statusUrl' => url('payment-status/' . urlencode($ref)),
            ]);
        }

        return redirect()->to($invoiceUrl)->with('error', 'Payment failed or cancelled.');
    }

    /**
     * Public polling endpoint for the payment processing page.
     * Returns only paid / pending / failed for a known order reference.
     */
    public function paymentStatus(string $ref)
    {
        $ref = trim($ref);
        if ($ref === '') {
            return response()->json(['status' => 'failed', 'message' => 'Invalid payment reference'], 400);
        }

        $result = $this->invoicePaymentService->statusForReference($ref);
        $payload = [
            'status' => $result['status'] ?? 'failed',
            'message' => $result['message'] ?? '',
        ];

        if (!empty($result['invoice_id'])) {
            $payload['invoice_url'] = url('invoice-to-customer/' . hashid_encode($result['invoice_id']));
        }

        return response()->json($payload);
    }
}
