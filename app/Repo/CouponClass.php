<?php
namespace App\Repo;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Location;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\CouponInterFace;
use App\Repo\Interfaces\LocationInterface;
use Illuminate\Http\Request;

class CouponClass implements CouponInterFace {
  public function saveCoupon($request)
  {
      // TODO: Implement saveCountry() method.


       $cop=new Coupon();
      $cop->code=$request->code;
      $cop->disc_type=$request->disc_type;
      $cop->from=$request->from;
      $cop->to=$request->to;
      $cop->amount=$request->amount;
      $cop->status=$request->status;

      if($cop->save()){
          return response()->json(['success' => 'Record save successfully'], 200);
      }
  }

  public function getAllCoupons()
  {
      // TODO: Implement getAllCountry() method.
       $qry=Coupon::query();
       $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
       $qry=$qry->get();
       return $qry;
  }

  public function deleteCoupon($id)
  {
      // TODO: Implement deleteCountry() method.
      $country=Coupon::find($id);
      $country->is_deleted=1;
      $country->save();
      return 1;
  }

  public function editCoupon($id)
  {
      // TODO: Implement editCountry() method.
      return $country=Coupon::find($id);

  }

public function updateCoupon($request)
{
    // TODO: Implement updateCountry() method.
    $cop=Coupon::find($request->coupon_id);
    $cop->code=$request->e_code;
    $cop->disc_type=$request->e_disc_type;
    $cop->from=$request->e_from;
    $cop->to=$request->e_to;
    $cop->amount=$request->e_amount;
    $cop->status=$request->e_status;
    $cop->save();
    return 1;
}
}
