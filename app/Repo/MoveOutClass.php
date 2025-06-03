<?php
namespace App\Repo;
use App\Http\Controllers\Backend\MoveInRequestController;
use App\Models\BarcodeLabel;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\MoveIn;
use App\Models\MoveInRequest;
use App\Models\MoveOut;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadSourceInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\MoveInInterface;
use App\Repo\Interfaces\MoveInRequestInterface;
use App\Repo\Interfaces\MoveOutInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MoveOutClass implements MoveOutInterface {

    public function saveMoveOut($request)
    {
        $moveout = MoveOut::updateOrCreate(
            [
                'contract_id' => $request->contract_id
            ],
            [
                'customer_id' =>  $request->customer_id,
                'contract_id' => $request->contract_id,
                'moved_out_items' => count($request->barcodeItems['id']),
                'note' =>$request->note,
                'move_date_date' => now(),
                'status' => 1
            ]
        );

        if ($moveout){
            if ($request->barcodeItems)
            {
                for($i =0; $i < count($request->barcodeItems['id']);$i++)
                {
                    $id = $request->barcodeItems['id'][$i];
                    $barcode = BarcodeLabel::find($id);
                    $barcode->status = 2;
                    $barcode->save();
                }
            }
            return response()->json(['success' => 'Record save successfully'], 200);
        }

    }

    public function getAllMoveOut()
    {
        $qry=MoveOut::with('customer','contract');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteMoveOut($id)
    {
        $country=MoveIn::find($id);
        $country->is_deleted=1;
        if($country->save()){
            return 1;
        }

    }

    public function editMoveOut($id)
    {
        return $country=MoveInRequest::find($id);
    }
    public function genrateBarcode($request)
    {
        $data = collect([]);
        for ($i=1 ;$i<=$request->qty;$i++)
        {
        $country=new BarcodeLabel();
        $country->request_id = $request->request_id;
        $country->code =  rand(100000000, 999999999);
        $country->status=0;
        $country->save();

            $data->push($country);
        }
        return $data;
    }
    public function getBarcodes($id)
    {
       $barcode = BarcodeLabel::query();
       $barcode = $barcode->where('request_id',$id);
       $barcode = $barcode->where('is_deleted',0);
       $barcode = $barcode->get();
       return $barcode;
    }

    public function updateMoveOut($request)
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
