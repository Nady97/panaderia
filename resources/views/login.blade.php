{{--
|--------------------------------------------------------------------------
| Vista: Login
|--------------------------------------------------------------------------
| Esta vista renderiza el acceso al sistema para usuarios autenticables.
| Incluye un panel informativo, formulario de credenciales y mensajes de
| error de sesion/validacion con estilo unificado del proyecto.
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ERP Panaderia Artesanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/global.css', 'resources/css/login.css'])
</head>
<body class="dark-mode d-flex flex-column vh-100">

    <div class="container-fluid flex-grow-1 d-flex p-0 m-0">
        <div class="row g-0 w-100">
            
            <!-- Panel izquierdo: resumen visual del sistema -->
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center login-showcase text-white p-5 text-center">
                <i class="bi bi-shop display-1 text-gold mb-3"></i>
                <h1 class="display-4 fw-bold mb-3">Panaderia Artesanal</h1>
                <p class="lead opacity-75">Gestión integral de producción, inventario y ventas.</p>
            </div>
            
            <!-- Panel derecho: formulario de autenticacion -->
            <div class="col-lg-6 col-12 d-flex justify-content-center align-items-center login-panel p-4 p-md-5">
                <div class="w-100" style="max-width: 420px;">
                    
                    <div class="mb-5">
                        <h2 class="fw-bold mb-2" style="color: var(--text-primary);">Iniciar Sesión</h2>
                        <p style="color: var(--text-light);">Ingresa tus credenciales para continuar</p>
                    </div>

                    <form method="POST" action="{{ url('/login') }}" novalidate>
                        @csrf

                        <!-- Email -->
                        <div class="input-group mb-4">
                            <span class="input-group-text input-group-text-custom px-3"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-custom py-3" placeholder="Correo electronico" value="{{ old('email') }}" required autofocus>
                        </div>

                        <!-- Password -->
                        <div class="input-group mb-4">
                            <span class="input-group-text input-group-text-custom px-3"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control form-control-custom py-3" placeholder="Contraseña" required>
                        </div>

                        <!-- Opciones secundarias -->
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember" style="color: var(--text-light);">
                                    Recordar sesión
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none text-gold fw-medium">¿Olvidaste tu clave?</a>
                        </div>

                        <button type="submit" class="btn btn-login w-100 py-3 mb-4 rounded-3 d-flex justify-content-center align-items-center gap-2">
                            Acceder al sistema <i class="bi bi-arrow-right"></i>
                        </button>

                        <!-- Alertas de backend -->
                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center gap-2 border-0 bg-danger bg-opacity-10 text-danger rounded-3">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                            </div>
                        @endif

                        @if( $errors->any() )
                            <div class="alert alert-danger d-flex align-items-center gap-2 border-0 bg-danger bg-opacity-10 text-danger rounded-3">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                            </div>
                        @endif
                    </form>

                    <div class="text-center mt-5 pt-4 text-secondary opacity-75 small" style="border-top: 1px solid var(--border-color);">
                        <i class="bi bi-shield-check text-gold"></i> Software de Gestión ERP<br>
                        &copy; {{ date('Y') }} Panaderia Artesanal
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>

