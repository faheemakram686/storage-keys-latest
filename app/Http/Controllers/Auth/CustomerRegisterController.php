<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Repo\Interfaces\CustomerInterface;
use App\Services\Auth\CustomerOtpService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerRegisterController extends Controller
{

    private $customer;
    private $otpService;

    public function __construct(CustomerInterface $customer, CustomerOtpService $otpService)
    {
        $this->customer = $customer;
        $this->otpService = $otpService;
    }

    public function create()
    {
        return view('ui.pages.customer.register');
    }

    public function register(Request $request)
    {
        try {
            // Honeypot field — leave empty for real users.
            if (filled($request->input('website'))) {
                return redirect()->back()->with('success', 'Successfully Registered Your Account');
            }

            $validated = $request->validate([
                'customer_type' => 'required|in:individual,company',
                'first_name'    => 'required|string|max:100',
                'last_name'     => 'required|string|max:100',
                'company_name'  => 'required_if:customer_type,company|nullable|string|max:150',
                'email'         => 'required|email|unique:contacts,email,NULL,id,is_deleted,0',
                'password'      => 'required|confirmed|min:8',
            ]);

            // Create pending registration and generate OTP
            $result = $this->otpService->createPendingRegistration($validated);
            
            // Send OTP email
            $customerName = trim($validated['first_name'] . ' ' . $validated['last_name']);
            $this->otpService->sendOtp($validated['email'], $result['otp'], $customerName);
            
            // Store verification token in session
            session(['customer_verification_token' => $result['verification_token']]);
            session(['customer_email' => $validated['email']]);

            return redirect()->route('customer.verify.otp.form')
                ->with('success', 'OTP has been sent to your email address. Please check your inbox.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();

        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected Error: ' . $e->getMessage());
        }

    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!session()->has('customer_verification_token')) {
            return redirect()->route('customer.register')->with('error', 'Please register first.');
        }

        $email = session('customer_email');
        $maskedEmail = $this->otpService->maskEmail($email);

        return view('ui.pages.customer.verify-otp', compact('maskedEmail'));
    }

    /**
     * Verify OTP and create account
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'otp' => 'required|digits:4',
            ]);

            $token = session('customer_verification_token');
            
            if (!$token) {
                return redirect()->route('customer.register')
                    ->with('error', 'Session expired. Please register again.');
            }

            // Verify OTP
            $registrationData = $this->otpService->verifyOtp($token, $validated['otp']);

            if (!$registrationData) {
                // Check if token still exists to provide better error message
                $pending = $this->otpService->getPendingRegistration($token);
                
                if (!$pending) {
                    return redirect()->route('customer.register')
                        ->with('error', 'Registration session not found. Please register again.');
                }
                
                if ($pending->isExpired()) {
                    return redirect()->back()
                        ->with('error', 'OTP code has expired. Please click "Resend Code" to get a new one.')
                        ->withInput();
                }
                
                return redirect()->back()
                    ->with('error', 'Invalid OTP code. Please check and try again.')
                    ->withInput();
            }

            // Create customer account
            DB::transaction(function () use ($registrationData) {
                $customer = Customer::create([
                    'customer_type' => $registrationData['customer_type'],
                    'customer_name' => $registrationData['first_name'] . ' ' . $registrationData['last_name'],
                    'company_name'  => $registrationData['customer_type'] == 'company' ? $registrationData['company_name'] : null,
                    'status'        => 1, // Active - OTP verified
                ]);

                Contact::create([
                    'customer_id' => $customer->id,
                    'first_name'  => $registrationData['first_name'],
                    'last_name'   => $registrationData['last_name'],
                    'email'       => $registrationData['email'],
                    'password'    => Hash::make($registrationData['password']),
                    'status'      => 1, // Active - OTP verified
                    'contact_type' => 'primary',
                ]);
            });

            // Delete pending registration
            $this->otpService->deletePendingRegistration($token);

            // Clear session
            session()->forget(['customer_verification_token', 'customer_email']);

            return redirect()->route('customer.login')
                ->with('success', 'Successfully Registered Your Account. You can now login.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        \Log::info('Resend OTP - Request received', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'has_session_token' => session()->has('customer_verification_token')
        ]);
        
        try {
            $token = session('customer_verification_token');
            
            if (!$token) {
                \Log::warning('Resend OTP - No session token found');
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Please register again.'
                ], 400);
            }

            \Log::info('Resend OTP - Checking rate limit', ['token' => substr($token, 0, 10) . '...']);

            // Check rate limit
            if (!$this->otpService->canResendOtp($token)) {
                \Log::warning('Resend OTP - Rate limited');
                return response()->json([
                    'success' => false,
                    'message' => 'Please wait before requesting another OTP.'
                ], 429);
            }

            // Resend OTP
            $result = $this->otpService->resendOtp($token);

            if (!$result) {
                \Log::error('Resend OTP - Service returned null');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resend OTP. Please try again.'
                ], 400);
            }

            \Log::info('Resend OTP - Sending email', ['email' => $result['email']]);

            // Send new OTP email
            $this->otpService->sendOtp($result['email'], $result['otp'], $result['customer_name']);

            \Log::info('Resend OTP - Success');

            return response()->json([
                'success' => true,
                'message' => 'New OTP has been sent to your email.',
                'expires_in' => 60
            ]);

        } catch (\Exception $e) {
            \Log::error('Resend OTP - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


}
