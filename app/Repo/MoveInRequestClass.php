<?php
namespace App\Repo;
use App\Http\Controllers\Backend\MoveInRequestController;
use App\Models\BarcodeLabel;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\MoveInRequest;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadSourceInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\MoveInRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MoveInRequestClass implements MoveInRequestInterface {

    public function saveMoveInRequest($request)
    {
        $country=new MoveInRequest();
        $country->customer_id = $request->customer_id;
        $country->contract_id = $request->contract_id;
        $country->request_date = $request->date;
        $country->note = $request->note;
        $country->status=$request->status;
        if($country->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function saveMoveInRequestApi($request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'customer_id' => 'required',
                    'contract_id' => 'required',
                    'date' => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $country=new MoveInRequest();
            $country->customer_id = $request->customer_id;
            $country->contract_id = $request->contract_id;
            $country->request_date = $request->date;
            $country->note = $request->request_type;
            $country->status=1;
            if($country->save()){
                return response()->json([
                    'movieRequest' => $country,
                    'status' => true,
                    'message' => 'Request save successfully',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }


    }
    public function getAllMoveInRequest()
    {
        $qry=MoveInRequest::with('customer','contract');
        $qry = $qry->withCount('barcode');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getCustomerMoveInRequest($id)
    {
        $qry= MoveInRequest::with('customer','contract');
        $qry = $qry->where('id',$id);
        $qry=$qry->get();
        return $qry;
    }

    public function deleteMoveInRequest($id)
    {
        $country=MoveInRequest::find($id);
        $country->is_deleted=1;
        if($country->save()){
            return 1;
        }

    }
    public function deleteBarcode($id)
    {
        $country=BarcodeLabel::find($id);
        $country->is_deleted=1;
        if($country->save()){
            return 1;
        }

    }

    public function editMoveInRequest($id)
    {
        return $country=MoveInRequest::find($id);
    }

    public function getMoveInRequest($id)
    {
        $qry=MoveInRequest::query();
        $qry = $qry->where('contract_id',$id);
        $qry = $qry->first();
        return $qry;

    }
    public function genrateBarcode($request)
    {
        $data = collect([]);
        for ($i=1 ;$i<=$request->qty;$i++)
        {
        $country=new BarcodeLabel();
        $country->request_id = $request->request_id;
        $country->contract_id = $request->contract_id;
        $country->code =  rand(100000000, 999999999);
        $country->status=0;
        $country->save();
        $data->push($country);
        }
        return $data;
    }
    public function getBarcodes($id)
    {
       $barcode = BarcodeLabel::with('moverequest');
       $barcode = $barcode->where('code',$id);
       $barcode = $barcode->where('is_deleted',0);
       $barcode = $barcode->get();
       return $barcode;
    }
    public function getAllBarcodes($id)
    {
       $barcode = BarcodeLabel::with('moverequest');
       $barcode = $barcode->where('request_id',$id);
       $barcode = $barcode->where('is_deleted',0);
       $barcode = $barcode->get();
       return $barcode;
    }
    public function getBarcodesMoved($id)
    {
       $barcode = BarcodeLabel::query();
       $barcode = $barcode->where('request_id',$id);
       $barcode = $barcode->where('is_deleted',0);
       $barcode = $barcode->where('status',1);
       $barcode = $barcode->get();
       return $barcode;
    }

    public function updateMoveInRequest($request)
    {
        $country=MoveInRequest::find($request->id);
        $country->customer_id = $request->edit_customer_id;
        $country->contract_id = $request->edit_contract_id;
        $country->request_date = $request->edit_date;
        $country->note = $request->edit_note;
        $country->status=$request->edit_status;
        if($country->save()){
            return 1;
        }
    }
}
