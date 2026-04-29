<?php

namespace App\Policies;

use App\Models\Load;
use App\Models\User;

class LoadPolicy
{
    /**
     * Determine whether the user can view any loads.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the load.
     */
    public function view(User $user, Load $load): bool
    {
        // Deans can view any load
        if ($user->isDean()) {
            return true;
        }

        // Faculty can only view their own loads
        return $user->id === $load->user_id;
    }

    /**
     * Determine whether the user can create loads.
     */
    public function create(User $user): bool
    {
        // Only deans can create loads
        return $user->isDean();
    }

    /**
     * Determine whether the user can update the load.
     */
    public function update(User $user, Load $load): bool
    {
        // Only deans can update loads
        return $user->isDean();
    }

    /**
     * Determine whether the user can delete the load.
     */
    public function delete(User $user, Load $load): bool
    {
        // Only deans can delete loads
        return $user->isDean();
    }

    /**
     * Determine whether the user can restore the load.
     */
    public function restore(User $user, Load $load): bool
    {
        return $user->isDean();
    }

    /**
     * Determine whether the user can permanently delete the load.
     */
    public function forceDelete(User $user, Load $load): bool
    {
        return $user->isDean();
    }
}
