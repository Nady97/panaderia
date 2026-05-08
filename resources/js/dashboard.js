// ============================================
// DASHBOARD - JAVASCRIPT MEJORADO
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // 1. GRÁFICO DE VENTAS
    // ============================================
    const ctx = document.getElementById('salesChart')?.getContext('2d');
    let salesChart = null;
    
    // Datos de ejemplo (reemplazar con datos reales de BD)
    const chartData = {
        week: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            values: [1250, 1450, 1680, 1820, 2100, 2350, 1980]
        },
        month: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
            values: [8500, 9200, 10100, 11800]
        },
        year: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            values: [25000, 28000, 31000, 29000, 35000, 38000, 42000, 45000, 43000, 48000, 51000, 55000]
        }
    };
    
    function initChart(period = 'week') {
        const data = chartData[period];
        if (salesChart) {
            salesChart.destroy();
        }
        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Ventas (Bs)',
                    data: data.values,
                    borderColor: '#e69317',
                    backgroundColor: 'rgba(230, 147, 23, 0.05)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#e69317',
                    pointBorderColor: 'white',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: (ctx) => `Bs ${ctx.raw.toLocaleString()}` } }
                },
                scales: { y: { beginAtZero: true, ticks: { callback: (value) => `Bs ${value.toLocaleString()}` } } }
            }
        });
    }
    
    if (ctx) {
        initChart('week');
        
        // Botones de período
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const period = this.getAttribute('data-period');
                initChart(period);
            });
        });
    }
    
    // ============================================
    // 2. ANIMACIÓN DE ENTRADA
    // ============================================
    const cards = document.querySelectorAll('.card-modern');
    cards.forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, i * 80);
    });
    
    // ============================================
    // 3. EFECTOS HOVER EN TARJETAS
    // ============================================
    document.querySelectorAll('.card-modern').forEach(card => {
        card.addEventListener('mouseenter', () => {
            const icon = card.querySelector('.stat-icon');
            if (icon) icon.style.transform = 'scale(1.05)';
        });
        card.addEventListener('mouseleave', () => {
            const icon = card.querySelector('.stat-icon');
            if (icon) icon.style.transform = 'scale(1)';
        });
    });
});

// ============================================
// FUNCIONES DE EXPORTACIÓN
// ============================================
function exportToExcel() {
    alert('📊 Funcionalidad de exportación a Excel en desarrollo');
    // Aquí iría la lógica de exportación
}

function exportToPDF() {
    alert('📄 Funcionalidad de exportación a PDF en desarrollo');
    // Aquí iría la lógica de exportación
}
