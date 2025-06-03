<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\TermLengthInterface;
use Illuminate\Http\Request;

class TermLengthController extends Controller
{

    private $term_length;

    public function __construct(TermLengthInterface $term_length)
    {
        $this->term_length = $term_length;
    }

    public function index()
    {
        return view('backend/term-length/index');
    }

    public function saveTermLength(Request $request)
    {
        return $this->term_length->saveTermLength($request);
    }


    public function getTermLength(Request $request)
    {
        return $this->term_length->getAllTermLength();

    }

    public function deleteTermLength(Request $request)
    {
        $this->term_length->deleteTermLength($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }

    public function editTermLength(Request $request)
    {
        $res=$this->term_length->editTermLength($request->id);
        return $res;
    }

    public function updateTermLength(Request $request)
    {
        $res=$this->term_length->updateTermLength($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
}
