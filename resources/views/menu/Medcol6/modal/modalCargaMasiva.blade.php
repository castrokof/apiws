<div class="modal fade" id="modalCargaMasiva" tabindex="-1" role="dialog" aria-labelledby="modalCargaMasivaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalCargaMasivaLabel">
                    <i class="fas fa-file-upload mr-2"></i>Carga Masiva de Entregas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Instrucciones -->
                <div class="alert alert-info alert-sm mb-3">
                    <strong><i class="fas fa-info-circle mr-1"></i>Formato del archivo requerido:</strong>
                    <ul class="mb-0 mt-1 pl-3">
                        <li>Formato: <strong>Excel (.xlsx, .xls) o CSV (.csv)</strong></li>
                        <li>La primera fila debe ser el encabezado con las columnas:
                            <code>documento</code>, <code>factura</code>, <code>codigo</code>,
                            <code>fecha_entrega</code>, <code>dispensacion</code>
                        </li>
                        <li><code>dispensacion</code>: texto como <em>FP001234</em> (letras → doc_entrega, números → factura_entrega)</li>
                    </ul>
                </div>

                <!-- Fase 1: Cargar archivo -->
                <div class="card mb-3" id="panelFase1">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><span class="badge badge-primary mr-2">Fase 1</span>Actualización de Entregas</h6>
                    </div>
                    <div class="card-body">
                        <form id="formCargaMasiva" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="archivoCargaMasiva" class="font-weight-bold">Seleccionar archivo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="archivoCargaMasiva"
                                               name="archivo" accept=".xlsx,.xls,.csv">
                                        <label class="custom-file-label" for="archivoCargaMasiva">Elegir archivo...</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="btnProcesarFase1">
                                            <i class="fas fa-play mr-1"></i> Procesar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div id="loadingFase1" class="text-center py-3" style="display:none;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <span class="ml-2">Procesando archivo, por favor espere...</span>
                        </div>
                    </div>
                </div>

                <!-- Resultados Fase 1 -->
                <div id="resultadosFase1" style="display:none;">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-chart-bar mr-1"></i>Resultados Fase 1</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Actualizados</span>
                                            <span class="info-box-number" id="cnt-procesados">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-search-minus"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">No encontrados</span>
                                            <span class="info-box-number" id="cnt-no-encontrados">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Errores</span>
                                            <span class="info-box-number" id="cnt-errores">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="detalle-no-encontrados" style="display:none;" class="mt-2">
                                <strong class="text-warning">Registros no encontrados:</strong>
                                <ul id="lista-no-encontrados" class="small text-muted mt-1" style="max-height:120px;overflow-y:auto;"></ul>
                            </div>
                            <div id="detalle-errores-fase1" style="display:none;" class="mt-2">
                                <strong class="text-danger">Errores:</strong>
                                <ul id="lista-errores-fase1" class="small text-muted mt-1" style="max-height:120px;overflow-y:auto;"></ul>
                            </div>
                        </div>
                    </div>

                    <!-- Fase 2 -->
                    <div class="card" id="panelFase2" style="display:none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><span class="badge badge-info mr-2">Fase 2</span>Registro de Observaciones</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">
                                Se crearán observaciones automáticas para los <strong id="cnt-para-fase2">0</strong> registro(s) actualizados en la Fase 1.
                            </p>
                            <button type="button" id="btnProcesarFase2" class="btn btn-info">
                                <i class="fas fa-comment-dots mr-1"></i> Registrar Observaciones
                            </button>
                            <div id="loadingFase2" class="d-inline-block ml-3" style="display:none;">
                                <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                                <span class="ml-1">Registrando...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Resultado Fase 2 -->
                    <div id="resultadosFase2" style="display:none;" class="mt-3">
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span id="msgFase2"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- JS gestionado en indexAnalista.blade.php dentro del $(document).ready() principal --}}
