<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\InquiryMail;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{


    public function index()
    {
        return view('backend.inquiry.index');
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email',
                'storage_type' => 'required|string',
                'phone'  => 'required|string|max:40',
                'message'=> 'nullable|string',
            ]);

            $extras = [];
            foreach (['source', 'company', 'size', 'storing', 'duration', 'volume', 'items'] as $key) {
                $val = trim((string) $request->input($key, ''));
                if ($val !== '') {
                    $extras[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $val;
                }
            }

            if ($extras) {
                $validated['message'] = trim(($validated['message'] ?? '') . "\n\n" . implode("\n", $extras));
            }

            DB::beginTransaction();

            $inquiry = Inquiry::create($validated);

            DB::commit();

            try {
                $notifyTo = config('mail.inquiry_to');
                if (!empty($notifyTo)) {
                    Mail::to($notifyTo)->send(new InquiryMail($inquiry));
                }
            } catch (\Exception $mailEx) {
                \Log::error('Inquiry email failed: '.$mailEx->getMessage());
            }

            return redirect()->route('inquiry.thankyou')->with('inquiry', [
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'phone' => $inquiry->phone,
                'storage_type' => $inquiry->storage_type,
                'reference' => 'SK-' . str_pad((string) $inquiry->id, 5, '0', STR_PAD_LEFT),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
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
