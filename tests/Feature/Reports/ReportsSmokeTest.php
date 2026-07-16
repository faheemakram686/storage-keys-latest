<?php

namespace Tests\Feature\Reports;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;

/**
 * Smoke tests for the entire Reports section:
 * every report page and every filter endpoint (with and without filters).
 * Runs against the real app DB inside a transaction (same pattern as MultiUnit QA).
 */
class ReportsSmokeTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $userId = DB::table('users')->orderBy('id')->value('id');
        $this->assertNotNull($userId, 'Need at least one user to act as admin');
        Auth::loginUsingId($userId);
    }

    /** @test */
    public function all_report_pages_load()
    {
        $pages = [
            'admin/view-warehouse-report',
            'admin/view-lead-report',
            'admin/view-estimate-report',
            'admin/view-contract-report',
            'admin/view-invoice-report',
            'admin/view-payment-report',
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $this->assertSame(200, $response->status(), "Page {$page} failed: " . $this->failureHint($response));
        }
    }

    /** @test */
    public function warehouse_report_filter_works()
    {
        $response = $this->get('admin/filter-warehouse-report');
        $this->assertSame(200, $response->status(), $this->failureHint($response));
        $rows = $response->json();
        $this->assertIsArray($rows);

        if (count($rows) > 0) {
            $row = $rows[0];
            foreach (['warehouse', 'storagetype', 'storagelevel', 'storagesize'] as $rel) {
                $this->assertArrayHasKey($rel, $row, "warehouse report row missing '{$rel}'");
            }
        }

        // With filters
        $whId = DB::table('warehouses')->where('is_deleted', 0)->value('id');
        $response = $this->get('admin/filter-warehouse-report?' . http_build_query([
            'wh_id' => $whId,
            'status' => 'vacant',
        ]));
        $this->assertSame(200, $response->status(), $this->failureHint($response));
    }

    /** @test */
    public function lead_report_filter_works()
    {
        $response = $this->get('admin/filter-lead-report');
        $this->assertSame(200, $response->status(), $this->failureHint($response));
        $rows = $response->json();
        $this->assertIsArray($rows);

        if (count($rows) > 0) {
            $row = $rows[0];
            foreach (['userresponsible', 'lead_status', 'lead_source', 'term_length', 'storage_units'] as $rel) {
                $this->assertArrayHasKey($rel, $row, "lead report row missing '{$rel}'");
            }
        }

        $userId = DB::table('users')->orderBy('id')->value('id');
        $statusId = DB::table('lead_statuses')->where('is_deleted', 0)->value('id');
        $response = $this->get('admin/filter-lead-report?' . http_build_query([
            'user_res' => $userId,
            'lead_status' => $statusId,
            'start_date' => '2000-01-01',
            'end_date' => date('Y-m-d'),
        ]));
        $this->assertSame(200, $response->status(), $this->failureHint($response));
    }

    /** @test */
    public function estimate_report_filter_works()
    {
        $response = $this->get('admin/filter-estimate-report');
        $this->assertSame(200, $response->status(), $this->failureHint($response));
        $rows = $response->json();
        $this->assertIsArray($rows);

        if (count($rows) > 0) {
            $row = $rows[0];
            foreach (['customer', 'user_responsible', 'term_length', 'estimate_storage_units'] as $rel) {
                $this->assertArrayHasKey($rel, $row, "estimate report row missing '{$rel}'");
            }
            if (!empty($row['user_responsible'])) {
                $this->assertArrayHasKey('full_name', $row['user_responsible']);
            }
        }

        $customerId = DB::table('customers')->where('is_deleted', 0)->value('id');
        $response = $this->get('admin/filter-estimate-report?' . http_build_query([
            'customer_id' => $customerId,
            'start_date' => '2000-01-01',
            'end_date' => date('Y-m-d'),
        ]));
        $this->assertSame(200, $response->status(), $this->failureHint($response));
    }

    /** @test */
    public function contract_report_filter_works()
    {
        $response = $this->get('admin/filter-contract-report');
        $this->assertSame(200, $response->status(), $this->failureHint($response));
        $rows = $response->json();
        $this->assertIsArray($rows);

        if (count($rows) > 0) {
            $row = $rows[0];
            foreach (['customer', 'estimate', 'user_rensonsible'] as $rel) {
                $this->assertArrayHasKey($rel, $row, "contract report row missing '{$rel}'");
            }
            if (!empty($row['estimate'])) {
                $this->assertArrayHasKey('estimate_storage_units', $row['estimate'], 'contract report estimate missing multi-unit relation');
                $this->assertArrayHasKey('term_length', $row['estimate']);
            }
        }

        $customerId = DB::table('customers')->where('is_deleted', 0)->value('id');
        $response = $this->get('admin/filter-contract-report?' . http_build_query([
            'customer_id' => $customerId,
        ]));
        $this->assertSame(200, $response->status(), $this->failureHint($response));
    }

    /** @test */
    public function invoice_report_filter_works()
    {
        $response = $this->get('admin/filter-invoice-report');
        $this->assertSame(200, $response->status(), $this->failureHint($response));
        $rows = $response->json();
        $this->assertIsArray($rows);

        if (count($rows) > 0) {
            $row = $rows[0];
            foreach (['customer', 'contract', 'order', 'user_responsible', 'payment_status'] as $key) {
                $this->assertArrayHasKey($key, $row, "invoice report row missing '{$key}'");
            }
            $this->assertContains($row['payment_status'], ['Paid', 'Partial', 'Pending']);
        }

        // Each payment_status filter value must work and return only matching rows
        foreach ([0 => 'Pending', 1 => 'Paid', 2 => 'Partial'] as $statusVal => $label) {
            $response = $this->get('admin/filter-invoice-report?payment_status=' . $statusVal);
            $this->assertSame(200, $response->status(), "payment_status={$statusVal}: " . $this->failureHint($response));
            foreach ($response->json() as $row) {
                $this->assertSame($label, $row['payment_status'], "payment_status filter {$statusVal} returned wrong row");
            }
        }

        $customerId = DB::table('customers')->where('is_deleted', 0)->value('id');
        $contractId = DB::table('contracts')->where('is_deleted', 0)->value('id');
        $response = $this->get('admin/filter-invoice-report?' . http_build_query([
            'customer_id' => $customerId,
            'contract_id' => $contractId,
            'start_date' => '2000-01-01',
            'end_date' => '2100-01-01',
        ]));
        $this->assertSame(200, $response->status(), $this->failureHint($response));
    }

    /** @test */
    public function payment_report_filter_works()
    {
        $response = $this->get('admin/filter-payment-report');
        $this->assertSame(200, $response->status(), $this->failureHint($response));
        $this->assertIsArray($response->json());

        $response = $this->get('admin/filter-payment-report?payment_status=1');
        $this->assertSame(200, $response->status(), $this->failureHint($response));
    }

    private function failureHint($response): string
    {
        $exception = $response->exception ?? null;
        if ($exception) {
            return get_class($exception) . ': ' . $exception->getMessage()
                . ' @ ' . $exception->getFile() . ':' . $exception->getLine();
        }

        return 'HTTP ' . $response->status();
    }
}
