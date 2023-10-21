<?php
namespace App\Repo;
use App\Models\Attachment;
use App\Models\Core\Auth\User;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\LeadStatus;
use App\Models\Reminder;
use App\Notifications\Backend\ReminderNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\AttachmentInterface;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\EmailTemplateInterface;
use App\Repo\Interfaces\LeadStatusInterface;
use App\Repo\Interfaces\ReminderInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ReminderClass implements ReminderInterface {

    public function saveReminder($request)
    {
        $temp=new Reminder();
        $temp->type =$request->type;
        $temp->type_id = $request->type_id;
        $temp->description = $request->description;
        $temp->reminder_date = $request->reminder_date;
        $temp->reminder_to = $request->reminder_to;
        $temp->user_id = auth()->user()->id;
        $temp->status=$request->status;
        if($temp->save()){
            $user = User::find($request->reminder_to);
            $email2 = [
                'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                'body' => "You have received a  reminder",
                'thanks' => 'Thank you this is from storage Key',
                'actionText' => 'View Reminder',
                'actionURL' => url('admin/'.$request->type.'/reminders').'/'.$request->type_id,
            ];
            Notification::send($user, new ReminderNotification($email2,$user));
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }


    public function getAllReminder()
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getReminder($request)
    {
        $qry=EmailTemplate::Query();
        $qry=$qry->where('temp_for','=', $request);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getRelatedReminder($request)
    {
        $qry=Reminder::with('remind');
        $qry=$qry->where('type','=', $request->type);
        $qry=$qry->where('type_id','=', $request->type_id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }



    public function deleteReminder($id)
    {
        $country=Reminder::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editReminder($id)
    {
        return $country=Reminder::find($id);
    }

    public function updateReminder($request)
    {

        $temp = Reminder::find($request->id);
        $temp->description = $request->description;
        $temp->reminder_date = $request->reminder_date;
        $temp->reminder_to = $request->reminder_to;
        $temp->user_id = auth()->user()->id;
        $temp->status=$request->edit_status;
        $temp->save();
        return 1;
    }
}
