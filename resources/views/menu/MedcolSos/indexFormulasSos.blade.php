@extends("theme.$theme.layout")

@section('titulo')
Formulas Pacientes SOS
@endsection
@section('styles')
<link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet"
    type="text/css" />
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    /* // Colores para las tarjetas widget */

    /*CREATE TRIGGER `Actualiza_Consecutivo` AFTER INSERT ON `entradas`
             FOR EACH ROW UPDATE documentos set consecutivo = new.consecutivo + 1 WHERE  documento = (SELECT documento FROM entradas order by id desc
            limit 1)
            */
    .card {
        background-color: #fff;
        border-radius: 10px;
        border: none;
        position: relative;
        margin-bottom: 30px;
        box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
    }

    .l-bg-blue-dark-card {
        background-color: linear-gradient(to right, #373b44, #4286f4) !important;
        color: #fff;
    }



    .l-bg-cherry {
        background: linear-gradient(to right, #493240, #f09) !important;
        color: #fff;
    }

    .l-bg-blue-dark {
        background: linear-gradient(to right, #373b44, #4286f4) !important;
        color: #fff;
    }

    .l-bg-green-dark {
        background: linear-gradient(to right, #0a504a, #38ef7d) !important;
        color: #fff;
    }

    .l-bg-orange-dark {
        background: linear-gradient(to right, #a86008, #ffba56) !important;
        color: #fff;
    }

    .l-bg-red-dark {
        background: linear-gradient(to right, #a80d08, #ff6756) !important;
        color: #fff;
    }

    .card .card-statistic-3 .card-icon-large .fas,
    .card .card-statistic-3 .card-icon-large .far,
    .card .card-statistic-3 .card-icon-large .fab,
    .card .card-statistic-3 .card-icon-large .fal {
        font-size: 110px;
    }

    .card .card-statistic-3 .card-icon {
        text-align: center;
        line-height: 50px;
        margin-left: 15px;
        color: #000;
        position: absolute;
        right: -5px;
        top: 20px;
        opacity: 0.1;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }

    .l-bg-green {
        background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
        color: #fff;
    }

    .l-bg-orange {
        background: linear-gradient(to right, #f9900e, #ffba56) !important;
        color: #fff;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }



    /*btn flotante*/
    .btn-flotante {
        font-size: 14px;
        /* Cambiar el tamaño de la tipografia */
        text-transform: uppercase;
        /* Texto en mayusculas */
        font-weight: bold;
        /* Fuente en negrita o bold */
        color: #ffffff;
        /* Color del texto */
        border-radius: 120px;
        /* Borde del boton */
        letter-spacing: 2px;
        /* Espacio entre letras */
        background: linear-gradient(to right, #a80d08, #ff6756) !important;
        /* Color de fondo */
        /*background-color: #e9321e; /* Color de fondo */
        padding: 18px 30px;
        /* Relleno del boton */
        position: fixed;
        bottom: 40px;
        right: 40px;
        transition: all 300ms ease 0ms;
        box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.5);
        z-index: 99;
        border: none;
        outline: none;
    }

    .btn-flotante:hover {
        background-color: #2c2fa5;
        /* Color de fondo al pasar el cursor */
        box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
        transform: translateY(-7px);
    }

    @media only screen and (max-width: 600px) {
        .btn-flotante {
            font-size: 14px;
            padding: 12px 20px;
            bottom: 20px;
            right: 20px;
        }
    }
</style>
@endsection


@section('scripts')

@endsection

@section('contenido')
@include('menu.MedcolSos.modal.modalFormulasSos')

@include('menu.MedcolSos.modal.modalFacturaArticulo')
@endsection


@section('scriptsPlugins')
<script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
</script>
<script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>

<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {

                //Select para cargar los articulos de la tabla
                $("#tipoDocId").select2({
                    language: "es",
                    theme: "bootstrap4",
                    placeholder: 'Tipo documento',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('selectlist') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                id: 3
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data.array[0], function(datas) {

                                    return {

                                        text: datas.slug + "=>" + datas.descripcion,
                                        id: datas.slug

                                    }
                                })
                            };
                        },
                        cache: true
                    }
                }).trigger('change');


                    var datosFormulasMedicas = [];
                    
                    $('#guardar_entrada').click(function() {
                       
                        var url = "{{ route('dataFormulasSos') }}";
                        var tipoDocId = $('#tipoDocId').val();
                        var numeroDocId = $('#numeroDocId').val();
                    
                        Swal.fire({
                            icon: 'info',
                            title: 'Espere por favor...',
                            html: 'Consultando datos...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            willOpen: () => {
                                Swal.showLoading();
                            },
                        });
                    
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            method: 'post',
                            data: {
                                tipoIdentificacion: tipoDocId,
                                numeroIdentificacion: numeroDocId,
                                "_token": $("meta[name='csrf-token']").attr("content")
                            },
                            success: function(response) {
                                Swal.close(); // Cerrar la alerta de carga
                    
                                if (response.status === 'error') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'No se pudo realizar la consulta. Intente nuevamente.',
                                        showConfirmButton: true
                                    });
                                    return;
                                }
                    
                                if (response.status === 'success') {
                                    const result = response.data.result;
                    
                                    // Validar que los datos de la fórmula médica existan
                                    if (!result || !result.datosFormulasMedica || result.datosFormulasMedica.length === 0) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Sin resultados',
                                            text: 'No se encontraron fórmulas médicas para este afiliado.',
                                            showConfirmButton: true
                                        });
                                        return;
                                    }
                    
                                    datosFormulasMedicas = result.datosFormulasMedica; // Guardar los datos en la variable global
                                    console.log(datosFormulasMedicas);
                    
                                    // Mostrar el contenedor de resultados
                                    $('#resultado-consulta').removeClass('d-none');
                    
                                    // Destruir el DataTable si ya existe
                                    if ($.fn.DataTable.isDataTable('#tabla-formulas-medicas')) {
                                        $('#tabla-formulas-medicas').DataTable().destroy();
                                    }
                    
                                    // Limpiar los datos anteriores
                                    $('#datos-afiliado, #tabla-formulas').empty();
                    
                                    // Datos del Afiliado
                                    $('#datos-afiliado').append(`
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Tipo de Identificación:</strong></label>
                                            <input type="text" class="form-control" readonly value="${result.tipoIdentificacion}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Número de Identificación:</strong></label>
                                            <input type="text" class="form-control" readonly value="${result.numeroIdentificacion}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Nombre Completo:</strong></label>
                                            <input type="text" class="form-control" readonly value="${result.primerNombre} ${result.segundoNombre || ''} ${result.primerApellido} ${result.segundoApellido || ''}">
                                        </div>
                                    `);
                    
                                    // Tabla de Formulas Médicas, Médicos y Medicamentos
                                    let tablaFormulas = `
                                        <table id="tabla-formulas-medicas" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Fecha Fórmula</th>
                                                    <th>Número Fórmula</th>
                                                    <th>Diagnostico</th>
                                                    <th>Nombre del Médico</th>
                                                    <th>Especialidad</th>
                                                    <th>Medicamentos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    `;
                    
                                    datosFormulasMedicas.forEach((formula, index) => {
                                        const datosGenerales = formula.datosGenerales;
                                        const datosMedico = formula.datosMedico;
                                        const medicamentos = formula.datosMedicamentos;
                    
                                        // Generar tabla de medicamentos para cada fórmula
                                        let listaMedicamentos = `
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Código</th>
                                                        <th>Medicamento</th>
                                                        <th>Cantidad</th>
                                                        <th>Dosis</th>
                                                        <th>Vía Administración</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                        `;
                    
                                        medicamentos.forEach((medicamento, i) => {
                                            listaMedicamentos += `
                                                <tr>
                                                    <td>${i + 1}</td>
                                                    <td>${medicamento.codigoMedicamento}</td>
                                                    <td>${medicamento.nombreMedicamento}</td>
                                                    <td>${medicamento.cantidadAEntregar}</td>
                                                    <td>${medicamento.cantidadDosis}</td>
                                                    <td>${medicamento.viaAdministracion}</td>
                                                </tr>
                                            `;
                                        });
                    
                                        listaMedicamentos += `</tbody></table>`;
                    
                                        // Añadir la fila de fórmula con los medicamentos ya visibles
                                        tablaFormulas += `
                                            <tr>
                                                <td>${datosGenerales.fechaFormula}</td>
                                                <td>${datosGenerales.numeroFormula}</td>
                                                <td>${datosGenerales.codigoDiagnostico}</td>
                                                <td>${datosMedico.primerNombre} ${datosMedico.segundoNombre || ''} ${datosMedico.primerApellido} ${datosMedico.segundoApellido}</td>
                                                <td>${datosMedico.especialidad}</td>
                                                <td>${listaMedicamentos}</td>
                                            </tr>
                                        `;
                                    });
                    
                                    tablaFormulas += `
                                            </tbody>
                                        </table>
                                    `;
                    
                                    // Añadir la tabla de fórmulas al DOM
                                    $('#tabla-formulas').append(tablaFormulas);
                    
                                    // Inicializar DataTable en la tabla creada
                                    $('#tabla-formulas-medicas').DataTable({
                                        
                                        language: idioma_espanol,
                                       
                                        lengthMenu: [
                                            [25, 50, 100, 500, -1],
                                            [25, 50, 100, 500, "Mostrar Todo"]
                                        ],
                                       
                                        aaSorting: [
                                            [0, "desc"]
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
                                ]
                                        
                                    });
                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Datos consultados correctamente',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ocurrió un error en la consulta.',
                                    showConfirmButton: true
                                });
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