<?php
namespace App\Repo;

use App\Models\Insurance;
use App\Models\Location;
use App\Models\StorageType;
use App\Models\Warehouse;
use App\Repo\Interfaces\InsuranceInterface;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class InsuranceClass implements InsuranceInterface {

    public function saveInsurance($request)
    {
        // TODO: Implement saveInsurance() method.
        $sy =new Insurance();
        $sy->name=$request->name;
        $sy->monthly_amount=$request->monthly_amount;
        $sy->cover=$request->cover;
        $sy->status=$request->status;
        if($sy->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getInsurance()
    {
        // TODO: Implement getInsurance() method.
        $qry=Insurance::query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteInsurance($id)
    {
        // TODO: Implement deleteInsurance() method.
        $country=Insurance::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editInsurance($id)
    {
        // TODO: Implement editInsurance() method.
        return $country=Insurance::find($id);
    }

    public function updateInsurance($request)
    {
        // TODO: Implement updateInsurance() method.
        $sy=Insurance::find($request->id);
        $sy->name=$request->e_name;
        $sy->monthly_amount=$request->e_monthly_amount;
        $sy->cover=$request->e_cover;
        $sy->status=$request->e_status;
        $sy->save();
        return 1;
    }
}
