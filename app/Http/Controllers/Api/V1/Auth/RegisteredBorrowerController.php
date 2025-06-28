<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreBorrowerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredBorrowerController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreBorrowerRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $storeId = Auth::user()->store_id;
        $role = 'borrower';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'store_id' => $storeId,
            'password' => Hash::make($validated['password']),
        ]);
        $user->role = $role;
        $user->assignRole($role);

        $token = $user->createToken('basic');

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }
}
