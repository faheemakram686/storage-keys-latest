<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

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
        'invoice_date',
        'invoice_no',
        'user_id',
        'sub_total',
        'vat',
        'grand_total',
        'due_date',
        'note',
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
