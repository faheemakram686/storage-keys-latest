<?php
namespace App\Repo;
use App\Models\Attachment;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\LeadStatus;
use App\Models\Note;
use App\Models\Reminder;
use App\Repo\Interfaces\AttachmentInterface;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\EmailTemplateInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\NoteInterface;
use App\Repo\Interfaces\ReminderInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoteClass implements NoteInterface {

    public function saveNote($request)
    {
        $temp=new Note();
        $temp->type =$request->type;
        $temp->type_id = $request->id;
        $temp->description = $request->description;
        $temp->user_id = auth()->user()->id;
        $temp->status=1;
        if($temp->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }


    public function getAllNote()
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getNote($request)
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('temp_for','=', $request);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getRelatedNote($request)
    {
        $qry=Note::with('user');
        $qry=$qry->where('type','=', $request->type);
        $qry=$qry->where('type_id','=', $request->type_id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }



    public function deleteNote($id)
    {
        $country=Note::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editNote($id)
    {
        return $country=Note::find($id);
    }

    public function updateNote($request)
    {
        $temp = Note::find($request->id);
        $temp->description = $request->description;
        $temp->user_id = auth()->user()->id;
        $temp->status=1;
        $temp->save();
        return 1;
    }
}
