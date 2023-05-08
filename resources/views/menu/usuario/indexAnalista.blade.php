@extends('layouts.app')

@section('titulo')
Pendientes Medcol San Fernando
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


<style>
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
        border-radius: 40px 40px 40px 40px;
        border-color: #ffffff;
        /* Borde del boton */
        letter-spacing: 2px;
        /* Espacio entre letras */
        background: linear-gradient(to right, #0880a8, #56e6ff) !important;
        /* Color de fondo */
        /*background-color: #e9321e; /* Color de fondo */
        padding: 8px 15px;
        /* Relleno del boton */
        position: fixed;
        top: 146px;

        right: 40px;
        transition: all 300ms ease 0ms;
        box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.5);
        z-index: 99;
        /* border: none;
    outline: none; */
    }

    .btn-flotante:hover {
        background-color: #2c2fa5;
        /* Color de fondo al pasar el cursor */
        box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
        transform: translateY(-5px);
    }

    @media only screen and (max-width: 300px) {
        .btn-flotante {
            font-size: 14px;
            padding: 12px 20px 0 0;
            bottom: 20px;
            right: 20px;
        }
    }

    .loaders {

        visibility: hidden;
        background-color: rgba(255, 253, 253, 0.952);
        position: absolute;
        z-index: +100 !important;
        width: 100%;
        height: 100%;
    }

    .loaders img {
        position: relative;
        top: 50%;
        left: 40%;
        width: 180px;
        height: 180px;
    }

    /* // Colores para las tarjetas widget */
    .card {
        background-color: #fff;
        border-radius: 10px;
        border: none;
        position: relative;
        margin-bottom: 30px;
        box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
    }

    .l-bg-blue-dark-card {
        background-color: linear-gradient(to right, #b7c7ec, #4286f4) !important;
        color: #fff;
    }



    .l-bg-cherry {
        background: linear-gradient(to right, #493240, #f09) !important;
        color: #fff;
    }

    .l-bg-blue-dark {
        background: linear-gradient(to right, #06b6cd, #319acb) !important;
        color: #fff;
    }

    .l-bg-green-dark {
        background: linear-gradient(to right, #0a504a, #3866ef) !important;
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
</style>

@endsection


@section('scripts')


<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="loaders"><img src="{{asset("assets/lte/dist/img/loader6.gif")}}" class="" /> </div>

@include('menu.usuario.form.forminforme')
@include('menu.usuario.tabs.tabsIndexAnalista')
@include('menu.usuario.modal.modalindexresumen')
@include('menu.usuario.modal.modalindexaddseguimiento')

@include('menu.usuario.modal.modalPendientes')
@include('menu.usuario.modal.modalDetallePendiente')


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


        fill_datatable1_resumen();
        tabs();

        function fill_datatable1_resumen() {
            $("#detalle").empty();
            $("#detalle1").empty();
            $("#detalle2").empty();
            $("#detalle3").empty();
            $("#detalle4").empty();
            //$("#detalle5").empty();
            $.ajax({
                url: "{{ route('informe') }}",
                // data: {
                //     fechaini: fechaini,
                //     fechafin: fechafin
                // },
                dataType: "json",
                success: function(data) {
                    //Widget Total Horas
                    // $.each(data.pendientes, function(i, item) {
                    var a = data.pendientes;
                    if (a == null) {
                        a = 0;
                    } else {
                        a = data.pendientes;
                    }

                    $("#detalle").append(
                        '<div class="small-box shadow-lg  l-bg-blue-dark"><div class="inner">' +
                        '<h5>TOTAL PENDIENTES</h5>' +
                        '<p><h5> ' + a +
                        '</h5></p>' +
                        '</div><div class="icon"><i class="fas fa-notes-medical"></i></div></div>'

                    );

                    // })

                    //  $.each(data.entregados, function(i, item) {
                    var a = data.entregados;
                    if (a == null) {
                        a = 0;
                    } else {
                        a = data.entregados;
                    }
                    $("#detalle1").append(


                        '<div class="small-box shadow-lg l-bg-orange-dark"><div class="inner">' +
                        '<h5>TOTAL ENTREGADOS</h5>' +
                        '<p><h5> ' + a +
                        '</h5></p>' +
                        '</div><div class="icon"><i class="fas fa-briefcase-medical"></i></div></div>'

                    );

                    // })
                    var a = data.tramitados;
                    if (a == null) {
                        a = 0;
                    } else {
                        a = data.tramitados;
                    }
                    $("#detalle2").append(


                        '<div class="small-box shadow-lg l-bg-green-dark"><div class="inner">' +
                        '<h5>EN TRAMITE</h5>' +
                        '<p><h5><i class="fas fa-comment-medical"></i> ' + a +
                        '</h5></p>' +
                        '</div><div class="icon"><i class="fas fa-ambulance"></i></div></div>'

                    );

                    // })
                    var a = data.anulados;
                    if (a == null) {
                        a = 0;
                    } else {
                        a = data.anulados;
                    }
                    $("#detalle3").append(


                        '<div class="small-box shadow-lg l-bg-red-dark"><div class="inner">' +
                        '<h5>ANULADOS</h5>' +
                        '<p><h6><i class="fas fa-hospital"></i> ' + a +
                        '</h6></p>' +
                        '</div><div class="icon"><i class="fas fa-trash-alt"></i></div></div>'

                    );

                    // }) Card para los medicamentos desabastecidos
                    var a = data.agotados;
                    if (a == null) {
                        a = 0;
                    } else {
                        a = data.agotados;
                    }
                    $("#detalle4").append(


                        '<div class="small-box shadow-lg l-bg-red-dark"><div class="inner">' +
                        '<h5>DESABASTECIDOS</h5>' +
                        '<p><h6> ' + a +
                        '</h6></p>' +
                        '</div><div class="icon"><i class="fas fa-trash-alt"></i></div></div>'

                    );

                }
            })
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
            var enviar_factura_entrega = $('#enviar_factura_entrega');
            var input1 = $('#fecha_entrega');
            var input2 = $('#fecha_impresion');
            var anulado = $('#fecha_anulado');
            var input3 = $('#cantord');
            var input4 = $('#cantdpx');
            var input5 = $('#doc_entrega');
            var input6 = $('#factura_entrega');

            if (estado_texto == "TRAMITADO") {
                futuro2.show();

                futuro1.hide();
                futuro3.hide();
                futuro4.hide();

                enviar_fecha_impresion.val('true');
                enviar_fecha_entrega.val('false');
                enviar_fecha_anulado.val('false');
                enviar_factura_entrega.val('true');

                //Limpia los inputs de las fechas seleccionadas cuando esrtan en show luego pasan a hide
                input1.val('');
                anulado.val('');
                input4.val('0');
                input5.val('MED');


            } else if (estado_texto == "ENTREGADO") {
                futuro1.show();

                futuro2.hide();
                futuro3.hide();
                futuro4.hide();

                enviar_fecha_entrega.val('true');
                enviar_fecha_impresion.val('false');
                enviar_fecha_anulado.val('false');
                enviar_factura_entrega.val('true');

                //Limpia los inputs de las fechas seleccionadas cuando esrtan en show luego pasan a hide
                input2.val('');
                anulado.val('');
                input5.val('MED');

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
                enviar_factura_entrega.val('false');

                //Limpia los inputs de las fechas seleccionadas cuando esrtan en show luego pasan a hide
                input1.val('');
                input2.val('');
                input4.val('0');
                input5.val('');
                input6.val('');


            } else if (estado_texto == "PENDIENTE") {
                futuro3.show();

                futuro1.hide();
                futuro2.hide();
                futuro4.hide();

                enviar_fecha_entrega.val('false');
                enviar_fecha_impresion.val('false');
                enviar_fecha_anulado.val('false');
                enviar_factura_entrega.val('false');

                input1.val('');
                input2.val('');
                anulado.val('');
                input4.val('0');
                input5.val('');
                input6.val('');

            } else {
                futuro1.hide();
                futuro2.hide();
                futuro3.hide();
                futuro4.hide();
                enviar_fecha_entrega.val('false');
                enviar_fecha_impresion.val('false');
                enviar_fecha_anulado.val('false');
                enviar_factura_entrega.val('false');
                input1.val('');
                input2.val('');
                anulado.val('');
                input4.val('0');
                input5.val('');
                input6.val('');
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
                    $('#entregados').DataTable().ajax.reload();
                    $('#tanulados').DataTable().ajax.reload();
                    $('#tdesabastecidos').DataTable().ajax.reload();
                    Manteliviano.notificaciones(data.respuesta, data.titulo, data.icon);
                }
            });
        }


       function tabs() {
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
                                [25, 50, 100, 500, -1],
                                [25, 50, 100, 500, "Mostrar Todo"]
                            ],
                            processing: true,
                            serverSide: true,
                            aaSorting: [
                                [1, "desc"]
                            ],
                            ajax: {
                                url: "{{route('pendientes')}}",
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
                    // Funcion para pintar con data table la pestaña de citas agendadas
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
                                url: "{{route('porentregar')}}",
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
                    // Funcion para pintar con data table la pestaña de seguimiento
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
                                url: "{{route('entregados')}}",
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
                                url: "{{route('desabastecidos')}}",
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
                                url: "{{route('anulados')}}",
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
                    url: "{{ route('observaciones')}}",
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

            $('#form-general')[0].reset();
            var id = $(this).attr('id');
            var nivel_idp2 = $(this).attr('id');

            if (nivel_idp2 != '') {

                if ($.fn.DataTable.isDataTable('#tobservaciones')) {
                    $('#tobservaciones').DataTable().destroy();
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
                    $('#fecha_factura').val(moment(data.pendiente.fecha_factura).format('YYYY-MM-DD'));
                    $('#fecha').val(moment(data.pendiente.fecha).format('YYYY-MM-DD'));
                    $('#historia').val(data.pendiente.historia);
                    $('#apellido1').val(data.pendiente.apellido1);
                    $('#apellido2').val(data.pendiente.apellido2);
                    $('#nombre1').val(data.pendiente.nombre1);
                    $('#nombre2').val(data.pendiente.nombre2);
                    $('#cantedad').val(data.pendiente.cantedad);
                    $('#direcres').val(data.pendiente.direcres);
                    $('#telefres').val(data.pendiente.telefres);
                    $('#documento').val(data.pendiente.documento);
                    $('#factura').val(data.pendiente.factura);
                    $('#codigo').val(data.pendiente.codigo);
                    $('#nombre').val(data.pendiente.nombre);
                    $('#cant_pndt').val(data.saldo_pendiente);

                    $('#cums').val(data.pendiente.cums);
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
                    $('#modal-edit-pendientes').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

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
                    $('#cums_n').val(data.pendiente.cums);
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





        // Función que envían los datos de la factura al controlador
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

            const text = 'De Medcol 2';

            Swal.fire({
                title: "¿Estás por sincronizar pendientes?",
                text: text,
                type: "info",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

                    ajaxRequestSync('syncapi');

                }
            });
        });

        function ajaxRequestSync(url) {
            $.ajax({
                beforeSend: function() {
                    $('.loaders').css("visibility", "visible");
                },
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#pendientes').DataTable().ajax.reload();


                    fill_datatable1_resumen();

                    $.each(data, function(i, item) {

                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });


                },
                complete: function() {
                    $('.loaders').css("visibility", "hidden");
                }
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
