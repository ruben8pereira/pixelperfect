<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any reports.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // All authenticated users can view reports list
        // Filtering is handled in the controller
        return true;
    }

    /**
     * Determine if the user can view the report.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Report  $report
     * @return bool
     */
    public function view(User $user, Report $report)
    {
        // Admins can view all reports
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can view reports from their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered users can view reports from their organization
        if ($user->role->name === 'RegisteredUser') {
            return $user->organization_id === $report->organization_id;
        }

        // Basic users can only view their own reports
        return $user->id === $report->created_by;
    }

    /**
     * Determine if the user can create reports.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        // BasicUsers cannot create reports, all others can
        return $user->role->name !== 'BasicUser';
    }

    /**
     * Determine if the user can update the report.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Report  $report
     * @return bool
     */
    public function update(User $user, Report $report)
    {
        // Admins can update all reports
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can update reports from their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered users can update their own reports
        if ($user->role->name === 'RegisteredUser') {
            return $user->id === $report->created_by &&
                   $user->organization_id === $report->organization_id;
        }

        // Basic users can update their own reports
        return $user->id === $report->created_by;
    }

    /**
     * Determine if the user can delete the report.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Report  $report
     * @return bool
     */
    public function delete(User $user, Report $report)
    {
        // Admins can delete all reports
        if ($user->role->name === 'Administrator') {
            return true;
        }

        // Organization managers can delete reports from their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered users can delete their own reports
        if ($user->role->name === 'RegisteredUser') {
            return $user->id === $report->created_by &&
                   $user->organization_id === $report->organization_id;
        }

        // Basic users can delete their own reports
        return $user->id === $report->created_by;
    }

    /**
     * Determine if the user can restore the report.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Report  $report
     * @return bool
     */
    public function restore(User $user, Report $report)
    {
        // Only admins can restore deleted reports
        return $user->role->name === 'Administrator';
    }

    /**
     * Determine if the user can permanently delete the report.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Report  $report
     * @return bool
     */
    public function forceDelete(User $user, Report $report)
    {
        // Only admins can force delete reports
        return $user->role->name === 'Administrator';
    }
}
