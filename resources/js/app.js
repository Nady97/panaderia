import Swal from 'sweetalert2';

window.Swal = Swal;

document.addEventListener('DOMContentLoaded', () => {
    // Configuración global de Toast
    window.Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: 'var(--bg-card)',
        color: 'var(--text-primary)',
        customClass: {
            popup: 'rounded-xl shadow border border-gray-200'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Leer alertas desde el Server y dispararlas
    if (window.ServerData) {
        if (window.ServerData.success) {
            window.Toast.fire({ icon: 'success', title: window.ServerData.success });
        }
        if (window.ServerData.error) {
            window.Toast.fire({ icon: 'error', title: window.ServerData.error });
        }
        if (window.ServerData.hasErrors) {
            window.Toast.fire({ icon: 'error', title: 'Hay errores en el formulario, revise los campos.' });
        }
    }

    // Event Delegation para botones de Eliminar genéricos
    document.body.addEventListener('submit', function (e) {
        if (e.target && e.target.classList.contains('form-delete')) {
            e.preventDefault();
            const form = e.target;
            const textMsg = form.getAttribute('data-confirm-text') || 'Esta acción no se puede deshacer y eliminará permanentemente el registro.';

            Swal.fire({
                title: '¿Está seguro?',
                text: textMsg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--text-muted)',
                confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: 'var(--bg-card)',
                color: 'var(--text-primary)',
                customClass: {
                    popup: 'rounded-xl shadow border border-gray-200',
                    confirmButton: 'inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700',
                    cancelButton: 'inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    // --- TEMA TOGGLE (Dark Mode) ---
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;

    // Función auxiliar para actualizar el ícono
    const updateThemeIcon = (isDark) => {
        if (themeIcon) {
            themeIcon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
        }
    };

    // Función auxiliar para aplicar el tema
    const applyTheme = (isDark) => {
        if (isDark) {
            htmlElement.classList.add('dark-mode');
        } else {
            htmlElement.classList.remove('dark-mode');
        }
        htmlElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeIcon(isDark);
    };

    // Estado UI inicial
    if (themeToggle && themeIcon) {
        const isDarkMode = htmlElement.classList.contains('dark-mode');
        updateThemeIcon(isDarkMode);

        // Click listener para cambiar tema
        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isDark = htmlElement.classList.toggle('dark-mode');
            applyTheme(isDark);
        });
    }
});
