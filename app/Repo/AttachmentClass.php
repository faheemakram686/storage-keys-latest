<?php
namespace App\Repo;
use App\Models\Attachment;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\LeadStatus;
use App\Repo\Interfaces\AttachmentInterface;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\EmailTemplateInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttachmentClass implements AttachmentInterface {

    public function saveAttachment($request)
    {
        $temp=new Attachment();
        $temp->name = $request['name'];
        $temp->type =$request['type'];
        $temp->type_id = $request['type_id'];
        $temp->req_doc_id = $request['req_doc_id'];
        $temp->path = $request['path'];
        $temp->status=$request['status'];
        if($temp->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }
    public function saveCommonAttachment($request)
    {
        if ($request->hasFile('files')) {
            $images = $request->file('files');
                if ($images->isValid()) {
                    $uniqueid = uniqid();
                    $original_name = $images->getClientOriginalName();
                    $size = $images->getSize();
                    $extension = $images->getClientOriginalExtension();
                    $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
                    $path = $images->storeAs('public/files', $name);

                    $temp=new Attachment();
                    $temp->name = $name;
                    $temp->type = $request->type;
                    $temp->type_id = $request->estimate_id;
                    $temp->req_doc_id = 3;
                    $temp->path = $path;
                    $temp->status= 1;;
                }

            if($temp->save()){
            return response()->json(['success' => 'Documents uploaded successfully'], 200);
            }
        }

        return response()->json(['success' => 'No images were uploaded.'], 200);


    }

    public function getAllAttachment()
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getAttachment($request)
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('temp_for','=', $request);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getRelatedAttachment($request)
    {
        $qry=Attachment::with('requireDocument');
        $qry=$qry->where('type','=', $request->type);
        $qry=$qry->where('type_id','=', $request->type_id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }



    public function deleteAttachment($id)
    {
        $country=EmailTemplate::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editAttachment($id)
    {
        return $country=EmailTemplate::find($id);
    }

    public function updateAttachment($request)
    {

        $temp = EmailTemplate::find($request->id);
        $temp->temp_name = $request->et_temp_name;
        $temp->temp_for = $request->et_temp_for;
        $temp->temp_cat = $request->et_temp_category;
        $temp->temp_subject = $request->et_temp_subject;
        $temp->temp_body = $request->et_temp_body;
        $temp->status=$request->edit_status;
        $temp->save();
        return 1;
    }
}
