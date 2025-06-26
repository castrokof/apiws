@extends('layouts.app')
@section("styles")
<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css")}}" rel="stylesheet"  type="text/css"/>
<link href="{{asset("assets/lte/plugins/toastr/toastr.css")}}" rel="stylesheet" type="text/css"/>

<style>
    .loader { 
     
    visibility: hidden; 
    background-color: rgba(255, 253, 253, 0.952); 
    position: absolute;
    z-index: +100 !important;
    width: 100%;  
    height:100%;
   }
      .loader img { position: relative; top:50%; left:40%;
        width: 180px; height: 180px; }
  </style>
@endsection
@section('content')
<div class="container col-12">
    <div class="loader"><img src="{{asset("assets/lte/dist/img/loader6.gif")}}" class="" /> </div>                   
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card col-l-12">
                <div class="card-header bg-info">{{ __('Direccionados') }}</div>

                <div class="card-body">
                        @isset($error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                        @endisset

                    {{-- {{ __('You are logged in!') }} --}}
                   
                    <form  action="{{route('home')}}" method="get">
                    @include('form.form-consulta')
                    
                    <button type="submit" id="consultar" class="btn btn-success">Consultar</button><button type="button" id="enviar" class="btn btn-warning">Programar</button>
                    </form>
                    
                    <div class="card-body col-md-12 table-responsive p-2">
                        <table id="mipres" class="table text-nowrap table-bordered" style="width:100%">
                        
                    <thead>
                        <tr>
                        <th class="width40"><input name="selectall" id="selectall" type="checkbox" class="select-all" /> Select / Deselect All</th>
                        <th>ID:</th>
                        <th>ID Direccionamiento:</th>
                        <th>Prescripcion:</th>
                        <th>Cons.:</th>
                        <th>Tipo documento:</th>
                        <th>Documento:</th>
                        <th>Tecnologia:</th>
                        <th>Desc Mipres:</th>
                        <th>Cantidad a entregar:</th>
                        <th>Numero entrega:</th>
                        <th>TipoIDProv:</th>
                        <th>NoIDProv:</th>
                        <th>Fecha máxima de entrega:</th>
                        <th>Fecha Direccionamiento:</th>
                        <th>CodSedeProv:</th>
                        <th>NIT EPS:</th>
                        <th>Cod EPS:</th>
                       </tr>
                    </thead>
                       <tbody>
                       
                        </tbody>    
                        </table>
                    </div>
                   
                </div>
               

                    

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section("scriptsPlugins")
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{asset("assets/lte/plugins/toastr/toastr.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script>

$(document).ready(function(){
    
    
        $("#selectall").on('click', function() {
          $(".case").prop("checked", this.checked);
        });

  var table =  $('#mipres').DataTable({
        
        lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
        language: idioma_espanol,
        processing: true,


        
         //Botones----------------------------------------------------------------------
     
        "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',
         
                   
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
                      "createdRow": function(row, data, dataIndex) { 
                    if (data[16] == "890303093") { 
                    $(row).css("background-color", "#90EE90"); 
                    $(row).addClass("warning");
                    
                    }else if(data[16] == "800112806"){
                    $(row).css("background-color", "#87CEEB"); 
                    $(row).addClass("warning"); 
                    }
        
                   }
    });
    
    function getDescripcionCodSerTec(codigo) {
    switch(parseInt(codigo)) {
        case 107:
            return "ASEO PERSONAL (GEL ANTIBACTERIAL, DESODORANTES, PROTECTORES LABIALES, TOALLAS DE PAPEL, TOALLAS HIGIENICAS, MAQUILLAJE, ENTRE OTRAS)";
        case 108:
            return "BLOQUEADORES SOLARES";
        case 109:
            return "CHAMPÚ Y LOCIONES CAPILARES";
        case 112:
            return "CREMAS ANTIPAÑALITIS";
        case 113:
            return "CREMAS CICATRIZANTES Y REPARADORES DÉRMICOS";
        case 114:
            return "CREMAS Y LOCIONES HUMECTANTES, HIDRATANTES Y EMOLIENTES";
        case 121:
            return "HIGIENE ORAL (CEPILLO, CREMA, SEDA DENTAL, ENJUAGUE)";
        case 127:
            return "JABONES COSMÉTICOS, ANTIALÉRGICOS Y ANTIBACTERIALES";
        case 130:
            return "LOCIONES REPELENTES DE USO PERSONAL Y DOMÉSTICO";
        case 133:
            return "MEDICAMENTOS FITOTERAPEÚTICOS";
        case 134:
            return "MEDICAMENTOS HOMEOPÁTICOS";
        case 139:
            return "PAÑALES";
        case 140:
            return "PAÑITOS HÚMEDOS";
        case 146:
            return "SUPLEMENTOS DIETARIOS";
        default:
            return "Medicamento";
    }
}
    
    // Función para cargar datos que puede ser reutilizada
    function cargarDatos(params = {}) {
        
                
        
        // Parámetros por defecto para la carga inicial
        var defaultParams = {
            cargaInicial: true,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        
        // Combinar parámetros por defecto con los proporcionados
        var requestParams = $.extend({}, defaultParams, params);
        
         Swal.fire({
                    icon: "info",
                    title: 'Espere por favor !',
                    html: 'Consultando con el ministerio', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),

        
        $.ajax({
            url: "{{ route('homeapi') }}",
            method: 'GET',
            dataType: "json",
            data: requestParams,
            success: function(response) {
                if (response.success) {
                    // Limpiamos la tabla existente
                    table.clear();
                    
                    // En tu función success del AJAX
                    $.each(response.data, function(i, items) {
                        $.each(items, function(j, item) {
                            var checkboxCell = '<input class="case" type="checkbox" title="Selecciona Orden" value="' + item.ID + '">';
                            
                            table.row.add([
                                checkboxCell,
                                item.ID || '',
                                item.IDDireccionamiento || '',
                                item.NoPrescripcion || '',
                                item.ConTec || '',
                                item.TipoIDPaciente || '',
                                item.NoIDPaciente || '',
                                item.CodSerTecAEntregar || '',
                                getDescripcionCodSerTec(item.CodSerTecAEntregar),
                                item.CantTotAEntregar || '',
                                item.NoEntrega || '',
                                item.TipoIDProv || '',
                                item.NoIDProv || '',
                                item.FecMaxEnt || '',
                                item.FecDireccionamiento || '',
                                "{{'PROV007788'}}",
                                item.NoIDEPS || '',
                                item.CodEPS || ''
                            ]).draw(false);
                        });
                    });
                    
                    // Vuelve a aplicar los eventos a los checkboxes
                    $(".case").on('click', function() {
                        if ($(".case:checked").length == $(".case").length) {
                            $("#selectall").prop("checked", true);
                        } else {
                            $("#selectall").prop("checked", false);
                        }
                    });
                    
                    toastr.success('Estado: ' + response.message);
                    
                } else if (response.error === 'token') {
                    // Redirigir al usuario a la página de token
                    window.location.href = "{{ route('tokenhercules') }}";
                } else {
                    toastr.error('Error al cargar los datos: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 401 || (xhr.responseJSON && xhr.responseJSON.error === 'token')) {
                    // Redirigir al usuario a la página de token
                    window.location.href = "{{ route('tokenhercules') }}";
                } else {
                    toastr.error('Error en la petición: ' + error);
                }
            }, complete: function() {
                Swal.close(); // Cierra el modal de carga
            }
        });
    }
    
    // Cargar datos automáticamente al iniciar la página
    cargarDatos();
    
    // Manejar el evento de clic en el botón "Consultar"
    $("#consultar").click(function(e) {
        e.preventDefault(); // Evitar el envío del formulario por defecto
        
        // Obtener valores del formulario
        var fechaini = $("input[name='fechaini']").val();
        var fechafin = $("input[name='fechafin']").val();
        var prescripcion = $("textarea[name='prescripcion']").val();
        
        // Llamar a la función con los parámetros del formulario
        cargarDatos({
            fechaini: fechaini,
            fechafin: fechafin,
            prescripcion: prescripcion,
            cargaInicial: false, // Ya no es carga inicial
            "_token": $("meta[name='csrf-token']").attr("content")
        });
    });
    
    
     // Filtrar por varios números usando expresión regular
    $('#filtro-numeros').on('keyup', function() {
        var filtro = $(this).val();
        var filtroRegex = filtro.split(' ').join('|'); // Convertir la lista de números en una expresión regular
        table.column(2) // Aquí usas el índice de la columna que contiene los números (por ejemplo, la columna 1)
            .search(filtroRegex, true, false) // Aplicar el filtro con la expresión regular
            .draw();
    });

//Funcion de envio de datos

    $(function(){

   
        $("#enviar").click(function(){

            var mipre =[];
            var mipretrue =[];
                   
    $("tbody tr").each(function(el){
                
                    var itemmipres = {};

            

                var tds = $(this).find("td");
                itemmipres.checked = tds.find(":checkbox").prop("checked");
                itemmipres.ID = parseFloat(tds.filter(":eq(1)").text());
                itemmipres.FecMaxEnt = tds.filter(":eq(13)").text();
                itemmipres.TipoIDSedeProv = tds.filter(":eq(11)").text();
                itemmipres.NoIDSedeProv = tds.filter(":eq(12)").text();
                itemmipres.CodSedeProv = tds.filter(":eq(15)").text();
                itemmipres.CodSerTecAEntregar = tds.filter(":eq(7)").text();
                itemmipres.CantTotAEntregar = tds.filter(":eq(9)").text();
                
                // Ingreso cada array en la variable itemmipres
                mipre.push(itemmipres);       
           
          
              
                       
            });           
            
            
            $.each(mipre, function(i, items) {

                var itemmiprestrue = {};

                 if(items.checked == true){
                    itemmiprestrue.ID = items.ID;
                    itemmiprestrue.FecMaxEnt = items.FecMaxEnt;
                    itemmiprestrue.TipoIDSedeProv = items.TipoIDSedeProv;
                    itemmiprestrue.NoIDSedeProv = items.NoIDSedeProv;
                    itemmiprestrue.CodSedeProv = items.CodSedeProv;
                    itemmiprestrue.CodSerTecAEntregar = items.CodSerTecAEntregar;
                    itemmiprestrue.CantTotAEntregar = items.CantTotAEntregar;
                
                    mipretrue.push(itemmiprestrue);
             
                 }
               
                

                
            });
            
             Swal.fire({
                    icon: "info",
                    title: 'Espere por favor !',
                    html: 'Programando con la API del ministerio', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),
        
          $.ajax({
           url:"{{route('programar')}}",
           method: 'post',
           data:{data:mipretrue,
            "_token": $("meta[name='csrf-token']").attr("content")
           },
           //dataType:"json",
           success:function(data){
            if(data.success == 'ya'){
                                for (var i = 0; i < data.result.length; i++) {
                        var parsedResult = JSON.parse(data.result[i]);
                
                        
                
                        // Mostrar advertencias específicas
                        if (parsedResult.Errors && Array.isArray(parsedResult.Errors)) {
                            parsedResult.Errors.forEach(function(error) {
                                toastr.warning('⚠️ ' + error);
                            });
                        }
                    }
            
            }else if(data.success == 'ok'){
                
                 for (var i = 0; i< data.result.length; i++) {

        var currentResult = data.result[i];

        // Verificar si el resultado es un string antes de aplicar JSON.parse
        if (typeof currentResult === "string") {
            try {
                currentResult = JSON.parse(currentResult);
            } catch (e) {
                // Si no es un JSON válido, podría ser un mensaje de error en string plano
                toastr.error("Error en la respuesta: " + currentResult + '!');
                continue; // Saltar al siguiente resultado
            }
        }

        $.each(currentResult, function(a, item) {
            console.log(item);
            if(Array.isArray(item) == true){
                toastr.warning(item + '!');
            } else {
                // Ahora dentro del 'else' (cuando item NO es un array)
                if (item && item.success === false) {
                    toastr.warning("Error al programar el ID: " + item.ID + ". " + (item.error || 'Error desconocido') + '!');
                } else if (item && item.IdProgramacion > 0) {
                    let respuesta = item.IdProgramacion;
                    let respuesta1 = item.ID; // Usamos item.ID (mayúscula) para coincidir con el backend
                    toastr.success("El ID: " + respuesta1 +"/n"+"Quedó programado con Id de programación: "+ respuesta + '!');
                } else if (typeof item === 'string') {
                    toastr.error("Error: " + item + '!');
                }
                // Puedes añadir más 'else if' aquí para otros formatos de error
            }
        });
    }
                   
                }else if(data.error == 'ok2'){
                
                                       var currentResult = data.result;
                
                        // Verificar si el resultado es un string antes de aplicar JSON.parse
                        if (typeof currentResult === "string") {
                            try {
                                currentResult = JSON.parse(currentResult);
                            } catch (e) {
                                // Si no es un JSON válido, podría ser un mensaje de error en string plano
                                toastr.error("Error en la respuesta: " + currentResult + '!');
                                
                            }
                        
                                    
                            }
                     Swal.close();
                    
                }},complete: function(){ 
                Swal.close(); // Cierra el modal de carga
                }


          });
           
        })

    
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
