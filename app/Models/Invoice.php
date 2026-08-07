<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $appends = ['hashid'];

    public function getHashidAttribute(): string
    {
        return hashid_encode((int) $this->id);
    }

    public const PAYMENT_PENDING = 0;
    public const PAYMENT_PAID = 1;
    public const PAYMENT_PARTIAL = 2;

    protected $fillable = [
        'q_invoice_id',
        'customer_id',
        'type',
        'contract_id',
        'order_id',
        'recurring',
        'no_cycle',
        'duration',
        'duration_type',
        'next_recurring_date',
        'parent_invoice_id',
        'invoice_date',
        'invoice_no',
        'user_id',
        'sub_total',
        'vat',
        'vat_type',
        'grand_total',
        'due_date',
        'note',
        'remarks',
        'payment_method',
        'status',
        'invoice_ref',
    ];


    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();

        if ($lastInvoice) {
            $lastInvoiceNumber = (int)substr($lastInvoice->invoice_no, -4);
            $newInvoiceNumber = $lastInvoiceNumber + 1;
        } else {
            $newInvoiceNumber = 1;
        }

        return 'INV-' . str_pad($newInvoiceNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Advance a date by this invoice's recurring interval.
     */
    public function calculateNextRecurringDate($fromDate = null): ?string
    {
        if ($this->recurring === null || $this->recurring === '' || $this->recurring === '0') {
            return null;
        }

        $date = $fromDate
            ? Carbon::parse($fromDate)
            : Carbon::parse($this->invoice_date ?? now());

        if ($this->recurring === 'custom') {
            $amount = max(1, (int) $this->duration);

            return match ($this->duration_type) {
                'days' => $date->addDays($amount)->format('Y-m-d'),
                'weeks' => $date->addWeeks($amount)->format('Y-m-d'),
                'years' => $date->addYears($amount)->format('Y-m-d'),
                default => $date->addMonths($amount)->format('Y-m-d'),
            };
        }

        $months = max(1, (int) $this->recurring);

        return $date->addMonths($months)->format('Y-m-d');
    }

    public function isRecurringActive(): bool
    {
        if ($this->recurring === null || $this->recurring === '' || $this->recurring === '0') {
            return false;
        }

        if ($this->no_cycle === 'infinity') {
            return true;
        }

        return (int) $this->no_cycle > 0;
    }

    /**
     * Human-readable billing period, e.g. "Every 3 months".
     */
    public function recurringIntervalLabel(): ?string
    {
        if ($this->recurring === null || $this->recurring === '' || $this->recurring === '0') {
            return null;
        }

        if ($this->recurring === 'custom') {
            $amount = max(1, (int) $this->duration);
            $type = $this->duration_type ?: 'months';
            $singular = rtrim($type, 's');
            $unit = $amount === 1 ? $singular : $type;

            return "Every {$amount} {$unit}";
        }

        $months = max(1, (int) $this->recurring);

        return $months === 1 ? 'Every 1 month' : "Every {$months} months";
    }

    /**
     * Whole months past due_date while payment is not fully paid; null if paid or not overdue.
     */
    public function monthsOverdue(): ?int
    {
        if ($this->isFullyPaid() || !$this->due_date) {
            return null;
        }

        $due = Carbon::parse($this->due_date)->startOfDay();
        $today = Carbon::today();

        if ($today->lte($due)) {
            return null;
        }

        return max(1, (int) $due->diffInMonths($today));
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function userResponsible()
    {
        return $this->belongsTo(\App\Models\Core\Auth\User::class, 'user_id', 'id');
    }
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function estimate()
    {
        return $this->belongsTo(Estimate::class, 'estimate_id', 'id');
    }
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }
    public function parentInvoice()
    {
        return $this->belongsTo(self::class, 'parent_invoice_id');
    }
    public function childInvoices()
    {
        return $this->hasMany(self::class, 'parent_invoice_id');
    }


    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];


    public function paidAmount(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('amount_received');
        }

        return (float) $this->payments()->where('is_deleted', 0)->sum('amount_received');
    }

    public function balanceAmount(): float
    {
        return max(0, round(((float) $this->grand_total) - $this->paidAmount(), 2));
    }

    public function resolvePaymentStatusValue(): int
    {
        $paid = $this->paidAmount();
        $balance = round(((float) $this->grand_total) - $paid, 2);

        if ($balance <= 0 || $paid >= (float) $this->grand_total) {
            return self::PAYMENT_PAID;
        }

        if ($paid > 0) {
            return self::PAYMENT_PARTIAL;
        }

        return self::PAYMENT_PENDING;
    }

    public function applyResolvedPaymentStatus(): self
    {
        $this->setAttribute('payment_status', $this->resolvePaymentStatusValue());

        return $this;
    }

    public function syncPaymentStatus(): int
    {
        $status = $this->resolvePaymentStatusValue();

        if ((int) $this->getRawOriginal('payment_status') !== $status) {
            $this->payment_status = $status;
            $this->save();
        }

        $this->setAttribute('payment_status', $status);

        return $status;
    }

    public function isFullyPaid(): bool
    {
        return $this->resolvePaymentStatusValue() === self::PAYMENT_PAID;
    }

    public function currentPaymentStatusValue(): int
    {
        return (int) ($this->attributes['payment_status'] ?? $this->getRawOriginal('payment_status') ?? self::PAYMENT_PENDING);
    }

    public static function paymentStatusRelations(): array
    {
        return [
            'payments' => function ($query) {
                $query->where('is_deleted', 0);
            },
        ];
    }

    public function setPaymentStatusAttribute($value)
    {
        $this->attributes['payment_status'] = (int) $value;
    }

    public function getPaymentStatusAttribute($value)
    {
        return match ((int) $value) {
            self::PAYMENT_PAID => 'Paid',
            self::PAYMENT_PARTIAL => 'Partial',
            default => 'Pending',
        };
    }

    public function paymentStatusBadgeClass(): string
    {
        return match ($this->currentPaymentStatusValue()) {
            self::PAYMENT_PAID => 'badge-success',
            self::PAYMENT_PARTIAL => 'badge-warning',
            default => 'badge-danger',
        };
    }

    public function isVatInclusive(): bool
    {
        return $this->vat_type === 'inclusive';
    }

    public function vatAmount(): float
    {
        $subtotal = (float) $this->sub_total;
        $rate = (float) $this->vat;

        if ($rate <= 0) {
            return 0.0;
        }

        $amount = $this->isVatInclusive()
            ? $subtotal - ($subtotal / (1 + ($rate / 100)))
            : $subtotal * ($rate / 100);

        return round($amount, 2);
    }

    public function taxableSubtotal(): float
    {
        return round(
            $this->isVatInclusive()
                ? (float) $this->sub_total - $this->vatAmount()
                : (float) $this->sub_total,
            2
        );
    }

    public function setStatusAttribute($value)
    {
        if($value==0){
            $value=0;
        }
        if($value==1){
            $value=1;
        }
        $this->attributes['status'] =$value;
    }

    public function getStatusAttribute($value)
    {
        if($value==1){
            $getVal='Active';
        }
        if($value==0){
            $getVal='In-Active';
        }
        return $getVal;
    }
}
