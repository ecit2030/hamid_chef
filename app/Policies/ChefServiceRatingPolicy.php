<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChefServiceRating;

class ChefServiceRatingPolicy
{
    /**
     * Determine if user can view any ratings (only their own as chef)
     */
    public function viewAny(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can view a specific rating
     * Chef can view ratings for their services
     * Customer can view their own ratings
     */
    public function view(User $user, ChefServiceRating $rating): bool
    {
        // Chef can view ratings for their services
        if ($user->chef !== null && $rating->chef_id === $user->chef->id) {
            return true;
        }

        // Customer can view their own ratings
        if ($rating->customer_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can create a rating (only customers who completed a booking)
     */
    public function create(User $user): bool
    {
        // Only customers can create ratings
        return $user->user_type === 'customer';
    }

    /**
     * Determine if user can update a rating (only the customer who created it)
     */
    public function update(User $user, ChefServiceRating $rating): bool
    {
        return $rating->customer_id === $user->id;
    }

    /**
     * Determine if user can delete a rating (only the customer who created it)
     */
    public function delete(User $user, ChefServiceRating $rating): bool
    {
        return $rating->customer_id === $user->id;
    }
}
