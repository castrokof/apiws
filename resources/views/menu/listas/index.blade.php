@extends("theme.$theme.layout")

@section('titulo')
    Listas Generales
@endsection
@section('styles')
   

    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset("assets/$theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css") }}" rel="stylesheet"
        type="text/css" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
        rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css"
        rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/js/gijgo-combined-1.9.13/css/gijgo.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('scripts')
    <script src="{{ asset('assets/pages/scripts/admin/listas/index.js') }}"></script>
@endsection

@section('contenido')
    @include('menu.listas.tablas.tablaIndexListas')
    @include('menu.listas.modal.modalListas')
    @include('menu.listas.modal.modalListasDetalle')
    @include('menu.listas.modal.modalListasDetalleIndex')
@endsection

@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>
    <script src="{{ asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js") }}"
        type="text/javascript"></script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/gijgo-combined-1.9.13/js/gijgo.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>


    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {

            // Funcion para pintar con data table tabla de listas generales
            var datatable = $('#listasGeneral').DataTable({
                language: idioma_espanol,
                processing: true,
                lengthMenu: [
                    [25, 50, 100, 500, -1],
                    [25, 50, 100, 500, "Mostrar Todo"]
                ],
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, "desc"]
                ],
                ajax: {
                    url: "{{ route('listasIndex') }}",
                },
                columns: [{
                        data: 'action',
                        order: false,
                        searchable: false
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'slug'
                    },
                    {
                        data: 'nombre'
                    },
                    {
                        data: 'descripcion'
                    },
                    {
                        data: 'activo'
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
                ]





            });




            // Función que envía los datos de listas al controlador ademas controla los input con sweat alert2

            $('#form-general1').on('submit', function(event) {
                event.preventDefault();
                var url = '';
                var method = '';
                var text = '';


                if ($('#action').val() == 'Add') {
                    text = "Estás por crear una lista"
                    url = "{{ route('crearlistas') }}";
                    method = 'post';
                }

                if ($('#nombre').val() == '' || $('#descripcion').val() == '' || $('#slug').val() == '' ||
                    $('#activo').val() == '') {
                    Swal.fire({
                        title: "Debes de rellenar todos los campos del formulario",
                        text: "Respuesta Paliativos",
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
                                data: $('#form-general1').serialize(),
                                dataType: "json",
                                success: function(data) {
                                    if (data.success == 'ok') {
                                        $('#form-general1')[0].reset();
                                        $('#modal-listas').modal('hide');
                                        $('#listasGeneral').DataTable().ajax.reload();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Lista creada correctamente',
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

            // Función para cambio de check y obtener el cambio del dom

            $(document).on('click', '.check_98', function() {

                if ($(this).is(':checked')) {
                    var data = {
                        id: $(this).val(),
                        _token: $('input[name=_token]').val()
                    };

                }
                //Si se ha desmarcado se ejecuta el siguiente mensaje.
                else {
                    var data = {
                        id: $(this).val(),
                        _token: $('input[name=_token]').val()
                    }
                }

                ajaxRequest('listas-estado', data);
            });


            // Función envío de datos para activar o desactivar

            function ajaxRequest(url, data) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(data) {

                        $('#listasGeneral').DataTable().ajax.reload();
                        Manteliviano.notificaciones(data.respuesta, data.titulo, data.icon);
                    }
                });
            }


            // Función para pintar Tabla detalle de listas


            filtroDetalle();

            function filtroDetalle(idlistp = '') {

                var datatable1 = $('#listasGeneralDetalle').DataTable({
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
                        url: "{{ route('listasdetalledetalle') }}",
                        data: {
                            id: idlistp
                        }
                    },
                    columns: [{
                            data: 'action'
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'slug'
                        },
                        {
                            data: 'nombre'
                        },
                        {
                            data: 'descripcion'
                        },
                        {
                            data: 'activo'
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
                    ]





                });


            }


            //Función para abrir detalle del registro



            $(document).on('click', '.listasDetalleAll', function() {

                var idlist = $(this).attr('id');
                var idlistp = $(this).attr('id');


                if (idlistp != '') {

                    $('#listasGeneralDetalle').DataTable().destroy();
                    filtroDetalle(idlistp);


                }

                $('#form-general2')[0].reset();
                $('#list_id').val(idlist);
                $.ajax({
                    url: "editar-listas/" + idlist + "",
                    dataType: "json",
                    success: function(result) {
                        $.each(result, function(i, items) {
                            $('#title-listas-detalle').text(items.nombre);
                            $('#modal-listas-detalle').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $('#modal-listas-detalle').modal('show');


                        });
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 403) {

                        Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                            'Sistema Paliativos', 'warning');

                    }
                });


            });


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
