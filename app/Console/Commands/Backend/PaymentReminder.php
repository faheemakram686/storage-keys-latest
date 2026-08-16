<?php

namespace App\Console\Commands\Backend;

use App\Models\Invoice;
use App\Models\InvoiceReminder;
use App\Models\InvoiceReminderLog;
use App\Notifications\Backend\PaymentReminderNotification;
use App\Repo\EmailTemplateClass;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class PaymentReminder extends Command
{
    protected $signature = 'payment:reminder';

    protected $description = 'Send due-date invoice reminder emails for customers with reminders enabled';

    public function handle()
    {
        $templates = new EmailTemplateClass();
        $today = Carbon::today();
        $sent = 0;

        $rules = InvoiceReminder::with('fromUser')
            ->where('is_deleted', 0)
            ->where('is_enabled', 1)
            ->get();

        if ($rules->isEmpty()) {
            $this->info('No enabled invoice reminders.');

            return 0;
        }

        $invoices = Invoice::with(array_merge([
            'customer.primaryContact',
            'userResponsible',
        ], Invoice::paymentStatusRelations()))
            ->where('is_deleted', 0)
            ->whereNotNull('due_date')
            ->whereHas('customer', function ($query) {
                $query->where('is_deleted', 0)
                    ->where('invoice_reminders_enabled', 1);
            })
            ->get();

        foreach ($invoices as $invoice) {
            $invoice->syncPaymentStatus();

            if ($invoice->isFullyPaid()) {
                continue;
            }

            foreach ($rules as $rule) {
                if (!$rule->matchesDueDate($invoice->due_date, $today)) {
                    continue;
                }

                $alreadySent = InvoiceReminderLog::where('invoice_id', $invoice->id)
                    ->where('invoice_reminder_id', $rule->id)
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                $payload = $this->buildPayload($invoice, $rule, $templates);
                if (!$payload) {
                    continue;
                }

                Notification::route('mail', $payload['to'])
                    ->notify(new PaymentReminderNotification($payload));

                InvoiceReminderLog::create([
                    'invoice_id' => $invoice->id,
                    'invoice_reminder_id' => $rule->id,
                    'recipient_email' => is_array($payload['to']) ? implode(',', $payload['to']) : $payload['to'],
                    'sent_at' => now(),
                ]);

                $sent++;
            }
        }

        $this->info("Invoice reminders sent: {$sent}");

        return 0;
    }

    protected function buildPayload(Invoice $invoice, InvoiceReminder $rule, EmailTemplateClass $templates): ?array
    {
        $customer = $invoice->customer;
        $contact = $customer?->primaryContact;
        $fromUser = $rule->fromUser ?: $invoice->userResponsible;

        $customerEmail = $contact?->email ?: $customer?->email;
        $staffEmail = $fromUser?->email;

        $to = [];
        $cc = $rule->cc_emails ?: [];

        if ($rule->recipient_type === 'me') {
            if (!$staffEmail) {
                return null;
            }
            $to[] = $staffEmail;
        } else {
            if (!$customerEmail) {
                return null;
            }
            $to[] = $customerEmail;
            if ($rule->recipient_type === 'customer_and_copy_me' && $staffEmail) {
                $cc[] = $staffEmail;
            }
        }

        $variables = $templates->buildRecipientVariables($contact ?: $customer);
        $variables = array_merge($variables, [
            'company_name' => $customer?->company_name ?: ($variables['company_name'] ?? ''),
            'invoice_no' => $invoice->invoice_no,
            'invoice_date' => $invoice->invoice_date,
            'due_date' => $invoice->due_date,
            'grand_total' => $invoice->grand_total,
            'amount_due' => $invoice->balanceAmount(),
        ]);

        if ($contact) {
            $variables['f_name'] = $contact->first_name ?? $variables['f_name'];
            $variables['l_name'] = $contact->last_name ?? $variables['l_name'];
            $variables['email'] = $contact->email ?? $variables['email'];
        }

        $body = $templates->applyTemplateVariables(html_entity_decode((string) $rule->body), $variables);
        $subject = $templates->applyTemplateVariables((string) $rule->subject, $variables);

        return [
            'to' => array_values(array_unique($to)),
            'cc' => array_values(array_unique(array_filter($cc))),
            'bcc' => $rule->bcc_emails ?: [],
            'from_email' => $staffEmail,
            'from_name' => $fromUser ? trim(($fromUser->first_name ?? '').' '.($fromUser->last_name ?? '')) : null,
            'subject' => $subject ?: 'Invoice reminder',
            'body' => $body,
        ];
    }
}
