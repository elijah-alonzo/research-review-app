<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Load;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoadPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Load');
    }

    public function view(AuthUser $authUser, Load $load): bool
    {
        if (! $authUser->can('View:Load')) {
            return false;
        }

        if ($authUser->hasRole('faculty')) {
            return $load->user_id === $authUser->id;
        }

        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Load');
    }

    public function update(AuthUser $authUser, Load $load): bool
    {
        if (! $authUser->can('Update:Load')) {
            return false;
        }

        if ($authUser->hasRole('faculty')) {
            return $load->user_id === $authUser->id;
        }

        return true;
    }

    public function delete(AuthUser $authUser, Load $load): bool
    {
        if (! $authUser->can('Delete:Load')) {
            return false;
        }

        if ($authUser->hasRole('faculty')) {
            return $load->user_id === $authUser->id;
        }

        return true;
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Load');
    }

    public function restore(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('Restore:Load');
    }

    public function forceDelete(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('ForceDelete:Load');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Load');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Load');
    }

    public function replicate(AuthUser $authUser, Load $load): bool
    {
        return $authUser->can('Replicate:Load');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Load');
    }

}