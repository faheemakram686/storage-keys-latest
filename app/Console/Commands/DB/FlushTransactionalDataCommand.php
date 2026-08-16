<?php

namespace App\Console\Commands\DB;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FlushTransactionalDataCommand extends Command
{
    protected $signature = 'db:flush-transactional
                            {--force : Run without confirmation}';

    protected $description = 'Truncate user/customer transactional tables for production, keeping master data, settings, and staff users';

    protected array $tables = [
        'invoice_reminder_logs',
        'invoice_items',
        'payments',
        'invoices',
        'order_items',
        'orders',
        'barcode_labels',
        'move_outs',
        'move_ins',
        'move_in_requests',
        'contract_storage_units',
        'contracts',
        'estimate_storage_units',
        'addon_price_estimates',
        'estimates',
        'lead_storage_units',
        'contacts',
        'customers',
        'leads',
        'storage_unit_assignments',
        'storage_unit_status_logs',
        'inquiries',
        'attachments',
        'documents',
        'files',
        'notes',
        'comments',
        'tasks',
        'reminders',
        'custom_field_values',
        'activity_log',
        'audits',
        'notifications',
        'notification_audiences',
        'attendance_details',
        'attendances',
        'leaves',
        'payslips',
        'salaries',
        'beneficiary_values',
        'beneficiaries',
        'payruns',
        'company_assets',
        'announcement_department',
        'announcements',
        'upcoming_user_working_shifts',
        'upcoming_department_working_shifts',
        'sessions',
        'password_resets',
        'password_histories',
        'personal_access_tokens',
        'jobs',
        'job_batches',
        'failed_jobs',
        'cache',
        'websockets_statistics_entries',
    ];

    public function handle(): int
    {
        $this->warn('This will permanently delete customers, leads, invoices, payments, contracts, and related transactional data.');
        $this->comment('Master data is kept: warehouses, storage units, products, templates, settings, roles, and staff users.');

        if (!$this->option('force') && !$this->confirm('Are you sure you want to continue?')) {
            $this->info('Cancelled.');

            return 0;
        }

        $existing = array_values(array_filter($this->tables, function ($table) {
            return Schema::hasTable($table);
        }));

        $missing = array_values(array_diff($this->tables, $existing));

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($existing as $table) {
                DB::table($table)->truncate();
                $this->line("Truncated: {$table}");
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->resetStorageUnitOccupancy();

        foreach ($missing as $table) {
            $this->comment("Skipped (table not found): {$table}");
        }

        $this->info('Transactional data flushed. Storage unit occupancy reset to vacant.');

        return 0;
    }

    protected function resetStorageUnitOccupancy(): void
    {
        if (!Schema::hasTable('storage_units')) {
            return;
        }

        $updates = ['status' => DB::raw("CASE WHEN COALESCE(is_maintenance, 0) = 1 THEN 'under maintenance' ELSE 'vacant' END")];

        if (Schema::hasColumn('storage_units', 'occupied_by_customer_id')) {
            $updates['occupied_by_customer_id'] = null;
        }

        if (Schema::hasColumn('storage_units', 'active_contract_id')) {
            $updates['active_contract_id'] = null;
        }

        if (Schema::hasColumn('storage_units', 'status_changed_at')) {
            $updates['status_changed_at'] = null;
        }

        if (!Schema::hasColumn('storage_units', 'is_maintenance')) {
            $updates['status'] = 'vacant';
        }

        DB::table('storage_units')->update($updates);
        $this->line('Reset storage_units occupancy.');
    }
}
