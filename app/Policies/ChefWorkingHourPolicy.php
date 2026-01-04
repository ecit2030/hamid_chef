<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChefWorkingHour;

class ChefWorkingHourPolicy
{
    /**
     * Determine if user can view any working hours (only their own)
     */
    public function viewAny(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can view a specific working hour
     */
    public function view(User $user, ChefWorkingHour $workingHour): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $workingHour->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can create working hours
     */
    public function create(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can update a working hour
     */
    public function update(User $user, ChefWorkingHour $workingHour): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $workingHour->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can delete a working hour
     */
    public function delete(User $user, ChefWorkingHour $workingHour): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $workingHour->chef_id === $user->chef->id;
    }
}
