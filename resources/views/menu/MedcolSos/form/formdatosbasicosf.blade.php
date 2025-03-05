<!-- Campo para Consultar Datos -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Datos a Consultar</h5>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="tipoDocId" class="form-label"><i class="fas fa-id-card"></i> Tipo de Documento</label>
                <select name="tipoDocId" id="tipoDocId" class="form-control select2bs4" style="width: 100%;" required>
                    <!-- Opciones dinámicas -->
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="numeroDocId" class="form-label"><i class="fas fa-id-badge"></i> Documento</label>
                <input type="number" name="numeroDocId" id="numeroDocId" class="form-control" value="{{ old('numeroDocId') }}" required>
            </div>
            <!--<div class="col-md-4 mb-3 align-self-end">
                <button id="guardar_entrada" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Consultar Datos
                </button>
            </div>-->
        </div>
    </div>
</div>

<!-- Sección de Resultados -->
<div id="resultado-consulta" class="d-none">
    <!-- Datos del Afiliado -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Datos del Afiliado</h5>
        </div>
        <div class="card-body">
            <div id="datos-afiliado" class="row">
                <!-- Aquí se cargarán dinámicamente los datos del afiliado -->
            </div>
        </div>
    </div>

    <!-- Tabla de Fórmulas Médicas y Médicos -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Fórmulas Médicas y Médicos</h5>
        </div>
        <div class="card-body">
            <!-- Tabla para mostrar las fórmulas y los médicos que ordenaron -->
            <table class="table table-bordered table-striped">
                <tbody id="tabla-formulas">
                    <!-- Aquí se cargarán dinámicamente las fórmulas médicas -->
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Medicamentos Formulados (Modal) -->
                <div class="modal fade" id="medicamentosModal" tabindex="-1" aria-labelledby="medicamentosModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" id="modal-header">
                                <!-- Aquí se mostrará el encabezado del modal con la información de la fórmula y del médico -->
                            </div>
                            <div class="modal-body" id="modal-body">
                                <!-- Aquí se cargará dinámicamente la tabla de medicamentos -->
                                <table class="table table-bordered table-striped">
                                        <!-- Aquí se cargara el encabezado de la tabla -->
                                    <tbody id="medicamentos-list">
                                        <!-- Aquí se cargarán dinámicamente los medicamentos -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
    
</div>
