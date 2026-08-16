<?php

namespace App\Repo;

use App\Models\InvoiceReminder;
use App\Repo\Interfaces\InvoiceReminderInterface;

class InvoiceReminderClass implements InvoiceReminderInterface
{
    public function saveInvoiceReminder($request)
    {
        $reminder = new InvoiceReminder();
        $this->fillReminder($reminder, $request);

        if ($reminder->save()) {
            return response()->json(['success' => 'Record save successfully'], 200);
        }

        return response()->json(['errors' => 'Unable to save reminder'], 200);
    }

    public function getAllInvoiceReminders()
    {
        return InvoiceReminder::with('fromUser')
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function deleteInvoiceReminder($id)
    {
        $reminder = InvoiceReminder::find($id);
        if (!$reminder) {
            return 0;
        }

        $reminder->is_deleted = 1;
        $reminder->save();

        return 1;
    }

    public function editInvoiceReminder($id)
    {
        return InvoiceReminder::with('fromUser')->find($id);
    }

    public function updateInvoiceReminder($request)
    {
        $reminder = InvoiceReminder::find($request->id);
        if (!$reminder) {
            return 0;
        }

        $this->fillReminder($reminder, $request);
        $reminder->save();

        return 1;
    }

    public function toggleInvoiceReminder($id, $enabled)
    {
        $reminder = InvoiceReminder::find($id);
        if (!$reminder) {
            return 0;
        }

        $reminder->is_enabled = (int) $enabled ? 1 : 0;
        $reminder->save();

        return 1;
    }

    protected function fillReminder(InvoiceReminder $reminder, $request): void
    {
        $reminder->name = $request->name;
        $enabled = $request->is_enabled ?? 0;
        $reminder->is_enabled = in_array($enabled, [1, '1', true, 'true', 'on'], true) ? 1 : 0;
        $reminder->trigger_days = max(0, (int) $request->trigger_days);
        $reminder->trigger_relation = in_array($request->trigger_relation, ['before', 'after', 'on'], true)
            ? $request->trigger_relation
            : 'before';
        $reminder->recipient_type = in_array($request->recipient_type, ['customer', 'me', 'customer_and_copy_me'], true)
            ? $request->recipient_type
            : 'customer';
        $reminder->from_user_id = $request->from_user_id ?: null;
        $reminder->cc_emails = $this->parseEmailList($request->cc_emails);
        $reminder->bcc_emails = $this->parseEmailList($request->bcc_emails);
        $reminder->subject = $request->subject;
        $reminder->body = $request->body;
        $reminder->status = 1;
    }

    protected function parseEmailList($value): array
    {
        if (is_array($value)) {
            $parts = $value;
        } else {
            $parts = preg_split('/[,;\s]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        }

        return array_values(array_unique(array_filter(array_map(function ($email) {
            return strtolower(trim((string) $email));
        }, $parts), function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        })));
    }
}
