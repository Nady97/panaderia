<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión · Panadería Artesanal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/global.css'])
    </head>
    <body class="min-h-screen bg-[var(--bg-primary)] flex items-center justify-center">

        <div class="w-full max-w-5xl overflow-hidden rounded-[45px] border border-[var(--border-color)] bg-[var(--bg-card)] shadow-[var(--shadow-lg)] md:flex">

            {{-- Panel Izquierdo --}}
            <div class="relative hidden items-center justify-center p-10 text-center md:flex md:w-1/2 bg-[url('https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop')] bg-cover bg-center">
                <div class="absolute inset-0 bg-[linear-gradient(160deg,rgba(36,20,11,0.92)_0%,rgba(15,9,6,0.95)_100%)]"></div>
                <div class="relative z-10">
                    <h1 class="mb-4 text-4xl font-extrabold leading-tight text-[var(--sidebar-text-primary)] drop-shadow">
                        Panadería<br>Artesanal
                    </h1>
                    <p class="mx-auto max-w-xs text-base font-light text-[var(--sidebar-text-secondary)]">
                        Gestión integral de producción, inventario y ventas
                    </p>
                </div>
            </div>

            {{-- Panel Derecho --}}
            <div class="flex w-full items-center bg-[var(--bg-card)] p-8 sm:p-12 md:w-1/2">
                <div class="mx-auto w-full max-w-sm">
                    <h2 class="mb-2 text-3xl font-extrabold text-[var(--text-primary)]">Iniciar Sesión</h2>
                    <p class="mb-8 text-sm text-[var(--text-muted)]">Ingresa tus credenciales para continuar</p>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-[var(--text-secondary)]">Correo electrónico</label>
                            <div class="flex items-stretch rounded-2xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                                <span class="flex items-center pl-4 text-[var(--text-muted)]"><i class="bi bi-envelope text-lg"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="w-full bg-transparent px-4 py-3.5 text-base text-[var(--text-primary)] outline-none placeholder:text-[var(--text-light)]"
                                    placeholder="tu@correo.com">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-[var(--text-secondary)]">Contraseña</label>
                            <div class="flex items-stretch rounded-2xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                                <span class="flex items-center pl-4 text-[var(--text-muted)]"><i class="bi bi-lock text-lg"></i></span>
                                <input type="password" name="password" required
                                    class="w-full bg-transparent px-4 py-3.5 text-base text-[var(--text-primary)] outline-none placeholder:text-[var(--text-light)]"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        {{-- Opciones --}}
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex cursor-pointer items-center gap-2 text-sm text-[var(--text-muted)]">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="h-4 w-4 rounded accent-[var(--btn-bg)]"> Recordar sesión
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[var(--gold-dark)]">¿Olvidaste tu clave?</a>
                        </div>

                        {{-- Botón --}}
                        <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[var(--btn-bg)] py-3.5 font-extrabold text-[var(--btn-text)] shadow-[0_4px_15px_rgba(129,87,45,0.3)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-[var(--btn-hover)]">
                            Acceder al sistema <i class="bi bi-arrow-right text-lg"></i>
                        </button>

                        {{-- Alertas --}}
                        @if(session('status'))
                            <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                            </div>
                        @endif
                    </form>

                    <div class="mt-10 border-t border-[var(--border-color)] pt-5 text-center text-xs text-[var(--text-muted)]">
                        <i class="bi bi-shield-check text-[var(--gold-light)]"></i> Software de Gestión ERP<br>
                        <span class="opacity-70">&copy; {{ date('Y') }} Panadería Artesanal</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toggle --}}
        <button class="fixed bottom-6 right-6 z-50 flex h-[50px] w-[50px] items-center justify-center rounded-full bg-[var(--btn-bg)] text-[var(--btn-text)] shadow-[var(--shadow-lg)] transition-all duration-300 hover:scale-110"
            onclick="toggleTheme()" title="Cambiar Apariencia">
            <i class="bi bi-moon-stars" id="themeIcon"></i>
        </button>
    
    <script>
        const html = document.documentElement;
        const icon = document.getElementById('themeIcon');
        if (localStorage.getItem('theme') === 'light') {
            html.classList.remove('dark');
            icon.classList.replace('bi-moon-stars', 'bi-sun-fill');
        }
        function toggleTheme() {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            icon.className = isDark ? 'bi bi-moon-stars' : 'bi bi-sun-fill';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>
</body>
</html>