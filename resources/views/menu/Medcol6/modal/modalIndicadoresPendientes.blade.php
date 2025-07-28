<!-- Modal Informe de Indicadores -->
<div class="modal fade" id="modalIndicadores" tabindex="-1" role="dialog" aria-labelledby="modalIndicadores_label">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Estructura de Card de AdminLTE para que funcionen los botones -->
            <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <h5 class="modal-title mb-0 flex-grow-1" id="modalIndicadores_label">Indicadores de Pendientes</h5>
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
                                <!-- <option value="">Seleccione opción...</option> -->
                                <option value="Todos">Todas</option> <!-- Nueva opción -->
                                <optgroup label="Farmacias Principales">
                                    <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                    <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                                    <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                    <option value="EM01">EM01-FARMACIA EMCALI</option>
                                    <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                    <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                    <option value="FRIO">FRIO-FARMACIA IDEO</option>
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
                            <a class="nav-link active" id="pendientes-tab" data-toggle="tab" href="#pendientes" role="tab" aria-controls="pendientes" aria-selected="true">
                                Informe Pendientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="detallependientes-tab" data-toggle="tab" href="#detallependientes" role="tab" aria-controls="detallependientes" aria-selected="false">
                                Detalle Pendientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pendientesconsaldos-tab" data-toggle="tab" href="#pendientesconsaldos" role="tab" aria-controls="pendientesconsaldos" aria-selected="false">
                                Pendientes vs Saldos
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" id="forgif-tab" data-toggle="tab" href="#forgif" role="tab" aria-controls="forgif" aria-selected="false">
                                Informe ForGif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sicmedic-tab" data-toggle="tab" href="#sicmedic" role="tab" aria-controls="sicmedic" aria-selected="false">
                                Informe Medicamentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="insumos-tab" data-toggle="tab" href="#insumos" role="tab" aria-controls="insumos" aria-selected="false">
                                Informe Insumos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="multiples-tab" data-toggle="tab" href="#multiples" role="tab" aria-controls="multiples" aria-selected="false">
                                Dispensación Multiple
                            </a>
                        </li> -->
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content mt-3" id="informeTabsContent">
                        <!-- Pestaña 1: Informe Dispensación -->
                        <div class="tab-pane fade show active" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                            <div id="resultado_informe" class="mt-4" style="display: none;">
                                <h4 class="mb-4 text-center font-weight-bold">Resumen Indicador Pendientes</h4>
                                <!-- Nueva fila para el TOTAL general -->
                                <div class="row mb-4">
                                    <div class="col-md-6 mx-auto">
                                        <div class="card border-0 rounded-lg shadow-sm hover-shadow transition-all">
                                            <div class="card-header bg-purple text-white p-3 rounded-top d-flex align-items-center">
                                                <i class="fas fa-chart-bar me-2"></i>
                                                <h5 class="m-0 text-center flex-grow-1">TOTAL</h5>
                                            </div>
                                            <div class="card-body p-4" id="detalle_informe_total">
                                                <!-- Este elemento se llenará con el total_pendientes_entregados -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <!-- Card: Total Pendientes -->
                                    <div class="col-md-4">
                                        <div class="card h-100 border-0 rounded-lg shadow-sm hover-shadow transition-all">
                                            <div class="card-header bg-primary text-white p-3 rounded-top d-flex align-items-center">
                                                <i class="fas fa-clipboard-list me-2"></i>
                                                <h5 class="m-0 text-center flex-grow-1">TOTAL PENDIENTES</h5>
                                            </div>
                                            <div class="card-body p-4" id="detalle_informe">
                                                <!-- Contenido dinámico -->
                                                <div class="d-flex align-items-center justify-content-center placeholder-content">
                                                    <div class="text-center">
                                                        <span class="display-4 fw-bold">0</span>
                                                        <p class="text-muted mt-2 mb-0">Pendientes actualmente</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 text-end">
                                                <button class="btn btn-sm btn-outline-primary">Ver detalles</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card: Entregados en 48H -->
                                    <div class="col-md-4">
                                        <div class="card h-100 border-0 rounded-lg shadow-sm hover-shadow transition-all">
                                            <div class="card-header bg-success text-white p-3 rounded-top d-flex align-items-center">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <h5 class="m-0 text-center flex-grow-1">ENTREGADOS EN 48H</h5>
                                            </div>
                                            <div class="card-body p-4" id="detalle_informe1">
                                                <!-- Contenido dinámico -->
                                                <div class="d-flex align-items-center justify-content-center placeholder-content">
                                                    <div class="text-center">
                                                        <span class="display-4 fw-bold">0</span>
                                                        <p class="text-muted mt-2 mb-0">Entregas recientes</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 text-end">
                                                <button class="btn btn-sm btn-outline-success">Ver detalles</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card: Anulados -->
                                    <div class="col-md-4">
                                        <div class="card h-100 border-0 rounded-lg shadow-sm hover-shadow transition-all">
                                            <div class="card-header bg-danger text-white p-3 rounded-top d-flex align-items-center">
                                                <i class="fas fa-times-circle me-2"></i>
                                                <h5 class="m-0 text-center flex-grow-1">ANULADOS</h5>
                                            </div>
                                            <div class="card-body p-4" id="detalle_informe2">
                                                <!-- Contenido dinámico -->
                                                <div class="d-flex align-items-center justify-content-center placeholder-content">
                                                    <div class="text-center">
                                                        <span class="display-4 fw-bold">0</span>
                                                        <p class="text-muted mt-2 mb-0">Anulaciones registradas</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 text-end">
                                                <button class="btn btn-sm btn-outline-danger">Ver detalles</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 2: Detalle Pendientes -->
                        <div class="tab-pane fade" id="detallependientes" role="tabpanel" aria-labelledby="detallependientes-tab">
                            <h5 class="text-center text-dark">Informe Detalle Pendientes</h5>

                            <!-- Botón de exportación a Excel -->
                            <div class="row mb-3">
                                <div class="col-12 text-right">
                                    <button type="button" id="exportar_excel" class="btn btn-success" style="display: none;" title="Exportar reporte completo a Excel">
                                        <i class="fas fa-file-excel"></i> Exportar a Excel
                                    </button>
                                </div>
                            </div>

                            <!-- Contenedor para medicamentos por farmacia -->
                            <div id="detalle_medicamentos_farmacia" class="mb-3"></div>

                            <div class="card-body table-responsive">
                                <table id="tablaDetPend" class="table table-bordered table-striped table-hover text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th rowspan="2" class="align-middle">Molecula/Insumo</th>
                                            <th colspan="13">Cantidad Pendiente por Farmacia</th>
                                            <th rowspan="2" class="align-middle">Total</th>
                                        </tr>
                                        <tr>
                                            <th>BIO1</th>
                                            <th>DLR1</th>
                                            <th>DPA1</th>
                                            <th>EM01</th>
                                            <th>EHU1</th>
                                            <th>FRJA</th>
                                            <th>FRIO</th>
                                            <th>INY</th>
                                            <th>PAC</th>
                                            <th>SM01</th>
                                            <th>BPDT</th>
                                            <th>EVEN</th>
                                            <th>EVSM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Filas dinámicas se llenan por JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pestaña 3: Pendientes vs Saldos -->
                        <div class="tab-pane fade" id="pendientesconsaldos" role="tabpanel" aria-labelledby="pendientesconsaldos-tab">
                            <h5 class="text-center text-dark">Informe Detallado: Medicamentos Pendientes vs Saldos</h5>
                            
                            <!-- Botón de exportación a Excel -->
                            <div class="row mb-3">
                                <div class="col-12 text-right">
                                    <button type="button" id="exportar_excel_saldos" class="btn btn-success" style="display: none;" title="Exportar reporte completo a Excel">
                                        <i class="fas fa-file-excel"></i> Exportar a Excel
                                    </button>
                                </div>
                            </div>

                            <!-- Resumen de estadísticas -->
                            <div id="resumen_saldos" class="mb-3" style="display: none;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h4 id="con_saldo_count">0</h4>
                                                <p class="mb-0">Con Saldo</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h4 id="saldo_parcial_count">0</h4>
                                                <p class="mb-0">Saldo Parcial</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body text-center">
                                                <h4 id="sin_saldo_count">0</h4>
                                                <p class="mb-0">Sin Saldo</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h4 id="total_medicamentos">0</h4>
                                                <p class="mb-0">Total Medicamentos</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive">
                                <table id="tablaPendSald" class="table table-striped table-hover table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Fecha Pendiente</th>
                                            <th>Farmacia</th>
                                            <th>Código</th>
                                            <th>Medicamento</th>
                                            <th>CUMS</th>
                                            <th class="text-center">Cant. Pendiente</th>
                                            <th class="text-center">Saldo Disponible</th>
                                            <th class="text-center">Estado Saldo</th>
                                            <th class="text-center">Comparación</th>
                                            <th>Fecha Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se agregarán las filas de la tabla dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Pestaña 4: Informe ForGif -->
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

                        <!-- Pestaña 5: Informe Medicamentos -->
                        <div class="tab-pane fade" id="sicmedic" role="tabpanel" aria-labelledby="sicmedic-tab">
                            <h5 class="text-center text-dark">Contenido del Informe Medicamentos</h5>
                            <p class="text-center">Aquí va el contenido específico del Informe SIC-Medicamentos.</p>
                        </div>

                        <!-- Pestaña 6: Informe Insumos -->
                        <div class="tab-pane fade" id="insumos" role="tabpanel" aria-labelledby="insumos-tab">
                            <h5 class="text-center text-dark">Contenido del Informe Insumos</h5>
                            <p class="text-center">Aquí va el contenido específico del Informe Insumos.</p>
                        </div>

                        <!-- Pestaña 7: Dispensación Multiple -->
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
                    <button type="submit" class="btn btn-primary" id="generar_informe">
                        <i class="fas fa-chart-bar"></i> Generar Informe
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
        // Manejar el botón de maximizar
        $('[data-card-widget="maximize"]').click(function(e) {
            e.preventDefault();
            const card = $(this).closest('.card');
            card.toggleClass('maximized-card');

            // Cambiar ícono
            $(this).find('i')
                .toggleClass('fa-expand')
                .toggleClass('fa-compress');
        });

        // Asegurar que el modal se cierre correctamente
        $('#modalIndicadores').on('hidden.bs.modal', function() {
            $(this).find('.card').removeClass('maximized-card');
            $(this).find('[data-card-widget="maximize"] i')
                .removeClass('fa-compress')
                .addClass('fa-expand');
        });
    });
</script>

