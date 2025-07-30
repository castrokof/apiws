<!-- Modal Generar Informe -->
<div class="modal fade" id="modal_generar_informe" tabindex="-1" role="dialog" aria-labelledby="modal_generar_informe_label">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Estructura de Card de AdminLTE para que funcionen los botones -->
            <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <h5 class="modal-title mb-0 flex-grow-1" id="modal_generar_informe_label">Generar Informe</h5>
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
                    <!-- Contenido del modal (se mantiene igual) -->
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
                            <select name="modal_contrato" id="modal_contrato" class="form-control select2bs4">
                                <option value="">Seleccione opción...</option>
                                <optgroup label="Farmacias Principales">
                                    <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                    <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                                    <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                    <option value="EM01">EM01-FARMACIA EMCALI</option>
                                    <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                    <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                    <option value="INY">INY-FARMACIA INYECTABLES</option>
                                    <option value="PAC">PAC-FARMACIA PAC</option>
                                    <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                                </optgroup>
                                <optgroup label="Farmacias Especializadas">
                                    <option value="BPDT">BPDT-BOLSA</option>
                                    <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                    <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- Pestañas -->
                    <ul class="nav nav-tabs mt-4" id="informeTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="dispensacion-tab" data-toggle="tab" href="#dispensacion" 
                            role="tab" aria-controls="dispensacion" aria-selected="true">
                                Informe Dispensación
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="forgif-tab" data-toggle="tab" href="#forgif" 
                            role="tab" aria-controls="forgif" aria-selected="false">
                                Informe ForGif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sicmedic-tab" data-toggle="tab" href="#sicmedic" 
                            role="tab" aria-controls="sicmedic" aria-selected="false">
                                Informe Medicamentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="insumos-tab" data-toggle="tab" href="#insumos" 
                            role="tab" aria-controls="insumos" aria-selected="false">
                                Informe Insumos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="multiples-tab" data-toggle="tab" href="#multiples" 
                            role="tab" aria-controls="multiples" aria-selected="false">
                                Dispensación Multiple
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
                                    <div class="col-md-4">
                                        <div class="card shadow-sm border-primary">
                                            <div class="card-header bg-primary text-white text-center">
                                                Pendientes por Revisar
                                            </div>
                                            <div class="card-body" id="detalle_informe"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-sm border-success">
                                            <div class="card-header bg-success text-white text-center">
                                                Revisadas
                                            </div>
                                            <div class="card-body" id="detalle_informe1"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-sm border-danger">
                                            <div class="card-header bg-danger text-white text-center">
                                                Anulados
                                            </div>
                                            <div class="card-body" id="detalle_informe2"></div>
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

                        <!-- Pestaña 3: Informe Medicamentos -->
                        <div class="tab-pane fade" id="sicmedic" role="tabpanel" aria-labelledby="sicmedic-tab">
                            <h5 class="text-center text-dark">Contenido del Informe Medicamentos</h5>
                            <p class="text-center">Aquí va el contenido específico del Informe SIC-Medicamentos.</p>
                        </div>

                        <!-- Pestaña 4: Informe Insumos -->
                        <div class="tab-pane fade" id="insumos" role="tabpanel" aria-labelledby="insumos-tab">
                            <h5 class="text-center text-dark">Contenido del Informe Insumos</h5>
                            <p class="text-center">Aquí va el contenido específico del Informe Insumos.</p>
                        </div>

                        <!-- Pestaña 5: Dispensación Multiple -->
                        <div class="tab-pane fade" id="multiples" role="tabpanel" aria-labelledby="multiples-tab">
                            <h5 class="text-center text-dark">Contenido del Informe Dispensacion Multiple</h5>
                            <p class="text-center">Aquí va el contenido específico del Informe Dispensacion Multiple.</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer modal-footer d-flex justify-content-between">
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
</div>

<!-- CSS adicional para asegurar el correcto funcionamiento -->
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
</style>

<script>
    $(document).ready(function() {
        // Manejador específico para este modal solamente
        $('#modal_generar_informe [data-card-widget="maximize"]').off('click').click(function(e) {
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
        });

        // Manejador específico para collapse de este modal
        $('#modal_generar_informe [data-card-widget="collapse"]').off('click').click(function(e) {
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

        // Asegurar que el modal se cierre correctamente
        $('#modal_generar_informe').on('hidden.bs.modal', function() {
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