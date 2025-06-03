<?php
namespace App\Repo;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\TermLength;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadSourceInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\TermLengthInterface;
use Illuminate\Http\Request;

class TermLengthClass implements TermLengthInterface {

    public function saveTermLength($request)
    {
        $term=new TermLength();
        $term->title = $request->title;
        $term->term_period = $request->term_period;
        $term->discount_percentage = $request->discount_percentage;
        $term->description = $request->description;
        $term->status=$request->status;
        if($term->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllTermLength()
    {
        $qry=TermLength::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','ASC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteTermLength($id)
    {
        $country=TermLength::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editTermLength($id)
    {
        return $country=TermLength::find($id);
    }

    public function updateTermLength($request)
    {
        $term=TermLength::find($request->id);
        $term->title = $request->edit_title;
        $term->term_period = $request->edit_term_period;
        $term->discount_percentage = $request->edit_discount_percentage;
        $term->description = $request->edit_description;
        $term->status=$request->edit_status;
        $term->save();
        return 1;
    }
}
