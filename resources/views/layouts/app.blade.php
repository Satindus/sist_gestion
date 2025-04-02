<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <style>
        :root {
            --sidebar-width: 280px;
            --topbar-height: 60px;
            --primary-color: #4e73df;
            --primary-dark: #3a54a5;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
            background-color: #f8f9fc;
        }
        
        /* Sidebar */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        #sidebar-wrapper.collapsed {
            width: 80px;
        }
        
        #sidebar-wrapper.collapsed .sidebar-logo span,
        #sidebar-wrapper.collapsed .nav-link span {
            display: none;
        }
        
        .sidebar-logo {
            height: var(--topbar-height);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-logo a {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar-logo img {
            width: 35px;
            height: 35px;
            margin-right: 0.75rem;
            border-radius: 5px;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .sidebar-toggle:hover {
            color: white;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            overflow-y: auto;
            height: calc(100vh - var(--topbar-height));
        }
        
        .divider {
            height: 0;
            margin: 0.5rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-heading {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.1rem;
        }
        
        .nav-item {
            margin: 0.2rem 0.7rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 0.35rem;
            transition: all 0.2s;
        }
        
        .nav-link i {
            width: 1.25rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 0.875rem;
        }
        
        .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }
        
        /* Content */
        #content-wrapper {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        #content-wrapper.expanded {
            width: calc(100% - 80px);
            margin-left: 80px;
        }
        
        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .topbar-search {
            position: relative;
            max-width: 300px;
        }
        
        .topbar-search input {
            border-radius: 0.35rem;
            padding-left: 2.5rem;
            border: 1px solid #d1d3e2;
        }
        
        .topbar-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #b7b9cc;
        }
        
        .topbar-divider {
            width: 0;
            border-right: 1px solid #e3e6f0;
            height: 2rem;
            margin: auto 1rem;
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
        }
        
        .topbar-user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .main-content {
            padding: 1.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                transform: translateX(-100%);
            }
            
            #sidebar-wrapper.show {
                transform: translateX(0);
            }
            
            #content-wrapper {
                width: 100%;
                margin-left: 0;
            }
            
            .topbar-toggle {
                display: block !important;
            }
        }
        
        /* Mobile Toggle */
        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            color: #5a5c69;
            font-size: 1.25rem;
            cursor: pointer;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }
        
        .card-header h6 {
            margin-bottom: 0;
            font-weight: 700;
            color: #4e73df;
        }
        
        /* Modals */
        .modal-header {
            border-bottom: 1px solid #e3e6f0;
            background-color: #f8f9fc;
        }
        
        .modal-footer {
            border-top: 1px solid #e3e6f0;
            background-color: #f8f9fc;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-logo">
                <a href="{{ route('dashboard') }}">
                    <img src="/img/logo.png" alt="Logo" onerror="this.src='https://via.placeholder.com/35x35?text=SG'">
                    <span>Sistema Gestión</span>
                </a>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="sidebar-nav">
                <div class="nav-heading">Principal</div>
                
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="divider"></div>
                <div class="nav-heading">Gestión</div>
                
                @can('ver', 'Usuarios')
                <div class="nav-item">
                    <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </div>
                @endcan
                
                @can('ver', 'Empleados')
                <div class="nav-item">
                    <a href="{{ route('empleados.index') }}" class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}">
                        <i class="fas fa-id-card"></i>
                        <span>Empleados</span>
                    </a>
                </div>
                @endcan
                
                @can('ver', 'Evidencias')
                <div class="nav-item">
                    <a href="{{ route('evidencias.index') }}" class="nav-link {{ request()->routeIs('evidencias.*') ? 'active' : '' }}">
                        <i class="fas fa-camera"></i>
                        <span>Evidencias</span>
                    </a>
                </div>
                @endcan
                
                @can('ver', 'Reportes')
                <div class="nav-item">
                    <a href="{{ route('reportes.index') }}" class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reportes</span>
                    </a>
                </div>
                @endcan
                
                @can('ver', 'Configuración')
                <div class="divider"></div>
                <div class="nav-heading">Sistema</div>
                
                <div class="nav-item">
                    <a href="{{ route('configuracion.index') }}" class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Configuración</span>
                    </a>
                </div>
                @endcan
                
                <div class="divider"></div>
                <div class="nav-item mt-auto">
                    <a href="#" class="nav-link" id="logout-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div id="content-wrapper">
            <!-- Topbar -->
            <nav class="topbar">
                <button class="topbar-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="topbar-search d-none d-md-block">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Buscar...">
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="btn btn-link text-dark dropdown-toggle" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="badge bg-danger badge-counter">3+</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                            <li><h6 class="dropdown-header">Centro de Notificaciones</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Notificación 1</a></li>
                            <li><a class="dropdown-item" href="#">Notificación 2</a></li>
                            <li><a class="dropdown-item" href="#">Notificación 3</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                        </ul>
                    </div>
                    
                    <div class="topbar-divider"></div>
                    
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-dark text-decoration-none" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="topbar-user">
                                <img src="https://via.placeholder.com/200" alt="Usuario">
                                <div>
                                    <span class="d-none d-lg-inline fw-bold">{{ auth()->user()->nombre_usuario }}</span>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('perfil.index') }}"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Perfil</a></li>
                            <li><a class="dropdown-item" href="{{ route('configuracion.index') }}"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" id="dropdown-logout"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Sidebar toggle
            $('#sidebarToggle').on('click', function() {
                $('#sidebar-wrapper').toggleClass('collapsed');
                $('#content-wrapper').toggleClass('expanded');
            });
            
            // Mobile toggle
            $('#mobileToggle').on('click', function() {
                $('#sidebar-wrapper').toggleClass('show');
            });
            
            // Logout
            $('#logout-link, #dropdown-logout').on('click', function(e) {
                e.preventDefault();
                $('#logout-form').submit();
            });
            
            // AJAX Setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>