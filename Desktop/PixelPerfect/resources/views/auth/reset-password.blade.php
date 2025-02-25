<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | PipeDefect Solutions</title>
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
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            border: none;
            overflow: hidden;
        }
        .form-control {
            border-radius: 5px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 500;
            color: var(--dark);
        }
        .brand-section {
            padding: 1.5rem;
        }
        .reset-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1rem;
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="brand-section text-center">
                            <a href="/">
                                <img src="logo.png" alt="PipeDefect Solutions Logo" class="img-fluid" style="max-height: 70px;">
                            </a>
                            <h3 class="reset-title mt-3">Reset Password</h3>
                            <p class="text-muted">Create a new secure password</p>
                        </div>

                        <div class="card-body p-4 pt-0">
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

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <!-- Email Address -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope text-secondary me-2"></i>Email
                                    </label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $request->email) }}" required autofocus readonly>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock text-secondary me-2"></i>New Password
                                    </label>
                                    <input id="password" type="password" class="form-control" name="password" required placeholder="Enter your new password">
                                    <small class="text-muted">Password must be at least 8 characters long</small>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock text-secondary me-2"></i>Confirm New Password
                                    </label>
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm your new password">
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('login') }}" class="text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i>Back to login
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-1"></i>Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
