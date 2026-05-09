<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panadería | Admin</title>

    <!-- Bootstrap + Iconos (CDN)
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Vite: TODOS los CSS compilados -->
    @vite([
        'resources/css/global.css',
        'resources/css/components.css',
        'resources/css/sidebar.css',
        'resources/css/login.css',
    ])

    <style>
        html { visibility: hidden; }
        html.loaded { visibility: visible; }
        
        .sidebar-heading {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 0 14px;
            margin-top: 24px;
            margin-bottom: 8px;
            opacity: 0.7;
        }
    </style>

    <script>
        const isDark = localStorage.getItem('theme') === 'dark' || 
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (isDark) {
            document.documentElement.classList.add('dark-mode');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
        document.documentElement.classList.add('loaded');
    </script>
</head>

<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <h5 class="d-flex align-items-center justify-content-start gap-2 mb-0">
            <div class="d-flex align-items-center justify-content-center rounded" 
            style="width: 36px; height: 36px; background: var(--gold-light); color: var(--bg-sidebar);">
                <i class="bi bi-box-seam" style="font-size: 1.1rem;"></i>
            </div>
            <span class="ms-1">Gestión</span>
        </h5>
        <small class="mt-2" style="color: var(--sidebar-text-secondary); opacity: 0.8;">Panel Administrativo</small>
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i> <span>Inicio</span>
            </a>
        </li>

        <div class="sidebar-heading mt-4 mb-2 small text-uppercase">Inventario</div>
        <li class="nav-item">
            <a href="{{ url('/productos') }}" class="nav-link {{ request()->is('productos*') ? 'active' : '' }}">
                <i class="bi bi-box"></i> <span>Productos</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/categorias') }}" class="nav-link {{ request()->is('categorias*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i> <span>Categorías</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/recetas') }}" class="nav-link {{ request()->is('recetas*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> <span>Recetas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/produccion') }}" class="nav-link {{ request()->is('produccion*') ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat"></i> <span>Producción</span>
            </a>
        </li>
        
        <div class="sidebar-heading mt-4 mb-2 small text-uppercase">Administración</div>
        <li class="nav-item">
            <a href="{{ url('/proveedores') }}" class="nav-link {{ request()->is('proveedores*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> <span>Proveedores</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/usuarios') }}" class="nav-link {{ request()->is('usuarios*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>Usuarios</span>
            </a>
        </li>

        <div class="sidebar-heading mt-4 mb-2 small text-uppercase">Cuenta</div>
        <li class="nav-item">
            <a href="{{ url('/perfil') }}" class="nav-link {{ request()->is('perfil') ? 'active' : '' }}">
                <i class="bi bi-person"></i> <span>Mi Perfil</span>
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer">
        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> <span>Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>

    <main class="main-content">
        <div class="container-fluid p-0">
            <div class="animate-fade-in">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Script principal de Vite -->
    @vite(['resources/js/app.js'])

    <script>
        window.ServerData = {
            success: @json(session('success')),
            error: @json(session('error')),
            hasErrors: @json($errors->any() ? true : false)
        };
    </script>

    <button class="theme-toggle" id="themeToggle" title="Cambiar Apariencia">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
    </button>

    <!-- Bootstrap JS 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>-->

    <!-- Scripts específicos de Vite -->
    @vite([
        'resources/js/dashboard.js',
        'resources/js/productos.js',
        'resources/js/categorias.js',
        'resources/js/perfil.js',
        'resources/js/usuarios.js',
    ])

    @stack('scripts')
</body>
</html>