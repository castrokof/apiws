@extends('layouts.admin')

@section('title', 'Gestión de Saldos - MEDCOL6')

@section('styles')
<!-- DataTables CSS -->
<link href="{{ asset('assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('assets/lte/plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-boxes"></i> Gestión de Saldos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item">MEDCOL6</li>
                    <li class="breadcrumb-item active">Saldos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Card Principal -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="card-title">
                            <i class="fas fa-pills"></i> Inventario de Medicamentos - MEDCOL6
                        </h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-success btn-sm" id="btn-sincronizar">
                            <i class="fas fa-sync"></i> Sincronizar Saldos
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" id="btn-probar-api">
                            <i class="fas fa-vials"></i> Probar API
                        </button>
                        <button type="button" class="btn btn-info btn-sm" id="btn-estadisticas">
                            <i class="fas fa-chart-bar"></i> Estadísticas
                        </button>
                    </div>
                </div>
            </div>
                <div class="card-body">
                    <!-- Panel de Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card card-secondary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="filtro-deposito">Depósito:</label>
                                            <select class="form-control" id="filtro-deposito">
                                                <option value="">Todos los depósitos</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="filtro-grupo">Grupo:</label>
                                            <select class="form-control" id="filtro-grupo">
                                                <option value="">Todos los grupos</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="filtro-vencimiento">Estado Vencimiento:</label>
                                            <select class="form-control" id="filtro-vencimiento">
                                                <option value="">Todos</option>
                                                <option value="vigente">Vigente</option>
                                                <option value="proximo_vencer">Próximo a vencer</option>
                                                <option value="vencido">Vencido</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="filtro-buscar">Buscar:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="filtro-buscar" 
                                                       placeholder="Código, nombre o CUMS">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" id="btn-buscar">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="filtro-con-saldo" value="1">
                                                <label class="form-check-label" for="filtro-con-saldo">
                                                    Solo con saldo
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-9 text-right">
                                            <button type="button" class="btn btn-secondary btn-sm" id="btn-limpiar-filtros">
                                                <i class="fas fa-eraser"></i> Limpiar Filtros
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm" id="btn-aplicar-filtros">
                                                <i class="fas fa-filter"></i> Aplicar Filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Estadísticas Rápidas -->
                    <div class="row mb-3" id="estadisticas-panel" style="display: none;">
                        <div class="col-md-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Estadísticas Rápidas</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info"><i class="fas fa-boxes"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Total Productos</span>
                                                    <span class="info-box-number" id="stat-total-productos">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Con Saldo</span>
                                                    <span class="info-box-number" id="stat-con-saldo">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Próximos a Vencer</span>
                                                    <span class="info-box-number" id="stat-proximos-vencer">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Vencidos</span>
                                                    <span class="info-box-number" id="stat-vencidos">0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Saldos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-saldos">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>CUMS</th>
                                    <th>Depósito</th>
                                    <th>Grupo</th>
                                    <th>Saldo</th>
                                    <th>Valor Total</th>
                                    <th>Fecha Venc.</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Ver Detalles -->
        <div class="modal fade" id="modal-detalle-saldo" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="detalleModalLabel">
                            <i class="fas fa-info-circle"></i> Detalle del Producto
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="detalle-contenido">
                        <!-- Contenido será cargado dinámicamente -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('scripts')
<!-- DataTables Plugins -->
<script src="{{ asset('assets/lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Configurar CSRF token para AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    
    // Debug de errores de AJAX
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        console.error('AJAX Error:', {
            url: settings.url,
            status: xhr.status,
            statusText: xhr.statusText,
            responseText: xhr.responseText,
            error: thrownError
        });
    });
    
    // Variables globales
    let tablaSaldos;

    // Inicializar DataTable
    function inicializarTabla() {
        tablaSaldos = $('#tabla-saldos').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("medcol6.saldos.data") }}',
                type: 'GET',
                data: function(d) {
                    d.deposito = $('#filtro-deposito').val();
                    d.grupo = $('#filtro-grupo').val();
                    d.estado_vencimiento = $('#filtro-vencimiento').val();
                    d.con_saldo = $('#filtro-con-saldo').is(':checked') ? '1' : '0';
                    d.buscar = $('#filtro-buscar').val();
                },
                error: function(xhr, error, code) {
                    console.error('Error en DataTables:', error, code);
                    console.error('Response:', xhr.responseText);
                    Swal.fire('Error', 'Error al cargar los datos: ' + (xhr.responseJSON?.message || error), 'error');
                }
            },
            columns: [
                { data: 'codigo', name: 'codigo' },
                { data: 'nombre', name: 'nombre' },
                { data: 'cums', name: 'cums' },
                { data: 'nombre_deposito', name: 'nombre_deposito' },
                { data: 'nombre_grupo', name: 'nombre_grupo' },
                { data: 'saldo_formatted', name: 'saldo', orderable: true },
                { data: 'total_formatted', name: 'total', orderable: true },
                { data: 'fecha_vencimiento_formatted', name: 'fecha_vencimiento' },
                { data: 'estado_vencimiento', name: 'estado_vencimiento', orderable: false },
                { data: 'accion', name: 'accion', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']], // Ordenar por nombre inicialmente
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                processing: "Procesando...",
                loadingRecords: "Cargando...",
                emptyTable: "No hay datos disponibles en la tabla",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            pageLength: 25,
            responsive: true,
            drawCallback: function(settings) {
                console.log('DataTable dibujada con', settings.json ? settings.json.recordsTotal : 0, 'registros');
            }
        });
    }

    // Cargar opciones de filtros
    function cargarOpcionesFiltros() {
        console.log('Cargando opciones de filtros...');
        $.get('{{ route("medcol6.saldos.filtros") }}')
            .done(function(response) {
                console.log('Respuesta de filtros:', response);
                if (response.success) {
                    // Cargar depósitos
                    const depositoSelect = $('#filtro-deposito');
                    depositoSelect.empty().append('<option value="">Todos los depósitos</option>');
                    
                    if (response.data.depositos && response.data.depositos.length > 0) {
                        response.data.depositos.forEach(function(deposito) {
                            const value = deposito.value || deposito.deposito;
                            const label = deposito.label || deposito.nombre_deposito || value;
                            depositoSelect.append(`<option value="${value}">${label}</option>`);
                        });
                        console.log('Depósitos cargados:', response.data.depositos.length);
                    }

                    // Cargar grupos
                    const grupoSelect = $('#filtro-grupo');
                    grupoSelect.empty().append('<option value="">Todos los grupos</option>');
                    
                    if (response.data.grupos && response.data.grupos.length > 0) {
                        response.data.grupos.forEach(function(grupo) {
                            const value = grupo.value || grupo.grupo;
                            const label = grupo.label || grupo.nombre_grupo || `Grupo ${value}`;
                            grupoSelect.append(`<option value="${value}">${label}</option>`);
                        });
                        console.log('Grupos cargados:', response.data.grupos.length);
                    }
                } else {
                    console.error('Error en respuesta de filtros:', response.message);
                    Swal.fire('Error', response.message || 'Error al cargar filtros', 'error');
                }
            })
            .fail(function(xhr, status, error) {
                console.error('Error en petición de filtros:', {xhr, status, error});
                Swal.fire('Error', 'No se pudieron cargar las opciones de filtros: ' + error, 'error');
            });
    }

    // Cargar estadísticas
    function cargarEstadisticas() {
        $.get('{{ route("medcol6.saldos.estadisticas") }}')
            .done(function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#stat-total-productos').text(data.resumen.total_productos.toLocaleString());
                    $('#stat-con-saldo').text(data.resumen.productos_con_saldo.toLocaleString());
                    $('#stat-proximos-vencer').text(data.resumen.proximos_vencer.toLocaleString());
                    $('#stat-vencidos').text(data.resumen.vencidos.toLocaleString());
                }
            });
    }

    // Event Listeners
    $('#btn-sincronizar').click(function() {
        const btn = $(this);
        const originalText = btn.html();
        
        Swal.fire({
            title: '¿Sincronizar Saldos?',
            text: 'Este proceso puede tomar varios minutos. ¿Desea continuar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, sincronizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sincronizando...');
                
                $.post('{{ route("medcol6.saldos.sincronizar") }}')
                    .done(function(response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success');
                            tablaSaldos.ajax.reload();
                            cargarEstadisticas();
                            cargarOpcionesFiltros();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    })
                    .fail(function(xhr) {
                        const message = xhr.responseJSON?.message || 'Error en la sincronización';
                        Swal.fire('Error', message, 'error');
                    })
                    .always(function() {
                        btn.prop('disabled', false).html(originalText);
                    });
            }
        });
    });

    $('#btn-probar-api').click(function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Probando...');
        
        $.post('{{ route("medcol6.saldos.probar-api") }}')
            .done(function(response) {
                if (response.success) {
                    const diagnostico = response.diagnostico;
                    let mensaje = `✅ Prueba exitosa\n\n`;
                    mensaje += `Status Code: ${diagnostico.status_code}\n`;
                    mensaje += `Respuesta es JSON: ${diagnostico.is_json ? 'Sí' : 'No'}\n`;
                    mensaje += `Tamaño respuesta: ${diagnostico.body_length} bytes\n`;
                    
                    if (diagnostico.json_keys) {
                        mensaje += `Claves JSON: ${diagnostico.json_keys.join(', ')}\n`;
                    }
                    
                    if (diagnostico.data_count) {
                        mensaje += `Registros encontrados: ${diagnostico.data_count}\n`;
                    }
                    
                    if (diagnostico.first_item_keys) {
                        mensaje += `Campos por registro: ${diagnostico.first_item_keys.join(', ')}\n`;
                    }
                    
                    Swal.fire({
                        title: 'Diagnóstico de API',
                        text: mensaje,
                        icon: 'success',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            })
            .fail(function(xhr) {
                const message = xhr.responseJSON?.message || 'Error en la prueba de API';
                Swal.fire('Error', message, 'error');
            })
            .always(function() {
                btn.prop('disabled', false).html(originalText);
            });
    });

    $('#btn-estadisticas').click(function() {
        const panel = $('#estadisticas-panel');
        if (panel.is(':visible')) {
            panel.hide();
            $(this).removeClass('btn-warning').addClass('btn-info');
        } else {
            panel.show();
            $(this).removeClass('btn-info').addClass('btn-warning');
            cargarEstadisticas();
        }
    });

    $('#btn-aplicar-filtros, #btn-buscar').click(function() {
        tablaSaldos.ajax.reload();
    });

    $('#btn-limpiar-filtros').click(function() {
        $('#filtro-deposito').val('');
        $('#filtro-grupo').val('');
        $('#filtro-vencimiento').val('');
        $('#filtro-con-saldo').prop('checked', false);
        $('#filtro-buscar').val('');
        console.log('Filtros limpiados');
        tablaSaldos.ajax.reload();
    });

    // Permitir buscar con Enter
    $('#filtro-buscar').keypress(function(e) {
        if (e.which === 13) {
            tablaSaldos.ajax.reload();
        }
    });

    // Ver detalle de saldo
    $(document).on('click', '.ver-detalle', function() {
        const id = $(this).data('id');
        
        $.get(`{{ url('medcol6/saldos') }}/${id}`)
            .done(function(response) {
                if (response.success) {
                    const saldo = response.data;
                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Información Básica</strong></h6>
                                <p><strong>Código:</strong> ${saldo.codigo}</p>
                                <p><strong>CUMS:</strong> ${saldo.cums || 'N/A'}</p>
                                <p><strong>Nombre:</strong> ${saldo.nombre}</p>
                                <p><strong>Marca:</strong> ${saldo.marca || 'N/A'}</p>
                                <p><strong>Línea:</strong> ${saldo.linea || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Inventario</strong></h6>
                                <p><strong>Saldo:</strong> ${parseFloat(saldo.saldo).toLocaleString()}</p>
                                <p><strong>Costo Unitario:</strong> $${parseFloat(saldo.costo_unitario).toLocaleString()}</p>
                                <p><strong>Valor Total:</strong> $${parseFloat(saldo.total).toLocaleString()}</p>
                                <p><strong>Fecha Vencimiento:</strong> ${saldo.fecha_vencimiento || 'N/A'}</p>
                                <p><strong>Invima:</strong> ${saldo.invima || 'N/A'}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Ubicación</strong></h6>
                                <p><strong>IPS:</strong> ${saldo.nombre_ips || saldo.ips}</p>
                                <p><strong>Depósito:</strong> ${saldo.nombre_deposito || saldo.deposito}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Clasificación</strong></h6>
                                <p><strong>Grupo:</strong> ${saldo.nombre_grupo || saldo.grupo}</p>
                                <p><strong>Subgrupo:</strong> ${saldo.nombre_subgrupo || saldo.subgrupo}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h6><strong>Información Adicional</strong></h6>
                                <p><strong>Agrupador:</strong> ${saldo.agrupador || 'N/A'}</p>
                                <p><strong>Fecha Saldo:</strong> ${saldo.fecha_saldo}</p>
                                <p><strong>Última Actualización:</strong> ${saldo.updated_at}</p>
                            </div>
                        </div>
                    `;
                    
                    $('#detalle-contenido').html(html);
                    $('#modal-detalle-saldo').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo cargar el detalle', 'error');
                }
            })
            .fail(function() {
                Swal.fire('Error', 'Error al obtener el detalle', 'error');
            });
    });

    // Debug: verificar dependencias
    console.log('=== INICIO DEBUG SALDOS ===');
    console.log('jQuery version:', $().jquery);
    console.log('DataTables available:', typeof $.fn.dataTable !== 'undefined');
    console.log('SweetAlert available:', typeof Swal !== 'undefined');
    
    // Verificar que los botones existen en el DOM
    console.log('Verificando botones:');
    console.log('btn-sincronizar exists:', $('#btn-sincronizar').length > 0);
    console.log('btn-probar-api exists:', $('#btn-probar-api').length > 0);
    console.log('btn-estadisticas exists:', $('#btn-estadisticas').length > 0);
    console.log('btn-aplicar-filtros exists:', $('#btn-aplicar-filtros').length > 0);
    console.log('btn-limpiar-filtros exists:', $('#btn-limpiar-filtros').length > 0);
    
    // Test simple click handler
    $('#btn-sincronizar').on('click', function() {
        console.log('¡Botón sincronizar clickeado!');
    });
    
    $('#btn-probar-api').on('click', function() {
        console.log('¡Botón probar API clickeado!');
    });
    
    $('#btn-estadisticas').on('click', function() {
        console.log('¡Botón estadísticas clickeado!');
    });
    
    // Inicializar componentes
    console.log('Iniciando inicialización...');
    try {
        inicializarTabla();
        console.log('Tabla inicializada');
        cargarOpcionesFiltros();
        console.log('Filtros cargados');
    } catch (error) {
        console.error('Error durante la inicialización:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Error durante la inicialización: ' + error.message, 'error');
        } else {
            alert('Error durante la inicialización: ' + error.message);
        }
    }
    
    console.log('=== FIN INICIALIZACIÓN ===');
});
</script>
@endsection