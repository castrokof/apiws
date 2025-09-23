<!-- Modal Gestión de Pendientes por Paciente -->
<div class="modal fade" id="modalGestionPacientes" tabindex="-1" role="dialog" aria-labelledby="modalGestionPacientes_label">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Estructura de Card de AdminLTE para que funcionen los botones -->
            <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <h5 class="modal-title mb-0 flex-grow-1" id="modalGestionPacientes_label">Gestión de Pendientes por Paciente</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-dismiss="modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body modal-body">
                    <!-- Filtros de búsqueda de pacientes -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h6>
                        </div>
                        <div class="card-body">
                            <form id="filtros-pacientes-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="paciente_fechaini">Fecha Inicial</label>
                                        <input type="date" id="paciente_fechaini" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="paciente_fechafin">Fecha Final</label>
                                        <input type="date" id="paciente_fechafin" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="paciente_documento">Historia</label>
                                        <input type="text" id="paciente_documento" class="form-control form-control-sm" placeholder="Ingrese documento o historia">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="paciente_contrato">Farmacia</label>
                                        <select id="paciente_contrato" class="form-control form-control-sm select2bs4">
                                            <option value="">Todas las farmacias</option>
                                            <optgroup label="Farmacias Principales">
                                                <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                                <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                                                <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                                <option value="EM01">EM01-FARMACIA EMCALI</option>
                                                <option value="FRIO">FRIO-FARMACIA IDEO</option>
                                                <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                                <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                                <option value="FRIP">FRIP-FARMACIA PASOANCHO</option>
                                                <option value="INY">INY-FARMACIA INYECTABLES</option>
                                                <option value="PAC">PAC-FARMACIA PAC</option>
                                                <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                                            </optgroup>
                                            <optgroup label="Farmacias Especializadas">
                                                <option value="BPDT">BPDT-BOLSA</option>
                                                <option value="EVIO">EVIO-EVENTO IDEO</option>
                                                <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                                <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="btn-group w-100">
                                            <button type="button" id="buscar_pacientes" class="btn btn-primary btn-sm">
                                                <i class="fas fa-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="gestionPacientesTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="lista-pacientes-tab" data-toggle="tab" href="#lista-pacientes"
                                role="tab" aria-controls="lista-pacientes" aria-selected="true">
                                <i class="fas fa-users"></i> Lista de Pacientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="detalle-paciente-tab" data-toggle="tab" href="#detalle-paciente"
                                role="tab" aria-controls="detalle-paciente" aria-selected="false">
                                <i class="fas fa-user-medical"></i> Detalle del Paciente
                            </a>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content mt-3" id="gestionPacientesTabsContent">
                        <!-- Pestaña 1: Lista de Pacientes -->
                        <div class="tab-pane fade show active" id="lista-pacientes" role="tabpanel" aria-labelledby="lista-pacientes-tab">
                            <div class="card">
                                <div class="card-body table-responsive">
                                    <table id="tablaPacientes" class="table table-bordered table-striped table-hover text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Historia</th>
                                                <th>Nombre Completo</th>
                                                <th>Teléfono</th>
                                                <th>Total Pendientes</th>
                                                <th>Pendientes Activos</th>
                                                <th>Último Pendiente</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Datos se cargan dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 2: Detalle del Paciente -->
                        <div class="tab-pane fade" id="detalle-paciente" role="tabpanel" aria-labelledby="detalle-paciente-tab">
                            <!-- Información del paciente -->
                            <div id="info-paciente" class="card mb-3" style="display: none;">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-user"></i> Información del Paciente</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong>Historia:</strong>
                                            <p id="paciente-historia" class="mb-1"></p>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Documento:</strong>
                                            <p id="paciente-documento" class="mb-1"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Nombre Completo:</strong>
                                            <p id="paciente-nombre" class="mb-1"></p>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Edad:</strong>
                                            <p id="paciente-edad" class="mb-1"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Teléfono:</strong>
                                            <p id="paciente-telefono" class="mb-1"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Dirección:</strong>
                                            <p id="paciente-direccion" class="mb-1"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de selección -->
                            <div id="info-seleccion" class="alert alert-info" style="display: none;">
                                <i class="fas fa-info-circle"></i> <span id="items-seleccionados">0</span> items seleccionados.
                                Complete los datos en la tabla y use los botones de abajo para aplicar los cambios.
                            </div>

                            <!-- Tabla de pendientes del paciente -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-pills"></i> Pendientes del Paciente
                                        <div class="float-right">
                                            <button type="button" id="seleccionar_todos" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-check-square"></i> Seleccionar Todos
                                            </button>
                                            <button type="button" id="deseleccionar_todos" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-square"></i> Deseleccionar Todos
                                            </button>
                                        </div>
                                    </h6>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="tablaPendientesPaciente" class="table table-bordered table-striped table-hover text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="check_all_pendientes">
                                                </th>
                                                <th>Farmacia</th>
                                                <th>Pendiente</th>
                                                <th>Fecha Pendiente</th>
                                                <th>Código</th>
                                                <th>Medicamento</th>
                                                <th>CUMS</th>
                                                <th>Cant. Ordenada</th>
                                                <th>Cant. Entregada</th>
                                                <th>Fecha Entrega</th>
                                                <th>Cant. Pendiente</th>
                                                <th>Estado Actual</th>
                                                <th class="text-center">Nuevo Estado</th>
                                                <th class="text-center doc-entrega-col">Doc Entrega</th>
                                                <th class="text-center factura-entrega-col">Factura Entrega</th>
                                                <th class="text-center">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Datos se cargan dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <div>
                        <button type="button" id="limpiar_seleccion" class="btn btn-warning mr-2" style="display: none;">
                            <i class="fas fa-eraser"></i> Limpiar Selección
                        </button>
                        <button type="button" id="aplicar_cambios_masivos" class="btn btn-success mr-2" style="display: none;">
                            <i class="fas fa-save"></i> Aplicar Cambios Seleccionados
                        </button>
                        <button type="button" id="actualizar_lista_pacientes" class="btn btn-info mr-2" style="display: none;">
                            <i class="fas fa-sync"></i> Actualizar Lista
                        </button>
                        <button type="button" id="volver_lista_pacientes" class="btn btn-primary" style="display: none;">
                            <i class="fas fa-arrow-left"></i> Volver a Lista
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS adicional -->
<style>
    /* Asegurar que la card ocupe todo el espacio del modal */
    .modal-content .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .modal-content .card-body {
        flex: 1;
        overflow-y: auto;
    }

    /* Estilos para checkbox seleccionados */
    .pendiente-selected {
        background-color: #e3f2fd !important;
    }

    /* Estilos para el modo maximizado */
    .modal-content .card.maximized-card {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1060;
        margin: 0;
        width: 100vw;
        height: 100vh;
    }

    /* Estilos para badges de estado */
    .badge-pendiente {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-entregado {
        background-color: #28a745;
    }

    .badge-tramitado {
        background-color: #17a2b8;
    }

    .badge-desabastecido {
        background-color: #fd7e14;
    }

    .badge-anulado {
        background-color: #dc3545;
    }

    .badge-vencido {
        background-color: #6c757d;
    }

    .badge-sincontacto {
        background-color: #88c3f7;
    }

    /* Estilos para los campos de la tabla */
    .estado-select {
        min-width: 140px;
    }

    .observaciones-pendiente {
        min-width: 200px;
        font-size: 12px;
    }

    /* Mejorar el espaciado de la tabla */
    #tablaPendientesPaciente td {
        vertical-align: middle;
        padding: 8px 4px;
    }

    /* Select2 dentro de la tabla */
    .select2-container--default .select2-selection--single {
        height: 31px;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 29px;
        font-size: 14px;
    }
</style>

<script>
    $(document).ready(function() {
        // Manejador específico para este modal solamente
        $('#modalGestionPacientes [data-card-widget="maximize"]').off('click').click(function(e) {
            e.preventDefault();
            const card = $(this).closest('.card');
            const isMaximized = card.hasClass('maximized-card');

            if (isMaximized) {
                card.removeClass('maximized-card');
                $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
            } else {
                card.addClass('maximized-card');
                $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
            }

            // Redimensionar tablas después de cambiar el tamaño
            setTimeout(function() {
                if ($.fn.DataTable.isDataTable('#tablaPacientes')) {
                    $('#tablaPacientes').DataTable().columns.adjust();
                }
                if ($.fn.DataTable.isDataTable('#tablaPendientesPaciente')) {
                    $('#tablaPendientesPaciente').DataTable().columns.adjust();
                }
            }, 300);
        });

        // Manejador específico para collapse de este modal
        $('#modalGestionPacientes [data-card-widget="collapse"]').off('click').click(function(e) {
            e.preventDefault();
            const cardBody = $(this).closest('.card').find('.card-body');
            const isCollapsed = cardBody.is(':hidden');

            if (isCollapsed) {
                cardBody.show();
                $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
            } else {
                cardBody.hide();
                $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
            }
        });

        // Event handler simple para ajuste de tablas al cambiar pestañas
        $('#gestionPacientesTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            // Solo ajustar tablas después del cambio de pestaña
            setTimeout(function() {
                if ($.fn.DataTable.isDataTable('#tablaPacientes')) {
                    $('#tablaPacientes').DataTable().columns.adjust();
                }
                if ($.fn.DataTable.isDataTable('#tablaPendientesPaciente')) {
                    $('#tablaPendientesPaciente').DataTable().columns.adjust();
                }
            }, 100);
        });

        // Redimensionar tablas cuando el modal se muestra completamente
        $('#modalGestionPacientes').on('shown.bs.modal', function() {
            setTimeout(function() {
                if ($.fn.DataTable.isDataTable('#tablaPacientes')) {
                    $('#tablaPacientes').DataTable().columns.adjust();
                }
                if ($.fn.DataTable.isDataTable('#tablaPendientesPaciente')) {
                    $('#tablaPendientesPaciente').DataTable().columns.adjust();
                }
            }, 200);
        });

        // Asegurar que el modal se cierre correctamente
        $('#modalGestionPacientes').on('hidden.bs.modal', function() {
            const card = $(this).find('.card');
            card.removeClass('maximized-card');
            card.find('.card-body').show();
            card.find('[data-card-widget="maximize"] i')
                .removeClass('fa-compress')
                .addClass('fa-expand');
            card.find('[data-card-widget="collapse"] i')
                .removeClass('fa-plus')
                .addClass('fa-minus');
        });
    });
</script>