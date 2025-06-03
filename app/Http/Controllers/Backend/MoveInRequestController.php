<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\ContractClass;
use App\Repo\CustomerClass;
use App\Repo\Interfaces\MoveInRequestInterface;
use Illuminate\Http\Request;

class MoveInRequestController extends Controller
{
    private $moveInRequest;
    private $customer;
    private $contract;

    public function __construct(MoveInRequestInterface $moveInRequest)
    {
        $this->moveInRequest = $moveInRequest;
        $this->customer = new CustomerClass();
        $this->contract =  new ContractClass();

    }
    public function index()
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['contracts'] = $this->contract->getAllContract();
        return view('backend.move-in-request.index')->with(compact('data'));
    }

    public function saveMoveInRequest(Request $request)
    {
//        return $request->all();
        return $this->moveInRequest->saveMoveInRequest($request);

    }
    public function saveMoveInRequestApi(Request $request)
    {

        return $this->moveInRequest->saveMoveInRequestApi($request);

    }

    public function getAllMoveInRequest()
    {
        $data = $this->moveInRequest->getAllMoveInRequest();
        return $data;
    }

    public function editMoveInRequest(Request $request)
    {
        $data['moveInRequest'] = $this->moveInRequest->editMoveInRequest($request->id);
        $data['customers'] = $this->customer->getAllCustomer();
        $data['contracts'] = $this->contract->getAllContract();
        return $data;
    }
    public function updateMoveInRequest(Request $request)
    {
//        return $request->all();
        $res=$this->moveInRequest->updateMoveInRequest($request);
        if($res){
            return response()->json(['success' => 'Record updated successfully'], 200);
        }else{
            return response()->json(['success' => 'Record not updated successfully'], 200);
        }


    }

    public function deleteMoveInRequest(Request $request)
    {
        $res = $this->moveInRequest->deleteMoveInRequest($request->id);
        if($res){
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }else{
            return response()->json(['success' => 'Record not deleted successfully'], 200);
        }

    }
    public function deleteBarcodeLabel(Request $request)
    {
        $res = $this->moveInRequest->deleteBarcode($request->id);
        if($res){
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }else{
            return response()->json(['success' => 'Record not deleted successfully'], 200);
        }

    }

    public function barcodeLabel(Request $request)
    {
        $data['barcode']=$this->moveInRequest->genrateBarcode($request);
        $data['moveInRequest']=$this->moveInRequest->getCustomerMoveInRequest($request->request_id);
        return view('backend.move-in-request.barcode-label')->with(compact('data'));
    }
    public function viewBarcodeLabels($id)
    {
        $data['barcode']=$this->moveInRequest->getAllBarcodes($id);
        $data['moveInRequest']=$this->moveInRequest->getCustomerMoveInRequest($id);
        return view('backend.move-in-request.show-barcode-label')->with(compact('data'));
    }
    public function printBarcodeLabels($id)
    {
        $data['barcode']=$this->moveInRequest->getAllBarcodes($id);
        $data['moveInRequest']=$this->moveInRequest->getCustomerMoveInRequest($id);
        return view('backend.move-in-request.barcode-label')->with(compact('data'));
    }
    public function reprintBarcodeLabels($id)
    {
        $data['barcode']=$this->moveInRequest->getBarcodes($id);
        $data['moveInRequest']=$this->moveInRequest->getCustomerMoveInRequest($data['barcode'][0]->request_id);
        return view('backend.move-in-request.barcode-label')->with(compact('data'));
    }

    public function getBarcodeLable(Request $request)
    {

//        $moveinrequest = $this->moveInRequest->getMoveInRequest();

        $data = $this->moveInRequest->getBarcodes($request->code);
        return $data;
    }
    public function getBarcodeLableMoved(Request $request)
    {
        $moveinrequest = $this->moveInRequest->getMoveInRequest($request->contract_id);
        $data = $this->moveInRequest->getBarcodesMoved($moveinrequest->id);
        return $data;
    }

}
