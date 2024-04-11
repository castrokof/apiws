@extends('layouts.app')

@section('titulo')
Dispensado Medcol Limonar
@endsection
@section("styles")

<link href="{{asset("assets/lte/plugins/sweetalert2/sweetalert2.min.css")}}" rel="stylesheet" type="text/css" />
{{-- <link href="{{asset("assets/lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css")}}" rel="stylesheet" type="text/css" /> --}}
<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/lte/plugins/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css" />


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/botones.css")}}" rel="stylesheet" type="text/css" />


@endsection


@section('scripts')


<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="loaders"><img src="{{asset("assets/lte/dist/img/loader6.gif")}}" class="" /> </div>


@include('menu.Medcol3.form.dispensado.forminformedispensado')
@include('menu.Medcol3.tabs.tabsIndexDispensado')

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


        $("#selectall").on('click', function() {
            $(".case").prop("checked", this.checked);
        });

        fill_datatable1_resumen();

        function fill_datatable1_resumen() {
            $("#detalle").empty();
            $("#detalle1").empty();
            $("#detalle2").empty();

            $.ajax({
                url: "{{ route('medcol3.informedis') }}",
                dataType: "json",
                success: function(data) {
                    const {
                        dispensado,
                        revisado,
                        anulado
                    } = data;

                    $("#detalle").append(`
                <div class="small-box shadow-lg l-bg-blue-dark">
                  <div class="inner">
                    <h5>PENDIENTES X REVISAR</h5>
                    <p><h5>${dispensado ?? 0}</h5></p>
                  </div>
                  <a class="informependientes" id="informependientesclic" href="#">
                    <div class="icon">
                      <i class="fas fa-notes-medical informependientes"></i>
                    </div>
                  </a>
                </div>
              `);

                    $("#detalle1").append(`
                <div class="small-box shadow-lg l-bg-orange-dark">
                  <div class="inner">
                    <h5>REVISADAS</h5>
                    <p><h5>${revisado ?? 0}</h5></p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-briefcase-medical"></i>
                  </div>
                </div>
              `);

                    $("#detalle2").append(`
                <div class="small-box shadow-lg l-bg-red-dark">
                  <div class="inner">
                    <h5>ANULADAS</h5>
                    <p><h5>${anulado ?? 0}</h5></p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-ban"></i>
                  </div>
                </div>
              `);
                }
            });
        }



        var fechaini;
        var fechafin;

        // Función para llenar la tabla al cargar la página
        fill_datatable_tabla();

        // Callback para filtrar los datos de la tabla y detalle
        $('#buscar').click(function() {

            fechaini = $('#fechaini').val();
            fechafin = $('#fechafin').val();
            //historia = $('#historia').val();


            if (fechaini != '' && fechafin != '') {

                $('#dispensados').DataTable().destroy();

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
            //$('#historia').val('');

            $('#dispensados').DataTable().destroy();
            $("#revisados").DataTable().destroy();
            $("#anulados").DataTable().destroy();
            fill_datatable_tabla();
        });

        function fill_datatable_tabla(fechaini = '', fechafin = '') {


            $(function() {
                // Se llama a la función correspondiente al tab activo al cargar la página
                var activeTab = $(".nav-tabs .active");
                var activeTabId = activeTab.attr("id");
                callFunction(activeTabId, fechaini, fechafin);

                // Se llama a la función correspondiente al tab seleccionado al cambiar de tab
                $('a[data-toggle="pill"]').on("shown.bs.tab", function(e) {
                    var target = $(e.target);
                    var targetId = target.attr("id");
                    callFunction(targetId, fechaini, fechafin);
                });

                function callFunction(tabId) {
                    if (tabId === "custom-tabs-one-datos-de-dispensado-tab") {
                        // Llamar a la función correspondiente al tab "Pendientes"
                        /* console.log("Pendientes"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#dispensados")) {
                            $("#dispensados").DataTable().destroy();
                            /* $(".diagnos").select2({
                                 language: "es",
                                 theme: "bootstrap4"
                                 }).trigger('change');
                                 
                                 $(".ipsss").select2({
                                 language: "es",
                                 theme: "bootstrap4"
                                 }).trigger('change');*/

                        }
                        // Funcion para pintar con data table la pestaña Lista de dispensados
                        var datatable =
                            $('#dispensados').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [28, "desc"]
                                ],


                                ajax: {
                                    url: "{{route('medcol3.dispensado1')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [{
                                        data: 'idusuario'
                                    },
                                    {
                                        data: 'tipo'
                                    },
                                    {
                                        data: 'facturad'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'tipodocument'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'expediente'
                                    },
                                    {
                                        data: 'consecutivo'
                                    },
                                    {
                                        data: 'cums_rips'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'tipo_medicamento'
                                    },
                                    {
                                        data: 'nombre_generico'
                                    },
                                    {
                                        data: 'atc'
                                    },
                                    {
                                        data: 'forma'
                                    },
                                    {
                                        data: 'concentracion'
                                    },
                                    {
                                        data: 'unidad_medicamento'
                                    },
                                    {
                                        data: 'numero_unidades'
                                    },
                                    {
                                        data: 'regimen'
                                    },
                                    {
                                        data: 'paciente'
                                    },
                                    {
                                        data: 'primer_apellido'
                                    },
                                    {
                                        data: 'segundo_apellido'
                                    },
                                    {
                                        data: 'primer_nombre'
                                    },
                                    {
                                        data: 'segundo_nombre'
                                    },
                                    {
                                        data: 'cuota_moderadora'
                                    },

                                    {
                                        data: 'copago1',
                                        orderable: false
                                    }, //25
                                    {
                                        data: 'numero_entrega1',
                                        orderable: false
                                    }, //26
                                    {
                                        data: 'fecha_orden',
                                        orderable: false
                                    }, //27

                                    {
                                        data: 'fecha_suministro'
                                    },

                                    {
                                        data: 'diagnostico',
                                        orderable: false
                                    }, //29

                                    {
                                        data: 'ips',
                                        render: function(data, type, full, meta) {
                                            return '<select class="ipsss form-control select2bs4" style="width: 100%;" required data-id="' + full.id + '"></select>';
                                        }

                                    }, //30

                                    {
                                        data: 'autorizacion1',
                                        orderable: false
                                    }, //31

                                    {
                                        data: 'mipres1',
                                        orderable: false
                                    }, //32

                                    {
                                        data: 'reporte_entrega1',
                                        orderable: false
                                    }, //33

                                    {
                                        data: 'id_medico1',
                                        orderable: false
                                    }, //34

                                    {
                                        data: 'medico1',
                                        orderable: false
                                    }, //35


                                    {
                                        data: 'precio_unitario'
                                    },
                                    {
                                        data: 'valor_total'
                                    },
                                    {
                                        data: 'estado'
                                    }, //38
                                    {
                                        data: 'centroprod'
                                    },
                                    {
                                        data: 'drogueria'
                                    },
                                    {
                                        data: 'user_id'
                                    }, //41
                                    {
                                        data: 'cajero'
                                    },

                                    {
                                        data: 'action',
                                        orderable: false
                                    }

                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Informe Facturas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Informe Facturas",
                                        className: "btn  btn-outline-success btn-sm",
                                        customize: function(xlsx) {
                                            var sheet = xlsx.xl.worksheets['Sheet1'];
                                            $('row c[r^="AG"]', sheet).attr('t', 's');
                                        }
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

                                drawCallback: (settings) => {

                                    $('.diagnos').select2({
                                        language: "es",
                                        theme: "bootstrap4",
                                        placeholder: 'Buscar cie10....',
                                        allowClear: true,
                                        ajax: {
                                            url: "{{ route('selectcie10') }}",
                                            dataType: 'json',
                                            delay: 250,
                                            data: function(params) {
                                                return {
                                                    q: params.term,

                                                };
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: $.map(data.array[0], function(datas) {

                                                        return {

                                                            text: datas.codigo + "=>" + datas.descripcion,
                                                            id: datas.codigo

                                                        }
                                                    })
                                                };
                                            },
                                            cache: true
                                        }
                                    }).trigger('change');

                                    $('.ipsss').select2({
                                        language: "es",
                                        theme: "bootstrap4",
                                        placeholder: 'Buscar Ips....',
                                        allowClear: true,
                                        ajax: {
                                            url: "{{ route('selectlist') }}",
                                            dataType: 'json',
                                            delay: 250,
                                            data: function(params) {
                                                return {
                                                    q: params.term,
                                                    id: 1

                                                };
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: $.map(data.array[0], function(datas) {

                                                        return {

                                                            text: datas.slug + "=>" + datas.descripcion,
                                                            id: datas.id

                                                        }
                                                    })
                                                };
                                            },
                                            cache: true
                                        }
                                    }).trigger('change');

                                }
                            });


                    } else if (tabId === "custom-tabs-one-datos-disrevisado-tab") {
                        // Llamar a la función correspondiente al tab "Revisados"
                        /* console.log("Pagos Parciales"); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#revisados")) {
                            $("#revisados").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista Revisados
                        var datatable1 =
                            $('#revisados').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [28, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol3.disrevisado')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [

                                    {
                                        data: 'idusuario'
                                    },
                                    {
                                        data: 'tipo'
                                    },
                                    {
                                        data: 'facturad'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'tipodocument'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'expediente'
                                    },
                                    {
                                        data: 'consecutivo'
                                    },
                                    {
                                        data: 'cums_rips'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'tipo_medicamento'
                                    },
                                    {
                                        data: 'nombre_generico'
                                    },
                                    {
                                        data: 'atc'
                                    },
                                    {
                                        data: 'forma'
                                    },
                                    {
                                        data: 'concentracion'
                                    },
                                    {
                                        data: 'unidad_medicamento'
                                    },
                                    {
                                        data: 'numero_unidades'
                                    },
                                    {
                                        data: 'regimen'
                                    },
                                    {
                                        data: 'paciente'
                                    },
                                    {
                                        data: 'primer_apellido'
                                    },
                                    {
                                        data: 'segundo_apellido'
                                    },
                                    {
                                        data: 'primer_nombre'
                                    },
                                    {
                                        data: 'segundo_nombre'
                                    },
                                    {
                                        data: 'cuota_moderadora'
                                    },

                                    {
                                        data: 'copago',
                                        orderable: false
                                    }, //26
                                    {
                                        data: 'numero_entrega',
                                        orderable: false
                                    }, //27
                                    {
                                        data: 'fecha_ordenamiento',
                                        orderable: false
                                    }, //28

                                    {
                                        data: 'fecha_suministro'
                                    },

                                    {
                                        data: 'dx',
                                        orderable: false
                                    }, //30

                                    {
                                        //data: 'ips',
                                        data: 'ips_nombre',
                                        orderable: false
                                    },

                                    {
                                        data: 'autorizacion',
                                        orderable: false
                                    }, //31

                                    {
                                        data: 'mipres',
                                        orderable: false
                                    }, //32

                                    {
                                        data: 'reporte_entrega_nopbs',
                                        orderable: false
                                    }, //33

                                    {
                                        data: 'id_medico',
                                        orderable: false
                                    }, //34

                                    {
                                        data: 'medico',
                                        orderable: false
                                    }, //35


                                    {
                                        data: 'precio_unitario'
                                    },
                                    {
                                        data: 'valor_total'
                                    },
                                    {
                                        data: 'estado'
                                    }, //38
                                    {
                                        data: 'centroprod'
                                    },
                                    {
                                        data: 'drogueria'
                                    },
                                    {
                                        data: 'user_id'
                                    }, //41
                                    {
                                        data: 'cajero'
                                    },

                                    {
                                        data: 'action',
                                        orderable: false
                                    }

                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Informe Facturas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Informe Facturas",
                                        className: "btn  btn-outline-success btn-sm",
                                        customize: function(xlsx) {
                                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                            $('row c[r^="AG"]', sheet).each(function() {
                                                $(this).attr('t', 's');
                                            });
                                        }

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

                    } else if (tabId === "custom-tabs-one-datos-disanulado-tab") {
                        // Llamar a la función correspondiente al tab "Anulados"
                        /* console.log(" "); */

                        // Destruir la tabla existente
                        if ($.fn.DataTable.isDataTable("#anulados")) {
                            $("#anulados").DataTable().destroy();
                        }
                        // Funcion para pintar con data table la pestaña Lista Anulados
                        var datatable1 =
                            $('#anulados').DataTable({
                                language: idioma_espanol,
                                processing: true,
                                lengthMenu: [
                                    [25, 50, 100, 500, -1],
                                    [25, 50, 100, 500, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [28, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol3.disanulado')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [

                                    {
                                        data: 'idusuario'
                                    },
                                    {
                                        data: 'tipo'
                                    },
                                    {
                                        data: 'facturad'
                                    },
                                    {
                                        data: 'factura'
                                    },
                                    {
                                        data: 'tipodocument'
                                    },
                                    {
                                        data: 'historia'
                                    },
                                    {
                                        data: 'cums'
                                    },
                                    {
                                        data: 'expediente'
                                    },
                                    {
                                        data: 'consecutivo'
                                    },
                                    {
                                        data: 'cums_rips'
                                    },
                                    {
                                        data: 'codigo'
                                    },
                                    {
                                        data: 'tipo_medicamento'
                                    },
                                    {
                                        data: 'nombre_generico'
                                    },
                                    {
                                        data: 'atc'
                                    },
                                    {
                                        data: 'forma'
                                    },
                                    {
                                        data: 'concentracion'
                                    },
                                    {
                                        data: 'unidad_medicamento'
                                    },
                                    {
                                        data: 'numero_unidades'
                                    },
                                    {
                                        data: 'regimen'
                                    },
                                    {
                                        data: 'paciente'
                                    },
                                    {
                                        data: 'primer_apellido'
                                    },
                                    {
                                        data: 'segundo_apellido'
                                    },
                                    {
                                        data: 'primer_nombre'
                                    },
                                    {
                                        data: 'segundo_nombre'
                                    },
                                    {
                                        data: 'cuota_moderadora'
                                    },

                                    {
                                        data: 'copago',
                                        orderable: false
                                    }, //26
                                    {
                                        data: 'numero_entrega',
                                        orderable: false
                                    }, //27
                                    {
                                        data: 'fecha_ordenamiento',
                                        orderable: false
                                    }, //28

                                    {
                                        data: 'fecha_suministro'
                                    },

                                    {
                                        data: 'dx',
                                        orderable: false
                                    }, //30

                                    {
                                        //data: 'ips',
                                        data: 'ips_nombre',
                                        orderable: false
                                    },

                                    {
                                        data: 'autorizacion',
                                        orderable: false
                                    }, //31

                                    {
                                        data: 'mipres',
                                        orderable: false
                                    }, //32

                                    {
                                        data: 'reporte_entrega_nopbs',
                                        orderable: false
                                    }, //33

                                    {
                                        data: 'id_medico',
                                        orderable: false
                                    }, //34

                                    {
                                        data: 'medico',
                                        orderable: false
                                    }, //35


                                    {
                                        data: 'precio_unitario'
                                    },
                                    {
                                        data: 'valor_total'
                                    },
                                    {
                                        data: 'estado'
                                    }, //38
                                    {
                                        data: 'centroprod'
                                    },
                                    {
                                        data: 'drogueria'
                                    },
                                    {
                                        data: 'user_id'
                                    }, //41
                                    {
                                        data: 'cajero'
                                    },

                                    {
                                        data: 'action',
                                        orderable: false
                                    }

                                ],

                                //Botones----------------------------------------------------------------------

                                "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                                buttons: [{

                                        extend: 'copyHtml5',
                                        titleAttr: 'Copiar Registros',
                                        title: "Informe Facturas",
                                        className: "btn  btn-outline-primary btn-sm"


                                    },
                                    {

                                        extend: 'excelHtml5',
                                        titleAttr: 'Exportar Excel',
                                        title: "Informe Facturas",
                                        className: "btn  btn-outline-success btn-sm",
                                        customize: function(xlsx) {
                                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                            $('row c[r^="AG"]', sheet).each(function() {
                                                $(this).attr('t', 's');
                                            });
                                        }

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
                }
            });
            //Poner la llave para cerrar el fill_datatable_tabla 
        }

        // Función que envían los datos de la factura al controlador para cambiar el estado
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
                type: "warning",
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
                                $('#form-general1')[0].reset();
                                $('#modal-edit-pendientes').modal('hide');
                                /* limpiarModal(); */
                                $('#pendientes').DataTable().ajax.reload();
                                // $('#tobservaciones').DataTable().ajax.reload();
                                // $('#porentregar').DataTable().ajax.reload();
                                // $('#entregados').DataTable().ajax.reload();
                                // $('#tanulados').DataTable().ajax.reload();
                                // $('#tdesabastecidos').DataTable().ajax.reload();
                                Swal.fire({
                                    type: 'success',
                                    title: 'Cuenta por pagar creada correctamente',
                                    showConfirmButton: false,
                                    timer: 1500

                                })


                            } else if (data.success == 'ok1') {
                                $('#form-general1')[0].reset();
                                $('#modal-edit-pendientes').modal('hide');
                                $('#pendientes').DataTable().ajax.reload();
                                // $('#tobservaciones').DataTable().ajax.reload();
                                // $('#porentregar').DataTable().ajax.reload();
                                // $('#entregados').DataTable().ajax.reload();
                                // $('#tanulados').DataTable().ajax.reload();
                                // $('#tdesabastecidos').DataTable().ajax.reload();
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


        // Función que envia el id al controlador y cambia el estado del registro
        $(document).on('click', '#syncapidis', function() {

            const text = 'De Medcol PCE-Huerfanas-Biologicos';

            Swal.fire({
                title: "¿Estás por sincronizar lo dispensado?",
                text: text,
                type: "info",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

                    ajaxRequestSyncDispensados();

                }
            });
        });

        function ajaxRequestSyncDispensados() {
            $.ajax({
                beforeSend: function() {
                    $('.loaders').css("visibility", "visible");
                },
                url: "{{route('medcol3.dispensadosyncapi')}}",
                type: 'GET',
                success: function(data) {
                    $('#dispensados').DataTable().ajax.reload();


                    $.each(data, function(i, item) {
                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });
                    // fill_datatable1_resumen();

                },
                complete: function() {
                    $('.loaders').css("visibility", "hidden");
                }
            });
        }

        //Funcion para sincronizar las facturas anuladas y actualizar el estado
        $(document).on('click', '#synanulados', function() {

            const text = 'De Medcol PCE-Huerfanas-Biologicos';

            Swal.fire({
                title: "¿Estás por sincronizar los anulados?",
                text: text,
                type: "info",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

                    ajaxRequestSyncAnulados();

                }
            });
        });

        function ajaxRequestSyncAnulados() {
            $.ajax({
                beforeSend: function() {
                    $('.loaders').css("visibility", "visible");
                },
                url: "{{route('medcol3.anuladosapi')}}",
                type: 'GET',
                success: function(data) {
                    $('#dispensados').DataTable().ajax.reload();


                    $.each(data, function(i, item) {
                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });
                    // fill_datatable1_resumen();

                },
                complete: function() {
                    $('.loaders').css("visibility", "hidden");
                }
            });
        }





        // Función que envia el id al controlador y cambia el estado del registro
        $(document).on('click', '#syncdis', function() {

            var dispensado = [];
            var dispensadotrue1 = [];

            // Utiliza 'tr' en lugar de 'tbody tr' para recorrer solo la fila específica
            $("tbody tr").each(function(el) {

                var itemdispensado = {};

                var tds = $(this).find("td");
                itemdispensado.checked = tds.find(":checkbox").prop("checked");
                itemdispensado.id = tds.find(":checkbox:checked").attr('id');
                itemdispensado.copago1 = tds.filter(":eq(25)").find('input').val();
                itemdispensado.numero_entrega1 = tds.filter(":eq(26)").find('input').val();
                itemdispensado.fecha_orden = tds.filter(":eq(27)").find('input').val();
                itemdispensado.diagnostico = tds.filter(":eq(29)").find('select').val();
                itemdispensado.ips = tds.filter(":eq(30)").find('select').val();
                itemdispensado.autorizacion1 = tds.filter(":eq(31)").find('input').val();
                itemdispensado.mipres1 = tds.filter(":eq(32)").find('input').val();
                itemdispensado.reporte_entrega1 = tds.filter(":eq(33)").find('input').val();
                itemdispensado.id_medico1 = tds.filter(":eq(34)").find('input').val();
                itemdispensado.medico1 = tds.filter(":eq(35)").find('input').val();
                itemdispensado.estado = "REVISADO";
                itemdispensado.user_id = "{{ Auth::user()->id }}";

                // Ingreso cada array en la variable itemdispensado
                dispensado.push(itemdispensado);

            });


            $.each(dispensado, function(i, items) {

                var dispensadotrue = {};

                if (items.checked == true) {

                    console.log("entra acá");
                    dispensadotrue.ID = items.id;
                    dispensadotrue.copago1 = items.copago1;
                    dispensadotrue.numero_entrega1 = items.numero_entrega1;
                    dispensadotrue.fecha_orden = items.fecha_orden;
                    dispensadotrue.diagnostico = items.diagnostico;
                    dispensadotrue.ips = items.ips;
                    dispensadotrue.autorizacion1 = items.autorizacion1;
                    dispensadotrue.mipres1 = items.mipres1;
                    dispensadotrue.reporte_entrega1 = items.reporte_entrega1;
                    dispensadotrue.id_medico1 = items.id_medico1;
                    dispensadotrue.medico1 = items.medico1;
                    dispensadotrue.estado = items.estado;
                    dispensadotrue.user_id = items.user_id;

                    dispensadotrue1.push(dispensadotrue);

                }




            });

            console.log(dispensadotrue1);

            $.each(dispensadotrue1, function(i, items) {


                console.log("entra acá1");
                console.log(items.id);
                console.log(items.fecha_orden);
                console.log(items.diagnostico);
                console.log(items.ips);
                console.log(items.autorizacion1);
                console.log(items.mipres1);
                console.log(items.reporte_entrega1);
                console.log(items.id_medico1);
                console.log(items.medico1);
                console.log(items.estado);
                console.log(items.user_id);

                /*if (items.fecha_orden > ){
                    
                    Swal.fire({
                        icon: 'warning',
                        title: "La fecha de Orenamiento no puede ser mayor a la de Dispensación",
                        showConfirmButton: true,
                        timer: 1500
                    })
                    
                } */

                if (items.numero_entrega1 == '' || items.fecha_orden == '' || items.diagnostico == '' || items.ips == '') {

                    Swal.fire({
                        icon: 'warning',
                        title: "Los campos numero de entrega, fecha orden, IPS, diagnostico no pueden estar vacios y La fecha de Orenamiento no puede ser mayor a la de Dispensación",
                        showConfirmButton: true,
                        timer: 1500
                    })


                } else if (items.autorizacion1 == '') {


                    enviardatos(dispensadotrue1);


                } else if (items.autorizacion1 != '' && items.mipre1 != '' && items.reporte_entrega1 != '') {

                    enviardatos(dispensadotrue1);

                } else {

                    Swal.fire({
                        icon: 'warning',
                        title: "Los campos numero de autorización, Mipres y reporte de entrega no pueden estar vacios",
                        showConfirmButton: true,
                        timer: 1500
                    })

                }

            });




        });


        function enviardatos(dispensadotrue1) {


            Swal.fire({
                    icon: "info",
                    title: 'Espere por favor !',
                    html: 'Realizando la revision..', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),
                $.ajax({

                    url: "{{route('add_dispensacion')}}",
                    method: 'post',
                    data: {
                        data: dispensadotrue1,
                        "_token": $("meta[name='csrf-token']").attr("content")
                    },

                    success: function(data) {
                        if (data.success == 'ya') {

                            Swal.fire({
                                type: 'warning',
                                title: "Factura no adicionada",
                                showConfirmButton: true,
                                timer: 1500
                            })

                            $("#dispensados").DataTable().ajax.reload();

                        } else if (data.success == 'ok') {

                            Swal.fire({
                                type: 'success',
                                title: "Factura adicionada correctamente",
                                showConfirmButton: true,
                                timer: 1500
                            })


                            //console.log(index);

                            //$("#dispensados").DataTable().row(index).remove().draw(false);

                            $("#dispensados").DataTable().ajax.reload();

                        }

                    },
                    error: function(xhr) {
                        // Manejar errores de validación de la solicitud AJAX
                        var errorMessage = "Revise los siguientes errores:<br>";
                        var errorMessage2 = "";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(fieldName, fieldErrors) {

                                errorMessage2 += "<strong>" + fieldName + ":</strong><br>";
                                $.each(fieldErrors, function(index, error) {
                                    errorMessage2 += "- " + error + "<br>";
                                });
                            });
                        } else {
                            errorMessage += "Error en la solicitud.";
                        }
                        Swal.fire({
                            type: 'error',
                            title: errorMessage,
                            showConfirmButton: true,
                            html: errorMessage2
                        });
                    }


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