{{--
|--------------------------------------------------------------------------
| Vista: Welcome
|--------------------------------------------------------------------------
| Esta es la portada publica del sistema. Presenta una bienvenida breve
| y redirige al usuario hacia el flujo de autenticacion.
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panaderia Artesanal</title>
    @vite(['resources/css/global.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .welcome-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-primary);
        }
        .welcome-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 45px;
            padding: 50px 60px;
            text-align: center;
            box-shadow: var(--shadow-lg);
            max-width: 600px;
            width: 90%;
            color: var(--text-primary);
        }
        .logo-icon {
            width: 96px;
            height: 96px;
            margin: 0 auto 20px;
            border-radius: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            color: #1a0f0a;
            background: linear-gradient(145deg, #f2d29a, #cda75f);
            box-shadow: 0 10px 25px rgba(205, 167, 95, 0.35);
        }
        h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 15px; }
        p { color: var(--text-muted); margin-bottom: 30px; font-size: 1.1rem; }
        .btn-ingresar {
            background-color: var(--accent-gold, #cda75f); /* Un fallback por las dudas */
            color: #1a0f0a !important; /* Texto oscuro sobre botón dorado */
            border: none;
            padding: 14px 40px;
            border-radius: 40px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        .btn-ingresar:hover {
            transform: translateY(-3px);
            opacity: 0.9;
            box-shadow: 0 8px 25px rgba(205, 167, 95, 0.4);
        }

        @media (max-width: 768px) {
            .welcome-card {
                border-radius: 28px;
                padding: 36px 28px;
            }

            h1 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .welcome-card {
                padding: 30px 20px;
            }

            .logo-icon {
                width: 78px;
                height: 78px;
                font-size: 1.9rem;
                border-radius: 22px;
            }

            .btn-ingresar {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="dark-mode">
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="logo-icon" aria-hidden="true"><i class="bi bi-shop"></i></div>
            <h1>Panaderia Artesanal</h1>
            <p>Sistema de gestión para la producción</p>
            <a href="{{ url('/login') }}" class="btn-ingresar">
                <i class="bi bi-box-arrow-in-right"></i>
                Iniciar Sesion
            </a>
        </div>
    </div>
</body>
</html>