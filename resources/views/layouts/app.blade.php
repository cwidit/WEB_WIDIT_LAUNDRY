<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Widit Laundry') &mdash; Sistem Informasi</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/components.css">
    <style>
        /* Custom Navy button */
        .btn-navy {
            background-color: #1e3a8a !important;
            border-color: #1e3a8a !important;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(30, 58, 138, 0.2);
        }
        .btn-navy:hover, .btn-navy:focus, .btn-navy:active {
            background-color: #0f172a !important;
            border-color: #0f172a !important;
            color: #fff !important;
        }

        /* Change grey/muted text to navy */
        .section-lead, 
        .text-muted, 
        .text-secondary,
        .section-title {
            color: #1e3a8a !important;
        }

        /* Force all sidebar elements to Navy */
        .main-sidebar,
        .main-sidebar *,
        .main-sidebar .sidebar-brand a,
        .main-sidebar .sidebar-menu li.menu-header,
        .main-sidebar .sidebar-menu li a,
        .main-sidebar .sidebar-menu li a span,
        .main-sidebar .sidebar-menu li a i,
        .main-sidebar .sidebar-menu li.active a,
        .main-sidebar .sidebar-menu li.active a span,
        .main-sidebar .sidebar-menu li.active a i,
        .main-sidebar .sidebar-menu li.dropdown.active > a,
        .main-sidebar .sidebar-menu li.dropdown.active > a i,
        .main-sidebar .sidebar-menu li.dropdown.active > a span,
        .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a,
        .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a span,
        .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li.active a,
        .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li.active a span {
            color: #1e3a8a !important;
        }

        /* Hover/focus color states for sidebar links */
        .main-sidebar .sidebar-menu li a:hover,
        .main-sidebar .sidebar-menu li a:hover i,
        .main-sidebar .sidebar-menu li a:hover span,
        .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a:hover,
        .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a:hover span {
            color: #0f172a !important; /* Darker navy hover color */
        }
        
        .sidebar-menu li.menu-header {
            letter-spacing: 0.5px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    </ul>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <img alt="image" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Operator') }}&background=random" class="rounded-circle mr-1">
                        <div class="d-sm-none d-lg-inline-block">Halo, {{ Auth::user()->name ?? 'Operator' }}</div></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title">Menu Pengguna</div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profil Saya
                            </a>
                            <div class="dropdown-divider"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item has-icon text-danger d-flex align-items-center" style="cursor: pointer; background: transparent; border: none;">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard') }}">Widit Laundry</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{ route('dashboard') }}">WL</a>
                    </div>
                    
                    <ul class="sidebar-menu">
                        @php
                            $role = optional(Auth::user()->level)->level_name;
                        @endphp

                        <li class="menu-header">Halaman Utama</li>
                        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
                        </li>

                        @if (in_array($role, ['Administrator', 'Operator']))
                            <li class="menu-header">Master Data</li>
                            <li class="dropdown {{ Request::is('level*') || Request::is('user*') || Request::is('customer*') || Request::is('type_of_service*') ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i> <span>Master Data</span></a>
                                <ul class="dropdown-menu">
                                    @if ($role === 'Administrator')
                                        <li class="{{ Request::is('level*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('level.index') }}">Level</a></li>
                                        <li class="{{ Request::is('user*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('user.index') }}">User</a></li>
                                    @endif
                                    <li class="{{ Request::is('customer*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('customer.index') }}">Customer</a></li>
                                    @if ($role === 'Administrator')
                                        <li class="{{ Request::is('type_of_service*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('type_of_service.index') }}">Layanan Laundry</a></li>
                                    @endif
                                </ul>
                            </li>

                            <li class="menu-header">Order</li>
                            <li class="dropdown {{ Request::is('transaction*') && !Request::is('transaction/*/print') ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-basket"></i> <span>Order</span></a>
                                <ul class="dropdown-menu">
                                    <li class="{{ Request::is('transaction/create') ? 'active' : '' }}"><a class="nav-link" href="{{ route('transaction.create') }}">New Order</a></li>
                                    <li class="{{ Request::is('transaction') ? 'active' : '' }}"><a class="nav-link" href="{{ route('transaction.index') }}">History Order</a></li>
                                </ul>
                            </li>
                        @endif

                        @if (in_array($role, ['Administrator', 'Owner']))
                            <li class="menu-header">Report</li>
                            <li class="{{ Request::is('report*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('report.index') }}"><i class="fas fa-chart-line"></i> <span>Report</span></a>
                            </li>
                        @endif
                    </ul>
                </aside>
            </div>

            <div class="main-content">
                @yield('content')
            </div>

            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{ date('Y') }} <div class="bullet"></div> Proyek UJIKOM Laundry
                </div>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/stisla.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/scripts.js"></script>
</body>
</html>