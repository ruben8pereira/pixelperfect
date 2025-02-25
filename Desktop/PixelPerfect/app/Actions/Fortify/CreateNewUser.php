<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser Implements CreatesNewUsers
{
   /* public function create(array $input)
    {
        Log::info('CreateNewUser input:', $input);
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'create_organization' => ['sometimes', 'boolean'],
            'organization_name' => ['required_if:create_organization,1', 'string', 'max:255'],
            'organization_id' => ['required_if:create_organization,0', 'nullable', 'exists:organizations,id'],
        ])->validate();

        // Create organization if needed
        $organizationId = null;
        if (!empty($input['create_organization']) && $input['create_organization']) {
            $organization = Organization::create([
                'name' => $input['organization_name'],
                'description' => $input['organization_description'] ?? null,
            ]);
            $organizationId = $organization->id;
        } else {
            $organizationId = $input['organization_id'] ?? null;
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => $input['role_id'],
            'organization_id' => $organizationId,
            'is_validated' => false, // New users need validation
        ]);
    }*/

    public function create(array $input)
{
    Validator::make($input, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role_id' => ['required', 'exists:roles,id'],
        'create_organization' => ['sometimes', 'boolean'],
        'organization_name' => ['required_if:create_organization,1', 'nullable', 'string', 'max:255'],
        'organization_description' => ['nullable', 'string'],
        'organization_vat' => ['nullable', 'string'],
        'organization phone' => ['nullable', 'string'],
        'organization email' => ['nullable', 'string'],
        'organization_id' => ['required_if:create_organization,0', 'nullable', 'exists:organizations,id'],
    ])->validate();

    // Create organization if needed
    $organizationId = null;
    if (!empty($input['create_organization']) && $input['create_organization'] == 1) {
        // Make sure organization_name is present
        if (!empty($input['organization_name'])) {
            $organization = Organization::create([
                'name' => $input['organization_name'],
                'description' => $input['organization_description'] ?? null,
                'vat' => $input['organization_vat'] ?? null,
                'phone' => $input['organization_phone'] ?? null,
                'email' => $input['organization_email'] ?? null,
            ]);
            $organizationId = $organization->id;
        }
    } else {
        $organizationId = !empty($input['organization_id']) ? $input['organization_id'] : null;
    }

    // Debug the values that will be used for creation
    Log::info('About to create user with:', [
        'name' => $input['name'],
        'email' => $input['email'],
        'role_id' => $input['role_id'],
        'organization_id' => $organizationId,
    ]);

    // Try creating the user with explicit property assignment
    $user = new User();
    $user->name = $input['name'];
    $user->email = $input['email'];
    $user->password = Hash::make($input['password']);
    $user->role_id = $input['role_id'];
    $user->organization_id = $organizationId;
    $user->is_validated = false;
    $user->save();

    return $user;
}
}
