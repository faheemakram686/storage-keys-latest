<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\ProductInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private $product;

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }
    public function index()
    {

        return view('backend.product.index');
    }
    public function saveProduct(Request $request)
    {
       return $data = $this->product->saveProduct($request);
    }
    public function getProduct()
    {
        return $res=$this->product->getProduct();
    }
    public function getProductDetail(Request $request)
    {
        return $res=$this->product->getProductDetail($request->product_id);
    }

    public function deleteProduct(Request $request)
    {
        $this->product->deleteProduct($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editProduct(Request $request)
    {
        $data['st']=$this->product->editProduct($request->id);
        return $data;
    }
    public function updateProduct(Request $request)
    {

        $res=$this->product->updateProduct($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function syncProductQuickbook()
    {
        return $this->product->syncWithQuickbook();
    }




}
