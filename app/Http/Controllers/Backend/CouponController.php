<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\CouponInterFace;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private $coupon;
    public function __construct(CouponInterFace $coupon)
    {
        $this->coupon = $coupon;
    }
    public function index()
    {
        return view('backend.coupons.index');
    }
    public function getAllCoupons()
    {
        return $res=$this->coupon->getAllCoupons();
    }

    public function saveCoupons(Request $request)
    {

        //$validated = $request->validated();
        return $this->coupon->saveCoupon($request);

    }

    public function deleteCoupon(Request $request)
    {
        $this->coupon->deleteCoupon($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editCoupon(Request $request)
    {
        $res=$this->coupon->editCoupon($request->id);
        return $res;
    }

    public function updateCoupon(Request $request)
    {
        $res=$this->coupon->updateCoupon($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
}
