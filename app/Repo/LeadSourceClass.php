<?php
namespace App\Repo;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadSourceInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use Illuminate\Http\Request;

class LeadSourceClass implements LeadSourceInterface {

    public function saveLeadSource($request)
    {
        $country=new LeadSource();
        $country->title = $request->country_name;
        $country->description = $request->description;
        $country->status=$request->status;
        if($country->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllLeadSource()
    {
        $qry=LeadSource::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteLeadSource($id)
    {
        $country=LeadSource::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editLeadSource($id)
    {
        return $country=LeadSource::find($id);
    }

    public function updateLeadSource($request)
    {
        $country=LeadSource::find($request->id);
        $country->title=$request->edit_title;
        $country->description=$request->edit_description;
        $country->status=$request->edit_status;
        $country->save();
        return 1;
    }
}
