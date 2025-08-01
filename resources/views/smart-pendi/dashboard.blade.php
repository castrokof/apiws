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

    <!-- Results Section -->
    <div class="row" id="results-section" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt mr-2"></i>
                        Pendientes en Ventana de Oportunidad (0-48 Horas)
                    </h5>
                </div>
                <div class="card-body table-responsive">
                    <table id="pendientes-table" class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Estado</th>
                                <th class="border-0">Paciente</th>
                                <th class="border-0">Documento</th>
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
    </div>

    <!-- Suggestions Section -->
    <div class="row mt-4" id="suggestions-section" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Sugerencias Inteligentes de Entrega
                    </h5>
                </div>
                <div class="card-body" id="suggestions-content">
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
                    data: 'documento',
                    name: 'documento'
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

    // Execute analysis
    $('#btn-analysis').click(function() {
        $('#results-section').show();
        $('#suggestions-section').hide();

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

    // Get predictive suggestions
    $('#btn-suggestions').click(function() {
        $('#loading-section').show();

        $.get('{{ route("smart.pendi.suggestions") }}')
            .done(function(response) {
                if (response.success) {
                    displaySuggestions(response.suggestions);
                    $('#suggestions-section').show();
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

    // Refresh data
    $('#btn-refresh').click(function() {
        loadStatistics();
        if (pendientesTable) {
            pendientesTable.ajax.reload();
        }

        Swal.fire({
            title: 'Datos Actualizados',
            text: 'Información refrescada correctamente',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    });

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

    // Helper function to get medication details dropdown with enhanced information
    function getMedicationDetailsDropdown(medicamentosString, fechaAntigua, fechaReciente, index) {
        if (!medicamentosString) return '';
        
        const medicamentos = medicamentosString.split(' | ');
        const dropdownId = `medicamentos-dropdown-${index}`;
        
        // Calculate days for oldest and newest medications
        const diasAntigua = fechaAntigua ? calculateDaysBetween(fechaAntigua) : 0;
        const diasReciente = fechaReciente ? calculateDaysBetween(fechaReciente) : 0;
        
        let dropdownOptions = '';
        medicamentos.forEach((medicamento, medIndex) => {
            // For demonstration, we'll distribute the days range across medications
            // In a real scenario, this would come from the backend with individual dates
            const estimatedDays = medIndex === 0 ? diasAntigua : 
                                 medIndex === medicamentos.length - 1 ? diasReciente : 
                                 Math.round((diasAntigua + diasReciente) / 2);
            
            const daysBadgeClass = estimatedDays >= 2 ? 'danger' : 
                                  estimatedDays >= 1 ? 'warning' : 'success';
            
            dropdownOptions += `
                <div class="dropdown-item py-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-pills text-primary mr-2"></i>
                                <strong class="text-primary">${medicamento}</strong>
                            </div>
                            <div class="row small text-muted">
                                <div class="col-6">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <span>Días pendientes:</span>
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-${daysBadgeClass} badge-sm">
                                        ${estimatedDays} día${estimatedDays !== 1 ? 's' : ''}
                                    </span>
                                </div>
                            </div>
                            <div class="row small text-muted mt-1">
                                <div class="col-12">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <em>Los detalles específicos de cada medicamento se cargarán desde el backend</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        return `
            <div class="dropdown">
                <button class="btn btn-outline-info btn-sm dropdown-toggle w-100" type="button" 
                        id="${dropdownId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-pills mr-1"></i>
                    Ver ${medicamentos.length} Medicamento${medicamentos.length > 1 ? 's' : ''} Pendiente${medicamentos.length > 1 ? 's' : ''}
                </button>
                <div class="dropdown-menu w-100" aria-labelledby="${dropdownId}" style="max-height: 400px; overflow-y: auto; min-width: 350px;">
                    <div class="dropdown-header bg-light">
                        <strong><i class="fas fa-list mr-2"></i>Medicamentos Pendientes</strong>
                        <br>
                        <small class="text-muted">Total: ${medicamentos.length} medicamento${medicamentos.length > 1 ? 's' : ''}</small>
                    </div>
                    ${dropdownOptions}
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-item-text">
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Rango de días: ${diasReciente} - ${diasAntigua} días
                        </small>
                    </div>
                </div>
            </div>
        `;
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
                                    <strong>Documento:</strong> ${suggestion.documento}
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
                                ${getMedicationDetailsDropdown(suggestion.medicamentos, suggestion.fecha_mas_antigua, suggestion.fecha_mas_reciente, index)}
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
                                <button class="btn btn-outline-info btn-block" onclick="viewPatientDetails('${suggestion.documento}')">
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

    .card:hover {
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
</style>
@endsection