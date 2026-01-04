<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChefVacation;

class ChefVacationPolicy
{
    /**
     * Determine if user can view any vacations (only their own)
     */
    public function viewAny(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can view a specific vacation
     */
    public function view(User $user, ChefVacation $vacation): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $vacation->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can create vacations
     */
    public function create(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can update a vacation
     */
    public function update(User $user, ChefVacation $vacation): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $vacation->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can delete a vacation
     */
    public function delete(User $user, ChefVacation $vacation): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $vacation->chef_id === $user->chef->id;
    }
}
