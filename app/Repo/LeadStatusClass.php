<?php
namespace App\Repo;
use App\Models\Country;
use App\Models\LeadStatus;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use Illuminate\Http\Request;

class LeadStatusClass implements LeadStatusInterface {

    public function saveLeadStatus($request)
    {
        $country=new LeadStatus();
        $country->title = $request->country_name;
        $country->description = $request->description;
        $country->status=$request->status;
        if($country->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllLeadStatus()
    {
        $qry=LeadStatus::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteLeadStatus($id)
    {
        $country=LeadStatus::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editLeadStatus($id)
    {
        return $country=LeadStatus::find($id);
    }

    public function updateLeadStatus($request)
    {
        $country=LeadStatus::find($request->id);
        $country->title=$request->edit_title;
        $country->description=$request->edit_description;
        $country->status=$request->edit_status;
        $country->save();
        return 1;
    }
}
