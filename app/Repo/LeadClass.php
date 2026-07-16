<?php
namespace App\Repo;
use App\Helpers\ResponseClass;
use App\Models\AppSettings;
use App\Models\Core\Auth\User;
use App\Models\Country;
use App\Models\Estimate;
use App\Models\Lead;
use App\Models\LeadModel;
use App\Models\Note;
use App\Notifications\Backend\AssignLeadNotification;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\Backend\LeadNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\LeadInterface;
use App\Services\InsurancePricingService;
use App\Services\StorageUnitStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class LeadClass implements LeadInterface {

    /** @var StorageUnitStatusService */
    protected $unitStatus;

    /** @var InsurancePricingService */
    protected $insurancePricing;

    public function __construct(?StorageUnitStatusService $unitStatus = null, ?InsurancePricingService $insurancePricing = null)
    {
        $this->unitStatus = $unitStatus ?: app(StorageUnitStatusService::class);
        $this->insurancePricing = $insurancePricing ?: app(InsurancePricingService::class);
    }

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

    /**
     * Resolve selected storage unit IDs from request (su_ids[], su_id, or legacy id).
     */
    private function resolveSuIds($request): array
    {
        $suIds = $this->toArray($request->su_ids ?? null);

        if (empty($suIds) && !empty($request->su_id)) {
            $suIds = $this->toArray($request->su_id);
        }

        if (empty($suIds) && !empty($request->id)) {
            $suIds = $this->toArray($request->id);
        }

        if (empty($suIds) && !empty($request->u_su_id)) {
            $suIds = $this->toArray($request->u_su_id);
        }

        $suIds = array_values(array_unique(array_filter(array_map('intval', $suIds))));

        return $suIds;
    }

    private function syncLeadStorageUnits(Lead $lead, array $suIds): void
    {
        $lead->storageUnits()->sync($suIds);
        $this->unitStatus->syncSoftAssignments($lead, $suIds, null);
    }

    private function applyLeadAttributes(Lead $lead, $request, bool $isUpdate = false): void
    {
        $suIds = $this->resolveSuIds($request);

        if (!empty($suIds)) {
            $lead->su_id = $suIds[0];
        } elseif ($isUpdate && $request->u_su_id) {
            $lead->su_id = $request->u_su_id;
        } elseif (!$isUpdate && $request->id) {
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
        $this->insurancePricing->applyFromRequest($lead, $request);
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
            $suIds = $this->resolveSuIds($request);
            if (empty($suIds)) {
                DB::rollBack();
                return ResponseClass::error('Please select at least one storage unit.', 422);
            }

            try {
                $this->unitStatus->assertAvailableForLead($suIds);
            } catch (ValidationException $e) {
                DB::rollBack();
                $messages = collect($e->errors())->flatten()->implode(' ');
                return ResponseClass::error($messages ?: 'Selected storage units are not available.', 422);
            }

            $this->applyLeadAttributes($lead, $request);
            $lead->approval_status = 0;

            $lead->save();
            $this->syncLeadStorageUnits($lead, $suIds);

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

            // Notify assigned/approval users (skip multi-level notify in direct mode)
            if (!AppSettings::isDirectApproval()) {
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
        $appsettings = AppSettings::query()->orderBy('id')->get();
        $qry=Lead::with('storageunit','storageUnits','userresponsible','leadStatus','termLength');
        if(!$user->hasRole('App Admin')){
            if (!AppSettings::isDirectApproval()) {
                if (!empty($appsettings[6]->value) && $appsettings[6]->value == auth()->id()) {
                    $qry = $qry->where('approval_status', 0);
                } elseif (!empty($appsettings[7]->value) && $appsettings[7]->value == auth()->id()) {
                    $qry = $qry->where('approval_status', 1);
                } elseif (!empty($appsettings[8]->value) && $appsettings[8]->value == auth()->id()) {
                    $qry = $qry->where('approval_status', 2);
                } else {
                    $qry = $qry->where('user_res_id', Auth::user()->id);
                }
            } else {
                $qry = $qry->where('user_res_id', Auth::user()->id);
            }
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
        $this->unitStatus->releaseByLead($country, 'lead.deleted');
        return 1;
    }

    public function editLead($id)
    {
        return Lead::with('storageUnits', 'storageunit')->find($id);
    }

    private function resolveLeadId($request): ?int
    {
        $raw = $request->lead_id ?? $request->id ?? null;
        if ($raw === null || $raw === '') {
            return null;
        }

        if (is_numeric($raw)) {
            return (int) $raw;
        }

        return hashid_decode((string) $raw);
    }

    private function existingLeadStorageUnitIds(Lead $lead): array
    {
        $lead->loadMissing('storageUnits');
        $suIds = $lead->storageUnits->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();

        if (empty($suIds) && $lead->su_id) {
            $suIds = [(int) $lead->su_id];
        }

        return array_values(array_unique($suIds));
    }

    public function updateLead($request)
    {
        $leadId = $this->resolveLeadId($request);
        if (!$leadId) {
            return 0;
        }

        $lead = Lead::find($leadId);
        if (!$lead) {
            return 0;
        }

        $suIds = $this->resolveSuIds($request);
        if (empty($suIds)) {
            $suIds = $this->existingLeadStorageUnitIds($lead);
        }
        if (empty($suIds)) {
            return ['error' => 'Please select at least one storage unit.'];
        }

        $currentSuIds = $this->existingLeadStorageUnitIds($lead);
        $newSuIds = array_values(array_diff($suIds, $currentSuIds));

        if (!empty($newSuIds)) {
            try {
                $this->unitStatus->assertAvailableForLead($newSuIds);
            } catch (ValidationException $e) {
                $messages = collect($e->errors())->flatten()->implode(' ');

                return ['error' => $messages ?: 'Selected storage units are not available.'];
            }
        }

        $this->applyLeadAttributes($lead, $request, true);
        $lead->status = $request->lead_status;
        $lead->save();
        $this->syncLeadStorageUnits($lead, $suIds);

        return 1;
    }
    public  function getLead($id)
    {
        $qry = Lead::with('storageunit','storageUnits.warehouse','userresponsible','leadStatus','leadSource','termLength');
        $qry = $qry->where( 'id' , $id );
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry = $qry->get();
        return $qry;
    }
    public  function getCustomerLeads($id)
    {
        $qry = Lead::with('storageunit','storageUnits','userresponsible','leadStatus','leadSource','termLength');
        $qry = $qry->where( 'customer_id' , $id );
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry = $qry->get();
        return $qry;
    }

    public function saveLeadBackend($request)
    {
        $suIds = $this->resolveSuIds($request);
        if (empty($suIds)) {
            return response()->json(['error' => 'Please select at least one storage unit.'], 422);
        }

        try {
            $this->unitStatus->assertAvailableForLead($suIds);
        } catch (ValidationException $e) {
            $messages = collect($e->errors())->flatten()->implode(' ');
            return response()->json(['error' => $messages ?: 'Selected storage units are not available.'], 422);
        }

        $lead=new Lead();
        $this->applyLeadAttributes($lead, $request);
        $lead->status=$request->lead_status;
        $lead->approval_status = 0;
        if($lead->save()){
            $this->syncLeadStorageUnits($lead, $suIds);

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

        $leadId = $this->resolveLeadId($request);
        $lead = $leadId ? Lead::find($leadId) : null;
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
        $leadId = $this->resolveLeadId($request);
        $lead = $leadId ? Lead::find($leadId) : null;
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
        $leadId = $this->resolveLeadId($request);
        $lead = $leadId ? Lead::find($leadId) : null;
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

    public function approveLead($id)
    {
        $appsettings = AppSettings::query()->orderBy('id')->get();
        $lead = Lead::find($id);
        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        if ($lead->approval_status == 'Approved') {
            return response()->json(['error' => 'Lead already Approved'], 200);
        }

        if (AppSettings::isDirectApproval()) {
            $user = Auth::user();
            if (!$user || !$user->hasRole('App Admin')) {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }
            $lead->approval_status = 3;
            $lead->save();
            return response()->json(['success' => 'Lead Approved successfully'], 200);
        }

        if ($lead->approval_status == 'Not Approved') {
            if (!empty($appsettings[6]->value) && $appsettings[6]->value == auth()->id()) {
                $lead->approval_status = 1;
                if ($lead->save()) {
                    $this->notifyLeadApprover($appsettings[7]->value ?? null, $lead);
                }
                return response()->json(['success' => 'Lead Approved successfully'], 200);
            }
            return response()->json(['error' => 'You are not allowed to approve'], 200);
        }

        if ($lead->approval_status == 'Approved Level 1') {
            if (!empty($appsettings[7]->value) && $appsettings[7]->value == auth()->id()) {
                $lead->approval_status = 2;
                if ($lead->save()) {
                    $this->notifyLeadApprover($appsettings[8]->value ?? null, $lead);
                }
                return response()->json(['success' => 'Lead Approved successfully'], 200);
            }
            return response()->json(['error' => 'You are not allowed to approve'], 200);
        }

        if ($lead->approval_status == 'Approved Level 2') {
            if (!empty($appsettings[8]->value) && $appsettings[8]->value == auth()->id()) {
                $lead->approval_status = 3;
                $lead->save();
                return response()->json(['success' => 'Lead Approved successfully'], 200);
            }
            return response()->json(['error' => 'You are not allowed to approve'], 200);
        }

        return response()->json(['error' => 'Unable to approve lead'], 200);
    }

    public function declineLead($request)
    {
        $lead = Lead::find($request->id);
        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        $note = new Note();
        $note->type = 'lead';
        $note->type_id = $request->id;
        $note->user_id = Auth::id();
        $note->description = $request->decline_reason;
        $note->status = 1;
        if ($note->save() && $lead->user_res_id) {
            $user = User::find($lead->user_res_id);
            if ($user) {
                $email2 = [
                    'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
                    'body' => $request->decline_reason,
                    'thanks' => 'Thank you this is from storage Key',
                    'actionText' => 'View Lead',
                    'actionURL' => url('admin/lead/profile/' . $lead->id),
                    'id' => $user->id,
                ];
                Notification::send($user, new EmailNotification($email2, $lead));
            }
        }

        return response()->json(['success' => 'Lead Declined successfully'], 200);
    }

    private function notifyLeadApprover($userId, Lead $lead): void
    {
        if (empty($userId)) {
            return;
        }
        $user = User::find($userId);
        if (!$user) {
            return;
        }
        $email2 = [
            'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
            'body' => 'A lead requires your approval. Please review as soon as possible.',
            'thanks' => 'Thank you from Storage Keys',
            'actionText' => 'View Lead',
            'actionURL' => url('admin/lead/profile/' . $lead->id),
            'id' => $user->id,
        ];
        Notification::send($user, new EmailNotification($email2, $lead));
    }
}
