<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panadería | Admin</title>

    <!-- Bootstrap + Iconos (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <!-- ... tu sidebar ... -->
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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