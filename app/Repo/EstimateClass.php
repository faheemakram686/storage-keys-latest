<?php
namespace App\Repo;
use App\Models\AddonPriceEstimate;
use App\Models\AppSettings;
use App\Models\Core\Auth\User;
use App\Models\Country;
use App\Models\Estimate;
use App\Models\EstimateStorageUnit;
use App\Models\Lead;
use App\Models\LeadModel;
use App\Models\Note;
use App\Models\StorageUnit;
use App\Notifications\Backend\EstimateApprovalNotification;
use App\Notifications\Backend\EstimateDeclineNotification;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\EstimateInterface;
use App\Repo\Interfaces\LeadInterface;
use App\Services\InsurancePricingService;
use App\Services\StorageUnitStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Auth;


class EstimateClass implements EstimateInterface {

    private $contact ;

    /** @var StorageUnitStatusService */
    protected $unitStatus;

    /** @var InsurancePricingService */
    protected $insurancePricing;

    public function __construct(?StorageUnitStatusService $unitStatus = null, ?InsurancePricingService $insurancePricing = null)
    {
        $this->unitStatus = $unitStatus ?: app(StorageUnitStatusService::class);
        $this->insurancePricing = $insurancePricing ?: app(InsurancePricingService::class);
    }

    /**
     * Normalize incoming request values to an array.
     *
     * Some request flows may send a single string (e.g. "1,2,3") instead of
     * `field[]`, or may omit the field entirely (null). This helper prevents
     * TypeErrors like:
     *   implode(): Argument #1 ($array) must be of type array, string given
     */
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

            // JSON-encoded arrays (rare, but supported).
            if (strlen($trimmed) > 0 && $trimmed[0] === '[') {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                    return array_values($decoded);
                }
            }

            // Comma-separated list like "1,2,3".
            if (strpos($trimmed, ',') !== false) {
                return array_values(array_filter(
                    array_map('trim', explode(',', $trimmed)),
                    function ($v) {
                        return $v !== '';
                    }
                ));
            }

            // Fallback: treat as a single item.
            return [$trimmed];
        }

        return [$value];
    }

    private function toCsvOrNull($value): ?string
    {
        $items = $this->toArray($value);
        return !empty($items) ? implode(',', $items) : null;
    }

    private function buildAddonPriceEstimateRows(int $estimateId, array $addonIds, array $addonPrices): array
    {
        $rows = [];
        for ($i = 0; $i < count($addonIds); $i++) {
            $addonId = $addonIds[$i] ?? null;
            if ($addonId === null || $addonId === '') {
                continue;
            }

            $rows[] = [
                'estimate_id' => $estimateId,
                'addon_id' => $addonId,
                'price' => (string)($addonPrices[$i] ?? ''),
            ];
        }

        return $rows;
    }

    private function resolveEstimateSuIds($request): array
    {
        $suIds = $this->toArray($request->su_ids ?? null);
        if (empty($suIds) && !empty($request->ssu_id)) {
            $suIds = $this->toArray($request->ssu_id);
        }
        if (empty($suIds) && !empty($request->su_id)) {
            $suIds = $this->toArray($request->su_id);
        }
        return array_values(array_unique(array_filter(array_map('intval', $suIds))));
    }

    private function resolveUnitPrices($request, array $suIds): array
    {
        $prices = $this->toArray($request->unit_prices ?? null);
        $map = [];

        // Parallel arrays: su_ids[] + unit_prices[]
        if (!empty($prices) && count($prices) === count($suIds)) {
            foreach ($suIds as $i => $suId) {
                $map[$suId] = (string)($prices[$i] ?? '');
            }
            return $map;
        }

        // Associative unit_prices[su_id] => price
        if (is_array($request->unit_prices) && !empty($request->unit_prices)) {
            foreach ($suIds as $suId) {
                if (isset($request->unit_prices[$suId])) {
                    $map[$suId] = (string)$request->unit_prices[$suId];
                }
            }
            if (!empty($map)) {
                return $map;
            }
        }

        // Fallbacks: single unit_price, or storage_units.price
        $units = StorageUnit::whereIn('id', $suIds)->get()->keyBy('id');
        foreach ($suIds as $suId) {
            if (!empty($request->unit_price) && count($suIds) === 1) {
                $map[$suId] = (string)$request->unit_price;
            } else {
                $map[$suId] = (string)(optional($units->get($suId))->price ?? $request->unit_price ?? '0');
            }
        }
        return $map;
    }

    private function syncEstimateStorageUnits(Estimate $estimate, array $suIds, array $priceMap): void
    {
        EstimateStorageUnit::where('estimate_id', $estimate->id)->delete();
        $rows = [];
        $now = now();
        foreach ($suIds as $suId) {
            $rows[] = [
                'estimate_id' => $estimate->id,
                'storage_unit_id' => $suId,
                'unit_price' => (string)($priceMap[$suId] ?? '0'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        if (!empty($rows)) {
            EstimateStorageUnit::insert($rows);
        }
        // Cache sum / primary price on estimate for legacy list views
        $sum = array_sum(array_map('floatval', array_values($priceMap)));
        $estimate->unit_price = (string)$sum;
        $estimate->save();

        $customerId = $estimate->customer_id ? (int) $estimate->customer_id : null;
        $this->unitStatus->syncSoftAssignments($estimate, $suIds, $customerId);
    }

    public function saveEstimate($request)
    {
        $estimate=new Estimate();

        $addonIds = $this->toArray($request->addon);
        $addonPrices = $this->toArray($request->addonprice);
        $requireDocIds = $this->toArray($request->require_document);

        $suIds = $this->resolveEstimateSuIds($request);

        // If no units in request, copy from lead
        if (empty($suIds) && !empty($request->lead_id)) {
            $lead = Lead::with('storageUnits')->find($request->lead_id);
            if ($lead) {
                $suIds = $lead->storageUnits->pluck('id')->toArray();
                if (empty($suIds) && $lead->su_id) {
                    $suIds = [(int)$lead->su_id];
                }
            }
        }

        if (empty($suIds)) {
            return response()->json(['errors' => 'Please select at least one storage unit.'], 422);
        }

        try {
            $this->unitStatus->assertAvailableForEstimate($suIds);
        } catch (ValidationException $e) {
            $messages = collect($e->errors())->flatten()->implode(' ');
            return response()->json(['errors' => $messages ?: 'Selected storage units are not available.'], 422);
        }

        $priceMap = $this->resolveUnitPrices($request, $suIds);
        $estimate->su_id = $suIds[0];

        $estimate->lead_id=$request->lead_id;
        $estimate->customer_id=$request->customer_id;
        $estimate->addon_id=1;
        $estimate->lead_type=$request->type;
        $estimate->r_date=$request->r_date;
        $estimate->company_name=$request->company_name;
        $estimate->f_name=$request->f_name;
        $estimate->l_name=$request->l_name;
        $estimate->email=$request->email;
        $estimate->phone=$request->phone;
        $estimate->mobile1=$request->mobile1;
        $estimate->mobile2=$request->mobile2;
        $estimate->user_id =Auth::id();
        $estimate->term_length= $request->term_length;
        $estimate->unit_price= (string)array_sum(array_map('floatval', array_values($priceMap)));
        $estimate->addon = $this->toCsvOrNull($addonIds);
        $estimate->require_documents = $this->toCsvOrNull($requireDocIds);
        $estimate->email_template =  $request->email_template;
        $leadForInsurance = !empty($request->lead_id) ? Lead::find($request->lead_id) : null;
        $this->insurancePricing->applyFromRequest($estimate, $request, $leadForInsurance);
        $estimate->status=$request->status;
        $estimate->estimate_date=$request->estimate_date;
        $estimate->expiry_date=$request->expiry_date;
        if($estimate->save()){
            $this->syncEstimateStorageUnits($estimate, $suIds, $priceMap);

            $addonpriceestimate = $this->buildAddonPriceEstimateRows($estimate->id, $addonIds, $addonPrices);

            if (!empty($addonpriceestimate)) {
                AddonPriceEstimate::insert($addonpriceestimate);
            }

            $lead = Lead::find($request->lead_id);
            if ($lead) {
                $lead->changeStatus(4);
            }

            $estimate_email = Estimate::with('emailTemplate')->find($estimate->id);
            $templateBody = optional($estimate_email->emailTemplate)->temp_body;
            if (!$templateBody) {
                return response()->json([
                    'errors' => 'Email template not found for this estimate. Please select an email template and try again.'
                ], 422);
            }

            $emailTemplate = new EmailTemplateClass();
            $templateBody = $emailTemplate->applyTemplateVariables(
                html_entity_decode($templateBody),
                $emailTemplate->buildRecipientVariables($estimate_email)
            );

            $email = [
                'greeting' => 'Hi '.$estimate_email->f_name.' '.$estimate_email->l_name.',',
                'body' => $templateBody,
                'thanks' => 'Thank you this Estimate from storage Key',
                'actionText' => 'View Estimate',
                'actionURL' => url('estimatetocustomer').'/'.hashid_encode($estimate_email->id),
                'id' => $estimate_email->id,
            ];

         Notification::route('mail', $estimate_email->email)->notify(new EstimateNotification($email));

            return response()->json(['success' => 'Estimate generated And Email Sent successfully'], 200);

        }

    }

    public function addEstimate($request)
    {
        $this->contact = new ContactClass();
        $data['contact'] = $this->contact->getCustomerPrimaryContect($request->customer_id);
        if (!$data['contact']) {
            return response()->json([
                'errors' => 'Customer primary contact not found.'
            ], 422);
        }

        $estimate=new Estimate();

        $addonIds = $this->toArray($request->addon);
        $addonPrices = $this->toArray($request->addonprice);
        $requireDocIds = $this->toArray($request->require_document);

        $suIds = $this->resolveEstimateSuIds($request);
        if (empty($suIds)) {
            return response()->json(['errors' => 'Please select at least one storage unit.'], 422);
        }
        try {
            $this->unitStatus->assertAvailableForEstimate($suIds);
        } catch (ValidationException $e) {
            $messages = collect($e->errors())->flatten()->implode(' ');
            return response()->json(['errors' => $messages ?: 'Selected storage units are not available.'], 422);
        }
        $priceMap = $this->resolveUnitPrices($request, $suIds);

        $estimate->su_id=$suIds[0];
        $estimate->customer_id=$request->customer_id;
        $estimate->user_id =Auth::id();
        $estimate->addon_id=1;
        $estimate->r_date=$request->r_date;
        $estimate->company_name=$data['contact']->customer->company_name;
        $estimate->f_name=$data['contact']->first_name;
        $estimate->l_name=$data['contact']->last_name;
        $estimate->email=$data['contact']->email;
        $estimate->phone=$data['contact']->phone;
        $estimate->term_length= $request->term_length;
        $estimate->unit_price= (string)array_sum(array_map('floatval', array_values($priceMap)));
        $estimate->addon = $this->toCsvOrNull($addonIds);
        $estimate->require_documents = $this->toCsvOrNull($requireDocIds);
        $estimate->email_template =  $request->email_template;
        $this->insurancePricing->applyFromRequest($estimate, $request);
        $estimate->status=$request->status;
        $estimate->estimate_date=$request->estimate_date;
        $estimate->expiry_date=$request->expiry_date;
        if($estimate->save()){
            $this->syncEstimateStorageUnits($estimate, $suIds, $priceMap);

            $addonpriceestimate = $this->buildAddonPriceEstimateRows($estimate->id, $addonIds, $addonPrices);

            if (!empty($addonpriceestimate)) {
                AddonPriceEstimate::insert($addonpriceestimate);
            }

            $appsettings = AppSettings::get();
            if (!AppSettings::isDirectApproval()) {
                $user = !empty($appsettings[0]->value) ? User::find($appsettings[0]->value) : null;
                if (!$user) {
                    return response()->json([
                        'errors' => 'Approval user not configured. Please configure app settings and try again.'
                    ], 422);
                }
                $template = new EmailTemplateClass();
                $notification = $template->getTemplateByName('estimate','email','Estimate_approval_email');
                if (empty($notification) || empty($notification[0]) || empty($notification[0]->temp_body)) {
                    return response()->json([
                        'errors' => 'Approval email template (Estimate_approval_email) not found. Please configure email templates and try again.'
                    ], 422);
                }
                $email2 = [
                    'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                    'body' => $template->applyTemplateVariables(
                        html_entity_decode($notification[0]->temp_body),
                        $template->buildRecipientVariables($user)
                    ),
                    'thanks' => 'Thank you this is from storage Key',
                    'actionText' => 'View Estimate',
                    'actionURL' => url('admin/estimate/detail').'/'.$estimate->id,
                    'id' => $user->id,

                ];
                Notification::send($user, new EstimateApprovalNotification($email2,$user,$estimate));
            }

            return response()->json(['success' => 'Estimate created successfully Require Approval'], 200);

        }

    }

    public function getAllEstimate()
    {
        $user = Auth::user();
        $appsettings = AppSettings::get();
        $qry=Estimate::with('storageunit','storageUnits','estimateStorageUnits.storageunit','termLength','customer');
        if(!$user->hasRole('App Admin')){
            if (!AppSettings::isDirectApproval()) {
                if($appsettings[0]->value == auth()->id()){
                    $qry=$qry->where('status', 0);
                }elseif($appsettings[1]->value == auth()->id()){
                    $qry=$qry->where('status', 1);
                }elseif($appsettings[2]->value == auth()->id()){
                    $qry=$qry->where('status', 2);
                }else{
                    $qry=$qry->where('user_id', \Illuminate\Support\Facades\Auth::user()->id);
                }
            } else {
                $qry=$qry->where('user_id', \Illuminate\Support\Facades\Auth::user()->id);
            }
        }
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteEstimate($id)
    {
        $country=Estimate::find($id);
        if (!$country) {
            return 0;
        }
        $country->is_deleted=1;
        $country->save();
        $this->unitStatus->releaseByEstimate($country, 'estimate.deleted');
        return 1;
    }

    public function editEstimate($id)
    {
        $qry = Estimate::with('storageunit.warehouse.loc.city.country','estimateStorageUnits.storageunit.warehouse.loc.city.country','storageUnits','estimateAddon.addon','requireDocument','termLength','customer');
        $qry = $qry->where( 'id' , $id );
        $qry = $qry->get();
        return $qry;
    }

    public function updateEstimate($request)
    {
        $estimate = Estimate::find($request->estimate_id);
        if (!$estimate) {
            return response()->json(['errors' => 'Estimate not found.'], 404);
        }

        $addonIds = $this->toArray($request->addon);
        $addonPrices = $this->toArray($request->addonprice);
        $requireDocIds = $this->toArray($request->require_document);

        $suIds = $this->resolveEstimateSuIds($request);
        if (empty($suIds)) {
            return response()->json(['errors' => 'Please select at least one storage unit.'], 422);
        }
        try {
            $this->unitStatus->assertAvailableForEstimate($suIds, (int) $estimate->id);
        } catch (ValidationException $e) {
            $messages = collect($e->errors())->flatten()->implode(' ');
            return response()->json(['errors' => $messages ?: 'Selected storage units are not available.'], 422);
        }
        $priceMap = $this->resolveUnitPrices($request, $suIds);

        $estimate->su_id = $suIds[0];

        $estimate->customer_id=$request->customer_id;
        $estimate->r_date=$request->r_date;
        $estimate->term_length= $request->term_length;
        $estimate->unit_price= (string)array_sum(array_map('floatval', array_values($priceMap)));
        $estimate->addon = $this->toCsvOrNull($addonIds);
        $estimate->require_documents = $this->toCsvOrNull($requireDocIds);
        $estimate->email_template =  $request->email_template;
        $this->insurancePricing->applyFromRequest($estimate, $request);
        $estimate->status=$request->status;
        $estimate->estimate_date=$request->estimate_date;
        $estimate->expiry_date=$request->expiry_date;
        
        if($estimate->save()){
            $this->syncEstimateStorageUnits($estimate, $suIds, $priceMap);

            // Update addon prices
            AddonPriceEstimate::where('estimate_id', $estimate->id)->delete();
            $addonpriceestimate = $this->buildAddonPriceEstimateRows($estimate->id, $addonIds, $addonPrices);

            if (!empty($addonpriceestimate)) {
                AddonPriceEstimate::insert($addonpriceestimate);
            }

            return response()->json(['success' => 'Estimate updated successfully'], 200);
        }

        return response()->json(['errors' => 'Failed to update estimate.'], 500);
    }

    public function getEstimate($id)
    {
        $qry = Estimate::with(
            'storageunit.warehouse.loc.city.country',
            'estimateStorageUnits.storageunit.warehouse.loc.city.country',
            'storageUnits.warehouse.loc.city.country',
            'estimateAddon.addon',
            'requireDocument',
            'termLength'
        );
        $qry = $qry->where( 'id' , $id );
        $qry = $qry->get();
        return $qry;
    }
    public  function getCustomerEstimates($id)
    {
        $qry = Estimate::with('storageunit','estimateStorageUnits.storageunit','storageUnits','termLength');
        $qry = $qry->where( 'customer_id' , $id );
        $qry = $qry->where( 'status' , 3 );
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry = $qry->get();
        return $qry;
    }




    public function getEstimateReqDocs($id)
    {
        $qry = Estimate::query();
        $qry = $qry->where( 'id' , $id );
        $qry = $qry->get();
        return $qry;
    }

    public function approveEstimate($id)
    {
        $appsettings = AppSettings::get();
        $estimate = Estimate::find($id);
        if (!$estimate) {
            return response()->json(['error' => 'Estimate not found'], 404);
        }

        if ($estimate->status == 'Approved') {
            return response()->json(['error' => 'Estimate already Approved'], 200);
        }

        if (AppSettings::isDirectApproval()) {
            if (!$this->canDirectApprove()) {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }

            $estimate->status = 3;
            $estimate->save();

            return $this->sendEstimateCustomerNotification($estimate);
        }

        if($estimate->status == 'Not Approved')
        {
            if(!empty($appsettings[0]->value) && $appsettings[0]->value == auth()->id())
            {
                $estimate->status = 1;
                if($estimate->save())
                {
                    $appsettings = AppSettings::get();
                    $user = !empty($appsettings[1]->value) ? User::find($appsettings[1]->value) : null;
                    if (!$user) {
                        return response()->json([
                            'error' => 'Next approval user is not configured in app settings.'
                        ], 422);
                    }
                    $template = new EmailTemplateClass();
                    $notification = $template->getTemplateByName('estimate','email','Estimate_approval_email');
                    if (empty($notification) || empty($notification[0]) || empty($notification[0]->temp_body)) {
                        return response()->json([
                            'error' => 'Approval email template (Estimate_approval_email) not found. Please configure email templates and try again.'
                        ], 422);
                    }
                    $email2 = [
                        'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                        'body' => $template->applyTemplateVariables(
                            html_entity_decode($notification[0]->temp_body),
                            $template->buildRecipientVariables($user)
                        ),
                        'thanks' => 'Thank you this is from storage Key',
                        'actionText' => 'View Estimate',
                        'actionURL' => url('admin/estimate/detail').'/'.$estimate->id,
                        'id' => $user->id,

                    ];
                    Notification::send($user, new EstimateApprovalNotification($email2,$user,$estimate));
                }

                return response()->json(['success' => 'Estimate Approved successfully'], 200);
            }else
            {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }

        }elseif ($estimate->status == 'Approved Level 1')
        {
            if(!empty($appsettings[1]->value) && $appsettings[1]->value == auth()->id())
            {
                $estimate->status = 2;
                if($estimate->save()) {
                    $appsettings = AppSettings::get();
                    $user = !empty($appsettings[2]->value) ? User::find($appsettings[2]->value) : null;
                    if (!$user) {
                        return response()->json([
                            'error' => 'Next approval user is not configured in app settings.'
                        ], 422);
                    }
                    $template = new EmailTemplateClass();
                    $notification = $template->getTemplateByName('estimate', 'email', 'Estimate_approval_email');
                    if (empty($notification) || empty($notification[0]) || empty($notification[0]->temp_body)) {
                        return response()->json([
                            'error' => 'Approval email template (Estimate_approval_email) not found. Please configure email templates and try again.'
                        ], 422);
                    }
                    $email2 = [
                        'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
                        'body' => $template->applyTemplateVariables(
                            html_entity_decode($notification[0]->temp_body),
                            $template->buildRecipientVariables($user)
                        ),
                        'thanks' => 'Thank you this is from storage Key',
                        'actionText' => 'View Estimate',
                        'actionURL' => url('admin/estimate/detail') . '/' . $estimate->id,
                        'id' => $user->id,

                    ];
                    Notification::send($user, new EstimateApprovalNotification($email2, $user, $estimate));
                    return response()->json(['success' => 'Estimate Approved successfully'], 200);
                }
            }else
            {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }
        }elseif ($estimate->status == 'Approved Level 2')
        {
            if(!empty($appsettings[2]->value) && $appsettings[2]->value == auth()->id())
            {
                $estimate->status = 3;
                $estimate->save();

                return $this->sendEstimateCustomerNotification($estimate);
            }else
            {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }
        }

        return response()->json(['error' => 'Unable to approve estimate'], 200);
    }

    private function canDirectApprove(): bool
    {
        $user = Auth::user();

        return $user && $user->hasRole('App Admin');
    }

    private function sendEstimateCustomerNotification(Estimate $estimate)
    {
        $estimate_email = Estimate::with('emailTemplate')->find($estimate->id);
        $templateBody = optional($estimate_email->emailTemplate)->temp_body;
        if (!$templateBody) {
            return response()->json([
                'error' => 'Email template not found for this estimate. Please select an email template and try again.'
            ], 422);
        }

        $emailTemplate = new EmailTemplateClass();
        $templateBody = $emailTemplate->applyTemplateVariables(
            html_entity_decode($templateBody),
            $emailTemplate->buildRecipientVariables($estimate_email)
        );

        $email = [
            'greeting' => 'Hi '.$estimate_email->f_name.' '.$estimate_email->l_name.',',
            'body' => $templateBody,
            'thanks' => 'Thank you this Estimate from storage Key',
            'actionText' => 'View Estimate',
            'actionURL' => url('estimatetocustomer').'/'.hashid_encode($estimate_email->id),
            'id' => $estimate_email->id,
        ];

        Notification::route('mail', $estimate_email->email)->notify(new EstimateNotification($email));

        return response()->json(['success' => 'Estimate Approved successfully'], 200);
    }

    public function declineEstimate($request)
    {
        $estimate = Estimate::find($request->id);
        if (!$estimate) {
            return response()->json(['error' => 'Estimate not found'], 404);
        }

        $note = new Note();
        $note->type = 'estimate';
        $note->type_id = $request->id;
        $note->user_id = Auth::id();
        $note->description = $request->decline_reason;
        $note->status = 1;
        if($note->save())
        {
            if($estimate->user_id)
            {
                $user = User::find($estimate->user_id);
                if (!$user) {
                    return response()->json(['success' => 'Estimate Declined successfully'], 200);
                }
                $email2 = [
                    'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
                    'body' => $request->decline_reason,
                    'thanks' => 'Thank you this is from storage Key',
                    'actionText' => 'View Estimate',
                    'actionURL' => url('admin/estimate/detail') . '/' . $estimate->id,
                    'id' => $user->id,

                ];
                Notification::send($user, new EstimateDeclineNotification($email2, $user, $estimate));
                return response()->json(['success' => 'Estimate Declined successfully'], 200);
            }

        }


    }
}
