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
            <div id="datos-afiliado" class="row"></div>
        </div>
    </div>

    <!-- Datos de la Fórmula Médica -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Datos de la Fórmula Médica</h5>
        </div>
        <div class="card-body">
            <div id="datos-formula" class="row"></div>
        </div>
    </div>

    <!-- Datos del Médico -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">Datos del Médico</h5>
        </div>
        <div class="card-body">
            <div id="datos-medico" class="row"></div>
        </div>
    </div>

    <!-- Medicamentos -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Medicamentos Formulados</h5>
        </div>
        <div class="card-body">
            <!-- Tabla para listar los medicamentos -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre Medicamento</th>
                        <th>Cantidad Ordenada</th>
                        <th>Concentración</th>
                        <th>Dosis</th>
                        <th>Vía de Administración</th>
                        <th>Frecuencia</th>
                        <th>Duracion</th>
                        <th>Número de Entregas</th>
                    </tr>
                </thead>
                <tbody id="medicamentos-list">
                    <!-- Aquí se cargarán dinámicamente los medicamentos -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Navegación de Fórmulas -->
    <div id="navegacion-formulas" class="d-flex justify-content-between">
        <button id="btn-anterior" class="btn btn-secondary">Anterior</button>
        <button id="btn-siguiente" class="btn btn-primary">Siguiente</button>
    </div>
</div>
