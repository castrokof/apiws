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

@include('menu.usuario.modal.modalPendientes')

@endsection

@section("scriptsPlugins")
<script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/gijgo-combined-1.9.13/js/gijgo.min.js")}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>


<script>
    $(document).ready(function() {

        function mostrarOcultarCampos() {
            var estado_id = $('#estado option:selected');
            var estado_texto = estado_id.text();
            var futuro1 = $('#futuro1');
            var futuro2 = $('#futuro2');
            var futuro3 = $('#futuro3');
            var enviar_fecha_entrega = $('#enviar_fecha_entrega');
            var enviar_fecha_impresion = $('#enviar_fecha_impresion');

            if (estado_texto == "EN TRANSITO") {
                futuro2.show();
                futuro1.hide();
                futuro3.hide();
                enviar_fecha_impresion.val('true');
                enviar_fecha_entrega.val('false');
            } else if (estado_texto == "ENTREGADO") {
                futuro1.show();
                futuro2.hide();
                futuro3.hide();
                enviar_fecha_entrega.val('true');
                enviar_fecha_impresion.val('false');
            } else if (estado_texto == "PENDIENTE") {
                futuro1.hide();
                futuro2.hide();
                futuro3.show();
                enviar_fecha_entrega.val('false');
                enviar_fecha_impresion.val('false');
            } else {
                futuro1.hide();
                futuro2.hide();
                futuro3.hide();
                enviar_fecha_entrega.val('false');
                enviar_fecha_impresion.val('false');
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
        $(document).on('click', '.edit_pendiente', function() {

            $('#form-general')[0].reset();
            var id = $(this).attr('id');


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

                    $('#modal-edit-pendientes').modal('show');

                },

            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                        'Sistema pendientes por pagar', 'warning');

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
                icon: "success",
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
                                $('#form-general')[0].reset();
                                $('#modal-edit-pendientes').modal('hide');
                                /* limpiarModal(); */
                                $('#pendientes').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Cuenta por pagar creada correctamente',
                                    showConfirmButton: false,
                                    timer: 1500

                                })


                            } else if (data.success == 'ok1') {
                                $('#form-general')[0].reset();
                                $('#modal-edit-pendientes').modal('hide');
                                $('#pendientes').DataTable().ajax.reload();
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
