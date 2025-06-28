<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class BorrowerFinanceService
{
    public function totalPaid(User $borrower): float
    {
        return $borrower->payments()->sum('amount');
    }

    public function totalLoanAmount(User $borrower): float
    {
        return $borrower->transactions()->sum('total_price');
    }

    public function overdueTransactions(User $borrower)
    {
        return Transaction::where([
            ['borrower_id', '=', $borrower->id],
            ['due_date', '<', now()->format('Y-m-d')]
        ])
            ->get();
    }

    //get the borrower user outstanding balance
    public function outstandingBalance(User $borrower): float
    {
        return $this->totalLoanAmount($borrower) - $this->totalPaid($borrower);
    }
}
