{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="es" class="dark-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería Artesanal</title>
    @vite(['resources/css/global.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">



</head>
<body style="background-color: var(--bg-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    
    <div style="
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 45px;
        padding: 50px 60px;
        text-align: center;
        box-shadow: var(--shadow-lg);
        max-width: 600px;
        width: 90%;
    ">
        {{-- Iconos de Panadería --}}
        <div style="font-size: 5rem; margin-bottom: 20px;">🥖🍞🥐</div>
        
        {{-- Título --}}
        <h1 style="
            color: var(--text-primary);
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 15px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        ">
            Panadería Artesanal
        </h1>
        
        {{-- Descripción --}}
        <p style="
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 1.1rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        ">
            Sistema de gestión para la producción
        </p>
        
        {{-- Botón de Inicio de Sesión --}}
        <a href="/login" style="
            background-color: var(--btn-bg);
            color: var(--btn-text);
            border: none;
            padding: 14px 40px;
            border-radius: 40px;
            font-weight: 800;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(129, 87, 45, 0.3);
        " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(129, 87, 45, 0.5)'; this.style.backgroundColor='var(--btn-hover)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(129, 87, 45, 0.3)'; this.style.backgroundColor='var(--btn-bg)'">
            <i class="bi bi-box-arrow-in-right"></i>
            Iniciar Sesión
        </a>
    </div>

    {{-- Theme Toggle --}}
    <button class="theme-toggle" id="themeToggle" title="Cambiar Apariencia">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
    </button>

    <script>
        // Toggle Modo Oscuro/Claro
        const toggle = document.getElementById('themeToggle');
        const icon = document.getElementById('themeIcon');
        const html = document.documentElement;
        
        // Estado inicial
        if (!html.classList.contains('dark-mode')) {
            icon.classList.replace('bi-moon-stars', 'bi-sun-fill');
        }
        
        toggle.addEventListener('click', () => {
            html.classList.toggle('dark-mode');
            html.classList.toggle('loaded');
            
            if (html.classList.contains('dark-mode')) {
                icon.classList.replace('bi-sun-fill', 'bi-moon-stars');
                html.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                icon.classList.replace('bi-moon-stars', 'bi-sun-fill');
                html.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });
    </script>

</body>
</html>