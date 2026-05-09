<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - ERP Panadería Artesanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/global.css', 'resources/css/login.css'])
</head>
<body class="dark-mode d-flex flex-column vh-100">

    <div class="container-fluid flex-grow-1 d-flex p-0 m-0">
        <div class="row g-0 w-100">
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center login-showcase text-white p-5 text-center">
                <i class="bi bi-shield-lock display-1 text-gold mb-3"></i>
                <h1 class="display-4 fw-bold mb-3">Recupera tu acceso</h1>
                <p class="lead opacity-75">Te enviaremos un enlace para crear una nueva contraseña.</p>
            </div>

            <div class="col-lg-6 col-12 d-flex justify-content-center align-items-center login-panel p-4 p-md-5">
                <div class="w-100" style="max-width: 420px;">
                    <div class="mb-5">
                        <h2 class="fw-bold mb-2" style="color: var(--text-primary);">Recuperar contraseña</h2>
                        <p style="color: var(--text-light);">Ingresa tu correo para recibir el enlace de recuperación</p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="input-group mb-4">
                            <span class="input-group-text input-group-text-custom px-3"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-custom py-3" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-login w-100 py-3 mb-4 rounded-3 d-flex justify-content-center align-items-center gap-2">
                            Enviar enlace <i class="bi bi-arrow-right"></i>
                        </button>

                        @if(session('status'))
                            <div class="alert alert-success d-flex align-items-center gap-2 border-0 bg-success bg-opacity-10 text-success rounded-3">
                                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger d-flex align-items-center gap-2 border-0 bg-danger bg-opacity-10 text-danger rounded-3">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                            </div>
                        @endif
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none text-gold fw-medium">Volver al login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
