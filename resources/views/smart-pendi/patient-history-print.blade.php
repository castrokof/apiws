<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Paciente - {{ $paciente->historia }}</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            background: #fff;
            padding: 20mm;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #007bff;
        }

        .header h1 {
            font-size: 20pt;
            color: #007bff;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header h2 {
            font-size: 14pt;
            color: #555;
            font-weight: normal;
        }

        /* Patient Info Section */
        .patient-info {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
            page-break-inside: avoid;
        }

        .patient-info h3 {
            font-size: 14pt;
            color: #007bff;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .info-item {
            display: flex;
        }

        .info-label {
            font-weight: bold;
            min-width: 140px;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        /* Metrics Section */
        .metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .metric-value {
            font-size: 22pt;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
        }

        /* Events Section */
        .events-section {
            margin-bottom: 20px;
        }

        .events-section h3 {
            font-size: 14pt;
            color: #007bff;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #007bff;
            font-weight: bold;
        }

        /* Events Table */
        .events-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .events-table thead {
            background: #007bff;
            color: #fff;
        }

        .events-table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10pt;
            font-weight: bold;
        }

        .events-table td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9pt;
        }

        .events-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .event-tipo {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .tipo-cambio-estado { background: #e3f2fd; color: #1976d2; }
        .tipo-contacto-llamada { background: #fff3e0; color: #f57c00; }
        .tipo-contacto-mensaje { background: #e8f5e9; color: #388e3c; }
        .tipo-contacto-visita { background: #fce4ec; color: #c2185b; }
        .tipo-observacion { background: #f3e5f5; color: #7b1fa2; }
        .tipo-creacion { background: #e0f2f1; color: #00796b; }
        .tipo-anulacion { background: #ffebee; color: #c62828; }
        .tipo-entrega { background: #e8f5e9; color: #2e7d32; }
        .tipo-reprogramacion { background: #fff9c4; color: #f57f17; }

        .resultado-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 8pt;
            font-weight: bold;
        }

        .resultado-exitoso { background: #c8e6c9; color: #2e7d32; }
        .resultado-no-contesta { background: #ffccbc; color: #d84315; }
        .resultado-reagendar { background: #fff9c4; color: #f57f17; }
        .resultado-pendiente { background: #fff9c4; color: #f57f17; }
        .resultado-entregado { background: #c8e6c9; color: #2e7d32; }

        /* Pendientes Activos */
        .pendientes-activos {
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        .pendientes-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .pendientes-table th {
            background: #28a745;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }

        .pendientes-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
            page-break-inside: avoid;
        }

        .footer p {
            margin: 3px 0;
        }

        /* Print-specific styles */
        @media print {
            body {
                padding: 10mm;
            }

            .header, .patient-info, .metrics, .events-section, .pendientes-activos {
                page-break-inside: avoid;
            }

            .events-table {
                page-break-inside: auto;
            }

            .events-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .events-table thead {
                display: table-header-group;
            }
        }

        /* No data message */
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>HISTÓRICO DE GESTIÓN DE PACIENTE - {{ $tipoInforme }}</h1>
        <h2>Sistema de Seguimiento - Smart Pendi</h2>
        @if($tipo === 'resumen')
        <p style="font-size: 10pt; color: #666; margin-top: 5px;">
            <i style="font-style: italic;">Informe Resumido - Solo datos del paciente e histórico de eventos</i>
        </p>
        @else
        <p style="font-size: 10pt; color: #666; margin-top: 5px;">
            <i style="font-style: italic;">Informe Detallado - Incluye pendientes activos y eventos completos</i>
        </p>
        @endif
    </div>

    <!-- Patient Information -->
    <div class="patient-info">
        <h3>Información del Paciente</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Historia Clínica:</span>
                <span class="info-value">{{ $paciente->historia }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Documento:</span>
                <span class="info-value">{{ $paciente->documento ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Nombre Completo:</span>
                <span class="info-value">{{ $nombreCompleto }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $paciente->telefres ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Contrato:</span>
                <span class="info-value">{{ $paciente->contrato ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">EPS:</span>
                <span class="info-value">{{ $paciente->eps ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Metrics (Only in Detailed Report) -->
    @if($tipo === 'detalle')
    <div class="metrics">
        <div class="metric-card">
            <div class="metric-value">{{ $totalPendientes }}</div>
            <div class="metric-label">Total Pendientes</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $totalContactos }}</div>
            <div class="metric-label">Contactos Realizados</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $tasaExito }}%</div>
            <div class="metric-label">Tasa de Éxito</div>
        </div>
    </div>
    @endif

    <!-- Pendientes Section -->
    <!-- Detallado: Shows PENDIENTE items -->
    <!-- Resumen: Shows ENTREGADO items -->
    @if(count($pendientes) > 0)
    <div class="pendientes-activos">
        <h3 style="font-size: 14pt; color: {{ $tipo === 'detalle' ? '#28a745' : '#007bff' }}; margin-bottom: 10px; font-weight: bold;">
            {{ $tituloSeccionPendientes }}
        </h3>
        <table class="pendientes-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Medicamento</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    @if($tipo === 'resumen')
                    <th>Factura</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($pendientes as $pendiente)
                <tr>
                    <td>{{ $pendiente->codigo }}</td>
                    <td>{{ $pendiente->nombre }}</td>
                    <td><span class="resultado-badge resultado-{{ strtolower($pendiente->estado) }}">{{ $pendiente->estado }}</span></td>
                    <td>{{ $pendiente->fecha ? \Carbon\Carbon::parse($pendiente->fecha)->format('d/m/Y') : 'N/A' }}</td>
                    @if($tipo === 'resumen')
                    <td>{{ $pendiente->factura ?? 'N/A' }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Events History -->
    <div class="events-section">
        <h3>Histórico de Eventos ({{ $totalEventos }} registros)</h3>

        @if($eventos->count() > 0)
        <table class="events-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Fecha/Hora</th>
                    <th style="width: 15%;">Tipo de Evento</th>
                    <th style="width: 25%;">Descripción</th>
                    <th style="width: 10%;">Resultado</th>
                    <th style="width: 15%;">Usuario</th>
                    <th style="width: 23%;">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventos as $evento)
                <tr>
                    <td>{{ $evento->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @php
                            $tipoClass = 'tipo-' . strtolower(str_replace('_', '-', $evento->tipo_evento));
                        @endphp
                        <span class="event-tipo {{ $tipoClass }}">
                            {{ str_replace('_', ' ', $evento->tipo_evento) }}
                        </span>
                    </td>
                    <td>{{ $evento->titulo }}</td>
                    <td>
                        @if($evento->resultado_contacto)
                            <span class="resultado-badge resultado-{{ strtolower(str_replace('_', '-', $evento->resultado_contacto)) }}">
                                {{ str_replace('_', ' ', $evento->resultado_contacto) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $evento->usuario->name ?? 'Sistema' }}</td>
                    <td style="font-size: 8pt;">{{ Str::limit($evento->descripcion, 100) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="no-data">No se encontraron eventos registrados para este paciente.</p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Tipo de Informe:</strong> {{ $tipoInforme }}</p>
        <p><strong>Documento generado el:</strong> {{ $fechaGeneracion }}</p>
        <p><strong>Sistema Smart Pendi</strong> - Gestión de Medicamentos Pendientes</p>
        <p style="margin-top: 10px; font-size: 8pt;">
            Este documento es un reporte informativo del histórico de gestión del paciente.
            @if($tipo === 'resumen')
            <br>Informe resumido sin detalle de pendientes activos.
            @else
            <br>Informe detallado con pendientes activos e histórico completo.
            @endif
        </p>
    </div>

    <!-- Auto-print script -->
    <script>
        // Auto-open print dialog when page loads
        window.onload = function() {
            window.print();
        };

        // Close window after printing or canceling
        window.onafterprint = function() {
            // Optional: close window automatically after print dialog closes
            // window.close();
        };
    </script>
</body>
</html>
