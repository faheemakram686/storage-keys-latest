<?php
namespace App\Repo\Interfaces;

interface CouponInterFace{
    public function saveCoupon($request);

    public function getAllCoupons();
    public function deleteCoupon($id);
    public function editCoupon($id);
    public function updateCoupon($request);
}
