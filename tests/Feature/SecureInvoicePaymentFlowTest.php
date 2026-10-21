<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\CustomerPaymentMethod;
use App\Models\Invoice;
use App\Models\InvoicePaymentAttempt;
use App\Models\NgeniusWebhookEvent;
use App\Models\Payment;
use App\Services\InvoiceNgeniusPaymentService;
use App\Services\NgeniusPaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\CreatesApplication;

class SecureInvoicePaymentFlowTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    private string $stamp;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stamp = 'sip'.uniqid();

        Config::set('services.ngenius.webhook_secret', 'test-webhook-secret');
        Config::set('services.ngenius.webhook_header', 'X-Webhook-Token');
        Config::set('services.ngenius.base_url', 'https://api-gateway.sandbox.ngenius-payments.com');
        Config::set('services.ngenius.outlet_reference', 'outlet-test');
        Config::set('services.ngenius.api_key', 'dGVzdA==');
        Config::set('services.ngenius.auth_url', 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token');
    }

    /** @test */
    public function older_attempt_can_still_be_confirmed_after_newer_pay_now()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 100);

        $oldRef = 'ord-old-'.$this->stamp;
        $newRef = 'ord-new-'.$this->stamp;

        app(InvoiceNgeniusPaymentService::class)->recordAttempt(
            $invoice,
            $oldRef,
            100,
            InvoicePaymentAttempt::SOURCE_HOSTED
        );
        app(InvoiceNgeniusPaymentService::class)->recordAttempt(
            $invoice->fresh(),
            $newRef,
            100,
            InvoicePaymentAttempt::SOURCE_HOSTED
        );

        $this->assertSame($newRef, $invoice->fresh()->invoice_ref);

        $this->fakeNgenius([
            '*/orders/'.$oldRef => Http::response($this->paidOrderPayload($oldRef, 10000), 200),
        ]);

        $result = app(InvoiceNgeniusPaymentService::class)->confirmByReference(
            $invoice->fresh(),
            $oldRef,
            'Paid via older tab'
        );

        $this->assertTrue($result['success'], $result['message'] ?? '');
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'gateway_order_reference' => $oldRef,
            'amount_received' => 100,
            'is_deleted' => 0,
        ]);
        $this->assertSame(InvoicePaymentAttempt::STATE_PAID, InvoicePaymentAttempt::findByOrderReference($oldRef)->state);
    }

    /** @test */
    public function webhook_and_confirm_insert_only_one_payment()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 50);
        $ref = 'ord-dup-'.$this->stamp;

        app(InvoiceNgeniusPaymentService::class)->recordAttempt(
            $invoice,
            $ref,
            50,
            InvoicePaymentAttempt::SOURCE_HOSTED
        );

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($this->paidOrderPayload($ref, 5000), 200),
        ]);

        $service = app(InvoiceNgeniusPaymentService::class);
        $first = $service->confirmByReference($invoice->fresh(), $ref, 'First');
        $second = $service->confirmByReference($invoice->fresh(), $ref, 'Second');

        $this->assertTrue($first['success'], $first['message'] ?? '');
        $this->assertTrue($second['success'], $second['message'] ?? '');
        $this->assertSame('Payment already recorded', $second['message']);
        $this->assertSame(1, Payment::where('gateway_order_reference', $ref)->where('is_deleted', 0)->count());
    }

    /** @test */
    public function paid_looking_webhook_body_does_not_mark_paid_when_live_order_is_unpaid()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 80);
        $ref = 'ord-forge-'.$this->stamp;

        app(InvoiceNgeniusPaymentService::class)->recordAttempt(
            $invoice,
            $ref,
            80,
            InvoicePaymentAttempt::SOURCE_HOSTED
        );

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($this->pendingOrderPayload($ref), 200),
        ]);

        $response = $this->postJson('/webhooks/ngenius', [
            'eventId' => 'evt-forge-'.$this->stamp,
            'eventName' => 'PURCHASED',
            'order' => $this->paidOrderPayload($ref, 8000),
        ], [
            'X-Webhook-Token' => 'test-webhook-secret',
        ]);

        $response->assertOk();
        $this->assertSame(0, Payment::where('invoice_id', $invoice->id)->where('is_deleted', 0)->count());
        $this->assertFalse($invoice->fresh()->isFullyPaid());
    }

    /** @test */
    public function failed_confirm_returns_500_and_retry_records_payment()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 60);
        $ref = 'ord-retry-'.$this->stamp;
        $eventId = 'evt-retry-'.$this->stamp;

        app(InvoiceNgeniusPaymentService::class)->recordAttempt(
            $invoice,
            $ref,
            60,
            InvoicePaymentAttempt::SOURCE_HOSTED
        );

        $call = 0;
        Http::fake(function ($request) use (&$call, $ref) {
            if (str_contains($request->url(), 'access-token')) {
                return Http::response([
                    'access_token' => 'test-token',
                    'expires_in' => 300,
                ], 200);
            }

            if (str_contains($request->url(), '/orders/'.$ref)) {
                $call++;
                if ($call === 1) {
                    return Http::response(['error' => 'unavailable'], 503);
                }

                return Http::response($this->paidOrderPayload($ref, 6000), 200);
            }

            return Http::response(['ok' => true], 200);
        });

        $first = $this->postJson('/webhooks/ngenius', [
            'eventId' => $eventId,
            'eventName' => 'CAPTURED',
            'order' => ['reference' => $ref],
        ], [
            'X-Webhook-Token' => 'test-webhook-secret',
        ]);

        $first->assertStatus(500);
        $this->assertSame(NgeniusWebhookEvent::STATUS_FAILED, NgeniusWebhookEvent::where('event_id', $eventId)->value('status'));
        $this->assertSame(0, Payment::where('gateway_order_reference', $ref)->count());

        $second = $this->postJson('/webhooks/ngenius', [
            'eventId' => $eventId,
            'eventName' => 'CAPTURED',
            'order' => ['reference' => $ref],
        ], [
            'X-Webhook-Token' => 'test-webhook-secret',
        ]);

        $second->assertOk();
        $this->assertSame(NgeniusWebhookEvent::STATUS_PROCESSED, NgeniusWebhookEvent::where('event_id', $eventId)->value('status'));
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'gateway_order_reference' => $ref,
            'amount_received' => 60,
        ]);
    }

    /** @test */
    public function card_without_recurring_consent_is_not_charged()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 40);

        CustomerPaymentMethod::create([
            'customer_id' => $customer->id,
            'card_token' => 'tok_'.$this->stamp,
            'masked_pan' => '4111********1111',
            'expiry' => '12/30',
            'scheme' => 'VISA',
            'cardholder_name' => 'Test User',
            'is_active' => true,
            'recurring_consent_at' => null,
        ]);

        Http::fake();

        $result = app(InvoiceNgeniusPaymentService::class)->chargeRecurringInvoice($invoice);

        $this->assertFalse($result['success']);
        $this->assertTrue($result['skipped']);
        $this->assertSame('No chargeable saved card', $result['message']);
        Http::assertNothingSent();
    }

    /** @test */
    public function recurring_saved_card_charge_records_payment_when_captured()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 90);
        $ref = 'ord-recurring-'.$this->stamp;

        CustomerPaymentMethod::create([
            'customer_id' => $customer->id,
            'card_token' => 'tok_rec_'.$this->stamp,
            'masked_pan' => '4111********1111',
            'expiry' => '2030-12',
            'scheme' => 'VISA',
            'cardholder_name' => 'Pay Test',
            'is_active' => true,
            'recurring_consent_at' => now(),
        ]);

        Http::fake(function ($request) use ($ref) {
            $url = $request->url();

            if (str_contains($url, 'access-token')) {
                return Http::response([
                    'access_token' => 'test-token',
                    'expires_in' => 300,
                ], 200);
            }

            if ($request->method() === 'POST' && str_contains($url, '/orders')) {
                return Http::response([
                    'reference' => $ref,
                    '_embedded' => [
                        'payment' => [[
                            'state' => 'STARTED',
                            '_links' => [
                                'payment:saved-card' => [
                                    'href' => 'https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/outlet-test/orders/'.$ref.'/payments/pay1/saved-card',
                                ],
                            ],
                        ]],
                    ],
                    '_links' => [
                        'payment' => ['href' => 'https://paypage.sandbox.ngenius-payments.com/?code=abc'],
                    ],
                ], 200);
            }

            if ($request->method() === 'PUT' && str_contains($url, 'saved-card')) {
                return Http::response([
                    'state' => 'CAPTURED',
                    'orderReference' => $ref,
                    'amount' => ['currencyCode' => 'AED', 'value' => 9000],
                ], 200);
            }

            if (str_contains($url, '/orders/'.$ref)) {
                return Http::response($this->paidOrderPayload($ref, 9000), 200);
            }

            return Http::response(['ok' => true], 200);
        });

        $result = app(InvoiceNgeniusPaymentService::class)->chargeRecurringInvoice($invoice->fresh());

        $this->assertTrue($result['success'], $result['message'] ?? '');
        $this->assertFalse($result['skipped'] ?? true);
        $this->assertSame('CAPTURED', $result['payment_state'] ?? null);
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount_received' => 90,
            'is_deleted' => 0,
        ]);
        $this->assertTrue($invoice->fresh()->isFullyPaid());
    }

    /** @test */
    public function payment_status_endpoint_returns_paid_pending_or_failed_only()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 25);
        $ref = 'ord-status-'.$this->stamp;

        app(InvoiceNgeniusPaymentService::class)->recordAttempt(
            $invoice,
            $ref,
            25,
            InvoicePaymentAttempt::SOURCE_HOSTED
        );

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($this->paidOrderPayload($ref, 2500), 200),
        ]);

        $response = $this->getJson('/payment-status/'.$ref);
        $response->assertOk();
        $response->assertJsonStructure(['status', 'message']);
        $this->assertContains($response->json('status'), ['paid', 'pending', 'failed']);
        $this->assertSame('paid', $response->json('status'));
    }

    private function fakeNgenius(array $extra = []): void
    {
        Http::fake(array_merge([
            '*/identity/auth/access-token' => Http::response([
                'access_token' => 'test-token',
                'expires_in' => 300,
            ], 200),
        ], $extra, [
            // Catch-all last so specific order stubs win.
            '*' => Http::response(['ok' => true], 200),
        ]));
    }

    private function makeCustomer(): Customer
    {
        $email = $this->stamp.uniqid().'@example.test';
        $customer = new Customer();
        $customer->customer_type = 'individual';
        $customer->customer_name = 'Pay Test';
        $customer->email = $email;
        $customer->status = 1;
        $customer->is_deleted = 0;
        $customer->save();

        Contact::create([
            'customer_id' => $customer->id,
            'first_name' => 'Pay',
            'last_name' => 'Test',
            'email' => $email,
            'contact_type' => 'primary',
            'status' => 1,
            'is_deleted' => 0,
        ]);

        return $customer->fresh(['primaryContact']);
    }

    private function makeInvoice(Customer $customer, float $total): Invoice
    {
        $invoice = new Invoice();
        $invoice->customer_id = $customer->id;
        $invoice->type = 'invoice';
        $invoice->invoice_no = 'INV-'.$this->stamp.uniqid();
        $invoice->invoice_date = now()->toDateString();
        $invoice->due_date = now()->addDays(7)->toDateString();
        $invoice->sub_total = $total;
        $invoice->vat = 0;
        $invoice->grand_total = $total;
        $invoice->status = 1;
        $invoice->is_deleted = 0;
        $invoice->payment_status = Invoice::PAYMENT_PENDING;
        $invoice->save();

        return $invoice;
    }

    private function paidOrderPayload(string $reference, int $minorUnits): array
    {
        return [
            'reference' => $reference,
            'amount' => [
                'currencyCode' => 'AED',
                'value' => $minorUnits,
            ],
            '_embedded' => [
                'payment' => [
                    [
                        'state' => 'CAPTURED',
                        'amount' => [
                            'currencyCode' => 'AED',
                            'value' => $minorUnits,
                        ],
                    ],
                ],
            ],
        ];
    }

    private function pendingOrderPayload(string $reference): array
    {
        return [
            'reference' => $reference,
            'amount' => [
                'currencyCode' => 'AED',
                'value' => 1000,
            ],
            '_embedded' => [
                'payment' => [
                    [
                        'state' => 'STARTED',
                        'amount' => [
                            'currencyCode' => 'AED',
                            'value' => 1000,
                        ],
                    ],
                ],
            ],
        ];
    }
}
