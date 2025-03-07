<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser Implements CreatesNewUsers
{
    /* Original implementation commented out */

    public function create(array $input)
    {
        try {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role_id' => ['required', 'exists:roles,id'],
                'create_organization' => ['sometimes', 'boolean'],
                'organization_name' => ['required_if:create_organization,1', 'nullable', 'string', 'max:255'],
                'organization_description' => ['nullable', 'string'],
                'organization_vat' => ['nullable', 'string', Rule::unique('organizations', 'vat')],
                'organization_phone' => ['nullable', 'string', Rule::unique('organizations', 'phone')],
                'organization_email' => ['nullable', 'string', 'email', Rule::unique('organizations', 'email')],
                'organization_id' => ['required_if:create_organization,0', 'nullable', 'exists:organizations,id'],
            ], [
                // Custom error messages
                'organization_vat.unique' => 'This VAT number is already registered to another organization.',
                'organization_phone.unique' => 'This phone number is already registered to another organization.',
                'organization_email.unique' => 'This email is already registered to another organization.',
            ])->validate();

            // Create organization if needed
            $organizationId = null;
            if (!empty($input['create_organization']) && $input['create_organization'] == 1) {
                // Make sure organization_name is present
                if (!empty($input['organization_name'])) {
                    try {
                        $organization = Organization::create([
                            'name' => $input['organization_name'],
                            'description' => $input['organization_description'] ?? null,
                            'vat' => $input['organization_vat'] ?? null,
                            'phone' => $input['organization_phone'] ?? null,
                            'email' => $input['organization_email'] ?? null,
                        ]);
                        $organizationId = $organization->id;
                    } catch (\Exception $e) {
                        // If there's an unexpected database error, throw a ValidationException
                        throw ValidationException::withMessages([
                            'organization_error' => ['An error occurred while creating the organization: ' . $e->getMessage()],
                        ]);
                    }
                }
            } else {
                $organizationId = !empty($input['organization_id']) ? $input['organization_id'] : null;
            }

            // Create user with the validated data
            $user = new User();
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->password = Hash::make($input['password']);
            $user->role_id = $input['role_id'];
            $user->organization_id = $organizationId;
            $user->is_validated = false;
            $user->save();

            return $user;
        } catch (ValidationException $e) {
            // This will be caught by Laravel and redirected back with errors
            throw $e;
        } catch (\Exception $e) {
            // For any other exception, convert to a validation exception with a message
            throw ValidationException::withMessages([
                'general_error' => ['An unexpected error occurred: ' . $e->getMessage()],
            ]);
        }
    }
}
