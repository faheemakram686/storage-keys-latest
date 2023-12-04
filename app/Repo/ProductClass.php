<?php
namespace App\Repo;

use App\Models\Location;
use App\Models\Product;
use App\Models\StorageType;
use App\Models\Warehouse;
use App\Repo\Interfaces\ProductInterface;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Item;

class ProductClass implements ProductInterface {

    public function saveProduct($request)
    {
        if ($request->hasFile('file')) {
            $uniqueid = uniqid();
            $original_name = $request->file('file')->getClientOriginalName();
            $size = $request->file('file')->getSize();
            $extension = $request->file('file')->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $imagepath = url('/storage/uploads/product-images/' . $name);
            $path = $request->file('file')->storeAs('public/uploads/product-images/', $name);
        }else{

            $name='empty';
        }

        // TODO: Implement saveProduct() method.
        $sy =new Product();
        $sy->p_name=$request->p_name;
        $sy->detail=$request->des;
        $sy->pur_price=$request->p_price;
        $sy->sell_price=$request->s_price;
        $sy->disc_type=$request->dis_type;
        $sy->disc_amount=$request->dis_amount;
        $sy->qty=$request->qty;
        $sy->image=$name;
        $sy->status=$request->status;
        if($sy->save()){
            $refreshtoken = $this->refreshToken();
            $config = config('quickbooks');
            $dataService = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => $config['client_id'],
                'ClientSecret' => $config['client_secret'],
                'RedirectURI' => $config['redirect_uri'],
                'accessTokenKey' => $refreshtoken['access_token'],
                'refreshTokenKey' => $refreshtoken['refresh_token'],
                'QBORealmID' => $config['realm_id'],
                'baseUrl' => $config['base_url'],
            ]);
            $dateTime = now();
            $Item = Item::create([
                "Name" => $sy->p_name,
                "Description" => $sy->detail,
                "Active" => true,
                "FullyQualifiedName" => "Office Supplies",
                "Taxable" => true,
                "UnitPrice" => $sy->sell_price,
                "Type" => "Inventory",
                "IncomeAccountRef" => [
                    "value" => 129,
                    "name" => "Sales of Product Income"
                ],
                "PurchaseDesc" => "This is the purchasing description.",
                "PurchaseCost" => $sy->pur_price,
                "ExpenseAccountRef" => [
                    "value" => 130,
                    "name" => "Cost of Goods Sold"
                ],
                "AssetAccountRef" => [
                    "value" => 131,
                    "name" => "Inventory Asset"
                ],
                "TrackQtyOnHand" => true,
                "QtyOnHand" => $sy->qty,
                "InvStartDate" => $dateTime
            ]);
            $resultObj = $dataService->Add($Item);
            if(isset($resultObj))
            {
                $mypro=Product::find($sy->id);
                $mypro->q_product_id = $resultObj-> Id;
                $mypro->save();
            }
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }
    public function getProduct()
    {
        $qry=Product::query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;

    }
    public function getProductPaginate()
    {
        $qry=Product::query();
        $qry=$qry->where('status',1);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->paginate(8);
        return $qry;
    }
    public function deleteProduct($id)
    {
        // TODO: Implement deleteProduct() method.
        $country=Product::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }
    public function editProduct($id)
    {
        // TODO: Implement editProduct() method.
        return $country=Product::find($id);
    }
    public function updateProduct($request)
    {
        // TODO: Implement updateProduct() method.
        $name=0;
        if ($request->hasFile('e_file')) {
            $uniqueid = uniqid();
            $original_name = $request->file('e_file')->getClientOriginalName();
            $size = $request->file('e_file')->getSize();
            $extension = $request->file('e_file')->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $imagepath = url('/storage/uploads/product-images/' . $name);
            $path = $request->file('e_file')->storeAs('public/uploads/product-images/', $name);
        }
        $sy=Product::find($request->id);
        $sy->p_name=$request->e_p_name;
        $sy->detail=$request->e_des;
        $sy->pur_price=$request->e_p_price;
        $sy->sell_price=$request->e_s_price;
        $sy->disc_type=$request->e_dis_type;
        $sy->disc_amount=$request->e_dis_amount;
        $sy->qty=$request->e_qty;
        if($name!=0){
            $sy->image=$name;
        }
        $sy->status=$request->e_status;
        $sy->save();
        return 1;
    }
    public function getProductDetail($id)
    {
        return $product=Product::find($id);
    }
    public function syncWithQuickbook()
    {
        $refreshtoken = $this->refreshToken();
        $config = config('quickbooks');
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'accessTokenKey' => $refreshtoken['access_token'],
            'refreshTokenKey' => $refreshtoken['refresh_token'],
            'QBORealmID' => $config['realm_id'],
            'baseUrl' => $config['base_url'],
        ]);
        $query = "SELECT * FROM Item";
        $q_products = $dataService->Query($query);

        if(isset($q_products))
        {
            DB::transaction(function() use ($q_products)
            {
                foreach ($q_products as $q_product)
                {
                    if(!Product::where('q_product_id',$q_product->Id)->first())
                    {
                     $myproduct = new Product();
                     $myproduct->q_product_id = $q_product->Id;
                     $myproduct->p_name = $q_product->Name;
                     $myproduct->sell_price = $q_product->UnitPrice;
                     $myproduct->pur_price = $q_product->PurchaseCost;
                     $myproduct->qty = $q_product->QtyOnHand;
                     $myproduct->detail = $q_product->Description;
                     $myproduct->status = 1;
                     $myproduct->save();
                    }

                }
            });
        }

        $qry=Product::query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();

        $dateTime = now();
        foreach ($qry as $product)
        {
            $query = "SELECT * FROM Item WHERE Name = '{$product->p_name}'";
            $deup_products = $dataService->Query($query);

            if (!isset($deup_products) && empty($deup_products)) {
                $Item = Item::create([
                    "Name" => $product->p_name,
                    "Description" => $product->detail,
                    "Active" => true,
                    "FullyQualifiedName" => "Office Supplies",
                    "Taxable" => true,
                    "UnitPrice" => $product->sell_price,
                    "Type" => "Inventory",
                    "IncomeAccountRef" => [
                        "value" => 129,
                        "name" => "Sales of Product Income"
                    ],
                    "PurchaseDesc" => "This is the purchasing description.",
                    "PurchaseCost" => $product->pur_price,
                    "ExpenseAccountRef" => [
                        "value" => 130,
                        "name" => "Cost of Goods Sold"
                    ],
                    "AssetAccountRef" => [
                        "value" => 131,
                        "name" => "Inventory Asset"
                    ],
                    "TrackQtyOnHand" => true,
                    "QtyOnHand" => $product->qty,
                    "InvStartDate" => $dateTime
                ]);
                $resultObj = $dataService->Add($Item);
                if(isset($resultObj))
                {
                    $mypro=Product::find($product->id);
                    $mypro->q_product_id = $resultObj-> Id;
                    $mypro->save();
                }
            }
        }

        $error = $dataService->getLastError();
        if ($error) {
//            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
//            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
//            echo "The Response message is: " . $error->getResponseBody() . "\n";
            return response()->json(['errors' => $error->getResponseBody()], $error->getHttpStatusCode());
        }else {

            return response()->json(['success' => "Data Inserted Successfully"], 200);

        }
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
