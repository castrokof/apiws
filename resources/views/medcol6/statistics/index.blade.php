<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estadísticas e Indicadores Medcol6 - Medicamentos Pendientes</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-danger: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-attachment: fixed;
        }

        .dashboard-container {
            background: var(--light-color);
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            margin: 20px;
            overflow: hidden;
        }

        .dashboard-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .dashboard-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .filters-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin: 2rem;
            box-shadow: var(--shadow-md);
            border-left: 4px solid var(--primary-color);
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .form-label {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .stat-card.success::before { background: var(--gradient-success); }
        .stat-card.warning::before { background: var(--gradient-warning); }
        .stat-card.info::before { background: var(--gradient-info); }
        .stat-card.danger::before { background: var(--gradient-danger); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
        }

        .stat-card.success .stat-icon { background: var(--gradient-success); }
        .stat-card.warning .stat-icon { background: var(--gradient-warning); }
        .stat-card.info .stat-icon { background: var(--gradient-info); }
        .stat-card.danger .stat-icon { background: var(--gradient-danger); }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            line-height: 1;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 2rem;
            margin: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: var(--shadow-xl);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .chart-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .chart-title i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 20px;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .estado-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .estado-pendiente { background: #fef3c7; color: #d97706; }
        .estado-entregado { background: #dcfce7; color: #16a34a; }
        .estado-anulado { background: #fee2e2; color: #dc2626; }
        .estado-desabastecido { background: #f3e8ff; color: #9333ea; }
        .estado-sincontacto { background: #e0f2fe; color: #0284c7; }
        .estado-tramitado { background: #ecfdf5; color: #059669; }
        .estado-vencido { background: #fecaca; color: #b91c1c; }

        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2rem;
            }

            .stats-grid,
            .charts-grid {
                grid-template-columns: 1fr;
                margin: 1rem;
            }

            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-chart-line me-2"></i>
                Medcol6 - Estadísticas e Indicadores
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('submenu') }}">
                    <i class="fas fa-home me-1"></i>
                    Inicio
                </a>
                <a class="nav-link text-white" href="{{ route('medcol6.pendientes') }}">
                    <i class="fas fa-pills me-1"></i>
                    Pendientes
                </a>
                <a class="nav-link text-white" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    Salir
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="dashboard-container animate__animated animate__fadeIn">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-bar"></i>
                Estadísticas e Indicadores Medcol6
            </h1>
            <p class="dashboard-subtitle">Análisis integral de pendientes por facturar y entregados</p>
        </div>

        <!-- Sección de Filtros -->
        <div class="filters-section animate__animated animate__fadeInUp">
            <h3 class="filters-title">
                <i class="fas fa-filter"></i>
                Filtros de Análisis
            </h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_inicio" class="form-label fw-semibold">Fecha Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_fin" class="form-label fw-semibold">Fecha Fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
                </div>
            </div>
        </div>

        <!-- Grid de Estadísticas por Estado -->
        <div class="stats-grid" id="estadisticas-cards">
            <!-- Se cargarán dinámicamente -->
        </div>

        <!-- Grid de Gráficas -->
        <div class="charts-grid">
            <div class="chart-card animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Distribución por Estado
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartEstados"></canvas>
                </div>
            </div>

            <div class="chart-card animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-dollar-sign"></i>
                        Valor por Estado
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartValores"></canvas>
                </div>
            </div>

            <div class="chart-card animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        Tendencias Mensuales
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartTendencias"></canvas>
                </div>
            </div>

            <div class="chart-card animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        Top Medicamentos por Valor
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartTopMedicamentos"></canvas>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.color = '#6b7280';
            Chart.defaults.borderColor = '#e5e7eb';
            Chart.defaults.backgroundColor = 'rgba(99, 102, 241, 0.1)';

            let chartEstados, chartValores, chartTendencias, chartTopMedicamentos;

            function showLoading() {
                $('#loading-overlay').fadeIn(300);
            }

            function hideLoading() {
                $('#loading-overlay').fadeOut(300);
            }

            function actualizarEstadisticas() {
                showLoading();

                let fechaInicio = $('#fecha_inicio').val();
                let fechaFin = $('#fecha_fin').val();

                $.ajax({
                    url: "{{ route('medcol6.statistics.ajax') }}",
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin
                    },
                    success: function(data) {
                        actualizarCards(data.estadisticas_por_estado);
                        actualizarGraficaEstados(data.estadisticas_por_estado);
                        actualizarGraficaValores(data.estadisticas_por_estado);
                        actualizarGraficaTendencias(data.tendencias_por_mes);
                        actualizarGraficaTopMedicamentos(data.top_medicamentos_pendientes);
                        hideLoading();
                    },
                    error: function() {
                        hideLoading();
                        alert('Error al cargar las estadísticas');
                    }
                });
            }

            function actualizarCards(estadisticas) {
                const container = $('#estadisticas-cards');
                container.empty();

                const estadosConfig = {
                    'PENDIENTE': { icon: 'fas fa-clock', class: 'warning', label: 'Pendientes' },
                    'ENTREGADO': { icon: 'fas fa-check-circle', class: 'success', label: 'Entregados' },
                    'ANULADO': { icon: 'fas fa-times-circle', class: 'danger', label: 'Anulados' },
                    'DESABASTECIDO': { icon: 'fas fa-exclamation-triangle', class: 'info', label: 'Desabastecidos' },
                    'SIN CONTACTO': { icon: 'fas fa-phone-slash', class: 'secondary', label: 'Sin Contacto' },
                    'TRAMITADO': { icon: 'fas fa-hourglass-half', class: 'primary', label: 'Tramitados' },
                    'VENCIDO': { icon: 'fas fa-calendar-times', class: 'danger', label: 'Vencidos' }
                };

                estadisticas.forEach((stat, index) => {
                    const config = estadosConfig[stat.estado] || { icon: 'fas fa-circle', class: 'secondary', label: stat.estado };
                    const valorFormateado = new Intl.NumberFormat('es-CO', {
                        style: 'currency',
                        currency: 'COP',
                        maximumFractionDigits: 0
                    }).format(stat.valor_total);

                    const card = $(`
                        <div class="stat-card ${config.class} animate__animated animate__fadeInUp" style="animation-delay: ${index * 0.1}s;">
                            <div class="stat-icon">
                                <i class="${config.icon}"></i>
                            </div>
                            <h2 class="stat-value">${stat.total_pendientes}</h2>
                            <p class="stat-label">${config.label}</p>
                            <div class="mt-2">
                                <small class="text-muted">Valor Total: <strong>${valorFormateado}</strong></small>
                            </div>
                        </div>
                    `);

                    container.append(card);
                });
            }

            function actualizarGraficaEstados(estadisticas) {
                const ctx = document.getElementById('chartEstados').getContext('2d');

                if (chartEstados) {
                    chartEstados.destroy();
                }

                const labels = estadisticas.map(stat => stat.estado);
                const datos = estadisticas.map(stat => stat.total_pendientes);

                const colors = [
                    '#f59e0b', '#10b981', '#ef4444', '#06b6d4',
                    '#8b5cf6', '#6366f1', '#f97316'
                ];

                chartEstados = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: datos,
                            backgroundColor: colors.slice(0, labels.length),
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: { weight: 500 }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#6366f1',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = ((context.parsed * 100) / total).toFixed(1);
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function actualizarGraficaValores(estadisticas) {
                const ctx = document.getElementById('chartValores').getContext('2d');

                if (chartValores) {
                    chartValores.destroy();
                }

                const labels = estadisticas.map(stat => stat.estado);
                const datos = estadisticas.map(stat => stat.valor_total);

                chartValores = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor Total',
                            data: datos,
                            backgroundColor: 'rgba(99, 102, 241, 0.8)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#6366f1',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return 'Valor: $' + context.parsed.y.toLocaleString('es-CO');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#6b7280',
                                    font: { weight: 500 }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f3f4f6' },
                                ticks: {
                                    color: '#6b7280',
                                    font: { weight: 500 },
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-CO');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function actualizarGraficaTendencias(tendencias) {
                const ctx = document.getElementById('chartTendencias').getContext('2d');

                if (chartTendencias) {
                    chartTendencias.destroy();
                }

                if (!tendencias || tendencias.length === 0) {
                    return;
                }

                const labels = tendencias.map(t => {
                    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    return meses[t.mes - 1] + ' ' + t.año;
                });

                const estados = ['PENDIENTE', 'ENTREGADO', 'ANULADO'];
                const coloresEstados = {
                    'PENDIENTE': '#f59e0b',
                    'ENTREGADO': '#10b981',
                    'ANULADO': '#ef4444'
                };

                const datasets = estados.map(estado => ({
                    label: estado,
                    data: tendencias.map(t => t.estados[estado] || 0),
                    borderColor: coloresEstados[estado],
                    backgroundColor: coloresEstados[estado] + '20',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4
                }));

                chartTendencias = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    font: { weight: 500 }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#6b7280',
                                    font: { weight: 500 }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f3f4f6' },
                                ticks: {
                                    color: '#6b7280',
                                    font: { weight: 500 }
                                }
                            }
                        }
                    }
                });
            }

            function actualizarGraficaTopMedicamentos(medicamentos) {
                const ctx = document.getElementById('chartTopMedicamentos').getContext('2d');

                if (chartTopMedicamentos) {
                    chartTopMedicamentos.destroy();
                }

                if (!medicamentos || medicamentos.length === 0) {
                    return;
                }

                const labels = medicamentos.slice(0, 10).map(med => {
                    const nombre = med.nombre;
                    return nombre.length > 20 ? nombre.substring(0, 20) + '...' : nombre;
                });
                const valores = medicamentos.slice(0, 10).map(med => med.valor_total);

                chartTopMedicamentos = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor Total',
                            data: valores,
                            backgroundColor: 'rgba(99, 102, 241, 0.8)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#6366f1',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return 'Valor: $' + context.parsed.x.toLocaleString('es-CO');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: '#f3f4f6' },
                                ticks: {
                                    color: '#6b7280',
                                    font: { weight: 500 },
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-CO');
                                    }
                                }
                            },
                            y: {
                                grid: { display: false },
                                ticks: {
                                    color: '#6b7280',
                                    font: { weight: 500 }
                                }
                            }
                        }
                    }
                });
            }

            // Event listeners para filtros
            $(document).on('change', '#fecha_inicio, #fecha_fin', function() {
                actualizarEstadisticas();
            });

            // Cargar datos iniciales
            actualizarEstadisticas();
        });
    </script>
</body>
</html>