<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class PendingCustomerRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'verification_token',
        'email',
        'registration_data',
        'otp_hash',
        'expires_at',
        'last_sent_at',
        'resend_count'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'resend_count' => 'integer'
    ];

    /**
     * Get decrypted registration data
     */
    public function getRegistrationDataAttribute($value)
    {
        return json_decode(Crypt::decryptString($value), true);
    }

    /**
     * Set encrypted registration data
     */
    public function setRegistrationDataAttribute($value)
    {
        $this->attributes['registration_data'] = Crypt::encryptString(json_encode($value));
    }

    /**
     * Scope to find by verification token
     */
    public function scopeByToken($query, string $token)
    {
        return $query->where('verification_token', $token);
    }

    /**
     * Scope to find expired records
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', Carbon::now());
    }

    /**
     * Check if the OTP has expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return true;
        }
        
        return $this->expires_at->isPast();
    }

    /**
     * Check if user can resend OTP (1 minute rate limit)
     */
    public function canResend(): bool
    {
        if (!$this->last_sent_at) {
            return true;
        }
        
        // Clone to avoid modifying the original timestamp
        $lastSentPlusMinute = $this->last_sent_at->copy()->addMinute();
        return Carbon::now()->greaterThan($lastSentPlusMinute);
    }

    /**
     * Increment resend count
     */
    public function incrementResendCount(): void
    {
        $this->increment('resend_count');
        $this->update(['last_sent_at' => Carbon::now()]);
    }
}
