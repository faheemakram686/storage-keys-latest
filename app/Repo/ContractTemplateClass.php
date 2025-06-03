<?php
namespace App\Repo;
use App\Models\Attachment;
use App\Models\ContractTemplate;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\LeadStatus;
use App\Models\Reminder;
use App\Repo\Interfaces\AttachmentInterface;
use App\Repo\Interfaces\ContractTemplateInterface;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\EmailTemplateInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\ReminderInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractTemplateClass implements ContractTemplateInterface {

    public function saveTemplate($request)
    {
        $temp=new ContractTemplate();
        $temp->temp_title =$request->temp_title;
        $temp->temp_body = $request->temp_body;
        $temp->status=$request->status;
        if($temp->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }


    public function getAllTemplate()
    {
        $qry=ContractTemplate::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getTemplate($request)
    {
        $qry=ContractTemplate::query();
        $qry=$qry->where('id','=', $request);
        $qry=$qry->get();
        return $qry;
    }
    public function getRelatedTemplate($request)
    {
        $qry=ContractTemplate::query();
        $qry=$qry->where('type','=', $request->type);
        $qry=$qry->where('type_id','=', $request->type_id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }



    public function deleteTemplate($id)
    {
        $country=ContractTemplate::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editTemplate($id)
    {
        return $country=ContractTemplate::find($id);
    }

    public function updateTemplate($request)
    {

        $temp = ContractTemplate::find($request->id);
        $temp->temp_title =$request->temp_title;
        $temp->temp_body = $request->temp_body;
        $temp->status=$request->status;
        $temp->save();
        return 1;
    }
}
