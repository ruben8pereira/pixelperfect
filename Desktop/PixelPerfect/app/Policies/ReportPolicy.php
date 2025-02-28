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
        // Organization can view reports from their organization
        if ($user->role->name === 'Organization') {
            return true;
        }

        // Registered Users can view reports from their organization
        if ($user->role->name === 'User') {
            return true;
        }

        // Guests and Administrators cannot view reports list
        return false;
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
        // Organization can view reports from their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered Users can view reports from their organization
        if ($user->role->name === 'User') {
            return $user->organization_id === $report->organization_id;
        }

        // Guests can only view shared reports (future implementation)
        if ($user->role->name === 'Guest') {
            // TODO: Implement shared report logic
            return false;
        }

        // Administrators cannot view reports
        return false;
    }

    /**
     * Determine if the user can create reports.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return in_array($user->role->name, ['User', 'Organization']);
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
        // Organization can update reports in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered Users can update their own reports
        if ($user->role->name === 'User') {
            return $user->id === $report->created_by &&
                   $user->organization_id === $report->organization_id;
        }

        // Guests and Administrators cannot update reports
        return false;
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
        // Organization can delete reports in their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered Users can delete their own reports
        if ($user->role->name === 'User') {
            return $user->id === $report->created_by &&
                   $user->organization_id === $report->organization_id;
        }

        // Guests and Administrators cannot delete reports
        return false;
    }

    public function exportPdf(User $user, Report $report)
    {
        // Organization can export reports from their organization
        if ($user->role->name === 'Organization') {
            return $user->organization_id === $report->organization_id;
        }

        // Registered Users can export their own reports
        if ($user->role->name === 'User') {
            return $user->organization_id === $report->organization_id;
        }

        // Guests and Administrators cannot export reports
        return false;
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
