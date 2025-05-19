<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Penerima;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenerimaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_penerima');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Penerima $penerima): bool
    {
        return $user->can('view_penerima');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_penerima');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Penerima $penerima): bool
    {
        return $user->can('update_penerima');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Penerima $penerima): bool
    {
        return $user->can('delete_penerima');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_penerima');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Penerima $penerima): bool
    {
        return $user->can('force_delete_penerima');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_penerima');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Penerima $penerima): bool
    {
        return $user->can('restore_penerima');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_penerima');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Penerima $penerima): bool
    {
        return $user->can('replicate_penerima');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_penerima');
    }
}
