@extends('layouts.app')

@section('titulo')
Dispensado Medcol Jamundi
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

<!-- Spinner Backdrop -->
<style>
    .spinner-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

</style>

@endsection


@section('scripts')

<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('content')
<div class="loaders"><img src="{{asset("assets/lte/dist/img/loader6.gif")}}" class="" /> </div>


@include('menu.Medcol6.form.dispensado.forminformedispensado')
@include('menu.Medcol6.tabs.tabsIndexDispensado')
@include('menu.Medcol6.modal.modalGestionMultiple')
@include('menu.Medcol6.modal.modalGenerarInforme')

@include('menu.Medcol6.modal.modalEditDispensados')

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

            const nombresExcluidos = ['FSAU', 'FSIO', 'FSOS', 'ENMP', 'EVSO']; // Nombres a excluir
            // Mapeo de centroprod a nombres más amigables
            const centroprodMap = {
                "FRJA": "Comfe Jamundi",
                "DOMI": "Domiciliario",
                "PAC": "Comfe PCE",
                "EHU1": "Comfe Huerfanas",
                "BIO1": "Comfe BIOLOGICOS",
                "SM01": "Comfe SALUD MENTAL",
                "DLR1": "Comfe DOLOR",
                "EVEN": "Comfe EVENTO",
                "EM01": "Comfe EMCALI",
                "EVSM": "Comfe EVENTO SM",
                "BPDT": "Comfe BOLSA",
                "DPA1": "Comfe PALIATIVOS",
                "INY": "Comfe INYECTABLES"
            };


            $.ajax({
                url: "{{ route('medcol6.informedis') }}",
                dataType: "json",
                success: function(data) {
                    const {
                        dispensado,
                        revisado,
                        anulado
                    } = data;

                    // Crear el contenido para dispensado dentro de una tarjeta
                    let dispensadoHTML = "<ul>";
                    dispensado.forEach(item => {
                        const nombreCentroprod = centroprodMap[item.centroprod] || item.centroprod; // Validar si existe en el mapeo

                        // Excluir los nombres no deseados
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            dispensadoHTML += `
                        <li>${nombreCentroprod}: ${item.total}</li>
                    `;
                        }


                    });
                    dispensadoHTML += "</ul>";

                    // Crear el contenido para revisado dentro de una tarjeta
                    let revisadoHTML = "<ul>";
                    revisado.forEach(item => {
                        const nombreCentroprod = centroprodMap[item.centroprod] || item.centroprod;

                        // Excluir los nombres no deseados
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            revisadoHTML += `
                        <li>${nombreCentroprod}: ${item.total}</li>
                    `;
                        }

                    });
                    revisadoHTML += "</ul>";

                    // Crear el contenido para anulado dentro de una tarjeta
                    let anuladoHTML = "<ul>";
                    anulado.forEach(item => {
                        const nombreCentroprod = centroprodMap[item.centroprod] || item.centroprod;



                        // Excluir los nombres no deseados
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            anuladoHTML += `
                        <li>${nombreCentroprod}: ${item.total}</li>
                    `;
                        }

                    });
                    anuladoHTML += "</ul>";

                    // Mostrar el contenido dentro de las respectivas tarjetas
                    $("#detalle").append(`
                <div class="small-box shadow-lg l-bg-blue-dark">
                  <div class="inner">
                    <h5>PENDIENTES X REVISAR</h5>
                    <p>${dispensadoHTML}</p>
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
                    <p>${revisadoHTML}</p>
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
                    <p>${anuladoHTML}</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-ban"></i>
                  </div>
                </div>
            `);
                }
            });
        }

        $("#ejecutar_informe").click(function() {
            fechaInicio = $("#modal_fechaini").val();
            fechaFin = $("#modal_fechafin").val();
            contrato = $("#modal_contrato").val();

            if (!fechaInicio || !fechaFin) {
                alert("Por favor seleccione ambas fechas.");
                return;
            }

            // Llamar a la función con las fechas seleccionadas
            generar_informe(fechaInicio, fechaFin);
            cargarTablaForgif(fechaInicio, fechaFin, contrato);
            //$("#modal_generar_informe").modal("hide");
        });

        function generar_informe(fechaInicio, fechaFin) {
            $("#detalle_informe").empty();
            $("#detalle_informe1").empty();
            $("#detalle_informe2").empty();

            const nombresExcluidos = ['FSAU', 'FSIO', 'FSOS', 'ENMP', 'EVSO'];
            const servicioMap = {
                "FRJA": "Jamundí",
                "DOMI": "Domicilios",
                "PAC": "PCE",
                "EHU1": "Huérfanas",
                "BIO1": "Biológicos",
                "SM01": "Salud Mental",
                "DLR1": "Dolor",
                "EVEN": "Evento",
                "EM01": "Emcali",
                "EVSM": "Evento SM",
                "BPDT": "Bolsa",
                "DPA1": "Paliativos",
                "INY": "Inyectables"
            };

            $.ajax({
                url: "{{ route('medcol6.gestionsdis') }}",
                data: {
                    fechaini: fechaInicio,
                    fechafin: fechaFin
                },
                dataType: "json",
                success: function(data) {
                    $("#resultado_informe").show();
                    const {
                        dispensado,
                        revisado,
                        anulado
                    } = data;

                    let htmlDispensado = "<ul>";
                    dispensado.forEach(item => {
                        const nombreCentroprod = servicioMap[item.centroprod] || item.centroprod;
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            htmlDispensado += `<li>${nombreCentroprod}: ${item.total}</li>`;
                        }
                    });
                    htmlDispensado += "</ul>";

                    let htmlRevisado = "<ul>";
                    revisado.forEach(item => {
                        const nombreCentroprod = servicioMap[item.centroprod] || item.centroprod;
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            htmlRevisado += `<li>${nombreCentroprod}: ${item.total}</li>`;
                        }
                    });
                    htmlRevisado += "</ul>";

                    let htmlAnulado = "<ul>";
                    anulado.forEach(item => {
                        const nombreCentroprod = servicioMap[item.centroprod] || item.centroprod;
                        if (!nombresExcluidos.includes(nombreCentroprod)) {
                            htmlAnulado += `<li>${nombreCentroprod}: ${item.total}</li>`;
                        }
                    });
                    htmlAnulado += "</ul>";

                    $("#detalle_informe").append(`
                        <div class="small-box shadow-lg l-bg-blue-dark">
                            <div class="inner">
                                <!--<h5>PENDIENTES X REVISAR</h5>-->
                                <h5>CONTRATOS</h5>
                                <p>${htmlDispensado}</p>
                            </div>
                            <a class="informependientes" id="informependientesclic" href="#">
                                <div class="icon">
                                    <i class="fas fa-notes-medical informependientes"></i>
                                </div>
                            </a>
                        </div>
                    `);

                    $("#detalle_informe1").append(`
                        <div class="small-box shadow-lg l-bg-orange-dark">
                            <div class="inner">
                                <!--<h5>REVISADAS</h5>-->
                                <h5>CONTRATOS</h5>
                                <p>${htmlRevisado}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-briefcase-medical"></i>
                            </div>
                        </div>
                    `);

                    $("#detalle_informe2").append(`
                        <div class="small-box shadow-lg l-bg-red-dark">
                            <div class="inner">
                                <!--<h5>ANULADAS</h5>-->
                                <p>${htmlAnulado}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-ban"></i>
                            </div>
                        </div>
                    `);
                }
            });
        }

        // Función para generar el informe basado en las fechas seleccionadas
        function cargarTablaForgif(fechaini, fechafin) {
            // Destruir DataTable existente si ya está inicializado
            if ($.fn.DataTable.isDataTable('#tablaForgif')) {
                $('#tablaForgif').DataTable().destroy();
            }

            // Inicializar DataTable con los nuevos datos
            $('#tablaForgif').DataTable({
                language: idioma_espanol,
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "Mostrar Todo"]
                ],
                ajax: {
                    url: "{{ route('medcol6.forgif') }}",
                    type: 'POST',
                    data: {
                        fechaini: fechaini,
                        fechafin: fechafin,
                        contrato: contrato,
                        //contrato: $("#contrato").val(), // Puedes obtener otros filtros aquí
                        //cobertura: $("#cobertura").val(),
                        _token: "{{ csrf_token() }}"
                    }
                },
                columns: [{
                        data: 'nit_prestador'
                    },
                    {
                        data: 'razon_social_prestador'
                    },
                    {
                        data: 'codigo_generico_eps'
                    },
                    {
                        data: 'expediente'
                    },
                    {
                        data: 'codigo'
                    },
                    {
                        data: 'nombre_generico'
                    },
                    {
                        data: 'nombre_comercial'
                    },
                    {
                        data: 'unidad_medicamento'
                    },
                    {
                        data: 'precio_unitario'
                    },
                    {
                        data: 'cums'
                    },
                    {
                        data: 'ambito'
                    },
                    {
                        data: 'registro_sanitario_invima'
                    },
                    {
                        data: 'opcion'
                    },
                    {
                        data: 'cobertura'
                    },
                    {
                        data: 'regulado'
                    },
                    {
                        data: 'categoria_medicamento'
                    },
                    {
                        data: 'forma'
                    },
                    {
                        data: 'tarifa_tope_regulado'
                    }
                ],
                dom: '<"row"<"col-md-4"l><"col-md-4"f><"col-md-4"B>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                buttons: [{
                        extend: 'copyHtml5',
                        titleAttr: 'Copiar Registros',
                        className: "btn btn-outline-primary btn-sm"
                    },
                    {
                        extend: 'excelHtml5',
                        titleAttr: 'Exportar Excel',
                        className: "btn btn-outline-success btn-sm"
                    },
                    {
                        extend: 'csvHtml5',
                        titleAttr: 'Exportar CSV',
                        className: "btn btn-outline-warning btn-sm"
                    },
                    {
                        extend: 'pdfHtml5',
                        titleAttr: 'Exportar PDF',
                        className: "btn btn-outline-secondary btn-sm"
                    }
                ]
            });
        }



        var fechaini;
        var fechafin;
        var contrato;
        var cobertura;

        // Función para llenar la tabla al cargar la página
        fill_datatable_tabla();

        // Callback para filtrar los datos de la tabla y detalle
        $('#buscar').click(function() {

            fechaini = $('#fechaini').val();
            fechafin = $('#fechafin').val();
            contrato = $('#contrato').val();
            cobertura = $('#cobertura').val();


            if (fechaini != '' && fechafin != '' || contrato != '' || cobertura != '') {

                $('#dispensados').DataTable().destroy();
                $("#revisados").DataTable().destroy();
                $("#anulados").DataTable().destroy();

                fill_datatable_tabla(fechaini, fechafin, contrato, cobertura);

            } else {

                Swal.fire({
                    title: 'Debes digitar fecha inicial y fecha final, la Droguería y Cobertura son opcionales',
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
            $('#contrato').val('');
            $('#cobertura').val('');

            $('#dispensados').DataTable().destroy();
            $("#revisados").DataTable().destroy();
            $("#anulados").DataTable().destroy();
            fill_datatable_tabla();
        });

        function fill_datatable_tabla(fechaini = '', fechafin = '', contrato = '', cobertura = '') {


            $(function() {
                // Se llama a la función correspondiente al tab activo al cargar la página
                var activeTab = $(".nav-tabs .active");
                var activeTabId = activeTab.attr("id");
                callFunction(activeTabId);

                // Se llama a la función correspondiente al tab seleccionado al cambiar de tab
                $('a[data-toggle="pill"]').on("shown.bs.tab", function(e) {
                    var target = $(e.target);
                    var targetId = target.attr("id");
                    callFunction(activeTabId);
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
                                    [25, 50, 100, 500, 5000, -1],
                                    [25, 50, 100, 500, 5000, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [28, "desc"]
                                ],


                                ajax: {
                                    url: "{{route('medcol6.dispensado1')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        contrato: contrato,
                                        cobertura: cobertura,
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
                                        data: 'fecha_suministro'
                                    },
                                    {
                                        data: 'id_medico'
                                    },
                                    {
                                        data: 'medico'
                                    },
                                    {
                                        data: 'especialidadmedico'
                                    },
                                    {
                                        data: 'mipres'
                                    },



                                    {
                                        data: 'autorizacion',
                                        orderable: false
                                    }, //31


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
                                    [25, 50, 100, 500, 5000, -1],
                                    [25, 50, 100, 500, 5000, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [28, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.disrevisado')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        contrato: contrato,
                                        cobertura: cobertura,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    method: 'POST'
                                },
                                columns: [

                                    /* {
                                        data: 'action', //56
                                        orderable: false
                                    },
                                    {
                                        data: 'id'
                                    }, */
                                    {
                                        data: 'idusuario' //1
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
                                        data: 'cums_rips' //10
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
                                        data: 'paciente' //20
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
                                        data: 'numero_orden',
                                        orderable: false
                                    },
                                    {
                                        data: 'numero_entrega',
                                        orderable: false
                                    },
                                    {
                                        data: 'num_total_entregas',
                                        orderable: false
                                    },
                                    {
                                        data: 'fecha_ordenamiento',
                                        orderable: false
                                    },

                                    {
                                        data: 'fecha_suministro'
                                    },

                                    {
                                        data: 'dx',
                                        orderable: false
                                    }, //31
                                    {
                                        //data: 'ips',
                                        data: 'nitips',
                                        orderable: false
                                    },

                                    {
                                        //data: 'ips',
                                        data: 'ips_nombre',
                                        orderable: false
                                    },

                                    {
                                        data: 'autorizacion',
                                        orderable: false
                                    }, //32

                                    {
                                        data: 'mipres',
                                        orderable: false
                                    }, //33

                                    {
                                        data: 'reporte_entrega_nopbs',
                                        orderable: false
                                    }, //34

                                    {
                                        data: 'id_medico',
                                        orderable: false
                                    },

                                    {
                                        data: 'numeroIdentificacion'
                                    }, //docuemnto de medico nuevo campo para SOS

                                    {
                                        data: 'medico',
                                        orderable: false
                                    }, //37

                                    {
                                        data: 'especialidadmedico'
                                    }, //Especialidad medico nuevo campo para SOS


                                    {
                                        data: 'precio_unitario'
                                    },

                                    {
                                        data: 'valor_total' //40
                                    },

                                    {
                                        data: 'estado'
                                    }, //41

                                    {
                                        data: 'centroprod'
                                    },

                                    {
                                        data: 'drogueria'
                                    },


                                    {
                                        data: 'cajero'
                                    },
                                    {
                                        data: 'user_id'
                                    }, //44

                                    // Nuevos datos de SOS para SOMA


                                    {
                                        data: 'nitips'
                                    },

                                    {
                                        data: 'frecuencia'
                                    },

                                    {
                                        data: 'dosis'
                                    },

                                    {
                                        data: 'duracion_tratamiento'
                                    },

                                    {
                                        data: 'cobertura' //50
                                    },

                                    {
                                        data: 'tipocontrato'
                                    },

                                    {
                                        data: 'tipoentrega'
                                    },

                                    {
                                        data: 'plan'
                                    },

                                    {
                                        data: 'via'
                                    },

                                    {
                                        data: 'ciudad'
                                    },


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


                                    },
                                    {
                                        text: 'Exportar Excel (Servidor)',
                                        className: 'btn btn-outline-success btn-sm',
                                        action: function() {
                                            const filters = {
                                                fechaini: fechaini,
                                                fechafin: fechafin,
                                                contrato: contrato,
                                                cobertura: cobertura,
                                                estado: 'REVISADO',
                                                _token: "{{ csrf_token() }}"
                                            };

                                            // Mostrar el spinner
                                            const spinner = document.createElement('div');
                                            spinner.innerHTML = `
                                                    <div class="spinner-backdrop">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="sr-only">Exportando...</span>
                                                        </div>
                                                        <p style="color: white; margin-top: 10px;">Exportando, por favor espera...</p>
                                                    </div>
                                                `;
                                            document.body.appendChild(spinner);

                                            $.ajax({
                                                url: "{{ route('exportar.excel') }}",
                                                method: 'POST',
                                                data: filters,
                                                xhrFields: {
                                                    responseType: 'blob'
                                                },
                                                success: function(data) {
                                                    const url = window.URL.createObjectURL(new Blob([data]));
                                                    const a = document.createElement('a');
                                                    a.href = url;
                                                    a.download = 'informe_facturas.xlsx';
                                                    document.body.appendChild(a);
                                                    a.click();
                                                    a.remove();

                                                    // Ocultar el spinner
                                                    document.body.removeChild(spinner);
                                                },
                                                error: function(xhr, status, error) {
                                                    alert('Error al exportar el archivo: ' + error);

                                                    // Ocultar el spinner en caso de error
                                                    document.body.removeChild(spinner);
                                                }
                                            });
                                        }
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
                                    [25, 50, 100, 500, 5000, -1],
                                    [25, 50, 100, 500, 5000, "Mostrar Todo"]
                                ],
                                processing: true,
                                serverSide: true,
                                aaSorting: [
                                    [28, "desc"]
                                ],
                                ajax: {
                                    url: "{{route('medcol6.disanulado')}}",
                                    data: {
                                        fechaini: fechaini,
                                        fechafin: fechafin,
                                        contrato: contrato,
                                        cobertura: cobertura,
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

            const text = 'De Medcol SOS - Jamundi';

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

                url: "{{route('medcol6.dispensadosyncapi')}}",
                type: 'GET',
                success: function(data) {
                    $('#dispensados').DataTable().ajax.reload();


                    $.each(data, function(i, item) {
                        Apiws.notificaciones(item.respuesta, item.titulo, item.icon, item.position);

                    });

                },

                complete: function() {

                    $('.loaders').css("visibility", "hidden");

                }
            });
        }

        //Funcion para sincronizar las facturas anuladas y actualizar el estado
        $(document).on('click', '#synanulados', function() {

            const text = 'De Medcol SOS - Jamundi';

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
                url: "{{route('medcol6.anuladosapi')}}",
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

                    url: "{{route('medcol6.add_dispensacion')}}",
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

        $('.dxcie10').select2({
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

        $('.ipsmul').select2({
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

        //Cargar select del iva
        $(".plansos").select2({
            language: "es",
            theme: "bootstrap4",
            placeholder: 'Seleccione un Plan',
            allowClear: true,
            ajax: {
                url: "{{ route('selectlist') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        id: 4
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.array[0], function(datas) {

                            return {

                                text: datas.slug + "=>" + datas.descripcion,
                                id: datas.descripcion

                            }
                        })
                    };
                },
                cache: true
            }
        }).trigger('change');

        $("#selector").on('click', function() {
            $(".checkbox2").prop("checked", this.checked);
        });




        //Funcion para buscar la factura y traer los datos al formulario y datatalbe
        $(document).ready(function() {
            $('#buscarFactura').on('click', function() {
                // Llamamos a la función guardarDispensacion al hacer clic en el botón "Enviar"
                buscarFactura();
            });
        });

        /* Script para manejar la lógica de búsqueda y DataTable */
        function buscarFactura() {
            const numeroFactura = $('#numero_factura').val();

            // Mostrar el spinner con SweetAlert2
            Swal.fire({
                icon: 'info',
                title: 'Buscando factura...',
                text: 'Por favor, espera mientras obtenemos la información.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ route('dispensado.medcol6', ['factura' => ':numero_factura']) }}`.replace(':numero_factura', numeroFactura),
                type: 'GET',
                success: function(data) {
                    Swal.close(); // Ocultar el loader

                    // Verificar si el backend no ha devuelto un error
                    if (data && !data.error && Array.isArray(data) && data.length > 0) {
                        const firstRecord = data[0];

                        $('#factura').val(firstRecord.factura);
                        $('#paciente').val(firstRecord.paciente);
                        $('#drogueria').val(firstRecord.drogueria);
                        $('#regimen').val(firstRecord.regimen);
                        $('#tipodocument').val(firstRecord.tipodocument);
                        $('#medico1').val(firstRecord.medico);
                        $('#fecha_solicitud').val(firstRecord.fecha_suministro);
                        $('#fecha_orden').val(firstRecord.fecha_ordenamiento);
                        $('#numero_entrega1').val(firstRecord.numero_entrega);
                        $('#num_total_entregas').val(firstRecord.num_total_entregas);
                        $('#formula1').val(firstRecord.numero_orden);
                        $('#facturaelectronica').val(firstRecord.documento_origen + ' - ' + firstRecord.factura_origen);
                        $('#ips_nombre').val(firstRecord.ips_nombre);
                        $('#diagnostico2').val(firstRecord.dx);
                        var newips = new Option(firstRecord.ips_nombre, firstRecord.ips, true, true);
                        $('.ipsmul').append(newips).trigger('change');

                        var newdx = new Option(firstRecord.dx, firstRecord.dx, true, true);
                        $('.dxcie10').append(newdx).trigger('change');

                        $('#estado2').val(firstRecord.estado);
                        actualizarEstadoIndicator(firstRecord.estado);
                        $('#id_medico').val(firstRecord.id_medico);
                        $('#tipoidmedico').val(firstRecord.tipoidmedico);
                        $('#numeroIdentificacion').val(firstRecord.numeroIdentificacion);
                        $('#especialidadmedico').val(firstRecord.especialidadmedico);
                        $('#ciudad').val(firstRecord.ciudad);
                        $('#tipocontrato').val(firstRecord.tipocontrato);
                        $('#ambito').val(firstRecord.ambito);
                        $('#cod_dispensario_sos').val(firstRecord.cod_dispensario_sos);
                        $('#tipoentrega').val(firstRecord.tipoentrega);
                        $('#cobertura2').val(firstRecord.cobertura);
                        $('#cod_dispen_transacc').val(firstRecord.cod_dispen_transacc);

                        if (firstRecord.fecha_suministro) {
                            const formattedFechaSuministro = new Date(firstRecord.fecha_suministro).toISOString().split('T')[0];
                            $('#fecha_suministro').val(formattedFechaSuministro);
                        } else {
                            $('#fecha_suministro').val('');
                        }

                        $('#idusuario').val(firstRecord.idusuario);
                        $('#cajero').val(firstRecord.cajero);

                        actualizarDataTable(data);

                        // Mostrar mensaje de éxito con SweetAlert2
                        /* Swal.fire({
                            icon: 'success',
                            title: 'Factura encontrada',
                            text: 'Los datos se han cargado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        }); */

                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: 'Factura encontrada',
                            text: 'Los datos se han cargado correctamente.'
                        });

                    } else if (data.error) {
                        // Mostrar el mensaje de error específico devuelto por el backend
                        mostrarError(data.error);
                    } else {
                        // Si no se encuentran registros y no hay error, mostrar mensaje genérico
                        mostrarError('No se encontraron registros para la factura ingresada.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al buscar la factura:', error);

                    // Extraer el mensaje de error del backend, si está disponible
                    let errorMessage = 'Error al buscar la factura. Por favor, inténtalo de nuevo.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }

                    // Mostrar alerta con el error
                    mostrarError(errorMessage);
                }
            });
        }

        function actualizarDataTable(data) {
            const tablaRegistros = $('#tablaRegistros').DataTable();
            tablaRegistros.clear().rows.add(data).draw();
        }


        // Función para mostrar alertas de error utilizando SweetAlert2
        function mostrarError(mensaje) {
            Swal.fire({
                type: 'error',
                icon: 'error',
                title: 'Error',
                html: mensaje,
                confirmButtonText: 'Aceptar'
            });
        }


        $(document).ready(function() {

            $('#tablaRegistros').DataTable({
                language: idioma_espanol,
                processing: true,
                lengthMenu: [
                    [25, 50, 100, 500, -1],
                    [25, 50, 100, 500, "Mostrar Todo"]
                ],
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                data: [], // Inicialmente, no hay datos para mostrar
                columns: [{
                        data: 'action',
                        orderable: false
                    },
                    /* {
                                           data: 'id',
                                           orderable: false
                                       }, */
                    {
                        data: 'codigo'
                    },
                    {
                        data: 'cums'
                    },
                    {
                        data: 'nombre_generico'
                    },
                    {
                        data: 'numero_unidades'
                    },
                    {
                        data: 'precio_unitario'
                    },
                    {
                        data: 'valor_total'
                    },
                    {
                        data: 'frecuencia2'
                    },
                    {
                        data: 'dosis2'
                    },
                    {
                        data: 'duracion_tratamiento2'
                    },
                    {
                        data: 'cuota_moderadora2'
                    },
                    {
                        data: 'autorizacion2'
                    },
                    {
                        data: 'mipres2'
                    },
                    {
                        data: 'reporte_entrega2'
                    }
                ]
            });
        });

        //Funcion para realizar la revision de la dispensacion de forma multiple
        $(document).ready(function() {
            $('#enviarDispensado').on('click', function() {
                // Llamamos a la función guardarDispensacion al hacer clic en el botón "Enviar"
                guardarDispensacion();
            });
        });

        async function guardarDispensacion() {
            try {
                // Obtener los valores de los campos
                const fechaDisp = $('#fecha_suministro').val();
                const fechaOrden = $('#fecha_orden').val();
                const numeroEntrega = $('#numero_entrega1').val();
                const numTotalEntregas = $('#num_total_entregas').val();
                const numFormula = $('#formula1').val();

                const diagnosticoElement = $('.dxcie10');
                const diagnostico = diagnosticoElement.val();

                const ipsElement = $('.ipsmul');
                const ips = ipsElement.val();

                //const planElement = $('.plansos');
                //const plan = planElement.val();

                const userId = "{{ Auth::user()->id }}";

                // Validación de campos obligatorios
                const camposFaltantes = [];
                if (!fechaOrden) camposFaltantes.push('Fecha de Ordenamiento');
                if (!numeroEntrega) camposFaltantes.push('Número de Entrega');
                if (!numTotalEntregas) camposFaltantes.push('Número Total de Entregas');
                if (!ips) camposFaltantes.push('IPS');
                if (!diagnostico) camposFaltantes.push('Diagnóstico');
                //if (!plan) camposFaltantes.push('Plan');
                if (!numFormula) camposFaltantes.push('No. de Fórmula');

                // Validar si las fechas son coherentes
                if (fechaOrden && fechaDisp && new Date(fechaOrden) > new Date(fechaDisp)) {
                    camposFaltantes.push('La Fecha de Ordenamiento no puede ser superior a la Fecha de Suministro');
                }

                /*if (numeroEntrega>numTotalEntregas){
                    camposFaltantes.push('El Número de Entrega no puede ser mayor que el Número Total de Entregas');
                }*/

                // Si hay campos faltantes, mostrar una alerta y detener el proceso
                if (camposFaltantes.length > 0) {
                    const mensaje = `Los siguientes campos son obligatorios:<br><br>${camposFaltantes.map(campo => `<span style="font-weight: bold;">- ${campo}</span><br>`).join('')}`;
                    await Swal.fire({
                        icon: "warning", // Usar "icon" en lugar de "type"
                        title: '<span style="color: #ff6347;">Oops...</span>',
                        html: `<div style="color: #333333; font-size: 16px; line-height: 1.5em;">${mensaje}</div>`,
                        confirmButtonText: 'Revisar',
                        confirmButtonColor: '#DD6B55'
                    });
                    return; // Detener la ejecución si faltan campos
                }

                // Recopilar los datos de la tabla
                const dispensado = [];

                $("#tablaRegistros tbody tr").each(function() {
                    const tds = $(this).find("td");
                    if (tds.eq(0).text() !== 'Ningún dato disponible en esta tabla =(') {
                        const itemdispensado = {
                            checked: tds.find(":checkbox").prop("checked"),
                            id: tds.find(":checkbox:checked").attr('id'),
                            frecuencia: tds.eq(7).find('input').val(),
                            dosis: tds.eq(8).find('input').val(),
                            duracion_tratamiento: tds.eq(9).find('input').val(),
                            cuota_moderadora: tds.eq(10).find('input').val(),
                            autorizacion: tds.eq(11).find('input').val(),
                            mipres: tds.eq(12).find('input').val(),
                            reporte_entrega: tds.eq(13).find('input').val(),
                            user_id: userId,
                            fecha_suministro: fechaDisp,
                            fecha_orden: fechaOrden,
                            numero_entrega: numeroEntrega,
                            num_total_entregas: numTotalEntregas,
                            numero_orden: numFormula,
                            //plan: plan,
                            diagnostico: diagnostico,
                            estado: "REVISADO",
                            ips: ips
                        };


                        dispensado.push(itemdispensado);
                    }
                });

                // Validar y filtrar los elementos que se van a guardar
                const dispensadotrue1 = [];
                for (const item of dispensado) {
                    if (!item.checked) continue;

                    console.log(item.autorizacion);

                    // Validar que duracion_tratamiento tenga máximo 3 caracteres y entre 1 y 365 días
                    if (item.duracion_tratamiento?.length > 3 || item.duracion_tratamiento < 1 || item.duracion_tratamiento > 365) {
                        await Swal.fire({
                            type: "error",
                            icon: "error",
                            title: "Error",
                            text: "El campo Duración del Tratamiento debe estar entre 1 y 365 días",
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Si autorizacion está vacío, solo validar Frecuencia y Dosis
                    if (!item.autorizacion) {
                        if (!item.frecuencia?.trim() || !item.dosis?.trim() || !item.duracion_tratamiento?.trim()) {
                            await Swal.fire({
                                type: "error",
                                icon: "error",
                                title: "Error",
                                text: "Los campos Frecuencia, Dosis y Duración del Tratamiento deben completarse",
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                    } else {
                        // Definir validaciones para cuando autorizacion tiene un valor
                        const validations = [{
                                condition: !item.mipres || !item.reporte_entrega,
                                message: "Los campos MIPRES y Reporte de Entrega deben completarse"
                            },
                            {
                                condition: item.autorizacion.length !== 12,
                                message: "El campo Autorización debe tener exactamente 12 caracteres"
                            },
                            {
                                condition: item.mipres?.length !== 20,
                                message: "El campo MIPRES debe tener exactamente 20 caracteres"
                            },
                            {
                                condition: item.reporte_entrega?.length !== 8,
                                message: "El campo Reporte de Entrega debe tener exactamente 8 caracteres"
                            },
                            {
                                condition: !item.frecuencia?.trim() || !item.dosis?.trim(),
                                message: "Los campos Frecuencia y Dosis deben completarse"
                            }
                        ];

                        // Ejecutar validaciones y mostrar alerta si falla alguna
                        for (const {
                                condition,
                                message
                            }
                            of validations) {
                            if (condition) {
                                await Swal.fire({
                                    type: "error",
                                    icon: "error",
                                    title: "Error",
                                    text: message,
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                        }
                    }

                    dispensadotrue1.push(item);
                }

                // Si no hay registros a enviar, mostrar alerta
                if (dispensadotrue1.length === 0) {
                    await Swal.fire({
                        type: "warning",
                        icon: "warning",
                        title: "Advertencia",
                        text: "No hay registros seleccionados para guardar.",
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Preparar los datos para enviar al controlador
                const datos = {
                    registros: dispensadotrue1
                };
                console.log('Datos a enviar al controlador:', dispensadotrue1);

                // Enviar los datos al controlador
                const response = await $.ajax({
                    url: "{{ route('dispensado6.guardar') }}", // Asegúrate de usar la ruta correcta
                    type: 'POST',
                    data: {
                        data: datos,
                        "_token": $("meta[name='csrf-token']").attr("content")
                    }
                });

                // Mostrar éxito
                await Swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Datos guardados correctamente.',
                    confirmButtonText: 'OK'
                });

                // Limpiar el formulario y la tabla
                $('#gestion_multiple').find('input, textarea').val('');
                $('#gestion_multiple').find('select').val('');
                $('#tablaRegistros').DataTable().clear().draw();

            } catch (error) {
                console.error('Error al guardar los datos:', error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar los datos. Por favor, inténtalo de nuevo.',
                    confirmButtonText: 'OK'
                });
            }
        }

        //Función para abrir modal del detalle de la factura para editar los campos con valopres erroneos.
        $(document).on('click', '.edit_dispensado', function() {

            $('#form-general1').trigger('reset');
            var id = $(this).attr('id');
            /* var nivel_idp2 = $(this).attr('id');

            if (nivel_idp2 != '') {

                if ($.fn.DataTable.isDataTable('#tobservaciones')) {
                    $('#tobservaciones').DataTable().destroy();
                    $('#tobservaciones').empty();
                }
                fill_datatable_f(nivel_idp2);
            } */


            $.ajax({
                url: "/medcol6/showdispensado/" + id,
                dataType: "json",
                success: function(data) {

                    // Primer form de información pendientes por pagar
                    $('#tipodocument').val(data.dispensado.tipodocument);
                    $('#numero_unidades').val(data.dispensado.numero_unidades);
                    $('#cantidad_ordenada').val(data.dispensado.cantidad_ordenada);
                    const safeDate = (dateStr) => dateStr ? moment(dateStr).format('YYYY-MM-DD') : '';
                    $('#fecha_ordenamiento').val(safeDate(data.dispensado.fecha_ordenamiento));
                    $('#fecha_suministro').val(safeDate(data.dispensado.fecha_suministro));
                    $('#historia').val(data.dispensado.historia);
                    $('#primer_apellido').val(data.dispensado.primer_apellido);
                    $('#segundo_apellido').val(data.dispensado.segundo_apellido);
                    $('#primer_nombre').val(data.dispensado.primer_nombre);
                    $('#segundo_nombre').val(data.dispensado.segundo_nombre);
                    const nombreCompleto = `${data.dispensado.primer_nombre} ${data.dispensado.segundo_nombre} ${data.dispensado.primer_apellido} ${data.dispensado.segundo_apellido}`;
                    $("#nombre_completo").val(nombreCompleto.trim().replace(/\s+/g, ' '));
                    $('#paciente').val(data.dispensado.paciente);
                    $('#ciudad').val(data.dispensado.ciudad);
                    $('#numeroIdentificacion').val(data.dispensado.numeroIdentificacion);
                    $('#factura').val(data.dispensado.factura);
                    $('#codigo').val(data.dispensado.codigo);
                    $('#nombre_generico').val(data.dispensado.nombre_generico);

                    if (data.dispensado.cums === '' || data.dispensado.cums === null) {
                        $('#cums').val(data.dispensado.codigo);
                    } else {
                        $('#cums').val(data.dispensado.cums);
                    }

                    $('#centroprod').val(data.dispensado.centroprod);
                    $('#ips').val(data.dispensado.ips);
                    $('#observ').val(data.dispensado.reporte_entrega_nopbs);
                    $('#numero_entrega').val(data.dispensado.numero_entrega);
                    $('#cajero').val(data.dispensado.cajero);
                    $('#medico').val(data.dispensado.medico);
                    $('#especialidadmedico').val(data.dispensado.especialidadmedico);
                    $('#estado').val(data.dispensado.estado);
                    /* $('#fecha_entrega').val(safeDate(data.dispensado.fecha_suministro)); */
                    $('#autorizacion').val(data.dispensado.autorizacion);
                    $('#mipres').val(data.dispensado.mipres);
                    $('#reporte_entrega_nopbs').val(data.dispensado.reporte_entrega_nopbs);

                    $('#hidden_id').val(id);
                    $('#edit_dispensado').text("Editando el registro # " + data.dispensado.id +
                        " de la factura " + data.dispensado.factura);

                    $('#action_button').val('Edit');
                    $('#action').val('Edit');

                    $('#modal-edit-dispensados').modal('show');

                },

            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                        'Sistema seguimiento dispensación', 'warning');

                }
            });

        });

        function actualizarEstadoIndicator(estado) {
            // Ocultar ambos íconos antes de actualizar
            $('#estado-activo, #estado-inactivo').addClass('d-none');

            if (estado === 'DISPENSADO') {
                $('#estado-inactivo').removeClass('d-none'); // Mostrar estado inactivo
            } else if (estado === 'REVISADO') {
                $('#estado-activo').removeClass('d-none'); // Mostrar estado activo
            }
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