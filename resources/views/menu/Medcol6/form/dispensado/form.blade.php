<div class="container-fluid">
    <!-- Sección de búsqueda -->
    <div class="row mb-3">
        <div class="col-lg-8">
            <div class="input-group">
                <label for="numero_factura" class="input-group-text">Número de Factura</label>
                <input type="text" name="numero_factura" id="numero_factura" class="form-control" placeholder="Ingrese el número de factura" aria-label="Número de Factura">
                <button type="button" class="btn btn-success" id="buscarFactura">Buscar</button>
            </div>
        </div>
    </div>

    <!-- Información de la factura y paciente -->
    <div class="row mb-4">
        <div class="col-lg-2">
            <div class="form-group">
                <label for="factura">Número de Factura</label>
                <input type="text" class="form-control" id="factura" name="factura" readonly>
            </div>
            <div class="form-group">
                <label for="tipodocument">Tipo de Documento</label>
                <input type="text" class="form-control" id="tipodocument" name="tipodocument" readonly>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label for="regimen">Régimen</label>
                <input type="text" class="form-control" id="regimen" name="regimen" readonly>
            </div>
            <div class="form-group">
                <label for="idusuario">Historia</label>
                <input type="text" class="form-control" id="idusuario" name="idusuario" readonly>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label for="fecha_suministro">Fecha Dispensación</label>
                <input type="date" class="form-control" id="fecha_suministro" name="fecha_suministro" readonly>
            </div>
            <div class="form-group">
                <label for="paciente">Paciente</label>
                <input type="text" class="form-control" id="paciente" name="paciente" readonly>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label for="drogueria">Droguería - Sede</label>
                <input type="text" class="form-control" id="drogueria" name="drogueria" readonly>
            </div>
            <div class="form-group">
                <label for="cajero">Auxiliar Dispenso</label>
                <input type="text" class="form-control" id="cajero" name="cajero" readonly>
            </div>
        </div>
    </div>

    <!-- Sección de completar la dispensación de la fórmula -->
    <div class="container-fluid mt-4">
        <fieldset>
            <legend style="color: #008080; font-weight: bold; font-size: 20px;">Completar la dispensación de la Fórmula</legend>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_orden">Fecha Ordenamiento</label>
                        <input type="date" class="form-control" id="fecha_orden" name="fecha_orden">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="entrega">Entrega</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="numero_entrega1" name="numero_entrega1" placeholder="1">
                            <span class="input-group-text">de</span>
                            <input type="number" class="form-control" id="num_total_entregas" name="num_total_entregas" placeholder="2">
                        </div>
                        <small class="form-text text-primary">Indique el número de esta entrega y el total de entregas.</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="diagnostico">Diagnóstico</label>
                        <select name="diagnostico" class="dxcie10 form-control select2bs4" style="width: 100%;" required>
                            <!-- Opciones -->
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="ips">IPS Formulación</label>
                        <select name="ips" class="ipsmul form-control select2bs4" style="width: 100%;" required>
                            <!-- Opciones -->
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="formula1">No. de Formula</label>
                        <input type="number" class="form-control" id="formula1" name="formula1">
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Contenedor de tabla para medicamentos -->
        <div class="container-fluid mt-4 modal-body" style="max-height: 400px; overflow-y: auto;">
            <table id="tablaRegistros" class="table table-striped">
                <caption>Lista de Medicamentos/Insumos</caption>
                <thead>
                    <tr>
                        <th><input name="selector" id="selector" type="checkbox" class="select-all checkbox-large tooltipsC" title="Seleccionar todo" /> Acciones </th>
                        <th style="width: 150px;">Código</th>
                        <th style="width: 300px;">Nombre Genérico</th>
                        <th>Cantidad</th>
                        <th>$ Unitario</th>
                        <th>$ Total</th>
                        <th>Duración Tratamiento</th>
                        <th>Cuota Moderadora</th>
                        <th>Autorización</th>
                        <th>MIPRES</th>
                        <th>Reporte de Entrega</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- Aquí se agregarán las filas de la tabla dinámicamente -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>