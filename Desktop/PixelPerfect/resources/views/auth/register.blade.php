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
        .required:after {
            content: " *";
            color: #dc3545;
        }
        .section-header {
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e3e3e3;
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

                        <!-- Hidden Role ID for Organization -->
                        <input type="hidden" name="role_id" value="2">
                        <input type="hidden" name="create_organization" value="1">

                        <!-- User Information Section -->
                        <div class="mb-4">
                            <h5 class="section-header">
                                <i class="fas fa-user-circle text-primary me-2"></i>Personal Information
                            </h5>
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label required">
                                        {{ __('Full Name') }}
                                    </label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required autofocus
                                        placeholder="Enter your full name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label required">
                                        {{ __('Email Address') }}
                                    </label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required
                                        placeholder="Enter your email address">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Company Information Section -->
                        <div class="mb-4">
                            <h5 class="section-header">
                                <i class="fas fa-building text-primary me-2"></i>Company Information
                            </h5>
                            <div class="row">
                                <!-- Company Name -->
                                <div class="col-md-12 mb-3">
                                    <label for="organization_name" class="form-label required">
                                        {{ __('Company Name') }}
                                    </label>
                                    <input id="organization_name" type="text"
                                        class="form-control @error('organization_name') is-invalid @enderror"
                                        name="organization_name" value="{{ old('organization_name') }}" required
                                        placeholder="Enter your company name">
                                    @error('organization_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- VAT Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="organization_vat" class="form-label required">
                                        {{ __('VAT Number') }}
                                    </label>
                                    <input id="organization_vat" type="text"
                                        class="form-control @error('organization_vat') is-invalid @enderror"
                                        name="organization_vat" value="{{ old('organization_vat') }}" required
                                        placeholder="Enter your VAT identification number">
                                    <small class="text-muted">Value Added Tax identification number</small>
                                    @error('organization_vat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Company Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="organization_phone" class="form-label required">
                                        {{ __('Company Phone') }}
                                    </label>
                                    <input id="organization_phone" type="tel"
                                        class="form-control @error('organization_phone') is-invalid @enderror"
                                        name="organization_phone" value="{{ old('organization_phone') }}" required
                                        placeholder="Enter company phone number">
                                    @error('organization_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Company Email -->
                                <div class="col-md-12 mb-3">
                                    <label for="organization_email" class="form-label required">
                                        {{ __('Company Email') }}
                                    </label>
                                    <input id="organization_email" type="email"
                                        class="form-control @error('organization_email') is-invalid @enderror"
                                        name="organization_email" value="{{ old('organization_email') }}" required
                                        placeholder="Enter company email address">
                                    @error('organization_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="mb-4">
                            <h5 class="section-header">
                                <i class="fas fa-lock text-primary me-2"></i>Set Password
                            </h5>
                            <div class="row">
                                <!-- Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label required">
                                        {{ __('Password') }}
                                    </label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password" required
                                        placeholder="Minimum 8 characters">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label required">
                                        {{ __('Confirm Password') }}
                                    </label>
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" required
                                        placeholder="Confirm your password">
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Privacy Policy -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="terms_accepted" name="terms_accepted" required>
                                <label class="form-check-label" for="terms_accepted">
                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and
                                    <a href="#" target="_blank">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
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
</body>
</html>
