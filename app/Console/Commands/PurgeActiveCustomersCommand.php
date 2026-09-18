<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeActiveCustomersCommand extends Command
{
    protected $signature = 'customers:purge-active
                            {--dry-run : Only show how many Active customers would be removed}
                            {--force : Run without interactive confirmation}';

    protected $description = 'Soft-delete all Active customers (status=1). Keeps In-Active (status=0) customers.';

    public function handle(): int
    {
        $query = Customer::query()
            ->where('is_deleted', 0)
            ->where('status', 1);

        $count = (clone $query)->count();

        if ($count === 0) {
            $this->info('No Active customers found to remove.');
            return self::SUCCESS;
        }

        $inactiveKept = Customer::query()
            ->where('is_deleted', 0)
            ->where('status', 0)
            ->count();

        $this->warn("Active customers to soft-delete: {$count}");
        $this->info("In-Active customers that will be kept: {$inactiveKept}");

        $sample = (clone $query)->orderByDesc('id')->limit(10)->get(['id', 'customer_name', 'company_name', 'email', 'created_at']);
        if ($sample->isNotEmpty()) {
            $this->table(
                ['ID', 'Name', 'Company', 'Email', 'Created'],
                $sample->map(fn ($c) => [
                    $c->id,
                    $c->customer_name,
                    $c->company_name,
                    $c->email,
                    optional($c->created_at)->toDateTimeString(),
                ])->all()
            );
        }

        if ($this->option('dry-run')) {
            $this->comment('Dry run only — nothing was deleted.');
            return self::SUCCESS;
        }

        if (!$this->option('force') && !$this->confirm('Soft-delete ALL Active customers now?', false)) {
            $this->comment('Cancelled.');
            return self::SUCCESS;
        }

        $ids = (clone $query)->pluck('id');

        DB::transaction(function () use ($ids) {
            Customer::query()
                ->whereIn('id', $ids)
                ->update(['is_deleted' => 1]);

            Contact::query()
                ->whereIn('customer_id', $ids)
                ->where('is_deleted', 0)
                ->update(['is_deleted' => 1, 'status' => 0]);
        });

        $this->info("Done. Soft-deleted {$ids->count()} Active customer(s). In-Active list is unchanged.");

        return self::SUCCESS;
    }
}
