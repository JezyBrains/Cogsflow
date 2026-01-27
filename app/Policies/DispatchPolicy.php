<?php

namespace App\Policies;

use App\Models\Dispatch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DispatchPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('logistics') || $user->hasRole('procurement') || $user->hasRole('finance');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dispatch $dispatch): bool
    {
        return $user->hasRole('admin') ||
            $user->hasRole('logistics') ||
            $user->hasRole('procurement') ||
            $user->hasRole('finance');
    }

    public function update(User $user, Dispatch $dispatch): bool
    {
        return $user->hasRole('admin') || $user->hasRole('logistics');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dispatch $dispatch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dispatch $dispatch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dispatch $dispatch): bool
    {
        return false;
    }
}
