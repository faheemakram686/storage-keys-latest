<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\NgeniusWebhookEvent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\NgeniusPaymentReconciliationService;
use App\Services\ShopOrderSettlementService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\CreatesApplication;

class ShopOrderSettlementTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    private string $stamp;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stamp = 'sos'.uniqid();

        Config::set('services.ngenius.webhook_secret', 'test-webhook-secret');
        Config::set('services.ngenius.webhook_header', 'X-Webhook-Token');
        Config::set('services.ngenius.base_url', 'https://api-gateway.sandbox.ngenius-payments.com');
        Config::set('services.ngenius.outlet_reference', 'outlet-test');
        Config::set('services.ngenius.api_key', 'dGVzdA==');
        Config::set('services.ngenius.auth_url', 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token');
    }

    /** @test */
    public function paid_shop_redirect_creates_invoice_and_payment()
    {
        $customer = $this->makeCustomer();
        $ref = 'shop-ref-'.$this->stamp;
        $order = $this->makeShopOrder($customer, 75, $ref, 0);

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($this->paidOrderPayload($ref, 7500), 200),
        ]);

        $response = $this->get('/redirectPaymentRef?ref='.$ref);
        $response->assertRedirect(route('checkout'));

        $this->assertSame(1, (int) $order->fresh()->getRawOriginal('status'));
        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'type' => 'order',
            'invoice_ref' => $ref,
            'recurring' => '0',
            'is_deleted' => 0,
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway_order_reference' => $ref,
            'amount_received' => 75,
            'is_deleted' => 0,
        ]);
        $this->assertSame(1, Invoice::where('order_id', $order->id)->where('is_deleted', 0)->count());
        $this->assertSame(1, Payment::where('gateway_order_reference', $ref)->where('is_deleted', 0)->count());
    }

    /** @test */
    public function settlement_is_idempotent()
    {
        $customer = $this->makeCustomer();
        $ref = 'shop-idem-'.$this->stamp;
        $order = $this->makeShopOrder($customer, 40, $ref, 0);
        $payload = $this->paidOrderPayload($ref, 4000);

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($payload, 200),
        ]);

        $service = app(ShopOrderSettlementService::class);
        $first = $service->settlePaidOrder($order, $payload);
        $second = $service->settlePaidOrder($order->fresh(), $payload);

        $this->assertTrue($first['success'], $first['message'] ?? '');
        $this->assertTrue($second['success'], $second['message'] ?? '');
        $this->assertSame(1, Invoice::where('order_id', $order->id)->where('is_deleted', 0)->count());
        $this->assertSame(1, Payment::where('gateway_order_reference', $ref)->where('is_deleted', 0)->count());
    }

    /** @test */
    public function shop_webhook_settles_order_without_invoice_attempt()
    {
        $customer = $this->makeCustomer();
        $ref = 'shop-wh-'.$this->stamp;
        $order = $this->makeShopOrder($customer, 55, $ref, 0);
        $eventId = 'evt-shop-'.$this->stamp;

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($this->paidOrderPayload($ref, 5500), 200),
        ]);

        $response = $this->postJson('/webhooks/ngenius', [
            'eventId' => $eventId,
            'eventName' => 'PURCHASED',
            'order' => ['reference' => $ref],
        ], [
            'X-Webhook-Token' => 'test-webhook-secret',
        ]);

        $response->assertOk();
        $this->assertSame(NgeniusWebhookEvent::STATUS_PROCESSED, NgeniusWebhookEvent::where('event_id', $eventId)->value('status'));
        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'invoice_ref' => $ref,
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway_order_reference' => $ref,
            'amount_received' => 55,
        ]);
        $this->assertSame(1, (int) $order->fresh()->getRawOriginal('status'));
    }

    /** @test */
    public function cash_on_delivery_order_does_not_create_invoice()
    {
        $customer = $this->makeCustomer();
        $order = $this->makeShopOrder($customer, 30, null, 0);
        $order->payment_method = 'cod';
        $order->save();

        $result = app(ShopOrderSettlementService::class)->settlePaidOrder($order);

        $this->assertFalse($result['success']);
        $this->assertSame(0, Invoice::where('order_id', $order->id)->count());
        $this->assertSame(0, Payment::where('order_id', $order->id)->count());
        $this->assertSame(0, (int) $order->fresh()->getRawOriginal('status'));
    }

    /** @test */
    public function reconcile_settles_paid_order_without_browser_return()
    {
        $customer = $this->makeCustomer();
        $ref = 'shop-noreturn-'.$this->stamp;
        // Customer paid on N-Genius but never clicked Return — order still unpaid in DB.
        $order = $this->makeShopOrder($customer, 90, $ref, 0);

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($this->paidOrderPayload($ref, 9000), 200),
        ]);

        $result = app(NgeniusPaymentReconciliationService::class)->reconcile(7, 50);

        $this->assertSame(1, $result['orders_settled']);
        $this->assertSame(1, (int) $order->fresh()->getRawOriginal('status'));
        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'invoice_ref' => $ref,
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway_order_reference' => $ref,
            'amount_received' => 90,
        ]);
    }

    /** @test */
    public function admin_order_detail_settles_unpaid_order_that_gateway_already_paid()
    {
        $customer = $this->makeCustomer();
        $ref = 'shop-admin-'.$this->stamp;
        $order = $this->makeShopOrder($customer, 45, $ref, 0);

        $this->fakeNgenius([
            '*/orders/'.$ref => Http::response($this->paidOrderPayload($ref, 4500), 200),
        ]);

        $result = app(ShopOrderSettlementService::class)->settleIfMissingInvoice($order);

        $this->assertTrue($result['success'], $result['message'] ?? '');
        $this->assertSame(1, (int) $order->fresh()->getRawOriginal('status'));
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway_order_reference' => $ref,
        ]);
    }

    private function fakeNgenius(array $extra = []): void
    {
        Http::fake(array_merge([
            '*/identity/auth/access-token' => Http::response([
                'access_token' => 'test-token',
                'expires_in' => 300,
            ], 200),
        ], $extra, [
            '*' => Http::response(['ok' => true], 200),
        ]));
    }

    private function makeCustomer(): Customer
    {
        $email = $this->stamp.uniqid().'@example.test';
        $customer = new Customer();
        $customer->customer_type = 'individual';
        $customer->customer_name = 'Shop Test';
        $customer->email = $email;
        $customer->status = 1;
        $customer->is_deleted = 0;
        $customer->save();

        Contact::create([
            'customer_id' => $customer->id,
            'first_name' => 'Shop',
            'last_name' => 'Test',
            'email' => $email,
            'contact_type' => 'primary',
            'status' => 1,
            'is_deleted' => 0,
        ]);

        return $customer->fresh(['primaryContact']);
    }

    private function makeShopOrder(Customer $customer, float $total, ?string $gatewayReference, int $status): Order
    {
        $product = new Product();
        $product->p_name = 'Shop Product '.$this->stamp;
        $product->sell_price = $total;
        $product->status = 1;
        $product->save();

        $order = new Order();
        $order->customer_id = $customer->id;
        $order->payment_method = $gatewayReference ? 'online' : 'cod';
        $order->gateway_reference = $gatewayReference;
        $order->sub_amount = (int) $total;
        $order->total_amount = (int) $total;
        $order->status = $status;
        $order->is_deleted = 0;
        $order->save();

        $item = new OrderItem();
        $item->order_id = $order->id;
        $item->product_id = $product->id;
        $item->item_name = $product->p_name;
        $item->qty = 1;
        $item->price = (int) $total;
        $item->total = (int) $total;
        $item->status = 1;
        $item->is_deleted = 0;
        $item->save();

        return $order->fresh(['orderItems']);
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
}
