@extends("theme.$theme.layout")

@section('titulo')
    Ordenes de Compras
@endsection
@section('styles')

    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* // Colores para las tarjetas widget */

        /*CREATE TRIGGER `Actualiza_Consecutivo` AFTER INSERT ON `entradas`
             FOR EACH ROW UPDATE documentos set consecutivo = new.consecutivo + 1 WHERE  documento = (SELECT documento FROM entradas order by id desc
            limit 1)
            */
        .card {
            background-color: #fff;
            border-radius: 10px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
        }

        .l-bg-blue-dark-card {
            background-color: linear-gradient(to right, #373b44, #4286f4) !important;
            color: #fff;
        }



        .l-bg-cherry {
            background: linear-gradient(to right, #493240, #f09) !important;
            color: #fff;
        }

        .l-bg-blue-dark {
            background: linear-gradient(to right, #373b44, #4286f4) !important;
            color: #fff;
        }

        .l-bg-green-dark {
            background: linear-gradient(to right, #0a504a, #38ef7d) !important;
            color: #fff;
        }

        .l-bg-orange-dark {
            background: linear-gradient(to right, #a86008, #ffba56) !important;
            color: #fff;
        }

        .l-bg-red-dark {
            background: linear-gradient(to right, #a80d08, #ff6756) !important;
            color: #fff;
        }

        .card .card-statistic-3 .card-icon-large .fas,
        .card .card-statistic-3 .card-icon-large .far,
        .card .card-statistic-3 .card-icon-large .fab,
        .card .card-statistic-3 .card-icon-large .fal {
            font-size: 110px;
        }

        .card .card-statistic-3 .card-icon {
            text-align: center;
            line-height: 50px;
            margin-left: 15px;
            color: #000;
            position: absolute;
            right: -5px;
            top: 20px;
            opacity: 0.1;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }

        .l-bg-green {
            background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
            color: #fff;
        }

        .l-bg-orange {
            background: linear-gradient(to right, #f9900e, #ffba56) !important;
            color: #fff;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }



        /*btn flotante*/
        .btn-flotante {
            font-size: 14px;
            /* Cambiar el tamaño de la tipografia */
            text-transform: uppercase;
            /* Texto en mayusculas */
            font-weight: bold;
            /* Fuente en negrita o bold */
            color: #ffffff;
            /* Color del texto */
            border-radius: 120px;
            /* Borde del boton */
            letter-spacing: 2px;
            /* Espacio entre letras */
            background: linear-gradient(to right, #a80d08, #ff6756) !important;
            /* Color de fondo */
            /*background-color: #e9321e; /* Color de fondo */
            padding: 18px 30px;
            /* Relleno del boton */
            position: fixed;
            bottom: 40px;
            right: 40px;
            transition: all 300ms ease 0ms;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.5);
            z-index: 99;
            border: none;
            outline: none;
        }

        .btn-flotante:hover {
            background-color: #2c2fa5;
            /* Color de fondo al pasar el cursor */
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(-7px);
        }

        @media only screen and (max-width: 600px) {
            .btn-flotante {
                font-size: 14px;
                padding: 12px 20px;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
    
@endsection


@section('scripts')

@endsection

@section('contenido')
    
    @include('menu.Compras.Medcol3.modal.modalCompras')
    @include('menu.Compras.Medcol3.modal.modalFacturaArticulo')
@endsection


@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        
        
        // Función que envia el id al controlador y cambia el estado del registro
        $(document).on('click', '#articulos', function() {

            const text = 'De Medcol Centralizado';

            Swal.fire({
                title: "¿Estás por sincronizar los medicamentos?",
                text: text,
                //type: "info",
                icon: "info",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

                    ajaxRequestSyncMedicamentos();

                }
            });
        });

        function ajaxRequestSyncMedicamentos() {
            
            $.ajax({
                beforeSend: function() {
                    $('.loaders').css("visibility", "visible");
                },
                
                url: "{{route('medcol3.syncmedicamentosapi')}}",
                type: 'GET',
                success: function(data) {
               

                    $.each(data, function(i, item) {
                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });
                   
                },
                
                complete: function() {
                    
                    $('.loaders').css("visibility", "hidden");
                    
                }
            });
        }
        
    
        
       // Función que envia el id al controlador y cambia el estado del registro
        $(document).on('click', '#proveedores', function() {

            const text = 'De Medcol Centralizado';

            Swal.fire({
                title: "¿Estás por sincronizar los Terceros?",
                text: text,
                //type: "info",
                icon: "info",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

                    ajaxRequestSyncTerceros();

                }
            });
        });

        function ajaxRequestSyncTerceros() {
            
            $.ajax({
                beforeSend: function() {
                    $('.loaders').css("visibility", "visible");
                },
                
                url: "{{route('medcol3.synctercerosapi')}}",
                type: 'GET',
                success: function(data) {
               

                    $.each(data, function(i, item) {
                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });
                   
                },
                
                complete: function() {
                    
                    $('.loaders').css("visibility", "hidden");
                    
                }
            });
        }
           
        
        
        
        
        
        
        
        $(document).ready(function() {
        // Llamar a la función de calcular totales cuando se carga la página
        calcularTotales();
        $(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            if (response.tbody && response.pagination) {
                $('#ordenes-body').html(response.tbody);
                $('#paginacion').html(response.pagination);
            } else {
                console.error('Error: formato de respuesta inesperado.');
            }
        },
        error: function(xhr) {
            console.error('Error AJAX:', xhr.responseText);
        }
    });
});

function cargarTabla(url) {
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        

        // Obtiene solo las filas <tr>
        const nuevasFilas = doc.querySelectorAll('tbody tr');
        const nuevaPaginacion = doc.querySelector('#paginacion');

        const cuerpo = document.querySelector('#ordenes-body');
        const paginacion = document.querySelector('#paginacion');

        // Limpia y reemplaza las filas
        cuerpo.innerHTML = '';
        nuevasFilas.forEach(fila => cuerpo.appendChild(fila));

        // Reemplaza paginación
        if (paginacion && nuevaPaginacion) {
            paginacion.innerHTML = nuevaPaginacion.innerHTML;
        }
    })
    .catch(err => {
        console.error("Error AJAX:", err);
    });
}
        fill_datatable1_resumen();


                $('#subir').click(function() {
                let text = 'Vas a importar una orden de compra';

                var formData = new FormData(document.getElementById("form-importar"));
                var proveedor = $("#codigop").val();
                
                // Agregar el valor del proveedor al FormData
                formData.append('proveedor', proveedor);
                
                
                
                if(proveedor != null && formData != null){
                    
                Swal.fire({
            target: document.getElementById('modal-pd'),
            title: "¿Estás seguro?",
            text: text,
            icon: "info", 
            showCancelButton: true,
            showCloseButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Aceptar',
            }).then((result)=>{
            if(result.value){
                Swal.fire({
                        target: document.getElementById('modal-pd'),
                        title: 'Espere por favor !',
                        html: 'Realizando el cargue de la información',// add html attribute if you want or remove
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                    }),    
                    
                    
                $.ajax({
                    beforeSend: function() {
                        $('.loader').css("visibility", "visible");
                    },
                    url: "{{ route('importarchivo3') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if (response.mensaje == 'ok') {
                            $('#importarModal').modal('hide');
                            
                            
                            AgregarMedicamentosMasivos(response.data);
                            
                            
                            Apiws.notificaciones('Archivo cargado exitosamente',
                                'Compras Medcol', 'success');
                                toastr.success('Archivo cargado exitosamente', 'Compras Medcol');
                        
                        } else if (response.mensaje == 'vacio') {

                            Apiws.notificaciones('No seleccionaste ningun arhivo',
                                'Compras Medcol', 'info');
                        } else if (response.mensaje == 'ng') {
                            $('#importarModal').modal('hide');
                            Apiws.notificaciones('Registros duplicados en base de datos',
                                'Compras Medcol', 'warning');
                        

                        }
                    },

                    complete: function() {
                        $('.loader').css("visibility", "hidden");
                    }

                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 0) {

                        alert('Not connect: Verify Network.');

                    } else if (jqXHR.status == 404) {

                        alert('Requested page not found [404]');

                    } else if (jqXHR.status == 500) {

                        Apiws.notificaciones('El archivo no tienen la estructura adecuada',
                            'Compras Medcol', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();

                    } else if (textStatus === 'parsererror') {

                        alert('Requested JSON parse failed.');

                    } else if (textStatus === 'timeout') {

                        alert('Time out error.');

                    } else if (textStatus === 'abort') {

                        alert('Ajax request aborted.');

                    } else {

                        Apiws.notificaciones(
                            'El campo file debe ser un archivo de tipo: xls, xlsx',
                            'Compras Medcol', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();
                    }

                });
                
            }
                
            });
            
            
                
            }else{
                
                Apiws.notificaciones('Debes seleccionar el proveedor',
                            'Compras Medcol', 'warning');
            }
            
            

            });
            
    $(document).on('click', '.btn-eliminar-orden', function () {
        let id = $(this).data('id');
        let row = $(this).closest('tr');

        if (confirm('¿Estás seguro de que deseas eliminar esta Orden?')) {
            $.ajax({
                url: '{{ url("/ordenes") }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        row.remove(); // Elimina la fila de la tabla    
                         $("#detalle").empty();
                $("#detalle1").empty();
                $("#detalle2").empty();
                $("#detalle3").empty();
                $("#detalle").append(
                            '<div class="small-box shadow-lg l-bg-blue-dark"><div class="inner">' +
                            '<h5>TOTAL ORDENES</h5>' +
                            '<p><h5> ' + response.Estadistica.CantidadOrdenes + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle1").append(
                            '<div class="small-box shadow-lg l-bg-green-dark"><div class="inner">' +
                            '<h5>ORDENES COMPLETAS</h5>' +
                            '<p><h5> ' + response.Estadistica.OrdenesCompletadas + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle2").append(
                            '<div class="small-box shadow-lg l-bg-orange-dark"><div class="inner">' +
                            '<h5>ORDENES PENDIENTES</h5>' +
                            '<p><h5> ' + response.Estadistica.OrdenesPendientes + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle3").append(
                            '<div class="small-box shadow-lg l-bg-red-dark"><div class="inner">' +
                            '<h5>ORDENES ANULADAS</h5>' +
                            '<p><h5> ' + response.Estadistica.OrdenesIncumplidas + '</h5></p>'
                            + '</div></div>'
                        );   
                        alert(response.message);
                    } else {
                        alert('No se pudo eliminar la Orden.');
                    }
                },
                error: function () {
                    alert('Ocurrió un error en el servidor.');
                }
            });
        }
    });        
            
        $('#guardar_entrada').click(function() {

            
            var url = "{{ route('entradasstore_3') }}";
            console.log($('#farmacia').val());
            var camposObligatorios = [
                {selector : '#consecutivo', mensaje: 'El campo documento es obligatorio.'},
                {selector : '#nombrep', mensaje: 'El campo proveedor es obligatorio.'},
                {selector : '#contrato', mensaje: 'El campo contrato es obligatorio.'},                                           
                {selector : '#fecha_facturae', mensaje: 'El campo fecha es obligatorio.'},                
                {selector : '#codigop', mensaje: 'El campo proveedor es obligatorio.'},
                {selector : '#farmacia', mensaje: 'El campo farmacia es obligatorio.'},
                {selector : '#numeroOrden', mensaje: 'El campo numero de orden es obligatorio.'},
                {selector : '#descripcion1', mensaje: 'El campo observaciones es obligatorio.'},
            ];
            for (var i = 0; i < camposObligatorios.length; i++) {
                var campo = camposObligatorios[i];
                if ($(campo.selector).val() == "" || $(campo.selector).val() == null) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: campo.mensaje,
                        showConfirmButton: true
                    });
                    return;
                }
            }

            var entradaOrden = [];
            var entradaOrdeninput = {};
            entradaOrdeninput.orden_de_compra =  $('#numeroOrden').val();
            entradaOrdeninput.nit = $('#codigop').val();
            entradaOrdeninput.proveedor = $('#nombrep').val();
            entradaOrdeninput.fecha = $('#fecha_facturae').val();
            entradaOrdeninput.cod_farmacia = $('#farmacia').val();
            entradaOrdeninput.num_orden_compra = $('#consecutivo').val();
            entradaOrdeninput.codigo_proveedor = $('#codigop').val();
            entradaOrdeninput.estado  = "Pendiente";
            entradaOrdeninput.user_create = $('#user_ids').val();
            entradaOrdeninput.created_at = $('#fecha_facturae').val();
            entradaOrdeninput.update_at = $('#fecha_facturae').val();


            let totalText = $('#totalTotal').val();
            // Eliminar símbolos de moneda y reemplazar comas por puntos (si es necesario)
            let totalNumerico = parseFloat(totalText.replace(/[$.]/g, "").replace(',', '.'));

            // Verificar si la conversión a número fue exitosa
            if (isNaN(totalNumerico)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de formato',
                    text: 'El valor total ingresado no es un número válido.',
                    showConfirmButton: true
                });
                return; // Detener el proceso si el formato es incorrecto
            }

            entradaOrdeninput.total = totalNumerico;
            entradaOrdeninput.observaciones = $('#descripcion1').val();
            entradaOrdeninput.numeroOrden = $('#consecutivo').val(); // Asegúrate de enviar también el numeroOrden para la tabla 6

            entradaOrden.push(entradaOrdeninput);


            var entrada = [];

            var entradainput = {};

            entradainput.documentoOrden = $('#documento').val();
            entradainput.numeroOrden = $('#consecutivo').val();
            entradainput.proveedor_id = $('#proveedor_id').val();
            entradainput.created_at = $('#fecha_facturae').val();
            entradainput.contrato = $('#contrato').val();
            entradainput.usuario_id = $('#user_ids').val();


            entrada.push(entradainput);

            var entradadetalle = [];

            $("#tcups > tbody > tr").each(function() {
                var itemdetalle = {};
                var tds = $(this).find("td");
                if(itemdetalle.cums == null){
                    itemdetalle.cums = "Sin Cums";
                }
                
                if(itemdetalle.presentacion == null){
                    itemdetalle.presentacion = "Sin Presentación";
                }

                itemdetalle.codigo = tds.eq(1).text();
                itemdetalle.nombre = tds.eq(2).text();
                itemdetalle.presentacion = tds.eq(3).text();
                itemdetalle.marca = tds.eq(4).text();
                itemdetalle.cums = tds.eq(5).text();

                let subtotalunitext = tds.eq(6).text();
                let subtotalunisin = subtotalunitext.replace(/[$.]/g, "").replace(',', '.');
                itemdetalle.precio = parseInt(subtotalunisin);

                let valorIvaUnitext = tds.eq(8).text();
                let valorIvaUnisin = valorIvaUnitext.replace(/[$.]/g, "").replace(',', '.');
                itemdetalle.iva = parseInt(valorIvaUnisin);

                itemdetalle.cantidad = parseInt(tds.eq(9).text());

                let subtotaltext = tds.eq(10).text();
                let subtotalsin = subtotaltext.replace(/[$.]/g, "").replace(',', '.');
                itemdetalle.subtotal = parseInt(subtotalsin);

                let ivaTotaltext = tds.eq(11).text();
                let ivaTotalsin = ivaTotaltext.replace(/[$.]/g, "").replace(',', '.');
                itemdetalle.cantidad_iva_total = parseInt(ivaTotalsin);

                let totaltext = tds.eq(12).text();
                let totalsin = totaltext.replace(/[$.]/g, "").replace(',', '.');
                itemdetalle.precio_compra_total = parseInt(totalsin);

                itemdetalle.documentoOrden = $('#documento').val();
                itemdetalle.numeroOrden = $('#consecutivo').val();
                itemdetalle.proveedor_id = $('#proveedor_id').val();
                itemdetalle.created_at = $('#fecha_facturae').val();
                itemdetalle.contrato = $('#contrato').val();
                itemdetalle.usuario_id = $('#user_ids').val();

                entradadetalle.push(itemdetalle);
            });

            var dataToSend = {
                entradaOrden: entradaOrden,
                entradadetalle: entradadetalle
            };

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Vas a realizar una entrada",
                type: "warning",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        type: "info",
                        title: 'Espere por favor !',
                        html: 'Realizando la entrada..',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                    });

                    $.ajax({
                        beforeSend: function() {
                            $('.loader').css("visibility", "visible");
                        },
                        url: url,
                        dataType: 'json',
                        method: 'post',
                        data: {
                            data: dataToSend, // Enviar el objeto dataToSend
                            "_token": $("meta[name='csrf-token']").attr("content")
                        },
                        success: function(data) {
                            Swal.close(); // Cerrar la alerta de carga

                            if (data.errors) {
                                const mensaje = Array.isArray(data.errors) ? data.errors.join(', ') : data.errors;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: mensaje,
                                    showConfirmButton: true
                                });
                            } else if (data.success == 'ok') {
                                Swal.fire({
                                    icon: 'success',
                                    type: 'success',
                                    title: "Orden realizada correctamente",
                                    text: `Documento: ${data.documento}, Número de Orden: ${data.numeroOrden}`,
                                    showConfirmButton: true
                                }).then(() => {

                                    location.reload(); // Refrescar la página completa

                                });
                            }
                        },
                        complete: function() {
                            $('.loader').css("visibility", "hidden");
                        }
                    });
                }
            });
            });

        calcularTotales();

        $('#buscarOrdenesBtn').click(function() {
            // Mostrar el loader
            $('#loader').show();

            // Obtener los valores de los campos de búsqueda
            var cod_farmacia = $('select[name="cod_farmacia"]').val();
            var orden_de_compra = $('input[name="orden_de_compra"]').val();
            var proveedor = $('input[name="proveedor"]').val();
            var fecha_desde = $('input[name="fecha_desde"]').val();
            var fecha_hasta = $('input[name="fecha_hasta"]').val();
            
            // Realizar la búsqueda utilizando AJAX
            $.ajax({
                url: "{{ route('buscar.ordenes.compra') }}",
                method: "GET",
                data: {
                    cod_farmacia: cod_farmacia,
                    proveedor: proveedor,
                    orden_de_compra: orden_de_compra,
                    fecha_desde: fecha_desde,
                    fecha_hasta: fecha_hasta
                },
                success: function(response) {
                
                var ordenes = response.data;
                var html = '';
                var CantidadOrdenes = ordenes.length;
                var CantidadOrdenesPendientes = ordenes.filter(function(orden) {
                    return orden.estado === "Pendiente";
                }).length;
                var CantidadOrdenesCompletadas = ordenes.filter(function(orden) {
                    return orden.estado === "Completada";
                }).length;
                var CantidadOrdenesIncumplidas = ordenes.filter(function(orden) {
                    return orden.estado === "Anulada";
                }).length;
                // Actualizar los contadores en el resumen
                $("#detalle").empty();
                $("#detalle1").empty();
                $("#detalle2").empty();
                $("#detalle3").empty();
                $("#detalle").append(
                            '<div class="small-box shadow-lg l-bg-blue-dark"><div class="inner">' +
                            '<h5>TOTAL ORDENES</h5>' +
                            '<p><h5> ' + CantidadOrdenes + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle1").append(
                            '<div class="small-box shadow-lg l-bg-green-dark"><div class="inner">' +
                            '<h5>ORDENES COMPLETAS</h5>' +
                            '<p><h5> ' + CantidadOrdenesCompletadas + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle2").append(
                            '<div class="small-box shadow-lg l-bg-orange-dark"><div class="inner">' +
                            '<h5>ORDENES PENDIENTES</h5>' +
                            '<p><h5> ' + CantidadOrdenesPendientes + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle3").append(
                            '<div class="small-box shadow-lg l-bg-red-dark"><div class="inner">' +
                            '<h5>ORDENES ANULADAS</h5>' +
                            '<p><h5> ' + CantidadOrdenesIncumplidas + '</h5></p>'
                            + '</div></div>'
                        );

                ordenes.forEach(function(orden) {
                    var estadoClass = '';
                    if (orden.estado === "Pendiente") {
                        estadoClass = 'small-box shadow-lg l-bg-orange-dark';
                    } else if (orden.estado === "Completada") {
                        estadoClass = 'small-box shadow-lg l-bg-green-dark';
                    } else {
                        estadoClass = 'small-box shadow-lg l-bg-red-dark';
                    }
                    var detalleUrl = `/public_apiws/medcol3/ordenes/${orden.num_orden_compra}/detalle`;
                    var detalleUrl2 = `/public_apiws/ordenes/${orden.id}/editar`;
                    

                    html += `
                        <tr>
                            <td id="td_OrdenDeCompra">#${orden.orden_de_compra}</td>
                            <td id="td_cod_farmacia">${orden.cod_farmacia}</td>
                            <td id="td_fecha">${orden.fecha}</td>
                            <td id="td_proveedor">${orden.proveedor}</td>
                            <td id="td_codigo_proveedor">${orden.codigo_proveedor}</td>
                            <td id="td_telefono">${orden.telefono}</td>
                            <td id="td_total">${orden.total ? new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(orden.total) : '0,00'}</td>
                            <td id="td_estado" class="${estadoClass}">${orden.estado}</td>
                            <td>
                                <a href="${detalleUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-search"></i>
                                </a>
                                <a href="${detalleUrl2}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-eliminar-orden" data-id="${orden.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                

                                
                            </td>
                        </tr>
                    `;
                });
                $('#ordenes-body').html(html);
            },
                complete: function() {
                    // Ocultar el loader
                    $('#loader').hide();
                }
            });
        });
  
        function fill_datatable1_resumen() {
                $("#detalle").empty();
                $("#detalle1").empty();
                $("#detalle2").empty();
                $("#detalle3").empty();
            

                $.ajax({
                    url: "{{ route('ordenes.resumen') }}", // Nueva ruta
                    dataType: "json",
                    success: function(data) {
                        $("#detalle").append(
                            '<div class="small-box shadow-lg l-bg-blue-dark"><div class="inner">' +
                            '<h5>TOTAL ORDENES</h5>' +
                            '<p><h5> ' + data.total_ordenes + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle1").append(
                            '<div class="small-box shadow-lg l-bg-green-dark"><div class="inner">' +
                            '<h5>ORDENES COMPLETAS</h5>' +
                            '<p><h5> ' + data.completadas + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle2").append(
                            '<div class="small-box shadow-lg l-bg-orange-dark"><div class="inner">' +
                            '<h5>ORDENES PENDIENTES</h5>' +
                            '<p><h5> ' + data.pendientes + '</h5></p>'
                            + '</div></div>'
                        );

                        $("#detalle3").append(
                            '<div class="small-box shadow-lg l-bg-red-dark"><div class="inner">' +
                            '<h5>ORDENES ANULADAS</h5>' +
                            '<p><h5> ' + data.incumplidas + '</h5></p>'
                            + '</div></div>'
                        );
                    }
                });
            }



       function calcularTotales() {
        var totalSubtotal = 0;
        var totalIva = 0;
        var totalTotal = 0;

        // Iterar sobre cada fila de la tabla
        $('#tcups tbody tr').each(function () {
           

            var subtotalformatted = $(this).find('.precio_compra_subtotal').text().replace(/[^\d,]/g, '');
            subtotalformatted = subtotalformatted.replace(',', '.');

            var ivaformatted = $(this).find('.cantidad_iva_total').text().replace(/[^\d,]/g, '');
            ivaformatted = ivaformatted.replace(',', '.');

            var totalformatted = $(this).find('.precio_compra_total').text().replace(/[^\d,]/g, '');
            totalformatted = totalformatted.replace(',', '.');

            var subtotal = parseFloat(subtotalformatted);
            var iva = parseFloat(ivaformatted) || 0;
            var total = parseFloat(totalformatted) || 0;

            totalSubtotal += subtotal;
            totalIva += iva;
            totalTotal += total;
        });

        // Actualizar los totales en la interfaz

        $('#totalSubtotal').val(totalSubtotal.toLocaleString('es-CO', {
                                            style: 'currency',
                                            currency: 'COP'
                                        }));
        $('#totalIva').val(totalIva.toLocaleString('es-CO', {
                                            style: 'currency',
                                            currency: 'COP'
                                        }));
        $('#totalTotal').val(totalTotal.toLocaleString('es-CO', {
                                            style: 'currency',
                                            currency: 'COP'
                                        }));

        

    }
        
            //Funcion que abre modal donde se deben seleccionar el articulo que se iran cargando en la factura
            $('#agregar_articulo').click(function() {

                $('#form-general_1')[0].reset();
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#form_result_1').html('');
                $('#modal-articulos').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal-articulos').modal('show');
            });



            // Agregar filas a tabla para guardar
            $('#addfila').click(function() {

            let molecula = null,
                nombrea = null,
                cums = null,
                presentacion = null,
                precio = null,
                iva = null,
                precio_compra_subtotal = null,
                cantidad = null,
                precio_compra_total = null;
                
                
                if($('#cums').val() === ''){
                    
                    $('#cums').val('Sin Cums');
                }

                if($('#presentacion').val() === ''){
                    $('#presentacion').val('Sin Presentación');
                }
                
                molecula = $('#molecula').val();
                nombrea = $('#nombrea').val();
                cums = $('#cums').val();
                presentacion = $('#presentacion').val();
                precio = $('#precio_compra_subtotal_unitario').val();
                
                if ($('#iva').val() == null) {
                    iva = 0;
                } else {
                    iva = $('#iva').val();
                }
                
                precio_compra_subtotal = $('#precio_compra_subtotal').val();
                cantidad = $('#cantidad').val();
                precio_compra_total = $('#precio_compra_total').val();

                const array = [codigo, nombrea, cums,presentacion, precio,
                    precio_compra_subtotal, cantidad, precio_compra_total
                ];


                function verificarArray(array) {
                    
                    for (var i = 0; i < array.length; i++) {
                        if (array[i] === null || array[i] === '' || array[i] == '0' || array[i] == 0) {
                            console.log(array[i]);
                            return false;
                        }
                    }

                  
                    return true; // Si ninguno es null || '' || 0 || '0', devuelve true
                }


                var resultado = verificarArray(array);

                if (resultado) {
                    const total = parseFloat($('#cantidad').val() * $('#precio_compra_subtotal_unitario').val());
                    var valordelpagosubtotalFormatted = parseFloat($('#precio_compra_subtotal').val())
                        .toLocaleString('es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        });

                    var valordelpagocompraFormatted = parseFloat($('#precio_compra_subtotal_unitario').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });

                    var valordelivaFormatted = parseFloat($('#iva').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    }); 

                    var valordelpagoivatotalFormatted = parseFloat($('#cantidad_iva_total').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });

                    var valordelpagototalFormatted = parseFloat($('#precio_compra_total').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    }); 

                        

                    $('#tcups> tbody:last-child')
                        .append(

                        
                            '<tr><td><button type="button" name="eliminar" class="btn-float bg-gradient-danger btn-sm tooltipsC" title="eliminar"><i class="fas fa-trash"></i></button></td>' +
                            '<td class="molecula">' + $('#molecula').val() + '</td>' +
                            '<td class="nombrea">' + $('#nombrea').val() + '</td>' +
                            '<td class="presentacion">' + $('#presentacion').val() + '</td>' +
                            '<td class="marca">' + $('#marca').val() + '</td>' +
                            '<td class="cums">' + $('#cums').val() + '</td>' +
                            '<td class="precio">' + valordelpagocompraFormatted + '</td>' +
                            '<td class="ivab">' + iva + '</td>' +
                            '<td class="iva">' + valordelivaFormatted + '</td>' +
                            '<td class="cantidad">' + $('#cantidad').val() + '</td>' +
                            '<td class="precio_compra_subtotal">' +  valordelpagosubtotalFormatted + '</td>' +
                            '<td class="cantidad_iva_total">' + valordelpagoivatotalFormatted + '</td>' +
                            '<td class="precio_compra_total">' +valordelpagototalFormatted + '</td></tr>'  
                          
                        );
                      
                      calcularTotales();

                    $('#form-general_1')[0].reset();
                    $("#codigo").val('').trigger('change');
                    $("#ivab").val('').trigger('change');

                    

                } else {

                    Swal.fire({
                        type: 'error',
                        title: 'El formulario contiene elementos vacios o en cero',
                        showConfirmButton: true,

                    })
                }




            });
            
         
         
          
            // eliminar filas de la tabla procedimientos para guardar

            $("#tcups tbody").on("click", 'button[name="eliminar"]', function() {
                $(this).closest("tr").remove();
                // Volver a calcular los totales si es necesario
                calcularTotales();
                
            });

            //--------- validacion de input solo valores enteros -------//
            $(function() {

                $('.validanumericos1').keypress(function(e) {
                        if (isNaN(this.value + String.fromCharCode(e.charCode)))
                            return false;
                    })
                    .on("cut copy paste", function(e) {
                        e.preventDefault();
                    });

            });


            $('.validanumericos').on({
                "focus": function(event) {
                    $(event.target).select();
                },
                "keyup": function(event) {
                    $(event.target).val(function(index, value) {
                        return value.replace(/\D/g, "")
                            .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                    });
                }
            });


            // Calcular la suma total de factura general
          
            $('#precio_compra_subtotalf').change(sumatotal);

            $('#totaliva').change(sumatotal);

            function sumatotal() {



                let ivax = $('#totaliva').val();

                ivax = ivax.replaceAll('.', '');

                let base = $('#precio_compra_subtotalf').val();

                base = base.replaceAll('.', '');

                const preciototal = (parseInt(ivax) + parseInt(base));

                $('#precio_compra_totalf').val(preciototal);


            }


             // Calcular la suma total de factura por item + el iva

            $('#ivab').change(valorivauni);
            $('#precio_compra_subtotal_unitario').change(valorivauni);
            $('#cantidad').change(valorivauni);
            

            function valorivauni() {



                const ivax = $('#ivab').val();
                let base = $('#precio_compra_subtotal_unitario').val();
                const cantidad = $('#cantidad').val();

                base = base.replaceAll('.', '');

                const resultado = (ivax * base) / 100;
                const resultado1 = (ivax * base * cantidad) / 100;

                $('#iva').val(resultado);
                $('#cantidad_iva_total').val(resultado1);


            }



            $('#precio_compra_subtotal_unitario').change(sumatotalarticulo);
            $('#ivab').change(sumatotalarticulo);
            $('#cantidad').change(sumatotalarticulo);

            function sumatotalarticulo() {

                let preciouni = $('#precio_compra_subtotal_unitario').val();
                let cantidad = $('#cantidad').val();
                let preciosubtotal = 0;

                preciouni = preciouni.replaceAll('.', '');
                preciosubtotal = parseInt(preciouni) * parseInt(cantidad);

                let ivax = $('#cantidad_iva_total').val();
                ivax = ivax.replaceAll('.', '');


                const preciototal = (parseInt(ivax) + parseInt(preciosubtotal));

                $('#precio_compra_subtotal').val(preciosubtotal);
                $('#precio_compra_total').val(preciototal);


            }







            //Select para cargar los articulos de la tabla

            $("#codigo").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Buscar articulo....',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectarticulo3') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,

                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.codigo + "=>" + datas.nombre + "=>" + datas.marca,
                                    id: datas.id

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#codigo').change(listasdetallearticulo);


            function listasdetallearticulo() {

                $("#molecula").val('');
                $("#cums").val('');
                $("#marca").val('');
                $("#nombrea").val('');
                $("#presentacion").val('');

                const ids1 = $('#codigo').val();
               
             
                const url =  "{{ route('detallearticulos3', ':id') }}".replace(':id', ids1);

                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(detalle) {
                        $.each(detalle, function(i, items) {

                            $("#molecula").val(items.codigo);
                            $("#cums").val(items.cums);
                            $("#marca").val(items.marca);
                            $("#nombrea").val(items.nombre);
                            $("#presentacion").val(items.forma);

                        });
                    }
                });
            }

         
            //Cargar select del iva
            

            //Select para cargar los proveedores de la tabla

            $("#proveedor_id").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione un proveedor',
                allowClear: true,
                ajax: {
                    url: "{{ route('proveedoreslist3') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,

                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.codigo_tercero + "=>" + datas.nombre_sucursal,
                                    id: datas.id

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#proveedor_id').change(listasdetalleproveedor);

            function listasdetalleproveedor() {

                $("#codigop").val('');
                $("#nombrep").val('');

                const ids1 = $('#proveedor_id').val();

                const url = "{{ route('proveedor3', ':id') }}".replace(':id', ids1);
                
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(showproveedor) {
                        $.each(showproveedor, function(i, items) {

                            console.log(showproveedor)

                            $("#codigop").val(items.codigo_tercero);

                            $("#nombrep").val(items.nombre_sucursal);

                        });

                    }

                });
            }

            $("#farmacia").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Buscar farmacia....',
                allowClear: true,
                
            });

            // Función para traer el contrato de la tabla listas detalle
            $("#contrato").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Buscar contrato....',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectcont') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 37 ,id2:38, id3:39, id4:40
    
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {
    
                                return {
    
                                    text: datas.slug + "=>" + datas.nombre,
                                    id: datas.id
    
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            
            
            
            // Función para traer el consecutivo del documento seleccionado


            //Select para cargar los documentos de la tabla

            $("#documento").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione un documento',
                allowClear: true,
                ajax: {
                    url: "{{ route('documentoslist3') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,

                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.documento,
                                    id: datas.documento

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#documento').change(listasdocumento);


            function listasdocumento() {

                $("#consecutivo").val('');

                const ids1 = $('#documento').val();
                
                const url = "{{ route('detalledocumento3', ':id') }}".replace(':id', ids1);
             
                
              
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(documento) {
                        $.each(documento, function(i, items) {

                            $("#consecutivo").val(items.consecutivo);

                        });
                    }
                });
            }

            



            $(document).on('click', '.create_cuenta', function() {
                $('#form-generalc')[0].reset();
                $('#cardtitle').text('Estas creando una nueva cuenta');
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#card-drawel1').removeClass('card card-warning');
                $('#card-drawel1').addClass('card card-info');
                $('#cardtabscuenta').removeClass('card card-warning card-tabs');
                $('#cardtabscuenta').addClass('card card-info card-tabs');
                $('#modal-cuenta').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal-cuenta').modal('show');

            });



            $('#create_ingreso').click(function() {
                $('#form-general')[0].reset();
                $('.card-title').text('Estas creando un nuevo ingreso');
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#form_result').html('');
                $('#card-drawel').removeClass('card card-warning');
                $('#card-drawel').addClass('card card-info');
                $('#cardtabspro').removeClass('card card-warning card-tabs');
                $('#cardtabspro').addClass('card card-info card-tabs');
                $('#cuenta').val('').trigger('change');
                $('#proveedor_id').val('').trigger('change');
                $('#tipoingreso').val('').trigger('change');
                $('#sede_ips').val('').trigger('change');

                $('#modal-ingreso').modal({
                    backdrop: 'static',
                    keyboard: false
                });



            });


            $(document).on('click', '.addingreso', function(event) {
                event.preventDefault();
                var url = '';
                var method = '';
                var text = '';

                if ($('#action').val() == 'Add') {
                    text = "Estás por crear una Orden de Compra"
                    url = "{{ route('compras_store3') }}";
                    method = 'post';
                }

                if ($('#action').val() == 'Edit') {
                    text = "Estás por actualizar un ingreso"
                    var updateid = $('#hidden_id').val();
                    url = "inventario/editentradas" + updateid;
                    method = 'put';
                }
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: text,
                    icon: "success",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                                icon: "info",
                                title: 'Espere por favor !',
                                html: 'Realizando la creacion..', // add html attribute if you want or remove
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                },
                            }),
                            $.ajax({
                                url: url,
                                method: method,
                                data: $('#form-general').serialize(),
                                dataType: "json",
                                success: function(data) {
                                    if (data.success == 'ok') {
                                        $('#form-general')[0].reset();
                                        $('#modal-ingreso').modal('hide');
                                        $('#ingresos').DataTable().ajax.reload();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'ingreso creado correctamente',
                                            showConfirmButton: false,
                                            timer: 1500

                                        })
                                        // Manteliviano.notificaciones('cliente creado correctamente', 'Sistema Ventas', 'success');

                                    } else if (data.success == 'ok1') {
                                        $('#form-general')[0].reset();
                                        $('#modal-ingreso').modal('hide');
                                        $('#ingresos').DataTable().ajax.reload();
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'ingreso actualizado correctamente',
                                            showConfirmButton: false,
                                            timer: 1500

                                        })
                                        // Manteliviano.notificaciones('cliente actualizado correctamente', 'Sistema Ventas', 'success');

                                    } else if (data.errors != null) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: data.errors,
                                            showConfirmButton: false,
                                            timer: 3000
                                        })
                                    }
                                }


                            }).fail(function(jqXHR, textStatus, errorThrown) {

                                if (jqXHR.status === 422) {

                                    var error = jqXHR.responseJSON;

                                    $.each(error, function(i, items) {

                                        var errores = [];
                                        errores.push(items.numeroingreso + '<br>');
                                        errores.push(items.tipoingreso + '<br>');
                                        errores.push(items.formadepago + '<br>');
                                        errores.push(items.fechadeingreso + '<br>');
                                        errores.push(items.totalingreso + '<br>');
                                        errores.push(items.observacion + '<br>');
                                        errores.push(items.cuenta_id + '<br>');
                                        errores.push(items.user_id + '<br>');
                                        errores.push(items.proveedor_id + '<br>');


                                        console.log(errores);

                                        var filtered = errores.filter(function(el) {
                                            return el != "undefined<br>";
                                        });

                                        console.log(filtered);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'El formulario contiene errores',
                                            html: filtered,
                                            showConfirmButton: true,
                                            //timer: 1500
                                        })


                                        //Manteliviano.notificaciones(items, 'Sistema Ventas', 'warning');

                                    });
                                }
                            });
                    }
                });


            });


            // Edición de proveedor

            $(document).on('click', '.edit_ingreso', function() {

                $('#form-general')[0].reset();
                var id = $(this).attr('id');

                $.ajax({
                    url: "editingresos/" + id,
                    dataType: "json",
                    success: function(data) {


                        // Primer form de información empleado
                        $('#tipodocumento').val(data.proveedor.tipodocumento);
                        $('#documento').val(data.proveedor.documento);
                        $('#nombre').val(data.proveedor.nombre);
                        $('#telefono').val(data.proveedor.telefono);
                        $('#direccion').val(data.proveedor.direccion);
                        $('#correo').val(data.proveedor.correo);

                        var newpais = new Option(data.proveedor.pais, data.proveedor.pais, true,
                            true);
                        $('#pais').append(newpais).trigger('change');

                        var newdpto = new Option(data.proveedor.dpto, data.proveedor.dpto, true,
                            true);
                        $('#dpto').append(newdpto).trigger('change');


                        var newcity = new Option(data.proveedor.ciudad, data.proveedor.ciudad,
                            true,
                            true);
                        $('#ciudad').append(newcity).trigger('change');






                        $('#hidden_id').val(id)
                        $('.card-title').text("Editando proveedor: " + data.proveedor.nombre +
                            "-" + data.proveedor.documento);
                        $('#card-drawel').removeClass('card card-info');
                        $('#card-drawel').addClass('card card-warning');
                        $('#cardtabspro').removeClass('card card-info card-tabs');
                        $('#cardtabspro').addClass('card card-warning card-tabs');
                        $('#action_button').val('Editar').removeClass('btn-sucess')
                        $('#action_button').addClass('btn-danger')
                        $('#action_button').val('Edit');
                        $('#action').val('Edit');
                        $('#modal-proveedor').modal('show');

                    },



                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 403) {

                        Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                            'Sistema cuentas por pagar', 'warning');

                    }
                });

            });
     


        // Agregar una cuenta
        $(document).on('click', '.addcuenta', function(event) {
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';

            if ($('#action').val() == 'Add') {
                text = "Estás por crear un proveedor"
                url = "{{ route('proveedores_store3') }}";
                method = 'post';
            }

            if ($('#action').val() == 'Edit') {
                text = "Estás por actualizar una proveedor"
                var updateid = $('#hidden_id').val();
                url = "editproveedores/" + updateid;
                method = 'put';
            }
            Swal.fire({
                    icon: "info",
                    title: 'Espere por favor !',
                    html: 'Realizando la programación..', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),
                $.ajax({
                    url: url,
                    method: method,
                    data: $('#form-generalc').serialize(),
                    dataType: "json",
                    success: function(data) {
                        if (data.success == 'ok') {
                            $('#form-generalc')[0].reset();
                            $('#modal-cuenta').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Proveedor creado correctamente',
                                showConfirmButton: false,
                                timer: 1500

                            })
                            // Manteliviano.notificaciones('cliente creado correctamente', 'Sistema Ventas', 'success');

                        } else if (data.success == 'ok1') {
                            $('#form-generalc')[0].reset();
                            $('#modal-cuenta').modal('hide');
                            Swal.fire({
                                icon: 'warning',
                                title: 'Proveedor actualizado correctamente',
                                showConfirmButton: false,
                                timer: 1500

                            })
                            // Manteliviano.notificaciones('cliente actualizado correctamente', 'Sistema Ventas', 'success');

                        } else if (data.errors != null) {
                            Swal.fire({
                                icon: 'error',
                                title: data.errors,
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    }


                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 422) {

                        var error = jqXHR.responseJSON;

                        $.each(error, function(i, items) {

                            var errores = [];
                            errores.push(items.nombrecuenta + '<br>');
                            errores.push(items.tipodecuenta + '<br>');
                            errores.push(items.observacion + '<br>');
                            errores.push(items.sede_id + '<br>');
                            errores.push(items.user_id + '<br>');


                            console.log(errores);

                            var filtered = errores.filter(function(el) {
                                return el != "undefined<br>";
                            });

                            
                            Swal.fire({
                                icon: 'error',
                                title: 'El formulario contiene errores',
                                html: filtered,
                                showConfirmButton: true,
                                //timer: 1500
                            })


                            //Manteliviano.notificaciones(items, 'Sistema Ventas', 'warning');

                        });
                    }
                });

        });
        
    

            
        
        
    // Función que envía los datos de listas al controlador ademas controla los input con sweat alert2
    
      //Funcion que abre modal donde se deben seleccionar el articulo que se iran cargando en la factura
            $('#agregar_articulo').click(function() {

                $('#form-general_1')[0].reset();
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#form_result_1').html('');
                $('#modal-articulos').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal-articulos').modal('show');
            });



            // Agregar filas a tabla para guardar
    function AgregarMedicamentosMasivos(data) {
            data.forEach(function(item) {
        
        
             const total = parseFloat(item.cantidad * item.precio);
                    var valordelpagosubtotalFormatted = parseFloat(total)
                        .toLocaleString('es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        });

                   

                    var valordelpagoivatotalFormatted = parseFloat(0)
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });

        let valordelpagocompraFormatted = parseFloat(item.precio)
            .toLocaleString('es-CO', { style: 'currency', currency: 'COP' });

        $('#tcups> tbody:last-child').append(
            '<tr>' +
            '<td><button type="button" name="eliminar" class="btn-float bg-gradient-danger btn-sm tooltipsC" title="eliminar"><i class="fas fa-trash"></i></button></td>' +
            '<td class="molecula">' + (item.codigo ? item.codigo : 'Sin Código') + '</td>' +
            '<td class="nombrea">' + item.nombre + '</td>' +
            '<td class="presentacion">' + item.presentacion + '</td>' +
            '<td class="marca">' + item.marca + '</td>' +
            '<td class="cums">' + (item.cums ? item.cums : "Sin Cums") + '</td>' +
            '<td class="precio">' + valordelpagocompraFormatted + '</td>' +
            '<td class="ivab">' + 0 + '</td>' +
            '<td class="iva">' + 0 + '</td>' +
            '<td class="cantidad">' + item.cantidad + '</td>' +
            '<td class="precio_compra_subtotal">' + valordelpagosubtotalFormatted + '</td>' +
            '<td class="cantidad_iva_total">' + valordelpagoivatotalFormatted + '</td>' +
            '<td class="precio_compra_total">' +valordelpagosubtotalFormatted + '</td></tr>'  +
            '</tr>'
        );
        
    });

    calcularTotales();
}
       
   });


        // Función para multimodal

        (function($, window) {
            'use strict';

            var MultiModal = function(element) {
                this.$element = $(element);
                this.modalCount = 0;
            };

            MultiModal.BASE_ZINDEX = 1040;

            MultiModal.prototype.show = function(target) {
                var that = this;
                var $target = $(target);
                var modalIndex = that.modalCount++;

                $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);

                // Bootstrap triggers the show event at the beginning of the show function and before
                // the modal backdrop element has been created. The timeout here allows the modal
                // show function to complete, after which the modal backdrop will have been created
                // and appended to the DOM.
                window.setTimeout(function() {
                    // we only want one backdrop; hide any extras
                    if (modalIndex > 0)
                        $('.modal-backdrop').not(':first').addClass('hidden');

                    that.adjustBackdrop();
                });
            };

            MultiModal.prototype.hidden = function(target) {
                this.modalCount--;

                if (this.modalCount) {
                    this.adjustBackdrop();
                    // bootstrap removes the modal-open class when a modal is closed; add it back
                    $('body').addClass('modal-open');
                }
            };

            MultiModal.prototype.adjustBackdrop = function() {
                var modalIndex = this.modalCount - 1;
                $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
            };

            function Plugin(method, target) {
                return this.each(function() {
                    var $this = $(this);
                    var data = $this.data('multi-modal-plugin');

                    if (!data)
                        $this.data('multi-modal-plugin', (data = new MultiModal(this)));

                    if (method)
                        data[method](target);
                });
            }

            $.fn.multiModal = Plugin;
            $.fn.multiModal.Constructor = MultiModal;

            $(document).on('show.bs.modal', function(e) {
                $(document).multiModal('show', e.target);
            });

            $(document).on('hidden.bs.modal', function(e) {
                $(document).multiModal('hidden', e.target);
            });
        }(jQuery, window));


        var idioma_espanol = {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla =(",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        }
    </script>
@endsection