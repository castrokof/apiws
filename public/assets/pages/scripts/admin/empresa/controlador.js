
 jQuery(function($) {
    //initiate dataTables plugin
  var datatable = 
    $('#empresa')
    //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
    .DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    ajax:{
      url:"{{ route('empresa')}}",
          },
    columns: [
      {data:'editar'},
      {data:'id'},
      {data:'nombre'},
      {data:'tipo_documento'},
      {data:'documento'},
      {data:'activo'},
      
    ],

     //Botones----------------------------------------------------------------------
 
     "dom":'<"row"<"col-md-9 form-inline"l><"col-xs-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',
               
               buttons: [
                  {

               extend:'copyHtml5',
               titleAttr: 'Copy',
               title:"seguimiento",
               className: "btn btn-info"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Excel',
               title:"seguimiento",
               className: "btn btn-success"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'csv',
               className: "btn btn-warning"


                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'pdf',
               className: "btn btn-primary"


                  }
               ],
    

    });

$('#create_empresa').click(function(){
$('#modal-u').modal('show');
});

$('#form-general').on('click', '#guardar',  function(){


var nombre = $('#nombre').val();
var tipo_documento = $('#tipo_documento').val();
var documento = $('#documento').val();
var activo = $('#activo').val();

if(nombre == '' || tipo_documento == '' || documento == '' || activo == ''){

    Swal.fire({
     title: 'Debes rellenar todos los campos',
     icon: 'warning',
     buttons:{
         cancel: "Cerrar"
         
             }
       }) 

}else{
 Swal.fire({
 title: "¿Estás seguro?",
 text: "Estás por crear empresa",
 icon: "success",
 showCancelButton: true,
 showCloseButton: true,
 confirmButtonText: 'Aceptar',
 }).then((result)=>{
if(result.value){  
   $.ajax({
    
         url:"{{route('guardar_empresa')}}",
         method:'post',
         data:{nombre:nombre, tipo_documento:tipo_documento, documento:documento, activo:activo,
          "_token": $("meta[name='csrf-token']").attr("content")},

         success:function(respuesta)
         {  
          if(respuesta.mensaje = 'ok') {
           $('#modal-u').modal('hide');
           $('#empresa').DataTable().ajax.reload();
           
           Manteliviano.notificaciones('Empresa creada correctamente', 'Sistema Ventas', 'success');
           }else if(respuesta.mensaje = 'ng'){
          
             $('#empresa').DataTable().ajax.reload();
             Manteliviano.notificaciones('Nombre o Documento ya estan registrados, documento debe ser numerico', 'Sistema Ventas', 'warning');
         }
         }
        
          }).fail( function( jqXHR, textStatus, errorThrown ) {

      if (jqXHR.status === 0) {

        alert('Not connect: Verify Network.');

      } else if (jqXHR.status == 404) {

        alert('Requested page not found [404]');

      } else if (jqXHR.status == 422) {
             Manteliviano.notificaciones('Nombre o Documento ya estan registrados, documento debe ser numerico y menor de 9999999999','Sistema Ventas',  'warning'); 
          
        
      } else if (textStatus === 'parsererror') {

        alert('Requested JSON parse failed.');

      } else if (textStatus === 'timeout') {

        alert('Time out error.');

      } else if (textStatus === 'abort') {

        alert('Ajax request aborted.');

      }       
        
          });

}

});

}
})
});
// $(document).on('click', '#guardar', function(){

//   var nombre = $('#nombre').val();
//   var tipodedocumento = $('#tipodedocumento').val();
//   var documento = $('#documento').val();
//   var estado = $('#estado').val();

//   if(nombre == '' || tipodedocumento == '' || documento == '' || estado == ''){

//         Swal.fire({
//          title: 'Debes rellenar todos los campos',
//          icon: 'warning',
//          buttons:{
//              cancel: "Cerrar"
         
//                  }
//            }) 

//   }else{

// Swal.fire({
//      title: "¿Estás seguro?",
//      text: "Estás por crear empresa",
//      icon: "success",
//      showCancelButton: true,
//      showCloseButton: true,
//      confirmButtonText: 'Aceptar',
//      }).then((result)=>{
//     if(result.value){  
//        $.ajax({
    
//              url:"{{ route('guardar_empresa')}}",
//              method:'post',
//              data:{nombre:nombre, tipo_documento:tipodedocumento, documento:documento, activo:estado,
         
//                "_token": $("meta[name='csrf-token']").attr("content")
         
//              },
//              success:function(respuesta)
//              {  
//                if(respuesta.mensaje = 'ok') {
//                 $('#modal-u').modal('hide');
//                $('#empresa').DataTable().ajax.reload();
//                Manteliviano.notificaciones('Empresa creada correctamente', 'Sistema Ventas', 'success');
//                }else if(respuesta.mensaje = 'ng'){
//                 $('#modal-u').modal('hide');
//                  $('#empresa').DataTable().ajax.reload();
//                  Manteliviano.notificaciones('Error', 'Sistema Ventas');
//              }
//              }
        
//               });

//        }
  
//       });
//     }
//   });


function Mostrar(btn){
var id = $(this).attr('id');

$('#modal-a').modal('show');
$.ajax({
url:"http://127.0.0.1:8000/empresa/+id+/editar",
dataType:"json",
success:function(data){
$('#nombre').val(data.nombre);
$('#tipo_documento').val(data.tipo_documento);
$('#documento').val(data.documento);
$('#activo').val(data.activo);
$('#modal-a').modal('show');
}
})

}
// $(document).on('click', '.edit', function(){

//   var id = $(this).attr('id');
//   $('#form_result').html('');
//   $.ajax({
//     url:"/empresa/"+id+"/editar",
//     dataType:"json",
//     success:function(data){
//       $('#nombre').val(data.result.nombre);
//       $('#tipo_documento').val(data.result.tipo_documento);
//       $('#documento').val(data.result.documento);
//       $('#activo').val(data.result.activo);
//     }

//   })

// })


   

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
   