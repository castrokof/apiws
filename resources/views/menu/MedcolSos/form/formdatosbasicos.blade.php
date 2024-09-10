<!-- Campo para Consultar Datos -->
<fieldset class="border p-4 rounded">
    <legend class="text-success font-weight-bold">Datos a Consultar</legend>
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
        <div class="col-md-4 mb-3">
            <label for="plan" class="form-label"><i class="fas fa-list-alt"></i> Plan</label>
            <select name="plan" id="plan" class="form-control select2bs4" style="width: 100%;" required>
                <!-- Opciones dinámicas -->
            </select>
        </div>
    </div>
</fieldset>

<!-- Datos del Afiliado -->
<fieldset class="mt-4">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5>Datos del Afiliado</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="planfil" class="form-label">Plan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                <input type="text" class="form-control" id="planfil" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                <input type="text" class="form-control" id="estado" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="rangoSalarial" class="form-label">Nivel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="text" class="form-control" id="rangoSalarial" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nui" class="form-label">NUI</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                <input type="text" class="form-control" id="nui" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="identificacionCompleta" class="form-label">Identificación</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                                <input type="text" class="form-control" id="identificacionCompleta" readonly>
                            </div>
                        </div>
                        <!--<div class="col-md-3 mb-3">
                            <label for="tipoidentificacion" class="form-label">Tipo ID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                                <input type="text" class="form-control" id="tipoidentificacion" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="numeroIdentificacion" class="form-label">Número de Identificación</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                                <input type="text" class="form-control" id="numeroIdentificacion" readonly>
                            </div>
                        </div> -->
                        <div class="col-md-3 mb-3">
                            <label for="primerNombre" class="form-label">Primer Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="primerNombre" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="segundoNombre" class="form-label">Segundo Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="segundoNombre" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="primerApellido" class="form-label">Primer Apellido</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="primerApellido" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="segundoApellido" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="form-control" id="fechaNacimiento" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="edad" class="form-label">Edad</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                <input type="text" class="form-control" id="edad" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="sexo" class="form-label">Sexo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                <input type="text" class="form-control" id="sexo" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" id="direccion" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="barrio" class="form-label">Barrio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-city"></i></span>
                                <input type="text" class="form-control" id="barrio" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" id="telefono" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="estadoCivil" class="form-label">Estado Civil</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-heart"></i></span>
                                <input type="text" class="form-control" id="estadoCivil" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tipoAfiliado" class="form-label">Tipo de Afiliado</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <input type="text" class="form-control" id="tipoAfiliado" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="descripcionIpsPrimaria" class="form-label">IPS Primaria</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-hospital"></i></span>
                                <input type="text" class="form-control" id="descripcionIpsPrimaria" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="codigoAfp" class="form-label">Código AFP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                <input type="text" class="form-control" id="codigoAfp" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="descripcionAfp" class="form-label">Descripción AFP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                                <input type="text" class="form-control" id="descripcionAfp" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="derecho" class="form-label">Descripción AFP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                                <input type="text" class="form-control" id="derecho" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</fieldset>
<!-- Tabla para mostrar los convenios -->
<fieldset class="border p-4 rounded mt-4">
    <legend class="text-success font-weight-bold">Convenios del Afiliado</legend>
    <div class="table-responsive">
        <table class="table table-bordered" id="tablaConvenios">
            <thead class="thead-light">
                <tr>
                    <th scope="col">NIT Prestador</th>
                    <th scope="col">Descripción Convenio</th>
                    <th scope="col">Capitacion</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se agregarán dinámicamente las filas -->
            </tbody>
        </table>
    </div>
</fieldset>

<!-- Tooltip Scripts -->
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

