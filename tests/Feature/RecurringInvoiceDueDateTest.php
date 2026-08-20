<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceReminder;
use App\Models\InvoiceReminderLog;
use App\Notifications\Backend\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\CreatesApplication;

class RecurringInvoiceDueDateTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    private string $stamp;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stamp = 'ridue'.uniqid();
        Carbon::setTestNow(Carbon::parse('2026-02-01')->startOfDay());
        InvoiceReminder::query()->update(['is_enabled' => 0]);
        Customer::query()->update(['invoice_reminders_enabled' => 0]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** @test */
    public function child_due_date_preserves_parent_payment_term_days()
    {
        $parent = $this->makeRecurringParent('2026-01-01', '2026-01-15');

        Artisan::call('invoice:recurring');

        $child = Invoice::where('parent_invoice_id', $parent->id)->first();
        $this->assertNotNull($child);
        $this->assertSame('2026-02-01', $child->invoice_date);
        $this->assertSame('2026-02-15', $child->due_date);
        $this->assertSame('0', (string) $child->recurring);

        $parent->refresh();
        $this->assertSame('2026-03-01', $parent->next_recurring_date);
    }

    /** @test */
    public function child_due_date_matches_invoice_date_when_parent_terms_are_same_day()
    {
        $parent = $this->makeRecurringParent('2026-01-01', '2026-01-01');

        Artisan::call('invoice:recurring');

        $child = Invoice::where('parent_invoice_id', $parent->id)->first();
        $this->assertNotNull($child);
        $this->assertSame('2026-02-01', $child->invoice_date);
        $this->assertSame('2026-02-01', $child->due_date);
    }

    /** @test */
    public function reminder_matches_child_due_date_using_payment_term_offset()
    {
        $parent = $this->makeRecurringParent('2026-01-01', '2026-01-15');

        Artisan::call('invoice:recurring');

        $child = Invoice::where('parent_invoice_id', $parent->id)->first();
        $this->assertNotNull($child);
        $this->assertSame('2026-02-15', $child->due_date);

        $rule = new InvoiceReminder([
            'trigger_days' => 14,
            'trigger_relation' => 'before',
        ]);

        $this->assertTrue($rule->matchesDueDate($child->due_date, '2026-02-01'));
        $this->assertFalse($rule->matchesDueDate($parent->due_date, '2026-02-01'));
    }

    /**
     * Full flow: generate recurring child (due = invoice + 14), then payment:reminder
     * with a "14 days before due" rule sends once for the child.
     *
     * @test
     */
    public function recurring_child_triggers_payment_reminder_from_settings()
    {
        Notification::fake();

        $parent = $this->makeRecurringParent('2026-01-01', '2026-01-15', true);
        $rule = $this->makeRule([
            'trigger_days' => 14,
            'trigger_relation' => 'before',
        ]);

        Artisan::call('invoice:recurring');
        $this->assertStringContainsString('Generated 1 recurring invoice(s).', Artisan::output());

        $child = Invoice::where('parent_invoice_id', $parent->id)->first();
        $this->assertNotNull($child);
        $this->assertSame('2026-02-01', $child->invoice_date);
        $this->assertSame('2026-02-15', $child->due_date);

        Artisan::call('payment:reminder');
        $this->assertStringContainsString('Invoice reminders sent: 1', Artisan::output());

        Notification::assertSentOnDemand(PaymentReminderNotification::class, function ($notification) use ($child) {
            $mail = $notification->toMail(null);

            return str_contains($mail->subject, $child->invoice_no)
                && str_contains($mail->viewData['body'] ?? '', '2026-02-15');
        });

        $this->assertDatabaseHas('invoice_reminder_logs', [
            'invoice_id' => $child->id,
            'invoice_reminder_id' => $rule->id,
        ]);
        $this->assertSame(0, InvoiceReminderLog::where('invoice_id', $parent->id)->count());

        Artisan::call('payment:reminder');
        $this->assertStringContainsString('Invoice reminders sent: 0', Artisan::output());
    }

    private function makeRecurringParent(string $invoiceDate, string $dueDate, bool $remindersEnabled = false): Invoice
    {
        $email = $this->stamp.uniqid().'@example.test';
        $customer = new Customer();
        $customer->customer_type = 'individual';
        $customer->customer_name = 'Recurring Due '.$this->stamp;
        $customer->email = $email;
        $customer->status = 1;
        $customer->is_deleted = 0;
        $customer->invoice_reminders_enabled = $remindersEnabled ? 1 : 0;
        $customer->save();

        Contact::create([
            'customer_id' => $customer->id,
            'first_name' => 'Abrar',
            'last_name' => 'Amjad',
            'email' => $email,
            'contact_type' => 'primary',
            'status' => 1,
            'is_deleted' => 0,
        ]);

        $invoice = new Invoice();
        $invoice->customer_id = $customer->id;
        $invoice->type = 'invoice';
        $invoice->invoice_no = 'INV-'.$this->stamp;
        $invoice->invoice_date = $invoiceDate;
        $invoice->due_date = $dueDate;
        $invoice->recurring = '1';
        $invoice->no_cycle = 'infinity';
        $invoice->next_recurring_date = '2026-02-01';
        $invoice->sub_total = 100;
        $invoice->vat = 0;
        $invoice->grand_total = 100;
        $invoice->status = 1;
        $invoice->is_deleted = 0;
        $invoice->payment_status = Invoice::PAYMENT_PENDING;
        $invoice->save();

        return $invoice;
    }

    private function makeRule(array $overrides = []): InvoiceReminder
    {
        return InvoiceReminder::create(array_merge([
            'name' => 'Rule '.$this->stamp,
            'is_enabled' => 1,
            'trigger_days' => 14,
            'trigger_relation' => 'before',
            'recipient_type' => 'customer',
            'subject' => 'Reminder {{invoice_no}}',
            'body' => '<p>Dear {{f_name}}, invoice {{invoice_no}} is due {{due_date}}.</p>',
            'status' => 1,
            'is_deleted' => 0,
        ], $overrides));
    }
}
