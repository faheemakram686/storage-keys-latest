<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceReminder;
use App\Models\InvoiceReminderLog;
use App\Models\Payment;
use App\Notifications\Backend\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\CreatesApplication;

class InvoiceReminderFlowTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    private string $stamp;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stamp = 'irtest'.uniqid();
        Carbon::setTestNow(Carbon::parse('2026-08-16')->startOfDay());
        InvoiceReminder::query()->update(['is_enabled' => 0]);
        Customer::query()->update(['invoice_reminders_enabled' => 0]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** @test */
    public function due_date_matching_covers_before_after_and_on()
    {
        $before = new InvoiceReminder(['trigger_days' => 1, 'trigger_relation' => 'before']);
        $after = new InvoiceReminder(['trigger_days' => 2, 'trigger_relation' => 'after']);
        $on = new InvoiceReminder(['trigger_days' => 0, 'trigger_relation' => 'on']);

        $this->assertTrue($before->matchesDueDate('2026-08-17', '2026-08-16'));
        $this->assertFalse($before->matchesDueDate('2026-08-17', '2026-08-17'));
        $this->assertTrue($after->matchesDueDate('2026-08-14', '2026-08-16'));
        $this->assertFalse($after->matchesDueDate('2026-08-14', '2026-08-15'));
        $this->assertTrue($on->matchesDueDate('2026-08-16', '2026-08-16'));
        $this->assertFalse($on->matchesDueDate('2026-08-15', '2026-08-16'));
    }

    /** @test */
    public function command_sends_once_when_customer_is_opted_in_and_due_date_matches()
    {
        Notification::fake();

        $customer = $this->makeCustomer(true);
        $invoice = $this->makeInvoice($customer, Carbon::parse('2026-08-17'));
        $rule = $this->makeRule(['trigger_days' => 1, 'trigger_relation' => 'before']);

        \Illuminate\Support\Facades\Artisan::call('payment:reminder');
        $this->assertStringContainsString('Invoice reminders sent: 1', \Illuminate\Support\Facades\Artisan::output());

        Notification::assertSentOnDemand(PaymentReminderNotification::class, function ($notification) use ($invoice) {
            $mail = $notification->toMail(null);

            return str_contains($mail->subject, $invoice->invoice_no)
                && str_contains($mail->viewData['body'] ?? '', 'Abrar');
        });

        $this->assertDatabaseHas('invoice_reminder_logs', [
            'invoice_id' => $invoice->id,
            'invoice_reminder_id' => $rule->id,
        ]);
        $this->assertSame(1, InvoiceReminderLog::where('invoice_id', $invoice->id)->count());

        \Illuminate\Support\Facades\Artisan::call('payment:reminder');
        $this->assertStringContainsString('Invoice reminders sent: 0', \Illuminate\Support\Facades\Artisan::output());
    }

    /** @test */
    public function command_skips_when_customer_reminders_are_disabled()
    {
        Notification::fake();

        $customer = $this->makeCustomer(false);
        $this->makeInvoice($customer, Carbon::parse('2026-08-17'));
        $this->makeRule(['trigger_days' => 1, 'trigger_relation' => 'before']);

        $this->artisan('payment:reminder')
            ->expectsOutput('Invoice reminders sent: 0')
            ->assertExitCode(0);

        Notification::assertNothingSent();
    }

    /** @test */
    public function command_skips_fully_paid_invoices()
    {
        Notification::fake();

        $customer = $this->makeCustomer(true);
        $invoice = $this->makeInvoice($customer, Carbon::parse('2026-08-17'));
        $payment = new Payment();
        $payment->customer_id = $customer->id;
        $payment->invoice_id = $invoice->id;
        $payment->payment_date = '2026-08-16';
        $payment->amount_received = 150;
        $payment->status = 1;
        $payment->is_deleted = 0;
        $payment->save();
        $this->makeRule(['trigger_days' => 1, 'trigger_relation' => 'before']);

        $this->artisan('payment:reminder')
            ->expectsOutput('Invoice reminders sent: 0')
            ->assertExitCode(0);

        Notification::assertNothingSent();
    }

    /** @test */
    public function settings_crud_and_customer_toggle_work()
    {
        $userId = DB::table('users')->orderBy('id')->value('id');
        $this->assertNotNull($userId, 'Need a user to log in');
        Auth::loginUsingId($userId);

        $page = $this->get('admin/invoice-reminders');
        $this->assertSame(200, $page->status(), $page->getContent());

        $save = $this->post('admin/save-invoice-reminder', [
            'name' => 'Flow '.$this->stamp,
            'is_enabled' => 1,
            'trigger_days' => 3,
            'trigger_relation' => 'after',
            'recipient_type' => 'customer',
            'subject' => 'Overdue {{invoice_no}}',
            'body' => '<p>Pay now {{amount_due}}</p>',
        ]);
        $save->assertOk();
        $save->assertJson(['success' => 'Record save successfully']);

        $list = $this->get('admin/get-invoice-reminders');
        $list->assertOk();
        $rows = collect($list->json());
        $this->assertTrue($rows->contains(fn ($row) => ($row['name'] ?? '') === 'Flow '.$this->stamp));

        $customer = $this->makeCustomer(false);
        $toggle = $this->post('admin/customer/toggle-invoice-reminders', [
            'id' => $customer->id,
            'enabled' => 1,
        ]);
        $toggle->assertOk();
        $toggle->assertJson(['success' => 'Invoice reminders updated', 'enabled' => true]);

        $customer->refresh();
        $this->assertSame(1, (int) $customer->getRawOriginal('invoice_reminders_enabled'));
    }

    private function makeCustomer(bool $remindersEnabled): Customer
    {
        $email = $this->stamp.uniqid().'@example.test';
        $customer = new Customer();
        $customer->customer_type = 'individual';
        $customer->customer_name = 'Abrar Amjad';
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

        return $customer->fresh(['primaryContact']);
    }

    private function makeInvoice(Customer $customer, Carbon $dueDate): Invoice
    {
        $invoice = new Invoice();
        $invoice->customer_id = $customer->id;
        $invoice->type = 'invoice';
        $invoice->invoice_no = 'INV-'.$this->stamp;
        $invoice->invoice_date = '2026-08-01';
        $invoice->due_date = $dueDate->toDateString();
        $invoice->sub_total = 150;
        $invoice->vat = 0;
        $invoice->grand_total = 150;
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
            'trigger_days' => 1,
            'trigger_relation' => 'before',
            'recipient_type' => 'customer',
            'subject' => 'Reminder {{invoice_no}}',
            'body' => '<p>Dear {{f_name}}, invoice {{invoice_no}} is due {{due_date}}.</p>',
            'status' => 1,
            'is_deleted' => 0,
        ], $overrides));
    }
}
