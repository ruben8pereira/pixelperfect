<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Report;
use App\Models\Organization;
use App\Policies\UserPolicy;
use App\Policies\ReportPolicy;
use App\Policies\OrganizationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Report::class => ReportPolicy::class,
        Organization::class => OrganizationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Administrator Gates
        Gate::define('admin-manage-system', function (User $user) {
            return $user->role->name === 'Administrator';
        });

        Gate::define('admin-archive-users', function (User $user) {
            return $user->role->name === 'Administrator';
        });

        Gate::define('admin-manage-permissions', function (User $user) {
            return $user->role->name === 'Administrator';
        });

        // Note: Administrator CANNOT see reports by design
        Gate::define('view-reports', function (User $user) {
            // Organization can see reports from their own organization
            if ($user->role->name === 'Organization') {
                return $user->organization_id !== null;
            }

            // Registered User can see their own organization's reports
            if ($user->role->name === 'User') {
                return $user->organization_id !== null;
            }

            // Guest can only see shared reports
            return false;
        });

        // Organization Gates
        Gate::define('organization-invite-users', function (User $user) {
            return $user->role->name === 'Organization';
        });

        Gate::define('organization-manage-reports', function (User $user) {
            return $user->role->name === 'Organization';
        });

        // Registered User Gates
        Gate::define('create-edit-reports', function (User $user) {
            return in_array($user->role->name, ['User', 'Organization']);
        });

        Gate::define('export-pdf', function (User $user) {
            return in_array($user->role->name, ['User', 'Organization']);
        });

        // Guest Gates
        Gate::define('view-shared-reports', function (User $user) {
            return $user->role->name === 'Guest';
        });
    }
}
