<?php

namespace App\Policies;

use App\Models\User;

class BorrowerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyBorrowers(User $user): bool
    {
        return $user->hasRole('store_owner') &&
            $user->can('view borrowers');
    }

    /**
     * Determine whether the user can view the borrower.
     */
    public function view(User $storeOwner, User $borrower): bool
    {
        return $storeOwner->hasRole(['store_owner', 'borrower']) &&
            $storeOwner->can('view borrower lent items') &&
            $borrower->hasRole('borrower') &&
            $storeOwner->store_id == $borrower->store_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('store_owner') &&
            $user->can('create borrower');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
