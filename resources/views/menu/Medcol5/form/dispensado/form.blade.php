<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-lg-4">
            <div class="input-group">
                <label for="numero_factura" class="input-group-text">Número de Factura</label>
                <input type="text" name="numero_factura" id="numero_factura" class="form-control" placeholder="Ingrese el número de factura" aria-label="Número de Factura">
            </div>
        </div>
        <div class="col-lg-4">
            <button type="button" class="btn btn-success btn-block" id="buscarFactura" >
                Buscar
            </button>
        </div>
        <!-- <div class="col-lg-4">
            <button type="submit" name="reset2" id="reset2" class="btn btn-warning btn-block">Limpiar</button>
        </div> -->
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="factura">Número de Factura</label>
                <input type="text" class="form-control" id="factura" name="factura" readonly>
            </div>

            <div class="form-group">
                <label for="paciente">Paciente</label>
                <input type="text" class="form-control" id="paciente" name="paciente" readonly>
            </div>

            <div class="form-group">
                <label for="drogueria">Droguería</label>
                <input type="text" class="form-control" id="drogueria" name="drogueria" readonly>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label for="regimen">Regimen</label>
                <input type="text" class="form-control" id="regimen" name="regimen" readonly>
            </div>

            <div class="form-group">
                <label for="tipodocument">Tipo de Documento</label>
                <input type="text" class="form-control" id="tipodocument" name="tipodocument" readonly>
            </div>

            <div class="form-group">
                <label for="medico1">Profesional</label>
                <input type="text" class="form-control" id="medico1" name="medico1" readonly>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label for="fecha_suministro">Fecha Dispensación</label>
                <input type="date" class="form-control" id="fecha_suministro" name="fecha_suministro" readonly>
            </div>

            <div class="form-group">
                <label for="idusuario">Historia</label>
                <input type="text" class="form-control" id="idusuario" name="idusuario" readonly>
            </div>

            <div class="form-group">
                <label for="cajero">Auxiliar Dispenso</label>
                <input type="text" class="form-control" id="cajero" name="cajero" readonly>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-4">
    <fieldset>
        <legend style="color: #008080; font-weight: bold; font-size: 20px;">Completar la dispensación de la Fórmula</legend>
        <!-- Agrega más contenido relacionado con la dispensación aquí -->
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="fecha_orden">Fecha Ordenamiento</label>
                    <input type="date" class="form-control" id="fecha_orden" name="fecha_orden" placeholder="Ingrese la fecha de la orden">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="numero_entrega1">Número de Entrega</label>
                    <input type="text" class="form-control" id="numero_entrega1" name="numero_entrega1" placeholder="Ejemplo: 1-2">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="diagnostico">Diagnóstico</label>
                    <select name="diagnostico" class="dxcie10 form-control select2bs4" style="width: 100%;" required>
                        <!-- Agrega opciones de diagnóstico -->
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ips">IPS Formulación</label>
                    <select name="ips" class="ipsmul form-control select2bs4" style="width: 100%;" required>
                        <!-- Agrega opciones de IPS -->
                    </select>
                </div>
            </div>
        </div>

    </fieldset>

    <!-- Agrega el contenedor de la tabla DataTable -->
    <div class="container-fluid mt-4 modal-body" style="max-height: 400px; overflow-y: auto;">
        <table id="tablaRegistros" class="table table-striped">
            <caption>Lista de Medicamentos/Insumos</caption>
            <thead>
                <tr>
                    <th><input name="selector" id="selector" type="checkbox" class="select-all checkbox-large tooltipsC" title="Seleccionar todo" /> Acciones </th>
                    <!-- <th>ID</th> -->
                    <th>Código</th>
                    <th>Nombre Genérico</th>
                    <!-- <th>Tipo de Medicamento</th> -->
                    <th>Número de Unidades</th>
                    <th>Precio Unitario</th>
                    <th>Valor Total</th>
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

