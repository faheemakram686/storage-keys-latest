<?php

namespace Tests\Feature\MultiUnit;

use App\Enums\StorageUnitStatus;
use App\Models\Contract;
use App\Models\ContractStorageUnit;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\EstimateStorageUnit;
use App\Models\Invoice;
use App\Models\MoveIn;
use App\Models\MoveOut;
use App\Models\Payment;
use App\Models\StorageUnit;
use App\Models\StorageUnitStatusLog;
use App\Models\TermLength;
use App\Repo\ContractClass;
use App\Repo\EstimateClass;
use App\Repo\InvoiceClass;
use App\Repo\StorageUnitClass;
use App\Services\StorageUnitStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * QA: Storage unit status lifecycle & exclusivity
 */
class StorageUnitStatusQaTest extends MultiUnitTestCase
{
    /** @var array<int> */
    protected $unitIds = [];

    /** @var TermLength|null */
    protected $term;

    /** @var Customer|null */
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $units = StorageUnit::where('is_deleted', 0)->orderBy('id')->take(2)->get();
        $this->assertGreaterThanOrEqual(2, $units->count(), 'Need at least 2 active storage units');
        $this->unitIds = $units->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();

        // Isolate status for tests
        foreach ($this->unitIds as $id) {
            StorageUnit::where('id', $id)->update([
                'status' => StorageUnitStatus::VACANT,
                'is_maintenance' => 0,
                'occupied_by_customer_id' => null,
                'active_contract_id' => null,
            ]);
            DB::table('storage_unit_assignments')->where('storage_unit_id', $id)->delete();
            DB::table('contract_storage_units')->where('storage_unit_id', $id)->delete();
        }

        $this->term = TermLength::query()->orderBy('id')->first();
        $this->assertNotNull($this->term, 'Need at least 1 term length');

        $this->customer = Customer::query()->orderBy('id')->first();
        if (!$this->customer) {
            $this->customer = new Customer();
            $this->customer->customer_name = 'QA Status Customer';
            $this->customer->email = 'qa.status.' . uniqid() . '@example.com';
            $this->customer->phone = '0509999999';
            $this->customer->status = 1;
            $this->customer->save();
        }
    }

    /** @test */
    public function qa_11_two_estimates_can_share_same_vacant_unit()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];

        $e1 = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        $e2 = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);

        $this->assertNotEquals($e1->id, $e2->id);
        $this->assertEquals(2, EstimateStorageUnit::where('storage_unit_id', $unitId)->count());

        $unit = StorageUnit::find($unitId);
        $this->assertEquals(StorageUnitStatus::VACANT, StorageUnitStatus::normalize($unit->status));
    }

    /** @test */
    public function qa_12_second_contract_on_same_unit_returns_422()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];

        $e1 = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        $e2 = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);

        $repo = new ContractClass();
        $r1 = $repo->saveContract($this->contractRequest($e1));
        $this->assertEquals(200, $r1->getStatusCode());

        $r2 = $repo->saveContract($this->contractRequest($e2));
        $this->assertEquals(422, $r2->getStatusCode());
        $payload = $r2->getData(true);
        $this->assertArrayHasKey('error', $payload);
    }

    /** @test */
    public function qa_13_contract_create_sets_booked_but_not_paid()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);

        $repo = new ContractClass();
        $response = $repo->saveContract($this->contractRequest($estimate));
        $this->assertEquals(200, $response->getStatusCode());

        $unit = StorageUnit::find($unitId);
        $this->assertEquals(StorageUnitStatus::BOOKED_NOT_PAID, StorageUnitStatus::normalize($unit->status));
        $this->assertNotNull($unit->active_contract_id);

        $this->assertTrue(
            ContractStorageUnit::where('storage_unit_id', $unitId)->whereNull('released_at')->exists()
        );
    }

    /** @test */
    public function qa_14_full_payment_sets_booked()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);

        $repo = new ContractClass();
        $this->assertEquals(200, $repo->saveContract($this->contractRequest($estimate))->getStatusCode());

        $contract = Contract::where('estimate_id', $estimate->id)->orderByDesc('id')->first();
        $this->assertNotNull($contract);

        $invoice = new Invoice();
        $invoice->customer_id = $this->customer->id;
        $invoice->contract_id = $contract->id;
        $invoice->type = 'contract';
        $invoice->invoice_no = 'QA-INV-' . uniqid();
        $invoice->invoice_date = date('Y-m-d');
        $invoice->due_date = date('Y-m-d', strtotime('+7 days'));
        $invoice->sub_total = 100;
        $invoice->vat = 0;
        $invoice->grand_total = 100;
        $invoice->status = 1;
        $invoice->payment_status = 0;
        $invoice->is_deleted = 0;
        $invoice->save();

        $payment = new Payment();
        $payment->customer_id = $this->customer->id;
        $payment->contract_id = $contract->id;
        $payment->invoice_id = $invoice->id;
        $payment->amount_received = 100;
        $payment->payment_method = 1;
        $payment->payment_date = date('Y-m-d');
        $payment->status = 1;
        $payment->is_deleted = 0;
        $payment->save();

        $invoiceRepo = new InvoiceClass();
        $invoiceRepo->syncPaymentStatus($invoice->id);

        $unit = StorageUnit::find($unitId);
        $this->assertEquals(StorageUnitStatus::BOOKED, StorageUnitStatus::normalize($unit->status));
    }

    /** @test */
    public function qa_15_move_in_sets_occupied_move_out_sets_vacant()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));
        $contract = Contract::where('estimate_id', $estimate->id)->orderByDesc('id')->first();

        MoveIn::create([
            'contract_id' => $contract->id,
            'customer_id' => $this->customer->id,
            'moved_items' => 0,
            'note' => 'qa',
            'move_in_date' => now(),
            'status' => 1,
        ]);

        app(StorageUnitStatusService::class)->recalculateForContract($contract, 'test.move_in');
        $unit = StorageUnit::find($unitId);
        $this->assertEquals(StorageUnitStatus::OCCUPIED, StorageUnitStatus::normalize($unit->status));

        MoveOut::create([
            'contract_id' => $contract->id,
            'customer_id' => $this->customer->id,
            'moved_out_items' => 0,
            'note' => 'qa out',
            'move_date_date' => now(),
            'status' => 1,
        ]);
        app(StorageUnitStatusService::class)->releaseByContract($contract, 'test.move_out');

        $unit->refresh();
        $this->assertEquals(StorageUnitStatus::VACANT, StorageUnitStatus::normalize($unit->status));
        $this->assertNull($unit->active_contract_id);
    }

    /** @test */
    public function qa_16_contract_delete_releases_unit_to_vacant()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));

        $contract = Contract::where('estimate_id', $estimate->id)->orderByDesc('id')->first();
        $result = (new ContractClass())->deleteContract($contract->id);
        $this->assertEquals(1, $result);

        $unit = StorageUnit::find($unitId);
        $this->assertEquals(StorageUnitStatus::VACANT, StorageUnitStatus::normalize($unit->status));
    }

    /** @test */
    public function qa_17_maintenance_blocks_lead_estimate_contract()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $unit = StorageUnit::find($unitId);
        app(StorageUnitStatusService::class)->setMaintenance($unit, true);

        $unit->refresh();
        $this->assertEquals(StorageUnitStatus::UNDER_MAINTENANCE, StorageUnitStatus::normalize($unit->status));

        $service = app(StorageUnitStatusService::class);
        $blocked = false;
        try {
            $service->assertAvailableForLead([$unitId]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $blocked = true;
        }
        $this->assertTrue($blocked, 'Lead should be blocked by maintenance');

        $blocked = false;
        try {
            $service->assertAvailableForContract([$unitId]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $blocked = true;
        }
        $this->assertTrue($blocked, 'Contract should be blocked by maintenance');

        app(StorageUnitStatusService::class)->setMaintenance($unit->fresh(), false);
    }

    /** @test */
    public function qa_18_public_search_excludes_non_vacant_units()
    {
        $unitId = $this->unitIds[0];
        StorageUnit::where('id', $unitId)->update([
            'status' => StorageUnitStatus::BOOKED,
            'is_maintenance' => 0,
        ]);

        $unit = StorageUnit::find($unitId);
        $repo = new StorageUnitClass();
        $request = Request::create('/get-storageunit', 'GET', ['warehouse_id' => $unit->wh_id]);
        // Method takes warehouse id directly in BookingController — pass wh_id
        $results = $repo->getSunitWarehouseWise($unit->wh_id);
        $ids = $results->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();

        $this->assertNotContains($unitId, $ids);

        // Vacant unit of same warehouse should appear (if another vacant exists) OR booked unit gone
        StorageUnit::where('id', $unitId)->update(['status' => StorageUnitStatus::VACANT]);
        $results2 = $repo->getSunitWarehouseWise($unit->wh_id);
        $ids2 = $results2->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();
        $this->assertContains($unitId, $ids2);
    }

    /** @test */
    public function qa_19_recalculate_is_idempotent()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));

        $service = app(StorageUnitStatusService::class);
        $s1 = $service->recalculate($unitId, 'test.pass1');
        $s2 = $service->recalculate($unitId, 'test.pass2');
        $s3 = $service->recalculate($unitId, 'test.pass3');

        $this->assertEquals($s1, $s2);
        $this->assertEquals($s2, $s3);
        $this->assertEquals(StorageUnitStatus::BOOKED_NOT_PAID, $s1);
    }

    /** @test */
    public function qa_20_partial_payment_stays_booked_but_not_paid()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));
        $contract = Contract::where('estimate_id', $estimate->id)->orderByDesc('id')->first();
        $this->assertNotNull($contract);

        // Use query builder to avoid InvoiceObserver / model side-effects during QA
        $invoiceId = DB::table('invoices')->insertGetId([
            'customer_id' => $this->customer->id,
            'contract_id' => $contract->id,
            'type' => 'contract',
            'invoice_no' => 'QA-PARTIAL-' . uniqid(),
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+7 days')),
            'sub_total' => 200,
            'vat' => 0,
            'grand_total' => 200,
            'status' => 1,
            'payment_status' => 0,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Clean any impossible legacy rows, then add a controlled partial payment
        DB::table('payments')->where('invoice_id', $invoiceId)->delete();
        DB::table('payments')->insert([
            'customer_id' => $this->customer->id,
            'contract_id' => $contract->id,
            'invoice_id' => $invoiceId,
            'amount_received' => 50,
            'payment_method' => 1,
            'payment_date' => date('Y-m-d'),
            'status' => 1,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $invoice = Invoice::find($invoiceId);
        $this->assertNotNull($invoice);
        $this->assertEquals(50.0, $invoice->paidAmount());
        $this->assertEquals(Invoice::PAYMENT_PARTIAL, $invoice->resolvePaymentStatusValue());

        (new InvoiceClass())->syncPaymentStatus($invoiceId);

        $unit = StorageUnit::find($unitId);
        $this->assertEquals(
            StorageUnitStatus::BOOKED_NOT_PAID,
            StorageUnitStatus::normalize($unit->status),
            'Partial payment must keep unit as booked but not paid'
        );
    }

    /** @test */
    public function qa_21_cannot_delete_contract_while_occupied()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));
        $contract = Contract::where('estimate_id', $estimate->id)->orderByDesc('id')->first();

        MoveIn::create([
            'contract_id' => $contract->id,
            'customer_id' => $this->customer->id,
            'moved_items' => 0,
            'note' => 'qa occupied',
            'move_in_date' => now(),
            'status' => 1,
        ]);
        app(StorageUnitStatusService::class)->recalculateForContract($contract, 'test.move_in');

        $result = (new ContractClass())->deleteContract($contract->id);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $result);
        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals(0, (int) $contract->fresh()->is_deleted);

        $unit = StorageUnit::find($unitId);
        $this->assertEquals(StorageUnitStatus::OCCUPIED, StorageUnitStatus::normalize($unit->status));
    }

    /** @test */
    public function qa_22_hard_held_unit_blocks_new_lead()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));

        $leadRepo = new \App\Repo\LeadClass();
        $request = Request::create('/admin/save-lead', 'GET', [
            'type' => 'individual',
            'r_date' => date('Y-m-d'),
            'f_name' => 'Blocked',
            'l_name' => 'Lead',
            'email' => 'qa.blocked.lead@example.com',
            'phone' => '0503333333',
            'mobile1' => '0503333333',
            'su_ids' => [$unitId],
            'id' => $unitId,
            'term_length' => $this->term->id,
            'insurance' => 'nothanks',
            'lead_status' => 1,
            'lead_source' => 1,
            'lead_rating' => 0,
            'user_res' => Auth::id(),
        ]);

        $response = $leadRepo->saveLeadBackend($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function qa_23_multi_unit_contract_hard_holds_all_units()
    {
        $this->actingAsAdmin();
        $estimate = $this->makeEstimate($this->unitIds, [
            $this->unitPrice($this->unitIds[0]),
            $this->unitPrice($this->unitIds[1]),
        ]);
        $response = (new ContractClass())->saveContract($this->contractRequest($estimate));
        $this->assertEquals(200, $response->getStatusCode());

        foreach ($this->unitIds as $unitId) {
            $unit = StorageUnit::find($unitId);
            $this->assertEquals(
                StorageUnitStatus::BOOKED_NOT_PAID,
                StorageUnitStatus::normalize($unit->status),
                "Unit {$unitId} should be hard-held"
            );
            $this->assertTrue(
                ContractStorageUnit::where('storage_unit_id', $unitId)->whereNull('released_at')->exists()
            );
        }
    }

    /** @test */
    public function qa_24_clearing_maintenance_restores_vacant_when_no_contract()
    {
        $unitId = $this->unitIds[0];
        $unit = StorageUnit::find($unitId);
        $service = app(StorageUnitStatusService::class);

        $service->setMaintenance($unit, true);
        $this->assertEquals(StorageUnitStatus::UNDER_MAINTENANCE, StorageUnitStatus::normalize($unit->fresh()->status));

        $service->setMaintenance($unit->fresh(), false);
        $this->assertEquals(StorageUnitStatus::VACANT, StorageUnitStatus::normalize($unit->fresh()->status));
        $this->assertFalse((bool) $unit->fresh()->is_maintenance);
    }

    /** @test */
    public function qa_25_status_transition_writes_audit_log()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $before = DB::table('storage_unit_status_logs')->where('storage_unit_id', $unitId)->count();

        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));

        $after = DB::table('storage_unit_status_logs')->where('storage_unit_id', $unitId)->count();
        $this->assertGreaterThan($before, $after);

        $latest = DB::table('storage_unit_status_logs')
            ->where('storage_unit_id', $unitId)
            ->orderByDesc('id')
            ->first();
        $this->assertEquals(StorageUnitStatus::BOOKED_NOT_PAID, $latest->new_status);
        $this->assertEquals('contract.created', $latest->trigger);
    }

    /** @test */
    public function qa_26_occupied_blocks_estimate_assert()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));
        $contract = Contract::where('estimate_id', $estimate->id)->orderByDesc('id')->first();

        MoveIn::create([
            'contract_id' => $contract->id,
            'customer_id' => $this->customer->id,
            'moved_items' => 0,
            'note' => 'qa',
            'move_in_date' => now(),
            'status' => 1,
        ]);
        app(StorageUnitStatusService::class)->recalculateForContract($contract, 'test.move_in');

        $blocked = false;
        try {
            app(StorageUnitStatusService::class)->assertAvailableForEstimate([$unitId]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $blocked = true;
        }
        $this->assertTrue($blocked);
    }

    /** @test */
    public function qa_27_search_storage_unit_excludes_booked_and_maintenance()
    {
        $unit = StorageUnit::find($this->unitIds[0]);
        StorageUnit::where('id', $unit->id)->update([
            'status' => StorageUnitStatus::BOOKED,
            'is_maintenance' => 0,
        ]);

        $repo = new StorageUnitClass();
        $request = Request::create('/booking', 'GET', []);
        $results = $repo->searchStorageUnit($request);
        $ids = $results->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();
        $this->assertNotContains((int) $unit->id, $ids);

        StorageUnit::where('id', $unit->id)->update([
            'status' => StorageUnitStatus::UNDER_MAINTENANCE,
            'is_maintenance' => 1,
        ]);
        $results2 = $repo->searchStorageUnit($request);
        $ids2 = $results2->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();
        $this->assertNotContains((int) $unit->id, $ids2);
    }

    /** @test */
    public function qa_28_hard_hold_key_unique_prevents_double_active_hold()
    {
        $this->actingAsAdmin();
        $unitId = $this->unitIds[0];
        $estimate = $this->makeEstimate([$unitId], [$this->unitPrice($unitId)]);
        (new ContractClass())->saveContract($this->contractRequest($estimate));

        $duplicateFailed = false;
        try {
            DB::table('storage_unit_assignments')->insert([
                'storage_unit_id' => $unitId,
                'customer_id' => $this->customer->id,
                'hold_type' => 'hard',
                'source_type' => Contract::class,
                'source_id' => 999999,
                'released_at' => null,
                'hard_hold_key' => $unitId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $duplicateFailed = true;
        }
        $this->assertTrue($duplicateFailed, 'Unique hard_hold_key must reject a second active hard hold');
    }

    protected function makeInvoice(Contract $contract, float $total): Invoice
    {
        $invoice = new Invoice();
        $invoice->customer_id = $this->customer->id;
        $invoice->contract_id = $contract->id;
        $invoice->type = 'contract';
        $invoice->invoice_no = 'QA-INV-' . uniqid();
        $invoice->invoice_date = date('Y-m-d');
        $invoice->due_date = date('Y-m-d', strtotime('+7 days'));
        $invoice->sub_total = $total;
        $invoice->vat = 0;
        $invoice->grand_total = $total;
        $invoice->status = 1;
        $invoice->payment_status = 0;
        $invoice->is_deleted = 0;
        $invoice->save();

        return $invoice;
    }

    protected function makePayment(Contract $contract, Invoice $invoice, float $amount): Payment
    {
        $payment = new Payment();
        $payment->customer_id = $this->customer->id;
        $payment->contract_id = $contract->id;
        $payment->invoice_id = $invoice->id;
        $payment->amount_received = $amount;
        $payment->payment_method = 1;
        $payment->payment_date = date('Y-m-d');
        $payment->status = 1;
        $payment->is_deleted = 0;
        $payment->save();

        return $payment;
    }

    protected function unitPrice(int $unitId): string
    {
        $price = StorageUnit::where('id', $unitId)->value('price');
        return (string) ($price ?: '100');
    }

    protected function makeEstimate(array $unitIds, array $prices): Estimate
    {
        $estimate = new Estimate();
        $estimate->su_id = $unitIds[0];
        $estimate->customer_id = $this->customer->id;
        $estimate->user_id = Auth::id() ?: (DB::table('users')->orderBy('id')->value('id') ?: 1);
        $estimate->addon_id = 1;
        $estimate->term_length = $this->term->id;
        $estimate->unit_price = (string) array_sum(array_map('floatval', $prices));
        $estimate->status = 3;
        $estimate->is_deleted = 0;
        $estimate->f_name = 'QA';
        $estimate->l_name = 'Status';
        $estimate->email = 'qa.status.' . uniqid() . '@example.com';
        $estimate->phone = '0502222222';
        $estimate->estimate_date = date('Y-m-d');
        $estimate->expiry_date = date('Y-m-d', strtotime('+30 days'));
        $estimate->save();

        $now = now();
        $rows = [];
        foreach ($unitIds as $i => $suId) {
            $rows[] = [
                'estimate_id' => $estimate->id,
                'storage_unit_id' => $suId,
                'unit_price' => (string) ($prices[$i] ?? '0'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        EstimateStorageUnit::insert($rows);

        return $estimate->fresh(['estimateStorageUnits']);
    }

    protected function contractRequest(Estimate $estimate): Request
    {
        return Request::create('/admin/save-contract', 'POST', [
            'customer_id' => $this->customer->id,
            'estimate_id' => $estimate->id,
            'subject' => 'QA Status Contract ' . uniqid(),
            'contract_value' => $estimate->unit_price,
            'contract_type' => 'monthly',
            's_date' => date('Y-m-d'),
            'e_date' => date('Y-m-d', strtotime('+1 year')),
            'description' => 'QA auto contract',
        ]);
    }

    protected function actingAsAdmin(): void
    {
        $userId = DB::table('users')->orderBy('id')->value('id');
        $this->assertNotNull($userId, 'Need at least one user');
        Auth::loginUsingId($userId);
    }
}
