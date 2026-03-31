<?php

namespace App\Policies;

use App\Models\Ulasan;
use App\Models\User;

class UlasanPolicy
{
    /**
     * Determine if the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Ulasan $ulasan): bool
    {
        return true;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Ulasan $ulasan): bool
    {
        return $user->id === $ulasan->user_id || $user->role === 'admin';
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Ulasan $ulasan): bool
    {
        return $user->id === $ulasan->user_id || $user->role === 'admin';
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user, Ulasan $ulasan): bool
    {
        return $user->id === $ulasan->user_id || $user->role === 'admin';
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ulasan $ulasan): bool
    {
        return $user->id === $ulasan->user_id || $user->role === 'admin';
    }
}
