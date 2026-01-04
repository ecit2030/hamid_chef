<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChefWallet;

class ChefWalletPolicy
{
    /**
     * Determine if user can view any wallets (only their own)
     */
    public function viewAny(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can view a specific wallet
     */
    public function view(User $user, ChefWallet $wallet): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $wallet->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can withdraw from wallet
     */
    public function withdraw(User $user, ChefWallet $wallet): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $wallet->chef_id === $user->chef->id;
    }
}
