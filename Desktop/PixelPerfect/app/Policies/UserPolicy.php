<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // Only administrators and organization managers can view user lists
        return in_array($user->role->name, ['Administrator', 'Organization']);
    }

    /**
     * Determine if the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function view(User $user, User $model)
    {
        // Admins can view all users
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can view users in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $model->organization_id;
        }

        // Users can view their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine if the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        // Only administrators and organization managers can create users
        return in_array($user->role->name, ['Administrator', 'Organization']);
    }

    /**
     * Determine if the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function update(User $user, User $model)
    {
        // Admins can update any user
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can update users in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $model->organization_id;
        }

        // Users can update their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine if the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function delete(User $user, User $model)
    {
        // Prevent users from deleting themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Admins can delete any user (except themselves)
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can delete users in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $model->organization_id &&
                   $model->role->name !== 'Administrator' &&
                   $model->role->name !== 'Organization';
        }

        return false;
    }

    /**
     * Determine if the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function restore(User $user, User $model)
    {
        // Only administrators can restore users
        return $user->role->name === 'Administrator';
    }

    /**
     * Determine if the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function forceDelete(User $user, User $model)
    {
        // Only administrators can permanently delete users
        return $user->role->name === 'Administrator';
    }

    /**
     * Determine if the user can validate other users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function validate(User $user, User $model)
    {
        // Admins can validate any user
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can validate users in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $model->organization_id &&
                   $model->role->name !== 'Administrator';
        }

        return false;
    }

    /**
     * Determine if the user can archive other users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function archive(User $user, User $model)
    {
        // Same permissions as delete
        return $this->delete($user, $model);
    }
}
