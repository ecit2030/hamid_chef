<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChefWithdrawalRequest;

class ChefWithdrawalRequestPolicy
{
    /**
     * Determine if user can view any withdrawal requests (only their own)
     */
    public function viewAny(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can view a specific withdrawal request
     */
    public function view(User $user, ChefWithdrawalRequest $request): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $request->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can create withdrawal requests
     */
    public function create(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can cancel a withdrawal request (only pending ones)
     */
    public function cancel(User $user, ChefWithdrawalRequest $request): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $request->chef_id === $user->chef->id && $request->status === 'pending';
    }
}
