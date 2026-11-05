<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Contract;
use App\Models\Core\Auth\User;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\MoveInRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiDataAccessTest extends TestCase
{
    use DatabaseTransactions;

    private Customer $mine;
    private Customer $other;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mine = $this->makeCustomer('Mine');
        $this->other = $this->makeCustomer('Other');
    }

    /** @test */
    public function customer_profile_ignores_another_customers_id()
    {
        $this->actingAsCustomer($this->mine);

        $this->postJson('/customer-api/profile', ['id' => $this->other->id])
            ->assertOk()
            ->assertJsonPath('data.id', $this->mine->id);
    }

    /** @test */
    public function customer_dashboard_ignores_another_customers_id()
    {
        $invoice = $this->makeInvoice($this->other);
        $this->actingAsCustomer($this->mine);

        $response = $this->postJson('/customer-api/customer', ['id' => $this->other->id])->assertOk();

        $this->assertNotSame($invoice->id, $response->json('data.invoice.id'));
    }

    /** @test */
    public function customer_invoice_list_only_returns_own_invoices()
    {
        $mine = $this->makeInvoice($this->mine);
        $theirs = $this->makeInvoice($this->other);
        $this->actingAsCustomer($this->mine);

        $ids = collect($this->postJson('/customer-api/invoice-list', ['customer_id' => $this->other->id])
            ->assertOk()
            ->json('Invoices'))->pluck('id');

        $this->assertTrue($ids->contains($mine->id));
        $this->assertFalse($ids->contains($theirs->id));
    }

    /** @test */
    public function customer_contract_list_only_returns_own_contracts()
    {
        $mine = $this->makeContract($this->mine);
        $theirs = $this->makeContract($this->other);
        $this->actingAsCustomer($this->mine);

        $ids = collect($this->postJson('/customer-api/contract-list', ['customer_id' => $this->other->id])
            ->assertOk()
            ->json('Contracts'))->pluck('id');

        $this->assertTrue($ids->contains($mine));
        $this->assertFalse($ids->contains($theirs));
    }

    /** @test */
    public function customer_payment_list_only_returns_own_payments()
    {
        $this->insertRow('payments', ['customer_id' => $this->mine->id, 'is_deleted' => 0]);
        $theirs = $this->insertRow('payments', ['customer_id' => $this->other->id, 'is_deleted' => 0]);
        $this->actingAsCustomer($this->mine);

        $response = $this->postJson('/customer-api/payment-list', ['customer_id' => $this->other->id])->assertOk();

        $this->assertStringNotContainsString('"id":'.$theirs.',', $response->getContent());
        $this->assertStringContainsString('"customer_id":'.$this->mine->id, $response->getContent());
    }

    /** @test */
    public function move_in_request_is_refused_for_another_customers_contract()
    {
        $theirs = $this->makeContract($this->other);
        $this->actingAsCustomer($this->mine);

        $this->postJson('/customer-api/move-in-request', [
            'customer_id' => $this->other->id,
            'contract_id' => $theirs,
            'date' => now()->toDateString(),
        ])->assertNotFound();

        $this->assertFalse(MoveInRequest::where('contract_id', $theirs)->exists());
    }

    /** @test */
    public function move_in_request_is_saved_for_own_contract()
    {
        $mine = $this->makeContract($this->mine);
        $this->actingAsCustomer($this->mine);

        $this->postJson('/customer-api/move-in-request', [
            'customer_id' => $this->other->id,
            'contract_id' => $mine,
            'date' => now()->toDateString(),
        ])->assertOk();

        $this->assertSame(
            $this->mine->id,
            (int) MoveInRequest::where('contract_id', $mine)->value('customer_id')
        );
    }

    /** @test */
    public function customer_token_is_refused_on_the_employee_api()
    {
        $this->actingAsCustomer($this->mine);

        $this->getJson('/api/getUserProfile')->assertForbidden();
        $this->postJson('/api/user', ['id' => User::query()->value('id')])->assertForbidden();
    }

    /** @test */
    public function employee_token_is_refused_on_the_customer_api()
    {
        Sanctum::actingAs($this->makeEmployee());

        $this->postJson('/customer-api/profile', ['id' => $this->mine->id])->assertForbidden();
    }

    /** @test */
    public function employee_user_endpoint_only_returns_the_logged_in_employee()
    {
        $me = $this->makeEmployee();
        $someoneElse = $this->makeEmployee();
        Sanctum::actingAs($me);

        $this->postJson('/api/user', ['id' => $someoneElse->id])
            ->assertOk()
            ->assertJsonPath('id', $me->id);
    }

    /** @test */
    public function attendance_log_is_not_returned_for_another_employee()
    {
        $me = $this->makeEmployee();
        $someoneElse = $this->makeEmployee();
        $mine = $this->makeAttendance($me);
        $theirs = $this->makeAttendance($someoneElse);
        Sanctum::actingAs($me);

        $this->postJson('/api/attendanceslog', ['attendance_id' => $mine])
            ->assertOk()
            ->assertJsonCount(1);
        $this->postJson('/api/attendanceslog', ['attendance_id' => $theirs])
            ->assertOk()
            ->assertJsonCount(0);
    }

    /** @test */
    public function customer_rate_limits_do_not_share_the_api_group_counter()
    {
        $expected = [
            'customer-api/customer/register' => 'throttle:5,1,customer-register',
            'customer-api/customer/login' => 'throttle:10,1,customer-login',
            'customer-api/customer/forgot-password' => 'throttle:5,1,customer-forgot-password',
            'customer-api/delete-account' => 'throttle:5,1,customer-delete-account',
        ];

        foreach ($expected as $uri => $throttle) {
            $route = collect(Route::getRoutes())->first(fn ($r) => $r->uri() === $uri);
            $this->assertContains($throttle, $route->gatherMiddleware(), $uri);
        }
    }

    private function actingAsCustomer(Customer $customer): void
    {
        Sanctum::actingAs(Contact::where('customer_id', $customer->id)->firstOrFail());
    }

    private function makeCustomer(string $name): Customer
    {
        $email = 'api'.uniqid().'@example.test';
        $customer = new Customer();
        $customer->customer_type = 'individual';
        $customer->customer_name = $name;
        $customer->email = $email;
        $customer->status = 1;
        $customer->is_deleted = 0;
        $customer->save();

        Contact::create([
            'customer_id' => $customer->id,
            'first_name' => $name,
            'last_name' => 'Test',
            'email' => $email,
            'status' => 1,
        ]);

        return $customer;
    }

    private function makeInvoice(Customer $customer): Invoice
    {
        $invoice = new Invoice();
        $invoice->customer_id = $customer->id;
        $invoice->type = 'invoice';
        $invoice->invoice_no = 'INV-'.uniqid();
        $invoice->invoice_date = now()->toDateString();
        $invoice->due_date = now()->addDays(7)->toDateString();
        $invoice->sub_total = 100;
        $invoice->vat = 0;
        $invoice->grand_total = 100;
        $invoice->status = 1;
        $invoice->is_deleted = 0;
        $invoice->payment_status = Invoice::PAYMENT_PENDING;
        $invoice->save();

        return $invoice;
    }

    private function makeContract(Customer $customer): int
    {
        return $this->insertRow((new Contract())->getTable(), [
            'customer_id' => $customer->id,
            'is_deleted' => 0,
        ]);
    }

    private function makeEmployee(): User
    {
        return User::findOrFail($this->insertRow('users', [
            'first_name' => 'Api',
            'last_name' => 'Test',
            'email' => 'employee'.uniqid().'@example.test',
            'password' => 'not-used',
        ]));
    }

    private function makeAttendance(User $user): int
    {
        $attendanceId = $this->insertRow('attendances', [
            'user_id' => $user->id,
            'in_date' => now()->toDateString(),
        ]);
        $this->insertRow('attendance_details', [
            'attendance_id' => $attendanceId,
            'in_time' => now(),
        ]);

        return $attendanceId;
    }

    /**
     * Inserts a row, filling any required column the test does not care about.
     */
    private function insertRow(string $table, array $values): int
    {
        foreach (DB::select("SHOW COLUMNS FROM `$table`") as $column) {
            if (
                array_key_exists($column->Field, $values)
                || $column->Null === 'YES'
                || $column->Default !== null
                || str_contains($column->Extra, 'auto_increment')
            ) {
                continue;
            }

            $type = strtolower($column->Type);
            if (str_contains($type, 'date') || str_contains($type, 'time')) {
                $values[$column->Field] = now();
            } elseif (preg_match('/int|decimal|double|float/', $type)) {
                $values[$column->Field] = 1;
            } else {
                $values[$column->Field] = 'test';
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $id = DB::table($table)->insertGetId($values);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return $id;
    }
}
