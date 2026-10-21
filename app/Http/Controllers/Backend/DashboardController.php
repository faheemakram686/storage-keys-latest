<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\AdminDashboardInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $dashboard;

    public function __construct(AdminDashboardInterface $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'leads' => $this->dashboard->getLeadsCount(),
            'estimates' => $this->dashboard->getEstimatesCount(),
            'customers' => $this->dashboard->getCustomersCount(),
            'contracts' => $this->dashboard->getContractCount(),
            'addons' => $this->dashboard->getAddonsCount(),
            'products' => $this->dashboard->getProductsCount(),
            'storageunits' => $this->dashboard->getStorageUnitsCount(),
            'collected_this_month' => $this->dashboard->getCollectedThisMonth(),
            'outstanding_balance' => $this->dashboard->getOutstandingBalance(),
            'overdue_invoices' => $this->dashboard->getOverdueInvoiceCount(),
            'occupancy_percent' => $this->dashboard->getOccupancyPercent(),
            'sales' => $this->dashboard->getSalesStatistics('weekly'),
            'lead_stats' => $this->dashboard->getLeadStatistics(),
            'storage_stats' => $this->dashboard->getStorageUnitStatistics(),
            'invoice_stats' => $this->dashboard->getInvoicePaymentStatistics(),
            'orders' => $this->dashboard->getRecentOrders(8),
            'recent_invoices' => $this->dashboard->getRecentInvoices(8),
            'contracts_ending' => $this->dashboard->getContractsEndingSoon(30, 8),
        ];

        return view('backend.dashboard')->with(compact('data'));
    }

    public function getSalesStat(Request $request)
    {
        $period = $request->query('period', 'weekly');

        return response()->json($this->dashboard->getSalesStatistics($period));
    }

    public function getLeadStat()
    {
        return response()->json($this->dashboard->getLeadStatistics());
    }
}
