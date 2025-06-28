<?php

namespace App\Services;

use App\Models\User;

class BorrowerStatsService
{
    public function getStats(User $borrower): array
    {
        $transactions = $borrower->transactions;
        $payments = $borrower->payments;

        $totalLoan = $transactions->sum('total_price');
        $totalPaid = $payments->sum('amount');

        return [
            'id' => $borrower->id,
            'name' => $borrower->name,
            'email' => $borrower->email,
            'phone' => $borrower->phone,
            'total_paid' => $totalPaid,
            'total_loan_amount' => $totalLoan,
            'outstanding_balance' => $totalLoan - $totalPaid,
            'transaction_count' => $transactions->count(),
            'last_transaction_date' => $transactions->sortByDesc('created_at')->first()?->created_at,
        ];
    }
}
