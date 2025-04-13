<?php

namespace App\Http\Controllers;

use App\Models\UserInvitation;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Carbon\Carbon;

class InvitationController extends Controller
{
    /**
     * Display a listing of the invitations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if user is admin or organization manager
        if (!in_array(Auth::user()->role->name, ['Administrator', 'Organization'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access invitations.');
        }

        $query = UserInvitation::with(['organization', 'inviter', 'role']);

        // Filter by organization for non-admins
        if (Auth::user()->role->name != 'Administrator') {
            $query->where('organization_id', Auth::user()->organization_id);
        }

        $invitations = $query->latest()->paginate(20);
        $roles = Role::where('name', '!=', 'Administrator')->get();

        return view('invitations.index', compact('invitations', 'roles'));
    }

    /**
     * Store a newly created invitation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if user is admin or organization manager
        if (!in_array(Auth::user()->role->name, ['Administrator', 'Organization'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to create invitations.');
        }

        $request->validate([
            'email' => 'required|email',
            'role_id' => 'required|exists:roles,id',
            'organization_id' => Auth::user()->role->name === 'Administrator' ? 'required|exists:organizations,id' : '',
        ]);

        // Determine organization ID
        $organizationId = Auth::user()->role->name === 'Administrator'
            ? $request->organization_id
            : Auth::user()->organization_id;

        // Check if email already exists in the system
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()
                ->with('error', 'A user with this email already exists.')
                ->withInput();
        }

        // Check if invitation already exists and is not expired
        $existingInvitation = UserInvitation::where('email', $request->email)
            ->where('organization_id', $organizationId)
            ->where('expires_at', '>', now())
            ->where('is_used', false)
            ->first();

        if ($existingInvitation) {
            return redirect()->back()
                ->with('error', 'An invitation has already been sent to this email address.')
                ->withInput();
        }

        // Create a new invitation
        $invitation = UserInvitation::create([
            'email' => $request->email,
            'organization_id' => $organizationId,
            'invited_by' => Auth::id(),
            'role_id' => $request->role_id,
            'token' => Str::random(64),
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        // Send invitation email
        try {
            Mail::to($request->email)->send(new InvitationMail($invitation));

            return redirect()->route('invitations.index')
                ->with('success', 'Invitation sent successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while sending the invitation: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the acceptance form for an invitation.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function accept($token)
    {
        $invitation = UserInvitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->where('is_used', false)
            ->firstOrFail();

        return view('invitations.accept', compact('invitation'));
    }

    /**
     * Process the accepted invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request, $token)
    {
        $invitation = UserInvitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->where('is_used', false)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => bcrypt($request->password),
            'role_id' => $invitation->role_id,
            'organization_id' => $invitation->organization_id,
            'is_validated' => true,
        ]);

        // Mark invitation as used
        $invitation->update(['is_used' => true]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Your account has been created successfully.');
    }

    /**
     * Resend an invitation.
     *
     * @param  \App\Models\UserInvitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function resend(UserInvitation $invitation)
    {
        // Check if user is admin or organization manager
        if (!in_array(Auth::user()->role->name, ['Administrator', 'Organization'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to resend invitations.');
        }

        // Check if organization matches for non-admins
        if (Auth::user()->role->name === 'Organization' &&
            Auth::user()->organization_id !== $invitation->organization_id) {
            return redirect()->back()
                ->with('error', 'You do not have permission to manage this invitation.');
        }

        // Update expiration date
        $invitation->update([
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        // Resend invitation email
        try {
            Mail::to($invitation->email)->send(new InvitationMail($invitation));

            return redirect()->route('invitations.index')
                ->with('success', 'Invitation resent successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while resending the invitation: ' . $e->getMessage());
        }
    }

    /**
     * Cancel an invitation.
     *
     * @param  \App\Models\UserInvitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function cancel(UserInvitation $invitation)
    {
        // Check if user is admin or organization manager
        if (!in_array(Auth::user()->role->name, ['Administrator', 'Organization'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to cancel invitations.');
        }

        // Check if organization matches for non-admins
        if (Auth::user()->role->name === 'Organization' &&
            Auth::user()->organization_id !== $invitation->organization_id) {
            return redirect()->back()
                ->with('error', 'You do not have permission to manage this invitation.');
        }

        try {
            $invitation->delete();

            return redirect()->route('invitations.index')
                ->with('success', 'Invitation cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while cancelling the invitation: ' . $e->getMessage());
        }
    }
}
