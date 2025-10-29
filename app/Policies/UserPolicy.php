<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view all users.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view their own profile.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can update their own data.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
