<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | Pixel Perfect</title>
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
        .forgot-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1rem;
        }
        .alert {
            border-radius: 5px;
        }
        .info-text {
            border-left: 3px solid var(--primary);
            padding: 10px 15px;
            background-color: rgba(13, 110, 253, 0.05);
            border-radius: 0 5px 5px 0;
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
                                <img src="{{ asset('img/logo.jpg') }}" alt="PipeDefect Solutions Logo" class="img-fluid" style="max-height: 70px;">
                            </a>
                            <h3 class="forgot-title mt-3">Forgot Password?</h3>
                        </div>

                        <div class="card-body p-4 pt-0">
                            <div class="info-text mb-4">
                                <p class="mb-0">Enter your email address, and we'll send you a password reset link to get back into your account.</p>
                            </div>

                            <!-- Session Status -->
                            @if(session('status'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                </div>
                            @endif

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

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope text-secondary me-2"></i>Email
                                    </label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email address">
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('login') }}" class="text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i>Back to login
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i>Send Reset Link
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
