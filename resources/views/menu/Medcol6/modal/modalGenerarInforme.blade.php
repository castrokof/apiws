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
                    <div class="col-md-6">
                        <label for="modal_fechaini">Fecha Inicial</label>
                        <input type="date" id="modal_fechaini" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="modal_fechafin">Fecha Final</label>
                        <input type="date" id="modal_fechafin" class="form-control">
                    </div>
                </div>

                <!-- Contenedor de resultados -->
                <div id="resultado_informe" class="mt-6" style="display: none;">
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
