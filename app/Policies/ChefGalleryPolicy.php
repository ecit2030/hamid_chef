<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChefGallery;

class ChefGalleryPolicy
{
    /**
     * Determine if user can view any gallery images (only their own)
     */
    public function viewAny(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can view a specific gallery image
     */
    public function view(User $user, ChefGallery $gallery): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $gallery->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can create gallery images
     */
    public function create(User $user): bool
    {
        return $user->chef !== null;
    }

    /**
     * Determine if user can update a gallery image
     */
    public function update(User $user, ChefGallery $gallery): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $gallery->chef_id === $user->chef->id;
    }

    /**
     * Determine if user can delete a gallery image
     */
    public function delete(User $user, ChefGallery $gallery): bool
    {
        if ($user->chef === null) {
            return false;
        }

        return $gallery->chef_id === $user->chef->id;
    }
}
