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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
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

        .analytics-menu {
            background: white;
            border-radius: 16px;
            margin: 2rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .menu-header {
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .menu-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .menu-header i {
            margin-right: 0.5rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 0;
        }

        .menu-item {
            padding: 2rem;
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: white;
        }

        .menu-item:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .menu-item.active {
            background: rgba(99, 102, 241, 0.1);
            border-left: 4px solid var(--primary-color);
        }

        .menu-item-icon {
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

        .menu-item.success .menu-item-icon {
            background: var(--gradient-success);
        }

        .menu-item.warning .menu-item-icon {
            background: var(--gradient-warning);
        }

        .menu-item.info .menu-item-icon {
            background: var(--gradient-info);
        }

        .menu-item-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .menu-item-description {
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .content-area {
            background: white;
            border-radius: 16px;
            margin: 2rem;
            box-shadow: var(--shadow-lg);
            min-height: 400px;
            display: none;
        }

        .content-area.active {
            display: block;
        }

        .content-header {
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px 16px 0 0;
        }

        .content-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .content-header i {
            margin-right: 0.5rem;
        }

        .content-body {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
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

        .stat-card.danger::before {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

        .stat-card.danger .stat-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: var(--shadow-lg);
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

        .loading-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            min-height: 200px;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table {
            margin-bottom: 0;
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

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
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

        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2rem;
            }

            .menu-grid {
                grid-template-columns: 1fr;
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

    <!-- Contenido Principal -->
    <div class="dashboard-container animate__animated animate__fadeIn">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                Dashboard Analytics Optimizado
            </h1>
            <p class="dashboard-subtitle">Sistema Modular de Análisis - Carga Solo lo que Necesitas</p>
        </div>

        <!-- Sección de Filtros -->
        <div class="filters-section animate__animated animate__fadeInUp">
            <h3 style="color: var(--dark-color); font-weight: 600; font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center;">
                <i class="fas fa-filter" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                Filtros Globales
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

        <!-- Menú de Análisis -->
        <div class="analytics-menu animate__animated animate__fadeInUp">
            <div class="menu-header">
                <h3>
                    <i class="fas fa-th-large"></i>
                    Selecciona el Análisis que Deseas Consultar
                </h3>
            </div>
            <div class="menu-grid">
                <div class="menu-item" data-section="resumen" data-type="primary">
                    <div class="menu-item-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="menu-item-title">Resumen General & Distribución</div>
                    <div class="menu-item-description">Estadísticas de dispensados, medicamentos más relevantes, distribución por contratos y tendencias mensuales</div>
                </div>

                <div class="menu-item success" data-section="pendientes" data-type="success">
                    <div class="menu-item-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="menu-item-title">Análisis de Pendientes</div>
                    <div class="menu-item-description">Estados de pendientes, valores por facturar y estadísticas detalladas</div>
                </div>

                <div class="menu-item info" data-section="tendencias-pendientes" data-type="info">
                    <div class="menu-item-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="menu-item-title">Tendencias de Pendientes</div>
                    <div class="menu-item-description">Evolución temporal de pendientes y análisis por estados</div>
                </div>

                <div class="menu-item" data-section="reportes-medicamentos" data-type="primary">
                    <div class="menu-item-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="menu-item-title">Reportes de Medicamentos</div>
                    <div class="menu-item-description">Análisis detallado de medicamentos, unidades y dispensaciones</div>
                </div>

                <div class="menu-item success" data-section="reportes-pacientes" data-type="success">
                    <div class="menu-item-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="menu-item-title">Reportes de Pacientes</div>
                    <div class="menu-item-description">Análisis por paciente, historias clínicas y valores facturados</div>
                </div>
            </div>
        </div>

        <!-- Áreas de Contenido -->

        <!-- Resumen General -->
        <div id="content-resumen" class="content-area">
            <div class="content-header">
                <h3>
                    <i class="fas fa-tachometer-alt"></i>
                    Resumen General - Estadísticas de Dispensados
                </h3>
            </div>
            <div class="content-body">
                <!-- Estadísticas principales -->
                <div class="stats-grid" id="resumen-stats">
                    <!-- Se cargarán dinámicamente -->
                </div>

                <!-- Primera fila de gráficas: Top 5 Medicamentos y Valor por Contrato -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-bar"></i>
                                Top 5 Medicamentos Dispensados
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartTopMedicamentos"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-file-contract"></i>
                                Valor Total Facturado por Contrato
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartValorPorContrato"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Separador visual -->
                <div style="margin: 30px 0; border-top: 2px solid #e9ecef;"></div>

                <!-- Subtítulo para sección de distribución -->
                <div class="content-header" style="margin-bottom: 20px;">
                    <h3>
                        <i class="fas fa-chart-pie"></i>
                        Análisis de Distribución y Tendencias
                    </h3>
                </div>

                <!-- Segunda fila de gráficas: Facturación Mensual y Distribución por Contrato -->
                <div class="charts-grid">
                    <div class="chart-card">
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

                    <div class="chart-card">
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
                </div>

                <!-- Separador visual -->
                <div style="margin: 30px 0; border-top: 2px solid #e9ecef;"></div>

                <!-- Subtítulo para sección de facturación diaria -->
                <div class="content-header" style="margin-bottom: 20px;">
                    <h3>
                        <i class="fas fa-calendar-day"></i>
                        Análisis de Facturación Diaria
                    </h3>
                </div>

                <!-- Grid principal: 2 gráficas y tarjetas estadísticas -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 2rem;">
                    <!-- Gráfica de facturación diaria -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-area"></i>
                                Facturación Diaria
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartFacturacionDiaria"></canvas>
                        </div>
                    </div>

                    <!-- Gráfica de pacientes únicos por día -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-user-check"></i>
                                Pacientes Únicos por Día
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartPacientesDiarios"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas estadísticas debajo de las gráficas -->
                <div id="estadisticas-diarias" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 2rem;">
                    <!-- Se cargarán dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Análisis de Pendientes -->
        <div id="content-pendientes" class="content-area">
            <div class="content-header">
                <h3>
                    <i class="fas fa-clock"></i>
                    Análisis de Pendientes - Medcol6
                </h3>
            </div>
            <div class="content-body">
                <div class="stats-grid" id="pendientes-stats">
                    <!-- Se cargarán dinámicamente -->
                </div>
                <div class="stats-grid" id="pendientes-detalle">
                    <!-- Se cargarán dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Tendencias de Pendientes -->
        <div id="content-tendencias-pendientes" class="content-area">
            <div class="content-header">
                <h3>
                    <i class="fas fa-chart-line"></i>
                    Tendencias de Pendientes por Estado
                </h3>
            </div>
            <div class="content-body">
                <div class="charts-grid">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-pie"></i>
                                Distribución por Estado
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartEstadosPendientes"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-dollar-sign"></i>
                                Valor por Estado
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartValoresPendientes"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-line"></i>
                                Tendencias Mensuales
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartTendenciasPendientes"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-bar"></i>
                                Top Medicamentos Pendientes
                            </h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartTopMedicamentosPendientes"></canvas>
                        </div>
                    </div>
                </div>

                <!-- DataTable de Medicamentos Pendientes -->
                <div class="content-header mt-4">
                    <h3>
                        <i class="fas fa-table"></i>
                        Detalle de Medicamentos Pendientes
                    </h3>
                </div>
                <div class="table-responsive">
                    <table id="tabla-medicamentos-pendientes" class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-pills me-2"></i>Medicamento</th>
                                <th><i class="fas fa-barcode me-2"></i>Código</th>
                                <th><i class="fas fa-boxes me-2"></i>Cantidad Pendiente</th>
                                <th><i class="fas fa-hashtag me-2"></i>Total Pendientes</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Valor Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se cargará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reportes de Medicamentos -->
        <div id="content-reportes-medicamentos" class="content-area">
            <div class="content-header">
                <h3>
                    <i class="fas fa-pills"></i>
                    Reporte Detallado de Medicamentos
                </h3>
            </div>
            <div class="content-body">
                <div class="table-responsive">
                    <table id="tabla-medicamentos" class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-pills me-2"></i>Medicamento</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Valor Total</th>
                                <th><i class="fas fa-boxes me-2"></i>Unidades</th>
                                <th><i class="fas fa-clipboard-check me-2"></i>Dispensaciones</th>
                                <th><i class="fas fa-users me-2"></i>Pacientes Únicos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se cargará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reportes de Pacientes -->
        <div id="content-reportes-pacientes" class="content-area">
            <div class="content-header">
                <h3>
                    <i class="fas fa-users"></i>
                    Reporte Detallado de Pacientes
                </h3>
            </div>
            <div class="content-body">
                <div class="table-responsive">
                    <table id="tabla-pacientes" class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Paciente</th>
                                <th><i class="fas fa-id-card me-2"></i>Historia</th>
                                <th><i class="fas fa-building me-2"></i>Contrato</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Valor Total</th>
                                <th><i class="fas fa-clipboard-check me-2"></i>Dispensaciones</th>
                                <th><i class="fas fa-pills me-2"></i>Medicamentos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se cargará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configuración global de Chart.js
            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.color = '#6b7280';
            Chart.defaults.borderColor = '#e5e7eb';
            Chart.defaults.backgroundColor = 'rgba(99, 102, 241, 0.1)';

            // Variables para gráficas
            let charts = {};

            // Función para mostrar loading
            function showLoading(containerId) {
                const container = $('#' + containerId);
                container.html(`
                    <div class="loading-placeholder">
                        <div class="loading-spinner"></div>
                        <p class="mt-3 text-muted">Cargando datos...</p>
                    </div>
                `);
            }

            // Función para mostrar mensaje de error
            function showError(containerId, message) {
                const container = $('#' + containerId);
                container.html(`
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${message}
                    </div>
                `);
            }

            // Función para obtener parámetros de filtros
            function getFilters() {
                return {
                    fecha_inicio: $('#fecha_inicio').val(),
                    fecha_fin: $('#fecha_fin').val(),
                    contrato: $('#contrato').val()
                };
            }

            // Manejador de clics en el menú
            $('.menu-item').click(function() {
                const section = $(this).data('section');

                // Actualizar menú activo
                $('.menu-item').removeClass('active');
                $(this).addClass('active');

                // Ocultar todas las áreas de contenido
                $('.content-area').removeClass('active');

                // Mostrar área seleccionada
                $('#content-' + section).addClass('active').addClass('fade-in');

                // Cargar datos según la sección
                loadSectionData(section);
            });

            // Función principal para cargar datos de sección
            function loadSectionData(section) {
                const filters = getFilters();

                switch(section) {
                    case 'resumen':
                        // Cargar resumen general y distribución juntos
                        loadResumenGeneral(filters);
                        loadAnalisisDistribucion(filters);
                        break;
                    case 'pendientes':
                        loadResumenPendientes(filters);
                        break;
                    case 'tendencias-pendientes':
                        loadTendenciasPendientes(filters);
                        break;
                    case 'reportes-medicamentos':
                        loadReportesMedicamentos(filters);
                        break;
                    case 'reportes-pacientes':
                        loadReportesPacientes(filters);
                        break;
                }
            }

            // Cargar resumen general
            function loadResumenGeneral(filters) {
                showLoading('resumen-stats');

                $.ajax({
                    url: "{{ route('dashboard.resumen-general') }}",
                    method: 'GET',
                    data: filters,
                    success: function(data) {
                        renderResumenStats(data);
                        loadTopMedicamentos(filters);
                        loadValorPorContrato(filters);
                    },
                    error: function() {
                        showError('resumen-stats', 'Error al cargar el resumen general');
                    }
                });
            }

            function renderResumenStats(data) {
                const container = $('#resumen-stats');
                const pacienteMayorValor = data.paciente_mayor_valor || {};

                container.html(`
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h2 class="stat-value">${data.total_pacientes.toLocaleString()}</h2>
                        <p class="stat-label">Pacientes Atendidos</p>
                    </div>

                    <div class="stat-card success">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h2 class="stat-value">$${data.valor_total_facturado.toLocaleString()}</h2>
                        <p class="stat-label">Valor Total Facturado</p>
                    </div>

                    <div class="stat-card info">
                        <div class="stat-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h2 class="stat-value">${data.total_medicamentos.toLocaleString()}</h2>
                        <p class="stat-label">Medicamentos Diferentes</p>
                    </div>

                    <div class="stat-card warning">
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h2 class="stat-value">$${pacienteMayorValor.total_paciente ? pacienteMayorValor.total_paciente.toLocaleString() : '0'}</h2>
                        <p class="stat-label">
                            <strong>${pacienteMayorValor.paciente || 'Sin datos'}</strong><br>
                            <small class="text-muted">Historia: ${pacienteMayorValor.historia || 'N/A'}</small>
                        </p>
                    </div>
                `);
            }

            function renderMesesFacturacion(mesMayor, mesMenor) {
                const container = $('#resumen-stats');

                // Validar que hay datos
                if (!mesMayor || !mesMenor) {
                    console.warn('No hay datos de meses mayor/menor facturación');
                    return;
                }

                // Nombres de meses en español
                const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

                const nombreMesMayor = `${meses[mesMayor.mes - 1]} ${mesMayor.año}`;
                const valorMesMayor = parseFloat(mesMayor.total_mes) || 0;

                const nombreMesMenor = `${meses[mesMenor.mes - 1]} ${mesMenor.año}`;
                const valorMesMenor = parseFloat(mesMenor.total_mes) || 0;

                // Crear las nuevas cards
                const cardMesMayor = `
                    <div class="stat-card success animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                        <div class="stat-icon">
                            <i class="fas fa-flag" style="color: #10b981;"></i>
                        </div>
                        <h2 class="stat-value">$${valorMesMayor.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h2>
                        <p class="stat-label">
                            <strong>Mes con Mayor Facturación</strong><br>
                            <small class="text-muted">${nombreMesMayor}</small>
                        </p>
                        <div style="position: absolute; top: 15px; right: 15px; font-size: 24px;">
                            <i class="fas fa-arrow-up" style="color: #10b981;"></i>
                        </div>
                    </div>
                `;

                const cardMesMenor = `
                    <div class="stat-card danger animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                        <div class="stat-icon">
                            <i class="fas fa-flag" style="color: #ef4444;"></i>
                        </div>
                        <h2 class="stat-value">$${valorMesMenor.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h2>
                        <p class="stat-label">
                            <strong>Mes con Menor Facturación</strong><br>
                            <small class="text-muted">${nombreMesMenor}</small>
                        </p>
                        <div style="position: absolute; top: 15px; right: 15px; font-size: 24px;">
                            <i class="fas fa-arrow-down" style="color: #ef4444;"></i>
                        </div>
                    </div>
                `;

                // Agregar las cards al contenedor
                container.append(cardMesMayor);
                container.append(cardMesMenor);
            }

            function loadTopMedicamentos(filters) {
                $.ajax({
                    url: "{{ route('dashboard.top-medicamentos') }}",
                    method: 'GET',
                    data: {...filters, limit: 5},
                    success: function(data) {
                        updateChartTopMedicamentos(data);
                    }
                });
            }

            function loadValorPorContrato(filters) {
                $.ajax({
                    url: "{{ route('dashboard.valor-por-contrato') }}",
                    method: 'GET',
                    data: filters,
                    success: function(data) {
                        updateChartValorPorContrato(data);
                    },
                    error: function() {
                        console.error('Error al cargar valor por contrato');
                    }
                });
            }

            // Cargar resumen de pendientes
            function loadResumenPendientes(filters) {
                showLoading('pendientes-stats');

                $.ajax({
                    url: "{{ route('dashboard.resumen-pendientes') }}",
                    method: 'GET',
                    data: filters,
                    success: function(data) {
                        renderPendientesStats(data);
                        renderPendientesDetalle(data.estadisticas_por_estado);
                    },
                    error: function() {
                        showError('pendientes-stats', 'Error al cargar las estadísticas de pendientes');
                    }
                });
            }

            function renderPendientesStats(data) {
                const container = $('#pendientes-stats');

                container.html(`
                    <div class="stat-card warning">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h2 class="stat-value">$${data.valor_total_pendiente.toLocaleString()}</h2>
                        <p class="stat-label">Valor Pendiente por Facturar</p>
                    </div>

                    <div class="stat-card success">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2 class="stat-value">$${data.valor_total_entregado.toLocaleString()}</h2>
                        <p class="stat-label">Valor Total Entregado</p>
                    </div>
                `);
            }

            function renderPendientesDetalle(estadisticas) {
                const container = $('#pendientes-detalle');
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

                    const card = $(`
                        <div class="stat-card ${config.class} animate__animated animate__fadeInUp" style="animation-delay: ${index * 0.1}s;">
                            <div class="stat-icon">
                                <i class="${config.icon}"></i>
                            </div>
                            <h2 class="stat-value">${stat.total_pendientes.toLocaleString()}</h2>
                            <p class="stat-label">${config.label}</p>
                            <div class="mt-2">
                                <small class="text-muted">Valor Total: <strong>$${stat.valor_total.toLocaleString()}</strong></small>
                            </div>
                        </div>
                    `);

                    container.append(card);
                });
            }

            // Cargar análisis de distribución
            function loadAnalisisDistribucion(filters) {
                // Buscar contenedores de las gráficas de distribución (son el 3º y 4º dentro de #content-resumen)
                const containerFacturacion = $('#content-resumen').find('.chart-container').eq(2);
                const containerPacientes = $('#content-resumen').find('.chart-container').eq(3);

                // Mostrar loading en ambas gráficas
                containerFacturacion.html(`
                    <div class="loading-placeholder">
                        <div class="loading-spinner"></div>
                        <p class="mt-3 text-muted">Cargando facturación mensual...</p>
                    </div>
                `);

                containerPacientes.html(`
                    <div class="loading-placeholder">
                        <div class="loading-spinner"></div>
                        <p class="mt-3 text-muted">Cargando distribución por contrato...</p>
                    </div>
                `);

                $.ajax({
                    url: "{{ route('dashboard.analisis-distribucion') }}",
                    method: 'GET',
                    data: filters,
                    success: function(data) {
                        console.log('Datos de análisis distribución recibidos:', data);

                        // Validar datos recibidos
                        if (!data) {
                            console.error('No se recibieron datos');
                            return;
                        }

                        // Buscar los contenedores de las gráficas de distribución (son el 3º y 4º dentro de #content-resumen)
                        const containerFacturacion = $('#content-resumen').find('.chart-container').eq(2);
                        const containerPacientes = $('#content-resumen').find('.chart-container').eq(3);

                        // Verificar que los contenedores existen
                        if (containerFacturacion.length === 0 || containerPacientes.length === 0) {
                            console.error('No se encontraron los contenedores de las gráficas');
                            return;
                        }

                        // Restaurar canvas en los contenedores
                        containerFacturacion.html('<canvas id="chartFacturacion" style="width: 100%; height: 100%;"></canvas>');
                        containerPacientes.html('<canvas id="chartPacientes" style="width: 100%; height: 100%;"></canvas>');

                        console.log('Canvas creados, esperando actualización del DOM...');

                        // Usar requestAnimationFrame para asegurar que el DOM se actualizó
                        requestAnimationFrame(function() {
                            // Verificar que los canvas fueron creados
                            const canvasFacturacion = document.getElementById('chartFacturacion');
                            const canvasPacientes = document.getElementById('chartPacientes');

                            if (!canvasFacturacion || !canvasPacientes) {
                                console.error('Los canvas no fueron creados correctamente', {
                                    canvasFacturacion: canvasFacturacion,
                                    canvasPacientes: canvasPacientes
                                });
                                return;
                            }

                            console.log('Canvas verificados, procediendo a renderizar gráficas...');

                            // Renderizar gráficas con manejo de errores
                            try {
                                updateChartFacturacion(data.facturas_por_mes);
                                console.log('Gráfica de facturación renderizada exitosamente');
                            } catch (error) {
                                console.error('Error al renderizar gráfica de facturación:', error);
                            }

                            try {
                                updateChartPacientes(data.pacientes_por_contrato);
                                console.log('Gráfica de pacientes renderizada exitosamente');
                            } catch (error) {
                                console.error('Error al renderizar gráfica de pacientes:', error);
                            }

                            // Renderizar cards de mes mayor y menor facturación
                            try {
                                renderMesesFacturacion(data.mes_mayor_facturacion, data.mes_menor_facturacion);
                                console.log('Cards de meses mayor/menor facturación renderizadas exitosamente');
                            } catch (error) {
                                console.error('Error al renderizar cards de facturación:', error);
                            }

                            // Renderizar gráfica de facturación diaria
                            try {
                                updateChartFacturacionDiaria(data.facturas_por_dia);
                                console.log('Gráfica de facturación diaria renderizada exitosamente');
                            } catch (error) {
                                console.error('Error al renderizar gráfica diaria:', error);
                            }

                            // Renderizar gráfica de pacientes únicos diarios
                            try {
                                updateChartPacientesDiarios(data.facturas_por_dia);
                                console.log('Gráfica de pacientes únicos diarios renderizada exitosamente');
                            } catch (error) {
                                console.error('Error al renderizar gráfica de pacientes diarios:', error);
                            }

                            // Renderizar cards de estadísticas diarias
                            try {
                                renderEstadisticasDiarias(data.dia_mayor_facturacion, data.dia_menor_facturacion, data.dia_mayor_pacientes);
                                console.log('Cards de estadísticas diarias renderizadas exitosamente');
                            } catch (error) {
                                console.error('Error al renderizar estadísticas diarias:', error);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar análisis de distribución:', {status, error, response: xhr.responseText});

                        const errorHtml = `
                            <div class="alert alert-danger text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error al cargar los datos. Por favor, intente nuevamente.
                            </div>
                        `;

                        // Usar los mismos selectores que en success
                        $('#content-resumen').find('.chart-container').eq(2).html(errorHtml);
                        $('#content-resumen').find('.chart-container').eq(3).html(errorHtml);
                    }
                });
            }

            // Cargar tendencias de pendientes
            function loadTendenciasPendientes(filters) {
                const contenedorPrincipal = $('#content-tendencias-pendientes');

                // Mostrar loading en todos los gráficos
                contenedorPrincipal.find('.chart-container').each(function(index) {
                    const chartIds = ['chartEstadosPendientes', 'chartValoresPendientes', 'chartTendenciasPendientes', 'chartTopMedicamentosPendientes'];
                    $(this).attr('data-chart-id', chartIds[index]).html(`
                        <div class="loading-placeholder">
                            <div class="loading-spinner"></div>
                            <p class="mt-3 text-muted">Cargando datos de tendencias...</p>
                            <small class="text-muted">Esto puede tardar unos segundos</small>
                        </div>
                    `);
                });

                $.ajax({
                    url: "{{ route('dashboard.tendencias-pendientes') }}",
                    method: 'GET',
                    data: filters,
                    timeout: 60000, // 60 segundos de timeout
                    success: function(data) {
                        console.log('Datos de tendencias recibidos:', data);

                        // Restaurar los canvas usando data-chart-id
                        contenedorPrincipal.find('.chart-container').each(function() {
                            const chartId = $(this).attr('data-chart-id');
                            if (chartId) {
                                $(this).html('<canvas id="' + chartId + '"></canvas>');
                                console.log('Canvas restaurado:', chartId);
                            }
                        });

                        // Esperar un tick para que el DOM se actualice
                        setTimeout(function() {
                            console.log('Iniciando renderizado de gráficos...');
                            console.log('- Estadísticas por estado:', data.estadisticas_por_estado);
                            console.log('- Tendencias por mes:', data.tendencias_por_mes);
                            console.log('- Top 10 medicamentos:', data.top_medicamentos_pendientes);
                            console.log('- Todos los medicamentos:', data.todos_medicamentos_pendientes ? data.todos_medicamentos_pendientes.length + ' registros' : 'No disponible');

                            updateChartEstadosPendientes(data.estadisticas_por_estado);
                            updateChartValoresPendientes(data.estadisticas_por_estado);
                            updateChartTendenciasPendientes(data.tendencias_por_mes);
                            updateChartTopMedicamentosPendientes(data.top_medicamentos_pendientes);

                            // Cargar DataTable con TODOS los medicamentos pendientes
                            loadTablaMedicamentosPendientes(data.todos_medicamentos_pendientes || data.top_medicamentos_pendientes);

                            console.log('Renderizado de gráficos y tabla completado');
                        }, 50);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar tendencias:', {status, error, response: xhr.responseText});

                        let errorMessage = 'Error al cargar los datos de tendencias.';
                        if (status === 'timeout') {
                            errorMessage = 'La consulta está tardando demasiado. Intente con un rango de fechas menor.';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Error del servidor. Contacte al administrador.';
                        }

                        contenedorPrincipal.find('.chart-container').each(function() {
                            $(this).html(`
                                <div class="alert alert-danger text-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    ${errorMessage}
                                </div>
                            `);
                        });
                    }
                });
            }

            // Cargar reportes de medicamentos
            function loadReportesMedicamentos(filters) {
                if ($.fn.DataTable.isDataTable('#tabla-medicamentos')) {
                    $('#tabla-medicamentos').DataTable().destroy();
                }

                $('#tabla-medicamentos').DataTable({
                    processing: true,
                    serverSide: false,
                    responsive: true,
                    ajax: {
                        url: "{{ route('dashboard.reportes-detallados') }}",
                        data: {...filters, tipo: 'medicamentos'},
                        dataSrc: ''
                    },
                    columns: [
                        { data: 'nombre_generico', name: 'nombre_generico' },
                        {
                            data: 'total_medicamento',
                            name: 'total_medicamento',
                            type: 'num',
                            render: function(data, type) {
                                if (type === 'display' || type === 'filter') {
                                    return '$' + parseFloat(data).toLocaleString();
                                }
                                return parseFloat(data);
                            }
                        },
                        {
                            data: 'total_unidades',
                            name: 'total_unidades',
                            type: 'num',
                            render: function(data, type) {
                                if (type === 'display' || type === 'filter') {
                                    return parseFloat(data).toLocaleString();
                                }
                                return parseFloat(data);
                            }
                        },
                        { data: 'total_dispensaciones', name: 'total_dispensaciones', type: 'num' },
                        { data: 'pacientes_unicos', name: 'pacientes_unicos', type: 'num' }
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                    },
                    order: [[1, 'desc']],
                    pageLength: 25
                });
            }

            // Cargar reportes de pacientes
            function loadReportesPacientes(filters) {
                if ($.fn.DataTable.isDataTable('#tabla-pacientes')) {
                    $('#tabla-pacientes').DataTable().destroy();
                }

                $('#tabla-pacientes').DataTable({
                    processing: true,
                    serverSide: false,
                    responsive: true,
                    ajax: {
                        url: "{{ route('dashboard.reportes-detallados') }}",
                        data: {...filters, tipo: 'pacientes'},
                        dataSrc: ''
                    },
                    columns: [
                        { data: 'paciente', name: 'paciente' },
                        { data: 'historia', name: 'historia' },
                        { data: 'centroprod', name: 'centroprod' },
                        {
                            data: 'total_paciente',
                            name: 'total_paciente',
                            type: 'num',
                            render: function(data, type) {
                                if (type === 'display' || type === 'filter') {
                                    return '$' + parseFloat(data).toLocaleString();
                                }
                                return parseFloat(data);
                            }
                        },
                        { data: 'total_dispensaciones', name: 'total_dispensaciones', type: 'num' },
                        { data: 'medicamentos_diferentes', name: 'medicamentos_diferentes', type: 'num' }
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                    },
                    order: [[3, 'desc']],
                    pageLength: 25
                });
            }

            // Funciones de actualización de gráficas
            function updateChartTopMedicamentos(data) {
                const ctx = document.getElementById('chartTopMedicamentos');
                if (!ctx) return;

                if (charts.topMedicamentos) {
                    charts.topMedicamentos.destroy();
                }

                const labels = data.slice(0, 5).map(item => {
                    const nombre = item.nombre_generico;
                    return nombre.length > 25 ? nombre.substring(0, 25) + '...' : nombre;
                });
                const valores = data.slice(0, 5).map(item => item.total_medicamento);

                // Colores diferentes para cada barra (misma paleta que Valor por Contrato)
                const colors = [
                    'rgba(99, 102, 241, 0.8)',   // Púrpura
                    'rgba(16, 185, 129, 0.8)',   // Verde
                    'rgba(245, 158, 11, 0.8)',   // Amarillo
                    'rgba(239, 68, 68, 0.8)',    // Rojo
                    'rgba(6, 182, 212, 0.8)'     // Cyan
                ];

                const borderColors = [
                    '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'
                ];

                charts.topMedicamentos = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor Facturado',
                            data: valores,
                            backgroundColor: colors.slice(0, labels.length),
                            borderColor: borderColors.slice(0, labels.length),
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
                                callbacks: {
                                    label: function(context) {
                                        return 'Valor: $' + context.parsed.y.toLocaleString('es-ES', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-ES', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        }
                    }
                });
            }

            function updateChartValorPorContrato(data) {
                const ctx = document.getElementById('chartValorPorContrato');
                if (!ctx) return;

                if (charts.valorPorContrato) {
                    charts.valorPorContrato.destroy();
                }

                // Validar que hay datos
                if (!data || data.length === 0) {
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay datos de facturación por contrato para el período seleccionado
                        </div>
                    `);
                    return;
                }

                const labels = data.map(item => item.centroprod);
                const valores = data.map(item => parseFloat(item.total_facturado) || 0);

                // Colores diferentes para cada barra
                const colors = [
                    'rgba(99, 102, 241, 0.8)',   // Púrpura
                    'rgba(16, 185, 129, 0.8)',   // Verde
                    'rgba(245, 158, 11, 0.8)',   // Amarillo
                    'rgba(239, 68, 68, 0.8)',    // Rojo
                    'rgba(6, 182, 212, 0.8)',    // Cyan
                    'rgba(139, 92, 246, 0.8)',   // Violeta
                    'rgba(249, 115, 22, 0.8)',   // Naranja
                    'rgba(236, 72, 153, 0.8)',   // Rosa
                    'rgba(20, 184, 166, 0.8)',   // Teal
                    'rgba(132, 204, 22, 0.8)'    // Lima
                ];

                const borderColors = [
                    '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4',
                    '#8b5cf6', '#f97316', '#ec4899', '#14b8a6', '#84cc16'
                ];

                charts.valorPorContrato = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor Total Facturado',
                            data: valores,
                            backgroundColor: colors.slice(0, labels.length),
                            borderColor: borderColors.slice(0, labels.length),
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
                                callbacks: {
                                    label: function(context) {
                                        return 'Valor: $' + context.parsed.y.toLocaleString('es-ES', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-ES', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        }
                    }
                });
            }

            function updateChartFacturacion(data) {
                const ctx = document.getElementById('chartFacturacion');
                if (!ctx) {
                    console.warn('Canvas chartFacturacion no encontrado');
                    return;
                }

                if (charts.facturacion) {
                    charts.facturacion.destroy();
                }

                // Validar que hay datos
                if (!data || data.length === 0) {
                    console.warn('No hay datos de facturación mensual');
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay datos de facturación mensual para el período seleccionado
                        </div>
                    `);
                    return;
                }

                console.log('Datos de facturación mensual:', data);

                const labels = data.map(item => {
                    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    return meses[item.mes - 1] + ' ' + item.año;
                });
                const valores = data.map(item => parseFloat(item.total_mes) || 0);

                charts.facturacion = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Facturación Mensual',
                            data: valores,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
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
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Facturación: $' + context.parsed.y.toLocaleString('es-ES', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-ES', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });
                                    }
                                }
                            }
                        }
                    }
                });

                console.log('Gráfica de facturación mensual renderizada correctamente');
            }

            function updateChartPacientes(data) {
                const ctx = document.getElementById('chartPacientes');
                if (!ctx) {
                    console.warn('Canvas chartPacientes no encontrado');
                    return;
                }

                if (charts.pacientes) {
                    charts.pacientes.destroy();
                }

                // Validar que hay datos
                if (!data || data.length === 0) {
                    console.warn('No hay datos de pacientes por contrato');
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay datos de distribución por contrato para el período seleccionado
                        </div>
                    `);
                    return;
                }

                console.log('Datos de pacientes por contrato:', data);

                const labels = data.map(item => item.centroprod);
                const valores = data.map(item => parseInt(item.total_pacientes) || 0);
                const colors = [
                    '#6366f1', '#8b5cf6', '#10b981', '#f59e0b',
                    '#ef4444', '#06b6d4', '#f97316', '#ec4899',
                    '#14b8a6', '#84cc16'
                ];

                // Calcular total para porcentajes
                const total = valores.reduce((a, b) => a + b, 0);

                charts.pacientes = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: valores,
                            backgroundColor: colors.slice(0, labels.length),
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 10
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
                                    padding: 15,
                                    font: {
                                        size: 12
                                    },
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map((label, i) => {
                                                const value = data.datasets[0].data[i];
                                                const percentage = ((value / total) * 100).toFixed(1);
                                                return {
                                                    text: `${label}: ${value.toLocaleString()} (${percentage}%)`,
                                                    fillStyle: data.datasets[0].backgroundColor[i],
                                                    hidden: false,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: ${value.toLocaleString()} pacientes (${percentage}%)`;
                                    }
                                }
                            },
                            // Plugin personalizado para mostrar porcentajes en las secciones
                            datalabels: {
                                display: true,
                                color: '#fff',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                },
                                formatter: function(value, context) {
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return percentage + '%';
                                }
                            }
                        }
                    },
                    
                });

                console.log('Gráfica de distribución por contrato renderizada correctamente');
            }

            function updateChartFacturacionDiaria(data) {
                const ctx = document.getElementById('chartFacturacionDiaria');
                if (!ctx) {
                    console.warn('Canvas chartFacturacionDiaria no encontrado');
                    return;
                }

                if (charts.facturacionDiaria) {
                    charts.facturacionDiaria.destroy();
                }

                // Validar que hay datos
                if (!data || data.length === 0) {
                    console.warn('No hay datos de facturación diaria');
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay datos de facturación diaria para el período seleccionado
                        </div>
                    `);
                    return;
                }

                console.log('Datos de facturación diaria (raw):', data);
                console.log('Total de días en el período:', data.length);

                // Verificar si hay datos duplicados o valores incorrectos
                const fechasDuplicadas = data.filter((item, index, self) =>
                    index !== self.findIndex(t => t.fecha === item.fecha)
                );
                if (fechasDuplicadas.length > 0) {
                    console.error('⚠️ Se encontraron fechas duplicadas:', fechasDuplicadas);
                }

                const labels = data.map(item => {
                    // Agregar T00:00:00 para evitar problemas de zona horaria
                    const fecha = new Date(item.fecha + 'T00:00:00');
                    const label = fecha.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: 'short',
                        year: '2-digit'  // Agregar año para evitar confusión
                    });
                    return label;
                });

                const valores = data.map(item => {
                    const valor = parseFloat(item.total_dia);
                    if (isNaN(valor) || valor < 0) {
                        console.warn('⚠️ Valor inválido encontrado:', item);
                        return 0;
                    }
                    return valor;
                });

                // Log detallado de los datos procesados
                console.log('Datos procesados para gráfica:', {
                    primer_dia: { fecha: data[0]?.fecha, valor: valores[0], label: labels[0] },
                    ultimo_dia: { fecha: data[data.length-1]?.fecha, valor: valores[data.length-1], label: labels[data.length-1] },
                    total_dias: labels.length,
                    valores_min: Math.min(...valores),
                    valores_max: Math.max(...valores),
                    suma_total: valores.reduce((a, b) => a + b, 0)
                });

                charts.facturacionDiaria = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Facturación Diaria',
                            data: valores,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
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
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        const index = context[0].dataIndex;
                                        const item = data[index];
                                        const fecha = new Date(item.fecha + 'T00:00:00');
                                        const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                        return diasSemana[fecha.getDay()] + ', ' + fecha.toLocaleDateString('es-ES', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric'
                                        });
                                    },
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const item = data[index];
                                        return [
                                            'Facturación: $' + context.parsed.y.toLocaleString('es-ES', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }),
                                            'Pacientes: ' + (item.pacientes_dia || 0).toLocaleString(),
                                            'Registros: ' + (item.total_registros || 0).toLocaleString()
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('es-ES', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    autoSkip: true,
                                    maxTicksLimit: 15  // Limitar número de etiquetas para mejor legibilidad
                                }
                            }
                        }
                    }
                });

                console.log('Gráfica de facturación diaria renderizada correctamente');
            }

            function updateChartPacientesDiarios(data) {
                const ctx = document.getElementById('chartPacientesDiarios');
                if (!ctx) {
                    console.warn('Canvas chartPacientesDiarios no encontrado');
                    return;
                }

                if (charts.pacientesDiarios) {
                    charts.pacientesDiarios.destroy();
                }

                // Validar que hay datos
                if (!data || data.length === 0) {
                    console.warn('No hay datos de pacientes diarios');
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay datos de pacientes para el período seleccionado
                        </div>
                    `);
                    return;
                }

                console.log('Datos de pacientes diarios:', data);

                const labels = data.map(item => {
                    const fecha = new Date(item.fecha + 'T00:00:00');
                    return fecha.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: 'short',
                        year: '2-digit'
                    });
                });

                // pacientes_dia ya contiene COUNT(DISTINCT historia) desde el backend
                const valores = data.map(item => {
                    const pacientes = parseInt(item.pacientes_dia) || 0;
                    if (pacientes < 0) {
                        console.warn('⚠️ Valor de pacientes inválido:', item);
                        return 0;
                    }
                    return pacientes;
                });

                // Log estadísticas
                console.log('Estadísticas de pacientes:', {
                    total_dias: valores.length,
                    pacientes_min: Math.min(...valores),
                    pacientes_max: Math.max(...valores),
                    promedio_diario: (valores.reduce((a, b) => a + b, 0) / valores.length).toFixed(1)
                });

                charts.pacientesDiarios = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pacientes Únicos',
                            data: valores,
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        const index = context[0].dataIndex;
                                        const item = data[index];
                                        const fecha = new Date(item.fecha + 'T00:00:00');
                                        const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                        return diasSemana[fecha.getDay()] + ', ' + fecha.toLocaleDateString('es-ES', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric'
                                        });
                                    },
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const item = data[index];
                                        const promedio = valores.reduce((a, b) => a + b, 0) / valores.length;
                                        const diferenciaProm = context.parsed.y - promedio;
                                        const signo = diferenciaProm >= 0 ? '+' : '';

                                        return [
                                            'Pacientes únicos: ' + context.parsed.y.toLocaleString(),
                                            'Registros totales: ' + (item.total_registros || 0).toLocaleString(),
                                            'Promedio: ' + promedio.toFixed(1),
                                            'Diferencia: ' + signo + diferenciaProm.toFixed(1) + ' (' + (diferenciaProm >= 0 ? '↑' : '↓') + ')'
                                        ];
                                    },
                                    labelColor: function(context) {
                                        return {
                                            borderColor: '#6366f1',
                                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                                            borderWidth: 2,
                                            borderRadius: 2
                                        };
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString() + ' pac.';
                                    },
                                    stepSize: function(context) {
                                        const max = Math.max(...valores);
                                        return Math.ceil(max / 10);
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Número de Pacientes',
                                    font: { size: 12, weight: 'bold' }
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    autoSkip: true,
                                    maxTicksLimit: 15
                                },
                                title: {
                                    display: true,
                                    text: 'Fecha',
                                    font: { size: 12, weight: 'bold' }
                                }
                            }
                        }
                    }
                });

                console.log('Gráfica de pacientes únicos diarios renderizada correctamente');
            }

            function renderEstadisticasDiarias(diaMayor, diaMenor, diaMayorPacientes) {
                const container = $('#estadisticas-diarias');

                // Validar que hay datos
                if (!diaMayor || !diaMenor || !diaMayorPacientes) {
                    console.warn('No hay datos de estadísticas diarias');
                    return;
                }

                const formatFecha = (fecha) => {
                    const date = new Date(fecha + 'T00:00:00');  // Agregar hora para evitar problemas de zona horaria
                    return date.toLocaleDateString('es-ES', {
                        weekday: 'long',  // Agregar día de la semana
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                };

                const valorMayor = parseFloat(diaMayor.total_dia) || 0;
                const valorMenor = parseFloat(diaMenor.total_dia) || 0;
                const pacientesMayor = parseInt(diaMayorPacientes.pacientes_dia) || 0;

                // Log para depuración
                console.log('Estadísticas diarias:', {
                    diaMayor: {
                        fecha: diaMayor.fecha,
                        valor: valorMayor,
                        pacientes: diaMayor.pacientes_dia,
                        registros: diaMayor.total_registros,
                        dia_semana: diaMayor.dia_semana
                    },
                    diaMenor: {
                        fecha: diaMenor.fecha,
                        valor: valorMenor,
                        pacientes: diaMenor.pacientes_dia,
                        registros: diaMenor.total_registros,
                        dia_semana: diaMenor.dia_semana
                    },
                    diaMayorPacientes: {
                        fecha: diaMayorPacientes.fecha,
                        pacientes: pacientesMayor,
                        registros: diaMayorPacientes.total_registros,
                        dia_semana: diaMayorPacientes.dia_semana
                    }
                });

                container.html(`
                    <div class="stat-card success animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                        <div class="stat-icon">
                            <i class="fas fa-arrow-trend-up"></i>
                        </div>
                        <h3 class="stat-value" style="font-size: 1.5rem;">$${valorMayor.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h3>
                        <p class="stat-label">
                            <strong>Día con Mayor Facturación</strong><br>
                            <small class="text-muted">${formatFecha(diaMayor.fecha)}</small><br>
                            <small class="text-muted" style="font-size: 0.75rem;">${diaMayor.pacientes_dia} pacientes • ${diaMayor.total_registros} registros</small>
                        </p>
                        <div style="position: absolute; top: 10px; right: 10px;">
                            <i class="fas fa-arrow-up" style="color: #10b981; font-size: 20px;"></i>
                        </div>
                    </div>

                    <div class="stat-card danger animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                        <div class="stat-icon">
                            <i class="fas fa-arrow-trend-down"></i>
                        </div>
                        <h3 class="stat-value" style="font-size: 1.5rem;">$${valorMenor.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h3>
                        <p class="stat-label">
                            <strong>Día con Menor Facturación</strong><br>
                            <small class="text-muted">${formatFecha(diaMenor.fecha)}</small><br>
                            <small class="text-muted" style="font-size: 0.75rem;">${diaMenor.pacientes_dia} pacientes • ${diaMenor.total_registros} registros</small>
                        </p>
                        <div style="position: absolute; top: 10px; right: 10px;">
                            <i class="fas fa-arrow-down" style="color: #ef4444; font-size: 20px;"></i>
                        </div>
                    </div>

                    <div class="stat-card info animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="stat-value" style="font-size: 1.8rem;">${pacientesMayor.toLocaleString()}</h3>
                        <p class="stat-label">
                            <strong>Día con Más Pacientes</strong><br>
                            <small class="text-muted">${formatFecha(diaMayorPacientes.fecha)}</small><br>
                            <small class="text-muted" style="font-size: 0.75rem;">$${parseFloat(diaMayorPacientes.total_dia).toLocaleString('es-ES', {minimumFractionDigits: 2})} • ${diaMayorPacientes.total_registros} registros</small>
                        </p>
                        <div style="position: absolute; top: 10px; right: 10px;">
                            <i class="fas fa-user-group" style="color: #06b6d4; font-size: 18px;"></i>
                        </div>
                    </div>
                `);
            }

            function updateChartEstadosPendientes(estadisticas) {
                const ctx = document.getElementById('chartEstadosPendientes');
                if (!ctx) {
                    console.warn('Canvas chartEstadosPendientes no encontrado');
                    return;
                }

                if (charts.estadosPendientes) {
                    charts.estadosPendientes.destroy();
                }

                // Validar que hay datos
                if (!estadisticas || estadisticas.length === 0) {
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay estadísticas de estados para el período seleccionado
                        </div>
                    `);
                    return;
                }

                const labels = estadisticas.map(stat => stat.estado);
                const datos = estadisticas.map(stat => stat.total_pendientes);
                const colors = ['#f59e0b', '#10b981', '#ef4444', '#06b6d4', '#8b5cf6', '#6366f1', '#f97316'];

                charts.estadosPendientes = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: datos,
                            backgroundColor: colors.slice(0, labels.length),
                            borderWidth: 3,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            function updateChartValoresPendientes(estadisticas) {
                const ctx = document.getElementById('chartValoresPendientes');
                if (!ctx) {
                    console.warn('Canvas chartValoresPendientes no encontrado');
                    return;
                }

                if (charts.valoresPendientes) {
                    charts.valoresPendientes.destroy();
                }

                // Validar que hay datos
                if (!estadisticas || estadisticas.length === 0) {
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay valores de estados para el período seleccionado
                        </div>
                    `);
                    return;
                }

                const labels = estadisticas.map(stat => stat.estado);
                const datos = estadisticas.map(stat => stat.valor_total);

                charts.valoresPendientes = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor Total',
                            data: datos,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: '#ef4444',
                            borderWidth: 2,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function updateChartTendenciasPendientes(tendencias) {
                const ctx = document.getElementById('chartTendenciasPendientes');
                if (!ctx) {
                    console.warn('Canvas chartTendenciasPendientes no encontrado');
                    return;
                }

                if (charts.tendenciasPendientes) {
                    charts.tendenciasPendientes.destroy();
                }

                if (!tendencias || tendencias.length === 0) {
                    console.warn('No hay datos de tendencias para mostrar');
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay datos de tendencias para el período seleccionado
                        </div>
                    `);
                    return;
                }

                console.log('Datos de tendencias recibidos:', tendencias);

                const labels = tendencias.map(t => {
                    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    return meses[t.mes - 1] + ' ' + t.año;
                });

                // Obtener todos los estados únicos de los datos
                const todosEstados = new Set();
                tendencias.forEach(t => {
                    if (t.estados) {
                        Object.keys(t.estados).forEach(estado => todosEstados.add(estado));
                    }
                });

                const estados = Array.from(todosEstados);
                const coloresEstados = {
                    'PENDIENTE': '#f59e0b',
                    'ENTREGADO': '#10b981',
                    'ANULADO': '#ef4444',
                    'DESABASTECIDO': '#06b6d4',
                    'SIN CONTACTO': '#8b5cf6',
                    'TRAMITADO': '#6366f1',
                    'VENCIDO': '#f97316'
                };

                const datasets = estados.map(estado => ({
                    label: estado,
                    data: tendencias.map(t => (t.estados && t.estados[estado]) ? t.estados[estado] : 0),
                    borderColor: coloresEstados[estado] || '#999999',
                    backgroundColor: (coloresEstados[estado] || '#999999') + '20',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4
                }));

                charts.tendenciasPendientes = new Chart(ctx, {
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
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            function updateChartTopMedicamentosPendientes(medicamentos) {
                const ctx = document.getElementById('chartTopMedicamentosPendientes');
                if (!ctx) {
                    console.warn('Canvas chartTopMedicamentosPendientes no encontrado');
                    return;
                }

                if (charts.topMedicamentosPendientes) {
                    charts.topMedicamentosPendientes.destroy();
                }

                if (!medicamentos || medicamentos.length === 0) {
                    console.warn('No hay medicamentos pendientes para mostrar');
                    $(ctx).closest('.chart-container').html(`
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay medicamentos pendientes para mostrar en el período seleccionado
                        </div>
                    `);
                    return;
                }

                console.log('Medicamentos pendientes recibidos:', medicamentos);

                const labels = medicamentos.slice(0, 10).map(med => {
                    const nombre = med.nombre;
                    return nombre && nombre.length > 20 ? nombre.substring(0, 20) + '...' : (nombre || 'Sin nombre');
                });
                const valores = medicamentos.slice(0, 10).map(med => parseFloat(med.valor_total) || 0);

                charts.topMedicamentosPendientes = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor Total',
                            data: valores,
                            backgroundColor: 'rgba(139, 92, 246, 0.8)',
                            borderColor: '#8b5cf6',
                            borderWidth: 2,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Cargar DataTable de medicamentos pendientes
            function loadTablaMedicamentosPendientes(medicamentos) {
                console.log('Cargando tabla de medicamentos pendientes:', medicamentos);

                // Destruir tabla existente si existe
                if ($.fn.DataTable.isDataTable('#tabla-medicamentos-pendientes')) {
                    $('#tabla-medicamentos-pendientes').DataTable().destroy();
                }

                // Validar datos
                if (!medicamentos || medicamentos.length === 0) {
                    $('#tabla-medicamentos-pendientes tbody').html(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay medicamentos pendientes para el período seleccionado
                            </td>
                        </tr>
                    `);
                    return;
                }

                // Crear DataTable
                $('#tabla-medicamentos-pendientes').DataTable({
                    data: medicamentos,
                    columns: [
                        {
                            data: 'nombre',
                            name: 'nombre'
                        },
                        {
                            data: 'codigo',
                            name: 'codigo'
                        },
                        {
                            data: 'total_cantidad',
                            name: 'total_cantidad',
                            type: 'num',
                            render: function(data, type) {
                                if (type === 'display' || type === 'filter') {
                                    return parseFloat(data).toLocaleString('es-ES', {minimumFractionDigits: 0, maximumFractionDigits: 2});
                                }
                                return parseFloat(data) || 0;
                            }
                        },
                        {
                            data: 'total_pendientes',
                            name: 'total_pendientes',
                            type: 'num'
                        },
                        {
                            data: 'valor_total',
                            name: 'valor_total',
                            type: 'num',
                            render: function(data, type) {
                                if (type === 'display' || type === 'filter') {
                                    return '$' + parseFloat(data).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                                return parseFloat(data) || 0;
                            }
                        }
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                    },
                    order: [[4, 'desc']], // Ordenar por valor total descendente
                    pageLength: 25,
                    responsive: true,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel me-2"></i>Exportar Excel',
                            className: 'btn btn-success btn-sm',
                            title: 'Medicamentos Pendientes'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf me-2"></i>Exportar PDF',
                            className: 'btn btn-danger btn-sm',
                            title: 'Medicamentos Pendientes'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print me-2"></i>Imprimir',
                            className: 'btn btn-info btn-sm',
                            title: 'Medicamentos Pendientes'
                        }
                    ]
                });

                console.log('Tabla de medicamentos pendientes cargada correctamente');
            }

            // Event listeners para filtros
            $('#fecha_inicio, #fecha_fin, #contrato').change(function() {
                const activeSection = $('.menu-item.active').data('section');
                if (activeSection) {
                    loadSectionData(activeSection);
                }
            });

            // Inicialización
            console.log('Dashboard optimizado cargado correctamente');
        });
    </script>

</body>

</html>