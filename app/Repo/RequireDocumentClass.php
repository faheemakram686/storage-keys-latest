<?php
namespace App\Repo;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\RequireDocument;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadSourceInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\RequireDocumentInterface;
use Illuminate\Http\Request;

class RequireDocumentClass implements RequireDocumentInterface {

    public function saveRequireDocument($request)
    {
        $country=new RequireDocument();
        $country->title = $request->country_name;
        $country->description = $request->description;
        $country->status=$request->status;
        if($country->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllRequireDocument()
    {
        $qry=RequireDocument::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','ASC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteRequireDocument($id)
    {
        $country=RequireDocument::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editRequireDocument($id)
    {
        return $country=RequireDocument::find($id);
    }
    public function getRequireDocument($id)
    {
        return $country=RequireDocument::find($id);
    }

    public function updateRequireDocument($request)
    {
        $country=RequireDocument::find($request->id);
        $country->title=$request->edit_title;
        $country->description=$request->edit_description;
        $country->status=$request->edit_status;
        $country->save();
        return 1;
    }










}
