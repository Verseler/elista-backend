<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\StoreStoreOwnerRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredStoreOwnerController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreStoreOwnerRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $role = 'store_owner';

        try {
            $user = DB::transaction(function () use ($validated, $role): User {
                $store = Store::create([
                    'name' => $validated['store_name'],
                    'image' => $validated['store_image'] ?? null,
                    'location' => $validated['store_location'] ?? null,
                ]);

                $newUser = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'store_id' => $store->id,
                    'password' => Hash::make($validated['password'])
                ]);
                $newUser->store;
                $newUser->assignRole($role);
                $newUser->role = $role;

                event(new Registered($newUser));

                Auth::login($newUser);

                return $newUser;
            });

            $token = $user->createToken('basic');

            return response()->json([
                'user' => $user,
                'token' => $token->plainTextToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

