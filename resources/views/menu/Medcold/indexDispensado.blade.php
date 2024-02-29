@extends('layouts.app')

@section('titulo')
Dispensado Medcol Dolor
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
<div class="content-header">
    <div class="container-fluid">
        <div class="row lg-12">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark">Ingrese Rango de Fechas </h1>
            </div><!-- /.col -->

            @csrf
            <div class="card-body">

                <div class="form-group row">
                    <div class="col-lg-3">
                        <label for="fecha" class="col-xs-4 control-label ">Fecha inicial</label>
                        <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{old('fechaini')}}">
                    </div>
                    <div class="col-lg-3">
                        <label for="fechafin" class="col-xs-4 control-label ">Fecha final</label>
                        <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{old('fechafin')}}">
                    </div>
                </div>



                <div class="row col-12">
                    <label>&nbsp;</label>
                    <div class="form-group row">
                        <button type="submit" name="reset" id="reset" class="btn btn-warning btn-xl col-md-6">Limpiar</button>
                        <button type="submit" name="buscar" id="buscar" class="btn btn-success btn-xl col-md-6">Buscar</button>
                    </div>
                    <!-- /.row -->
                </div>


            </div>
            <!-- /.container-fluid -->
        </div>
    </div>
</div>

@include('menu.Medcold.form.dispensado.forminformedispensado')
@include('menu.Medcold.tabs.tabsIndexDispensado')

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

        var fechaini;
        var fechafin;

        // Función para llenar la tabla al cargar la página
        fill_datatable_tabla();

        // Callback para filtrar los datos de la tabla y detalle
        $('#buscar').click(function() {

            fechaini = $('#fechaini').val();
            fechafin = $('#fechafin').val();
            //historia = $('#historia').val();


            if (fechaini != '' && fechafin != '') {

                $('#dispensados').DataTable().destroy();

                fill_datatable_tabla(fechaini, fechafin);

            } else {

                Swal.fire({
                    title: 'Debes digitar fecha inicial y fecha final',
                    icon: 'warning',
                    buttons: {
                        cancel: "Cerrar"

                    }
                })
            }

        });

        $('#reset').click(function() {

            $('#fechaini').val('');
            $('#fechafin').val('');
            //$('#historia').val('');

            $('#dispensados').DataTable().destroy();
            fill_datatable_tabla();
        });

        function fill_datatable_tabla(fechaini = '', fechafin = '') {

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
                    if (tabId === "custom-tabs-one-datos-de-dispensado-tab") {
                        // Llamar a la función correspondiente al tab "Pendientes"
                        /* console.log("Pendientes"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#dispensados")) {
                            $("#dispensados").DataTable().destroy();
                            $(".diagnos").select2({
                                language: "es",
                                theme: "bootstrap4"
                            }).trigger('change');

                        }
                        // Funcion para pintar con data table la pestaña Lista de dispensados
                        var datatable =
                            $('#dispensados').DataTable({
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
                                    url: "{{route('medcold.dispensado1')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'idusuario'
                                    },
                                    {
                                        data: 'tipo'
                                    },
                                    {
                                        data: 'facturad'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'tipodocument'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'expediente'
                                    },
                                    {
                                        data: 'consecutivo'
                                    },
                                    {
                                        data: 'cums_rips'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'tipo_medicamento'
                                    },
                                    {
                                        data: 'nombre_generico'
                                    },
                                    {
                                        data: 'atc'
                                    },
                                    {
                                        data: 'forma'
                                    },
                                    {
                                        data: 'concentracion'
                                    },
                                    {
                                        data: 'unidad_medicamento'
                                    },
                                    {
                                        data: 'numero_unidades'
                                    },
                                    {
                                        data: 'regimen'
                                    },
                                    {
                                        data: 'paciente'
                                    },
                                    {
                                        data: 'primer_apellido'
                                    },
                                    {
                                        data: 'segundo_apellido'
                                    },
                                    {
                                        data: 'primer_nombre'
                                    },
                                    {
                                        data: 'segundo_nombre'
                                    },
                                    {
                                        data: 'cuota_moderadora'
                                    },

                                    {
                                        data: 'copago1',
                                        orderable: false
                                    }, //25
                                    {
                                        data: 'numero_entrega1',
                                        orderable: false
                                    }, //26
                                    {
                                        data: 'fecha_orden',
                                        orderable: false
                                    }, //27

                                    {
                                        data: 'fecha_suministro'
                                    },

                                    {
                                        data: 'diagnostico',
                                        orderable: false
                                    }, //29

                                    {
                                        data: 'ips',
                                        render: function(data, type, full, meta) {
                                            return '<select class="ipsss form-control select2bs4" style="width: 100%;" required data-id="' + full.id + '"></select>';
                                        }

                                    }, //30

                                    {
                                        data: 'autorizacion1',
                                        orderable: false
                                    }, //31

                                    {
                                        data: 'mipres1',
                                        orderable: false
                                    }, //32

                                    {
                                        data: 'reporte_entrega1',
                                        orderable: false
                                    }, //33

                                    {
                                        data: 'id_medico1',
                                        orderable: false
                                    }, //34

                                    {
                                        data: 'medico1',
                                        orderable: false
                                    }, //35


                                    {
                                        data: 'precio_unitario'
                                    },
                                    {
                                        data: 'valor_total'
                                    },
                                    {
                                        data: 'estado'
                                    }, //38
                                    {
                                        data: 'centroprod'
                                    },
                                    {
                                        data: 'drogueria'
                                    },
                                    {
                                        data: 'user_id'
                                    }, //41
                                    {
                                        data: 'cajero'
                                    },

                                    {
                                        data: 'action',
                                        orderable: false
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

                                drawCallback: (settings) => {

                                    $('.diagnos').select2({
                                        language: "es",
                                        theme: "bootstrap4",
                                        placeholder: 'Buscar cie10....',
                                        allowClear: true,
                                        ajax: {
                                            url: "{{ route('selectcie10') }}",
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

                                                            text: datas.codigo + "=>" + datas.descripcion,
                                                            id: datas.codigo

                                                        }
                                                    })
                                                };
                                            },
                                            cache: true
                                        }
                                    }).trigger('change');

                                    $('.ipsss').select2({
                                        language: "es",
                                        theme: "bootstrap4",
                                        placeholder: 'Buscar Ips....',
                                        allowClear: true,
                                        ajax: {
                                            url: "{{ route('selectlist') }}",
                                            dataType: 'json',
                                            delay: 250,
                                            data: function(params) {
                                                return {
                                                    q: params.term,
                                                    id: 1

                                                };
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: $.map(data.array[0], function(datas) {

                                                        return {

                                                            text: datas.slug + "=>" + datas.descripcion,
                                                            id: datas.id

                                                        }
                                                    })
                                                };
                                            },
                                            cache: true
                                        }
                                    }).trigger('change');

                                }

                            });

                    } else if (tabId === "custom-tabs-one-datos-disrevisado-tab") {
                        // Llamar a la función correspondiente al tab "En Tramite"
                        /* console.log("Pagos Parciales"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#revisados")) {
                            $("#revisados").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista En Tramite
                        var datatable =
                            $('#revisados').DataTable({
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
                                    url: "{{route('medcold.disrevisado')}}",
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [

                                    {
                                        data: 'idusuario'
                                    },
                                    {
                                        data: 'tipo'
                                    },
                                    {
                                        data: 'facturad'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'tipodocument'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'expediente'
                                    },
                                    {
                                        data: 'consecutivo'
                                    },
                                    {
                                        data: 'cums_rips'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'tipo_medicamento'
                                    },
                                    {
                                        data: 'nombre_generico'
                                    },
                                    {
                                        data: 'atc'
                                    },
                                    {
                                        data: 'forma'
                                    },
                                    {
                                        data: 'concentracion'
                                    },
                                    {
                                        data: 'unidad_medicamento'
                                    },
                                    {
                                        data: 'numero_unidades'
                                    },
                                    {
                                        data: 'regimen'
                                    },
                                    {
                                        data: 'paciente'
                                    },
                                    {
                                        data: 'primer_apellido'
                                    },
                                    {
                                        data: 'segundo_apellido'
                                    },
                                    {
                                        data: 'primer_nombre'
                                    },
                                    {
                                        data: 'segundo_nombre'
                                    },
                                    {
                                        data: 'cuota_moderadora'
                                    },

                                    {
                                        data: 'copago',
                                        orderable: false
                                    }, //26
                                    {
                                        data: 'numero_entrega',
                                        orderable: false
                                    }, //27
                                    {
                                        data: 'fecha_ordenamiento',
                                        orderable: false
                                    }, //28

                                    {
                                        data: 'fecha_suministro'
                                    },

                                    {
                                        data: 'dx',
                                        orderable: false
                                    }, //30

                                    {
                                        data: 'ips',
                                        orderable: false
                                    },

                                    {
                                        data: 'autorizacion',
                                        orderable: false
                                    }, //31

                                    {
                                        data: 'mipres',
                                        orderable: false
                                    }, //32

                                    {
                                        data: 'reporte_entrega_nopbs',
                                        orderable: false
                                    }, //33

                                    {
                                        data: 'id_medico',
                                        orderable: false
                                    }, //34

                                    {
                                        data: 'medico',
                                        orderable: false
                                    }, //35


                                    {
                                        data: 'precio_unitario'
                                    },
                                    {
                                        data: 'valor_total'
                                    },
                                    {
                                        data: 'estado'
                                    }, //38
                                    {
                                        data: 'centroprod'
                                    },
                                    {
                                        data: 'drogueria'
                                    },
                                    {
                                        data: 'user_id'
                                    }, //41
                                    {
                                        data: 'cajero'
                                    },

                                    {
                                        data: 'action',
                                        orderable: false
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
            //Poner la llave para cerrar el fill_datatable_tabla

        }

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
        $(document).on('click', '#syncapidis', function() {

            const text = 'De Medcol Dolor y Cuidados Paliativos';

            Swal.fire({
                title: "¿Estás por sincronizar lo dispensado?",
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
                url: "{{route('medcold.dispensadosyncapi')}}",
                type: 'GET',
                success: function(data) {
                    $('#dispensados').DataTable().ajax.reload();


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




        //Funcion de envio de datos

        $(document).on('click', '.add_medicamento', function() {

            var dispensado = [];
            var dispensadotrue = [];

            var id = $(this).attr('id');


            // Encuentra la fila (tr) más cercana que contiene el botón al que se le dio clic
            var tr = $(this).closest('tr');


            // Utiliza 'tr' en lugar de 'tbody tr' para recorrer solo la fila específica
            tr.each(function(el) {

                var itemdispensado = {};

                var tds = $(this).find("td");
                // itemdispensado.checked = tds.find(":checkbox").prop("checked");
                itemdispensado.id = id.trim();
                itemdispensado.copago1 = tds.filter(":eq(25)").find('input').val();
                itemdispensado.numero_entrega1 = tds.filter(":eq(26)").find('input').val();
                itemdispensado.fecha_orden = tds.filter(":eq(27)").find('input').val();
                itemdispensado.diagnostico = tds.filter(":eq(29)").find('select').val();
                itemdispensado.ips = tds.filter(":eq(30)").find('select').val();
                itemdispensado.autorizacion1 = tds.filter(":eq(31)").find('input').val();
                itemdispensado.mipres1 = tds.filter(":eq(32)").find('input').val();
                itemdispensado.reporte_entrega1 = tds.filter(":eq(33)").find('input').val();
                itemdispensado.id_medico1 = tds.filter(":eq(34)").find('input').val();
                itemdispensado.medico1 = tds.filter(":eq(35)").find('input').val();
                itemdispensado.estado = "REVISADO";
                itemdispensado.user_id = "{{ Auth::user()->id }}";

                // Ingreso cada array en la variable itemdispensado
                dispensado.push(itemdispensado);


            });


            $.each(dispensado, function(i, items) {

                console.log(items.id);
                console.log(items.fecha_orden);
                console.log(items.diagnostico);
                console.log(items.ips);
                console.log(items.autorizacion1);
                console.log(items.mipres1);
                console.log(items.reporte_entrega1);
                console.log(items.id_medico1);
                console.log(items.medico1);
                console.log(items.estado);
                console.log(items.user_id);

                if (items.numero_entrega1 == '' || items.fecha_orden == '' || items.diagnostico == '') {


                    Swal.fire({
                        icon: 'warning',
                        title: "Los campos numero de entrega, fecha orden y diagnostico no pueden estar vacios",
                        showConfirmButton: true,
                        timer: 1500
                    })


                } else if (items.autorizacion1 == '') {


                    enviardatos(dispensado);


                } else if (items.autorizacion1 != '' && items.mipre1 != '' && items.reporte_entrega1 != '') {


                    enviardatos(dispensado);


                } else {



                    Swal.fire({
                        icon: 'warning',
                        title: "Los campos numero de autorización, Mipres y reporte de entrega no pueden estar vacios",
                        showConfirmButton: true,
                        timer: 1500
                    })

                }

            });

        })


        function enviardatos(dispensado) {


            Swal.fire({
                    icon: "info",
                    title: 'Espere por favor !',
                    html: 'Realizando la revision..', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),
                $.ajax({

                    url: "{{route('add_dispensaciond')}}",
                    method: 'post',
                    data: {
                        data: dispensado,
                        "_token": $("meta[name='csrf-token']").attr("content")
                    },

                    success: function(data) {
                        if (data.success == 'ya') {

                            Swal.fire({
                                type: 'warning',
                                title: "Factura no adicionada",
                                showConfirmButton: true,
                                timer: 1500
                            })

                            $("#dispensados").DataTable().ajax.reload();

                        } else if (data.success == 'ok') {

                            Swal.fire({
                                type: 'success',
                                title: "Factura adicionada correctamente",
                                showConfirmButton: true,
                                timer: 1500
                            })

                            $("#dispensados").DataTable().ajax.reload();

                        }

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