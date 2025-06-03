<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RequireDocument;
use App\Repo\AddonClass;
use App\Repo\AppSettingsClass;
use App\Repo\AttachmentClass;
use App\Repo\CountryClass;
use App\Repo\CustomerClass;
use App\Repo\EmailTemplateClass;
use App\Repo\Interfaces\EstimateInterface;
use App\Repo\LeadClass;
use App\Repo\RequireDocumentClass;
use App\Repo\StorageUnitClass;
use App\Repo\TermLengthClass;
use App\Repo\UserClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;


class EstimateController extends Controller
{
    private $su;
    private $addon;
    private $lead;
    private $estimate;

    private $email_template;

    private $require_document;

    private $attachment;
    private $user;
    private $customer;
    private $term_length;
    private $country;
    private $appsettings;

    public function __construct(EstimateInterface $estimate)
    {

        $this->su = new StorageUnitClass();
        $this->addon = new AddonClass();
        $this->lead = new LeadClass();
        $this->email_template = new EmailTemplateClass();
        $this->require_document = new RequireDocumentClass();
        $this->estimate = $estimate;
        $this->attachment = new AttachmentClass();
        $this->user = new UserClass();
        $this->customer = new CustomerClass();
        $this->term_length = new TermLengthClass();
        $this->country = new CountryClass();
        $this->appsettings = new AppSettingsClass();

    }

    public function index()
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['estimate'] = $this->estimate->getAllEstimate();
        return view("backend.estimate.index")->with(compact('data'));
    }
    public function create()
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['loc'] =  $this->country->getAllCountry();
        $data['term_length'] = $this->term_length->getAllTermLength();
        $data['addon'] = $this->addon->getAllAddon();
        $data['email_temp'] = $this->email_template->getEmailTemplate('estimate');
        $data['req_docs'] = $this->require_document->getAllRequireDocument();
        return view("backend.estimate.create")->with(compact('data'));
    }

    public function createCustomerEstimate($id)
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['loc'] =  $this->country->getAllCountry();
        $data['term_length'] = $this->term_length->getAllTermLength();
        $data['addon'] = $this->addon->getAllAddon();
        $data['email_temp'] = $this->email_template->getEmailTemplate('estimate');
        $data['req_docs'] = $this->require_document->getAllRequireDocument();
        return view("backend.estimate.create")->with(compact('data','id'));
    }
    public function bookingEstimate($id)
    {
        $data['loc'] =  $this->country->getAllCountry();
        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['lead'] = $this->lead->getLead($id);
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = explode(',', $item['addon']);
            $su_id = $item['su_id'];
        }
        $data['su'] = $this->su->leadStorageUnit($su_id);
        $data['email_temp'] = $this->email_template->getEmailTemplate('estimate');
        $data['req_docs'] = $this->require_document->getAllRequireDocument();
        $data['term_length'] = $this->term_length->getAllTermLength();
        return view('backend.estimate.estimate')->with(compact('data'));
    }

    public function getCustomerEstimates(Request $request)
    {
         $res=$this->estimate->getCustomerEstimates($request->customer_id);

        return $res;
    }

    public function estimateToCustomer(Request $request)
    {

        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['lead'] = $this->estimate->getEstimate($request->id);
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = explode(',', $item['addon']);
            $su_id = $item['su_id'];
        }
        $data['su'] = $this->su->leadStorageUnit($su_id);
        $data['term_lengths'] = $this->term_length->getAllTermLength();


        if ( $data['lead'] && Carbon::now()->gt( $data['lead'][0]->expiry_date)) {
            $expire = array('title' => 'Link Expired','code'=>'419','messege'=>'This link is expired please contect to admin for extend estiamte date');
            return view('backend.layouts.error')->with(compact('expire'));
        } else {

            return view('backend.estimate.estimatefrontend')->with(compact('data'));
        }

    }

    public function pdftest($id)
    {
        $data['estimate'] = $this->estimate->getEstimate($id);
       return view('backend.estimate.show1')->with(compact('data'));
    }

    public function estimateToCustomerPDF(Request $request)
    {
        $data['estimate'] = $this->estimate->getEstimate($request->id);
        $pdf = PDF::loadView('backend.estimate.show1', compact('data'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream('estimate.pdf');
    }
    public function estimateToCustomer2(Request $request)
    {
        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['lead'] = $this->estimate->getEstimate($request->id);
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = explode(',', $item['addon']);
            $su_id = $item['su_id'];
        }
        $data['su'] = $this->su->leadStorageUnit($su_id);
        return $data;
    }

    public function getEstimate(Request $request)
    {
        $data['estimate'] = $this->estimate->getEstimate($request->id);
        return $data;
    }

    public function detailEstimate($id)
    {
        $data['estimate'] = $this->estimate->getEstimate($id);
        $data['appSettings'] = $this->appsettings->getAppSettings();
        return view('backend.estimate.show')->with(compact('data'));
    }

    public function showAttachment($id)
    {

        $data['estimate'] = $this->estimate->getEstimate($id);
        return view('backend.estimate.attachments')->with(compact('data'));
    }



    public function saveEstimate(Request $request)
    {
        return $this->estimate->saveEstimate($request);
    }
    public function approveSendEstimate(Request $request)
    {
        return $this->estimate->approveEstimate($request->id);
    }

    public function declineSendEstimate(Request $request)
    {
        return $this->estimate->declineEstimate($request);
    }


    public function addEstimate(Request $request)
    {

        return $this->estimate->addEstimate($request);
    }

    public function getEstimates()
    {
        return $this->estimate->getAllEstimate();
    }

    public function showEstimates()
    {
        return view('backend.estimate.show1');
    }

    public function showUploadDocuments($id)
    {
        $data['estimate'] = $this->estimate->getEstimateReqDocs($id);
        foreach ($data['estimate'] as $item) {
            $data['req_docs'] = explode(',', $item['require_documents']);
        }
        $data['req_documents'] = collect([]);
        foreach($data['req_docs'] as $doc => $v)
        {
            $data['req_documents']->push($this->require_document->getRequireDocument($v));
        }
        return view('backend.estimate.estimate-upload-documents')->with(compact('data'));

    }

    public function uploadDocuments(Request $request)
    {

        if ($request->hasFile('files')) {
            $images = $request->file('files');
            $id = $request->input('id');
            for ($c = 0; $c < count($images); $c++) {
                if ($images[$c]->isValid()) {

                    $uniqueid = uniqid();
                    $original_name = $images[$c]->getClientOriginalName();
                    $size = $images[$c]->getSize();
                    $extension = $images[$c]->getClientOriginalExtension();
                    $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
                    $path = $images[$c]->storeAs('public/files', $name);

                        $items = array(
                            'type' => 'estimate',
                            'type_id' => $request->estimate_id,
                            'req_doc_id' => $id[$c],
                            'name' => $name,
                            'path' => $path,
                            'status' => 1,
                        );
                     $this->attachment->saveAttachment($items);
                }

                }
            return response()->json(['success' => 'Documents uploaded successfully'], 200);
        }

        return response()->json(['success' => 'No images were uploaded.'], 200);
    }


    public function showTasks($request)
    {
        $data['estimate'] = $this->estimate->getEstimate($request);
        $data['user'] = $this->user->getUser();
        return view('backend.estimate.tasks')->with(compact('data'));
    }
    public function showReminders($request)
    {
        $data['estimate'] = $this->estimate->getEstimate($request);
        $data['users'] = $this->user->getUser();
        return view('backend.estimate.reminders')->with(compact('data'));
    }
    public function showNotes($request)
    {
        $data['estimate'] = $this->estimate->getEstimate($request);
        $data['users'] = $this->user->getUser();
        return view('backend.estimate.notes')->with(compact('data'));
    }

    public function deleteEstimate(Request $request)
    {
        $this->estimate->deleteEstimate($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }

}
