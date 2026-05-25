<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARE System — Bicol University IT Support Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">BU CARE<span>.</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#workflow">System Module</a></li>
                    <li class="nav-item"><a class="nav-link" href="#team">Our Team</a></li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <button class="btn-nav-signin" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fa-solid fa-circle-user me-2"></i>Portal Login
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1>Campus tech help,<br><span>at your fingertips.</span></h1>
                    <p class="my-4">
                        The CARE System is Bicol University's centralized platform for managing IT support requests, enabling students, faculty, and staff to submit technical concerns, technicians to track and resolve issues efficiently, and administrators to monitor service performance—all while maintaining a searchable knowledge base of solutions to reduce repetitive troubleshooting and improve institutional IT support quality.
                    </p>
                    <button class="btn-hero-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fa-solid fa-circle-plus me-2"></i>Submit a Ticket
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="workflow section-bg-alt" id="workflow">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <span class="section-tag">Module Scope</span>
                    <h2 class="section-title">Customer-Side Ticket CRUD Workflow</h2>
                    <p class="section-subtitle mx-auto">This interface directly implements the required user operations mapped to our core MySQL relational schema entities.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-card">
                        <div class="number">01</div>
                        <h4>Submit Ticket [CREATE]</h4>
                        <p>File active incidents. Input problem descriptions, map systemic classifications to a specific category, and link unique institutional equipment IDs directly from the interface form.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-card">
                        <div class="number">02</div>
                        <h4>View Dashboard [READ]</h4>
                        <p>Track reported records in a centralized portal grid. Real-time rendering filters items by execution states including Pending, In-Progress, Resolved, and Cancelled flags.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-card">
                        <div class="number">03</div>
                        <h4>Modify Requests [UPDATE]</h4>
                        <p>Correct submission discrepancies easily. Customers retain the capability to change structural descriptions or append contextual hardware updates before technicians claim assignments.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-card">
                        <div class="number">04</div>
                        <h4>Void Record [CANCEL]</h4>
                        <p>Maintain structural log integrity. Safely terminate duplicate issues or erroneous inputs through a secure built-in cancellation protocol that marks tickets as inactive instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team" id="team">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <span class="section-tag">Contributors</span>
                    <h2 class="section-title">Project Development Team</h2>
                    <p class="section-subtitle mx-auto">Engineered and refined by the systemic engineering group members supporting cross-campus IT operational portals.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="team-card">
                        <img src="{{ asset('images/ALA.png') }}" alt="Pio Gabriel Ala">
                        <h5>Pio Gabriel Ala</h5>
                        <p>Front End Developer</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="team-card">
                        <img src="{{ asset('images/ANDOJAR.png') }}" alt="Vincent Andojar">
                        <h5>Vincent Gabriel Andojar</h5>
                        <p>Fullstack Developer</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="team-card">
                        <img src="{{ asset('images/HERRERA.png') }}" alt="Joshua Herrera">
                        <h5>Joshua Herrera</h5>
                        <p>Front End Developer</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="team-card">
                        <img src="{{ asset('images/VALLADOLID.png') }}" alt="Vincent Valladolid">
                        <h5>Vincent Valladolid</h5>
                        <p>Fullstack Developer</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sign In Your Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('customer.login.post') }}" id="loginForm">
                        @csrf

                        @if ($errors->any())
                            <div class="form-alert-banner">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ $errors->first() }}
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="email" class="form-label">University Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="username@bicol-u.edu.ph" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Account Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label text-muted small" for="remember">Cache session on this device</label>
                        </div>
                        <button type="submit" class="btn-modal-submit">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <h4>Bicol University</h4>
            <p>Computer And Response Emergency © {{ date('Y') }} &mdash; Information Technology Infrastructure Directorate</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/welcome-modal.js') }}"></script>

    @if ($errors->any() || old('email'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'), {
                backdrop: 'static',
                keyboard: false
            });
            loginModal.show();
        });
    </script>
    @endif

</body>
</html>