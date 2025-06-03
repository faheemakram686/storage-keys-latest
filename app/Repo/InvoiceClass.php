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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;

class InvoiceClass implements InvoiceInterface {


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

//            $refreshtoken = $this->refreshToken();
//                $config = config('quickbooks');
//                $dataService = DataService::Configure([
//                    'auth_mode' => 'oauth2',
//                    'ClientID' => $config['client_id'],
//                    'ClientSecret' => $config['client_secret'],
//                    'RedirectURI' => $config['redirect_uri'],
//                    'accessTokenKey' => $refreshtoken['access_token'],
//                    'refreshTokenKey' => $refreshtoken['refresh_token'],
//                    'QBORealmID' => $config['realm_id'],
//                    'baseUrl' => $config['base_url'],
//                ]);
//                $myinvoice = Invoice::find($invoice->id)->first();
//                $items = InvoiceItem::query();
//                $items = $items->where('invoice_id',$invoice->id);
//                $items = $items->get();
//
//        $invoiceObj = \QuickBooksOnline\API\Facades\Invoice::create([
//            "Line" => [
//                [
//                "Amount" => $myinvoice->_total,
//                "DetailType" => "SalesItemLineDetail",
//                "SalesItemLineDetail" => [
//                    "Qty" => 2,
//                    "ItemRef" => [
//                        "value" => 42
//                    ]
//                ]
//            ],
////                [
////                    "Amount" => $myinvoice->grand_total,
////                    "DetailType" => "SalesItemLineDetail",
////                    "SalesItemLineDetail" => [
////                        "Qty" => 3,
////                        "ItemRef" => [
////                            "value" => 41
////                        ]
////                    ]
////                ],
//            ],
//            "CustomerRef"=> [
//                "value"=> $myinvoice->customer->q_customer_id,
//            ],
//            "BillEmail" => [
//                "Address" => "author@intuit.com"
//            ]
//        ]);
//        $resultingInvoiceObj = $dataService->Add($invoiceObj);
//            $error = $dataService->getLastError();
//            if ($error) {
//                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
//                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
//                echo "The Response message is: " . $error->getResponseBody() . "\n";
//            }
//            else {
//
//                echo "Created Id={$resultingInvoiceObj->Id}. Reconstructed response body:\n\n";
//            }



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
        $qry=Invoice::with('customer','estimate.storageunit','contract','order');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getCustomerInvoices($customerid)
    {
        $qry=Invoice::with('customer','estimate.storageunit','contract');
        $qry=$qry->where('customer_id',$customerid);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getCustomerInvoicesApi($customerid)
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
    public function deleteInvoice($id)
    {
        $country=Invoice::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;

    }

    public function editInvoice($id)
    {
        $qry=Invoice::with('customer.primaryContact','contract','invoiceItems');
        $qry=$qry->where('id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getInvoice($id)
    {
        $qry=Invoice::with('customer.primaryContact','contract','invoiceItems');
        $qry=$qry->where('id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
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
    public function changePaymentStatus($id,$status)
    {
        $invoice =Invoice::find($id);
        $invoice->payment_status = 1;
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
