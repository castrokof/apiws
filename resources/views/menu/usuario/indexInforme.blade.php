@extends("theme.$theme.layout")

@section('titulo')
Linea Psicologica
@endsection
@section("styles")


<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>



@endsection


@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/psicologia/index.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')

    @include('lineaPsicologica.form.formIndexInforme')

    @include('lineaPsicologica.modal.modalindexresumenPsi')

@endsection

@section("scriptsPlugins")

    <script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
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

$(document).ready(function(){

    var fechaini;
    var fechafin;



fill_datatable_tabla();


// Callback para filtrar los datos de la tabla y detalle
$('#buscar').click(function(){

fechaini = $('#fechaini').val();
fechafin = $('#fechafin').val();


    if(fechaini != '' && fechafin != ''){

        $('#informepsicologica').DataTable().destroy();

        fill_datatable_tabla(fechaini, fechafin);

    }else{

        Swal.fire({
        title: 'Debes digitar fecha inicial y fecha final',
        icon: 'warning',
        buttons:{
            cancel: "Cerrar"

                }
        })
    }

});

$('#reset').click(function(){

$('#fechaini').val('');
$('#fechafin').val('');
$('#informepsicologica').DataTable().destroy();
fill_datatable_tabla();

});
       // Funcion para pintar con data table



function  fill_datatable_tabla(fechaini = '', fechafin = ''){

var datatable = $('#informepsicologica').DataTable({
            language: idioma_espanol,
            processing: true,
            lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
            processing: true,
            serverSide: true,
            aaSorting: [[ 20, "desc" ]],
            ajax:{
              url:"{{route('informepsico')}}",
              data:{fechaini:fechaini, fechafin:fechafin },
                  },
            columns: [
              {data:'action',
              order: false, searchable: false},
              {data:'id'},
              {data:'surname'},
              {data:'ssurname'},
              {data:'fname'},
              {data:'sname'},
              {data:'type_document'},
              {data:'document'},
              {data:'date_birth'},
              {data:'municipality'},
              {data:'other'},
              {data:'address'},
              {data:'celular'},
              {data:'phone'},
              {data:'email'},
              {data:'sex'},
              {data:'eapb'},
              {data:'reason_consultation'},
              {data:'consultation'},
              {data:'diagnosis'},
              {data:'usuario', name: 'usuario.usuario'},
              {data:'created_at'}
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
                       "columnDefs": [
                        {

                                    "render": function ( data, type, row ) {
                                        if (row["consultation"] == 1) {
                                        return data +' - Orientación Psicológica';

                                    }else{

                                            return data +' - Call center';

                                        }

                                        },
                                        "targets":[18]
                        },
                        {
                            'targets': [0],
                            'visible': true,
                            'searchable': false
                        }


                       ],

                        "createdRow": function(row, data, dataIndex) {
                        if (data["consultation"] == 1) {
                            $($(row).find("td")[18]).addClass("btn btn-sm btn-danger rounded-lg");
                        }else{
                            $($(row).find("td")[18]).addClass("btn btn-sm btn-dark rounded-lg");
                            }

                        }


 });


}



      //Función para abrir detalle del registro
$(document).on('click', '.resumen', function(){
    var idevo = $(this).attr('id');
    $('#names2').empty();
    $('#documents1').empty();
    $('#evolution1').empty();
    $('#names3').empty();
    $('#address1').empty();
    $('#date_birth1').empty();
    $('#celular1').empty();
    $('#sex1').empty();
    $('#consultation1').empty();
    $('#created_at1').empty();

    console.log(idevo);
    $.ajax({
    url:"evolucion/"+idevo+"",
    dataType:"json",
    success:function(data){
        $.each(data[0], function(i, items){
        $('#names2').append(items.surname + " " + items.fname);
        $('#documents1').append(items.type_document + "-" + items.document);
        $('#evolution1').append(items.reason_consultation);
        $('#names3').append(items.surname  + " " +  items.fname);
        $('#address1').append("Ciudad: "+ items.municipality + " | Dirección: "+items.address + " | Eapb: " +items.eapb);
        $('#date_birth1').append(items.date_birth);
        $('#celular1').append(items.celular);
        if(items.sex == "M"){
            $('#sex1').append("MASCULINO");
        }else{
            $('#sex1').append("FEMENINO") ;
        }
        $('#created_at1').append("Fecha de evolución: "+ " " + items.created_at);
        if(items.consultation == 1){
        $('#consultation1').append("Evolución "+items.consultation+"-"+"Orientación Psicológica");
        }
        $('.modal-title-resumen1').text('Evolución');
        $('#modal-resumen1').modal({backdrop: 'static', keyboard: false});
        $('#modal-resumen1').modal('show');

    });
    }


    }).fail( function( jqXHR, textStatus, errorThrown ) {

    if (jqXHR.status === 403) {

    Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

    }});


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
