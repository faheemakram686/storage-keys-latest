<?php

namespace Tests\Feature\MultiUnit;

use App\Enums\StorageUnitStatus;
use App\Models\Estimate;
use App\Models\Lead;
use App\Models\StorageUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * HTTP / route-level smoke tests for multi-unit booking surfaces.
 */
class MultiUnitHttpSmokeTest extends MultiUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    /** @test */
    public function public_save_lead_route_accepts_multiple_su_ids()
    {
        $units = StorageUnit::where('is_deleted', 0)->orderBy('id')->take(2)->pluck('id')->all();
        $this->assertCount(2, $units);

        $termId = DB::table('term_lengths')->orderBy('id')->value('id') ?: 1;

        $response = $this->get('/save-lead?' . http_build_query([
            'type' => 'individual',
            'r_date' => date('Y-m-d'),
            'f_name' => 'HTTP',
            'l_name' => 'QA',
            'email' => 'qa.http.lead@example.com',
            'phone' => '0509999001',
            'mobile1' => '0509999001',
            'su_ids' => $units,
            'id' => $units[0],
            'price' => $termId,
            'insurance' => 'nothanks',
            'terms' => 'agree',
        ]));

        $response->assertStatus(200);

        $lead = Lead::where('email', 'qa.http.lead@example.com')->orderByDesc('id')->first();
        $this->assertNotNull($lead);
        $this->assertCount(2, $lead->storageUnits()->get());
    }

    /** @test */
    public function warehouse_storage_unit_ajax_still_returns_units()
    {
        $unit = StorageUnit::where('is_deleted', 0)
            ->where('status', StorageUnitStatus::VACANT)
            ->where(function ($q) {
                $q->where('is_maintenance', 0)->orWhereNull('is_maintenance');
            })
            ->orderBy('id')
            ->first();
        $this->assertNotNull($unit, 'Need at least one vacant unit for warehouse AJAX smoke');

        $response = $this->get('/get-storageunit?warehouse_id=' . $unit->wh_id);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        foreach ($data as $row) {
            $this->assertEquals(StorageUnitStatus::VACANT, StorageUnitStatus::normalize($row['status'] ?? ''));
        }
    }

    /** @test */
    public function warehouse_ajax_excludes_booked_units()
    {
        $unit = StorageUnit::where('is_deleted', 0)->orderBy('id')->first();
        $this->assertNotNull($unit);

        $original = [
            'status' => $unit->status,
            'is_maintenance' => $unit->is_maintenance,
        ];

        $unit->status = StorageUnitStatus::BOOKED;
        $unit->is_maintenance = 0;
        $unit->save();

        $response = $this->get('/get-storageunit?warehouse_id=' . $unit->wh_id);
        $response->assertStatus(200);
        $ids = collect($response->json())->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();
        $this->assertNotContains((int) $unit->id, $ids);

        // Restore within transaction (will rollback anyway)
        $unit->status = $original['status'];
        $unit->is_maintenance = $original['is_maintenance'];
        $unit->save();
    }

    /** @test */
    public function public_save_lead_rejects_hard_held_unit()
    {
        $unit = StorageUnit::where('is_deleted', 0)->orderBy('id')->first();
        $this->assertNotNull($unit);

        $unit->status = StorageUnitStatus::OCCUPIED;
        $unit->is_maintenance = 0;
        $unit->save();

        $termId = DB::table('term_lengths')->orderBy('id')->value('id') ?: 1;

        $response = $this->get('/save-lead?' . http_build_query([
            'type' => 'individual',
            'r_date' => date('Y-m-d'),
            'f_name' => 'HTTP',
            'l_name' => 'Blocked',
            'email' => 'qa.http.blocked@example.com',
            'phone' => '0509999002',
            'mobile1' => '0509999002',
            'su_ids' => [$unit->id],
            'id' => $unit->id,
            'price' => $termId,
            'insurance' => 'nothanks',
            'terms' => 'agree',
        ]));

        $response->assertStatus(422);
    }

    /** @test */
    public function estimate_model_exposes_storage_units_relation_for_api_payloads()
    {
        $estimate = Estimate::with(['estimateStorageUnits.storageunit', 'storageUnits'])
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->first();

        $this->assertNotNull($estimate);
        // Relations must be present (may be empty for unclean legacy — but accessible)
        $this->assertTrue($estimate->relationLoaded('estimateStorageUnits'));
        $this->assertTrue($estimate->relationLoaded('storageUnits'));
        $json = $estimate->toArray();
        $this->assertArrayHasKey('estimate_storage_units', $json);
        $this->assertArrayHasKey('storage_units', $json);
    }
}
