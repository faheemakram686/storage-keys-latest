<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\ContractClass;
use App\Repo\CustomerClass;
use App\Repo\Interfaces\MoveInInterface;
use App\Repo\Interfaces\MoveOutInterface;
use Illuminate\Http\Request;

class MoveOutController extends Controller
{

    private $customer;
    private $contract;
    private $moveOut;

    public function __construct(MoveOutInterface $moveOut)
    {
        $this->moveOut = $moveOut;
        $this->customer = new CustomerClass();
        $this->contract = new ContractClass();

    }

    public function index()
    {
        return view('backend.move-out.index');
    }


    public function createMoveOut()
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['contracts'] = $this->contract->getAllContract();
        return view('backend.move-out.create')->with(compact('data'));
    }

    public function saveMoveOut(Request $request)
    {
        $data = $this->moveOut->saveMoveOut($request);
        return $data;
    }

    public function getAllMoveOut()
    {
        $data = $this->moveOut->getAllMoveOut();
        return $data;
    }

    public function deleteMoveOut(Request $request)
    {
        $res = $this->moveOut->deleteMoveOut($request->id);
        if($res){
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }else{
            return response()->json(['success' => 'Record not deleted successfully'], 200);
        }
    }
}
