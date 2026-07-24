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
use App\Services\InsurancePricingService;
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
        $data['insurances'] = app(InsurancePricingService::class)->activePackages();
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
        $data['insurances'] = app(InsurancePricingService::class)->activePackages();
        return view("backend.estimate.create")->with(compact('data','id'));
    }
    public function bookingEstimate($id)
    {
        $data['loc'] =  $this->country->getAllCountry();
        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['lead'] = $this->lead->getLead($id);
        $suIds = [];
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = !empty($item['addon']) ? explode(',', $item['addon']) : [];
            $suIds = $item->storageUnits->pluck('id')->toArray();
            if (empty($suIds) && $item->su_id) {
                $suIds = [$item->su_id];
            }
        }
        $data['su'] = $this->su->leadStorageUnits($suIds);
        $data['email_temp'] = $this->email_template->getEmailTemplate('estimate');
        $data['req_docs'] = $this->require_document->getAllRequireDocument();
        $data['term_length'] = $this->term_length->getAllTermLength();
        $data['insurances'] = app(InsurancePricingService::class)->activePackages();
        return view('backend.estimate.estimate')->with(compact('data'));
    }

    public function getCustomerEstimates(Request $request)
    {
        return $res=$this->estimate->getCustomerEstimates($request->customer_id);

    }

    public function estimateToCustomer(Request $request)
    {

        $data['addon'] = $this->addon->getStorageUnitAddon();
        $data['lead'] = $this->estimate->getEstimate($request->id);
        $suIds = [];
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = !empty($item['addon']) ? explode(',', $item['addon']) : [];
            $suIds = $item->estimateStorageUnits->pluck('storage_unit_id')->toArray();
            if (empty($suIds) && $item->su_id) {
                $suIds = [$item->su_id];
            }
        }
        $data['su'] = $this->su->leadStorageUnits($suIds);
        $suById = collect($data['su'])->keyBy('id');
        $estimateUnits = !empty($data['lead'][0])
            ? ($data['lead'][0]->estimateStorageUnits ?? collect([]))
            : collect([]);

        // Ensure each pivot row has full unit + warehouse for the customer view.
        $estimateUnits->each(function ($eu) use ($suById) {
            $full = $suById->get($eu->storage_unit_id);
            if ($full) {
                $eu->setRelation('storageunit', $full);
            } elseif ($eu->storageunit && !$eu->storageunit->relationLoaded('warehouse')) {
                $eu->storageunit->loadMissing('warehouse.loc.city.country');
            }
        });

        if ($estimateUnits->isEmpty() && $suById->isNotEmpty()) {
            $headerPrice = (float) ($data['lead'][0]->unit_price ?? 0);
            $estimateUnits = $suById->values()->map(function ($su) use ($headerPrice) {
                return (object) [
                    'storage_unit_id' => $su->id,
                    'unit_price' => $headerPrice > 0 ? $headerPrice : (float) ($su->price ?? 0),
                    'storageunit' => $su,
                ];
            });
        }

        $data['estimate_units'] = $estimateUnits;
        $data['term_lengths'] = $this->term_length->getAllTermLength();

        if (!empty($data['lead']) && Carbon::now()->gt($data['lead'][0]->expiry_date)) {
            $expire = array('title' => 'Link Expired','code'=>'419','messege'=>'This link is expired please contect to admin for extend estiamte date');
            return view('backend.layouts.error')->with(compact('expire'));
        }

        return view('backend.estimate.estimatefrontend')->with(compact('data'));

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
        $suIds = [];
        foreach ($data['lead'] as $item) {
            $data['leadaddon'] = !empty($item['addon']) ? explode(',', $item['addon']) : [];
            $suIds = $item->estimateStorageUnits->pluck('storage_unit_id')->toArray();
            if (empty($suIds) && $item->su_id) {
                $suIds = [$item->su_id];
            }
        }
        $data['su'] = $this->su->leadStorageUnits($suIds);
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

    public function editEstimate($id)
    {
        $data['customer']=$this->customer->getAllCustomer();
        $data['loc'] =  $this->country->getAllCountry();
        $data['term_length'] = $this->term_length->getAllTermLength();
        $data['addon'] = $this->addon->getAllAddon();
        $data['email_temp'] = $this->email_template->getEmailTemplate('estimate');
        $data['req_docs'] = $this->require_document->getAllRequireDocument();
        $data['estimate'] = $this->estimate->editEstimate($id);
        $data['insurances'] = app(InsurancePricingService::class)->activePackages();

        return view("backend.estimate.edit")->with(compact('data'));
    }

    public function updateEstimate(Request $request)
    {
        return $this->estimate->updateEstimate($request);
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
        $data['req_documents'] = $this->estimateRequiredDocuments($data['estimate']);
        $data['uploaded_doc_ids'] = \App\Models\Attachment::where('type', 'estimate')
            ->where('type_id', $id)
            ->where('is_deleted', 0)
            ->pluck('req_doc_id')
            ->map(function ($docId) { return (string) $docId; })
            ->all();
        return view('backend.estimate.estimate-upload-documents')->with(compact('data'));

    }

    /**
     * Resolve the RequireDocument models referenced by an estimate's
     * require_documents CSV, skipping missing/deleted document types so the
     * upload page never renders (or requires) a document that no longer exists.
     */
    private function estimateRequiredDocuments($estimates)
    {
        $documents = collect([]);
        foreach ($estimates as $item) {
            $ids = !empty($item['require_documents']) ? explode(',', $item['require_documents']) : [];
            foreach ($ids as $id) {
                $id = trim((string) $id);
                if ($id === '') {
                    continue;
                }
                $doc = $this->require_document->getRequireDocument($id);
                if ($doc && !$doc->is_deleted && !$documents->contains('id', $doc->id)) {
                    $documents->push($doc);
                }
            }
        }
        return $documents;
    }

    public function uploadDocuments(Request $request)
    {
        $result = $this->storeEstimateDocuments($request);

        if (!$result['status']) {
            return response()->json(['errors' => $result['message']], $result['code']);
        }

        return response()->json(['success' => $result['message']], 200);
    }

    /**
     * Shared upload handler for the customer web portal and the mobile API.
     * Pairs submitted req-doc ids with their files, only requires documents
     * that are still configured on the estimate and not already uploaded,
     * and stores each file as an estimate attachment.
     */
    private function storeEstimateDocuments(Request $request, $customerId = null)
    {
        $estimateId = $request->input('estimate_id');
        if (empty($estimateId)) {
            return ['status' => false, 'code' => 422, 'message' => 'Estimate reference is missing. Please reopen the upload link and try again.'];
        }

        $query = \App\Models\Estimate::where('id', $estimateId)->where('is_deleted', 0);
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }
        $estimate = $query->first();

        if (!$estimate) {
            return ['status' => false, 'code' => 404, 'message' => 'Estimate not found.'];
        }

        $requiredDocuments = $this->estimateRequiredDocuments(collect([$estimate]));

        if ($requiredDocuments->isEmpty()) {
            return ['status' => true, 'message' => 'No documents are required for this estimate.'];
        }

        // Map uploaded files to require-document ids.
        // Web form posts files[docId]; older/mobile clients may still post files[] + id[].
        $files = $request->file('files', []);
        if (!is_array($files)) {
            $files = $files ? [$files] : [];
        }

        $submittedIds = array_values(array_map('strval', (array) $request->input('id', [])));

        $isList = true;
        foreach (array_keys($files) as $i => $key) {
            if ((string) $key !== (string) $i) {
                $isList = false;
                break;
            }
        }

        $fileByDocId = [];
        if ($isList) {
            foreach ($files as $index => $file) {
                if (!($file instanceof \Illuminate\Http\UploadedFile && $file->isValid())) {
                    continue;
                }
                if (!isset($submittedIds[$index])) {
                    continue;
                }
                $fileByDocId[$submittedIds[$index]] = $file;
            }
        } else {
            foreach ($files as $docId => $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    $fileByDocId[(string) $docId] = $file;
                }
            }
        }

        $alreadyUploaded = \App\Models\Attachment::where('type', 'estimate')
            ->where('type_id', $estimate->id)
            ->where('is_deleted', 0)
            ->pluck('req_doc_id')
            ->map(function ($id) { return (string) $id; })
            ->all();

        $missing = [];
        $toStore = [];
        foreach ($requiredDocuments as $doc) {
            $docId = (string) $doc->id;
            if (isset($fileByDocId[$docId])) {
                $toStore[$docId] = $fileByDocId[$docId];
            } elseif (!in_array($docId, $alreadyUploaded, true)) {
                $missing[] = $doc->title;
            }
        }

        if (!empty($missing)) {
            return ['status' => false, 'code' => 422, 'message' => 'Please upload the following required documents: ' . implode(', ', $missing)];
        }

        if (empty($toStore)) {
            return ['status' => true, 'message' => 'All required documents have already been uploaded.'];
        }

        foreach ($toStore as $docId => $file) {
            $uniqueid = uniqid();
            $extension = $file->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $path = $file->storeAs('public/files', $name);

            $this->attachment->saveAttachment(array(
                'type' => 'estimate',
                'type_id' => $estimate->id,
                'req_doc_id' => $docId,
                'name' => $name,
                'path' => $path,
                'status' => 1,
            ));
        }

        return ['status' => true, 'message' => 'Documents uploaded successfully'];
    }

    /**
     * Mobile API: list the authenticated customer's estimates.
     */
    public function getCustomerEstimatesApi(Request $request)
    {
        $customerId = optional($request->user())->customer_id;
        if (!$customerId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer estimates',
            'data' => $this->estimate->getCustomerEstimates($customerId),
        ], 200);
    }

    /**
     * Mobile API: list the required documents for one of the customer's
     * estimates, with an uploaded flag so the app knows what is pending.
     */
    public function getEstimateRequiredDocumentsApi(Request $request)
    {
        $customerId = optional($request->user())->customer_id;
        if (!$customerId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        if (empty($request->estimate_id)) {
            return response()->json(['status' => false, 'message' => 'estimate_id is required'], 422);
        }

        $estimate = \App\Models\Estimate::where('id', $request->estimate_id)
            ->where('customer_id', $customerId)
            ->where('is_deleted', 0)
            ->first();

        if (!$estimate) {
            return response()->json(['status' => false, 'message' => 'Estimate not found'], 404);
        }

        $uploaded = \App\Models\Attachment::where('type', 'estimate')
            ->where('type_id', $estimate->id)
            ->where('is_deleted', 0)
            ->pluck('req_doc_id')
            ->map(function ($id) { return (string) $id; })
            ->all();

        $documents = $this->estimateRequiredDocuments(collect([$estimate]))
            ->map(function ($doc) use ($uploaded) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'description' => $doc->description,
                    'uploaded' => in_array((string) $doc->id, $uploaded, true),
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'message' => 'Estimate required documents',
            'data' => $documents,
        ], 200);
    }

    /**
     * Mobile API: upload estimate documents. Accepts the same payload as the
     * web form (estimate_id, id[] of require-document ids, files[]).
     */
    public function uploadEstimateDocumentsApi(Request $request)
    {
        $customerId = optional($request->user())->customer_id;
        if (!$customerId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->storeEstimateDocuments($request, $customerId);

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
        ], $result['status'] ? 200 : $result['code']);
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
        $deleted = $this->estimate->deleteEstimate($request->id);
        if (!$deleted) {
            return response()->json(['error' => 'Estimate not found'], 404);
        }
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }

}
