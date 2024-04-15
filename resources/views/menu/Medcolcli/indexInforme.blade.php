@extends('layouts.app')


@section('title')

Pendientes Medcol

@endsection

@section("styles")



<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css" />




@endsection


@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('content')

<div class="content-wrapper col-mb-12" style="min-height: 543px;">
    <!-- Content Header (Page header) -->
    <div class="row">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-12">
                    <div class="col-sm-12">
                        <h1 class="m-0 text-dark">Informe Pendientes</h1>
                    </div><!-- /.col -->

                    @csrf
                    <div class="card-body">
                        <div class="row col-lg-12">

                            <div class="form-group row col-lg-12">
                                <div class="col-md-6">
                                    <label for="fechaini" class="col-xs-2 control-label requerido">Fecha de
                                        Informes</label>
                                    <div class="form-group row">
                                        <input type="date" name="fechaini" id="fechaini" class="form-control col-md-6" value="">
                                        <input type="date" name="fechafin" id="fechafin" class="form-control col-md-6" value="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label>&nbsp;</label>
                                    <div class="form-group row">
                                        <button type="submit" name="reset" id="reset" class="btn btn-warning btn-xl col-md-6">Limpiar</button>
                                        <button type="submit" name="buscar" id="buscar" class="btn btn-success btn-xl col-md-6">Buscar</button>
                                    </div>
                                </div>

                            </div>


                            </tr>
                            </td>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">



        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg p-3 mb-5 card-success card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-datos-del-pago-tab" data-toggle="pill" href="#custom-tabs-one-datos-del-pago" role="tab" aria-controls="custom-tabs-one-datos-del-pago" aria-selected="false">Lista de Pendientes</a>
                            </li>
                        </ul>
                    </div>


                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-pago" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-pago-tab">


                            @csrf
                            @include('menu.Medcolcli.tablas.tablaIndexPendientes')

                        </div>
                    </div>

                    <!-- /.card -->
                </div>
            </div>


        </div>


    </section>
    <!-- /.content -->

</div>




@endsection

@section("scriptsPlugins")

<script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/gijgo-combined-1.9.13/js/gijgo.min.js")}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>


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



        fill_datatable_tabla();


        // Callback para filtrar los datos de la tabla y detalle
        $('#buscar').click(function() {

            fechaini = $('#fechaini').val();
            fechafin = $('#fechafin').val();


            if (fechaini != '' && fechafin != '') {

                $('#pendientescli').DataTable().destroy();

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

            $('#pendientescli').DataTable().destroy();
            fill_datatable_tabla();

        });
        // Funcion para pintar con data table



        function fill_datatable_tabla(fechaini = '', fechafin = '') {

            var datatable = $('#pendientescli').DataTable({
                language: idioma_espanol,
                processing: true,
                lengthMenu: [
                    [25, 50, 100, 500, -1],
                    [25, 50, 100, 500, "Mostrar Todo"]
                ],
                processing: true,
                serverSide: true,
                aaSorting: [
                    [3, "desc"]
                ],
                ajax: {
                    url: "{{route('medcolCli.pendientes1')}}",
                    data: {
                        fechaini: fechaini,
                        fechafin: fechafin,
                        _token: "{{ csrf_token() }}"
                    },
                    method: 'POST'
                },
                columns: [

                    {
                        data: 'centroproduccion'
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
                        data: 'cajero'
                    },
                    {
                        data: 'usuario'
                    },
                    {
                        data: 'doc_entrega'
                    },
                    {
                        data: 'factura_entrega'
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: 'fecha_entrega'
                    }
                    /*,
                    {
                        data: 'diferencia_dias'
                    }*/
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