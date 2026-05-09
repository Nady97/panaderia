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

    <!-- Vite: Tailwind -->
    @vite([
        'resources/css/global.css'
    ])

    <style>
        html { visibility: hidden; }
        html.loaded { visibility: visible; }
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
<aside class="fixed left-0 top-0 z-50 flex h-screen w-20 lg:w-72 flex-col overflow-y-auto border-r border-[var(--border-color)] bg-[var(--bg-sidebar)] shadow-sm transition-all">
    <div class="flex flex-col border-b border-white/5 px-4 py-6 lg:px-6">
        <h5 class="flex items-center gap-2 text-[var(--sidebar-text-primary)] font-bold text-lg">
            <span class="flex h-9 w-9 items-center justify-center rounded bg-[var(--gold-light)] text-[var(--bg-sidebar)]">
                <i class="bi bi-box-seam text-base"></i>
            </span>
            <span class="hidden lg:inline">Gestión</span>
        </h5>
        <small class="mt-2 hidden lg:block text-xs font-medium text-[var(--sidebar-text-secondary)]/80">Panel Administrativo</small>
    </div>

    <ul class="mt-4 flex-1 space-y-1 px-2 lg:px-3">
        <li>
            <a href="{{ url('/dashboard') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('dashboard') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-house text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Inicio</span>
            </a>
        </li>

        <div class="mt-6 mb-2 px-3 text-[0.7rem] font-bold uppercase tracking-wider text-[var(--sidebar-text-secondary)]/80">Inventario</div>
        <li>
            <a href="{{ url('/productos') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('productos*') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-box text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Productos</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/categorias') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('categorias*') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-tag text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Categorías</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/recetas') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('recetas*') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-journal-text text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Recetas</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/produccion') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('produccion*') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-arrow-repeat text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Producción</span>
            </a>
        </li>

        <div class="mt-6 mb-2 px-3 text-[0.7rem] font-bold uppercase tracking-wider text-[var(--sidebar-text-secondary)]/80">Administración</div>
        <li>
            <a href="{{ url('/proveedores') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('proveedores*') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-truck text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Proveedores</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/usuarios') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('usuarios*') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-people text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Usuarios</span>
            </a>
        </li>

        <div class="mt-6 mb-2 px-3 text-[0.7rem] font-bold uppercase tracking-wider text-[var(--sidebar-text-secondary)]/80">Cuenta</div>
        <li>
            <a href="{{ url('/perfil') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->is('perfil') ? 'bg-white/10 text-[var(--sidebar-text-primary)]' : 'text-[var(--sidebar-text-secondary)] hover:bg-white/5 hover:text-[var(--sidebar-text-primary)]' }}">
                <i class="bi bi-person text-lg text-[var(--sidebar-text-muted)] group-hover:text-[var(--sidebar-text-primary)]"></i>
                <span class="hidden lg:inline">Mi Perfil</span>
            </a>
        </li>
    </ul>

    <div class="border-t border-[var(--border-color)] px-3 py-4">
        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-transparent px-3 py-2 text-sm font-medium text-[var(--danger)] hover:bg-red-500/10">
                <i class="bi bi-box-arrow-left"></i> <span class="hidden lg:inline">Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>

    <main class="ml-20 lg:ml-72 min-h-screen px-4 py-6 lg:px-6">
        <div class="max-w-none">
            <div>
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

    <button class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[var(--primary-brown)] text-white shadow-lg transition-transform hover:scale-110" id="themeToggle" title="Cambiar Apariencia">
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