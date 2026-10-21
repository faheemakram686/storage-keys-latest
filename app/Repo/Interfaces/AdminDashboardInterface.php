<?php
namespace App\Repo\Interfaces;

interface AdminDashboardInterface
{
    public function getLeadsCount();
    public function getCustomersCount();
    public function getEstimatesCount();
    public function getContractCount();
    public function getProductsCount();
    public function getAddonsCount();
    public function getStorageUnitsCount();
    public function getCollectedThisMonth(): float;
    public function getOutstandingBalance(): float;
    public function getOverdueInvoiceCount(): int;
    public function getOccupancyPercent(): float;
    public function getSalesStatistics(string $period = 'weekly'): array;
    public function getLeadStatistics(): array;
    public function getStorageUnitStatistics(): array;
    public function getInvoicePaymentStatistics(): array;
    public function getRecentOrders(int $limit = 8);
    public function getRecentInvoices(int $limit = 8);
    public function getContractsEndingSoon(int $days = 30, int $limit = 8);
}
