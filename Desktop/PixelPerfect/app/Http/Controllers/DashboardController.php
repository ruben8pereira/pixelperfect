<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;
use App\Models\Organization;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
{
    // Eager load the relationships to ensure they're properly loaded
    $user = User::with(['role', 'organization'])->find(Auth::id());

    // Check if the user has a role before proceeding
    if (!$user->role) {
        // Handle the case when the user doesn't have a role
        // This could redirect to an error page or set a default role
        return view('dashboard.index', [
            'error' => 'User role not found. Please contact an administrator.',
            'reports' => 0,
            'recent_reports' => collect(),
        ]);
    }

    // Different dashboard stats based on user role
    if ($user->role->name === 'Administrator') {
        $organizations = Organization::count();
        $users = User::count();
        $reports = Report::count();

        $recent_reports = Report::with(['organization', 'reportDefects'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('organizations', 'users', 'reports', 'recent_reports'));

    } elseif ($user->role->name === 'Organization') {
        // Organization dashboard - check if organization_id exists
        if (!$user->organization_id) {
            // Handle missing organization
            $users = 0;
            $reports = 0;
            $recent_reports = collect();
        } else {
            $users = User::where('organization_id', $user->organization_id)->count();
            $reports = Report::where('organization_id', $user->organization_id)->count();

            $recent_reports = Report::with(['reportDefects'])
                ->where('organization_id', $user->organization_id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact('users', 'reports', 'recent_reports'));

    } else {
        // Regular user dashboard
        $reports = Report::where('created_by', $user->id)->count();

        $recent_reports = Report::with(['organization', 'reportDefects'])
            ->where('created_by', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('reports', 'recent_reports'));
    }
}
}
