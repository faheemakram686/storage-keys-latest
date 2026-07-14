<?php

namespace Tests\Feature\MultiUnit;

use App\Models\Contract;
use App\Models\Estimate;
use App\Models\EstimateStorageUnit;
use App\Models\Insurance;
use App\Models\Lead;
use App\Models\StorageUnit;
use App\Models\TermLength;
use App\Repo\ContractClass;
use App\Repo\EstimateClass;
use App\Repo\LeadClass;
use App\Services\InsurancePricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * QA: Dynamic insurance package selection, snapshots, and invoice term math.
 */
class InsurancePricingQaTest extends MultiUnitTestCase
{
    /** @var Insurance */
    protected $package;

    /** @var TermLength|null */
    protected $term;

    /** @var int */
    protected $unitId;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $unit = StorageUnit::where('is_deleted', 0)->orderBy('id')->first();
        $this->assertNotNull($unit, 'Need at least 1 active storage unit');
        $this->unitId = (int) $unit->id;

        StorageUnit::where('id', $this->unitId)->update([
            'status' => 'vacant',
            'is_maintenance' => 0,
            'occupied_by_customer_id' => null,
            'active_contract_id' => null,
        ]);
        DB::table('storage_unit_assignments')->where('storage_unit_id', $this->unitId)->delete();
        DB::table('contract_storage_units')->where('storage_unit_id', $this->unitId)->delete();

        $this->term = TermLength::query()->orderBy('id')->first();
        $this->assertNotNull($this->term, 'Need at least 1 term length');

        $this->package = new Insurance();
        $this->package->name = 'QA Cover ' . uniqid();
        $this->package->monthly_amount = '40.00';
        $this->package->cover = '50000';
        $this->package->status = 1;
        $this->package->is_deleted = 0;
        $this->package->save();

        $userId = DB::table('users')->orderBy('id')->value('id');
        $this->assertNotNull($userId);
        Auth::loginUsingId($userId);
    }

    /** @test */
    public function service_resolves_package_snapshot_from_request()
    {
        $service = new InsurancePricingService();
        // No percent in name → scale catalog by goods/baseCover
        $request = Request::create('/', 'POST', [
            'insurance_id' => $this->package->id,
            'goodsval' => '100000',
        ]);

        $snap = $service->resolveFromRequest($request);

        $this->assertEquals($this->package->id, $snap['insurance_id']);
        // base monthly 40, base cover 50000, goods 100000 → scale 2 → monthly 80, cover 100000
        $this->assertEquals(80.0, $snap['insurance_amount']);
        $this->assertEquals('100000', $snap['insurance_cover']);
        $this->assertEquals('cover', $snap['insurence']);
        $this->assertEquals('100000', $snap['goods']);
    }

    /** @test */
    public function service_auto_calcs_percent_package_from_goods_value()
    {
        $pkg = new Insurance();
        $pkg->name = '10%';
        $pkg->monthly_amount = '1000';
        $pkg->cover = '10000';
        $pkg->status = 1;
        $pkg->is_deleted = 0;
        $pkg->save();

        $service = new InsurancePricingService();
        $calc = $service->calculateForPackage($pkg, 50000);

        // cover = 50000 * 10% = 5000; fee ratio = 1000/10000 = 0.1 → monthly = 500
        $this->assertEquals(5000.0, $calc['cover']);
        $this->assertEquals(500.0, $calc['monthly']);
        $this->assertEquals(10.0, $calc['percent']);

        $request = Request::create('/', 'POST', [
            'insurance_id' => $pkg->id,
            'goodsval' => '50000',
        ]);
        $snap = $service->resolveFromRequest($request);
        $this->assertEquals(500.0, $snap['insurance_amount']);
        $this->assertEquals('5000', $snap['insurance_cover']);
    }

    /** @test */
    public function service_uses_catalog_defaults_when_goods_empty()
    {
        $pkg = new Insurance();
        $pkg->name = '20%';
        $pkg->monthly_amount = '2000';
        $pkg->cover = '20000';
        $pkg->status = 1;
        $pkg->is_deleted = 0;
        $pkg->save();

        $service = new InsurancePricingService();
        $calc = $service->calculateForPackage($pkg, null);

        $this->assertEquals(2000.0, $calc['monthly']);
        $this->assertEquals(20000.0, $calc['cover']);
    }

    /** @test */
    public function service_nothanks_clears_snapshot()
    {
        $service = new InsurancePricingService();
        $request = Request::create('/', 'POST', ['insurance_id' => '']);

        $snap = $service->resolveFromRequest($request);

        $this->assertNull($snap['insurance_id']);
        $this->assertNull($snap['insurance_amount']);
        $this->assertEquals('nothanks', $snap['insurence']);
    }

    /** @test */
    public function service_term_total_is_monthly_times_period()
    {
        $service = new InsurancePricingService();
        $this->assertEquals(120.0, $service->termTotal(40.0, 3));
        $this->assertEquals(40.0, $service->termTotal(40.0, 1));
    }

    /** @test */
    public function lead_save_snapshots_insurance_package()
    {
        $userId = Auth::id();
        $request = Request::create('/admin/save-lead', 'GET', [
            'type' => 'individual',
            'r_date' => date('Y-m-d'),
            'f_name' => 'QA',
            'l_name' => 'Insurance',
            'email' => 'qa.insurance.' . uniqid() . '@example.com',
            'phone' => '0500000099',
            'mobile1' => '0500000099',
            'mobile2' => '',
            'term_length' => $this->term->id,
            'su_ids' => [$this->unitId],
            'id' => $this->unitId,
            'insurance_id' => $this->package->id,
            'goodsval' => '75000',
            'lead_status' => 1,
            'lead_source' => 1,
            'lead_rating' => 0,
            'user_res' => $userId,
            'addon' => [],
        ]);

        $repo = new LeadClass();
        $response = $repo->saveLeadBackend($request);
        $this->assertEquals(200, $response->getStatusCode(), json_encode($response->getData(true)));

        $lead = Lead::where('email', $request->email)->orderByDesc('id')->first();
        $this->assertNotNull($lead);
        $this->assertEquals($this->package->id, (int) $lead->insurance_id);
        // goods 75000 / base cover 50000 = 1.5x → monthly 60, cover 75000
        $this->assertEquals(60.0, (float) $lead->insurance_amount);
        $this->assertEquals('75000', $lead->insurance_cover);
        $this->assertEquals('cover', $lead->insurence);
        $this->assertEquals('75000', $lead->goods);
        $this->assertEquals(60.0, $lead->insuranceFee());
    }

    /** @test */
    public function estimate_uses_dynamic_insurance_amount_not_static_25()
    {
        $estimate = new Estimate();
        $estimate->su_id = $this->unitId;
        $estimate->user_id = Auth::id();
        $estimate->addon_id = 1;
        $estimate->term_length = $this->term->id;
        $estimate->unit_price = '100';
        $estimate->status = 3;
        $estimate->is_deleted = 0;
        $estimate->f_name = 'QA';
        $estimate->l_name = 'Est';
        $estimate->email = 'qa.est.ins@example.com';
        $estimate->phone = '0500000088';
        $estimate->insurence = 'cover';
        $estimate->insurance_id = $this->package->id;
        $estimate->insurance_amount = 40;
        $estimate->insurance_cover = '50000';
        $estimate->estimate_date = date('Y-m-d');
        $estimate->expiry_date = date('Y-m-d', strtotime('+30 days'));
        $estimate->save();

        EstimateStorageUnit::insert([[
            'estimate_id' => $estimate->id,
            'storage_unit_id' => $this->unitId,
            'unit_price' => '100',
            'created_at' => now(),
            'updated_at' => now(),
        ]]);

        $estimate = $estimate->fresh();
        $this->assertEquals(40.0, $estimate->insuranceFee());
        $this->assertNotEquals(25.0, $estimate->insuranceFee());
    }

    /** @test */
    public function contract_copies_insurance_snapshot_from_estimate()
    {
        $customerId = DB::table('customers')->orderBy('id')->value('id');
        $this->assertNotNull($customerId, 'Need a customer');

        $estimate = new Estimate();
        $estimate->su_id = $this->unitId;
        $estimate->customer_id = $customerId;
        $estimate->user_id = Auth::id();
        $estimate->addon_id = 1;
        $estimate->term_length = $this->term->id;
        $estimate->unit_price = '100';
        $estimate->status = 3;
        $estimate->is_deleted = 0;
        $estimate->f_name = 'QA';
        $estimate->l_name = 'ContractIns';
        $estimate->email = 'qa.contract.ins@example.com';
        $estimate->phone = '0500000077';
        $estimate->insurence = 'cover';
        $estimate->insurance_id = $this->package->id;
        $estimate->insurance_amount = 40;
        $estimate->insurance_cover = '50000';
        $estimate->estimate_date = date('Y-m-d');
        $estimate->expiry_date = date('Y-m-d', strtotime('+30 days'));
        $estimate->save();

        EstimateStorageUnit::insert([[
            'estimate_id' => $estimate->id,
            'storage_unit_id' => $this->unitId,
            'unit_price' => '100',
            'created_at' => now(),
            'updated_at' => now(),
        ]]);

        $request = Request::create('/admin/save-contract', 'GET', [
            'customer_id' => $customerId,
            'estimate_id' => $estimate->id,
            'subject' => 'QA Insurance Contract',
            'contract_value' => 500,
            'contract_type' => 1,
            's_date' => date('Y-m-d'),
            'e_date' => date('Y-m-d', strtotime('+1 year')),
            'description' => 'QA',
        ]);

        $repo = new ContractClass();
        $response = $repo->saveContract($request);
        $this->assertEquals(200, $response->getStatusCode(), json_encode($response->getData(true)));

        $contract = Contract::where('estimate_id', $estimate->id)->orderByDesc('id')->first();
        $this->assertNotNull($contract);
        $this->assertEquals($this->package->id, (int) $contract->insurance_id);
        $this->assertEquals(40.0, (float) $contract->insurance_amount);
        $this->assertEquals('50000', $contract->insurance_cover);
        $this->assertEquals('cover', $contract->insurence);

        $period = (float) ($this->term->term_period ?? 1);
        $expectedInvoiceLine = 40.0 * $period;
        $service = new InsurancePricingService();
        $this->assertEquals($expectedInvoiceLine, $service->termTotal((float) $contract->insurance_amount, $period));
    }

    /** @test */
    public function estimate_add_persists_insurance_from_request()
    {
        $customerId = DB::table('customers')->orderBy('id')->value('id');
        $this->assertNotNull($customerId);

        $emailTemplateId = DB::table('email_templates')->orderBy('id')->value('id') ?: 1;

        $request = Request::create('/admin/add-estimate', 'GET', [
            'customer_id' => $customerId,
            'su_ids' => [$this->unitId],
            'unit_prices' => ['100'],
            'term_length' => $this->term->id,
            'r_date' => date('Y-m-d'),
            'insurance_id' => $this->package->id,
            'goodsval' => '20000',
            'email_template' => $emailTemplateId,
            'status' => 0,
            'estimate_date' => date('Y-m-d'),
            'expiry_date' => date('Y-m-d', strtotime('+14 days')),
            'addon' => [],
            'addonprice' => [],
            'require_document' => [],
        ]);

        $repo = new EstimateClass();
        $response = $repo->addEstimate($request);
        $status = $response->getStatusCode();
        if ($status !== 200) {
            $payload = $response->getData(true);
            $msg = json_encode($payload);
            if (stripos($msg, 'contact') !== false) {
                $this->markTestSkipped('addEstimate requires primary contact: ' . $msg);
            }
            $this->fail('Unexpected estimate failure: ' . $msg);
        }

        $estimate = Estimate::where('customer_id', $customerId)
            ->where('insurance_id', $this->package->id)
            ->orderByDesc('id')
            ->first();
        $this->assertNotNull($estimate);
        // goods 20000 / base cover 50000 = 0.4x → monthly 16, cover 20000
        $this->assertEquals(16.0, (float) $estimate->insurance_amount);
        $this->assertEquals('20000', $estimate->insurance_cover);
        $this->assertEquals('cover', $estimate->insurence);
    }
}
