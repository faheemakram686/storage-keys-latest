<?php
namespace App\Repo;
use App\IPPReferenceType;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Repo\Interfaces\ContactInterface;

use App\Repo\Interfaces\ContractInterface;
use App\Repo\Interfaces\InvoiceInterface;
use App\Services\StorageUnitStatusService;
use App\Services\ZapierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;

class InvoiceClass implements InvoiceInterface {

    /** @var StorageUnitStatusService */
    protected $unitStatus;

    public function __construct(?StorageUnitStatusService $unitStatus = null)
    {
        $this->unitStatus = $unitStatus ?: app(StorageUnitStatusService::class);
    }

    public function saveInvoice($request)
    {

        $invoice =new Invoice();
        $invoice->customer_id = $request->customer_id;
        $invoice->type = $request->invoice_type;
        $invoice->contract_id = $request->contract_id;
        $invoice->order_id = $request->order_id;
        $invoice->recurring = $request->recurring;
        $invoice->no_cycle = $request->no_cycle;
        if($request->recurring == 'custom')
        {
            $invoice->duration = $request->duration;
            $invoice->duration_type = $request->duration_type;
        }
        $invoice->invoice_date = $request->invoice_date;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->user_id = $request->sale_agent;
        $invoice->sub_total = $request->sub_total;
        $invoice->vat = $request->vat;
        $invoice->grand_total = $request->grand_total;
        $invoice->due_date = $request->due_date;
        $invoice->note = $request->note;
        $invoice->payment_method = $request->payment_method;
        $invoice->status = $request->status;
        if($invoice->save()){
                if ($request->invoiceItems)
                {
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
                }

            if ($invoice->contract_id) {
                $contract = Contract::find($invoice->contract_id);
                if ($contract) {
                    $this->unitStatus->recalculateForContract($contract, 'invoice.created');
                }
            }

            $invoice = Invoice::with(['invoiceItems.productdetail', 'invoiceItems.termsdetail','invoiceItems.addOndetail','customer'])->find($invoice->id);

            if ($invoice->invoiceItems->isEmpty()) {
                \Log::info("Invoice #{$invoice->id} has no items yet. Zapier sync skipped.");
                return;
            }

            $payload = [
                'invoice' => [
                    'id' => $invoice->id,
                    'customer_id' => optional($invoice->customer)->q_customer_id,
                    'type' => $invoice->type,
                    'contract_id' => $invoice->contract_id,
                    'order_id' => $invoice->order_id,
                    'recurring' => $invoice->recurring,
                    'no_cycle' => $invoice->no_cycle,
                    'duration' => $invoice->duration,
                    'duration_type' => $invoice->duration_type,
                    'invoice_date' => $invoice->invoice_date,
                    'invoice_no' => $invoice->invoice_no,
                    'user_id' => $invoice->user_id,
                    'sub_total' => $invoice->sub_total,
                    'vat' => $invoice->vat,
                    'grand_total' => $invoice->grand_total,
                    'due_date' => $invoice->due_date,
                    'note' => $invoice->note,
                    'payment_method' => $invoice->payment_method,
                    'status' => $invoice->status,
                ],
                'invoiceItems' => $invoice->invoiceItems->map(function ($item) {
                    $itemId = match ($item->category) {
                        'product'       => optional($item->productdetail)->q_product_id,
                        'addon'         => optional($item->addOndetail)->q_service_id,
                        'storage_unit'  => optional($item->termsdetail)->q_service_id,
                        default         => null,
                    };
                    return [
                        'id' => $item->id,
                        'invoice_id' => $item->invoice_id,
                        'item_id' => $itemId,
                        'category' => $item->category,
                        'item_name' => $item->item_name,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                    ];
                })->toArray(),
            ];

            app(ZapierService::class)->send('invoice', $payload);

            return response()->json(['success' => 'Record save successfully'], 200);

            }

    }
    public function convertInvoice($request)
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

    public function getAllInvoices()
    {
        $invoices = Invoice::with(array_merge([
            'customer',
            'estimate.storageunit',
            'estimate.estimateStorageUnits.storageunit',
            'contract',
            'order',
        ], Invoice::paymentStatusRelations()))
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $pendingCount = 0;
        $totalBalance = 0;

        $invoices->each(function ($invoice) use (&$pendingCount, &$totalBalance) {
            $invoice->applyResolvedPaymentStatus();
            $balance = $invoice->balanceAmount();
            $invoice->setAttribute('balance', $balance);

            if ($balance > 0) {
                $pendingCount++;
                $totalBalance += $balance;
            }
        });

        return [
            'invoices' => $invoices,
            'summary' => [
                'pending_count' => $pendingCount,
                'total_balance' => round($totalBalance, 2),
            ],
        ];
    }
    public function getCustomerInvoices($customerid)
    {
        $invoices = Invoice::with(array_merge([
            'customer',
            'estimate.storageunit',
            'estimate.estimateStorageUnits.storageunit',
            'contract',
            'order',
        ], Invoice::paymentStatusRelations()))
            ->where('customer_id', $customerid)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $invoices->each(function ($invoice) {
            $invoice->syncPaymentStatus();
        });

        return $invoices;
    }
    public function getCustomerInvoicesApi($customerid)
    {
        try {
            $invoices = Invoice::with(array_merge([
                'invoiceItems',
                'customer',
                'estimate.storageunit',
                'estimate.estimateStorageUnits.storageunit',
                'contract',
            ], Invoice::paymentStatusRelations()))
                ->where('customer_id', $customerid)
                ->where('is_deleted', 0)
                ->orderBy('id', 'DESC')
                ->get();

            $invoices->each(function ($invoice) {
                $invoice->syncPaymentStatus();
            });

            return response()->json([
                'Invoices' => $invoices,
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
    public function deleteInvoice($id)
    {
        $country=Invoice::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;

    }

    public function editInvoice($id)
    {
        $invoices = Invoice::with(array_merge([
            'customer.primaryContact',
            'contract',
            'invoiceItems',
        ], Invoice::paymentStatusRelations()))
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $invoices->each(function ($invoice) {
            $invoice->applyResolvedPaymentStatus();
        });

        return $invoices;
    }
    public function getInvoice($id)
    {
        $invoices = Invoice::with(array_merge([
            'customer.primaryContact',
            'contract',
            'invoiceItems',
        ], Invoice::paymentStatusRelations()))
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $invoices->each(function ($invoice) {
            $invoice->syncPaymentStatus();
        });

        return $invoices;
    }

    public function updateInvoice($request)
    {
        $invoice =Invoice::find($request->invoice_id);
        $invoice->customer_id = $request->customer_id;
        $invoice->type = $request->invoice_type;
        $invoice->contract_id = $request->contract_id;
        $invoice->order_id = $request->order_id;
        $invoice->recurring = $request->recurring;
        $invoice->no_cycle = $request->no_cycle;
        if($request->recurring == 'custom')
        {
            $invoice->duration = $request->duration;
            $invoice->duration_type = $request->duration_type;
        }
        $invoice->invoice_date = $request->invoice_date;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->user_id = $request->sale_agent;
        $invoice->sub_total = $request->sub_total;
        $invoice->vat = $request->vat;
        $invoice->grand_total = $request->grand_total;
        $invoice->due_date = $request->due_date;
        $invoice->note = $request->note;
        $invoice->payment_method = $request->payment_method;
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

    public function getInvoiceItems($id)
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
    public function changePaymentStatus($id, $status = null)
    {
        if ($status === null) {
            return $this->syncPaymentStatus($id);
        }

        $invoice = Invoice::find($id);
        if (!$invoice) {
            return 0;
        }

        $invoice->payment_status = $status;
        $invoice->save();

        return 1;
    }

    public function syncPaymentStatus($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return 0;
        }

        $status = $invoice->syncPaymentStatus();

        if ($invoice->contract_id) {
            $contract = Contract::find($invoice->contract_id);
            if ($contract) {
                $this->unitStatus->recalculateForContract($contract, 'payment.synced');
            }
        }

        return $status;
    }
    public function updateInvoiceRef($invoiceReq)
    {

        $invoice =Invoice::find($invoiceReq->id);
        $invoice->invoice_ref = $invoiceReq->invoice_ref;
        $invoice->save();
        return 1;
    }


    public function generateOrderInvoice($id)
    {

    }

    public function refreshToken(){
        $config = config('quickbooks');
        $oauth2LoginHelper = new OAuth2LoginHelper($config['client_id'],$config['client_secret']);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($config['refresh_token']);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        return [
            'access_token'=>$accessTokenValue,
            'refresh_token'=>$refreshTokenValue
        ];
    }
}
