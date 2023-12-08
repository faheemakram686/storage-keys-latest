<?php
namespace App\Repo;
use App\Models\AppSettings;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Core\Auth\User;
use App\Models\Country;
use App\Models\Estimate;
use App\Models\Note;
use App\Notifications\Backend\ContractApprovalNotification;
use App\Notifications\Backend\ContractDeclineNotification;
use App\Notifications\Backend\EstimateApprovalNotification;
use App\Notifications\Backend\EstimateDeclineNotification;
use App\Repo\Interfaces\ContactInterface;

use App\Repo\Interfaces\ContractInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Auth;

class ContractClass implements ContractInterface {


    public function saveContract($request)
    {
        $contract =new Contract();
        $contract->customer_id = $request->customer_id;
        $contract->estimate_id = $request->estimate_id;
        $contract->user_id = Auth::id();
        $contract->subject = $request->subject;
        $contract->contract_value = $request->contract_value;
        $contract->contract_type = $request->contract_type;
        $contract->start_date = $request->s_date;
        $contract->end_date = $request->e_date;
        $contract->description = $request->description;
        $contract->is_accepted=0;
        $contract->status=0;
        if($contract->save()){
            $appsettings = AppSettings::get();
            $user = User::find($appsettings[3]->value);
            $template = new EmailTemplateClass();
            $notification = $template->getTemplateByName('contract','email','Contract_approval_email');
            $email2 = [
                'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                'body' => $notification[0]->temp_body,
                'thanks' => 'Thank you this is from storage Key',
                'actionText' => 'View Contract',
                'actionURL' => url('admin/contract/detail').'/'.$contract->id,
                'id' => $user->id,

            ];
            Notification::send($user, new ContractApprovalNotification($email2,$user,$contract));

            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getAllContract()
    {
        $qry=Contract::with('customer','estimate.storageunit','estimate.termLength');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteContract($id)
    {
        $country=Contract::find($id);
        $country->is_deleted=1;
        $country->save();
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

        $qry=Contract::with('contractTemplate','estimate.storageunit.storagesize.mUnit','estimate.termLength','estimate.estimateAddon.addon','customer');
        $qry=$qry->where('id',$id);
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }


    public function signContact($request)
    {
        $folderPath = public_path('upload/');
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $image_name = uniqid() . '.'.$image_type;
        $imagepath = storage_path('app/public/uploads/contract_sign_images/') . $image_name;
//        $request->file('signed')->storeAs('public/uploads/product-images/', $image_name);
//        $file = $folderPath . $image_name;

        file_put_contents($imagepath, $image_base64);
        $contract=Contract::find($request->id);
        $contract->sign_image = $image_name;
        $contract->is_signed = 1;
        $contract->save();
        return 1;




    }

    public function updateContract($request)
    {
        $contract=Contract::find($request->id);
        $contract->customer_id = $request->customer_id;
        $contract->estimate_id = $request->estimate_id;
        $contract->subject = $request->subject;
        $contract->contract_value = $request->contract_value;
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
        $qry=Contract::with('estimate.storageunit','estimate.termLength');
        $qry=$qry->where('customer_id',$request);
        $qry = $qry->where( 'status' , 3 );
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;

    }
    public function getCustomerContractsApi($request)
    {


        try {
            $qry=Contract::with('estimate.storageunit','estimate.termLength');
            $qry=$qry->where('customer_id',$request);
            $qry = $qry->where( 'status' , 3 );
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
                        'body' => $notification[0]->temp_body,
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
                        'body' => $notification[0]->temp_body,
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
        }elseif ($estimate->status == 'Approved')
        {
            return response()->json(['error' => 'Estimate already Approved'], 200);
        }

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
