<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Navigation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('path/to/logo.png') }}" alt="Application Logo" class="h-10">
        </a>

        <!-- Navbar Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>

                @auth
                    @if(optional(auth()->user()->role)->name != 'Guest')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.index') ? 'active fw-bold' : '' }}" href="{{ route('reports.index') }}">{{ __('Reports') }}</a>
                    </li>
                    @endif

                    @php
                        $userRole = optional(auth()->user()->role)->name;
                    @endphp

                    @if($userRole == 'Administrator' || $userRole == 'Organization')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('invitations.index') ? 'active fw-bold' : '' }}" href="{{ route('invitations.index') }}">{{ __('Invitations') }}</a>
                    </li>
                    @endif

                    @if($userRole == 'Administrator')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('filament.admin.pages.dashboard') }}" target="_blank">{{ __('Admin Panel') }}</a>
                    </li>
                    @endif
                @endauth
            </ul>

            <!-- User Dropdown -->
            @auth
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="dropdown-item text-muted">{{ Auth::user()->email }}</li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>
</nav>

</body>
</html>
