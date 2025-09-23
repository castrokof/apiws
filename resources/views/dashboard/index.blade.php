<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Analytics - Medicamentos Pendientes</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
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

        .filters-title {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .filters-title i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-control:focus,
        .form-select:focus {
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

        .stat-card.success::before {
            background: var(--gradient-success);
        }

        .stat-card.warning::before {
            background: var(--gradient-warning);
        }

        .stat-card.info::before {
            background: var(--gradient-info);
        }

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

        .stat-card.success .stat-icon {
            background: var(--gradient-success);
        }

        .stat-card.warning .stat-icon {
            background: var(--gradient-warning);
        }

        .stat-card.info .stat-icon {
            background: var(--gradient-info);
        }

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
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
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
            justify-content: between;
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
            height: 300px;
        }

        .data-table-section {
            margin: 2rem;
        }

        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .table-header {
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
        }

        .table-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .table-header i {
            margin-right: 0.5rem;
        }

        .table-container {
            padding: 2rem;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table thead th {
            background: #f8fafc;
            border: none;
            font-weight: 600;
            color: var(--dark-color);
            padding: 1rem;
        }

        .table tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .badge {
            border-radius: 12px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
        }

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

            .stats-grid {
                grid-template-columns: 1fr;
                margin: 1rem;
            }

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
    <!-- Navbar Simple -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-chart-line me-2"></i>
                MedCol Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('submenu') }}">
                    <i class="fas fa-home me-1"></i>
                    Inicio
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configuración global de Chart.js
            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.color = '#6b7280';
            Chart.defaults.borderColor = '#e5e7eb';
            Chart.defaults.backgroundColor = 'rgba(99, 102, 241, 0.1)';

            // Inicializar DataTable con diseño mejorado
            let table = $('#top-medicamentos-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('dashboard.top-medicamentos-datatable') }}",
                    data: function(d) {
                        d.fecha_inicio = $('#fecha_inicio').val();
                        d.fecha_fin = $('#fecha_fin').val();
                        d.contrato = $('#contrato').val();
                    }
                },
                columns: [{
                        data: 'nombre_generico',
                        name: 'nombre_generico',
                        title: 'Medicamento',
                        render: function(data, type, row) {
                            return '<span class="fw-semibold text-primary">' + data + '</span>';
                        }
                    },
                    {
                        data: 'total_medicamento',
                        name: 'total_medicamento',
                        title: 'Valor Total',
                        render: function(data, type, row) {
                            return '<span class="badge bg-success">' + data + '</span>';
                        }
                    },
                    {
                        data: 'total_unidades',
                        name: 'total_unidades',
                        title: 'Unidades',
                        render: function(data, type, row) {
                            return '<span class="text-info fw-bold">' + data + '</span>';
                        }
                    },
                    {
                        data: 'total_dispensaciones',
                        name: 'total_dispensaciones',
                        title: 'Dispensaciones',
                        render: function(data, type, row) {
                            return '<span class="text-warning fw-bold">' + data + '</span>';
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json",
                    processing: '<div class="d-flex align-items-center"><div class="loading-spinner me-2"></div>Cargando datos...</div>'
                },
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function() {
                    // Animación para las filas de la tabla
                    $('#top-medicamentos-table tbody tr').addClass('animate__animated animate__fadeInUp');
                }
            });

            // Variables para gráficas
            let chartFacturacion, chartPacientes, chartTopMedicamentos;

            // Función para mostrar loading
            function showLoading() {
                $('#loading-overlay').fadeIn(300);
            }

            // Función para ocultar loading
            function hideLoading() {
                $('#loading-overlay').fadeOut(300);
            }

            // Función para actualizar estadísticas
            function actualizarEstadisticas() {
                showLoading();

                let fechaInicio = $('#fecha_inicio').val();
                let fechaFin = $('#fecha_fin').val();
                let contrato = $('#contrato').val();

                $.ajax({
                    url: "{{ route('dashboard.estadisticas-ajax') }}",
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin,
                        contrato: contrato
                    },
                    success: function(data) {
                        // Actualizar cards con animaciones
                        $('#total-pacientes').html(data.total_pacientes.toLocaleString());
                        const valorFormateado = Number(data.valor_total_facturado).toLocaleString('es-CO', {
                            style: 'currency',
                            currency: 'COP',
                            maximumFractionDigits: 0,
                            minimumFractionDigits: 0
                        });

                        $('#valor-total').html(valorFormateado);

                        $('#total-medicamentos').html(data.top_medicamentos.length);

                        if (data.paciente_mayor_valor) {
                            // Asegurándonos que sea un número válido con validación
                            const valorFormateado = Number(data.paciente_mayor_valor.total_paciente).toLocaleString('es-CO', {
                                style: 'currency',
                                currency: 'COP',
                                maximumFractionDigits: 0,
                                minimumFractionDigits: 0
                            });

                            $('#paciente-mayor-info').html(
                                '<h2 class="stat-value">' + valorFormateado + '</h2>' +
                                '<p class="stat-label">' +
                                '<strong>' + data.paciente_mayor_valor.paciente + '</strong><br>' +
                                '<small class="text-muted">Historia: ' + data.paciente_mayor_valor.historia + '</small>' +
                                '</p>'
                            );
                        } else {
                            // También aplicamos formato al valor por defecto
                            const valorPorDefecto = Number(0).toLocaleString('es-CO', {
                                style: 'currency',
                                currency: 'COP',
                                maximumFractionDigits: 0,
                                minimumFractionDigits: 0
                            });

                            $('#paciente-mayor-info').html('<h2 class="stat-value">' + valorPorDefecto + '</h2><p class="stat-label">Sin datos disponibles</p>');
                        }

                        // Actualizar gráficas con mejor diseño
                        actualizarGraficaFacturacion(data.facturas_por_mes);
                        actualizarGraficaPacientes(data.pacientes_por_contrato);
                        actualizarGraficaTopMedicamentos(data.top_medicamentos);

                        // Recargar DataTable
                        table.ajax.reload(null, false);

                        hideLoading();
                    },
                    error: function() {
                        hideLoading();
                        // Mostrar mensaje de error elegante
                        console.error('Error al cargar las estadísticas');
                    }
                });
            }

            // Función para actualizar gráfica de facturación por mes
            function actualizarGraficaFacturacion(data) {
                let ctx = document.getElementById('chartFacturacion').getContext('2d');

                if (chartFacturacion) {
                    chartFacturacion.destroy();
                }

                let labels = data.map(item => {
                    let meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    return meses[item.mes - 1] + ' ' + item.año;
                });
                let valores = data.map(item => item.total_mes);

                // Crear gradiente
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0.1)');

                chartFacturacion = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Facturación Mensual',
                            data: valores,
                            borderColor: '#6366f1',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
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
                                display: false
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
                                        return 'Facturación: $' + context.parsed.y.toLocaleString('es-CO');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        weight: 500
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6'
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        weight: 500
                                    },
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-CO');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Función para actualizar gráfica de pacientes por contrato
            function actualizarGraficaPacientes(data) {
                let ctx = document.getElementById('chartPacientes').getContext('2d');

                if (chartPacientes) {
                    chartPacientes.destroy();
                }

                let labels = data.map(item => item.centroprod);
                let valores = data.map(item => item.total_pacientes);

                // Colores modernos para la gráfica de dona
                const colors = [
                    '#6366f1', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4',
                    '#84cc16', '#f97316', '#ec4899', '#14b8a6'
                ];

                chartPacientes = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: valores,
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
                                    font: {
                                        weight: 500
                                    }
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

            // Función para actualizar gráfica de top medicamentos
            function actualizarGraficaTopMedicamentos(data) {
                let ctx = document.getElementById('chartTopMedicamentos').getContext('2d');

                if (chartTopMedicamentos) {
                    chartTopMedicamentos.destroy();
                }

                let labels = data.slice(0, 5).map(item => {
                    let nombre = item.nombre_generico;
                    return nombre.length > 25 ? nombre.substring(0, 25) + '...' : nombre;
                });
                let valores = data.slice(0, 5).map(item => item.total_medicamento);

                // Crear gradientes para las barras
                const gradients = valores.map((_, index) => {
                    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                    const colors = [
                        ['#667eea', '#764ba2'],
                        ['#11998e', '#38ef7d'],
                        ['#f093fb', '#f5576c'],
                        ['#4facfe', '#00f2fe'],
                        ['#43e97b', '#38f9d7']
                    ];
                    gradient.addColorStop(0, colors[index % colors.length][0]);
                    gradient.addColorStop(1, colors[index % colors.length][1]);
                    return gradient;
                });

                chartTopMedicamentos = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor Facturado',
                            data: valores,
                            backgroundColor: gradients,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
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
                                        return 'Valor: $' + context.parsed.y.toLocaleString('es-CO');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        weight: 500
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6'
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        weight: 500
                                    },
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-CO');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Event listeners para filtros con debounce
            let timeoutId;
            $(document).on('change', '#fecha_inicio, #fecha_fin, #contrato', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(actualizarEstadisticas, 500);
            });

            // Animaciones adicionales para elementos
            function animateCounters() {
                $('.stat-value').each(function() {
                    const $this = $(this);
                    const countTo = parseInt($this.text().replace(/[^0-9]/g, ''));

                    if (countTo > 0) {
                        $({
                            countNum: 0
                        }).animate({
                            countNum: countTo
                        }, {
                            duration: 2000,
                            easing: 'swing',
                            step: function() {
                                $this.text(Math.floor(this.countNum).toLocaleString());
                            },
                            complete: function() {
                                $this.text(countTo.toLocaleString());
                            }
                        });
                    }
                });
            }

            // Efectos hover para cards
            $('.stat-card').hover(
                function() {
                    $(this).addClass('animate__pulse');
                },
                function() {
                    $(this).removeClass('animate__pulse');
                }
            );

            // Inicialización
            setTimeout(() => {
                animateCounters();
            }, 500);

            // Cargar datos iniciales
            actualizarEstadisticas();

            // Refresh automático cada 5 minutos (opcional)
            // setInterval(actualizarEstadisticas, 300000);
        });
    </script>

    <!-- Contenido Principal -->
    <div class="dashboard-container animate__animated animate__fadeIn">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                Dashboard Analytics
            </h1>
            <p class="dashboard-subtitle">Sistema Integral de Análisis de Farmacias</p>
        </div>

        <!-- Sección de Filtros -->
        <div class="filters-section animate__animated animate__fadeInUp">
            <h3 class="filters-title">
                <i class="fas fa-filter"></i>
                Filtros de Análisis
            </h3>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha_inicio" class="form-label fw-semibold">Fecha Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_fin" class="form-label fw-semibold">Fecha Fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="contrato" class="form-label fw-semibold">Contrato</label>
                    <select id="contrato" name="contrato" class="form-select">
                        <option value="all" {{ $contrato == 'all' ? 'selected' : '' }}>🏢 Todos los contratos</option>
                        @foreach($contratos as $contratoItem)
                        <option value="{{ $contratoItem }}" {{ $contrato == $contratoItem ? 'selected' : '' }}>{{ $contratoItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid de Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h2 class="stat-value" id="total-pacientes">{{ number_format($estadisticas['total_pacientes']) }}</h2>
                <p class="stat-label">Pacientes Atendidos</p>
            </div>

            <div class="stat-card success animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h2 class="stat-value" id="valor-total">${{ number_format($estadisticas['valor_total_facturado'], 0) }}</h2>
                <p class="stat-label">Valor Total Facturado</p>
            </div>

            <div class="stat-card warning animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div id="paciente-mayor-info">
                    @if($estadisticas['paciente_mayor_valor'])
                    <h2 class="stat-value">${{ number_format($estadisticas['paciente_mayor_valor']->total_paciente, 0) }}</h2>
                    <p class="stat-label">
                        <strong>{{ $estadisticas['paciente_mayor_valor']->paciente }}</strong><br>
                        <small class="text-muted">Historia: {{ $estadisticas['paciente_mayor_valor']->historia }}</small>
                    </p>
                    @else
                    <h2 class="stat-value">$0</h2>
                    <p class="stat-label">Sin datos disponibles</p>
                    @endif
                </div>
            </div>

            <div class="stat-card info animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="stat-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <h2 class="stat-value" id="total-medicamentos">{{ count($estadisticas['top_medicamentos']) }}</h2>
                <p class="stat-label">Medicamentos Diferentes</p>
            </div>
        </div>

        <!-- Grid de Gráficas -->
        <div class="charts-grid">
            <div class="chart-card animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        Facturación por Mes
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartFacturacion"></canvas>
                </div>
            </div>

            <div class="chart-card animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Distribución por Contrato
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartPacientes"></canvas>
                </div>
            </div>

            <div class="chart-card animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        Top 5 Medicamentos
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartTopMedicamentos"></canvas>
                </div>
            </div>
        </div>

        <!-- Sección de DataTable -->
        <div class="data-table-section animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
            <div class="table-card">
                <div class="table-header">
                    <h3>
                        <i class="fas fa-table"></i>
                        Análisis Detallado de Medicamentos
                    </h3>
                </div>
                <div class="table-container">
                    <table id="top-medicamentos-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-pills mr-2"></i>Medicamento</th>
                                <th><i class="fas fa-dollar-sign mr-2"></i>Valor Total</th>
                                <th><i class="fas fa-boxes mr-2"></i>Unidades</th>
                                <th><i class="fas fa-clipboard-check mr-2"></i>Dispensaciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>
    </div>

</body>

</html>