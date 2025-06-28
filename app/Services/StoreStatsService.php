<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;

class StoreStatsService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected BorrowerFinanceService $borrowerFinancial
    ) {
    }

    public function totalBorrowers(Store $store): int
    {
        return $store->borrowers()->count();
    }

    public function monthlyRevenue(Store $store): int
    {
        return $store->payments()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    public function totalOutstanding(Store $store): float
    {
        return $store->borrowers()
            ->with(['transactions', 'payments'])
            ->get()
            ->sum(function (User $borrower) {
                return $this->borrowerFinancial
                    ->outstandingBalance($borrower);
            });
    }
}
