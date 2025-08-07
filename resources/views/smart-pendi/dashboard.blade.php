@extends('layouts.app')

@section('title', 'Smart Pendi - Análisis Predictivo')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center text-white py-4">
                    <div class="mb-3">
                        <i class="fas fa-brain fa-3x mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
                    </div>
                    <h2 class="font-weight-bold mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                        🧠 SMART PENDI
                    </h2>
                    <p class="mb-0 lead">Análisis Predictivo de Pendientes - Sistema Inteligente de Gestión</p>
                    <small class="opacity-75">Enfocado en pendientes dentro de 0-48 horas para optimizar entregas oportunas</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="card-title text-primary mb-1" id="total-pendientes">0</h4>
                    <p class="card-text text-muted mb-0">Total Dentro 48h</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <h4 class="card-title text-warning mb-1" id="criticos-24-48h">0</h4>
                    <p class="card-text text-muted mb-0">Críticos 24-48h</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-danger mb-2">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <h4 class="card-title text-danger mb-1" id="proximos-vencer">0</h4>
                    <p class="card-text text-muted mb-0">Próximos a Vencer</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="card-title text-success mb-1" id="nuevos-24h">0</h4>
                    <p class="card-text text-muted mb-0">Nuevos -24h</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Analysis Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-search mr-2"></i>
                            Panel de Análisis
                        </h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" id="btn-analysis">
                                <i class="fas fa-analytics mr-1"></i>
                                Ejecutar Análisis
                            </button>
                            <button type="button" class="btn btn-success" id="btn-suggestions">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Ver Sugerencias
                            </button>
                            <button type="button" class="btn btn-info" id="btn-refresh">
                                <i class="fas fa-sync-alt mr-1"></i>
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Analysis Tabs Section -->
    <div class="row" id="analysis-tabs-section" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-3">
                        <i class="fas fa-chart-line mr-2"></i>
                        Análisis - Vista Organizada
                    </h5>
                    <!-- Main Navigation Tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom" id="mainAnalysisTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pendientes-tab" data-toggle="tab" href="#pendientes-panel" role="tab">
                                <i class="fas fa-clock text-primary mr-1"></i>
                                <span class="d-none d-md-inline">Pendientes en Ventana</span>
                                <span class="d-md-none">Pendientes</span>
                                <small class="d-block text-muted">(0-48 Horas)</small>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sugerencias-tab" data-toggle="tab" href="#sugerencias-panel" role="tab">
                                <i class="fas fa-lightbulb text-warning mr-1"></i>
                                <span class="d-none d-md-inline">Sugerencias Inteligentes</span>
                                <span class="d-md-none">Sugerencias</span>
                                <small class="d-block text-muted">de Entrega</small>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="inventario-tab" data-toggle="tab" href="#inventario-panel" role="tab">
                                <i class="fas fa-warehouse text-info mr-1"></i>
                                <span class="d-none d-md-inline">Análisis por Inventario</span>
                                <span class="d-md-none">Inventario</span>
                                <small class="d-block text-muted">Estado de Saldo</small>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <!-- Tab Content -->
                    <div class="tab-content" id="mainAnalysisTabsContent">
                        <!-- Pendientes Tab -->
                        <div class="tab-pane fade show active" id="pendientes-panel" role="tabpanel">
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list-alt mr-2"></i>
                                        Pendientes en Ventana de Oportunidad (0-48 Horas)
                                    </h6>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" id="refresh-pendientes">
                                            <i class="fas fa-sync-alt mr-1"></i>
                                            Actualizar
                                        </button>
                                        <button type="button" class="btn btn-outline-success" id="export-pendientes">
                                            <i class="fas fa-download mr-1"></i>
                                            Exportar
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="pendientes-table" class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">Estado</th>
                                                <th class="border-0">Paciente</th>
                                                <th class="border-0">Historia</th>
                                                <th class="border-0">Medicamento</th>
                                                <th class="border-0">Horas Transcurridas</th>
                                                <th class="border-0">Fecha Factura</th>
                                                <th class="border-0">Teléfono</th>
                                                <th class="border-0">Municipio</th>
                                                <th class="border-0">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas dinámicas se llenan por JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Sugerencias Tab -->
                        <div class="tab-pane fade" id="sugerencias-panel" role="tabpanel">
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        Sugerencias Inteligentes de Entrega
                                    </h6>
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="refresh-sugerencias">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                        Actualizar Sugerencias
                                    </button>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Información:</strong> Estas sugerencias priorizan pacientes con múltiples medicamentos pendientes para optimizar las entregas consolidadas.
                                </div>
                                <div id="suggestions-content">
                                    <!-- Content loaded by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Inventario Tab -->
                        <div class="tab-pane fade" id="inventario-panel" role="tabpanel">
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-warehouse mr-2"></i>
                                        Análisis por Estado de Inventario
                                    </h6>
                                    <button type="button" class="btn btn-outline-info btn-sm" id="refresh-inventario">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                        Actualizar Inventario
                                    </button>
                                </div>
                                
                                <!-- Sub-tabs for inventory status -->
                                <ul class="nav nav-pills nav-fill" id="inventoryTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="con-saldo-tab" data-toggle="tab" href="#con-saldo" role="tab">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            <span class="d-none d-sm-inline">Medicamentos</span> Con Saldo
                                            <span class="badge badge-success ml-2" id="con-saldo-count">0</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="sin-saldo-tab" data-toggle="tab" href="#sin-saldo" role="tab">
                                            <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                                            <span class="d-none d-sm-inline">Medicamentos</span> Sin Saldo
                                            <span class="badge badge-warning ml-2" id="sin-saldo-count">0</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Inventory sub-tabs content -->
                                <div class="tab-content mt-3" id="inventoryTabsContent">
                                    <!-- Con Saldo Sub-tab -->
                                    <div class="tab-pane fade show active" id="con-saldo" role="tabpanel">
                                        <div class="alert alert-success">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <strong>Medicamentos Disponibles:</strong> Estos pacientes tienen medicamentos con saldo disponible, priorizados para entrega inmediata.
                                        </div>
                                        <div id="con-saldo-content"></div>
                                    </div>

                                    <!-- Sin Saldo Sub-tab -->
                                    <div class="tab-pane fade" id="sin-saldo" role="tabpanel">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <strong>Medicamentos Sin Disponibilidad:</strong> Estos pacientes requieren gestión de compras o coordinación con proveedores.
                                        </div>
                                        <div id="sin-saldo-content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="row" id="loading-section" style="display: none;">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Analizando pendientes críticos...</p>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-phone mr-2"></i>
                    Contactar Paciente
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contact-modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-call">
                    <i class="fas fa-phone mr-1"></i>
                    Llamar Ahora
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scriptsPlugins")
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    let pendientesTable;

    $(document).ready(function() {
        loadStatistics();
        initializeDataTable();

        // Auto-refresh every 5 minutes
        setInterval(loadStatistics, 300000);

        // Handle modal events to prevent conflicts
        $(document).on('show.bs.modal', '[id^="medicamentos-modal-"]', function() {
            // Add modal-open class to prevent page scrolling
            $('body').addClass('modal-medication-open');
        });

        $(document).on('hidden.bs.modal', '[id^="medicamentos-modal-"]', function() {
            // Remove modal-open class to restore page functionality
            $('body').removeClass('modal-medication-open');
        });
    });

    // Initialize DataTable with server-side processing
    function initializeDataTable() {
        pendientesTable = $('#pendientes-table').DataTable({
            language: idioma_espanol,
            pageLength: 25,
            responsive: true,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Todos"]],
            dom: '<"row"<"col-md-4"l><"col-md-4"f><"col-md-4"B>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            autoWidth: true,
            ajax: {
                url: '{{ route("smart.pendi.analysis") }}',
                type: 'GET'
            },
            columns: [{
                    data: 'estado_prioridad',
                    name: 'estado',
                    orderable: false,
                    render: function(data, type, row) {
                        return getEstadoBadge(data);
                    }
                },
                {
                    data: 'paciente',
                    name: 'paciente',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold">' + data + '</span>';
                    }
                },
                {
                    data: 'historia',
                    name: 'historia',
                    render: function(data, type, row) {
                        return '<span class="font-weight-medium text-info">' + (data || 'N/A') + '</span>';
                    }
                },
                {
                    data: 'medicamento',
                    name: 'nombre',
                    render: function(data, type, row) {
                        return '<span class="text-truncate" style="max-width: 200px;" title="' + data + '">' + data + '</span>';
                    }
                },
                {
                    data: null,
                    name: 'fecha_factura',
                    orderable: true,
                    render: function(data, type, row) {
                        const horas = row.horas_transcurridas || 0;
                        const badgeClass = horas >= 40 ? 'danger' : (horas >= 24 ? 'warning' : 'success');
                        return '<span class="badge badge-' + badgeClass + ' badge-pill">' + horas + ' horas</span>';
                    }
                },
                {
                    data: 'fecha_factura',
                    name: 'fecha_factura',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                {
                    data: 'telefono',
                    name: 'telefres',
                    orderable: false,
                    render: function(data, type, row) {
                        if (data) {
                            return '<button class="btn btn-sm btn-outline-primary" onclick="contactPatient(\'' + row.id + '\', \'' + row.paciente + '\', \'' + data + '\', \'' + row.medicamento + '\')">' +
                                '<i class="fas fa-phone mr-1"></i>' + data +
                                '</button>';
                        }
                        return '<span class="text-muted">No disponible</span>';
                    }
                },
                {
                    data: 'municipio',
                    name: 'municipio',
                    render: function(data, type, row) {
                        return data || 'N/A';
                    }
                },
                {
                    data: null,
                    name: 'acciones',
                    orderable: false,
                    render: function(data, type, row) {
                        return '<div class="btn-group-vertical btn-group-sm">' +
                            '<button class="btn btn-outline-info btn-sm" onclick="viewDetails(\'' + row.id + '\')">' +
                            '<i class="fas fa-eye"></i>' +
                            '</button>' +
                            '</div>';
                    }
                }
            ],
            
            order: [
                [4, 'desc']
            ], // Order by hours descending
            buttons: [
                
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn-danger'
                }
            ]
        });
    }

    // Load dashboard statistics
    function loadStatistics() {
        $.get('{{ route("smart.pendi.statistics") }}')
            .done(function(response) {
                if (response.success) {
                    const stats = response.statistics;
                    $('#total-pendientes').text(stats.dentro_48h || 0);
                    $('#criticos-24-48h').text(stats.criticos_24_48h || 0);
                    $('#proximos-vencer').text(stats.proximos_vencer || 0);
                    $('#nuevos-24h').text(stats.nuevos_24h || 0);
                }
            })
            .fail(function() {
                console.error('Error loading statistics');
            });
    }

    // Execute analysis - Updated for new tab structure
    $('#btn-analysis').click(function() {
        $('#analysis-tabs-section').show();
        $('#pendientes-tab').tab('show'); // Show pendientes tab by default

        if (pendientesTable) {
            pendientesTable.ajax.reload();
        }

        Swal.fire({
            title: 'Análisis Ejecutado',
            text: 'Datos actualizados correctamente',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    });

    // Get predictive suggestions with inventory balance logic - Updated for tabs
    $('#btn-suggestions').click(function() {
        $('#analysis-tabs-section').show();
        $('#sugerencias-tab').tab('show'); // Show sugerencias tab
        $('#loading-section').show();

        $.get('{{ route("smart.pendi.suggestions") }}')
            .done(function(response) {
                if (response.success) {
                    displaySuggestions(response.suggestions);
                    displayInventoryBasedSuggestions(response.suggestions);
                } else {
                    Swal.fire('Error', 'No se pudieron cargar las sugerencias', 'error');
                }
            })
            .fail(function() {
                Swal.fire('Error', 'Error al cargar las sugerencias', 'error');
            })
            .always(function() {
                $('#loading-section').hide();
            });
    });

    // Individual tab refresh buttons
    $('#refresh-pendientes').click(function() {
        if (pendientesTable) {
            pendientesTable.ajax.reload();
        }
        showSuccessMessage('Datos de pendientes actualizados');
    });

    $('#refresh-sugerencias').click(function() {
        $('#loading-section').show();
        $.get('{{ route("smart.pendi.suggestions") }}')
            .done(function(response) {
                if (response.success) {
                    displaySuggestions(response.suggestions);
                    displayInventoryBasedSuggestions(response.suggestions);
                    showSuccessMessage('Sugerencias actualizadas');
                } else {
                    Swal.fire('Error', 'No se pudieron cargar las sugerencias', 'error');
                }
            })
            .fail(function() {
                Swal.fire('Error', 'Error al cargar las sugerencias', 'error');
            })
            .always(function() {
                $('#loading-section').hide();
            });
    });

    $('#refresh-inventario').click(function() {
        $('#loading-section').show();
        $.get('{{ route("smart.pendi.suggestions") }}')
            .done(function(response) {
                if (response.success) {
                    displayInventoryBasedSuggestions(response.suggestions);
                    showSuccessMessage('Análisis de inventario actualizado');
                } else {
                    Swal.fire('Error', 'No se pudieron cargar los datos de inventario', 'error');
                }
            })
            .fail(function() {
                Swal.fire('Error', 'Error al cargar los datos de inventario', 'error');
            })
            .always(function() {
                $('#loading-section').hide();
            });
    });

    // Export functionality for pendientes tab
    $('#export-pendientes').click(function() {
        // Trigger DataTables export functionality
        if (pendientesTable) {
            $('.buttons-excel').click();
        }
    });

    // Refresh data - Updated to work with tabs
    $('#btn-refresh').click(function() {
        loadStatistics();
        
        // Check which tab is active and refresh accordingly
        if ($('#pendientes-tab').hasClass('active')) {
            if (pendientesTable) {
                pendientesTable.ajax.reload();
            }
        }
        
        if ($('#sugerencias-tab').hasClass('active') || $('#inventario-tab').hasClass('active')) {
            // Refresh suggestions and inventory data
            $('#loading-section').show();
            $.get('{{ route("smart.pendi.suggestions") }}')
                .done(function(response) {
                    if (response.success) {
                        displaySuggestions(response.suggestions);
                        displayInventoryBasedSuggestions(response.suggestions);
                    }
                })
                .always(function() {
                    $('#loading-section').hide();
                });
        }

        showSuccessMessage('Información refrescada correctamente');
    });

    // Helper function for success messages
    function showSuccessMessage(message) {
        Swal.fire({
            title: 'Datos Actualizados',
            text: message,
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    }

    // Helper function to get estado badge (moved up for DataTable render function)
    function getEstadoBadge(prioridad) {
        const badges = {
            'EN_TIEMPO': '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>EN TIEMPO</span>',
            'PRIORIDAD': '<span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>PRIORIDAD</span>',
            'CRITICO': '<span class="badge badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i>CRÍTICO</span>',
            'URGENTE': '<span class="badge badge-danger"><i class="fas fa-exclamation-circle mr-1"></i>URGENTE</span>'
        };
        return badges[prioridad.estado] || '<span class="badge badge-secondary">DESCONOCIDO</span>';
    }

    // Helper function to calculate days between dates
    function calculateDaysBetween(dateString) {
        const invoiceDate = new Date(dateString);
        const currentDate = new Date();
        const timeDifference = currentDate - invoiceDate;
        return Math.floor(timeDifference / (1000 * 60 * 60 * 24));
    }

    // Helper function to get medication details with improved collapsible design
    function getMedicationDetailsAccordion(medicamentosString, fechaAntigua, fechaReciente, index) {
        if (!medicamentosString) return '';
        
        const medicamentos = medicamentosString.split(' | ');
        const accordionId = `medicamentos-accordion-${index}`;
        const collapseId = `collapse-medicamentos-${index}`;
        
        // Calculate days for oldest and newest medications
        const diasAntigua = fechaAntigua ? calculateDaysBetween(fechaAntigua) : 0;
        const diasReciente = fechaReciente ? calculateDaysBetween(fechaReciente) : 0;
        
        // Determine if we should show as expanded list or modal for many items
        const showAsModal = medicamentos.length > 6;
        
        if (showAsModal) {
            return getMedicationDetailsModal(medicamentosString, fechaAntigua, fechaReciente, index);
        }
        
        let medicationItems = '';
        medicamentos.forEach((medicamento, medIndex) => {
            const estimatedDays = medIndex === 0 ? diasAntigua : 
                                 medIndex === medicamentos.length - 1 ? diasReciente : 
                                 Math.round((diasAntigua + diasReciente) / 2);
            
            const daysBadgeClass = estimatedDays >= 2 ? 'danger' : 
                                  estimatedDays >= 1 ? 'warning' : 'success';
            
            medicationItems += `
                <div class="medication-item border-left border-${daysBadgeClass} pl-3 py-2 mb-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-pills text-primary mr-2"></i>
                                <strong class="text-primary" style="font-size: 0.9rem;">${medicamento}</strong>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-muted mr-2">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Días pendientes:
                                </small>
                                <span class="badge badge-${daysBadgeClass} badge-sm">
                                    ${estimatedDays} día${estimatedDays !== 1 ? 's' : ''}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        return `
            <div class="medication-accordion">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light p-2" id="heading-${accordionId}">
                        <button class="btn btn-link text-left w-100 p-0 text-decoration-none collapsed" 
                                type="button" data-toggle="collapse" data-target="#${collapseId}" 
                                aria-expanded="false" aria-controls="${collapseId}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-pills text-info mr-2"></i>
                                    <span class="font-weight-medium text-dark">
                                        ${medicamentos.length} Medicamento${medicamentos.length > 1 ? 's' : ''} Pendiente${medicamentos.length > 1 ? 's' : ''}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted mr-2">${diasReciente} - ${diasAntigua} días</small>
                                    <i class="fas fa-chevron-down transition-icon"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="${collapseId}" class="collapse" aria-labelledby="heading-${accordionId}">
                        <div class="card-body p-3">
                            <div class="medication-list">
                                ${medicationItems}
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Rango de días calculado entre el medicamento más antiguo y el más reciente
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Helper function for modal approach when there are many medications
    function getMedicationDetailsModal(medicamentosString, fechaAntigua, fechaReciente, index) {
        const medicamentos = medicamentosString.split(' | ');
        const modalId = `medicamentos-modal-${index}`;
        
        // Calculate days for oldest and newest medications
        const diasAntigua = fechaAntigua ? calculateDaysBetween(fechaAntigua) : 0;
        const diasReciente = fechaReciente ? calculateDaysBetween(fechaReciente) : 0;

        // Build medication cards separately to avoid template string issues
        let medicationCards = '';
        medicamentos.forEach((medicamento, medIndex) => {
            const estimatedDays = medIndex === 0 ? diasAntigua : 
                                 medIndex === medicamentos.length - 1 ? diasReciente : 
                                 Math.round((diasAntigua + diasReciente) / 2);
            const daysBadgeClass = estimatedDays >= 2 ? 'danger' : 
                                  estimatedDays >= 1 ? 'warning' : 'success';
            
            medicationCards += `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-left-${daysBadgeClass} h-100 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-pills text-primary mr-2"></i>
                                <strong class="text-primary text-truncate" title="${medicamento}">${medicamento}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-${daysBadgeClass} badge-pill">
                                    ${estimatedDays} día${estimatedDays !== 1 ? 's' : ''}
                                </span>
                                <small class="text-muted">#${medIndex + 1}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        return `
            <div class="medication-summary">
                <button type="button" class="btn btn-outline-info btn-block medication-modal-btn" data-toggle="modal" data-target="#${modalId}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-pills mr-2"></i>
                            <span>${medicamentos.length} Medicamentos Pendientes</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="text-muted mr-2">${diasReciente} - ${diasAntigua} días</small>
                            <i class="fas fa-external-link-alt"></i>
                        </div>
                    </div>
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog" aria-labelledby="${modalId}Label" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white sticky-top">
                                <h5 class="modal-title" id="${modalId}Label">
                                    <i class="fas fa-pills mr-2"></i>
                                    Medicamentos Pendientes Detallados (${medicamentos.length} total)
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                                <div class="alert alert-info sticky-top mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <strong>Total:</strong> ${medicamentos.length} medicamentos
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            <strong>Rango:</strong> ${diasReciente} - ${diasAntigua} días
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-scroll mr-2"></i>
                                            <small class="text-muted">Desplázate para ver todos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="max-height: calc(75vh - 120px); overflow-y: auto; padding-right: 10px;">
                                    ${medicationCards}
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <div class="d-flex justify-content-between w-100 align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb mr-1"></i>
                                        Contacte al paciente para coordinar entrega consolidada
                                    </small>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-check mr-1"></i>
                                        Entendido
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Display inventory-based suggestions
    function displayInventoryBasedSuggestions(suggestions) {
        // Separar sugerencias por estado de saldo
        const conSaldo = suggestions.filter(s => s.tiene_saldo === true || s.medicamentos_con_saldo > 0);
        const sinSaldo = suggestions.filter(s => s.tiene_saldo === false || s.medicamentos_sin_saldo > 0);
        
        // Actualizar contadores
        $('#con-saldo-count').text(conSaldo.length);
        $('#sin-saldo-count').text(sinSaldo.length);
        
        // Mostrar sugerencias con saldo
        displayInventoryGroup(conSaldo, '#con-saldo-content', 'success');
        
        // Mostrar sugerencias sin saldo
        displayInventoryGroup(sinSaldo, '#sin-saldo-content', 'warning');
    }

    // Helper function to display inventory groups
    function displayInventoryGroup(suggestions, containerId, theme) {
        const content = $(containerId);
        content.empty();
        
        if (suggestions.length === 0) {
            const messageType = theme === 'success' ? 'Disponibles' : 'Sin Disponibilidad';
            const iconType = theme === 'success' ? 'check-circle' : 'info-circle';
            
            content.append(`
                <div class="text-center py-4">
                    <i class="fas fa-${iconType} text-${theme} fa-2x mb-3"></i>
                    <h6 class="text-${theme}">Sin Medicamentos ${messageType}</h6>
                    <p class="text-muted">No hay pacientes en esta categoría actualmente</p>
                </div>
            `);
            return;
        }
        
        suggestions.forEach(function(suggestion, index) {
            const priorityColor = suggestion.prioridad === 'ALTA' ? 'danger' : 
                                (suggestion.prioridad === 'MEDIA-ALTA' ? 'warning' : 'info');
            const statusBadge = theme === 'success' ? 'success' : 'warning';
            const statusText = theme === 'success' ? 'CON SALDO' : 'SIN SALDO';
            const statusIcon = theme === 'success' ? 'check-circle' : 'exclamation-triangle';
            
            content.append(`
                <div class="card mb-3 border-${theme}">
                    <div class="card-header bg-${theme} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-user mr-2"></i>
                                ${suggestion.paciente}
                            </h6>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light mr-2">
                                    <i class="fas fa-${statusIcon} mr-1"></i>
                                    ${statusText}
                                </span>
                                <span class="badge badge-light mr-2">
                                    <i class="fas fa-pills mr-1"></i>
                                    ${suggestion.total_medicamentos} Meds
                                </span>
                                <span class="badge badge-light">${suggestion.plazo || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Historia:</strong> <span class="text-info font-weight-bold">${suggestion.historia || 'N/A'}</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Promedio Horas:</strong> 
                                    <span class="badge badge-${priorityColor}">${suggestion.promedio_horas_transcurridas || 'N/A'}h</span>
                                </p>
                                ${suggestion.municipio ? `<p class="mb-2"><strong>Municipio:</strong> ${suggestion.municipio}</p>` : ''}
                                ${suggestion.telefono ? 
                                    `<p class="mb-2"><strong>Teléfono:</strong> 
                                        <button class="btn btn-sm btn-outline-primary" onclick="contactPatientMultiple('${suggestion.pendiente_ids ? suggestion.pendiente_ids.join(',') : ''}', '${suggestion.paciente}', '${suggestion.telefono}', '${suggestion.total_medicamentos}')">
                                            <i class="fas fa-phone mr-1"></i>${suggestion.telefono}
                                        </button>
                                    </p>` : 
                                    '<p class="mb-2 text-muted"><i class="fas fa-phone-slash mr-1"></i>Sin teléfono</p>'
                                }
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="text-${theme} mb-2">
                                        <i class="fas fa-pills mr-1"></i>
                                        Medicamentos (${suggestion.total_medicamentos})
                                    </h6>
                                    <div style="max-height: 150px; overflow-y: auto;">
                                        ${getMedicamentosCompactList(suggestion.medicamentos, theme)}
                                    </div>
                                    ${theme === 'success' ? 
                                        '<small class="text-success"><i class="fas fa-check mr-1"></i>Listos para entrega</small>' :
                                        '<small class="text-warning"><i class="fas fa-shopping-cart mr-1"></i>Requiere gestión de compras</small>'
                                    }
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-${theme} btn-sm" onclick="prioritizarPaciente('${suggestion.historia || suggestion.documento}', '${theme}')">
                                        <i class="fas fa-star mr-1"></i>
                                        ${theme === 'success' ? 'Priorizar Entrega' : 'Gestionar Compras'}
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="markSuggestionAsHandled(${index})">
                                        <i class="fas fa-check mr-1"></i>
                                        Gestionado
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="viewPatientDetails('${suggestion.historia || suggestion.documento}')">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });
    }
    
    // Helper function for compact medication list
    function getMedicamentosCompactList(medicamentosString, theme) {
        if (!medicamentosString) return '<p class="text-muted">Sin medicamentos</p>';
        
        const medicamentos = medicamentosString.split(' | ');
        const iconClass = theme === 'success' ? 'check-circle text-success' : 'exclamation-circle text-warning';
        
        return medicamentos.map((med, index) => 
            `<div class="d-flex align-items-center mb-1">
                <i class="fas fa-${iconClass} mr-2" style="font-size: 0.8rem;"></i>
                <small class="text-truncate" title="${med}">${med}</small>
            </div>`
        ).join('');
    }

    // New function for prioritizing patients
    function prioritizarPaciente(historia, tipo) {
        const accion = tipo === 'success' ? 'priorizar la entrega' : 'gestionar las compras';
        const titulo = tipo === 'success' ? 'Priorizar Entrega' : 'Gestionar Compras';
        
        Swal.fire({
            title: titulo,
            text: `¿Desea ${accion} para el paciente con historia ${historia}?`,
            icon: tipo === 'success' ? 'success' : 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, proceder',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: tipo === 'success' ? '#28a745' : '#ffc107'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aquí se puede hacer una llamada AJAX al backend
                Swal.fire({
                    title: '¡Procesado!',
                    text: `Paciente marcado para ${accion}`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    // Display suggestions
    function displaySuggestions(suggestions) {
        const content = $('#suggestions-content');
        content.empty();

        if (suggestions.length === 0) {
            content.append(`
            <div class="text-center py-4">
                <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                <h5 class="text-success">¡Perfecto!</h5>
                <p class="text-muted">No hay sugerencias urgentes en este momento</p>
            </div>
        `);
            return;
        }

        suggestions.forEach(function(suggestion, index) {
            const priorityColor = suggestion.prioridad === 'ALTA' ? 'danger' : 
                                (suggestion.prioridad === 'MEDIA-ALTA' ? 'warning' : 'info');
            const priorityIcon = suggestion.prioridad === 'ALTA' ? 'fas fa-exclamation-circle' : 
                               (suggestion.prioridad === 'MEDIA-ALTA' ? 'fas fa-clock' : 'fas fa-info-circle');

            content.append(`
            <div class="card mb-3 border-${priorityColor}">
                <div class="card-header bg-${priorityColor} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="${priorityIcon} mr-2"></i>
                            Sugerencia ${index + 1} - Prioridad ${suggestion.prioridad}
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-light mr-2">
                                <i class="fas fa-pills mr-1"></i>
                                ${suggestion.total_medicamentos} Medicamentos
                            </span>
                            <span class="badge badge-light">${suggestion.plazo}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="text-primary">
                                    <i class="fas fa-user mr-1"></i>
                                    ${suggestion.paciente}
                                </h6>
                                <p class="mb-1">
                                    <strong>Historia:</strong> <span class="text-info font-weight-bold">${suggestion.historia || 'N/A'}</span>
                                    ${suggestion.municipio ? `<span class="ml-3"><strong>Municipio:</strong> ${suggestion.municipio}</span>` : ''}
                                </p>
                                <p class="mb-3">
                                    <strong>Promedio Horas Transcurridas:</strong> 
                                    <span class="badge badge-${priorityColor}">${suggestion.promedio_horas_transcurridas || 'N/A'}h</span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="font-weight-bold mb-2">
                                    <i class="fas fa-list mr-1"></i>
                                    Medicamentos Pendientes:
                                </label>
                                ${getMedicationDetailsAccordion(suggestion.medicamentos, suggestion.fecha_mas_antigua, suggestion.fecha_mas_reciente, index)}
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold mb-2">Acción Recomendada:</label>
                                <div class="alert alert-${priorityColor} alert-dismissible mb-2">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    ${suggestion.accion}
                                </div>
                                ${suggestion.ventaja_consolidacion ? 
                                    `<div class="alert alert-success alert-dismissible">
                                        <i class="fas fa-shipping-fast mr-2"></i>
                                        <strong>Ventaja:</strong> ${suggestion.ventaja_consolidacion}
                                    </div>` : ''
                                }
                            </div>

                            ${suggestion.fecha_mas_antigua ? 
                                `<div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-1">
                                            <i class="fas fa-calendar-alt mr-1 text-info"></i>
                                            <strong>Fecha más antigua:</strong><br>
                                            <small>${new Date(suggestion.fecha_mas_antigua).toLocaleDateString()}</small>
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="mb-1">
                                            <i class="fas fa-calendar-check mr-1 text-success"></i>
                                            <strong>Fecha más reciente:</strong><br>
                                            <small>${new Date(suggestion.fecha_mas_reciente).toLocaleDateString()}</small>
                                        </p>
                                    </div>
                                </div>` : ''
                            }
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                ${suggestion.telefono ?
                                    `<button class="btn btn-${priorityColor} btn-block mb-2" onclick="contactPatientMultiple('${suggestion.pendiente_ids ? suggestion.pendiente_ids.join(',') : ''}', '${suggestion.paciente}', '${suggestion.telefono}', '${suggestion.total_medicamentos}')">
                                        <i class="fas fa-phone mr-1"></i>
                                        Contactar: ${suggestion.telefono}
                                    </button>` : 
                                    `<div class="alert alert-warning text-center">
                                        <i class="fas fa-phone-slash mr-1"></i>
                                        Sin teléfono disponible
                                    </div>`
                                }
                                <button class="btn btn-outline-secondary btn-block mb-2" onclick="markSuggestionAsHandled(${index})">
                                    <i class="fas fa-check mr-1"></i>
                                    Marcar como Gestionada
                                </button>
                                <button class="btn btn-outline-info btn-block" onclick="viewPatientDetails('${suggestion.historia || suggestion.documento}')">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
        });
    }


    // Contact patient function
    function contactPatient(id, nombre, telefono, medicamento) {
        $('#contact-modal-body').html(`
        <div class="row">
            <div class="col-12">
                <h6 class="text-primary">Información del Paciente</h6>
                <p><strong>Nombre:</strong> ${nombre}</p>
                <p><strong>Teléfono:</strong> ${telefono}</p>
                <p><strong>Medicamento:</strong> ${medicamento}</p>
                <hr>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Guión sugerido:</strong><br>
                    "Buenos días/tardes, me comunico de MEDCOL para informarle que su medicamento <em>${medicamento}</em> está listo para entrega. ¿Podríamos coordinar la entrega?"
                </div>
            </div>
        </div>
    `);

        $('#btn-call').off('click').on('click', function() {
            window.location.href = `tel:${telefono}`;
            $('#contactModal').modal('hide');
        });

        $('#contactModal').modal('show');
    }

    // Mark suggestion as handled
    function markSuggestionAsHandled(index) {
        Swal.fire({
            title: '¿Confirmar?',
            text: '¿Desea marcar esta sugerencia como gestionada?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, marcar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you could make an AJAX call to update the backend
                Swal.fire('¡Listo!', 'Sugerencia marcada como gestionada', 'success');
                // Optionally remove the suggestion from the UI
                $('#suggestions-content .card').eq(index).fadeOut();
            }
        });
    }

    // Contact patient function for multiple medications
    function contactPatientMultiple(pendienteIds, nombre, telefono, totalMedicamentos) {
        $('#contact-modal-body').html(`
        <div class="row">
            <div class="col-12">
                <h6 class="text-primary">Información del Paciente</h6>
                <p><strong>Nombre:</strong> ${nombre}</p>
                <p><strong>Teléfono:</strong> ${telefono}</p>
                <p><strong>Total Medicamentos Pendientes:</strong> <span class="badge badge-info">${totalMedicamentos}</span></p>
                <hr>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Guión sugerido para entrega consolidada:</strong><br>
                    "Buenos días/tardes, me comunico de MEDCOL para informarle que tiene <em>${totalMedicamentos} medicamentos</em> listos para entrega. Para brindarle un mejor servicio, queremos coordinar la entrega de todos sus medicamentos en una sola visita. ¿Cuándo sería el mejor momento para usted?"
                </div>
                <div class="alert alert-success">
                    <i class="fas fa-shipping-fast mr-2"></i>
                    <strong>Beneficios de la entrega consolidada:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Una sola visita para todos los medicamentos</li>
                        <li>Mayor comodidad para el paciente</li>
                        <li>Optimización de recursos de entrega</li>
                    </ul>
                </div>
            </div>
        </div>
    `);

        $('#btn-call').off('click').on('click', function() {
            window.location.href = `tel:${telefono}`;
            $('#contactModal').modal('hide');
        });

        $('#contactModal').modal('show');
    }

    // View patient details
    function viewPatientDetails(documento) {
        Swal.fire({
            title: 'Detalles del Paciente',
            html: `
                <div class="text-left">
                    <p><strong>Documento:</strong> ${documento}</p>
                    <p class="text-muted">Esta funcionalidad permitirá ver el historial completo del paciente y todos sus pendientes.</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        En desarrollo: Integración con sistema de historiales
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Entendido'
        });
    }

    // View details (placeholder)
    function viewDetails(id) {
        Swal.fire('Info', `Ver detalles del pendiente ID: ${id}`, 'info');
    }

    var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla =(",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copy": "Copiar",
            "colvis": "Visibilidad"
        }
    }
</script>
@endsection

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">

<style>
    .card-header.bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .card-header.bg-gradient-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .badge-pill {
        font-size: 0.8rem;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover:not([id^="medicamentos-modal-"] .card) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .btn-group-vertical .btn {
        border-radius: 0.25rem !important;
        margin-bottom: 2px;
    }

    .text-truncate {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* DataTables custom styles */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-bottom: 10px;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 10px;
    }

    .dt-button {
        margin-right: 5px !important;
    }

    /* Enhanced Medication Accordion Styles */
    .medication-accordion .card {
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 0;
    }

    .medication-accordion .card-header {
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa !important;
    }

    .medication-accordion .btn-link {
        color: #495057 !important;
        text-decoration: none !important;
        font-size: 0.95rem;
    }

    .medication-accordion .btn-link:hover {
        color: #007bff !important;
        text-decoration: none !important;
    }

    .medication-accordion .btn-link:focus {
        box-shadow: none;
        text-decoration: none !important;
    }

    .medication-accordion .transition-icon {
        transition: transform 0.3s ease;
    }

    .medication-accordion .btn-link:not(.collapsed) .transition-icon {
        transform: rotate(180deg);
    }

    .medication-item {
        background-color: #f8f9fa;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .medication-item:hover {
        background-color: #e9ecef;
        transform: translateX(3px);
    }

    .medication-item.border-left {
        border-left-width: 4px !important;
    }

    /* Modal enhancements for many medications */
    .medication-summary .medication-modal-btn {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        z-index: 1;
    }

    .medication-summary .medication-modal-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .medication-summary .medication-modal-btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .medication-accordion .card-header {
            padding: 0.75rem;
        }
        
        .medication-item {
            padding: 0.75rem !important;
        }
        
        .medication-accordion .btn-link {
            font-size: 0.9rem;
        }
    }

    /* Animation for collapse */
    .medication-accordion .collapse {
        transition: all 0.3s ease;
    }

    .medication-accordion .collapsing {
        transition: height 0.3s ease;
    }

    /* Enhanced badge styles */
    .badge-sm {
        font-size: 0.75rem;
        padding: 0.25em 0.5em;
    }

    /* Modal improvements */
    .modal-lg .modal-body {
        max-height: 75vh;
        overflow-y: auto;
        padding: 1.5rem;
    }
    
    /* Específico para modales de medicamentos */
    [id^="medicamentos-modal-"] .modal-dialog {
        max-width: 90%;
    }
    
    [id^="medicamentos-modal-"] .modal-body {
        max-height: 80vh;
        overflow-y: auto;
        padding: 1rem;
    }
    
    [id^="medicamentos-modal-"] .row {
        margin: -0.5rem;
    }
    
    [id^="medicamentos-modal-"] .col-md-6,
    [id^="medicamentos-modal-"] .col-lg-4 {
        padding: 0.5rem;
    }

    /* Prevenir efectos hover problemáticos en cards del modal */
    [id^="medicamentos-modal-"] .card {
        transform: none !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }

    .modal-header.bg-info {
        border-bottom: none;
    }

    /* Card hover effects in modal - specific scope to avoid conflicts */
    .medication-summary .modal-body .card {
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .medication-summary .modal-body .card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-color: #007bff;
    }

    /* Improved spacing for medication items */
    .medication-list {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .medication-list::-webkit-scrollbar {
        width: 6px;
    }

    .medication-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .medication-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .medication-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Prevent interference with page elements when modal is open */
    body.modal-medication-open {
        overflow: hidden;
    }

    body.modal-medication-open .medication-summary .medication-modal-btn {
        pointer-events: none;
    }

    /* Ensure modals have proper z-index to avoid conflicts */
    .medication-summary .modal {
        z-index: 1055;
    }

    .medication-summary .modal-backdrop {
        z-index: 1050;
    }

    /* Prevent hover effects on disabled elements */
    .medication-summary .medication-modal-btn:disabled,
    .medication-summary .medication-modal-btn[disabled] {
        pointer-events: none;
        opacity: 0.6;
        transform: none !important;
        box-shadow: none !important;
    }

    /* ==================== NEW TABBED LAYOUT STYLES ==================== */
    
    /* Main analysis tabs custom styling */
    .nav-tabs-custom .nav-link {
        border: none;
        background-color: #f8f9fa;
        color: #495057;
        padding: 1rem 1.5rem;
        margin-right: 0.25rem;
        border-radius: 0.5rem 0.5rem 0 0;
        transition: all 0.3s ease;
        min-height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .nav-tabs-custom .nav-link:hover {
        background-color: #e9ecef;
        color: #007bff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .nav-tabs-custom .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    }

    .nav-tabs-custom .nav-link.active:hover {
        background-color: #0056b3;
        color: white;
        transform: translateY(-2px);
    }

    .nav-tabs-custom .nav-link i {
        font-size: 1.2rem;
        margin-bottom: 0.25rem;
    }

    .nav-tabs-custom .nav-link small {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-top: 0.25rem;
    }

    /* Tab content styling */
    #mainAnalysisTabsContent {
        min-height: 600px;
    }

    #mainAnalysisTabsContent .tab-pane {
        animation: fadeInUp 0.3s ease-in-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    /* Individual tab headers */
    .tab-pane h6 {
        color: #495057;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Pills navigation for inventory sub-tabs */
    .nav-pills .nav-link {
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .nav-pills .nav-link.active {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Enhanced button groups for tabs */
    .tab-pane .btn-group-sm .btn {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
    }

    .tab-pane .btn-outline-primary:hover,
    .tab-pane .btn-outline-success:hover,
    .tab-pane .btn-outline-warning:hover,
    .tab-pane .btn-outline-info:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Alert styling within tabs */
    .tab-pane .alert {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .tab-pane .alert-info {
        background: linear-gradient(45deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
    }

    .tab-pane .alert-success {
        background: linear-gradient(45deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .tab-pane .alert-warning {
        background: linear-gradient(45deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
    }

    /* Responsive design for tabs */
    @media (max-width: 768px) {
        .nav-tabs-custom .nav-link {
            padding: 0.75rem;
            min-height: 60px;
            font-size: 0.875rem;
        }
        
        .nav-tabs-custom .nav-link i {
            font-size: 1rem;
        }
        
        .nav-tabs-custom .nav-link small {
            font-size: 0.6875rem;
        }
        
        #mainAnalysisTabsContent {
            min-height: 400px;
        }
    }

    @media (max-width: 576px) {
        .nav-tabs-custom {
            flex-direction: column;
        }
        
        .nav-tabs-custom .nav-link {
            margin-right: 0;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
            text-align: left;
            flex-direction: row;
            align-items: center;
        }
        
        .nav-tabs-custom .nav-link i {
            margin-right: 0.5rem;
            margin-bottom: 0;
        }
        
        .nav-tabs-custom .nav-link small {
            margin-left: auto;
            margin-top: 0;
        }
    }

    /* Loading overlay for tab content */
    .tab-pane {
        position: relative;
    }

    .tab-loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    /* Badge enhancements for tab navigation */
    .nav-tabs-custom .badge,
    .nav-pills .badge {
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Enhanced card styling within tabs */
    .tab-pane .card {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
    }

    .tab-pane .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }

    /* Table enhancements within tabs */
    .tab-pane .table-responsive {
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .tab-pane .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
</style>
@endsection