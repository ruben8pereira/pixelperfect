<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
    public function boot()
    {
        $this->registerPolicies();

        // Define role-based permissions
        Gate::define('access-admin', function (User $user) {
            return $user->role->name === 'Administrator';
        });

        Gate::define('manage-organization', function (User $user) {
            return in_array($user->role->name, ['Administrator', 'Organization']);
        });

        Gate::define('create-reports', function (User $user) {
            return in_array($user->role->name, ['Administrator', 'Organization', 'RegisteredUser']);
        });

        Gate::define('invite-users', function (User $user) {
            return in_array($user->role->name, ['Administrator', 'Organization']);
        });
    }
}
