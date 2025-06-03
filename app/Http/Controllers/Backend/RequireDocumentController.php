<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\RequireDocumentInterface;
use Illuminate\Http\Request;

class RequireDocumentController extends Controller
{


    private $req_document;

    public function __construct(RequireDocumentInterface $req_document)
    {
        $this->req_document = $req_document;
    }

    public function index()
    {
        return view('backend.settings.requiredocuments.index');
    }


    public function saveRequireDocument(Request $request)
    {
        return $this->req_document->saveRequireDocument($request);
    }


    public function getRequireDocument(Request $request)
    {
        return $this->req_document->getAllRequireDocument();

    }

    public function deleteRequireDocument(Request $request)
    {
        $this->req_document->deleteRequireDocument($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editRequireDocument(Request $request)
    {
        $res=$this->req_document->editRequireDocument($request->id);
        return $res;
    }

    public function updateRequireDocument(Request $request)
    {
        $res=$this->req_document->updateRequireDocument($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
}
