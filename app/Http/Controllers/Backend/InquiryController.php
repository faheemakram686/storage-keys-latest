<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{


    public function index()
    {
        return view('backend.inquiry.index');
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email',
                'storage_type' => 'required|string',
                'phone'  => 'required|string|max:20',
                'message'=> 'nullable|string',
            ]);

            Inquiry::create($validated);

            DB::commit();

            return redirect()->back()->with('success', 'Your inquiry has been submitted successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Inquiry submission failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your inquiry. Please try again later.');
        }
    }


    public function getInquires(Request $request)
    {
        try {
            $qry=Inquiry::all();
            return $qry;
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteInquiry(Request $request)
    {
        $qry=Inquiry::find($request->id);
        $qry->delete();
        if ($qry)
        {
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }else
        {
            return response()->json(['error' => 'Record not deleted, Technical Error'], 200);
        }


    }

}
