<?php

namespace App\Console\Commands;

use App\Models\PendingCustomerRegistration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupPendingRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:cleanup-pending-registrations {--days=7 : Number of days to keep pending registrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old pending customer registrations that were never verified';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        
        if ($days < 1) {
            $this->error('Days must be at least 1.');
            return 1;
        }

        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Cleaning up pending registrations older than {$days} days (before {$cutoffDate->toDateTimeString()})...");
        
        $count = PendingCustomerRegistration::where('created_at', '<', $cutoffDate)->count();
        
        if ($count === 0) {
            $this->info('No pending registrations found to clean up.');
            return 0;
        }
        
        $this->line("Found {$count} pending registration(s) to delete.");
        
        if ($this->confirm('Do you want to proceed with deletion?', true)) {
            $deleted = PendingCustomerRegistration::where('created_at', '<', $cutoffDate)->delete();
            
            $this->info("Successfully deleted {$deleted} pending registration(s).");
            $this->line('');
            $this->line('Cleanup Summary:');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Records Found', $count],
                    ['Records Deleted', $deleted],
                    ['Cutoff Date', $cutoffDate->toDateTimeString()],
                    ['Retention Period', "{$days} days"]
                ]
            );
            
            return 0;
        }
        
        $this->warn('Cleanup cancelled by user.');
        return 0;
    }
}
