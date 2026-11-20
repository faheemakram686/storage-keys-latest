<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Services\InquiryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    public function index()
    {
        return view('backend.inquiry.index');
    }

    public function store(Request $request, InquiryService $inquiries)
    {
        try {
            // Silent fake-success for bots — never write DB / email / Google Sheet.
            if ($inquiries->isSpam($request)) {
                $inquiries->logSpam($request);

                return redirect()->route('inquiry.thankyou')->with('inquiry', [
                    'name' => (string) $request->input('name', ''),
                    'email' => (string) $request->input('email', ''),
                    'phone' => (string) $request->input('phone', ''),
                    'storage_type' => (string) $request->input('storage_type', ''),
                    'reference' => 'SK-00000',
                ]);
            }

            if ($this->recaptchaEnabled() && !$this->verifyRecaptcha($request)) {
                return redirect()->back()
                    ->withErrors(['g-recaptcha-response' => 'Please confirm you are not a robot.'])
                    ->withInput();
            }

            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email',
                'storage_type' => 'required|string',
                'phone'  => 'required|string|max:40',
                'message'=> 'nullable|string',
            ]);

            $inquiry = $inquiries->create($validated, $request);

            return redirect()->route('inquiry.thankyou')->with('inquiry', [
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'phone' => $inquiry->phone,
                'storage_type' => $inquiry->storage_type,
                'reference' => $inquiries->reference($inquiry),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Inquiry submission failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your inquiry. Please try again later.');
        }
    }

    private function recaptchaEnabled(): bool
    {
        return filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret_key'));
    }

    private function verifyRecaptcha(Request $request): bool
    {
        $token = (string) $request->input('g-recaptcha-response', '');
        if ($token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

            $ok = (bool) data_get($response->json(), 'success', false);
            if (!$ok) {
                Log::info('inquiry.recaptcha_failed', [
                    'ip' => $request->ip(),
                    'errors' => data_get($response->json(), 'error-codes'),
                ]);
            }

            return $ok;
        } catch (\Throwable $e) {
            Log::error('inquiry.recaptcha_error: '.$e->getMessage());

            // Fail closed when keys are configured — do not accept unverified posts.
            return false;
        }
    }

    public function getInquires(Request $request)
    {
        try {
            $qry = Inquiry::all();
            return $qry;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteInquiry(Request $request)
    {
        $qry = Inquiry::find($request->id);
        $qry->delete();
        if ($qry) {
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }

        return response()->json(['error' => 'Record not deleted, Technical Error'], 200);
    }
}
