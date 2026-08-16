<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'invoice_reminder_id',
        'recipient_email',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function reminder()
    {
        return $this->belongsTo(InvoiceReminder::class, 'invoice_reminder_id');
    }
}
