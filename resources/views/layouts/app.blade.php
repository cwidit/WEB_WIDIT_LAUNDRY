<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Teeya Laundry') &mdash; Sistem Informasi</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/components.css">
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
                        <a href="{{ route('dashboard') }}">LaundryKu</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{ route('dashboard') }}">LK</a>
                    </div>
                    
                    <ul class="sidebar-menu">
                        <li class="menu-header">Halaman Utama</li>
                        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
                        </li>

                        <li class="menu-header">Master Data</li>
                        <li class="{{ Request::is('level*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('level.index') }}"><i class="fas fa-users-cog"></i> <span>Data Level</span></a>
                        </li>
                        <li class="{{ Request::is('customer*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('customer.index') }}"><i class="fas fa-user-friends"></i> <span>Data Customer</span></a>
                        </li>
                        <li class="{{ Request::is('type_of_service*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('type_of_service.index') }}"><i class="fas fa-tshirt"></i> <span>Layanan Laundry</span></a>
                        </li>

                        <li class="menu-header">Operasional</li>
                        <li class="{{ Request::is('transaction*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('transaction.index') }}"><i class="fas fa-file-invoice-dollar"></i> <span>Transaksi</span></a>
                        </li>
                        <li class="{{ Request::is('report*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('report.index') }}"><i class="fas fa-print"></i> <span>Laporan</span></a>
                        </li>
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