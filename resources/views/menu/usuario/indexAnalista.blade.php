@extends('layouts.app')

@section('titulo')
Pendientes Medcol San Fernando
@endsection
@section("styles")

<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/lte/plugins/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css" />


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

@include('menu.usuario.tabs.tabsIndexAnalista')
@include('menu.usuario.modal.modalindexresumen')
@include('menu.usuario.modal.modalindexaddseguimiento')




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
                    Manteliviano.notificaciones(data.respuesta, data.titulo, data.icon);
                }
            });
        }



        // Funcion para pintar con data table la pestaña de linea psicologica
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



        //Función para abrir modal del detalle de la evolución y muestra las observaciones agregadas
        $(document).on('click', '.resumen', function() {
            var idevo = $(this).attr('id');
            $('#names').empty();
            $('#documents').empty();
            $('#evolution').empty();
            $('#names1').empty();
            $('#address').empty();
            $('#date_birth').empty();
            $('#celular').empty();
            $('#sex').empty();
            $('#consultation').empty();
            $('#created_at').empty();
            $('#observaciones_chat').empty();


            $.ajax({
                url: "evolucion/" + idevo + "",
                dataType: "json",
                success: function(data) {


                    var usuarios = data[1];
                    console.log(usuarios);
                    console.log(data[0]);
                    $.each(data[0], function(i, items) {
                        $('#names').append(items.surname + " " + items.fname);
                        $('#documents').append(items.type_document + "-" + items.document);
                        $('#evolution').append(items.reason_consultation);
                        $('#names1').append(items.surname + " " + items.fname);
                        $('#address').append("Ciudad: " + items.municipality + " | Dirección: " + items.address + " | Eapb: " + items.eapb);
                        $('#date_birth').append(items.date_birth);
                        $('#celular').append(items.celular);
                        if (items.sex == "M") {
                            $('#sex').append("MASCULINO");
                        } else {
                            $('#sex').append("FEMENINO");
                        }
                        $('#created_at').append("Fecha de evolución: " + " " + items.created_at);
                        if (items.consultation == 1) {
                            $('#consultation').append("Evolución " + items.consultation + "-" + "Orientación Psicológica");
                        }
                    });


                    $.each(usuarios, function(i, items1) {
                        $.each(data[0], function(i, items) {
                            $.each(items.observacionadd, function(i, itemobs) {
                                var filtered = items1.filter(el => el.id == itemobs.user_id);
                                $.each(filtered, function(i, itemsusu) {
                                    $('#observaciones_chat').append(
                                        '<div class="direct-chat-msg">' +
                                        '<div class="direct-chat-infos clearfix">' +
                                        '<span class="direct-chat-name float-left">' + 'Usuario: ' + itemsusu.usuario + '</span>' +
                                        '<span class="direct-chat-timestamp float-right">' + 'Fecha creación: ' + itemobs.created_at + '</span>' +
                                        '</div>' +
                                        '<div class="direct-chat-text">' + 'Observación: ' +
                                        itemobs.addobservacion +
                                        '</div>' +
                                        '</div>');
                                });
                            });

                        });
                    });




                    $('.modal-title-resumen').text('Evolución');
                    $('#modal-resumen').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modal-resumen').modal('show');
                }


            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

                }
            });


        });


        //Función para abrir modal y prevenir el cierre de agregar observaciones

        $(document).on('click', '.seguimientoadd', function() {
            var idas = $(this).attr('value');

            $('#namesadd').empty();
            $('#documentsadd').empty();
            $('#evo_id').val(idas);
            /* $('#user_id').val({
                {
                    Session() -> get('usuario_id') ?? ''
                }
            }); */

            $.ajax({
                url: "addseguimiento/" + idas + "",
                dataType: "json",
                success: function(data) {
                    $.each(data.add, function(i, items) {
                        $('#namesadd').append(items.surname + " " + items.fname);
                        $('#documentsadd').append(items.type_document + "-" + items.document);
                        $('.modal-title-addseguimiento').text('Add Seguimiento');
                        $('#modal-addseguimiento').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('#modal-addseguimiento').modal('show');

                    });
                }


            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

                }
            });


        });

        //Función para abrir modal y prevenir el cierre
        $(document).on('click', '.observacion', function() {
            var idas = $(this).attr('value');

            $('#namesadd').empty();
            $('#documentsadd').empty();
            $('#evo_id').val(idas);
            /* $('#user_id').val({
                {
                    Session() - > get('usuario_id') ?? ''
                }
            }); */

            $.ajax({
                url: "addseguimiento/" + idas + "",
                dataType: "json",
                success: function(data) {
                    $.each(data.add, function(i, items) {
                        $('#namesadd').append(items.surname + " " + items.fname);
                        $('#documentsadd').append(items.type_document + "-" + items.document);
                        $('.modal-title-addseguimiento').text('Add Seguimiento');
                        $('#modal-addseguimiento').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('#modal-addseguimiento').modal('show');

                    });
                }


            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

                }
            });


        });

        // Función que envían los datos de la obervación al controlador
        $('#form-generaladd').on('submit', function(event) {
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';


            if ($('#action').val() == 'Add') {
                text = "Estás por crear una observación"
                url = "{{route('guardar_observacion')}}";
                method = 'post';
            }

            if ($('#addobservacion').val() == '') {
                Swal.fire({
                    title: "Debes de rellenar todos los campos del formulario",
                    text: "Respuesta Linea Psicologica",
                    icon: "warning",
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                });
            } else {

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: text,
                    icon: "success",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            method: method,
                            data: $('#form-generaladd').serialize(),
                            dataType: "json",
                            success: function(data) {
                                if (data.success == 'ok') {
                                    $('#form-generaladd')[0].reset();
                                    $('#modal-addseguimiento').modal('hide');
                                    $('#psicologica').DataTable().ajax.reload();
                                    $('#psicologicaSeguimiento').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Observación agregada correctamente y estado en seguimiento',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })
                                } else
                                if (data.success == 'ok1') {
                                    $('#form-generaladd')[0].reset();
                                    $('#modal-addseguimiento').modal('hide');
                                    $('#psicologicaSeguimiento').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Observación agregada correctamente',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })
                                } else if (data.errors != null) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: data.errors,
                                        showConfirmButton: false,
                                        timer: 3000
                                    })
                                }
                            }

                        });
                    }
                });

            }

        });









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
