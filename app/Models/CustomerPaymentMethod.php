<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'card_token',
        'masked_pan',
        'expiry',
        'scheme',
        'cardholder_name',
        'is_active',
        'recurring_consent_at',
    ];

    protected $casts = [
        'card_token' => 'encrypted',
        'is_active' => 'boolean',
        'recurring_consent_at' => 'datetime',
    ];

    protected $hidden = [
        'card_token',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function activeForCustomer(int $customerId): ?self
    {
        return static::query()
            ->where('customer_id', $customerId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Active card with recurring consent and a future expiry (MM/YY or YYYY-MM).
     */
    public static function chargeableForCustomer(int $customerId): ?self
    {
        $method = static::query()
            ->where('customer_id', $customerId)
            ->where('is_active', true)
            ->whereNotNull('recurring_consent_at')
            ->orderByDesc('id')
            ->first();

        if (!$method || !$method->isExpiryValid()) {
            return null;
        }

        return $method;
    }

    public function hasRecurringConsent(): bool
    {
        return $this->recurring_consent_at !== null;
    }

    public function isExpiryValid(): bool
    {
        $expiry = trim((string) $this->expiry);
        if ($expiry === '') {
            return false;
        }

        // N-Genius typically returns MMYY or MM/YY
        if (preg_match('/^(\d{2})[\/\-]?(\d{2})$/', $expiry, $m)) {
            $month = (int) $m[1];
            $year = 2000 + (int) $m[2];
        } elseif (preg_match('/^(\d{4})-(\d{2})$/', $expiry, $m)) {
            $year = (int) $m[1];
            $month = (int) $m[2];
        } else {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }

        $expiresAt = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        return $expiresAt->isFuture() || $expiresAt->isToday();
    }

    public function toNgeniusSavedCard(): array
    {
        return array_filter([
            'cardToken' => $this->card_token,
            'cardholderName' => $this->cardholder_name,
            'expiry' => $this->expiry,
            'maskedPan' => $this->masked_pan,
            'scheme' => $this->scheme,
        ], static fn ($value) => $value !== null && $value !== '');
    }

    public static function upsertFromSavedCard(int $customerId, array $savedCard, bool $withRecurringConsent = false): ?self
    {
        $token = $savedCard['cardToken'] ?? null;
        if (!$token) {
            return null;
        }

        static::query()
            ->where('customer_id', $customerId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return static::create([
            'customer_id' => $customerId,
            'card_token' => $token,
            'masked_pan' => $savedCard['maskedPan'] ?? null,
            'expiry' => $savedCard['expiry'] ?? null,
            'scheme' => $savedCard['scheme'] ?? null,
            'cardholder_name' => $savedCard['cardholderName'] ?? null,
            'is_active' => true,
            'recurring_consent_at' => $withRecurringConsent ? now() : null,
        ]);
    }
}
