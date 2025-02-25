<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PipeDefect Solutions - Professional Pipe Inspection & Defect Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0057b8;
            --primary-dark: #004494;
            --primary-light: #e6f0ff;
            --secondary: #6c757d;
            --success: #198754;
            --bg-light: #f8f9fa;
            --dark: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/images/pipeline-inspection.jpg');
            background-size: cover;
            background-position: center;
            min-height: 600px;
            color: white;
            position: relative;
        }

        .navbar {
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand img {
            height: 40px;
        }

        .navbar-solid {
            background-color: var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: #ffffff;
        }

        .btn-outline-light {
            border-width: 2px;
        }

        /* Features section */
        .feature-box {
            padding: 2rem;
            text-align: center;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            font-size: 2rem;
        }

        /* How it works section */
        .step-box {
            position: relative;
            padding-left: 80px;
            margin-bottom: 2rem;
        }

        .step-number {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 1.5rem;
        }

        /* Testimonials */
        .testimonial-card {
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            height: 100%;
        }

        .testimonial-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        /* CTA section */
        .cta-section {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            color: white;
            padding: 4rem 0;
        }

        /* Footer */
        .footer {
            background-color: var(--dark);
            color: rgba(255, 255, 255, 0.8);
            padding: 4rem 0 1rem;
        }

        .footer-title {
            color: white;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            text-decoration: underline;
        }

        .social-icons a {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 3rem;
        }
    </style>
</head>

<body>
    <!-- Hero Section with Navigation -->
    <section class="hero-section">
        <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="/images/logo-white.png" alt="PipeDefect Solutions">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @guest
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
                        @endguest

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reports.index') }}">Reports</a>
                            </li>
                            @if (auth()->user()->role &&
                                    (auth()->user()->role->name == 'Administrator' || auth()->user()->role->name == 'Organization'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Users</a>
                                </li>
                            @endif
                            @if (auth()->user()->role && auth()->user()->role->name == 'Administrator')
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Organizations</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <div class="ms-auto">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Log In</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                        @else
                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ auth()->user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i
                                                class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="#"><i
                                                class="fas fa-user-circle me-2"></i>Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                            @csrf
                                            <a class="dropdown-item" href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <div class="container h-100">
            <div class="row h-75 align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-bold mb-4">Professional Pipe Inspection & Defect Management</h1>
                    <p class="lead mb-4">Our comprehensive platform streamlines the process of documenting, analyzing
                        and reporting pipe defects for organizations of all sizes.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 me-md-2">Get Started</a>
                        <a href="#demo" class="btn btn-outline-light btn-lg px-4">Request Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light" id="features">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Powerful Features for Pipe Inspection Professionals</h2>
                <p class="lead text-muted">Everything you need to efficiently track, document, and manage pipe defects
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box bg-white">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4>Comprehensive Reports</h4>
                        <p class="text-muted">Generate detailed and customizable inspection reports with just a few
                            clicks. Support for multiple languages and formats.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box bg-white">
                        <div class="feature-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h4>Photo Documentation</h4>
                        <p class="text-muted">Upload and organize inspection photos with automatic categorization and
                            defect linking capabilities.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box bg-white">
                        <div class="feature-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4>Geo-Location Tracking</h4>
                        <p class="text-muted">Record precise coordinates for each defect to facilitate easy locating
                            and repeat inspections.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box bg-white">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h4>Role-Based Access</h4>
                        <p class="text-muted">Control who can view, edit, and generate reports with our granular
                            permission system.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box bg-white">
                        <div class="feature-icon">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <h4>PDF Export</h4>
                        <p class="text-muted">Export professional-quality PDF reports complete with your company
                            branding and all inspection details.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box bg-white">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Analytics Dashboard</h4>
                        <p class="text-muted">Track inspection trends, defect frequencies, and team performance with
                            our intuitive dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5" id="how-it-works">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">How It Works</h2>

                    <div class="step-box">
                        <div class="step-number">1</div>
                        <h4>Create an Account</h4>
                        <p class="text-muted">Sign up as an organization or individual user and set up your profile
                            with your company details.</p>
                    </div>

                    <div class="step-box">
                        <div class="step-number">2</div>
                        <h4>Document Pipe Defects</h4>
                        <p class="text-muted">Create new reports, upload inspection images, and document defects with
                            our intuitive interface.</p>
                    </div>

                    <div class="step-box">
                        <div class="step-number">3</div>
                        <h4>Generate Professional Reports</h4>
                        <p class="text-muted">Export detailed PDF reports or share secure links with your clients or
                            team members.</p>
                    </div>

                    <div class="step-box">
                        <div class="step-number">4</div>
                        <h4>Track & Analyze Over Time</h4>
                        <p class="text-muted">Monitor defect trends, compare inspection results, and make data-driven
                            maintenance decisions.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <img src="/images/report-example.jpg" alt="Report Example" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-5 bg-light" id="pricing">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Flexible Pricing for Teams of All Sizes</h2>
                <p class="lead text-muted">Choose the plan that's right for your organization</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-5">
                            <h5 class="card-title text-center mb-4">Basic</h5>
                            <h1 class="card-text text-center mb-4">$49<span class="text-muted fs-5">/month</span></h1>

                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Up to 5 users</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> 100 reports per
                                    month</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> 500MB storage</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> PDF export</li>
                                <li class="mb-3 text-muted"><i class="fas fa-times me-2"></i> Custom branding</li>
                                <li class="mb-3 text-muted"><i class="fas fa-times me-2"></i> API access</li>
                            </ul>

                            <div class="d-grid mt-4">
                                <a href="{{ route('register') }}?plan=basic" class="btn btn-outline-primary">Get
                                    Started</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow border border-primary">
                        <div class="card-body p-5">
                            <div class="ribbon bg-primary text-white position-absolute px-3 py-1"
                                style="top: 15px; right: -10px; transform: rotate(45deg);">Popular</div>
                            <h5 class="card-title text-center mb-4">Professional</h5>
                            <h1 class="card-text text-center mb-4">$99<span class="text-muted fs-5">/month</span></h1>

                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Up to 15 users</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Unlimited reports
                                </li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> 5GB storage</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> PDF export</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Custom branding</li>
                                <li class="mb-3 text-muted"><i class="fas fa-times me-2"></i> API access</li>
                            </ul>

                            <div class="d-grid mt-4">
                                <a href="{{ route('register') }}?plan=professional" class="btn btn-primary">Get
                                    Started</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-5">
                            <h5 class="card-title text-center mb-4">Enterprise</h5>
                            <h1 class="card-text text-center mb-4">$249<span class="text-muted fs-5">/month</span>
                            </h1>

                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Unlimited users</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Unlimited reports
                                </li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> 25GB storage</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> PDF export</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Custom branding</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> API access</li>
                            </ul>

                            <div class="d-grid mt-4">
                                <a href="{{ route('register') }}?plan=enterprise" class="btn btn-outline-primary">Get
                                    Started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5" id="testimonials">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">What Our Customers Say</h2>
                <p class="lead text-muted">Trusted by pipe inspection professionals worldwide</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="text-center">
                            <img src="/images/testimonial-1.jpg" alt="Testimonial" class="testimonial-image">
                            <h5>Jean Dupont</h5>
                            <p class="text-muted">Pipeline Manager, Suez</p>
                        </div>
                        <p class="mt-3">"This platform has transformed our inspection workflow. The report generation
                            saves us hours of work every week, and our clients love the professional PDFs."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="text-center">
                            <img src="/images/testimonial-2.jpg" alt="Testimonial" class="testimonial-image">
                            <h5>Maria Schmidt</h5>
                            <p class="text-muted">CEO, GermTech Inspections</p>
                        </div>
                        <p class="mt-3">"The ability to track defects over time has allowed us to provide better
                            recommendations to our clients and schedule maintenance more effectively."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="text-center">
                            <img src="/images/testimonial-3.jpg" alt="Testimonial" class="testimonial-image">
                            <h5>John Anderson</h5>
                            <p class="text-muted">Operations Manager, CityWorks</p>
                        </div>
                        <p class="mt-3">"Managing our team of 12 inspectors used to be a nightmare. Now everyone
                            follows the same process, and we have complete visibility into all inspection activities."
                        </p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="demo">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">Ready to streamline your pipe inspection process?</h2>
                    <p class="lead mb-4">Join thousands of professionals using our platform to deliver better
                        inspection reports.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Get Started Today</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5 bg-light" id="contact">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-5">
                    <h2 class="fw-bold mb-4">Contact Us</h2>
                    <p class="mb-4">Have questions about our platform? Contact our team for more information or to
                        schedule a live demonstration.</p>

                    <div class="mb-4">
                        <h5 class="fw-bold">Address</h5>
                        <p class="text-muted">
                            123 Inspection Street<br>
                            Pipe City, PC 12345<br>
                            Switzerland
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold">Contact Information</h5>
                        <p class="text-muted">
                            <i class="fas fa-envelope me-2"></i> info@pipedefect-solutions.com<br>
                            <i class="fas fa-phone me-2"></i> +41 123 456 789
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold">Office Hours</h5>
                        <p class="text-muted">
                            Monday - Friday: 9:00 AM - 5:00 PM<br>
                            Saturday & Sunday: Closed
                        </p>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Send us a message</h3>

                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="company" class="form-label">Company</label>
                                        <input type="text" class="form-control" id="company">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="tel" class="form-control" id="phone">
                                    </div>

                                    <div class="col-12">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="subject" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="5" required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="/images/logo-white.png" alt="PipeDefect Solutions" class="mb-4"
                        style="height: 40px;">
                    <p>PipeDefect Solutions provides comprehensive pipe inspection and defect management software for
                        professionals in the water, sewage, and pipeline industries.</p>

                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Company</h5>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Team</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Partners</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Product</h5>
                    <ul class="footer-links">
                        <li><a href="#">Features</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">Testimonials</a></li>
                        <li><a href="#">API Documentation</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Support</h5>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">System Status</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">GDPR Compliance</a></li>
                    </ul>
                </div>
            </div>

            <div class="text-center copyright">
                <p>&copy; {{ date('Y') }} PipeDefect Solutions. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-solid');
            } else {
                navbar.classList.remove('navbar-solid');
            }
        });
    </script>
</body>

</html>
