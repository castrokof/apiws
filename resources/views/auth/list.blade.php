@extends('layouts.app')

@section('titulo')
Usuarios APIWS
@endsection
@section("styles")


<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>




@endsection



@section('content')

    @include('auth.tablas.tablaIndexEvolucion')
     @include('auth.modal.modal-edit-user')
  

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

$(document).ready(function(){

       // Funcion para pintar con data table
var datatable = $('#usuarioapiws').DataTable({
            language: idioma_espanol,
            processing: true,
            lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
            processing: true,
            serverSide: true,
            aaSorting: [[ 0, "asc" ]],
            ajax:{
              url:"{{route('usuariosapiws')}}",
                  },
            columns: [
              {data:'action'},
              {data:'name'},
              {data:'email'},
              {data:'email_verified_at'},

            ],

             //Botones----------------------------------------------------------------------

             "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

             buttons: [
                          {

                       extend:'copyHtml5',
                       titleAttr: 'Copiar Registros',
                       title:"Control de horas",
                       className: "btn  btn-outline-primary btn-sm"


                          },
                          {

                       extend:'excelHtml5',
                       titleAttr: 'Exportar Excel',
                       title:"Control de horas",
                       className: "btn  btn-outline-success btn-sm"


                          },
                           {

                       extend:'csvHtml5',
                       titleAttr: 'Exportar csv',
                       className: "btn  btn-outline-warning btn-sm"

                          },
                          {

                       extend:'pdfHtml5',
                       titleAttr: 'Exportar pdf',
                       className: "btn  btn-outline-secondary btn-sm"


                          }
                       ],



 });
        
        
          $('#form-general').on('submit', function(event) {
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';


            if ($('#action').val() == 'Edit') {
                text = "Estás por actualizar un usuario"
                var updateid = $('#hidden_id').val();
                url = "usuarioupdate/" + updateid;
                method = 'put';
            }
            Swal.fire({
                title: "¿Estás seguro?",
                text: text,
                type: "info",
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
                            if (data.status === 'success') {
                               $('#form-general')[0].reset();
                                $('#modal-u').modal('hide');
                                $('#usuarioapiws').DataTable().ajax.reload();
                                Swal.fire({
                                    type: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1500

                                })
                                // Manteliviano.notificaciones('cliente creado correctamente', 'Sistema Ventas', 'success');

                            } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message,
                                    });
                                }
                        },


                    }).fail(function(jqXHR, textStatus, errorThrown) {

                        if (jqXHR.status === 422) {

                            var error = jqXHR.responseJSON;

                            $.each(error, function(i, items) {

                                var errores = [];
                                errores.push(items.name + '<br>');
                                errores.push(items.email + '<br>');
                                errores.push(items.drogueria + '<br>');
                                errores.push(items.rol + '<br>');
                               
                                console.log(errores);

                                var filtered = errores.filter(function(el) {
                                    return el != "undefined<br>";
                                });

                                
                                Swal.fire({
                                    icon: 'danger',
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
        
        

      
      
      
        // Edición de cliente

        $(document).on('click', '.edit_user', function() {
            var id = $(this).attr('id');

            $.ajax({
                url: "usuario/" + id +"/editar",
                dataType: "json",
                success: function(data) {
                     if (data.status === 'success') {
                    $('#name').val(data.data.name);
                    $('#email').val(data.data.email);
                    $('#drogueria').val(data.data.drogueria);
                    $('#rol').val(data.data.rol);
                    $('#password').val('');
                    $('#password-confirm').val('')
                    $('#hidden_id').val(id)
                    $('.card-title').text('Editar usuario');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#modal-u').modal('show');

                }else {
                // Maneja cualquier otro estado de éxito no esperado
                Manteliviano.notificaciones(data.message, 'Apiws Medcol', 'warning');
            }

                },

            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                        'Apiws Medcol', 'warning');

                }
            });

        });








});

var idioma_espanol =
                 {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
                }
</script>
@endsection
