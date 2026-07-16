<?php
namespace App\Repo;
use App\Models\AppSettings;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Core\Auth\User;
use App\Models\Country;
use App\Models\Estimate;
use App\Models\MoveIn;
use App\Models\MoveOut;
use App\Models\Note;
use App\Notifications\Backend\ContractApprovalNotification;
use App\Notifications\Backend\ContractDeclineNotification;
use App\Notifications\Backend\EstimateApprovalNotification;
use App\Notifications\Backend\EstimateDeclineNotification;
use App\Repo\Interfaces\ContactInterface;

use App\Repo\Interfaces\ContractInterface;
use App\Services\InsurancePricingService;
use App\Services\StorageUnitStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Auth;
use Illuminate\Support\Facades\Storage;

class ContractClass implements ContractInterface {

    /** @var StorageUnitStatusService */
    protected $unitStatus;

    /** @var InsurancePricingService */
    protected $insurancePricing;

    public function __construct(?StorageUnitStatusService $unitStatus = null, ?InsurancePricingService $insurancePricing = null)
    {
        $this->unitStatus = $unitStatus ?: app(StorageUnitStatusService::class);
        $this->insurancePricing = $insurancePricing ?: app(InsurancePricingService::class);
    }

    public function saveContract($request)
    {
        try {
            $estimate = Estimate::with('estimateStorageUnits')->find($request->estimate_id);
            if (!$estimate) {
                return response()->json(['error' => 'Estimate not found.'], 422);
            }

            $unitRows = [];
            foreach ($estimate->estimateStorageUnits as $row) {
                $unitRows[] = [
                    'storage_unit_id' => (int) $row->storage_unit_id,
                    'unit_price' => $row->unit_price,
                ];
            }

            if (empty($unitRows) && $estimate->su_id) {
                $unitRows[] = [
                    'storage_unit_id' => (int) $estimate->su_id,
                    'unit_price' => $estimate->unit_price,
                ];
            }

            $unitIds = array_column($unitRows, 'storage_unit_id');
            $this->unitStatus->assertAvailableForContract($unitIds);

            DB::beginTransaction();

            $contract = new Contract();
            $contract->customer_id = $request->customer_id;
            $contract->estimate_id = $request->estimate_id;
            $contract->user_id = Auth::id();
            $contract->subject = $request->subject;
            $contract->contract_value = $request->contract_value;
            $this->insurancePricing->applySnapshot(
                $contract,
                $this->insurancePricing->snapshotFromModel($estimate),
                false
            );
            $contract->contract_type = $request->contract_type;
            $contract->start_date = $request->s_date;
            $contract->end_date = $request->e_date;
            $contract->description = $request->description;
            $contract->is_accepted = 0;
            $contract->status = 0;
            $contract->save();

            $this->unitStatus->hardHoldContract($contract, $unitRows);

            DB::commit();

            if (!AppSettings::isDirectApproval()) {
                $appsettings = AppSettings::get();
                $user = !empty($appsettings[3]->value) ? User::find($appsettings[3]->value) : null;
                if ($user) {
                    $template = new EmailTemplateClass();
                    $notification = $template->getTemplateByName('contract','email','Contract_approval_email');
                    if (!empty($notification[0]->temp_body)) {
                        $email2 = [
                            'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                            'body' => $template->applyTemplateVariables(
                                html_entity_decode($notification[0]->temp_body),
                                $template->buildRecipientVariables($user)
                            ),
                            'thanks' => 'Thank you this is from storage Key',
                            'actionText' => 'View Contract',
                            'actionURL' => url('admin/contract/detail').'/'.$contract->id,
                            'id' => $user->id,

                        ];
                        Notification::send($user, new ContractApprovalNotification($email2,$user,$contract));
                    }
                }
            }

            return response()->json(['success' => 'Record save successfully'], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            $messages = collect($e->errors())->flatten()->all();
            return response()->json([
                'error' => implode(' ', $messages),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllContract()
    {
        $qry=Contract::with('customer','estimate.storageunit','estimate.estimateStorageUnits.storageunit','contractStorageUnits.storageunit','estimate.termLength');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteContract($id)
    {
        $contract = Contract::find($id);
        if (!$contract) {
            return 0;
        }

        $hasMoveIn = MoveIn::query()
            ->where('contract_id', $contract->id)
            ->where('is_deleted', 0)
            ->exists();
        $hasMoveOut = MoveOut::query()
            ->where('contract_id', $contract->id)
            ->where('is_deleted', 0)
            ->exists();

        if ($hasMoveIn && !$hasMoveOut) {
            return response()->json([
                'error' => 'Cannot delete contract while units are occupied. Complete move-out first.',
            ], 422);
        }

        $contract->is_deleted = 1;
        $contract->save();
        $this->unitStatus->releaseByContract($contract, 'contract.deleted');

        return 1;
    }

    public function editContract($id)
    {
        $qry=Contract::query();
        $qry=$qry->where('id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }
    public function getContract($id)
    {

        $qry=Contract::with('contractTemplate','estimate.storageunit.storagesize.mUnit','estimate.estimateStorageUnits.storageunit','contractStorageUnits.storageunit','estimate.termLength','estimate.estimateAddon.addon','customer');
        $qry=$qry->where('id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }


    public function signContact($request)
    {
        $image_parts = explode(";base64,", $request->signed);

        if (count($image_parts) === 2) {
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'png'; // default to png if missing
            $image_base64 = base64_decode($image_parts[1]);

            $image_name = uniqid() . '.' . $image_type;
            $path = 'public/uploads/contract_sign_images/' . $image_name;

            // Save using Laravel's Storage (recommended over file_put_contents)
            Storage::put($path, $image_base64);

            $contract = Contract::find($request->id);

            if ($contract) {
                $contract->sign_image = $image_name;
                $contract->is_signed = 1;
                $contract->save();
                return 1;
            }

        }

    }

    public function updateContract($request)
    {
        $contract=Contract::find($request->id);
        $contract->customer_id = $request->customer_id;
        $contract->estimate_id = $request->estimate_id;
        $contract->subject = $request->subject;
        $contract->contract_value = $request->contract_value;
        if ($request->estimate_id) {
            $estimate = Estimate::find($request->estimate_id);
            if ($estimate) {
                $this->insurancePricing->applySnapshot(
                    $contract,
                    $this->insurancePricing->snapshotFromModel($estimate),
                    false
                );
            }
        }
        $contract->contract_type = $request->contract_type;
        $contract->start_date = $request->s_date;
        $contract->end_date = $request->e_date;
        $contract->description = $request->description;
        $contract->status=$request->status;
        $contract->save();
        return 1;
    }

    public function updateContractId($request)
    {
        $contract=Contract::find($request->id);
        $contract->template_id = $request->contract_temp_id;
        $contract->save();
        return 1;
    }


    public function getCustomerContracts($request)
    {
        $qry=Contract::with('estimate.storageunit','estimate.estimateStorageUnits.storageunit','estimate.termLength');
        $qry=$qry->where('customer_id',$request);
//        $qry = $qry->where( 'status' , 1 );
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;

    }
    public function getCustomerContractsApi($request)
    {


        try {
            $qry=Contract::with('estimate.storageunit','estimate.estimateStorageUnits.storageunit','estimate.storageUnits','estimate.termLength');
            $qry=$qry->where('customer_id',$request);
            $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
            $qry=$qry->get();

            return response()->json([
                'Contracts' => $qry,
                'status' => true,
                'message' => 'Customer Contracts',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }


    }


    public function approveContract($id)
    {
        $appsettings = AppSettings::get();
        $estimate = Contract::find($id);
        if (!$estimate) {
            return response()->json(['error' => 'Contract not found'], 404);
        }

        if ($estimate->status == 'Approved') {
            return response()->json(['error' => 'Estimate already Approved'], 200);
        }

        if (AppSettings::isDirectApproval()) {
            $user = Auth::user();
            if (!$user || !$user->hasRole('App Admin')) {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }
            $estimate->status = 3;
            $estimate->save();
            return response()->json(['success' => 'Estimate Approved successfully'], 200);
        }

        if($estimate->status == 'Not Approved')
        {
            if($appsettings[3]->value == auth()->id())
            {
                $estimate->status = 1;
                if($estimate->save())
                {
                    $appsettings = AppSettings::get();
                    $user = User::find($appsettings[4]->value);
                    $template = new EmailTemplateClass();
                    $notification = $template->getTemplateByName('contract','email','Contract_approval_email');
                    $email2 = [
                        'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                        'body' => $template->applyTemplateVariables(
                            html_entity_decode($notification[0]->temp_body),
                            $template->buildRecipientVariables($user)
                        ),
                        'thanks' => 'Thank you this is from storage Key',
                        'actionText' => 'View Contract',
                        'actionURL' => url('admin/contract/detail').'/'.$estimate->id,
                        'id' => $user->id,

                    ];
                    Notification::send($user, new ContractApprovalNotification($email2,$user,$estimate));
                }

                return response()->json(['success' => 'Estimate Approved successfully'], 200);
            }else
            {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }

        }elseif ($estimate->status == 'Approved Level 1')
        {
            if($appsettings[4]->value == auth()->id())
            {
                $estimate->status = 2;
                if($estimate->save()) {
                    $appsettings = AppSettings::get();
                    $user = User::find($appsettings[5]->value);
                    $template = new EmailTemplateClass();
                    $notification = $template->getTemplateByName('contract','email','Contract_approval_email');
                    $email2 = [
                        'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
                        'body' => $template->applyTemplateVariables(
                            html_entity_decode($notification[0]->temp_body),
                            $template->buildRecipientVariables($user)
                        ),
                        'thanks' => 'Thank you this is from storage Key',
                        'actionText' => 'View Contract',
                        'actionURL' => url('admin/contract/detail') . '/' . $estimate->id,
                        'id' => $user->id,

                    ];
                    Notification::send($user, new ContractApprovalNotification($email2, $user, $estimate));
                    return response()->json(['success' => 'Estimate Approved successfully'], 200);
                }
            }else
            {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }
        }elseif ($estimate->status == 'Approved Level 2')
        {
            if($appsettings[5]->value == auth()->id())
            {
                $estimate->status = 3;
                $estimate->save();
                return response()->json(['success' => 'Estimate Approved successfully'], 200);
            }else
            {
                return response()->json(['error' => 'You are not allowed to approve'], 200);
            }
        }

        return response()->json(['error' => 'Unable to approve contract'], 200);
    }

    public function declineContract($request)
    {
        $note = new Note();
        $note->type = $request->type;
        $note->type_id = $request->type_id;
        $note->user_id = Auth::id();
        $note->description = $request->decline_reason;
        $note->status = 1;
        if($note->save())
        {
            $contract = Contract::find($request->type_id);
            if($contract->user_id)
            {
                $user = User::find($contract->user_id);
                $email2 = [
                    'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
                    'body' => $request->decline_reason,
                    'thanks' => 'Thank you this is from storage Key',
                    'actionText' => 'View Contract',
                    'actionURL' => url('admin/contract/detail') . '/' . $contract->id,
                    'id' => $user->id,

                ];
                Notification::send($user, new ContractDeclineNotification($email2, $user, $contract));
                return response()->json(['success' => 'Contract Declined successfully'], 200);
            }

        }
    }
}
