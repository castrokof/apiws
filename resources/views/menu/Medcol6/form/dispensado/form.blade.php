<div class="container-fluid">
    <!-- Sección de búsqueda optimizada -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Búsqueda de Factura</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                        <input type="text" name="numero_factura" id="numero_factura" class="form-control"
                            placeholder="Ingrese el número de factura" aria-label="Número de Factura">
                        <button type="button" class="btn btn-primary" id="buscarFactura">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la factura y paciente en cards -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Información de Factura y Paciente</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="factura" class="form-label">Número de Factura</label>
                        <input type="text" class="form-control bg-light" id="factura" name="factura" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tipodocument" class="form-label">Tipo de Documento</label>
                        <input type="text" class="form-control bg-light" id="tipodocument" name="tipodocument" readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="regimen" class="form-label">Régimen</label>
                        <input type="text" class="form-control bg-light" id="regimen" name="regimen" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="idusuario" class="form-label">Historia</label>
                        <input type="text" class="form-control bg-light" id="idusuario" name="idusuario" readonly>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="fecha_suministro" class="form-label">Fecha Dispensación</label>
                        <input type="date" class="form-control bg-light" id="fecha_suministro" name="fecha_suministro" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="paciente" class="form-label">Paciente</label>
                        <input type="text" class="form-control bg-light" id="paciente" name="paciente" readonly>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="drogueria" class="form-label">Droguería - Sede</label>
                        <input type="text" class="form-control bg-light" id="drogueria" name="drogueria" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cajero" class="form-label">Auxiliar Dispenso</label>
                        <input type="text" class="form-control bg-light" id="cajero" name="cajero" readonly>
                    </div>

                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="estado2" class="form-label">Estado</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" id="estado2" name="estado2" readonly>
                            <span class="input-group-text" id="estado-indicator">
                                <i class="fas fa-circle text-success d-none" id="estado-activo"></i>
                                <i class="fas fa-circle text-danger d-none" id="estado-inactivo"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de completar la dispensación de la fórmula -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Completar la dispensación de la Fórmula</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="fecha_orden" class="form-label">Fecha Ordenamiento</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" class="form-control" id="fecha_orden" name="fecha_orden">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="entrega" class="form-label">Entrega</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="numero_entrega1" name="numero_entrega1" placeholder="1" min="1">
                            <span class="input-group-text">de</span>
                            <input type="number" class="form-control" id="num_total_entregas" name="num_total_entregas" placeholder="2" min="1">
                        </div>
                        <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Indique el número de esta entrega y el total de entregas.</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="diagnostico" class="form-label">Diagnóstico</label>
                        <select name="diagnostico" id="diagnostico" class="form-select" required>
                            <option value="" disabled selected>Seleccione diagnóstico</option>
                            <!-- Opciones se cargarán dinámicamente -->
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="ips" class="form-label">IPS Formulación</label>
                        <select name="ips" id="ips" class="form-select" required>
                            <option value="" disabled selected>Seleccione IPS</option>
                            <!-- Opciones se cargarán dinámicamente -->
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="formula1" class="form-label">No. de Formula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-prescription"></i></span>
                            <input type="number" class="form-control" id="formula1" name="formula1" required min="1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor de tabla para medicamentos -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Medicamentos/Insumos</h5>
            <div>
                <button type="button" class="btn btn-sm btn-success" id="guardarSeleccionados">
                    <i class="fas fa-save"></i> Guardar Seleccionados
                </button>
                <button type="button" class="btn btn-sm btn-secondary" id="imprimirFormula">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table id="tablaRegistros" class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selector">
                                <label class="form-check-label" for="selector">Seleccionar</label>
                            </div>
                        </th>
                        <th>Código</th>
                        <th>Nombre Genérico</th>
                        <th>Cantidad</th>
                        <th>$ Unitario</th>
                        <th>$ Total</th>
                        <th>Frecuencia</th>
                        <th>Dosis</th>
                        <th>Duración</th>
                        <th>Cuota Mod.</th>
                        <th>Autorización</th>
                        <th>MIPRES</th>
                        <th>Reporte</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se agregarán las filas de la tabla dinámicamente -->
                </tbody>
                <!-- <tfoot class="table-light">
                    <tr>
                        <th colspan="5" class="text-end">Totales:</th>
                        <th id="total-precio">0.00</th>
                        <th colspan="3"></th>
                        <th id="total-cuota">0.00</th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot> -->
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <!-- <div>
                    <span class="badge bg-primary">Total items: <span id="total-items">0</span></span>
                </div> -->
                <div>
                    <button type="button" class="btn btn-primary" id="finalizar">
                        <i class="fas fa-check-circle"></i> Finalizar Dispensación
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>