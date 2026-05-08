<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panadería | Admin</title>

    <!-- Enlaces (Google Fonts, Bootstrap 5 + Iconos) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite([
        'resources/js/app.js',
        'resources/css/global.css',
        'resources/css/components.css',
        'resources/css/sidebar.css',
    ])

    <style>
        /* Anti FOUC (Flash of Unstyled Content) para Modo Oscuro */
        html { visibility: hidden; }
        html.loaded { visibility: visible; }
        
        /* Pequeños ajustes de tipografía complementaria */
        .sidebar-heading {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            text-transform: uppercase;
            padding: 0 14px;
            margin-top: 24px;
            margin-bottom: 8px;
            opacity: 0.7;
        }
    </style>

    <!-- Script Crítico Anti-FOUC (Modo Oscuro Instantáneo) -->
    <script>
        const isDark = localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (isDark) {
            document.documentElement.classList.add('dark-mode');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
        document.documentElement.classList.add('loaded');
    </script>
</head>

<body>

<aside class="sidebar shadow-sm">
    <div class="sidebar-brand">
        <h5 class="d-flex align-items-center justify-content-start gap-2 mb-0">
            <div class="d-flex align-items-center justify-content-center rounded" style="width: 36px; height: 36px; background: var(--gold-light); color: var(--bg-sidebar);">
                <i class="bi bi-box-seam" style="font-size: 1.1rem;"></i>
            </div>
            <span class="ms-1 fw-bold">Gestión</span>
        </h5>
        <small class="mt-2 d-block" style="color: var(--sidebar-text-secondary); opacity: 0.8;">Panel Administrativo</small>
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i> <span>Inicio</span>
            </a>
        </li>

        <div class="sidebar-heading">Inventario</div>
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
        
        <div class="sidebar-heading">Administración</div>
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

        <div class="sidebar-heading">Cuenta</div>
        <li class="nav-item">
            <a href="{{ url('/perfil') }}" class="nav-link {{ request()->is('perfil') ? 'active' : '' }}">
                <i class="bi bi-person"></i> <span>Mi Perfil</span>
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer border-top border-border-color">
        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="logout-btn w-100 d-flex align-items-center justify-content-center gap-2">
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

<script>
    // MEJORA 3: Sistema Global de Notificaciones UX (SweetAlert2)
    // Extraemos la información generada en el Backend (Ej. with('success', '...'))
    window.ServerData = {
        success: @json(session('success')),
        error: @json(session('error')),
        hasErrors: @json($errors->any() ? true : false)
    };
</script>

<!-- Importamos SweetAlert2 para Notificaciones Profesionales -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Botón de modo oscuro nativo -->
<button class="theme-toggle" id="themeToggle" title="Cambiar Apariencia">
    <i class="bi bi-moon-stars" id="themeIcon"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- MEJORA 2: Carga de Javascript Modular y Optimizada -->
<!-- Script Base General -->
@stack('scripts')
<!-- Script Específico de la Vista (Si existe) mediante Vite -->
@if(isset($page_js))
    @vite(['resources/js/' . $page_js])
@endif

<script>
    // Lógica Global para lanzar alertas UX (SweetAlert) instantáneas al usuario
    document.addEventListener("DOMContentLoaded", () => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        if (window.ServerData.success) {
            Toast.fire({ icon: 'success', title: window.ServerData.success });
        }
        if (window.ServerData.error) {
            Toast.fire({ icon: 'error', title: window.ServerData.error });
        }
        if (window.ServerData.hasErrors) {
            Toast.fire({ icon: 'warning', title: 'Por favor, corrige los errores del formulario.' });
        }
    });

    // Pequeño script para el botn de modo oscuro
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark-mode');
            const isDark = document.documentElement.classList.contains('dark-mode');
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    }
</script>

</body>
</html>




