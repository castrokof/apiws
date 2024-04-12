<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-lg-4">
            <div class="input-group">
                <label for="numero_factura" class="input-group-text">Número de Factura</label>
                <input type="text" name="numero_factura" id="numero_factura" class="form-control" placeholder="Ingrese el número de factura" aria-label="Número de Factura">
            </div>
        </div>
        <div class="col-lg-4">
            <button type="button" class="btn btn-success btn-block" onclick="buscarFactura()">
                Buscar
            </button>
        </div>
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
        <legend style="color: #008080; font-weight: bold; font-size: 20px;">Verificar la dispensación de la Fórmula</legend>
        <!-- Aquí puedes agregar más contenido relacionado con la dispensación -->
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
                    <select name="dx" class="diagnos form-control select2bs4" style="width: 100%;" required>

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ips">IPS Formulación</label>
                    <select name="ips" class="ipsss form-control select2bs4" style="width: 100%;" required>

                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="autorizacion1">Autorización</label>
                    <input type="number" class="form-control" id="autorizacion1" name="autorizacion1" placeholder="Ingrese la autorización">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="mipres1">MIPRES</label>
                    <input type="number" class="form-control" id="mipres1" name="mipres1" placeholder="Ingrese el MIPRES">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="reporte_entrega1">Reporte de Entrega</label>
                    <input type="number" class="form-control" id="reporte_entrega1" name="reporte_entrega1" placeholder="Ingrese el reporte de entrega">
                </div>
            </div>
        </div>
    </fieldset>
    <div class="container-fluid mt-4">
        <table id="tablaRegistros" class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre Genérico</th>
                    <th>Tipo de Medicamento</th>
                    <th>Número de Unidades</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se agregarán las filas de la tabla dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<script>

    function buscarFactura() {
        // Obtener el número de factura ingresado
        const numeroFactura = document.getElementById('numero_factura').value;

        // Realizar la solicitud al controlador
        $.ajax({
            url: "{{ route('dispensado.buscar', ['factura' => ':numero_factura']) }}".replace(':numero_factura', numeroFactura),
            type: 'GET',
            success: function(data) {
                // Verificar si se recibieron datos válidos y si hay al menos un registro
                if (data && Array.isArray(data) && data.length > 0) {
                    // Obtener el primer elemento del arreglo
                    const firstRecord = data[0];

                    // Asignar los valores del primer registro a los campos de formulario
                    document.getElementById('factura').value = firstRecord.factura;
                    document.getElementById('paciente').value = firstRecord.paciente;
                    document.getElementById('drogueria').value = firstRecord.drogueria;
                    document.getElementById('regimen').value = firstRecord.regimen;
                    document.getElementById('tipodocument').value = firstRecord.tipodocument;
                    document.getElementById('medico1').value = firstRecord.medico;

                    // Verificar y formatear la fecha de suministro
                    if (firstRecord.fecha_suministro) {
                        const formattedFechaSuministro = moment(firstRecord.fecha_suministro).format('YYYY-MM-DD');
                        document.getElementById('fecha_suministro').value = formattedFechaSuministro;
                    } else {
                        document.getElementById('fecha_suministro').value = '';
                    }

                    document.getElementById('idusuario').value = firstRecord.idusuario;
                    document.getElementById('cajero').value = firstRecord.cajero;
                } else {
                    console.error('Error: no se recibieron datos válidos o no se encontraron registros.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al buscar la factura:', error);
            }
        });
    }
</script>