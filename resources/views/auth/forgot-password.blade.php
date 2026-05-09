<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - ERP Panadería Artesanal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/global.css'])
</head>
<body class="min-h-screen bg-[var(--bg-primary)]">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <div class="relative hidden items-center justify-center bg-[url('https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop')] bg-cover bg-center p-10 text-center lg:flex">
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(45,31,26,0.85)_0%,rgba(26,15,10,0.95)_100%)]"></div>
            <div class="relative z-10 text-white">
                <i class="bi bi-shield-lock mb-3 text-5xl text-[var(--gold-dark)]"></i>
                <h1 class="mb-3 text-4xl font-extrabold">Recupera tu acceso</h1>
                <p class="text-base opacity-75">Te enviaremos un enlace para crear una nueva contraseña.</p>
            </div>
        </div>

        <div class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md">
                <div class="mb-6">
                    <h2 class="mb-2 text-2xl font-extrabold text-[var(--text-primary)]">Recuperar contraseña</h2>
                    <p class="text-[var(--text-light)]">Ingresa tu correo para recibir el enlace de recuperación</p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-[var(--text-secondary)]">Correo electrónico</label>
                        <div class="flex items-stretch rounded-xl border border-[var(--border-color)] bg-[var(--bg-input)]">
                            <span class="flex items-center px-3 text-[var(--text-light)]"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="w-full bg-transparent px-3 py-3 text-[var(--text-primary)] outline-none placeholder:text-[var(--text-light)]" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[var(--btn-bg)] py-3 font-semibold text-[var(--btn-text)] shadow-[0_4px_15px_rgba(129,87,45,0.3)] transition-all hover:-translate-y-0.5 hover:bg-[var(--btn-hover)]">
                        Enviar enlace <i class="bi bi-arrow-right"></i>
                    </button>

                    @if(session('status'))
                        <div class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                        </div>
                    @endif
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-[var(--gold-dark)]">Volver al login</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
