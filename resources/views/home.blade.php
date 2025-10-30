@extends('layouts.admin')
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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- {{ __('You are logged in!') }} --}}
                    Estatus Body: {{$statusP ?? $statusF ?? ''}}
                    <form  action="{{route('home')}}" method="get">
                    @include('form-consulta')
                    <button type="submit" id="consultar" class="btn btn-success">Consultar</button><button type="button" id="enviar" class="btn btn-warning">Programar</button>
                    </form>
                    
                    <div class="card-body col-md-12 table-responsive p-2">
                        <table id="mipres" class="table text-nowrap table-bordered" style="width:100%">
                        
                    <thead>
                        <tr>
                        <th>Seleccione</th>
                        <th>ID:</th>
                        <th>ID Direccionamiento:</th>
                        <th>Prescripcion:</th>
                        <th>Cons.:</th>
                        <th>Tipo documento:</th>
                        <th>Documento:</th>
                        <th>Cums:</th>
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
                        @foreach ($medicamentos2 ?? '' as $item3)
                        @foreach ($item3 as $item)
                        <tr>
                            <td><input class="case" type="checkbox" title="Selecciona Orden" value="{{$item['ID'] ?? ''}}"></td>
                            <td> {{$item['ID'] ?? ''}}</td>
                            <td> {{$item['IDDireccionamiento'] ?? ''}}</td>
                            <td> {{$item['NoPrescripcion'] ?? ''}}</td>
                            <td> {{$item['ConTec'] ?? ''}}</td>
                            <td>{{$item['TipoIDPaciente'] ?? ''}}</td>
                            <td>{{$item['NoIDPaciente'] ?? ''}}</td>
                            <td>{{$item['CodSerTecAEntregar'] ?? ''}}</td>
                            @switch($item['CodSerTecAEntregar'])
                                @case(107)
                                <td>ASEO PERSONAL (GEL ANTIBACTERIAL, DESODORANTES, PROTECTORES LABIALES, TOALLAS DE PAPEL, TOALLAS HIGIENICAS, MAQUILLAJE, ENTRE OTRAS)</td> 
                                    @break

                                @case(108)
                                <td> BLOQUEADORES SOLARES</td>
                                    @break

                                 @case(109)
                                <td>CHAMPÚ Y LOCIONES CAPILARES</td> 
                                    @break

                                @case(112)
                                <td> CREMAS ANTIPAÑALITIS</td>
                                    @break
                                
                                @case(113)
                                <td>CREMAS CICATRIZANTES Y REPARADORES DÉRMICOS</td> 
                                   @break
    
                                @case(114)
                                <td> CREMAS Y LOCIONES HUMECTANTES, HIDRATANTES Y EMOLIENTES</td>
                                    @break
                            
                                @case(121)
                                <td>HIGIENE ORAL (CEPILLO, CREMA, SEDA DENTAL, ENJUAGUE)</td> 
                                    @break
    
                                @case(127)
                                <td> JABONES COSMÉTICOS, ANTIALÉRGICOS Y ANTIBACTERIALES</td>
                                     @break
                            
                                @case(130)
                                <td>LOCIONES REPELENTES DE USO PERSONAL Y DOMÉSTICO</td> 
                                     @break
        
                                @case(133)
                                <td>MEDICAMENTOS FITOTERAPEÚTICOS</td>
                                     @break

                                @case(134)
                                <td>MEDICAMENTOS HOMEOPÁTICOS</td> 
                                @break
        
                                @case(139)
                                <td>PAÑALES</td> 
                                 @break
            
                                @case(140)
                                <td> PAÑITOS HÚMEDOS</td>
                                @break
         
                                @case(146)
                                <td> SUPLEMENTOS DIETARIOS</td>
                                @break
                

                                @default
                                <td>Medicamento</td> 
                            @endswitch
                                                    
                            <td>{{$item['CantTotAEntregar'] ?? ''}}</td>
                            <td>{{$item['NoEntrega'] ?? ''}}</td>
                            <td>{{$item['TipoIDProv'] ?? ''}}</td>
                            <td>{{$item['NoIDProv'] ?? ''}}</td>
                            <td>{{$item['FecMaxEnt'] ?? ''}}</td>
                            <td>{{$item['FecDireccionamiento'] ?? ''}}</td>
                            <td>PROV007788</td>
                            <td>{{$item['NoIDEPS'] ?? ''}}</td>
                            <td>{{$item['CodEPS'] ?? ''}}</td>
                        </tr>
                        @endforeach
                        @endforeach
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

    $('#mipres').DataTable({
        
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
                    
                    }else if(data[16] == "805001157"){
                    $(row).css("background-color", "#87CEEB"); 
                    $(row).addClass("warning"); 
                    }
        
                   }
    });

//Funcion de envio de datos

    $(function(){

    //     Swal.fire({
    //     title: "¿Estás seguro?",
    //     text: "Estás por programar prescripciones",
    //     icon: "success",
    //     showCancelButton: true,
    //     showCloseButton: true,
    //     confirmButtonText: 'Aceptar',
    //     }).then((result)=>{
    //    if(result.value){      
        
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
         /* Swal.fire({
                icon: "info",
                title: 'Espere por favor !',
                html: 'Realizando la programación..',// add html attribute if you want or remove
                showConfirmButton: false,
                allowOutsideClick: false,
                willOpen: () => {
                    Swal.showLoading()
                },
            }), */   
          $.ajax({
            beforeSend: function(){ 
            $('.loader').css("visibility", "visible"); },
           url:"{{route('programar')}}",
           method: 'post',
           data:{data:mipretrue,
            "_token": $("meta[name='csrf-token']").attr("content")
           },
           //dataType:"json",
           success:function(data){
            if(data.success == 'ya'){
             for (var i = 0; i< data.result.length; i++) {
                $.each(JSON.parse(data.result[i]), function(a, items) {

                    toastr.warning('¡ '+items+ ' !');
                    // Swal.fire(
                    //     {
                    //       icon: 'warning',
                    //       title: items,
                    //       showConfirmButton: true,
                    //       //timer: 1500
                    //     }
                    //   )

                });
                }
            //$('#mipres').DataTable().destroy(); 
            }else if(data.success == 'ok'){
                
                for (var i = 0; i< data.result.length; i++) {

                $.each(JSON.parse(data.result[i]), function(a, item) {
                    console.log(item);
                   if(Array.isArray(item) == true){
                    toastr.warning(item + '!');   
                   }else{
                       
                       if(item.IdProgramacion > 0){
                           
                           let respuesta = item.IdProgramacion;
                           let respuesta1 = item.Id;
                            toastr.success("El ID: " + respuesta1 +"/n"+"Quedo programado con Id de programación: "+ respuesta + '!');  
                           
                          
                           
                       }
                       
                      }
                    
                    
                   });

                      

                    }
                   
                } 
                    
            },complete: function(){ 
                $('.loader').css("visibility", "hidden");
                }


          });
           
        })

    //    }
    //    });
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
