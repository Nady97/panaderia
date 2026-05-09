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
<body style="background-color: var(--bg-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div class="w-full max-w-5xl flex rounded-[45px] overflow-hidden" style="border: 1px solid var(--border-color); background-color: var(--bg-card); box-shadow: var(--shadow-lg);">
        
        {{-- Panel Izquierdo --}}
        <div class="hidden md:flex md:w-1/2 relative items-center justify-center p-10 text-center" 
             style="background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop'); background-size: cover; background-position: center;">
            <div class="absolute inset-0" style="background: linear-gradient(160deg, rgba(36,20,11,0.92) 0%, rgba(15,9,6,0.95) 100%);"></div>
            <div class="relative z-10">
                <h1 class="text-4xl font-extrabold mb-4 leading-tight" style="color: var(--sidebar-text-primary); text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                    Panadería<br>Artesanal
                </h1>
                <p class="text-base font-light max-w-xs mx-auto" style="color: var(--sidebar-text-secondary);">
                    Gestión integral de producción, inventario y ventas
                </p>
            </div>
        </div>
        
        {{-- Panel Derecho --}}
        <div class="w-full md:w-1/2 p-8 sm:p-12 flex items-center" style="background-color: var(--bg-card);">
            <div class="w-full max-w-sm mx-auto">
                <h2 class="text-3xl font-extrabold mb-2" style="color: var(--text-primary);">Iniciar Sesión</h2>
                <p class="text-sm mb-8" style="color: var(--text-muted);">Ingresa tus credenciales para continuar</p>
                
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color: var(--text-secondary);">Correo electrónico</label>
                        <div class="flex items-stretch rounded-2xl border" style="border-color: var(--border-color); background-color: var(--bg-input);">
                            <span class="flex items-center pl-4" style="color: var(--text-muted);"><i class="bi bi-envelope text-lg"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                                   class="w-full py-3.5 px-4 bg-transparent outline-none text-base" style="color: var(--text-primary);" placeholder="tu@correo.com">
                        </div>
                    </div>
                    
                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color: var(--text-secondary);">Contraseña</label>
                        <div class="flex items-stretch rounded-2xl border" style="border-color: var(--border-color); background-color: var(--bg-input);">
                            <span class="flex items-center pl-4" style="color: var(--text-muted);"><i class="bi bi-lock text-lg"></i></span>
                            <input type="password" name="password" required 
                                   class="w-full py-3.5 px-4 bg-transparent outline-none text-base" style="color: var(--text-primary);" placeholder="••••••••">
                        </div>
                    </div>
                    
                    {{-- Opciones --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-sm" style="color: var(--text-muted);">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 rounded" style="accent-color: var(--btn-bg);"> Recordar sesión
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold" style="color: var(--gold-dark);">¿Olvidaste tu clave?</a>
                    </div>
                    
                    {{-- Botón --}}
                    <button type="submit" 
                            class="w-full py-3.5 rounded-2xl font-extrabold flex items-center justify-center gap-2 transition-all duration-300"
                            style="background-color: var(--btn-bg); color: var(--btn-text); box-shadow: 0 4px 15px rgba(129,87,45,0.3);"
                            onmouseover="this.style.backgroundColor='var(--btn-hover)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(129,87,45,0.5)'"
                            onmouseout="this.style.backgroundColor='var(--btn-bg)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(129,87,45,0.3)'">
                        Acceder al sistema <i class="bi bi-arrow-right text-lg"></i>
                    </button>
                    
                    {{-- Alertas --}}
                    @if(session('status'))
                        <div class="flex items-center gap-3 p-4 rounded-2xl text-sm" style="background-color: rgba(42,123,79,0.08); border: 1px solid rgba(42,123,79,0.2); color: var(--success);">
                            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="flex items-center gap-3 p-4 rounded-2xl text-sm" style="background-color: rgba(207,59,59,0.08); border: 1px solid rgba(207,59,59,0.2); color: var(--danger);">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                        </div>
                    @endif
                </form>
                
                <div class="mt-10 pt-5 border-t text-center text-xs" style="border-color: var(--border-color); color: var(--text-muted);">
                    <i class="bi bi-shield-check" style="color: var(--gold-light);"></i> Software de Gestión ERP<br>
                    <span class="opacity-70">&copy; {{ date('Y') }} Panadería Artesanal</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Toggle --}}
    <button class="fixed bottom-6 right-6 w-[50px] h-[50px] rounded-full flex items-center justify-center text-xl transition-all duration-300 z-50"
            style="background-color: var(--btn-bg); color: var(--btn-text); box-shadow: var(--shadow-lg);"
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