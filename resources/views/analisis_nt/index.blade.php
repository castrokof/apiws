@extends('layouts.app')

@section('titulo')
Análisis NT - Gestión de Medicamentos por Contrato
@endsection

@section("styles")
<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<style>
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .btn-import {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border: none;
        color: #333;
    }
    .btn-import:hover {
        background: linear-gradient(135deg, #fcb69f 0%, #ffecd2 100%);
        color: #333;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Mensajes de éxito y error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Card Principal -->
            <div class="card shadow-lg border-0">
                <div class="card-header card-header-custom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Análisis NT - Medicamentos por Contrato
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group" role="group">
                                <a href="{{ route('analisis-nt.create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Nuevo Registro
                                </a>
                                <button type="button" class="btn btn-import btn-sm" data-toggle="modal" data-target="#modalImportExcel">
                                    <i class="fas fa-file-excel mr-1"></i> Importar Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Tabla de datos -->
                    <div class="table-responsive">
                        <table id="analisisNtTable" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Código Cliente</th>
                                    <th>Código Medcol</th>
                                    <th>Agrupador</th>
                                    <th>Nombre</th>
                                    <th>CUMS</th>
                                    <th>Expediente</th>
                                    <th>Valor Unitario</th>
                                    <th>Frecuencia Uso</th>
                                    <th>Contrato</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargan via DataTables AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para importar Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header card-header-custom">
                <h5 class="modal-title">
                    <i class="fas fa-file-excel mr-2"></i>Importar desde Excel
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('analisis-nt.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle mr-1"></i> Formato del archivo:</h6>
                        <small>
                            El archivo debe tener las siguientes columnas en orden:<br>
                            <strong>Código Cliente | Código Medcol | Agrupador | Nombre | CUMS | Expediente | Valor Unitario | Frecuencia Uso | Contrato</strong>
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="archivo_excel">Seleccionar archivo Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control-file" id="archivo_excel" name="archivo_excel" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">Tamaño máximo: 10MB</small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <small>
                            <strong>Nota:</strong> Los registros con la misma combinación de Código Medcol, CUMS y Contrato serán actualizados. Los nuevos se insertarán.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload mr-1"></i>Importar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section("scriptsPlugins")
<script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>

<script>
$(document).ready(function(){
    // Configuración de idioma en español para DataTables
    var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
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
        }
    };

    // Inicializar DataTable
    $('#analisisNtTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('analisis-nt.datatable') }}",
            type: "GET"
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'codigo_cliente', name: 'codigo_cliente', defaultContent: '-'},
            {data: 'codigo_medcol', name: 'codigo_medcol'},
            {data: 'agrupador', name: 'agrupador', defaultContent: '-'},
            {data: 'nombre', name: 'nombre'},
            {data: 'cums', name: 'cums'},
            {data: 'expediente', name: 'expediente', defaultContent: '-'},
            {
                data: 'valor_unitario', 
                name: 'valor_unitario',
                render: function(data) {
                    return data ? '$' + parseFloat(data).toLocaleString('es-CO', {minimumFractionDigits: 2}) : '-';
                }
            },
            {data: 'frecuencia_uso', name: 'frecuencia_uso', defaultContent: '-'},
            {data: 'contrato', name: 'contrato'},
            {
                data: 'id',
                name: 'acciones',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/analisis-nt/${data}" class="btn btn-info btn-sm" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/analisis-nt/${data}/edit" class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="eliminar(${data})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: idioma_espanol,
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            }
        ]
    });

    // Función para eliminar registro
    window.eliminar = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Crear formulario para enviar DELETE
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '/analisis-nt/' + id
                });
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                $('body').append(form);
                form.submit();
            }
        });
    };
});
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection