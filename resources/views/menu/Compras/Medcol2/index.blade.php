@extends("theme.$theme.layout")

@section('titulo')
    Ordenes de Compra
@endsection
@section('styles')
    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet"
        type="text/css" />
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
<script src="{{ asset('assets/pages/scripts/admin/enviodatain/index.js') }}"></script>
@endsection

@section('contenido')
    @include('menu.Compras.Medcol2.modal.modalCompras')
    @include('menu.Compras.Medcol2.modal.modalFacturaArticulo')
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
        // Llamar a la función de calcular totales cuando se carga la página
        calcularTotales();


       function calcularTotales() {
        var totalSubtotal = 0;
        var totalIva = 0;
        var totalTotal = 0;

        // Iterar sobre cada fila de la tabla
        $('#tcups tbody tr').each(function () {
           

            var subtotalformatted = $(this).find('.precio_compra_subtotal').text().replace(/[^\d,]/g, '');
            subtotalformatted = subtotalformatted.replace(',', '.');

            var ivaformatted = $(this).find('.cantidad_iva_total').text().replace(/[^\d,]/g, '');
            ivaformatted = ivaformatted.replace(',', '.');

            var totalformatted = $(this).find('.precio_compra_total').text().replace(/[^\d,]/g, '');
            totalformatted = totalformatted.replace(',', '.');
            
            


            var subtotal = parseFloat(subtotalformatted);
            var iva = parseFloat(ivaformatted) || 0;
            var total = parseFloat(totalformatted) || 0;

            totalSubtotal += subtotal;
            totalIva += iva;
            totalTotal += total;
        });

        // Actualizar los totales en la interfaz

               


        $('#totalSubtotal').val(totalSubtotal.toLocaleString('es-CO', {
                                            style: 'currency',
                                            currency: 'COP'
                                        }));
        $('#totalIva').val(totalIva.toLocaleString('es-CO', {
                                            style: 'currency',
                                            currency: 'COP'
                                        }));
        $('#totalTotal').val(totalTotal.toLocaleString('es-CO', {
                                            style: 'currency',
                                            currency: 'COP'
                                        }));

        

    }
        



            //Funcion que abre modal donde se deben seleccionar el articulo que se iran cargando en la factura
            $('#agregar_articulo').click(function() {

                $('#form-general_1')[0].reset();
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#form_result_1').html('');
                $('#modal-articulos').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal-articulos').modal('show');
            });



            // Agregar filas a tabla para guardar
            $('#addfila').click(function() {

            let codigo = null,
                cums = null,
                nombrea = null,
                precio_compra_subtotal_unitario = null,
                iva = null,
                precio_compra_subtotal = null,
                cantidad = null,
                precio_compra_total = null;
                
                codigo = $('#codigo').val();
                cums = $('#cums').val();
                nombrea = $('#nombrea').val();
                precio_compra_subtotal_unitario = $('#precio_compra_subtotal_unitario').val();
                iva = $('#iva').val();
                precio_compra_subtotal = $('#precio_compra_subtotal').val();
                cantidad = $('#cantidad').val();
                precio_compra_total = $('#precio_compra_total').val();

                const array = [codigo, nombrea, cums, precio_compra_subtotal_unitario,
                    precio_compra_subtotal, cantidad, precio_compra_total
                ];


                function verificarArray(array) {
                    for (var i = 0; i < array.length; i++) {
                        if (array[i] === null || array[i] === '' || array[i] == '0' || array[i] == 0) {
                            console.log(array[i]);
                            return false;
                        }
                    }

                   /* for (var i = 0; i < array.length; i++) {

                        console.log(array[i]);


                    }*/


                    return true; // Si ninguno es null || '' || 0 || '0', devuelve true
                }


                var resultado = verificarArray(array);

                if (resultado) {
                    const total = parseFloat($('#cantidad').val() * $('#valor').val());
                    var valordelpagosubtotalFormatted = parseFloat($('#precio_compra_subtotal').val())
                        .toLocaleString('es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        });

                    var valordelpagocompraFormatted = parseFloat($('#precio_compra_subtotal_unitario').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });

                    var valordelivaFormatted = parseFloat($('#iva').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    }); 

                    var valordelpagoivatotalFormatted = parseFloat($('#cantidad_iva_total').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });

                    var valordelpagototalFormatted = parseFloat($('#precio_compra_total').val())
                    .toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });    

                        

                    $('#tcups> tbody:last-child')
                        .append(

                        
                            '<tr><td><button type="button" name="eliminar" class="btn-float bg-gradient-danger btn-sm tooltipsC" title="eliminar"><i class="fas fa-trash"></i></button></td>' +
                            '<td class="codigo">' + $('#codigo').val() + '</td>' +
                            '<td class="cums">' + $('#cums').val() + '</td>' +
                            '<td class="nombrea">' + $('#nombrea').val() + '</td>' +
                            '<td class="precio_compra_subtotal_unitario">' + valordelpagocompraFormatted + '</td>' +
                            '<td class="ivab">' + $('#ivab').val() + '</td>' +
                            '<td class="iva">' + valordelivaFormatted + '</td>' +
                            '<td class="cantidad">' + $('#cantidad').val() + '</td>' +
                            '<td class="precio_compra_subtotal">' +  valordelpagosubtotalFormatted + '</td>' +
                            '<td class="cantidad_iva_total">' + valordelpagoivatotalFormatted + '</td>' +
                            '<td class="precio_compra_total">' +valordelpagototalFormatted + '</td></tr>'  
                           
                           
                          
                        );

                      
                      calcularTotales();
                                


                    $('#form-general_1')[0].reset();
                    $("#codigo").val('').trigger('change');
                    $("#ivab").val('').trigger('change');

                    

                } else {

                    Swal.fire({
                        type: 'error',
                        title: 'El formulario contiene elementos vacios o en cero',
                        showConfirmButton: true,

                    })
                }




            });

         
          
            // eliminar filas de la tabla procedimientos para guardar

            $("#tcups tbody").on("click", 'button[name="eliminar"]', function() {
                $(this).closest("tr").remove();
                // Volver a calcular los totales si es necesario
                calcularTotales();
                
            });

            //--------- validacion de input solo valores enteros -------//
            $(function() {

                $('.validanumericos1').keypress(function(e) {
                        if (isNaN(this.value + String.fromCharCode(e.charCode)))
                            return false;
                    })
                    .on("cut copy paste", function(e) {
                        e.preventDefault();
                    });

            });


            $('.validanumericos').on({
                "focus": function(event) {
                    $(event.target).select();
                },
                "keyup": function(event) {
                    $(event.target).val(function(index, value) {
                        return value.replace(/\D/g, "")
                            .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                    });
                }
            });


            // Calcular la suma total de factura general
          
            $('#precio_compra_subtotalf').change(sumatotal);

            $('#totaliva').change(sumatotal);

            function sumatotal() {



                let ivax = $('#totaliva').val();

                ivax = ivax.replaceAll('.', '');

                let base = $('#precio_compra_subtotalf').val();

                base = base.replaceAll('.', '');

                const preciototal = (parseInt(ivax) + parseInt(base));

                $('#precio_compra_totalf').val(preciototal);


            }


             // Calcular la suma total de factura por item + el iva

            $('#ivab').change(valorivauni);
            $('#precio_compra_subtotal_unitario').change(valorivauni);
            $('#cantidad').change(valorivauni);
            

            function valorivauni() {



                const ivax = $('#ivab').val();
                let base = $('#precio_compra_subtotal_unitario').val();
                const cantidad = $('#cantidad').val();

                base = base.replaceAll('.', '');

                const resultado = (ivax * base) / 100;
                const resultado1 = (ivax * base * cantidad) / 100;

                $('#iva').val(resultado);
                $('#cantidad_iva_total').val(resultado1);


            }



            $('#precio_compra_subtotal_unitario').change(sumatotalarticulo);
            $('#ivab').change(sumatotalarticulo);
            $('#cantidad').change(sumatotalarticulo);

            function sumatotalarticulo() {

                let preciouni = $('#precio_compra_subtotal_unitario').val();
                let cantidad = $('#cantidad').val();
                let preciosubtotal = 0;

                preciouni = preciouni.replaceAll('.', '');
                preciosubtotal = parseInt(preciouni) * parseInt(cantidad);

                let ivax = $('#cantidad_iva_total').val();
                ivax = ivax.replaceAll('.', '');


                const preciototal = (parseInt(ivax) + parseInt(preciosubtotal));

                $('#precio_compra_subtotal').val(preciosubtotal);
                $('#precio_compra_total').val(preciototal);


            }




            $("#facturae").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione tipo factura',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 10
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });




            //Select para cargar los articulos de la tabla

            $("#codigo").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Buscar articulo....',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectarticulo2') }}",
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

                                    text: datas.codigo + "=>" + datas.nombre,
                                    id: datas.codigo

                                }
                            })
                        };
                    },
                    cache: true
                }
            }).trigger('change');

            $('#codigo').change(listasdetallearticulo);


            function listasdetallearticulo() {

                $("#cums").val('');
                $("#nombrea").val('');

                const ids1 = $('#codigo').val();
                var url = "/inventario/articulos/" + ids1;


                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(detalle) {
                        $.each(detalle, function(i, items) {



                            $("#cums").val(items.cums);

                            $("#nombrea").val(items.nombrep);

                        });



                    }



                });





            }


         
            //Cargar select del iva
            $("#ivab").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'iva',
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

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            }).trigger('change');


            //Select para cargar los proveedores de la tabla

            $("#proveedor_id").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione un proveedor',
                allowClear: true,
                ajax: {
                    url: "{{ route('proveedoreslist2') }}",
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

                                    text: datas.codigo_tercero + "=>" + datas.nombre_sucursal,
                                    id: datas.id

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#proveedor_id').change(listasdetalleproveedor);

            function listasdetalleproveedor() {

                $("#codigop").val('');
                $("#nombrep").val('');

                const ids1 = $('#proveedor_id').val();

                var url = "/detalleproveedores2/" + ids1;

                console.log(ids1);
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(proveedor) {
                        $.each(proveedor, function(i, items) {

                            console.log(proveedor)

                            $("#codigop").val(items.codigo_tercero);

                            $("#nombrep").val(items.nombre_sucursal);

                        });

                    }

                });
            }


            // Función para traer el consecutivo del documento seleccionado


            //Select para cargar los documentos de la tabla

            /*$("#documento").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione un documento',
                allowClear: true,
                ajax: {
                    url: "{{ route('documentoslist') }}",
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

                                    text: datas.documento,
                                    id: datas.id

                                }
                            })
                        };
                    },
                    cache: true
                }
            });*/

            /*$('#documento').change(listasdocumento);


            function listasdocumento() {

                $("#consecutivo").val('');

                const ids1 = $('#documento').val();

                var url = "/detalledocumento/" + ids1;

                console.log(ids1);
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function(documento) {
                        $.each(documento, function(i, items) {

                            console.log(documento)

                            $("#consecutivo").val(items.consecutivo);

                            // $("#nombrep").val(items.nombrep);

                        });

                    }


                });


            }*/


            var myTable =
                $('#ordenes').DataTable({
                    language: idioma_espanol,
                    processing: true,
                    lengthMenu: [
                        [25, 50, 100, 500, -1],
                        [25, 50, 100, 500, "Mostrar Todo"]
                    ],
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [1, "asc"]
                    ],

                    ajax: {
                        url: "{{ route('comprasli2') }}",
                    },
                    columns: [{
                            data: 'action',
                            name: 'action',
                            orderable: false
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'documentoOrden',
                            name: 'documentoOrden'
                        },
                        {
                            data: 'numeroOrden',
                            name: 'numeroOrden'
                        },
                        {
                            data: 'proveedor_nombre',
                            name: 'proveedor_nombre'
                        },
                        {
                            data: 'codigo',
                            name: 'codigo'
                        },
                        {
                            data: 'nombre',
                            name: 'nombre'
                        },
                        {
                            data: 'marca',
                            name: 'marca'
                        },
                        {
                            data: 'cantidad',
                            name: 'cantidad'
                        },
                        {
                            data: 'precio',
                            name: 'precio'
                        },
                        {
                            data: 'subtotal',
                            name: 'subtotal'
                        },
                        {
                            data: 'contrato',
                            name: 'contrato'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'user_id.usuario',
                            name: 'user_id.usuario'
                        }

                    ],

                    //Botones----------------------------------------------------------------------

                    "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',


                    buttons: [{

                            extend: 'copyHtml5',
                            titleAttr: 'Copiar Registros',
                            title: "seguimiento",
                            className: "btn  btn-outline-primary btn-sm"


                        },
                        {

                            extend: 'excelHtml5',
                            titleAttr: 'Exportar Excel',
                            title: "seguimiento",
                            className: "btn  btn-outline-success btn-sm"


                        },
                        {

                            extend: 'csvHtml5',
                            titleAttr: 'Exportar csv',
                            className: "btn  btn-outline-warning btn-sm"
                            //text: '<i class="fas fa-file-excel"></i>'

                        },
                        {

                            extend: 'pdfHtml5',
                            titleAttr: 'Exportar pdf',
                            className: "btn  btn-outline-secondary btn-sm"


                        }
                    ]

                    // "columnDefs": [{

                    //         "render": function(data, type, row) {
                    //             if (row["activo"] == 1) {
                    //                 return data + ' - Activo';
                    //             } else {

                    //                 return data + ' - Inactivo';

                    //             }

                    //         },
                    //         "targets": [11]
                    //     },
                    //],

                    // "createdRow": function(row, data, dataIndex) {
                    //     if (data["activo"] == 1) {
                    //         $($(row).find("td")[11]).addClass("btn btn-sm btn-success rounded-lg");
                    //     } else {
                    //         $($(row).find("td")[11]).addClass("btn btn-sm btn-warning rounded-lg");
                    //     }
                    //     if (data["type_salary"] == 1) {
                    //         $($(row).find("td")[15]).addClass("btn btn-sm btn-info rounded-lg");
                    //     } else {
                    //         $($(row).find("td")[15]).addClass("btn btn-sm btn-dark rounded-lg");
                    //     }

                    // }



                });

            //     });

            // });


            $(document).on('click', '.create_cuenta', function() {
                $('#form-generalc')[0].reset();
                $('#cardtitle').text('Estas creando una nueva cuenta');
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#card-drawel1').removeClass('card card-warning');
                $('#card-drawel1').addClass('card card-info');
                $('#cardtabscuenta').removeClass('card card-warning card-tabs');
                $('#cardtabscuenta').addClass('card card-info card-tabs');
                $('#modal-cuenta').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#modal-cuenta').modal('show');

            });



            $('#create_ingreso').click(function() {
                $('#form-general')[0].reset();
                $('.card-title').text('Estas creando un nuevo ingreso');
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#form_result').html('');
                $('#card-drawel').removeClass('card card-warning');
                $('#card-drawel').addClass('card card-info');
                $('#cardtabspro').removeClass('card card-warning card-tabs');
                $('#cardtabspro').addClass('card card-info card-tabs');
                $('#cuenta').val('').trigger('change');
                $('#proveedor_id').val('').trigger('change');
                $('#tipoingreso').val('').trigger('change');
                $('#sede_ips').val('').trigger('change');

                $('#modal-ingreso').modal({
                    backdrop: 'static',
                    keyboard: false
                });



            });


            $(document).on('click', '.addingreso', function(event) {
                event.preventDefault();
                var url = '';
                var method = '';
                var text = '';

                if ($('#action').val() == 'Add') {
                    text = "Estás por crear una Orden de Compra"
                    url = "{{ route('compras_store2') }}";
                    method = 'post';
                }

                if ($('#action').val() == 'Edit') {
                    text = "Estás por actualizar un ingreso"
                    var updateid = $('#hidden_id').val();
                    url = "inventario/editentradas" + updateid;
                    method = 'put';
                }
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: text,
                    icon: "success",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                                icon: "info",
                                title: 'Espere por favor !',
                                html: 'Realizando la creacion..', // add html attribute if you want or remove
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                },
                            }),
                            $.ajax({
                                url: url,
                                method: method,
                                data: $('#form-general').serialize(),
                                dataType: "json",
                                success: function(data) {
                                    if (data.success == 'ok') {
                                        $('#form-general')[0].reset();
                                        $('#modal-ingreso').modal('hide');
                                        $('#ingresos').DataTable().ajax.reload();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'ingreso creado correctamente',
                                            showConfirmButton: false,
                                            timer: 1500

                                        })
                                        // Manteliviano.notificaciones('cliente creado correctamente', 'Sistema Ventas', 'success');

                                    } else if (data.success == 'ok1') {
                                        $('#form-general')[0].reset();
                                        $('#modal-ingreso').modal('hide');
                                        $('#ingresos').DataTable().ajax.reload();
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'ingreso actualizado correctamente',
                                            showConfirmButton: false,
                                            timer: 1500

                                        })
                                        // Manteliviano.notificaciones('cliente actualizado correctamente', 'Sistema Ventas', 'success');

                                    } else if (data.errors != null) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: data.errors,
                                            showConfirmButton: false,
                                            timer: 3000
                                        })
                                    }
                                }


                            }).fail(function(jqXHR, textStatus, errorThrown) {

                                if (jqXHR.status === 422) {

                                    var error = jqXHR.responseJSON;

                                    $.each(error, function(i, items) {

                                        var errores = [];
                                        errores.push(items.numeroingreso + '<br>');
                                        errores.push(items.tipoingreso + '<br>');
                                        errores.push(items.formadepago + '<br>');
                                        errores.push(items.fechadeingreso + '<br>');
                                        errores.push(items.totalingreso + '<br>');
                                        errores.push(items.observacion + '<br>');
                                        errores.push(items.cuenta_id + '<br>');
                                        errores.push(items.user_id + '<br>');
                                        errores.push(items.proveedor_id + '<br>');


                                        console.log(errores);

                                        var filtered = errores.filter(function(el) {
                                            return el != "undefined<br>";
                                        });

                                        console.log(filtered);
                                        Swal.fire({
                                            icon: 'error',
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


            // Edición de proveedor

            $(document).on('click', '.edit_ingreso', function() {

                $('#form-general')[0].reset();
                var id = $(this).attr('id');

                $.ajax({
                    url: "editingresos/" + id,
                    dataType: "json",
                    success: function(data) {


                        // Primer form de información empleado
                        $('#tipodocumento').val(data.proveedor.tipodocumento);
                        $('#documento').val(data.proveedor.documento);
                        $('#nombre').val(data.proveedor.nombre);
                        $('#telefono').val(data.proveedor.telefono);
                        $('#direccion').val(data.proveedor.direccion);
                        $('#correo').val(data.proveedor.correo);

                        var newpais = new Option(data.proveedor.pais, data.proveedor.pais, true,
                            true);
                        $('#pais').append(newpais).trigger('change');

                        var newdpto = new Option(data.proveedor.dpto, data.proveedor.dpto, true,
                            true);
                        $('#dpto').append(newdpto).trigger('change');


                        var newcity = new Option(data.proveedor.ciudad, data.proveedor.ciudad,
                            true,
                            true);
                        $('#ciudad').append(newcity).trigger('change');






                        $('#hidden_id').val(id)
                        $('.card-title').text("Editando proveedor: " + data.proveedor.nombre +
                            "-" + data.proveedor.documento);
                        $('#card-drawel').removeClass('card card-info');
                        $('#card-drawel').addClass('card card-warning');
                        $('#cardtabspro').removeClass('card card-info card-tabs');
                        $('#cardtabspro').addClass('card card-warning card-tabs');
                        $('#action_button').val('Editar').removeClass('btn-sucess')
                        $('#action_button').addClass('btn-danger')
                        $('#action_button').val('Edit');
                        $('#action').val('Edit');
                        $('#modal-proveedor').modal('show');

                    },



                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 403) {

                        Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                            'Sistema cuentas por pagar', 'warning');

                    }
                });

            });







        });


        // Agregar una cuenta



        $(document).on('click', '.addcuenta', function(event) {
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';

            if ($('#action').val() == 'Add') {
                text = "Estás por crear una cuenta"
                url = "{{ route('proveedores_store2') }}";
                method = 'post';
            }

            if ($('#action').val() == 'Edit') {
                text = "Estás por actualizar una cuenta"
                var updateid = $('#hidden_id').val();
                url = "editproveedores/" + updateid;
                method = 'put';
            }
            Swal.fire({
                    icon: "info",
                    title: 'Espere por favor !',
                    html: 'Realizando la programación..', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),
                $.ajax({
                    url: url,
                    method: method,
                    data: $('#form-generalc').serialize(),
                    dataType: "json",
                    success: function(data) {
                        if (data.success == 'ok') {
                            $('#form-generalc')[0].reset();
                            $('#modal-cuenta').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Cuenta creada correctamente',
                                showConfirmButton: false,
                                timer: 1500

                            })
                            // Manteliviano.notificaciones('cliente creado correctamente', 'Sistema Ventas', 'success');

                        } else if (data.success == 'ok1') {
                            $('#form-generalc')[0].reset();
                            $('#modal-cuenta').modal('hide');
                            Swal.fire({
                                icon: 'warning',
                                title: 'Cuenta actualizado correctamente',
                                showConfirmButton: false,
                                timer: 1500

                            })
                            // Manteliviano.notificaciones('cliente actualizado correctamente', 'Sistema Ventas', 'success');

                        } else if (data.errors != null) {
                            Swal.fire({
                                icon: 'error',
                                title: data.errors,
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    }


                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 422) {

                        var error = jqXHR.responseJSON;

                        $.each(error, function(i, items) {

                            var errores = [];
                            errores.push(items.nombrecuenta + '<br>');
                            errores.push(items.tipodecuenta + '<br>');
                            errores.push(items.observacion + '<br>');
                            errores.push(items.sede_id + '<br>');
                            errores.push(items.user_id + '<br>');


                            console.log(errores);

                            var filtered = errores.filter(function(el) {
                                return el != "undefined<br>";
                            });

                            console.log(filtered);
                            Swal.fire({
                                icon: 'error',
                                title: 'El formulario contiene errores',
                                html: filtered,
                                showConfirmButton: true,
                                //timer: 1500
                            })


                            //Manteliviano.notificaciones(items, 'Sistema Ventas', 'warning');

                        });
                    }
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

