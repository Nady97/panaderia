// Se ejecuta al cargar la página
document.addEventListener('DOMContentLoaded', () => {

    // --- Animaciones sutiles de Carga de Elementos ---
    const elements = document.querySelectorAll('.dashboard-container .card, .dashboard-container .row > div');
    elements.forEach((el, index) => {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
        el.style.transition = `all 0.4s ease ${index * 0.05}s`;
    });

    // --- Confirmaciones Nativas Serias antes de Eliminar ---
    const deleteForms = document.querySelectorAll('form[action*="/productos/"]');
    deleteForms.forEach(form => {
        // Asegurar que si hay confirmación inline no choque con la de JS o viceversa
        // Preferimos dejar el onclick="return confirm(...)" directamente en Blade
        // por ser más robusto, así que aquí solo limpiamos lógica residual.
    });

    // --- Lógica para filtro en cliente de productos ---
    const searchInput = document.getElementById('searchInput');
    const filterStock = document.getElementById('filterStock');
    const searchBtn = document.getElementById('searchBtn');
    const rows = document.querySelectorAll('.producto-fila');
    
    function filterProducts() {
        if (!searchInput || !filterStock) return;
        
        const searchTerm = searchInput.value.toLowerCase();
        const stockFilter = filterStock.value;
        
        rows.forEach(row => {
            const name = row.getAttribute('data-nombre') || '';
            const stock = parseFloat(row.getAttribute('data-stock')) || 0;
            const estado = row.getAttribute('data-estado') || '';
            const precioStr = row.getAttribute('data-precio') || '';
            const iteraStr = row.getAttribute('data-itera') || '';

            const matchesSearch = name.includes(searchTerm) || 
                                  stock.toString().includes(searchTerm) || 
                                  precioStr.includes(searchTerm) || 
                                  iteraStr.includes(searchTerm);
            let matchesStock = true;
            
            if (stockFilter === 'active') matchesStock = (estado === 'activo');
            else if (stockFilter === 'low') matchesStock = (stock > 0 && stock <= 5);
            else if (stockFilter === 'out') matchesStock = (stock <= 0);
            
            row.style.display = (matchesSearch && matchesStock) ? '' : 'none';
        });
    }
    
    if (searchInput && filterStock) {
        searchInput.addEventListener('input', filterProducts);
        filterStock.addEventListener('change', filterProducts);
        
        if(searchBtn) {
            searchBtn.addEventListener('click', filterProducts);
        }
    }

});
