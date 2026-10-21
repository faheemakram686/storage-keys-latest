<?php

namespace App\Console\Commands\Backend;

use App\Services\NgeniusPaymentReconciliationService;
use Illuminate\Console\Command;

class ReconcileNgeniusPayments extends Command
{
    protected $signature = 'ngenius:reconcile-payments
                            {--days=7 : Only check payments from the last N days}
                            {--limit=50 : Max shop orders and invoice attempts to check}';

    protected $description = 'Confirm N-Genius payments that never returned via the browser redirect';

    public function handle(NgeniusPaymentReconciliationService $reconciliation): int
    {
        $days = max(1, (int) $this->option('days'));
        $limit = max(1, (int) $this->option('limit'));

        $result = $reconciliation->reconcile($days, $limit);

        $this->info(sprintf(
            'Reconciled %d shop order(s) and %d invoice payment(s).',
            $result['orders_settled'],
            $result['invoices_settled']
        ));

        return self::SUCCESS;
    }
}
