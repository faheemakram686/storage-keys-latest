<?php
namespace App\Repo;
use App\Helpers\ResponseClass;
use App\Models\AppSettings;
use App\Models\Core\Auth\User;
use App\Models\Country;
use App\Models\Estimate;
use App\Models\Lead;
use App\Models\LeadModel;
use App\Notifications\Backend\AssignLeadNotification;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\Backend\LeadNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LeadClass implements LeadInterface {

    private function toArray($value): array
    {
        if ($value === null) {
            return [];
        }

        if (is_array($value)) {
            return array_values($value);
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return [];
            }

            if (strlen($trimmed) > 0 && $trimmed[0] === '[') {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                    return array_values($decoded);
                }
            }

            if (strpos($trimmed, ',') !== false) {
                return array_values(array_filter(
                    array_map('trim', explode(',', $trimmed)),
                    function ($v) { return $v !== ''; }
                ));
            }

            return [$trimmed];
        }

        return [$value];
    }

    private function toCsvOrNull($value): ?string
    {
        $items = $this->toArray($value);
        return !empty($items) ? implode(',', $items) : null;
    }

    private function applyLeadAttributes(Lead $lead, $request, bool $isUpdate = false): void
    {
        if ($isUpdate) {
            if ($request->su_id) {
                $lead->su_id = $request->su_id;
            } else {
                $lead->su_id = $request->u_su_id;
            }
        } else {
            $lead->su_id = $request->id;
        }

        if (!empty($request->customer_id)) {
            $lead->customer_id = $request->customer_id;
        }

        $lead->lead_type = $request->type;
        $lead->r_date = $request->r_date;
        $lead->company_name = $request->company_name;
        $lead->f_name = $request->f_name;
        $lead->l_name = $request->l_name;
        $lead->lead_source = $request->lead_source ?? $lead->lead_source ?? 1;
        $lead->lead_rating = $request->lead_rating ?? $lead->lead_rating ?? 0;
        $lead->user_res_id = $request->user_res ?? $lead->user_res_id;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->mobile1 = $request->mobile1;
        $lead->mobile2 = $request->mobile2 ?? "";
        $lead->price = $request->term_length ?? $request->price;
        $lead->addon = $this->toCsvOrNull($request->addon);
        $lead->insurence = $request->insurance;
        $lead->goods = $request->insurance === 'cover' ? $request->goodsval : null;
        $lead->term = $request->terms;

        if (!$isUpdate && empty($lead->status)) {
            $lead->status = 1;
        }
    }

    public function saveLead($request)
    {
        try {
            $assignUser = AppSettings::get();

//            if (empty($assignUser[6]->value)) {
//                return ResponseClass::error('Assign user not found.',500);
//            }

            $userarray = [];
            if (!empty($assignUser[6]->value)) $userarray[] = $assignUser[6]->value;
            if (!empty($assignUser[7]->value)) $userarray[] = $assignUser[7]->value;
            if (!empty($assignUser[8]->value)) $userarray[] = $assignUser[8]->value;

            DB::beginTransaction();

            $lead = new Lead();
            $lead->lead_source = 1;
            $lead->lead_rating = 0;
            $lead->user_res_id = !empty($assignUser[6]->value) ? $assignUser[6]->value : 1;
            $this->applyLeadAttributes($lead, $request);

            $lead->save();

            // Notify lead
            $email1 = [
                'greeting' => 'Hi ' . $lead->f_name . ' ' . $lead->l_name . ',',
                'body' => "Thanks for selecting Storage Keys. Our team will contact you soon.",
                'thanks' => 'Thank you from Storage Keys',
                'actionText' => 'Visit Storage Keys',
                'actionURL' => url('/'),
                'id' => $lead->id,
            ];
            Notification::route('mail', $lead->email)->notify(new LeadNotification($email1));

            // Notify assigned users
            foreach ($userarray as $userid) {
                $user = User::find($userid);
                if (!$user) {
                    continue;
                }
                $email2 = [
                    'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
                    'body' => "You have received a new lead. Please respond as soon as possible.",
                    'thanks' => 'Thank you from Storage Keys',
                    'actionText' => 'View Lead',
                    'actionURL' => url('admin/lead/profile/' . $lead->id),
                    'id' => $user->id,
                ];
                Notification::send($user, new EmailNotification($email2, $lead));
            }

            DB::commit();
            return ResponseClass::success($lead, 'Lead saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseClass::error($e->getMessage(), 500);
        }
    }

    public function getAllLead()
    {
        $user = Auth::user();
        $qry=Lead::with('storageunit','userresponsible','leadStatus','termLength');
        if(!$user->hasRole('App Admin')){
            $qry=$qry->where('user_res_id',Auth::user()->id);
        }
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteLead($id)
    {
        $country=Lead::find($id);
        if (!$country) {
            return 0;
        }
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editLead($id)
    {
        return $lead = Lead::find($id);
    }

    public function updateLead($request)
    {
        $lead=Lead::find($request->lead_id);
        if (!$lead) {
            return 0;
        }
        $this->applyLeadAttributes($lead, $request, true);
        $lead->status = $request->lead_status;
        $lead->save();
        return 1;
    }
    public  function getLead($id)
    {
        $qry = Lead::with('storageunit','userresponsible','leadStatus','leadSource','termLength');
        $qry = $qry->where( 'id' , $id );
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry = $qry->get();
        return $qry;
    }
    public  function getCustomerLeads($id)
    {
        $qry = Lead::with('storageunit','userresponsible','leadStatus','leadSource','termLength');
        $qry = $qry->where( 'customer_id' , $id );
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry = $qry->get();
        return $qry;
    }

    public function saveLeadBackend($request)
    {
        $lead=new Lead();
        $this->applyLeadAttributes($lead, $request);
        $lead->status=$request->lead_status;
        if($lead->save()){


            $email1 = [

                'greeting' => 'Hi '.$lead->f_name.' '.$lead->l_name.',',
                'body' => "Thanks for select Storage Keys Our team will contact you soon.",
                'thanks' => 'Thank you this is from storage Keys',
                'actionText' => 'Visit Storage Keys',
                'actionURL' => url('/'),
                'id' => $lead->id,


            ];

            Notification::route('mail', $lead->email)->notify(new LeadNotification($email1));

            $user = User::find($lead->user_res_id);
            if ($user) {

                $email2 = [
                    'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                    'body' => "You have received a lead respond the lead as soon as possible",
                    'thanks' => 'Thank you this is from storage Key',
                    'actionText' => 'View Lead',
                    'actionURL' => url('admin/lead/profile').'/'.$lead->id,
                    'id' => $user->id,

                ];
                Notification::send($user, new EmailNotification($email2,$lead));
            }



            return response()->json(['success' => 'Lead generated successfully'], 200);
        }
    }

    public function changeStatus($request)
    {

        $lead=Lead::find($request->lead_id);
        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }
        $lead->status=$request->lead_status;
        if($lead->save()){
            return response()->json(['success' => 'Lead status change successfully'], 200);
        }
    }

    public function changeSource($request)
    {
        $lead=Lead::find($request->lead_id);
        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }
        $lead->lead_source=$request->lead_source;
        if($lead->save()){
            return response()->json(['success' => 'Lead source change successfully'], 200);
        }
    }

    public function changeAssignee($request)
    {
        $lead=Lead::find($request->lead_id);
        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }
        $lead->user_res_id=$request->lead_assignee;
        if($lead->save()){
            $user = User::find($request->lead_assignee);
            if (!$user) {
                return response()->json(['success' => 'Lead user Responsible change successfully'], 200);
            }
            $email2 = [
                'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                'body' => "You have received a lead respond the lead as soon as possible",
                'thanks' => 'Thank you this is from storage Key',
                'actionText' => 'View Lead',
                'actionURL' => url('admin/lead/profile').'/'.$lead->id,
                'id' => $user->id,

            ];
            Notification::send($user, new AssignLeadNotification($email2,$user,$lead));

            return response()->json(['success' => 'Lead user Responsible change successfully'], 200);
        }
    }
}
