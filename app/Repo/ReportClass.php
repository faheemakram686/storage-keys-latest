<?php
namespace App\Repo;

use App\Models\Contract;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\StorageUnit;
use App\Models\Warehouse;
use App\Repo\Interfaces\ReportInterface;
use Illuminate\Http\Request;

class ReportClass implements ReportInterface {


    public function getWarehouseReport($request)
    {
        $qry=StorageUnit::with('warehouse','storagetype','storagelevel','storagesize');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        if($request->wh_id)
            $qry = $qry->where( 'wh_id' , $request->wh_id);
        if($request->st_id)
            $qry = $qry->where( 'stype_id' , $request->st_id);
        if($request->sl_id)
            $qry = $qry->where( 'slevel_id' , $request->sl_id);
        if($request->ss_id)
            $qry = $qry->where( 'ssize_id' , $request->ss_id);
        if($request->status)
            $qry = $qry->where( 'status' , $request->status);
        return $qry->get();
    }
    public function getLeadReport($request)
    {
        $qry=Lead::with('storageunit','userresponsible','leadStatus','termLength','leadSource');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        if($request->user_res)
            $qry = $qry->where( 'user_res_id' , $request->user_res);
        if($request->start_date)
            $qry = $qry->whereDate( 'created_at' ,'>=', $request->start_date);
        if($request->end_date)
            $qry = $qry->whereDate( 'created_at' ,'<=', $request->end_date);
        if($request->lead_status)
            $qry = $qry->where( 'status' , $request->lead_status);
            $qry = $qry->get();
            return $qry;
    }
    public function getEstimateReport($request)
    {
        $qry=Estimate::with('storageunit','termLength','customer','userResponsible');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        if($request->customer_id)
            $qry = $qry->where( 'customer_id' , $request->customer_id);
        if($request->user_res)
            $qry = $qry->where( 'user_id' , $request->user_res);
        if($request->start_date)
            $qry = $qry->whereDate( 'estimate_date' ,'>=', $request->start_date);
        if($request->end_date)
            $qry = $qry->whereDate( 'estimate_date' ,'<=', $request->end_date);
        if($request->status)
            $qry = $qry->where( 'status' , $request->status);
        $qry = $qry->get();
        return $qry;
    }

    public function getContractReport($request)
    {
        $qry=Contract::with('customer','estimate.storageunit','estimate.termLength','userRensonsible');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        if($request->customer_id)
            $qry = $qry->where( 'customer_id' , $request->customer_id);
        if($request->user_res)
            $qry = $qry->where( 'user_id' , $request->user_res);
        if($request->start_date)
            $qry = $qry->whereDate( 'start_date' ,'>=', $request->start_date);
        if($request->end_date)
            $qry = $qry->whereDate( 'end_date' ,'<=', $request->end_date);
        if($request->status)
            $qry = $qry->where( 'status' , $request->status);
        $qry = $qry->get();
        return $qry;
    }

    public function getInvoiceReport($request)
    {
        $qry=Invoice::with('customer','estimate.storageunit','contract','order','userResponsible');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        if($request->customer_id)
            $qry = $qry->where( 'customer_id' , $request->customer_id);
        if($request->user_res)
            $qry = $qry->where( 'user_id' , $request->user_res);
        if($request->contract_id)
            $qry = $qry->where( 'contract_id' ,$request->contract_id);
        if($request->payment_status)
            $qry = $qry->where( 'payment_status' ,$request->payment_status);
        if($request->start_date)
            $qry = $qry->whereDate( 'invoice_date' ,'>=', $request->start_date);
        if($request->end_date)
            $qry = $qry->whereDate( 'due_date' ,'<=', $request->end_date);
        $qry = $qry->get();
        return $qry;

    }

    public function getPaymentReport($request)
    {
        $qry=Invoice::with('customer','estimate.storageunit','contract','order','userResponsible');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        if($request->customer_id)
            $qry = $qry->where( 'customer_id' , $request->customer_id);
        if($request->user_res)
            $qry = $qry->where( 'user_id' , $request->user_res);
        if($request->contract_id)
            $qry = $qry->where( 'contract_id' ,$request->contract_id);
        if($request->payment_status)
            $qry = $qry->where( 'payment_status' ,$request->payment_status);
        if($request->start_date)
            $qry = $qry->whereDate( 'invoice_date' ,'>=', $request->start_date);
        if($request->end_date)
            $qry = $qry->whereDate( 'due_date' ,'<=', $request->end_date);
        $qry = $qry->get();
        return $qry;
    }

    public function getMoveInRequestReport($request)
    {
        // TODO: Implement getMoveInRequestReport() method.
    }

    public function getMoveInReport($request)
    {
        // TODO: Implement getMoveInReport() method.
    }

    public function getMoveOutReport($request)
    {
        // TODO: Implement getMoveOutReport() method.
    }
}
