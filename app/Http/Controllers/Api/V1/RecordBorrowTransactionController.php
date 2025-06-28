<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTransactionRequest;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecordBorrowTransactionController extends Controller
{
    public function __invoke(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        try {
            $transaction = DB::transaction(fn() => $this->createTransaction($validated));

            return response()->json([
                'message' => 'Transaction recorded successfully.',
                'transaction' => $transaction->load('items')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function createTransaction(array $validated): Transaction
    {
        $ownerStoreId = Auth::user()->store_id;
        $borrowerStoreId = User::where('id', $validated['borrower_id'])
            ->value('store_id');

        if ($borrowerStoreId != $ownerStoreId) {
            throw new AuthorizationException('Borrower does not belong to your store.');
        }

        $transaction = Transaction::create([
            'proof_image' => $validated['proof_image'] ?? null,
            'total_price' => 0,
            'borrower_id' => $validated['borrower_id'],
            'store_id' => $ownerStoreId,
            'due_date' => $validated['due_date'],
        ]);

        $this->createItems(
            $validated['items'],
            $validated['borrower_id'],
            $transaction->id,
            $transaction
        );


        return $transaction;
    }

    // For every provided items input, insert them as batch and
    // calculate the grand total price and update the transactions total_price
    private function createItems(array $itemsData, int $borrowerId, int $transactionId, Transaction $transaction): void
    {
        $items = [];
        $grandTotal = 0;

        foreach ($itemsData as $item) {
            $items[] = [
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'user_id' => $borrowerId,
                'transaction_id' => $transactionId,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $grandTotal += Item::computeTotalPrice($item);
        }

        Item::insert($items);

        $transaction->update(['total_price' => $grandTotal]);
    }
}
