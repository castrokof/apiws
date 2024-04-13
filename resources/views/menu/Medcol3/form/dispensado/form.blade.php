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
                    <select name="dx" class="diagnos form-control select2bs4" style="width: 100%;" required>
                        <!-- Agrega opciones de diagnóstico -->
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ips">IPS Formulación</label>
                    <select name="ips" class="ipsss form-control select2bs4" style="width: 100%;" required>
                        <!-- Agrega opciones de IPS -->
                    </select>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="autorizacion">Autorización</label>
                    <input type="number" class="form-control" id="autorizacion" name="autorizacion" placeholder="Ingrese la autorización">
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
        </div> -->
    </fieldset>

    <!-- Agrega el contenedor de la tabla DataTable -->
    <div class="container-fluid mt-4">
        <table id="tablaRegistros" class="table table-striped">
            <thead>
                <tr>
                    <!-- <th><input name="selectall" id="selectall" type="checkbox" class="select-all checkbox-large tooltipsC" title="Seleccionar todo" /> Acciones </th> -->
                    <th>Acciones</th>
                    <th>Código</th>
                    <th>Nombre Genérico</th>
                    <th>Tipo de Medicamento</th>
                    <th>Número de Unidades</th>
                    <th>Autorización</th>
                    <th>MIPRES</th>
                    <th>Reporte de Entrega</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se agregarán las filas de la tabla dinámicamente -->
            </tbody>
        </table>
    </div>
</div>


<!-- Script para manejar la lógica de búsqueda y DataTable -->
<script>
    function buscarFactura() {
        const numeroFactura = $('#numero_factura').val();

        $.ajax({
            url: `{{ route('dispensado.buscar', ['factura' => ':numero_factura']) }}`.replace(':numero_factura', numeroFactura),
            type: 'GET',
            success: function(data) {
                if (data && Array.isArray(data) && data.length > 0) {
                    const firstRecord = data[0];

                    $('#factura').val(firstRecord.factura);
                    $('#paciente').val(firstRecord.paciente);
                    $('#drogueria').val(firstRecord.drogueria);
                    $('#regimen').val(firstRecord.regimen);
                    $('#tipodocument').val(firstRecord.tipodocument);
                    $('#medico1').val(firstRecord.medico);

                    if (firstRecord.fecha_suministro) {
                        const formattedFechaSuministro = new Date(firstRecord.fecha_suministro).toISOString().split('T')[0];
                        $('#fecha_suministro').val(formattedFechaSuministro);
                    } else {
                        $('#fecha_suministro').val('');
                    }

                    $('#idusuario').val(firstRecord.idusuario);
                    $('#cajero').val(firstRecord.cajero);

                    actualizarDataTable(data);
                } else {
                    console.error('Error: no se recibieron datos válidos o no se encontraron registros.');
                    // Mostrar alerta de SweetAlert2 cuando no se encuentran registros
                    mostrarError('No se encontraron registros para la factura ingresada.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al buscar la factura:', error);
                mostrarError('Error al buscar la factura. Por favor, inténtalo de nuevo.');
            }
        });
    }

    function actualizarDataTable(data) {
        const tablaRegistros = $('#tablaRegistros').DataTable();
        tablaRegistros.clear().rows.add(data).draw();
    }

    function mostrarError(mensaje) {
        // Mostrar alerta de SweetAlert2
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: mensaje,
            confirmButtonText: 'OK'
        });
    }
</script>