<?php

namespace App\Services\Auth;

use App\Mail\Contact\CustomerOtpMail;
use App\Models\PendingCustomerRegistration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerOtpService
{
    /**
     * Generate a 4-digit OTP code
     */
    public function generateOtp(): string
    {
        return (string) random_int(1000, 9999);
    }

    /**
     * Create a pending registration record with OTP
     * 
     * @param array $validatedData Registration form data
     * @return array Contains verification_token and otp
     */
    public function createPendingRegistration(array $validatedData): array
    {
        $otp = $this->generateOtp();
        $verificationToken = Str::random(64);
        
        PendingCustomerRegistration::create([
            'verification_token' => $verificationToken,
            'email' => $validatedData['email'],
            'registration_data' => $validatedData,
            'otp_hash' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinute(1),
            'last_sent_at' => Carbon::now(),
            'resend_count' => 0
        ]);

        return [
            'verification_token' => $verificationToken,
            'otp' => $otp
        ];
    }

    /**
     * Send OTP email to customer
     * 
     * @param string $email Customer email
     * @param string $otp OTP code to send
     * @param string $customerName Customer's name
     */
    public function sendOtp(string $email, string $otp, string $customerName): void
    {
        Mail::to($email)->send(new CustomerOtpMail($customerName, $otp, 1));
    }

    /**
     * Verify OTP against stored hash
     * 
     * @param string $token Verification token
     * @param string $otp OTP code to verify
     * @return array|null Returns registration data if valid, null otherwise
     */
    public function verifyOtp(string $token, string $otp): ?array
    {
        $pending = PendingCustomerRegistration::byToken($token)->first();

        if (!$pending) {
            return null;
        }

        if ($pending->isExpired()) {
            return null;
        }

        if (!Hash::check($otp, $pending->otp_hash)) {
            return null;
        }

        return $pending->registration_data;
    }

    /**
     * Check if user can resend OTP (rate limit check)
     * 
     * @param string $token Verification token
     * @return bool
     */
    public function canResendOtp(string $token): bool
    {
        $pending = PendingCustomerRegistration::byToken($token)->first();

        if (!$pending) {
            return false;
        }

        return $pending->canResend();
    }

    /**
     * Resend OTP - generate new code and update record
     * 
     * @param string $token Verification token
     * @return array|null Returns new otp and email, null if token invalid
     */
    public function resendOtp(string $token): ?array
    {
        $pending = PendingCustomerRegistration::byToken($token)->first();

        if (!$pending || !$pending->canResend()) {
            return null;
        }

        $newOtp = $this->generateOtp();
        
        $pending->update([
            'otp_hash' => Hash::make($newOtp),
            'expires_at' => Carbon::now()->addMinute(1),
            'last_sent_at' => Carbon::now()
        ]);
        
        $pending->increment('resend_count');

        $registrationData = $pending->registration_data;
        $customerName = trim($registrationData['first_name'] . ' ' . $registrationData['last_name']);

        return [
            'otp' => $newOtp,
            'email' => $pending->email,
            'customer_name' => $customerName
        ];
    }

    /**
     * Mask email address for display
     * 
     * @param string $email Email to mask
     * @return string Masked email (e.g., j***@example.com)
     */
    public function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $name = $parts[0];
        $domain = $parts[1];

        if (strlen($name) <= 1) {
            return $name . '***@' . $domain;
        }

        return substr($name, 0, 1) . '***@' . $domain;
    }

    /**
     * Delete pending registration after successful verification
     * 
     * @param string $token Verification token
     */
    public function deletePendingRegistration(string $token): void
    {
        PendingCustomerRegistration::byToken($token)->delete();
    }

    /**
     * Get pending registration by token
     * 
     * @param string $token Verification token
     * @return PendingCustomerRegistration|null
     */
    public function getPendingRegistration(string $token): ?PendingCustomerRegistration
    {
        return PendingCustomerRegistration::byToken($token)->first();
    }
}
