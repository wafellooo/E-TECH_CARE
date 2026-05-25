<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARE System | @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="{{ route('customer.tickets.index') }}">
            <i class="bi bi-shield-check"></i> CARE System
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <span class="nav-link text-white">
                        <i class="bi bi-person-circle"></i>
                        {{ Session::get('customer_name') }}
                        <span class="badge bg-secondary ms-1">{{ ucfirst(Session::get('customer_type')) }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Page Layout --}}
<div class="container-fluid">
    <div class="row">

        {{-- Sidebar --}}
        <div class="col-md-2 sidebar d-none d-md-block">
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.tickets.*') ? 'active' : '' }}"
                       href="{{ route('customer.tickets.index') }}">
                        <i class="bi bi-ticket-perforated-fill"></i> My Tickets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.tickets.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Submit Ticket
                    </a>
                </li>
            </ul>
        </div>

        {{-- Main Content --}}
        <div class="col-md-10 main-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')

</body>
</html>