<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any organizations.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // Only administrators can view all organizations
        return $user->role->name === 'Administrator';
    }

    /**
     * Determine if the user can view the organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return bool
     */
    public function view(User $user, Organization $organization)
    {
        // Admins can view all organizations
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Users can view their own organization
        return $user->organization_id === $organization->id;
    }

    /**
     * Determine if the user can create organizations.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        // Only administrators can create organizations
        return $user->role->name === 'Administrator';
    }

    /**
     * Determine if the user can update the organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return bool
     */
    public function update(User $user, Organization $organization)
    {
        // Admins can update all organizations
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can update their own organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $organization->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return bool
     */
    public function delete(User $user, Organization $organization)
    {
        // Only administrators can delete organizations
        return $user->role->name === 'Administrator';
    }

    /**
     * Determine if the user can restore the organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return bool
     */
    public function restore(User $user, Organization $organization)
    {
        // Only administrators can restore organizations
        return $user->role->name === 'Administrator';
    }

    /**
     * Determine if the user can permanently delete the organization.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return bool
     */
    public function forceDelete(User $user, Organization $organization)
    {
        // Only administrators can permanently delete organizations
        return $user->role->name === 'Administrator';
    }
}
