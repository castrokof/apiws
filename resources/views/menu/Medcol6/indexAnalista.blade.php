@extends('layouts.app')

@section('titulo')
Pendientes Medcol Jamundi
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


@endsection

@section('scripts')


<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="loaders"><img src="{{asset("assets/lte/dist/img/loader6.gif")}}" class="" /> </div>

@include('menu.Medcol6.form.forminforme')
@include('menu.Medcol6.tabs.tabsIndexAnalista')
@include('menu.Medcol6.modal.modalindexresumen')
@include('menu.Medcol6.modal.modalindexaddseguimiento')

@include('menu.Medcol6.modal.modalPendientes')
@include('menu.Medcol6.modal.modalDetallePendiente')

@include('menu.Medcol6.modal.modalindexresumenpendientes')


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
                    const cardsData = [
                        { id: "#detalle", title: "TOTAL PENDIENTES", value: data.pendientes || 0, icon: "fas fa-notes-medical", bgColor: "l-bg-blue-dark" },
                        { id: "#detalle1", title: "TOTAL ENTREGADOS", value: data.entregados || 0, icon: "fas fa-briefcase-medical", bgColor: "l-bg-green-dark" },
                        { id: "#detalle2", title: "EN TRAMITE", value: data.tramitados || 0, icon: "fas fa-comment-medical", bgColor: "l-bg-orange-dark" },
                        { id: "#detalle3", title: "ANULADOS", value: data.anulados || 0, icon: "fas fa-hospital", bgColor: "l-bg-red-dark" },
                        { id: "#detalle4", title: "DESABASTECIDOS", value: data.agotados || 0, icon: "fas fa-exclamation-triangle", bgColor: "l-bg-orange-dark" },
                        { id: "#detalle5", title: "VENCIDOS", value: data.vencidos || 0, icon: "fas fa-clock", bgColor: "l-bg-gray-dark" }
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
            var estado_id = $('#estado option:selected');
            var estado_texto = estado_id.text();
            var futuro1 = $('#futuro1');
            var futuro2 = $('#futuro2');
            var futuro3 = $('#futuro3');
            var futuro4 = $('#futuro4');

            var enviar_fecha_entrega = $('#enviar_fecha_entrega');
            var enviar_fecha_impresion = $('#enviar_fecha_impresion');
            var enviar_fecha_anulado = $('#enviar_fecha_anulado');
            var input1 = $('#fecha_entrega');
            var input2 = $('#fecha_impresion');
            var anulado = $('#fecha_anulado');
            var input3 = $('#cantord');
            var input4 = $('#cantdpx');

            if (estado_texto == "TRAMITADO") {
                futuro2.show();

                futuro1.hide();
                futuro3.hide();
                futuro4.hide();

                enviar_fecha_impresion.val('true');
                enviar_fecha_entrega.val('false');
                enviar_fecha_anulado.val('false');

                //Limpia los inputs de las fechas seleccionadas cuando esrtan en show luego pasan a hide
                input1.val('');
                anulado.val('');


            } else if (estado_texto == "ENTREGADO") {
                futuro1.show();

                futuro2.hide();
                futuro3.hide();
                futuro4.hide();

                enviar_fecha_entrega.val('true');
                enviar_fecha_impresion.val('false');
                enviar_fecha_anulado.val('false');

                //Limpia los inputs de las fechas seleccionadas cuando esrtan en show luego pasan a hide
                input2.val('');
                anulado.val('');

                var cant_entregada = parseInt(input3.val());
                input4.val(cant_entregada);

            } else if (estado_texto == "ANULADO") {
                futuro4.show();

                futuro1.hide();
                futuro2.hide();
                futuro3.hide();

                enviar_fecha_anulado.val('true');
                enviar_fecha_entrega.val('false');
                enviar_fecha_impresion.val('false');

                //Limpia los inputs de las fechas seleccionadas cuando esrtan en show luego pasan a hide
                input1.val('');
                input2.val('');


            } else if (estado_texto == "PENDIENTE") {
                futuro3.show();

                futuro1.hide();
                futuro2.hide();
                futuro4.hide();

                enviar_fecha_entrega.val('false');
                enviar_fecha_impresion.val('false');
                enviar_fecha_anulado.val('false');

                input1.val('');
                input2.val('');
                anulado.val('');
            } else {
                futuro1.hide();
                futuro2.hide();
                futuro3.hide();
                futuro4.hide();
                enviar_fecha_entrega.val('false');
                enviar_fecha_impresion.val('false');
                enviar_fecha_anulado.val('false');
                input1.val('');
                input2.val('');
                anulado.val('');
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
                                [25, 50, 100, 500, 5000,10000, -1],
                                [25, 50, 100, 500, 5000,10000, "Mostrar Todo"]
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





        //Función para abrir modal del detalle de la evolución y muestra las observaciones agregadas
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
                        "EHU1": "CDHU",
                        "FRJA": "CDJA",
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

            /* if ($('#action').val() == 'Add') {
                text = "Estás por crear una factura o cuenta por pagar"
                url = "{{route('crear_observacion')}}";
                method = 'post';
            } */

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

                                html =
                                    '<div class="alert alert-danger alert-dismissible">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                    '<h5><i class="icon fas fa-ban"></i> Alerta! Verifica los siguientes datos: </h5>';

                                for (var count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '<p>';
                                }
                                html += '</div>';
                            }

                            if (data.success == 'ok') {
                                $('#form-general1')[0].reset();
                                $('#modal-edit-pendientes').modal('hide');
                                /* limpiarModal(); */
                                $('#pendientes').DataTable().ajax.reload();
                                // $('#tobservaciones').DataTable().ajax.reload();
                                // $('#porentregar').DataTable().ajax.reload();
                                // $('#entregados').DataTable().ajax.reload();
                                // $('#tanulados').DataTable().ajax.reload();
                                // $('#tdesabastecidos').DataTable().ajax.reload();
                                Swal.fire({
                                    type: 'success',
                                    title: 'Cuenta por pagar creada correctamente',
                                    showConfirmButton: false,
                                    timer: 1500

                                })


                            } else if (data.success == 'ok1') {
                                $('#form-general1')[0].reset();
                                $('#modal-edit-pendientes').modal('hide');
                                $('#pendientes').DataTable().ajax.reload();
                                // $('#tobservaciones').DataTable().ajax.reload();
                                // $('#porentregar').DataTable().ajax.reload();
                                // $('#entregados').DataTable().ajax.reload();
                                // $('#tanulados').DataTable().ajax.reload();
                                // $('#tdesabastecidos').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Documento pendiente actualizado correctamente',
                                    showConfirmButton: false,
                                    timer: 1500

                                })


                            }
                            $('#form_result').html(html)
                        }


                    });
                }
            });


        });


        // Función que envia el id al controlador y cambia el estado del registro
        $(document).on('click', '#syncapi', function() {

            const text = 'De Medcol JAMUNDI';

            Swal.fire({
                title: "¿Estás por sincronizar pendientes?",
                text: text,
                type: "info",
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

            const text = 'De Medcol - JAMUNDI';

            Swal.fire({
                title: "¿Estás por sincronizar los pendientes anulados?",
                text: text,
                type: "error",
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
</script>
@endsection