<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BorrowerFinanceService;
use App\Services\BorrowerStatsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowerController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(BorrowerStatsService $service): JsonResponse
    {
        $this->authorize('viewAnyBorrowers', User::class);

        $borrowers = User::role('borrower')
            ->where('store_id', Auth::user()->store_id)
            ->with(['transactions', 'payments'])
            ->get();

        $data = $borrowers->map(fn($borrower) => $service->getStats($borrower));

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, BorrowerFinanceService $service)
    {
        $borrower = User::with(['transactions.items', 'roles', 'payments'])
            ->findOrFail($id);

        if (!$borrower->hasRole('borrower')) {
            abort(404, 'Borrower not found.');
        }

        //check the current store owner allowed to view the borrower data
        $this->authorize('view', $borrower);

        // Overall borrower stats
        $borrower->outstanding_balance = $service->outstandingBalance($borrower);
        $borrower->total_paid = $service->totalPaid($borrower);
        $borrower->overdue_transactions = $service->overdueTransactions($borrower);

        //per transaction stats
        $payments = $borrower->payments->groupBy('transaction_id');

        $borrower->transactions->each(function ($transaction) use ($payments) {
            $paid = $payments[$transaction->id] ?? collect(); // safe default
            $paidAmount = $paid->sum('amount');

            $transaction->paid_amount = $paidAmount;
            $transaction->outstanding_balance = $transaction->total_price - $paidAmount;
        });

        return response()->json($borrower);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
