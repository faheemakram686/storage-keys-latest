<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\ContractClass;
use App\Repo\CustomerClass;
use App\Repo\Interfaces\MoveInInterface;
use App\Repo\Interfaces\MoveInRequestInterface;
use Illuminate\Http\Request;

class MoveInController extends Controller
{

    private $customer;
    private $contract;
    private $moveIn;

    public function __construct(MoveInInterface $moveIn)
    {
        $this->moveIn = $moveIn;
        $this->customer = new CustomerClass();
        $this->contract = new ContractClass();

    }

    public function index()
    {
        return view('backend.move-in.index');
    }


    public function createMoveIn()
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['contracts'] = $this->contract->getAllContract();
        return view('backend.move-in.create')->with(compact('data'));
    }

    public function saveMoveIn(Request $request)
    {
           $data = $this->moveIn->saveMoveIn($request);
           return $data;
    }
      public function editMoveIn(Request $request)
        {
               $data = $this->moveIn->editMoveInBarcode($request);
               return $data;
        }


    public function getAllMoveIn()
    {
        $data = $this->moveIn->getAllMoveIn();
        return $data;
    }

    public function deleteMoveIn(Request $request)
    {
        $res = $this->moveIn->deleteMoveIn($request->id);
        if($res){
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }else{
            return response()->json(['success' => 'Record not deleted successfully'], 200);
        }
    }

    public function updateMoveIn(Request $request)
    {
        $res = $this->moveIn->updateMoveIn($request);
        if($res){
            return response()->json(['success' => 'Record Updated successfully'], 200);
        }else{
            return response()->json(['success' => 'Record not Updated successfully'], 200);
        }
    }



    public function viewMoveInItems($id)
    {
        $data['movein'] = $this->moveIn->getAllMovedInItems($id);
        return view('backend.move-in.show-move-in')->with(compact('data'));
    }

}
