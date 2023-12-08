<?php
namespace App\Repo;
use App\Models\AddonPriceEstimate;
use App\Models\AppSettings;
use App\Models\Core\Auth\User;
use App\Models\Country;
use App\Models\Estimate;
use App\Models\Lead;
use App\Models\LeadModel;
use App\Models\Note;
use App\Notifications\Backend\EstimateApprovalNotification;
use App\Notifications\Backend\EstimateDeclineNotification;
use App\Notifications\Backend\EstimateNotification;
use App\Notifications\EmailNotification;
use App\Repo\Interfaces\CountryInterface;
use App\Repo\Interfaces\EstimateInterface;
use App\Repo\Interfaces\LeadInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Auth;


class EstimateClass implements EstimateInterface {

    private $contact ;
    public function saveEstimate($request)
    {
        $estimate=new Estimate();
        if($request->ssu_id){
            $estimate->su_id=$request->ssu_id;
        }else{
            $estimate->su_id=$request->su_id;
        }

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
        $estimate->unit_price= $request->unit_price;
        $estimate->addon= implode(',', $request->addon);
        $estimate->require_documents = implode(',', $request->require_document);
        $estimate->email_template =  $request->email_template;
        $estimate->insurence =  $request->insurance;
        if($request->insurance == "cover" ){
            $estimate->goods=$request->goodsval;
        }
        $estimate->status=$request->status;
        $estimate->estimate_date=$request->estimate_date;
        $estimate->expiry_date=$request->expiry_date;
        if($estimate->save()){
            for ($i = 0; $i < count($request->addon); $i++) {
                $addonpriceestimate[] = [
                    'estimate_id' => $estimate->id,
                    'addon_id' => $request->addon[$i],
                    'price' => $request->addonprice[$i],
                ];
            }
            AddonPriceEstimate::insert($addonpriceestimate);
            $lead = Lead::find($request->lead_id);
            $lead->changeStatus(4);

            $estimate_email = Estimate::with('emailTemplate')->find($estimate->id);

            $email = [
                'greeting' => 'Hi '.$estimate_email->f_name.' '.$estimate_email->l_name.',',
                'body' => html_entity_decode($estimate_email->emailTemplate->temp_body),
                'thanks' => 'Thank you this Estimate from storage Key',
                'actionText' => 'View Estimate',
                'actionURL' => url('estimatetocustomer').'/'.$estimate_email->id,
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

        $estimate=new Estimate();
        $estimate->su_id=$request->su_id;
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
        $estimate->unit_price= $request->unit_price;
        $estimate->addon= implode(',', $request->addon);
        $estimate->require_documents = implode(',', $request->require_document);
        $estimate->email_template =  $request->email_template;
        $estimate->insurence =  $request->insurance;
        if($request->insurance == "cover" ){
            $estimate->goods=$request->goodsval;
        }
        $estimate->status=$request->status;
        $estimate->estimate_date=$request->estimate_date;
        $estimate->expiry_date=$request->expiry_date;
        if($estimate->save()){
            for ($i = 0; $i < count($request->addon); $i++) {
                $addonpriceestimate[] = [
                    'estimate_id' => $estimate->id,
                    'addon_id' => $request->addon[$i],
                    'price' => $request->addonprice[$i],
                ];
            }
            AddonPriceEstimate::insert($addonpriceestimate);
            $appsettings = AppSettings::get();
            $user = User::find($appsettings[0]->value);
            $template = new EmailTemplateClass();
            $notification = $template->getTemplateByName('estimate','email','Estimate_approval_email');
            $email2 = [
                'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                'body' => $notification[0]->temp_body,
                'thanks' => 'Thank you this is from storage Key',
                'actionText' => 'View Estimate',
                'actionURL' => url('admin/estimate/detail').'/'.$estimate->id,
                'id' => $user->id,

            ];
            Notification::send($user, new EstimateApprovalNotification($email2,$user,$estimate));

            return response()->json(['success' => 'Estimate created successfully Require Approval'], 200);

        }

    }

    public function getAllEstimate()
    {
        $qry=Estimate::with('storageunit','termLength','customer');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteEstimate($id)
    {
        $country=Estimate::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editEstimate($id)
    {
        // TODO: Implement editEstimate() method.
    }

    public function updateUpdate($request)
    {
        // TODO: Implement updateUpdate() method.
    }

    public function getEstimate($id)
    {
        $qry = Estimate::with('storageunit','estimateAddon.addon','requireDocument','termLength');
        $qry = $qry->where( 'id' , $id );
        $qry = $qry->get();
        return $qry;
    }
    public  function getCustomerEstimates($id)
    {
        $qry = Estimate::with('storageunit','termLength');
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
        if($estimate->status == 'Not Approved')
        {
            if($appsettings[0]->value == auth()->id())
            {
                $estimate->status = 1;
                if($estimate->save())
                {
                    $appsettings = AppSettings::get();
                    $user = User::find($appsettings[1]->value);
                    $template = new EmailTemplateClass();
                    $notification = $template->getTemplateByName('estimate','email','Estimate_approval_email');
                    $email2 = [
                        'greeting' => 'Hi '.$user->first_name.' '.$user->last_name.',',
                        'body' => $notification[0]->temp_body,
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
            if($appsettings[1]->value == auth()->id())
            {
                $estimate->status = 2;
                if($estimate->save()) {
                    $appsettings = AppSettings::get();
                    $user = User::find($appsettings[2]->value);
                    $template = new EmailTemplateClass();
                    $notification = $template->getTemplateByName('estimate', 'email', 'Estimate_approval_email');
                    $email2 = [
                        'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
                        'body' => $notification[0]->temp_body,
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
            if($appsettings[2]->value == auth()->id())
            {
                $estimate->status = 3;
                $estimate->save();

                $estimate_email = Estimate::with('emailTemplate')->find($estimate->id);

                $email = [
                    'greeting' => 'Hi '.$estimate_email->f_name.' '.$estimate_email->l_name.',',
                    'body' => html_entity_decode($estimate_email->emailTemplate->temp_body),
                    'thanks' => 'Thank you this Estimate from storage Key',
                    'actionText' => 'View Estimate',
                    'actionURL' => url('estimatetocustomer').'/'.$estimate_email->id,
                    'id' => $estimate_email->id,
                ];

                Notification::route('mail', $estimate_email->email)->notify(new EstimateNotification($email));

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

    public function declineEstimate($request)
    {
        $note = new Note();
        $note->type = 'estimate';
        $note->type_id = $request->id;
        $note->user_id = Auth::id();
        $note->description = $request->decline_reason;
        $note->status = 1;
        if($note->save())
        {
            $estimate = Estimate::find($request->id);
            if($estimate->user_id)
            {
                $user = User::find($estimate->user_id);
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
