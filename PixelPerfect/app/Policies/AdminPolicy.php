<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Report $report)
    {
        // Administrators cannot view reports in Filament
        if ($user->role->name === 'Administrator') {
            return false;
        }

        // Organization can view reports from their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered Users can view reports from their organization
        if ($user->role->name === 'User') {
            return $user->organization_id === $report->organization_id;
        }

        return false;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user)
    {
        // Administrators cannot create reports in Filament
        if ($user->role->name === 'Administrator') {
            return false;
        }

        // Organization and Registered Users can create reports
        return in_array($user->role->name, ['Organization', 'User']);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Report $report)
    {
        // Administrators cannot update reports in Filament
        if ($user->role->name === 'Administrator') {
            return false;
        }

        // Organization can update reports in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered Users can update their own reports
        if ($user->role->name === 'User') {
            return $user->id === $report->created_by &&
                   $user->organization_id === $report->organization_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Report $report)
    {
        // Administrators cannot delete reports in Filament
        if ($user->role->name === 'Administrator') {
            return false;
        }

        // Organization can delete reports in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered Users can delete their own reports
        if ($user->role->name === 'User') {
            return $user->id === $report->created_by &&
                   $user->organization_id === $report->organization_id;
        }

        return false;
    }
}
