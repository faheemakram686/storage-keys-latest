<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_enabled',
        'trigger_days',
        'trigger_relation',
        'recipient_type',
        'from_user_id',
        'cc_emails',
        'bcc_emails',
        'subject',
        'body',
        'status',
        'is_deleted',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'cc_emails' => 'array',
        'bcc_emails' => 'array',
        'created_at' => 'datetime:Y-m-d H:00',
    ];

    protected $appends = ['summary', 'recipient_label'];

    public function fromUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'from_user_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(InvoiceReminderLog::class, 'invoice_reminder_id');
    }

    public function getRecipientLabelAttribute(): string
    {
        return match ($this->recipient_type) {
            'me' => 'me',
            'customer_and_copy_me' => 'customer and copy me',
            default => 'customer',
        };
    }

    public function getSummaryAttribute(): string
    {
        $days = (int) $this->trigger_days;
        $relation = ucfirst((string) $this->trigger_relation);

        return 'Remind '.$this->recipient_label.' '.$days.' day(s) '.$relation.' due date';
    }

    public function matchesDueDate($dueDate, $today = null): bool
    {
        if (!$dueDate) {
            return false;
        }

        $today = Carbon::parse($today ?? now())->startOfDay();
        $due = Carbon::parse($dueDate)->startOfDay();
        $days = max(0, (int) $this->trigger_days);

        $target = match ($this->trigger_relation) {
            'before' => $due->copy()->subDays($days),
            'after' => $due->copy()->addDays($days),
            default => $due->copy(),
        };

        return $today->equalTo($target);
    }
}
