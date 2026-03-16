<!-- Modal Validar Integridad de Datos -->
<div class="modal fade" id="modal_validar_integridad" tabindex="-1" role="dialog" aria-labelledby="modal_validar_integridad_label">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="card card-warning" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                <div class="card-header bg-warning text-white d-flex align-items-center justify-content-between">
                    <h5 class="modal-title mb-0 flex-grow-1" id="modal_validar_integridad_label">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Validación de Integridad de Datos
                    </h5>
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

                    <!-- Filtros de fecha -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="vi_fechaini" class="font-weight-bold">
                                <i class="far fa-calendar-alt text-primary mr-1"></i> Fecha Inicial
                            </label>
                            <input type="date" id="vi_fechaini" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="vi_fechafin" class="font-weight-bold">
                                <i class="far fa-calendar-check text-primary mr-1"></i> Fecha Final
                            </label>
                            <input type="date" id="vi_fechafin" class="form-control">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" id="btn_ejecutar_validacion" class="btn btn-warning btn-block">
                                <i class="fas fa-search-plus mr-1"></i> Ejecutar Validación
                            </button>
                        </div>
                    </div>

                    <!-- Indicador de carga -->
                    <div id="vi_loading" style="display:none;" class="text-center my-4">
                        <div class="spinner-border text-warning" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Analizando registros, por favor espere...</p>
                    </div>

                    <!-- Resumen de resultados -->
                    <div id="vi_resumen" style="display:none;">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="info-box shadow-sm">
                                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Pacientes Analizados</span>
                                        <span class="info-box-number" id="vi_total_analizados">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box shadow-sm">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Con Inconsistencias</span>
                                        <span class="info-box-number" id="vi_total_inconsistencias">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box shadow-sm">
                                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Sin Inconsistencias</span>
                                        <span class="info-box-number" id="vi_total_correctos">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Referencia de campos validados -->
                        <div class="alert alert-light border mb-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle text-info mr-1"></i>
                                <strong>Campos validados:</strong>
                                <span class="badge badge-secondary mx-1">Tipo Documento</span>
                                <span class="badge badge-primary mx-1">Régimen (RS/RC)</span>
                                <span class="badge badge-info mx-1">Nivel / Cobertura</span>
                                &mdash; Se detectan pacientes con valores distintos en el mismo campo entre sus facturas.
                            </small>
                        </div>

                        <!-- Tabla de resultados -->
                        <div class="table-responsive">
                            <table id="tabla_validacion_integridad" class="table table-bordered table-hover table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Historia / Documento</th>
                                        <th>Paciente</th>
                                        <th class="text-center">Total Facturas</th>
                                        <th class="text-center">Registros Inconsistentes</th>
                                        <th>Tipo Inconsistencia</th>
                                        <th>Tipo Doc. Encontrados</th>
                                        <th>Regímenes Encontrados</th>
                                        <th>Niveles Encontrados</th>
                                    </tr>
                                </thead>
                                <tbody id="vi_tbody">
                                </tbody>
                            </table>
                        </div>

                        <!-- Mensaje sin resultados -->
                        <div id="vi_sin_resultados" style="display:none;" class="alert alert-success text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <p class="mb-0"><strong>¡Sin inconsistencias!</strong> Todos los pacientes del período tienen datos consistentes.</p>
                        </div>
                    </div>

                </div>

                <div class="card-footer modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                    <button type="button" id="btn_exportar_validacion" class="btn btn-success" style="display:none;">
                        <i class="fas fa-file-excel mr-1"></i> Exportar CSV
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #modal_validar_integridad .modal-content .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    #modal_validar_integridad .modal-content .card-body {
        flex: 1;
        overflow-y: auto;
    }
    #modal_validar_integridad .modal-content .card.maximized-card {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 1060;
        margin: 0;
        width: 100vw;
        height: 100vh;
    }
    #tabla_validacion_integridad td { vertical-align: middle; font-size: 0.85rem; }
    .badge-inconsistencia { font-size: 0.8rem; }
</style>

{{-- El JavaScript de este modal se encuentra en indexDispensado.blade.php (@section scriptsPlugins) --}}
