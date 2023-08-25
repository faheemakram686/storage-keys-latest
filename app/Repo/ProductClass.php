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
}
