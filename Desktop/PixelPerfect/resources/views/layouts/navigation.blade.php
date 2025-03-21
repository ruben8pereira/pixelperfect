<nav class="navbar navbar-expand-lg {{ Route::is('home') ? 'navbar-dark position-absolute w-100' : 'navbar-light bg-white shadow-sm' }}" id="mainNav"
     style="{{ Route::is('home') ? 'z-index: 1030; top: 0;' : '' }}">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-' . (Route::is('home') ? 'white' : 'dark') . '.png') }}" alt="PipeDefect Solutions" class="h-10">
        </a>

        <!-- Navbar Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @guest
                    @if(Route::is('home'))
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#how-it-works">How It Works</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pricing">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#testimonials">Testimonials</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('Management') ? 'active fw-bold' : '' }}" href="{{ url('/admin') }}">{{ __('Management') }}</a>
                    </li>

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
                        <a class="nav-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                    </li>
                    @endif
                @endauth
            </ul>

            <!-- User Dropdown -->
            <div class="ms-auto">
                @guest
                    <a href="{{ route('login') }}" class="btn {{ Route::is('home') ? 'btn-outline-light' : 'btn-outline-primary' }} me-2">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                    @else
                    <div class="d-flex align-items-center">
                        <!-- Language Switcher -->
                        <div class="me-3">
                            <x-language-switcher />
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><span class="dropdown-item text-muted">{{ Auth::user()->email }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ url('/admin') }}"><i class="fas fa-tachometer-alt me-2"></i>Management</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>{{ __('Log Out') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

@if(Route::is('home'))
<script>
    // Navbar scroll effect for homepage only
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('mainNav');
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-solid', 'bg-primary', 'fixed-top');
            navbar.classList.remove('position-absolute');
        } else {
            navbar.classList.remove('navbar-solid', 'bg-primary', 'fixed-top');
            navbar.classList.add('position-absolute');
        }
    });
</script>
@endif
