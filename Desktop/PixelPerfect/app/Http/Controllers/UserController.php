<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\AuditService;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check permissions
        $this->authorize('viewAny', User::class);

        $query = User::with(['role', 'organization']);

        // Handle search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by organization
        if ($request->has('organization_id')) {
            $query->where('organization_id', $request->input('organization_id'));
        }

        // Filter by role
        if ($request->has('role_id')) {
            $query->where('role_id', $request->input('role_id'));
        }

        $users = $query->paginate(15);
        $roles = Role::all();
        $organizations = Organization::all();

        return view('users.index', compact('users', 'roles', 'organizations'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::all();
        $organizations = Organization::all();

        return view('users.create', compact('roles', 'organizations'));
    }

    /**
     * Store a newly created user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'organization_id' => $request->organization_id,
            'is_validated' => true,
        ]);

        // Log for audit trail
        Log::info('User created', [
            'user_id' => $user->id,
            'created_by' => Auth::id(),
            'role_id' => $user->role_id,
            'organization_id' => $user->organization_id
        ]);

        AuditService::log(
            'user_created',
            "User {$user->name} was created",
            null,
            ['id' => $user->id, 'email' => $user->email, 'role_id' => $user->role_id]
        );


        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = Role::all();
        $organizations = Organization::all();

        return view('users.edit', compact('user', 'roles', 'organizations'));
    }

    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        $oldRole = $user->role_id;
        $oldOrg = $user->organization_id;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'organization_id' => $request->organization_id,
        ]);

        // Log for audit trail
        if ($oldRole != $user->role_id || $oldOrg != $user->organization_id) {
            AuditService::log(
                'user_access_changed',
                "Access level changed for user {$user->name}",
                ['role_id' => $oldRole, 'organization_id' => $oldOrg],
                ['role_id' => $user->role_id, 'organization_id' => $user->organization_id]
            );
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Archive the user account.
     */
    public function archive(User $user)
    {
        $this->authorize('archive', $user);

        $user->update(['is_archived' => true]);

        // Log for audit trail
        Log::info('User archived', [
            'user_id' => $user->id,
            'archived_by' => Auth::id()
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User archived successfully');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $this->authorize('resetPassword', $user);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log for audit trail
        Log::info('User password reset', [
            'user_id' => $user->id,
            'reset_by' => Auth::id()
        ]);

        return redirect()->route('users.edit', $user)
            ->with('success', 'Password reset successfully');
    }
}
