@extends('layouts.app')

@section('titulo')
Usuarios Api
@endsection
@section("styles")


<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>




@endsection



@section('content')

    @include('menu.Medcold.tablas.tablaIndexEvolucion')
    {{-- @include('menu.usuario.modal.modalEvolucion')
    @include('menu.Medcold.modal.modalindexresumenPsi') --}}

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
var datatable = $('#usuarioapi').DataTable({
            language: idioma_espanol,
            processing: true,
            lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
            processing: true,
            serverSide: true,
            aaSorting: [[ 0, "asc" ]],
            ajax:{
              url:"{{route('usuariosapi')}}",
                  },
            columns: [
              {data:'idapi'},
              {data:'name'},
              {data:'email'},
              {data:'created_api'},

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



$('#form-general').on('submit', function(event){
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';


        if($('#action').val() == 'Add')
        {
            text = "Estás por crear un usuario"
            url = "{{route('guardar_usuario')}}";
            method = 'post';
        }

        if ($('#surname').val() == '' || $('#fname').val()== '' || $('#type_document').val()== '' || $('#document').val()== '' ||
        $('#date_birth').val() == '' || $('#municipality').val()== '' || $('#address').val()== '' || $('#celular').val()== '' ||
         $('#sex').val()== '' || $('#eapb').val()== '' || $('#reason_consultation').val()== '' ||
         $('#consultation').val()== '' )
        {
        Swal.fire({
            title: "Debes de rellenar todos los campos del formulario",
            text: "Respuesta Linea Psicologica",
            icon: "warning",
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
            });
         }else{

            Swal.fire({
            title: "¿Estás seguro?",
            text: text,
            icon: "success",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
            }).then((result)=>{
            if(result.value){
            $.ajax({
                url:url,
                method:method,
                data: $('#form-general').serialize(),
                dataType:"json",
                success:function(data){
                            if(data.success == 'ok') {
                            $('#form-general')[0].reset();
                            $('#modal-evolution').modal('hide');
                            $('#psicologica').DataTable().ajax.reload();
                            Swal.fire(
                                {
                                icon: 'success',
                                title: 'diagnostico creado correctamente',
                                showConfirmButton: false,
                                timer: 2000
                                }    )}
                            else if(data.errors != null) {
                            Swal.fire(
                                {
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
