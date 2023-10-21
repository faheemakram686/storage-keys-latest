<?php
namespace App\Repo;
use App\Http\Controllers\Backend\MoveInRequestController;
use App\Models\BarcodeLabel;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\MoveIn;
use App\Models\MoveInRequest;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadSourceInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\MoveInInterface;
use App\Repo\Interfaces\MoveInRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MoveInClass implements MoveInInterface {

    public function saveMoveIn($request)
    {

        $movein = MoveIn::updateOrCreate(
            [
                'contract_id' => $request->contract_id
            ],
            [
                'customer_id' =>  $request->customer_id,
                'contract_id' => $request->contract_id,
                'moved_items' => count($request->barcodeItems['id']),
                'note' =>$request->note,
                'move_in_date' => now(),
                'status' => 1
            ]
        );

        if ($movein){
            if ($request->barcodeItems)
            {
                for($i =0; $i < count($request->barcodeItems['id']);$i++)
                {
                    $id = $request->barcodeItems['id'][$i];
                    $barcode = BarcodeLabel::find($id);
                    $barcode->description = $request->barcodeItems['des'][$i];
                    $barcode->status = 1;
                    $barcode->save();
                }
            }
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllMoveIn()
    {
        $qry=MoveIn::with(['customer','contract.barcode' => function($query) { $query->where('status', 1); }]);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function viewMoveInItems($id)
    {
        $qry=MoveIn::with('customer','contract');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteMoveIn($id)
    {
        $country=MoveIn::find($id);
        $country->is_deleted=1;
        if($country->save()){
            return 1;
        }

    }

    public function editMoveIn($id)
    {
        return $country=MoveInRequest::find($id);
    }
    public function editMoveInBarcode($id)
    {
        return $country=BarcodeLabel::find($id)->first();
    }
    public function genrateBarcode($request)
    {
        $data = collect([]);
        for ($i=1 ;$i<=$request->qty;$i++)
        {
        $country=new BarcodeLabel();
        $country->request_id = $request->request_id;
        $country->code =  rand(100000000, 999999999);
        $country->status=1;
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

    public function updateMoveIn($request)
    {
        $country= BarcodeLabel::find($request->id);
        $country->description = $request->edit_des;
        $country->save();
        if($country->save()){
            return 1;
        }
    }

    public function getAllMovedInItems($id)
    {
        $movein = MoveIn::with(['customer','contract.barcode' => function($query) { $query->where('status', 1); }]);
        $movein = $movein->where('contract_id',$id);
        $movein = $movein->where('is_deleted',0);
        $movein = $movein->get();
        return $movein;
    }

  }
