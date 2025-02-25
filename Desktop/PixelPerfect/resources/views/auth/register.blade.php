<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | PipeDefect Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --dark: #212529;
            --light-bg: #f8f9fa;
        }
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }
        .form-control, .form-select {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        .page-header {
            color: var(--dark);
            font-weight: 600;
        }
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <a href="/">
                            <img src="logo.png" alt="PipeDefect Solutions Logo" class="img-fluid" style="max-height: 80px;">
                        </a>
                        <h2 class="page-header mt-3">Create Account</h2>
                        <p class="text-muted">Join our platform to manage pipe inspection reports</p>
                    </div>

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user text-secondary me-2"></i>{{ __('Full Name') }}
                                </label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your full name">
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope text-secondary me-2"></i>{{ __('Email Address') }}
                                </label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Enter your email address">
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label for="role_id" class="form-label">
                                    <i class="fas fa-id-badge text-secondary me-2"></i>{{ __('Account Type') }}
                                </label>
                                <select id="role_id" name="role_id" class="form-select">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Organization Selection -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-building text-secondary me-2"></i>{{ __('Organization') }}
                                </label>
                                <div class="form-check">
                                    <input id="create_organization" type="checkbox" class="form-check-input" name="create_organization" value="1" onchange="toggleOrganizationFields()">
                                    <label for="create_organization" class="form-check-label">{{ __('Create a new organization') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- New Organization Fields (Hidden by default) -->
                        <div id="new_organization_fields" class="mb-3 d-none">
                            <div class="card bg-light p-3 mb-3">
                                <h5><i class="fas fa-plus-circle text-primary me-2"></i>New Organization Details</h5>
                                <div class="row mt-2">
                                    <div class="col-md-12 mb-2">
                                        <label for="organization_name" class="form-label">{{ __('Organization Name') }}</label>
                                        <input id="organization_name" type="text" class="form-control" name="organization_name" value="{{ old('organization_name') }}" placeholder="Enter organization name">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="organization_description" class="form-label">{{ __('Organization Description') }}</label>
                                        <textarea id="organization_description" class="form-control" name="organization_description" rows="3" placeholder="Briefly describe your organization">{{ old('organization_description') }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="organization_vat" class="form-label">{{ __('Organization vat') }}</label>
                                        <textarea id="organization_vat" class="form-control" name="organization_vat" rows="3" placeholder="Enter you organization vat">{{ old('organization_vat') }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="organization_phone" class="form-label">{{ __('Organization phone') }}</label>
                                        <textarea id="organization_phone" class="form-control" name="organization_phone" rows="3" placeholder="Enter you organization phone number">{{ old('organization_phone') }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="organization_email" class="form-label">{{ __('Organization_email') }}</label>
                                        <textarea id="organization_email" class="form-control" name="organization_email" rows="3" placeholder="Enter you organization email adress">{{ old('organization_phone') }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Existing Organization Selection (Shown by default) -->
                        <div id="existing_organization_fields" class="mb-3">
                            <label for="organization_id" class="form-label">
                                <i class="fas fa-building text-secondary me-2"></i>{{ __('Select Organization') }}
                            </label>
                            <select id="organization_id" name="organization_id" class="form-select">
                                <option value="">{{ __('No Organization') }}</option>
                                @foreach(\App\Models\Organization::all() as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock text-secondary me-2"></i>{{ __('Password') }}
                                </label>
                                <input id="password" type="password" class="form-control" name="password" required placeholder="Enter your password">
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock text-secondary me-2"></i>{{ __('Confirm Password') }}
                                </label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm your password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a class="text-decoration-none" href="{{ route('login') }}">
                                <i class="fas fa-arrow-left me-1"></i> {{ __('Already registered?') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-1"></i> {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleOrganizationFields() {
            const createOrgCheckbox = document.getElementById('create_organization');
            const newOrgFields = document.getElementById('new_organization_fields');
            const existingOrgFields = document.getElementById('existing_organization_fields');

            if (createOrgCheckbox.checked) {
                newOrgFields.classList.remove('d-none');
                existingOrgFields.classList.add('d-none');
            } else {
                newOrgFields.classList.add('d-none');
                existingOrgFields.classList.remove('d-none');
            }
        }
    </script>
</body>
</html>
