<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StorePaymentRequest;
use App\Models\Payment;
use App\Models\User;

class StorePaymentController extends Controller
{
    public function __invoke(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        $borrower = User::findOrFail($validated['user_id']);

        return response()->json(Payment::create([
            'amount' => $validated['amount'],
            'user_id' => $borrower->id,
            'store_id' => $borrower->store_id,
            'notes' => $validated['notes'] ?? null,
        ]));
    }
}
