@extends('layouts.app')

@section('titulo')
Pendientes Medcol
@endsection
@section("styles")

<link href="{{asset("assets/lte/plugins/sweetalert2/sweetalert2.min.css")}}" rel="stylesheet" type="text/css" />
{{-- <link href="{{asset("assets/lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css")}}" rel="stylesheet" type="text/css" /> --}}
<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/lte/plugins/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/botones.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/modal-form.css")}}" rel="stylesheet" type="text/css" />

@endsection

@section('scripts')


<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script> -->

@endsection

@section('content')
<div class="loaders"><img src="{{asset("assets/lte/dist/img/loader6.gif")}}" class="" /> </div>

@include('menu.Medcol6.form.forminforme')
@include('menu.Medcol6.tabs.tabsIndexAnalista')

@include('menu.Medcol6.modal.modalPendientes')
@include('menu.Medcol6.modal.modalDetallePendiente')

@include('menu.Medcol6.modal.modalindexresumenpendientes')
@include('menu.Medcol6.modal.modalIndicadoresPendientes')


@endsection

@section("scriptsPlugins")
<script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>


<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        // Cargar datos al abrir el dropdown
        $('#dropdownPendientes').on('show.bs.dropdown', function() {
            const pacienteId = $('#historia').val(); // Obtener ID del paciente

            $.ajax({
                url: `/api/pacientes/${pacienteId}/pendientes`,
                method: 'GET',
                success: function(data) {
                    const tbody = $('#tablaPendientes tbody').empty();

                    data.forEach(pendiente => {
                        tbody.append(`
                        <tr>
                            <td>${moment(pendiente.fecha).format('DD/MM/YYYY')}</td>
                            <td>${pendiente.codigo}</td>
                            <td>${pendiente.molecula}</td>
                            <td>${pendiente.documento}</td>
                            <td>${pendiente.numero}</td>
                            <td>
                                <span class="estado-badge estado-${pendiente.estado}">
                                    ${pendiente.estado}
                                </span>
                            </td>
                        </tr>
                    `);
                    });

                    $('#contadorPendientes').text(`Mostrando ${data.length} registros`);
                },
                error: function() {
                    $('#tablaPendientes tbody').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            Error al cargar los datos
                        </td>
                    </tr>
                `);
                }
            });
        });

        // Cerrar dropdown al hacer clic fuera
        $('body').on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').hide();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {

        fill_datatable1_resumen();

        function fill_datatable1_resumen() {
            $("#detalle, #detalle1, #detalle2, #detalle3, #detalle4, #detalle5").empty();

            $.ajax({
                url: "{{ route('medcol6.informe') }}",
                dataType: "json",
                success: function(data) {
                    const cardsData = [{
                            id: "#detalle",
                            title: "TOTAL PENDIENTES",
                            value: data.pendientes || 0,
                            icon: "fas fa-notes-medical",
                            bgColor: "l-bg-blue-dark"
                        },
                        {
                            id: "#detalle1",
                            title: "TOTAL ENTREGADOS",
                            value: data.entregados || 0,
                            icon: "fas fa-briefcase-medical",
                            bgColor: "l-bg-green-dark"
                        },
                        {
                            id: "#detalle2",
                            title: "EN TRAMITE",
                            value: data.tramitados || 0,
                            icon: "fas fa-comment-medical",
                            bgColor: "l-bg-orange-dark"
                        },
                        {
                            id: "#detalle3",
                            title: "ANULADOS",
                            value: data.anulados || 0,
                            icon: "fas fa-hospital",
                            bgColor: "l-bg-red-dark"
                        },
                        {
                            id: "#detalle4",
                            title: "DESABASTECIDOS",
                            value: data.agotados || 0,
                            icon: "fas fa-exclamation-triangle",
                            bgColor: "l-bg-orange-dark"
                        },
                        {
                            id: "#detalle5",
                            title: "VENCIDOS",
                            value: data.vencidos || 0,
                            icon: "fas fa-clock",
                            bgColor: "l-bg-gray-dark"
                        }
                    ];

                    cardsData.forEach(card => {
                        $(card.id).append(createCard(card.title, card.value, card.icon, card.bgColor));
                    });
                }
            });
        }

        function createCard(title, value, icon, bgColor) {
            return `
                <div class="small-box shadow-lg ${bgColor}">
                    <div class="inner">
                        <h5>${title}</h5>
                        <p><h5>${value}</h5></p>
                    </div>
                     <a class="informependientes" id="informependientesclic" href="#">
                    <div class="icon">
                       <i class="${icon}"></i>
                    </div>
                    </a>
                </div>
            `;
        }

        function mostrarOcultarCampos() {
            // CORREGIDO: Usar .val() en lugar de .text() para obtener el valor del option
            var estado_valor = $('#estado').val(); // Obtener el valor directamente
            console.log('🔄 mostrarOcultarCampos() llamada con estado:', estado_valor);

            var futuro1 = $('#futuro1');
            var futuro2 = $('#futuro2');
            var futuro3 = $('#futuro3');
            var futuro4 = $('#futuro4');

            var enviar_fecha_entrega = $('#enviar_fecha_entrega');
            var enviar_fecha_impresion = $('#enviar_fecha_impresion');
            var enviar_fecha_anulado = $('#enviar_fecha_anulado');
            var enviar_factura_entrega = $('#enviar_factura_entrega');

            var input1 = $('#fecha_entrega');
            var input2 = $('#fecha_impresion');
            var anulado = $('#fecha_anulado');
            var input3 = $('#cantord');
            var input4 = $('#cantdpx');

            // Ocultar todos los campos primero
            futuro1.hide().addClass('hidden');
            futuro2.hide().addClass('hidden');
            futuro3.hide().addClass('hidden');
            futuro4.hide().addClass('hidden');

            // Resetear campos ocultos
            enviar_fecha_entrega.val('false');
            enviar_fecha_impresion.val('false');
            enviar_fecha_anulado.val('false');
            enviar_factura_entrega.val('false');

            // CORREGIDO: Usar el valor en lugar del texto y agregar todos los estados
            switch (estado_valor) {
                case "ENTREGADO":
                    console.log('✅ Mostrando campos para ENTREGADO');
                    futuro1.show().removeClass('hidden');
                    enviar_fecha_entrega.val('true');
                    enviar_factura_entrega.val('true');

                    // Limpiar otros campos
                    input2.val('');
                    anulado.val('');

                    // Auto-completar cantidad entregada con cantidad ordenada
                    var cant_ordenada = parseInt(input3.val()) || 0;
                    if (cant_ordenada > 0 && !input4.val()) {
                        input4.val(cant_ordenada);
                    }
                    break;

                case "DESABASTECIDO":
                    console.log('✅ Mostrando campos para DESABASTECIDO');
                    futuro2.show().removeClass('hidden');
                    enviar_fecha_impresion.val('true');

                    // Limpiar otros campos
                    input1.val('');
                    anulado.val('');
                    break;

                case "ANULADO":
                    console.log('✅ Mostrando campos para ANULADO');
                    futuro4.show().removeClass('hidden');
                    enviar_fecha_anulado.val('true');

                    // Limpiar otros campos
                    input1.val('');
                    input2.val('');
                    break;

                case "PENDIENTE":
                    console.log('✅ Mostrando campos para PENDIENTE');
                    futuro3.show().removeClass('hidden');

                    // Limpiar todos los campos
                    input1.val('');
                    input2.val('');
                    anulado.val('');
                    break;

                case "TRAMITADO":
                    console.log('✅ Mostrando campos para TRAMITADO');
                    futuro2.show().removeClass('hidden');
                    enviar_fecha_impresion.val('true');

                    // Limpiar otros campos
                    input1.val('');
                    anulado.val('');
                    break;

                default:
                    console.log('⚠️ Estado no reconocido:', estado_valor);
                    // Limpiar todo
                    input1.val('');
                    input2.val('');
                    anulado.val('');
                    break;
            }

            // Llamar también al handler del nuevo sistema si existe
            if (window.pendientesFormStatusHandler) {
                window.pendientesFormStatusHandler(estado_valor);
            }

            // Recalcular cantidades pendientes si existe la función
            if (window.pendientesFormManager && window.pendientesFormManager.calculatePendingQuantity) {
                window.pendientesFormManager.calculatePendingQuantity();
            }
        }

        $('#estado').change(mostrarOcultarCampos);

        // Función que envia el id al controlador y cambia el estado del registro
        $(document).on('click', '.agenda', function() {
            var data = {
                id: $(this).attr('value'),
                _token: $('input[name=_token]').val()
            };

            ajaxRequest('agendado', data);
        });

        function ajaxRequest(url, data) {
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(data) {
                    $('#pendientes').DataTable().ajax.reload();
                    $('#porentregar').DataTable().ajax.reload();
                    // $('#entregados').DataTable().ajax.reload();
                    // $('#tanulados').DataTable().ajax.reload();
                    // $('#tdesabastecidos').DataTable().ajax.reload();
                    Manteliviano.notificaciones(data.respuesta, data.titulo, data.icon);
                }
            });
        }


        var fechaini;
        var fechafin;
        var contrato;

        // Función para llenar la tabla al cargar la página
        fill_datatable_tabla();

        // Callback para filtrar los datos de la tabla y detalle
        $('#buscar').click(function() {
            fechaini = $('#fechaini').val();
            fechafin = $('#fechafin').val();
            contrato = $('#contrato').val();

            // Validación mejorada
            if ((fechaini != '' && fechafin != '') || contrato != '') {

                // Destruir las DataTables antes de llenarlas nuevamente
                $('#pendientes').DataTable().destroy();
                $("#tdesabastecidos").DataTable().destroy();
                $("#porentregar").DataTable().destroy();
                $("#entregados").DataTable().destroy();

                // Llamar a la función para llenar la tabla con los filtros aplicados
                fill_datatable_tabla(fechaini, fechafin, contrato);

            } else {
                // Mostrar alerta si no se ingresan las fechas o el contrato
                Swal.fire({
                    title: 'Debes digitar fecha inicial y fecha final o la Droguería',
                    icon: 'warning',
                    confirmButtonText: 'Cerrar'
                });
            }
        });

        $('#reset').click(function() {

            $('#fechaini').val('');
            $('#fechafin').val('');
            $('#contrato').val('');

            $('#pendientes').DataTable().destroy();
            $("#tdesabastecidos").DataTable().destroy();
            $("#porentregar").DataTable().destroy();
            $("#entregados").DataTable().destroy();
            fill_datatable_tabla();
        });


        function fill_datatable_tabla(fechaini = '', fechafin = '', contrato = '') {
            $(function() {
                // Se llama a la función correspondiente al tab activo al cargar la página
                var activeTab = $(".nav-tabs .active");
                var activeTabId = activeTab.attr("id");
                callFunction(activeTabId);

                // Se llama a la función correspondiente al tab seleccionado al cambiar de tab
                $('a[data-toggle="pill"]').on("shown.bs.tab", function(e) {
                    var target = $(e.target);
                    var targetId = target.attr("id");
                    callFunction(targetId);
                });

                function callFunction(tabId) {
                    if (tabId === "custom-tabs-one-datos-del-paciente-tab") {
                        // Llamar a la función correspondiente al tab "Pendientes"
                        /* console.log("Pendientes"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#pendientes")) {
                            $("#pendientes").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista de pendientes
                        var datatable =
                            $('#pendientes').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, 5000, 10000, -1],
                                    [25, 50, 100, 500, 5000, 10000, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [1, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.pendientes1')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        contrato: contrato,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'action',
                                        orderable: false
                                    },
                                    {
                                        data: 'id'
                                    },
                                    {
                                        data: 'Tipodocum'
                                    },
                                    {
                                        data: 'cantdpx'
                                    },
                                    {
                                        data: 'cantord'
                                    },
                                    {
                                        data: 'fecha_factura'
                                    },
                                    {
                                        data: 'fecha'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'apellido1'
                                    },
                                    {
                                        data: 'apellido2'
                                    },
                                    {
                                        data: 'nombre1'
                                    },
                                    {
                                        data: 'nombre2'
                                    },
                                    {
                                        data: 'cantedad'
                                    },
                                    {
                                        data: 'direcres'
                                    },
                                    {
                                        data: 'telefres'
                                    },
                                    {
                                        data: 'documento'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'nombre'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'cantidad'
                                    },
                                    {
                                        data: 'cajero'
                                    },
                                    {
                                        data: 'usuario'
                                    },
                                    {
                                        data: 'estado'
                                    },
                                    {
                                        data: 'fecha_impresion'
                                    },
                                    {
                                        data: 'fecha_entrega'
                                    },
                                    {
                                        data: 'centroproduccion'
                                    },
                                    {
                                        data: 'municipio'
                                    }
                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-success btn-sm"


                                    },
                                    {

                                        extend: 'csvHtml5',
                                        titleAttr: 'Exportar csv',
                                        className: "btn  btn-outline-warning btn-sm"

                                    },
                                    {

                                        extend: 'pdfHtml5',
                                        titleAttr: 'Exportar pdf',
                                        className: "btn  btn-outline-secondary btn-sm"


                                    }
                                ],

                            });

                    } else if (tabId === "custom-tabs-one-datos-agendados-tab") {
                        // Llamar a la función correspondiente al tab "En Tramite"
                        /* console.log("Pagos Parciales"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#porentregar")) {
                            $("#porentregar").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista En Tramite
                        var datatable =
                            $('#porentregar').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [1, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.porentregar')}}",
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'action',
                                        orderable: false
                                    },
                                    {
                                        data: 'id'
                                    },
                                    {
                                        data: 'Tipodocum'
                                    },
                                    {
                                        data: 'cantdpx'
                                    },
                                    {
                                        data: 'cantord'
                                    },
                                    {
                                        data: 'fecha_factura'
                                    },
                                    {
                                        data: 'fecha'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'apellido1'
                                    },
                                    {
                                        data: 'apellido2'
                                    },
                                    {
                                        data: 'nombre1'
                                    },
                                    {
                                        data: 'nombre2'
                                    },
                                    {
                                        data: 'cantedad'
                                    },
                                    {
                                        data: 'direcres'
                                    },
                                    {
                                        data: 'telefres'
                                    },
                                    {
                                        data: 'documento'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'nombre'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'cantidad'
                                    },
                                    {
                                        data: 'cajero'
                                    },
                                    {
                                        data: 'usuario'
                                    },
                                    {
                                        data: 'estado'
                                    },
                                    {
                                        data: 'fecha_impresion'
                                    },
                                    {
                                        data: 'fecha_entrega'
                                    },
                                    {
                                        data: 'centroproduccion'
                                    }
                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-success btn-sm"


                                    },
                                    {

                                        extend: 'csvHtml5',
                                        titleAttr: 'Exportar csv',
                                        className: "btn  btn-outline-warning btn-sm"

                                    },
                                    {

                                        extend: 'pdfHtml5',
                                        titleAttr: 'Exportar pdf',
                                        className: "btn  btn-outline-secondary btn-sm"


                                    }
                                ],

                            });

                    } else if (tabId === "custom-tabs-one-datos-seguimiento-tab") {
                        // Llamar a la función correspondiente al tab "En Tramite"
                        /* console.log("Pagos Parciales"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#entregados")) {
                            $("#entregados").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista de entregados
                        var datatable =
                            $('#entregados').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [1, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.entregados')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        contrato: contrato,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'action',
                                        orderable: false
                                    },
                                    {
                                        data: 'id'
                                    },
                                    {
                                        data: 'Tipodocum'
                                    },
                                    {
                                        data: 'cantdpx'
                                    },
                                    {
                                        data: 'cantord'
                                    },
                                    {
                                        data: 'fecha_factura'
                                    },
                                    {
                                        data: 'fecha'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'apellido1'
                                    },
                                    {
                                        data: 'apellido2'
                                    },
                                    {
                                        data: 'nombre1'
                                    },
                                    {
                                        data: 'nombre2'
                                    },
                                    {
                                        data: 'cantedad'
                                    },
                                    {
                                        data: 'direcres'
                                    },
                                    {
                                        data: 'telefres'
                                    },
                                    {
                                        data: 'documento'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'nombre'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'cantidad'
                                    },
                                    {
                                        data: 'cajero'
                                    },
                                    {
                                        data: 'usuario'
                                    },
                                    {
                                        data: 'estado'
                                    },
                                    {
                                        data: 'fecha_impresion'
                                    },
                                    {
                                        data: 'fecha_entrega'
                                    },
                                    {
                                        data: 'centroproduccion'
                                    }
                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-success btn-sm"


                                    },
                                    {

                                        extend: 'csvHtml5',
                                        titleAttr: 'Exportar csv',
                                        className: "btn  btn-outline-warning btn-sm"

                                    },
                                    {

                                        extend: 'pdfHtml5',
                                        titleAttr: 'Exportar pdf',
                                        className: "btn  btn-outline-secondary btn-sm"


                                    }
                                ],

                            });

                    } else if (tabId === "custom-tabs-one-datos-desabastecido-tab") {
                        // Llamar a la función correspondiente al tab "En Tramite"
                        /* console.log("Pagos Parciales"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#tdesabastecidos")) {
                            $("#tdesabastecidos").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista de pendientes desabastecidos
                        var datatable =
                            $('#tdesabastecidos').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [1, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.desabastecidos')}}",
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'action',
                                        orderable: false
                                    },
                                    {
                                        data: 'id'
                                    },
                                    {
                                        data: 'Tipodocum'
                                    },
                                    {
                                        data: 'cantdpx'
                                    },
                                    {
                                        data: 'cantord'
                                    },
                                    {
                                        data: 'fecha_factura'
                                    },
                                    {
                                        data: 'fecha'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'apellido1'
                                    },
                                    {
                                        data: 'apellido2'
                                    },
                                    {
                                        data: 'nombre1'
                                    },
                                    {
                                        data: 'nombre2'
                                    },
                                    {
                                        data: 'cantedad'
                                    },
                                    {
                                        data: 'direcres'
                                    },
                                    {
                                        data: 'telefres'
                                    },
                                    {
                                        data: 'documento'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'nombre'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'cantidad'
                                    },
                                    {
                                        data: 'cajero'
                                    },
                                    {
                                        data: 'usuario'
                                    },
                                    {
                                        data: 'estado'
                                    },
                                    {
                                        data: 'fecha_impresion'
                                    },
                                    {
                                        data: 'fecha_entrega'
                                    },
                                    {
                                        data: 'centroproduccion'
                                    }
                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-success btn-sm"


                                    },
                                    {

                                        extend: 'csvHtml5',
                                        titleAttr: 'Exportar csv',
                                        className: "btn  btn-outline-warning btn-sm"

                                    },
                                    {

                                        extend: 'pdfHtml5',
                                        titleAttr: 'Exportar pdf',
                                        className: "btn  btn-outline-secondary btn-sm"


                                    }
                                ],

                            });

                    } else if (tabId === "custom-tabs-one-datos-anulado-tab") {
                        // Llamar a la función correspondiente al tab "En Tramite"
                        /* console.log("Pagos Parciales"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#tanulados")) {
                            $("#tanulados").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista de pendientes anulados
                        var datatable =
                            $('#tanulados').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [1, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.anulados')}}",
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'action',
                                        orderable: false
                                    },
                                    {
                                        data: 'id'
                                    },
                                    {
                                        data: 'Tipodocum'
                                    },
                                    {
                                        data: 'cantdpx'
                                    },
                                    {
                                        data: 'cantord'
                                    },
                                    {
                                        data: 'fecha_factura'
                                    },
                                    {
                                        data: 'fecha'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'apellido1'
                                    },
                                    {
                                        data: 'apellido2'
                                    },
                                    {
                                        data: 'nombre1'
                                    },
                                    {
                                        data: 'nombre2'
                                    },
                                    {
                                        data: 'cantedad'
                                    },
                                    {
                                        data: 'direcres'
                                    },
                                    {
                                        data: 'telefres'
                                    },
                                    {
                                        data: 'documento'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'nombre'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'cantidad'
                                    },
                                    {
                                        data: 'cajero'
                                    },
                                    {
                                        data: 'usuario'
                                    },
                                    {
                                        data: 'estado'
                                    },
                                    {
                                        data: 'fecha_anulado'
                                    },
                                    {
                                        data: 'fecha_entrega'
                                    },
                                    {
                                        data: 'centroproduccion'
                                    }
                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-success btn-sm"


                                    },
                                    {

                                        extend: 'csvHtml5',
                                        titleAttr: 'Exportar csv',
                                        className: "btn  btn-outline-warning btn-sm"

                                    },
                                    {

                                        extend: 'pdfHtml5',
                                        titleAttr: 'Exportar pdf',
                                        className: "btn  btn-outline-secondary btn-sm"


                                    }
                                ],

                            });

                    } // aqui va la tabla de los vencidos 
                    else if (tabId === "custom-tabs-one-datos-vencidos-tab") {
                        // Llamar a la función correspondiente al tab "En Tramite"
                        /* console.log("Pagos Parciales"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#tvencidos")) {
                            $("#tvencidos").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista de pendientes vencidos
                        var datatable =
                            $('#tvencidos').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [1, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.vencidos')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        contrato: contrato,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'action',
                                        orderable: false
                                    },
                                    {
                                        data: 'id'
                                    },
                                    {
                                        data: 'Tipodocum'
                                    },
                                    {
                                        data: 'cantdpx'
                                    },
                                    {
                                        data: 'cantord'
                                    },
                                    {
                                        data: 'fecha_factura'
                                    },
                                    {
                                        data: 'fecha'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'apellido1'
                                    },
                                    {
                                        data: 'apellido2'
                                    },
                                    {
                                        data: 'nombre1'
                                    },
                                    {
                                        data: 'nombre2'
                                    },
                                    {
                                        data: 'cantedad'
                                    },
                                    {
                                        data: 'direcres'
                                    },
                                    {
                                        data: 'telefres'
                                    },
                                    {
                                        data: 'documento'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'nombre'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'cantidad'
                                    },
                                    {
                                        data: 'cajero'
                                    },
                                    {
                                        data: 'usuario'
                                    },
                                    {
                                        data: 'estado'
                                    },
                                    {
                                        data: 'fecha_anulado'
                                    },
                                    {
                                        data: 'fecha_entrega'
                                    },
                                    {
                                        data: 'centroproduccion'
                                    }
                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Control de horas",
                                        className: "btn  btn-outline-success btn-sm"


                                    },
                                    {

                                        extend: 'csvHtml5',
                                        titleAttr: 'Exportar csv',
                                        className: "btn  btn-outline-warning btn-sm"

                                    },
                                    {

                                        extend: 'pdfHtml5',
                                        titleAttr: 'Exportar pdf',
                                        className: "btn  btn-outline-secondary btn-sm"


                                    }
                                ],

                            });

                    }
                }

            });
        }





        //--------------------------------Tabla relacion Observaciones y los documentos pendientes----------------------------//
        function fill_datatable_f(nivel_idp2 = '') {
            var tobservaciones = $('#tobservaciones').DataTable({
                language: idioma_espanol,
                processing: true,
                lengthMenu: [
                    [25, 50, 100, 500, -1],
                    [25, 50, 100, 500, "Mostrar Todo"]
                ],
                processing: true,
                serverSide: true,
                aaSorting: [
                    [1, "asc"]
                ],
                ajax: {
                    url: "{{ route('medcol6.observaciones')}}",
                    //type: "get",
                    data: {
                        id: nivel_idp2
                    }
                },
                columns: [{
                        data: 'id_obs',
                        name: 'id_obs'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'observacion',
                        name: 'observacion'
                    },
                    {
                        data: 'usuario',
                        name: 'usuario'
                    },
                    {
                        data: 'estado',
                        name: 'estado'
                    }

                ],

                //Botones----------------------------------------------------------------------

                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',


                buttons: [{

                        extend: 'copyHtml5',
                        titleAttr: 'Copiar Registros',
                        title: "seguimiento",
                        className: "btn  btn-outline-primary btn-sm"


                    },
                    {

                        extend: 'excelHtml5',
                        titleAttr: 'Exportar Excel',
                        title: "seguimiento",
                        className: "btn  btn-outline-success btn-sm"


                    },
                    {

                        extend: 'csvHtml5',
                        titleAttr: 'Exportar csv',
                        className: "btn  btn-outline-warning btn-sm"
                        //text: '<i class="fas fa-file-excel"></i>'

                    },
                    {

                        extend: 'pdfHtml5',
                        titleAttr: 'Exportar pdf',
                        className: "btn  btn-outline-secondary btn-sm"


                    }
                ],

            });

        }





        //Función para abrir modal del detalle del pendiente y muestra las observaciones agregadas
        $(document).on('click', '.edit_pendiente', function() {

            $('#form-general1').trigger('reset');
            var id = $(this).attr('id');
            var nivel_idp2 = $(this).attr('id');

            if (nivel_idp2 != '') {

                if ($.fn.DataTable.isDataTable('#tobservaciones')) {
                    $('#tobservaciones').DataTable().destroy();
                    $('#tobservaciones').empty();
                }
                fill_datatable_f(nivel_idp2);
            }


            $.ajax({
                url: "editpendientes/" + id,
                dataType: "json",
                success: function(data) {

                    // Primer form de información pendientes por pagar
                    $('#Tipodocum').val(data.pendiente.Tipodocum);
                    $('#cantdpx').val(data.pendiente.cantdpx);
                    $('#cantord').val(data.pendiente.cantord);
                    const safeDate = (dateStr) => dateStr ? moment(dateStr).format('YYYY-MM-DD') : '';
                    $('#fecha_factura').val(safeDate(data.pendiente.fecha_factura));
                    $('#fecha').val(safeDate(data.pendiente.fecha));
                    //$('#fecha').val(moment(data.pendiente.fecha).format('YYYY-MM-DD'));
                    $('#historia').val(data.pendiente.historia);
                    $('#apellido1').val(data.pendiente.apellido1);
                    $('#apellido2').val(data.pendiente.apellido2);
                    $('#nombre1').val(data.pendiente.nombre1);
                    $('#nombre2').val(data.pendiente.nombre2);
                    const nombreCompleto = `${data.pendiente.nombre1} ${data.pendiente.nombre2} ${data.pendiente.apellido1} ${data.pendiente.apellido2}`;
                    $("#nombre_completo").val(nombreCompleto.trim().replace(/\s+/g, ' '));
                    $('#cantedad').val(data.pendiente.cantedad);
                    $('#direcres').val(data.pendiente.direcres);
                    $('#telefres').val(data.pendiente.telefres);
                    $('#documento').val(data.pendiente.documento);
                    $('#factura').val(data.pendiente.factura);
                    $('#codigo').val(data.pendiente.codigo);
                    $('#nombre').val(data.pendiente.nombre);
                    $('#cant_pndt').val(data.saldo_pendiente);

                    if (data.pendiente.cums == '' || data.pendiente.cums == null) {
                        $('#cums').val(data.pendiente.codigo)
                    } else {
                        $('#cums').val(data.pendiente.cums);
                    }

                    $('#centroproduccion').val(data.pendiente.centroproduccion);

                    // Mapeo de servicios a documentos de entrega
                    var servicioDocMap = {
                        "BIO1": "CDBI",
                        "PAC": "CDPC",
                        "DLR1": "CDDO",
                        "DPA1": "CDDO",
                        "EM01": "CDEM",
                        "FRIO": "CDIO",
                        "EHU1": "CDHU",
                        "FRJA": "CDJA",
                        "FRIO": "CDIO",
                        "BDNT": "EVIO",
                        "SM01": "CDSM"
                    };

                    // Obtener el valor del centro de producción (servicio)
                    var servicio = data.pendiente.centroproduccion;

                    // Actualizar el campo Doc Entrega según el servicio
                    if (servicioDocMap.hasOwnProperty(servicio)) {
                        $('#doc_entrega').val(servicioDocMap[servicio]);
                    } else {
                        $('#doc_entrega').val(''); // Si no hay coincidencia, dejar vacío o mantener un valor por defecto
                    }


                    $('#observ').val(data.pendiente.observaciones);
                    $('#cantidad').val(data.pendiente.cantidad);
                    $('#cajero').val(data.pendiente.cajero);
                    $('#usuario').val(data.pendiente.usuario);
                    $('#estado').val(data.pendiente.estado);
                    /* $('#fecha_impresion').val(data.pendiente.fecha_impresion); */
                    $('#fecha_entrega').val(data.pendiente.fecha_entrega);


                    $('#hidden_id').val(id)
                    $('#edit_pendiente').text("Editando entrega pendiente: " + data.pendiente.documento +
                        "-" + data.pendiente.factura);
                    /* $('#action_button').val('Editar').removeClass('btn-sucess') */
                    /* $('#action_button').addClass('btn-danger') */
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');

                    $('#modal-edit-pendientes').modal('show');

                    // Cargar saldo del medicamento específico después de cargar todos los datos
                    if (data.pendiente.codigo && data.pendiente.centroproduccion) {
                        console.log('🔄 Cargando saldo para medicamento:', {
                            codigo: data.pendiente.codigo,
                            centroproduccion: data.pendiente.centroproduccion
                        });

                        // Usar función independiente para cargar saldo
                        loadMedicamentoSaldoIndependiente(data.pendiente.codigo, data.pendiente.centroproduccion);
                    } else {
                        console.warn('⚠️ Faltan datos para cargar el saldo:', {
                            codigo: data.pendiente.codigo,
                            centroproduccion: data.pendiente.centroproduccion
                        });
                        setSaldoFieldIndependiente(0, 'Datos incompletos', 'badge-warning');
                    }

                },

            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                        'Sistema seguimiento medicamentos pendientes', 'warning');

                }
            });

        });


        //Función para abrir modal del detalle medicamento pendiente y muestra las observaciones agregadas
        $(document).on('click', '.show_detail', function() {

            $('#form-general-show')[0].reset();
            var id = $(this).attr('id');

            $.ajax({
                url: "showpendientes/" + id,
                dataType: "json",
                success: function(data) {

                    // Primer form de información pendientes por pagar
                    $('#Tipodocum_n').val(data.pendiente.Tipodocum);
                    $('#cantdpx_n').val(data.pendiente.cantdpx);
                    $('#cantord_n').val(data.pendiente.cantord);
                    $('#fecha_factura_n').val(moment(data.pendiente.fecha_factura).format('YYYY-MM-DD'));
                    $('#fecha_n').val(moment(data.pendiente.fecha).format('YYYY-MM-DD'));
                    $('#historia_n').val(data.pendiente.historia);
                    $('#apellido1_n').val(data.pendiente.apellido1);
                    $('#apellido2_n').val(data.pendiente.apellido2);
                    $('#nombre1_n').val(data.pendiente.nombre1);
                    $('#nombre2_n').val(data.pendiente.nombre2);
                    $('#cantedad_n').val(data.pendiente.cantedad);
                    $('#direcres_n').val(data.pendiente.direcres);
                    $('#telefres_n').val(data.pendiente.telefres);
                    $('#documento_n').val(data.pendiente.documento);
                    $('#factura_n').val(data.pendiente.factura);
                    $('#codigo_n').val(data.pendiente.codigo);
                    $('#nombre_n').val(data.pendiente.nombre);
                    $('#cant_pndt_n').val(data.saldo_pendiente);
                    $('#centroproduccion_n').val(data.pendiente.centroproduccion);
                    $('#observ_n').val(data.pendiente.observaciones);
                    $('#fac_entrega').val(data.fac_entrega);

                    if (data.pendiente.cums == '' || data.pendiente.cums == null) {
                        $('#cums_n').val(data.pendiente.codigo);
                    } else {
                        $('#cums_n').val(data.pendiente.cums);
                    }

                    $('#cantidad_n').val(data.pendiente.cantidad);
                    $('#cajero_n').val(data.pendiente.cajero);
                    $('#usuario_n').val(data.pendiente.usuario);
                    $('#estado_n').val(data.pendiente.estado);
                    $('#fecha_impresion_n').val(data.pendiente.fecha_impresion);
                    $('#fecha_entrega_n').val(data.pendiente.fecha_entrega);
                    $('#fecha_anulado_n').val(data.pendiente.fecha_anulado);
                    $('#usuario_n').val(data.pendiente.usuario);

                    $('#hidden_id').val(id)
                    $('#edit_pendiente_n').text("Detalle documento pendiente: " + data.pendiente.documento +
                        "-" + data.pendiente.factura);
                    /* $('#action_button').val('Editar').removeClass('btn-sucess') */
                    /* $('#action_button').addClass('btn-danger') */
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');

                    // Cache the selector
                    var estado = $('#estado_n').val();

                    // Switch statement for showing the corresponding date based on the state of the document
                    switch (estado) {
                        case "PENDIENTE":
                            $('#fecha_estado').val(moment(data.pendiente.fecha).format('YYYY-MM-DD'));
                            $('label[for="fecha_estado"]').text('Fecha Pendiente');
                            break;
                        case "TRAMITADO":
                            $('#fecha_estado').val(moment(data.pendiente.fecha_impresion).format('YYYY-MM-DD'));
                            $('label[for="fecha_estado"]').text('Fecha Tramitado');
                            break;
                        case "ENTREGADO":
                            $('#fecha_estado').val(moment(data.pendiente.fecha_entrega).format('YYYY-MM-DD'));
                            $('label[for="fecha_estado"]').text('Fecha Entrega');
                            break;
                        case "ANULADO":
                            $('#fecha_estado').val(moment(data.pendiente.fecha_anulado).format('YYYY-MM-DD'));
                            $('label[for="fecha_estado"]').text('Fecha Anulación');
                            break;
                        default:
                            $('#fecha_estado').val("");
                            $('label[for="fecha_estado"]').text('Fecha Estado');
                            break;
                    }

                    $('#modal-show-pendientes').modal('show');

                    // Condicional para mostrar en el input fecha_estado la fecha que corresponda al estado del documento
                    /* if ($('#estado_n').val() === "PENDIENTE") {
                        $('#fecha_estado').val(moment(data.pendiente.fecha).format('YYYY-MM-DD'));

                    } else if ($('#estado_n').val() === "TRAMITADO") {
                        $('#fecha_estado').val(moment(data.pendiente.fecha_impresion).format('YYYY-MM-DD'));

                    } else if ($('#estado_n').val() === "ENTREGADO") {
                        $('#fecha_estado').val(moment(data.pendiente.fecha_entrega).format('YYYY-MM-DD'));

                    } else if ($('#estado_n').val() === "ANULADO") {
                        $('#fecha_estado').val(moment(data.pendiente.fecha_anulado).format('YYYY-MM-DD'));
                    } */

                },

            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                        'Sistema seguimiento medicamentos pendientes ', 'warning');
                }
            });

        });





        // Función que envían los datos de la factura al controlador para cambiar el estado
        $('#form-general1').on('submit', function(event) {
            event.preventDefault();
            /* guardar($(this).serialize()); */
            var url = '';
            var method = '';
            var text = '';

            if ($('#action').val() == 'Edit') {
                text = "Estás por entregar o despachar medicamentos pendientes"
                var updateid = $('#hidden_id').val();
                url = "pendientes/" + updateid;
                method = 'put';
            }

            Swal.fire({
                title: "¿Estás seguro?",
                text: text,
                type: "warning",
                icon: "warning",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        method: method,
                        data: $(this).serialize(),
                        dataType: "json",
                        success: function(data) {
                            var html = '';
                            if (data.errors) {
                                // Usar Swal.fire directamente para errores
                                let erroresTexto = '';
                                for (var count = 0; count < data.errors.length; count++) {
                                    erroresTexto += '• ' + data.errors[count] + '\n';
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Errores de Validación',
                                    text: erroresTexto,
                                    confirmButtonText: 'Entendido',
                                    confirmButtonColor: '#dc3545'
                                });
                            }

                            if (data.success == 'ok') {
                                // Usar Swal.fire directamente para éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Cuenta por pagar creada correctamente',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });

                                // Limpiar campos y recargar tabla
                                setTimeout(() => {
                                    if (typeof limpiarCamposEditables === 'function') {
                                        limpiarCamposEditables();
                                    }
                                    $('#pendientes').DataTable().ajax.reload();
                                }, 500);

                            } else if (data.success == 'ok1') {
                                $('#form-general1')[0].reset();
                                $('#modal-edit-pendientes').modal('hide');
                                $('#pendientes').DataTable().ajax.reload();
                                // Usar Swal.fire directamente para éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: 'Documento pendiente actualizado correctamente',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }
                            $('#form_result').html(html)
                        }


                    });
                }
            });


        });


        // Función que envia el id al controlador y cambia el estado del registro
        $(document).on('click', '#syncapi', function() {

            const text = 'De Medcol Centralizado';

            Swal.fire({
                title: "¿Estás por sincronizar pendientes?",
                text: text,
                icon: "info",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

                    ajaxRequestSync();

                }
            });
        });

        function ajaxRequestSync() {
            $.ajax({
                beforeSend: function() {
                    $('.loaders').css("visibility", "visible");
                },
                url: "{{route('medcol6.syncapi')}}",
                type: 'GET',
                success: function(data) {
                    $('#pendientes').DataTable().ajax.reload();
                    // $('#porentregar').DataTable().ajax.reload();
                    // $('#entregados').DataTable().ajax.reload();

                    // for (var count = 0; count < data.length; count++) {
                    //     console.log(count);
                    //     Apiws.notificaciones(data[count].respuesta, data[count].titulo, data[count].icon, data[count].position);
                    //      }


                    $.each(data, function(i, item) {
                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });
                    fill_datatable1_resumen();

                },
                complete: function() {
                    $('.loaders').css("visibility", "hidden");
                }
            });
        }

        //Funcion para sincronizar los pendientes anuladas y actualizar el estado a ANULADO
        $(document).on('click', '#synanuladospndt', function() {

            const text = 'De Medcol - Centralizado';

            Swal.fire({
                title: "¿Estás por sincronizar los pendientes anulados?",
                text: text,
                icon: "error",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

                    ajaxRequestSyncAnulados();

                }
            });
        });

        function ajaxRequestSyncAnulados() {
            $.ajax({
                beforeSend: function() {
                    $('.loaders').css("visibility", "visible");
                },
                url: "{{route('medcol6.pendientesanulados')}}",
                type: 'GET',
                success: function(data) {
                    $('#pendientes').DataTable().ajax.reload();


                    $.each(data, function(i, item) {
                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });
                    // fill_datatable1_resumen();

                },
                complete: function() {
                    $('.loaders').css("visibility", "hidden");
                }
            });
        }

        // Consulta de resumen de pendientes
        $(document).on('click', '#informependientesclic', function() {
            $('.modal-title-resumen-pendientes').text('Resumen de pendientes');
            $('#modal-resumen-pendientes').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#modal-resumen-pendientes').modal('show');
            $('#tablaIndexInformemedicamentos').DataTable().destroy();

            ajaxRequest1();
        });

        function ajaxRequest1() {

            var tinformependientes = $('#tablaIndexInformemedicamentos').DataTable({
                language: idioma_espanol,
                processing: true,
                lengthMenu: [
                    [25, 50, 100, 500, -1],
                    [25, 50, 100, 500, "Mostrar Todo"]
                ],
                processing: true,
                serverSide: true,
                aaSorting: [
                    [1, "asc"]
                ],
                ajax: {
                    url: "{{route('informepedientes6')}}",
                    type: 'get',
                },
                columns: [

                    // {
                    //     data: 'codigo',
                    //     name: 'codigo'
                    // },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'cantord',
                        name: 'cantord'
                    }

                ],

                //Botones----------------------------------------------------------------------

                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',


                buttons: [{

                        extend: 'copyHtml5',
                        titleAttr: 'Copiar Registros',
                        title: "seguimiento",
                        className: "btn  btn-outline-primary btn-sm"


                    },
                    {

                        extend: 'excelHtml5',
                        titleAttr: 'Exportar Excel',
                        title: "seguimiento",
                        className: "btn  btn-outline-success btn-sm"


                    },
                    {

                        extend: 'csvHtml5',
                        titleAttr: 'Exportar csv',
                        className: "btn  btn-outline-warning btn-sm"
                        //text: '<i class="fas fa-file-excel"></i>'

                    },
                    {

                        extend: 'pdfHtml5',
                        titleAttr: 'Exportar pdf',
                        className: "btn  btn-outline-secondary btn-sm"


                    }
                ],

            });

        }

        $("#generar_informe").click(function() {
            console.log("=== GENERAR INFORME CLICKED ===");

            // Obtener valores de los campos
            const fechaInicio = $("#modal_fechaini").val().trim();
            const fechaFin = $("#modal_fechafin").val().trim();
            const contrato = $("#modal_contrato").val().trim(); // Opcional

            console.log("Valores obtenidos del modal:", {
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                contrato: contrato
            });

            // Validar campos obligatorios
            if (!fechaInicio || !fechaFin) {
                mostrarError("Debe seleccionar ambas fechas (fecha inicial y fecha final)");
                return;
            }

            // Validar que la fecha de inicio no sea mayor a la fecha fin
            if (new Date(fechaInicio) > new Date(fechaFin)) {
                mostrarError("La fecha de inicio no puede ser mayor a la fecha final");
                return;
            }

            parametrosActuales = {
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                contrato: contrato || null
            };

            console.log('Parámetros actualizados:', parametrosActuales);

            // Llamar a la función generadora del informe
            show_report(fechaInicio, fechaFin, contrato || null);
            cargarDetallePendientes(fechaInicio, fechaFin, contrato || null);
            cargarPendientesVsSaldos(fechaInicio, fechaFin, contrato || null);
        });

        // Función auxiliar para mostrar errores (puedes personalizarla)
        function mostrarError(mensaje) {
            // Opción 1: Usar SweetAlert (recomendado para mejor UX)
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: mensaje
                });
            }
            // Opción 2: Usar toast (ejemplo con Toastr)
            else if (typeof toastr !== 'undefined') {
                toastr.error(mensaje);
            }
            // Opción 3: Alert estándar (como último recurso)
            else {
                alert(mensaje);
            }
        }

        function show_report(fechaInicio, fechaFin, contrato) {
            $("#detalle_informe").empty();
            $("#detalle_informe1").empty();
            $("#detalle_informe2").empty();

            const nombresExcluidos = ['FSAU', 'FSIO', 'FSOS', 'ENMP', 'EVSO'];
            const servicioMap = {
                "FRJA": "Jamundí",
                "DOMI": "Domicilios",
                "PAC": "PCE",
                "EHU1": "Huérfanas",
                "BIO1": "Biológicos",
                "SM01": "Salud Mental",
                "DLR1": "Dolor",
                "EVEN": "Evento",
                "EM01": "Emcali",
                "EVSM": "Evento SM",
                "BPDT": "Bolsa",
                "DPA1": "Paliativos",
                "INY": "Inyectables"
            };

            $.ajax({
                url: "{{ route('medcol6.getreport') }}",
                data: {
                    fechaini: fechaInicio,
                    fechafin: fechaFin,
                    contrato: contrato
                },
                dataType: "json",
                success: function(data) {
                    // Actualizar la nueva card con el total general
                    actualizarTotalGeneral(data.total_pendientes_generados);

                    $("#resultado_informe").show();
                    const {
                        pendiente,
                        entregas_48h,
                        anulado,
                        porcentaje_entregas_48h,
                        porcentaje_pendientes
                    } = data;

                    let htmlDispensado = "<ul>";
                    pendiente.forEach(item => {
                        const nombreCentroprod = servicioMap[item.centroproduccion] || item.centroproduccion;
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            htmlDispensado += `<li>${nombreCentroprod}: ${item.total}</li>`;
                        }
                    });
                    htmlDispensado += "</ul>";

                    let htmlEntregado = "<ul>";
                    // Primero añadimos el porcentaje general de entregas en 48h
                    htmlEntregado += `<li>Oportunidad de entrega 48h: <strong> ${porcentaje_entregas_48h}% </strong></li>`;

                    // Luego continuamos con el resto de los elementos
                    entregas_48h.forEach(item => {
                        const nombreCentroprod = servicioMap[item.centroproduccion] || item.centroproduccion;
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            htmlEntregado += `<li>${nombreCentroprod}: ${item.total}</li>`;
                        }
                    });
                    htmlEntregado += "</ul>";

                    let htmlAnulado = "<ul>";
                    anulado.forEach(item => {
                        const nombreCentroprod = servicioMap[item.centroproduccion] || item.centroproduccion;
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            htmlAnulado += `<li>${nombreCentroprod}: ${item.total}</li>`;
                        }
                    });
                    htmlAnulado += "</ul>";

                    $("#detalle_informe").append(`
                        <div class="small-box shadow-lg l-bg-blue-dark">
                            <div class="inner">
                                <!--<h5>PENDIENTES X REVISAR</h5>-->
                                <h5>CONTRATO</h5>
                                <p>${htmlDispensado}</p>
                            </div>
                            <a class="informependientes" id="informependientesclic" href="#">
                                <div class="icon">
                                    <!-- <i class="fas fa-notes-medical informependientes"></i> -->
                                </div>
                            </a>
                        </div>
                    `);

                    $("#detalle_informe1").append(`
                        <div class="small-box shadow-lg l-bg-green-dark">
                            <div class="inner">
                                <!--<h5>REVISADAS</h5>-->
                                <h5>CONTRATO</h5>
                                <p>${htmlEntregado}</p>
                            </div>
                            <div class="icon">
                                <!-- <i class="fas fa-briefcase-medical"></i> -->
                            </div>
                        </div>
                    `);

                    $("#detalle_informe2").append(`
                        <div class="small-box shadow-lg l-bg-red-dark">
                            <div class="inner">
                                <!--<h5>ANULADAS</h5>-->
                                <h5>CONTRATO</h5>
                                <p>${htmlAnulado}</p>
                            </div>
                            <div class="icon">
                                <!-- <i class="fas fa-ban"></i> -->
                            </div>
                        </div>
                    `);
                }
            });
        }

        // Esta función se llamará cuando se reciban los datos del controlador
        function actualizarTotalGeneral(totalPendientesEntregados) {
            $("#detalle_informe_total").html(`
                <div class="small-box shadow-lg" style="background: linear-gradient(to right, #7952b3, #9a77d1); color: #fff;">
                    <div class="inner text-center">
                        <h2 class="display-4 fw-bold">${totalPendientesEntregados}</h2>
                        <p class="mb-0">Total de pendientes generados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            `);
        }

        // Variables globales para almacenar los parámetros actuales
        let parametrosActuales = {
            fechaInicio: null,
            fechaFin: null,
            contrato: null
        };

        function cargarDetallePendientes(fechaInicio, fechaFin, contrato) {
            // Ocultar botón de Excel al inicio
            $("#exportar_excel").hide();

            // Limpiar contenedores
            $("#detalle_medicamentos_farmacia").empty();
            $("#tablaDetPend tbody").empty();

            // Mostrar loading
            $("#detalle_medicamentos_farmacia").html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');

            const servicioMap = {
                "BIO1": "Biológicos",
                "DLR1": "Dolor",
                "DPA1": "Paliativos",
                "EM01": "Emcali",
                "EHU1": "Huérfanas",
                "FRJA": "Jamundí",
                "FRIO": "Ideo",
                "INY": "Inyectables",
                "PAC": "PCE",
                "SM01": "Salud Mental",
                "BPDT": "Bolsa",
                "EVEN": "Evento",
                "EVSM": "Evento SM"
            };

            $.ajax({
                url: "{{ route('medcol6.getMedicamentosPorFarmacia') }}",
                method: 'GET',
                data: {
                    fechaini: fechaInicio,
                    fechafin: fechaFin,
                    contrato: contrato
                },
                dataType: "json",
                success: function(response) {
                    console.log("Respuesta del servidor:", response);

                    if (!response.success || !response.medicamentos) {
                        mostrarError("No se encontraron datos de medicamentos.");
                        $("#detalle_medicamentos_farmacia").empty();
                        return;
                    }

                    const medicamentos = response.medicamentos;
                    const totalesPorFarmacia = response.totales_por_farmacia;
                    const farmacias = response.farmacias;

                    // Mostrar resumen por farmacia
                    mostrarResumenFarmacias(totalesPorFarmacia, servicioMap);

                    // Llenar la tabla detallada
                    llenarTablaDetallada(medicamentos, farmacias, totalesPorFarmacia);

                    // Mostrar botón de Excel
                    $("#exportar_excel").show();
                    $("#resultado_informe_medicamentos").show();
                },
                error: function(xhr, status, error) {
                    console.error("Error al obtener medicamentos por farmacia:", error);
                    mostrarError("No se pudo cargar el informe de medicamentos por farmacia.");
                    $("#detalle_medicamentos_farmacia").empty();
                }
            });
        }

        function cargarPendientesVsSaldos(fechaInicio, fechaFin, contrato) {
            console.log('cargarPendientesVsSaldos llamada con:', {
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                contrato: contrato
            });

            // Ocultar elementos de la pestaña anterior
            $("#exportar_excel_saldos").hide();
            $("#resumen_saldos").hide();

            // Destruir tabla existente completamente antes de limpiar
            if ($.fn.DataTable.isDataTable('#tablaPendSald')) {
                $('#tablaPendSald').DataTable().destroy();
                console.log('Tabla tablaPendSald destruida para nueva carga');
            }

            // Limpiar tabla
            $("#tablaPendSald tbody").empty();

            // Mostrar loading en la tabla
            $("#tablaPendSald tbody").html(
                '<tr><td colspan="10" class="text-center">' +
                '<i class="fas fa-spinner fa-spin"></i> Cargando datos de pendientes vs saldos...' +
                '</td></tr>'
            );

            $.ajax({
                url: "{{ route('medcol6.pendientes_vs_saldos') }}",
                method: 'GET',
                data: {
                    fechaini: fechaInicio,
                    fechafin: fechaFin,
                    contrato: contrato
                },
                dataType: "json",
                success: function(response) {
                    console.log("Respuesta pendientes vs saldos recibida:", {
                        success: response.success,
                        dataLength: response.data ? response.data.length : 0,
                        response: response
                    });

                    // Limpiar tabla
                    $("#tablaPendSald tbody").empty();

                    if (!response.success || !response.data || response.data.length === 0) {
                        $("#tablaPendSald tbody").html(
                            '<tr><td colspan="10" class="text-center text-muted">' +
                            'No se encontraron datos para el rango de fechas seleccionado.' +
                            '</td></tr>'
                        );
                        return;
                    }

                    // Contadores para el resumen
                    let conSaldo = 0;
                    let saldoParcial = 0;
                    let sinSaldo = 0;

                    // Procesar datos
                    response.data.forEach(function(item) {
                        // Determinar clase para el estado del saldo
                        let estadoClass = '';
                        let comparacionClass = '';

                        if (item.estado === 'CON SALDO') {
                            estadoClass = 'bg-success text-white';
                            conSaldo++;
                        } else {
                            estadoClass = 'bg-danger text-white';
                            sinSaldo++;
                        }

                        // Determinar clase para la comparación
                        if (item.pendiente_vs_saldo === 'SALDO SUFICIENTE') {
                            comparacionClass = 'bg-success text-white';
                        } else if (item.pendiente_vs_saldo === 'SALDO PARCIAL') {
                            comparacionClass = 'bg-warning text-dark';
                            saldoParcial++;
                        } else {
                            comparacionClass = 'bg-danger text-white';
                        }

                        // Crear fila de la tabla
                        let fila = `
                            <tr>
                                <td><span class="badge badge-primary">${item.farmacia}</span></td>
                                <td><code>${item.codigo}</code></td>
                                <td class="text-left">${item.nombre}</td>
                                <td class="text-left">${item.marca || '-'}</td>
                                <td><small>${item.cums || '-'}</small></td>
                                <td class="text-center"><strong>${item.cantidad_pendiente}</strong></td>
                                <td class="text-center"><strong>${parseFloat(item.saldo).toFixed(0)}</strong></td>
                                <td class="text-center">
                                    <span class="badge ${estadoClass}">${item.estado}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge ${comparacionClass}">${item.pendiente_vs_saldo}</span>
                                </td>
                                <td>${item.fecha_saldo || 'N/A'}</td>
                            </tr>
                        `;

                        $("#tablaPendSald tbody").append(fila);
                    });

                    // Actualizar contadores del resumen
                    $("#con_saldo_count").text(conSaldo);
                    $("#saldo_parcial_count").text(saldoParcial);
                    $("#sin_saldo_count").text(sinSaldo);
                    $("#total_medicamentos").text(response.data.length);

                    // Mostrar resumen y botón de exportar
                    $("#resumen_saldos").show();
                    $("#exportar_excel_saldos").show();

                    // Inicializar DataTable con configuración simplificada
                    $('#tablaPendSald').DataTable({
                        language: idioma_espanol,
                        pageLength: 25,
                        lengthMenu: [
                            [25, 50, 100, -1],
                            [25, 50, 100, "Todos"]
                        ],
                        dom: '<"row"<"col-md-4"l><"col-md-4"f><"col-md-4"B>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                        autoWidth: true,
                        buttons: [{
                                extend: 'excelHtml5',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'btn btn-success btn-sm',
                                title: 'Informe Pendientes vs Saldos',
                                filename: 'pendientes_vs_saldos_' + new Date().toISOString().slice(0, 10)
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: 'btn btn-danger btn-sm',
                                title: 'Informe Pendientes vs Saldos',
                                filename: 'pendientes_vs_saldos_' + new Date().toISOString().slice(0, 10),
                                orientation: 'landscape',
                                pageSize: 'A4'
                            }
                        ]
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar pendientes vs saldos:", error);
                    $("#tablaPendSald tbody").html(
                        '<tr><td colspan="10" class="text-center text-danger">' +
                        '<i class="fas fa-exclamation-triangle"></i> Error al cargar los datos. Intente nuevamente.' +
                        '</td></tr>'
                    );

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar el informe de pendientes vs saldos'
                        });
                    }
                }
            });
        }

        function mostrarResumenFarmacias(totalesPorFarmacia, servicioMap) {
            const nombresExcluidos = ['FSAU', 'FSIO', 'FSOS', 'ENMP', 'EVSO'];

            let htmlMedicamentos = "<ul class='list-unstyled'>";
            let totalGeneral = 0;

            Object.entries(totalesPorFarmacia).forEach(([farmacia, total]) => {
                const nombreFarmacia = servicioMap[farmacia] || farmacia;
                if (!nombresExcluidos.includes(farmacia) && total > 0) {
                    htmlMedicamentos += `<li><strong>${nombreFarmacia}:</strong> ${number_format(total)}</li>`;
                    totalGeneral += total;
                }
            });

            htmlMedicamentos += `<li class='border-top pt-2 mt-2'><strong>Total General:</strong> ${number_format(totalGeneral)}</li>`;
            htmlMedicamentos += "</ul>";

            $("#detalle_medicamentos_farmacia").html(`
                <div class="small-box shadow-lg l-bg-purple-dark">
                    <div class="inner text-white">
                        <h5>TOTAL UNIDADES POR FARMACIA</h5>
                        ${htmlMedicamentos}
                    </div>
                    <div class="icon">
                        <i class="fas fa-pills"></i>
                    </div>
                </div>
            `);
        }

        function llenarTablaDetallada(medicamentos, farmacias, totalesPorFarmacia) {
            const tbody = $("#tablaDetPend tbody");

            // Llenar filas de medicamentos
            medicamentos.forEach(item => {
                let fila = `<tr><td class="text-left">${item.nombre}</td>`;

                farmacias.forEach(farmacia => {
                    const cantidad = item.cantidades[farmacia] || 0;
                    fila += `<td>${number_format(cantidad)}</td>`;
                });

                fila += `<td><strong>${number_format(item.total)}</strong></td></tr>`;
                tbody.append(fila);
            });

            // Agregar fila de totales
            let filaTotales = '<tr class="table-info"><th>TOTALES</th>';
            farmacias.forEach(farmacia => {
                const total = totalesPorFarmacia[farmacia] || 0;
                filaTotales += `<th>${number_format(total)}</th>`;
            });

            const granTotal = Object.values(totalesPorFarmacia).reduce((sum, val) => sum + val, 0);
            filaTotales += `<th>${number_format(granTotal)}</th></tr>`;
            tbody.append(filaTotales);
        }

        // Función auxiliar para formatear números
        function number_format(num) {
            return new Intl.NumberFormat('es-CO').format(num);
        }

        // Función principal para exportar tabla a Excel
        $("#exportar_excel").click(function() {
            exportarTablaAExcel();
        });

        function exportarTablaAExcel() {
            // Verificar que hay datos en la tabla
            if ($("#tablaDetPend tbody tr").length === 0) {
                mostrarError("No hay datos para exportar. Genere el informe primero.");
                return;
            }

            // Mostrar loading en el botón
            const botonOriginal = $("#exportar_excel").html();
            $("#exportar_excel").html('<i class="fas fa-spinner fa-spin"></i> Exportando...').prop('disabled', true);

            try {
                // Obtener información del reporte
                const fechaInicio = parametrosActuales.fechaInicio ? formatearFecha(parametrosActuales.fechaInicio) : 'N/A';
                const fechaFin = parametrosActuales.fechaFin ? formatearFecha(parametrosActuales.fechaFin) : 'N/A';
                const contrato = parametrosActuales.contrato || 'Todos';

                // Crear nombre del archivo
                const nombreArchivo = `Medicamentos_Farmacia_${fechaInicio.replace(/\//g, '')}_${fechaFin.replace(/\//g, '')}.xls`;

                // Obtener resumen de medicamentos por farmacia
                const resumenHtml = obtenerResumenFarmacias();

                // Crear contenido del Excel con formato HTML
                let contenidoExcel = `
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    .encabezado { font-weight: bold; font-size: 16px; text-align: center; }
                    .info { font-weight: bold; margin: 5px 0; }
                    .tabla { border-collapse: collapse; width: 100%; margin-top: 20px; }
                    .tabla th, .tabla td { border: 1px solid #000; padding: 8px; text-align: center; }
                    .tabla th { background-color: #366092; color: white; font-weight: bold; }
                    .totales { background-color: #E7E6E6; font-weight: bold; }
                    .resumen { margin: 20px 0; }
                    .resumen table { border-collapse: collapse; width: 50%; }
                    .resumen th, .resumen td { border: 1px solid #000; padding: 5px; }
                    .resumen th { background-color: #f0f0f0; }
                </style>
            </head>
            <body>
                <div class="encabezado">REPORTE DE MEDICAMENTOS PENDIENTES POR FARMACIA</div>
                <br>
                <div class="info">Fecha Inicio: ${fechaInicio}</div>
                <div class="info">Fecha Fin: ${fechaFin}</div>
                <div class="info">Contrato: ${contrato}</div>
                <div class="info">Generado: ${new Date().toLocaleString('es-CO')}</div>
                
                <div class="resumen">
                    <h4>RESUMEN POR FARMACIA:</h4>
                    ${resumenHtml}
                </div>
                
                <h4>DETALLE COMPLETO:</h4>
                ${obtenerTablaCompleta()}
            </body>
            </html>
        `;

                // Crear blob y descargar
                const blob = new Blob([contenidoExcel], {
                    type: 'application/vnd.ms-excel;charset=utf-8'
                });

                // Crear enlace temporal para descarga
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = nombreArchivo;
                link.style.display = 'none';

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Limpiar URL objeto
                URL.revokeObjectURL(link.href);

                // Mostrar mensaje de éxito
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Excel Generado',
                        text: 'El archivo Excel se ha descargado correctamente',
                        timer: 3000
                    });
                }

            } catch (error) {
                console.error('Error al generar Excel:', error);
                mostrarError('Error al generar el archivo Excel. Intente nuevamente.');
            } finally {
                // Restaurar botón
                setTimeout(() => {
                    $("#exportar_excel").html(botonOriginal).prop('disabled', false);
                }, 2000);
            }
        }

        // Función para obtener el resumen de farmacias - MODIFICADA
        function obtenerResumenFarmacias() {
            let resumenHtml = '<table class="resumen"><thead><tr><th>Farmacia</th><th>Número de Items</th></tr></thead><tbody>';

            // Extraer totales de la última fila de la tabla para verificar si > 0
            const filasTotales = $("#tablaDetPend tbody tr:last");
            if (filasTotales.length > 0) {
                const celdas = filasTotales.find('th');
                const farmacias = ['BIO1', 'DLR1', 'DPA1', 'EM01', 'EHU1', 'FRJA', 'FRIO', 'INY', 'PAC', 'SM01', 'BPDT', 'EVEN', 'EVSM'];

                const servicioMap = {
                    "BIO1": "Biológicos",
                    "DLR1": "Dolor",
                    "DPA1": "Paliativos",
                    "EM01": "Emcali",
                    "EHU1": "Huérfanas",
                    "FRJA": "Jamundí",
                    "FRIO": "Ideo",
                    "INY": "Inyectables",
                    "PAC": "PCE",
                    "SM01": "Salud Mental",
                    "BPDT": "Bolsa",
                    "EVEN": "Evento",
                    "EVSM": "Evento SM"
                };

                celdas.each(function(index) {
                    if (index > 0 && index <= farmacias.length) { // Saltar la primera celda "TOTALES"
                        const farmacia = farmacias[index - 1];
                        const total = $(this).text().trim();
                        const nombreFarmacia = servicioMap[farmacia] || farmacia;

                        // Solo procesar si el total es mayor a cero
                        if (parseInt(total.replace(/\./g, '')) > 0) {
                            // Contar filas que tienen datos en esta columna específica (sin contar header y total)
                            const numeroFilas = contarFilasPorFarmacia(farmacia, index);

                            resumenHtml += `<tr><td>${nombreFarmacia}</td><td>${numeroFilas}</td></tr>`;
                        }
                    }
                });
            }

            resumenHtml += '</tbody></table>';
            return resumenHtml;
        }

        // Función auxiliar para contar filas por farmacia
        function contarFilasPorFarmacia(codigoFarmacia, indiceColumna) {
            let contador = 0;

            // Recorrer todas las filas de la tabla (excepto header y última fila de totales)
            $("#tablaDetPend tbody tr").each(function(index, fila) {
                // Saltar la primera fila (header) y la última fila (totales)
                const totalFilas = $("#tablaDetPend tbody tr").length;
                if (index > 0 && index < totalFilas - 1) {
                    // Obtener el valor de la celda en la columna de esta farmacia
                    const celda = $(fila).find('td, th').eq(indiceColumna);
                    const valor = celda.text().trim();

                    // Si tiene un valor numérico mayor a 0, contar esta fila
                    const valorNumerico = parseInt(valor.replace(/\./g, '')) || 0;
                    if (valorNumerico > 0) {
                        contador++;
                    }
                }
            });

            return contador;
        }

        // Función para contar TODAS las filas que tienen datos y agruparlos por farmacia
        function obtenerResumenFarmaciasSimple() {
            let resumenHtml = '<table class="resumen"><thead><tr><th>Farmacia</th><th>Número de Items</th></tr></thead><tbody>';

            // Extrae el numero de filas de la tabla
            const filasTotales = $("#tablaDetPend tbody tr:last");
            if (filasTotales.length > 0) {
                const celdas = filasTotales.find('th');
                const farmacias = ['BIO1', 'DLR1', 'DPA1', 'EM01', 'EHU1', 'FRJA', 'FRIO', 'INY', 'PAC', 'SM01', 'BPDT', 'EVEN', 'EVSM'];

                const servicioMap = {
                    "BIO1": "Biológicos",
                    "DLR1": "Dolor",
                    "DPA1": "Paliativos",
                    "EM01": "Emcali",
                    "EHU1": "Huérfanas",
                    "FRJA": "Jamundí",
                    "FRIO": "Ideo",
                    "INY": "Inyectables",
                    "PAC": "PCE",
                    "SM01": "Salud Mental",
                    "BPDT": "Bolsa",
                    "EVEN": "Evento",
                    "EVSM": "Evento SM"
                };

                celdas.each(function(index) {
                    if (index > 0 && index <= farmacias.length) { // Saltar la primera celda "TOTALES"
                        const farmacia = farmacias[index - 1];
                        const total = $(this).text().trim();
                        const nombreFarmacia = servicioMap[farmacia] || farmacia;

                        // Solo mostrar farmacias con total > 0
                        if (parseInt(total.replace(/\./g, '')) > 0) {
                            // Contar filas de datos (total de filas - header - fila de totales)
                            const totalFilasTabla = $("#tablaDetPend tbody tr").length;
                            const numeroRegistros = Math.max(0, totalFilasTabla - 2); // Restar header y totales

                            resumenHtml += `<tr><td>${nombreFarmacia}</td><td>${numeroRegistros}</td></tr>`;
                        }
                    }
                });
            }

            resumenHtml += '</tbody></table>';
            return resumenHtml;
        }

        // Función para obtener la tabla completa con formato
        function obtenerTablaCompleta() {
            const tabla = $("#tablaDetPend")[0];
            if (!tabla) return '<p>No hay datos disponibles</p>';

            let tablaHtml = '<table class="tabla">';

            // Procesar encabezados
            const thead = tabla.querySelector('thead');
            if (thead) {
                tablaHtml += '<thead>';
                $(thead).find('tr').each(function() {
                    tablaHtml += '<tr>';
                    $(this).find('th').each(function() {
                        const rowspan = $(this).attr('rowspan') || 1;
                        const colspan = $(this).attr('colspan') || 1;
                        tablaHtml += `<th rowspan="${rowspan}" colspan="${colspan}">${$(this).text()}</th>`;
                    });
                    tablaHtml += '</tr>';
                });
                tablaHtml += '</thead>';
            }

            // Procesar cuerpo de la tabla
            const tbody = tabla.querySelector('tbody');
            if (tbody) {
                tablaHtml += '<tbody>';
                $(tbody).find('tr').each(function() {
                    const esFilaTotales = $(this).hasClass('table-info') || $(this).find('th').length > 0;
                    const claseCSS = esFilaTotales ? ' class="totales"' : '';
                    tablaHtml += `<tr${claseCSS}>`;

                    $(this).find('td, th').each(function() {
                        const tag = $(this).is('th') ? 'th' : 'td';
                        tablaHtml += `<${tag}>${$(this).text()}</${tag}>`;
                    });
                    tablaHtml += '</tr>';
                });
                tablaHtml += '</tbody>';
            }

            tablaHtml += '</table>';
            return tablaHtml;
        }

        // Función auxiliar para formatear fechas
        function formatearFecha(fecha) {
            if (!fecha) return 'N/A';

            let date;

            // Si la fecha viene en formato ISO (YYYY-MM-DD), procesarla directamente
            if (typeof fecha === 'string' && fecha.match(/^\d{4}-\d{2}-\d{2}$/)) {
                // Extraer año, mes, día directamente del string para evitar problemas de zona horaria
                const [año, mes, dia] = fecha.split('-');
                return `${dia}/${mes}/${año}`;
            }

            // Para otros formatos, usar Date normalmente
            date = new Date(fecha);
            if (isNaN(date.getTime())) return fecha; // Si no se puede parsear, devolver original

            const dia = String(date.getDate()).padStart(2, '0');
            const mes = String(date.getMonth() + 1).padStart(2, '0');
            const año = date.getFullYear();

            return `${dia}/${mes}/${año}`;
        }

        // Función auxiliar para mostrar errores (ya existente, pero la incluyo por complementar)
        function mostrarError(mensaje) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.error(mensaje);
            } else {
                alert(mensaje);
            }
        }


    });


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

    // ⚠️ CONFIGURACIÓN UNIFICADA DE SELECT2 ⚠️
    // La inicialización de Select2 ahora se maneja de forma unificada en pendientes-form.js
    // para evitar conflictos y duplicaciones. Esta sección se mantiene como comentario para referencia.
    /*
    CONFIGURACIÓN MIGRADA A: pendientes-form.js
    - Inicialización unificada de Select2
    - Gestión centralizada de eventos
    - Aplicación consistente de estilos
    - Prevención de inicializaciones duplicadas
    
    TEMPLATES ORIGINALES CONSERVADOS:
    - templateResult: Agrega clases CSS según el estado seleccionado
    - templateSelection: Muestra el texto con íconos en la selección
    - Bootstrap4 theme con dropdown parent correcto
    */

    // Función independiente para cargar saldo del medicamento
    async function loadMedicamentoSaldoIndependiente(codigo, centroproduccion) {
        try {
            // Limpiar y validar los parámetros
            const codigoLimpio = codigo ? codigo.toString().trim() : '';
            const centroproduccionLimpio = centroproduccion ? centroproduccion.toString().trim() : '';

            if (!codigoLimpio || !centroproduccionLimpio) {
                console.warn('⚠️ Parámetros inválidos para cargar saldo:', {
                    codigo: codigoLimpio,
                    centroproduccion: centroproduccionLimpio
                });
                setSaldoFieldIndependiente(0, 'Parámetros inválidos', 'badge-warning');
                return;
            }

            console.log('🔍 Cargando saldo para medicamento específico:', {
                codigo: codigoLimpio,
                centroproduccion: centroproduccionLimpio
            });

            // Mostrar indicador de carga
            setSaldoFieldIndependiente('...', 'Consultando...', 'badge-info');

            const response = await fetch('/medcol6/saldo-medicamento', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        document.querySelector('input[name="_token"]')?.value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    codigo: codigoLimpio,
                    deposito: centroproduccionLimpio
                })
            });

            const result = await response.json();
            console.log('📊 Respuesta del servidor:', result);

            if (response.ok && result.success) {
                const saldoValue = parseFloat(result.saldo) || 0;
                console.log('✅ Saldo cargado exitosamente:', {
                    saldo: saldoValue,
                    estado: result.estado,
                    medicamento: result.nombre_medicamento,
                    deposito: result.deposito,
                    fecha: result.fecha_saldo
                });

                // Actualizar campo y badge
                if (saldoValue > 0) {
                    setSaldoFieldIndependiente(saldoValue, `Disponible: ${saldoValue} unidades`, 'badge-success');
                } else {
                    const mensaje = result.estado === 'SIN REGISTRO' ? 'Sin registro en inventario' : 'Sin saldo disponible';
                    setSaldoFieldIndependiente(0, mensaje, 'badge-danger');
                }

            } else {
                console.warn('⚠️ Error en respuesta del servidor:', result.message || 'Respuesta inválida');
                setSaldoFieldIndependiente(0, 'Error al consultar', 'badge-warning');
            }

        } catch (error) {
            console.error('❌ Error al cargar saldo del medicamento:', error);
            setSaldoFieldIndependiente(0, 'Error de conexión', 'badge-danger');
        }
    }

    // NOTA: La función setSaldoFieldIndependiente ahora está unificada en pendientes-form.js
    // para evitar duplicación y mantener consistencia en el manejo de badges de saldo
</script>

<!-- Script para gestión del formulario de pendientes -->
<script src="{{asset("assets/js/pendientes-form.js")}}" type="text/javascript"></script>

<!-- Script de debug (solo para desarrollo) -->
<script src="{{asset("assets/js/debug-estados.js")}}" type="text/javascript"></script>

@endsection