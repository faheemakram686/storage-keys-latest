<?php

use App\Services\Core\Auth\StaffRoleUnificationMigrator;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Unify CRM (admin) and HRM (tenant) into one staff role per user.
     * Idempotent: safe to re-run.
     */
    public function up(): void
    {
        resolve(StaffRoleUnificationMigrator::class)->run();
    }

    public function down(): void
    {
        // Irreversible data migration — dual-role collapse cannot be undone safely.
    }
};
