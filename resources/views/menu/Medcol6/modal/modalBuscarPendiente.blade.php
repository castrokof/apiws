{{-- Modal: Buscar Pendiente por Documento / Factura --}}
{{-- NOTA: El JavaScript está en @section('scriptsPlugins') de indexAnalista.blade.php
     porque jQuery carga DESPUÉS de @yield('content') en el layout. --}}
<div class="modal fade" id="modal-buscar-pendiente" tabindex="-1" role="dialog"
     aria-labelledby="modalBuscarPendienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content card">

            {{-- Header --}}
            <div class="modal-header card-header">
                <h5 class="modal-title" id="modalBuscarPendienteLabel">
                    <i class="fas fa-search mr-2"></i>Buscar Pendiente por Documento / Factura
                </h5>
                <div class="card-tools ml-auto">
                    <button type="button" class="btn btn-tool" id="btn-maximizar-buscar">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-dismiss="modal" aria-label="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body modal-body" style="max-height:85vh; overflow-y:auto;">

                {{-- Barra de búsqueda --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-body py-2">
                        <div class="row align-items-end">
                            <div class="col-md-7">
                                <label class="small font-weight-bold mb-1">
                                    <i class="fas fa-barcode mr-1 text-primary"></i>
                                    Documento del Paciente o Número de Factura
                                </label>
                                <div class="input-group">
                                    <input type="text" id="buscar-doc-factura-input"
                                           class="form-control"
                                           placeholder="Ej: MPE131270  ó  documento  ó  factura"
                                           maxlength="100" autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-ejecutar-busqueda-pendiente"
                                                class="btn btn-primary">
                                            <i class="fas fa-search mr-1"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Busca por <strong>Orden Externa</strong> (ej: MPE131270), documento del paciente o número de factura.</small>
                            </div>
                            <div class="col-md-5 text-right">
                                <span id="bp-total-badge" class="badge badge-info" style="display:none; font-size:13px;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Loading --}}
                <div id="bp-loading" class="text-center py-4" style="display:none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Buscando...</span>
                    </div>
                    <p class="mt-2 text-muted">Consultando pendientes...</p>
                </div>

                {{-- Mensaje sin resultados --}}
                <div id="bp-sin-resultados" class="alert alert-warning" style="display:none;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span id="bp-sin-resultados-msg"></span>
                </div>

                {{-- Resultados --}}
                <div id="bp-resultados" style="display:none;">

                    {{-- Encabezado: Información del Paciente --}}
                    <div class="card mb-3 border-primary">
                        <div class="card-header py-2" style="background: linear-gradient(135deg,#667eea,#764ba2); color:#fff;">
                            <i class="fas fa-user-circle mr-2"></i><strong>Información del Paciente</strong>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <small class="text-muted d-block">Historia</small>
                                    <strong id="bp-pac-historia"></strong>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <small class="text-muted d-block">Documento</small>
                                    <strong id="bp-pac-documento"></strong>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <small class="text-muted d-block">Nombre Completo</small>
                                    <strong id="bp-pac-nombre"></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Encabezado: Información de Contacto --}}
                    <div class="card mb-3 border-success">
                        <div class="card-header py-2" style="background: linear-gradient(135deg,#10b981,#059669); color:#fff;">
                            <i class="fas fa-phone-alt mr-2"></i><strong>Información de Contacto</strong>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <small class="text-muted d-block">Teléfono</small>
                                    <strong id="bp-pac-telefono"></strong>
                                </div>
                                <div class="col-md-5 col-sm-6">
                                    <small class="text-muted d-block">Dirección</small>
                                    <strong id="bp-pac-direccion"></strong>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <small class="text-muted d-block">Municipio</small>
                                    <strong id="bp-pac-municipio"></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Controles de selección --}}
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                        <div>
                            <button type="button" id="bp-seleccionar-todos" class="btn btn-outline-primary btn-sm mr-1">
                                <i class="fas fa-check-square mr-1"></i> Seleccionar todos
                            </button>
                            <button type="button" id="bp-deseleccionar-todos" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-square mr-1"></i> Deseleccionar todos
                            </button>
                        </div>
                        <div>
                            <span id="bp-contador-sel" class="badge badge-info mr-2">0 seleccionados</span>
                            <button type="button" id="bp-btn-guardar" class="btn btn-success btn-sm" disabled>
                                <i class="fas fa-save mr-1"></i> Guardar Seleccionados
                            </button>
                        </div>
                    </div>

                    {{-- Tabla de medicamentos --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="bp-tabla-items" style="min-width:1400px;">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:40px; text-align:center;">
                                        <input type="checkbox" id="bp-check-all" title="Seleccionar/Deseleccionar todos">
                                    </th>
                                    <th>Código</th>
                                    <th>Medicamento</th>
                                    <th style="width:65px;">Cant. Ord.</th>
                                    <th style="width:90px;">Cant. Entregada</th>
                                    <th style="width:140px;">Estado</th>
                                    <th style="width:130px;">Fecha</th>
                                    <th style="width:120px;">Factura Entrega</th>
                                    <th style="width:160px;">Observaciones</th>
                                    <th style="width:120px;">Nro. Fórmula <small class="d-block text-warning" style="font-weight:normal;">(Res. 1604)</small></th>
                                    <th style="width:120px;">Fecha Ordenamiento <small class="d-block text-warning" style="font-weight:normal;">(Res. 1604)</small></th>
                                    <th style="width:140px;">Frec. Administ. <small class="d-block text-warning" style="font-weight:normal;">(Res. 1604)</small></th>
                                    <th style="width:130px;">Duración Trat. <small class="d-block text-warning" style="font-weight:normal;">(Res. 1604)</small></th>
                                </tr>
                            </thead>
                            <tbody id="bp-tabla-body">
                            </tbody>
                        </table>
                    </div>

                    {{-- Botón guardar inferior --}}
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" id="bp-btn-guardar-bottom" class="btn btn-success" disabled>
                            <i class="fas fa-save mr-1"></i> Guardar Seleccionados
                        </button>
                    </div>

                </div>{{-- /bp-resultados --}}

            </div>{{-- /card-body --}}
        </div>{{-- /modal-content --}}
    </div>{{-- /modal-dialog --}}
</div>{{-- /modal --}}

<style>
    #modal-buscar-pendiente .bp-row-input {
        font-size: 12px;
        padding: 3px 6px;
        height: auto;
    }
    #modal-buscar-pendiente .bp-estado-select {
        font-size: 12px;
        padding: 3px 6px;
        height: auto;
    }
    #modal-buscar-pendiente td {
        vertical-align: middle;
    }
    #modal-buscar-pendiente .estado-PENDIENTE     { background-color: #fff3cd; color: #856404; }
    #modal-buscar-pendiente .estado-ENTREGADO     { background-color: #d1e7dd; color: #0f5132; }
    #modal-buscar-pendiente .estado-TRAMITADO     { background-color: #cff4fc; color: #055160; }
    #modal-buscar-pendiente .estado-DESABASTECIDO { background-color: #f8d7da; color: #842029; }
    #modal-buscar-pendiente .estado-ANULADO       { background-color: #e2e3e5; color: #383d41; }
    #modal-buscar-pendiente .estado-VENCIDO       { background-color: #ffe5d0; color: #7b3f00; }
    #modal-buscar-pendiente .bp-row-disabled td   { opacity: 0.55; }
</style>
