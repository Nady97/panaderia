{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería Artesanal</title>
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
        .logo-icon { font-size: 5rem; margin-bottom: 20px; }
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
    </style>
</head>
<body class="dark-mode">
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="logo-icon">🥖🍞🥐</div>
            <h1>Panadería Artesanal</h1>
            <p>Sistema de gestión para la producción</p>
            <a href="/login" class="btn-ingresar">
                <i class="bi bi-box-arrow-in-right"></i>
                Iniciar Sesión
            </a>
        </div>
    </div>
</body>
</html>