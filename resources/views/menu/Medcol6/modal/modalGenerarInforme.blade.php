<!-- Modal Generar Informe -->
<div class="modal fade" id="modal_generar_informe" tabindex="-1" role="dialog" aria-labelledby="modal_generar_informe_label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal_generar_informe_label">Generar Informe</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Selección de Fechas -->
                <div class="row">
                    <div class="col-md-4">
                        <label for="modal_fechaini">Fecha Inicial</label>
                        <input type="date" id="modal_fechaini" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="modal_fechafin">Fecha Final</label>
                        <input type="date" id="modal_fechafin" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="modal_contrato">Farmacia</label>
                        <select name="modal_contrato" id="modal_contrato" class="form-control select2bs4" >
                            <option value="">Seleccione opcion...</option>
                            <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                            <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                            <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                            <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                            <option value="EM01">EM01-FARMACIA EMCALI</option>
                            <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                            <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                            <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                            <option value="INY">INY-FARMACIA INYECTABLES</option>
                            <option value="PAC">PAC-FARMACIA PAC</option>
                            <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                            <option value="BPDT">BPDT-BOLSA</option>
                        </select>
                    </div>
                </div>

                <!-- Pestañas -->
                <ul class="nav nav-tabs mt-4" id="informeTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="dispensacion-tab" data-toggle="tab" href="#dispensacion" role="tab" aria-controls="dispensacion" aria-selected="true">
                            Informe Dispensación
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="forgif-tab" data-toggle="tab" href="#forgif" role="tab" aria-controls="forgif" aria-selected="false">
                            Informe ForGif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="insumos-tab" data-toggle="tab" href="#insumos" role="tab" aria-controls="insumos" aria-selected="false">
                            Informe Insumos
                        </a>
                    </li>
                </ul>

                <!-- Contenido de las pestañas -->
                <div class="tab-content mt-3" id="informeTabsContent">
                    <!-- Pestaña 1: Informe Dispensación -->
                    <div class="tab-pane fade show active" id="dispensacion" role="tabpanel" aria-labelledby="dispensacion-tab">
                        <div id="resultado_informe" class="mt-3" style="display: none;">
                            <h5 class="text-center text-dark">Resultados del Informe</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card shadow-sm border-primary">
                                        <div class="card-header bg-primary text-white text-center">
                                            Pendientes por Revisar
                                        </div>
                                        <div class="card-body" id="detalle_informe"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow-sm border-success">
                                        <div class="card-header bg-success text-white text-center">
                                            Revisadas
                                        </div>
                                        <div class="card-body" id="detalle_informe1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña 2: Informe ForGif -->
                    <div class="tab-pane fade" id="forgif" role="tabpanel" aria-labelledby="forgif-tab">
                        <h5 class="text-center text-dark">Contenido del Informe FOR_GIF_003</h5>
                        <div class="card-body table-responsive">
                            <table id="tablaForgif" class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>NIT del prestador</th>
                                        <th>Prestador</th>
                                        <th>Código Genérico EPS Comfenalco</th>
                                        <th>Código Expediente</th>
                                        <th>Código comercial proveedor</th>
                                        <th>Nombre genérico</th>
                                        <th>Nombre comercial</th>
                                        <th>Unidad mínima de dispensación</th>
                                        <th>Valor Unitario</th>
                                        <th>CUM</th>
                                        <th>Modalidad de contratación</th>
                                        <th>Registro Sanitario (INVIMA)</th>
                                        <th>OPCIÓN</th>
                                        <th>PBS/ NO PBS/ PBS Condicionado</th>
                                        <th>REGULADO</th>
                                        <th>Categoría / Medicamento / Insumo / Alimento</th>
                                        <th>Forma Farmacéutica</th>
                                        <th>Tarifa Tope de regulado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se agregarán las filas de la tabla dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Pestaña 3: Informe Insumos -->
                    <div class="tab-pane fade" id="insumos" role="tabpanel" aria-labelledby="insumos-tab">
                        <h5 class="text-center text-dark">Contenido del Informe Insumos</h5>
                        <p class="text-center">Aquí va el contenido específico del Informe Insumos.</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-primary" id="ejecutar_informe">
                    <i class="fas fa-chart-bar"></i> Ejecutar Informe
                </button>
            </div>
        </div>
    </div>
</div>