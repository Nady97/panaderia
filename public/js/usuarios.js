/**
 * USUARIOS - JavaScript para gestión de usuarios
 * Adaptado para Paginación Server-Side
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // CONFIRMACIÓN PARA ELIMINAR (Fallback por si no se usa el del Blade)
    // ============================================
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm(`⚠️ ¿Estás seguro de eliminar este usuario?\n\nEsta acción no se puede deshacer.`)) {
                e.preventDefault();
            }
        });
    });
    
});
