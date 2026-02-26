@extends('layouts.admin')

@section('title', 'Rotación de Medicamentos')

@section('styles')
<link href="{{ asset('assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<style>
    /* ── Tabla ── */
    #rotacion-table th,
    #rotacion-table td {
        white-space: nowrap;
        font-size: 0.82rem;
        vertical-align: middle;
    }
    #rotacion-table td.mes-col {
        text-align: right;
        min-width: 52px;
    }
    #rotacion-table td.num-col {
        text-align: right;
    }
    /* Tabla scroll horizontal gestionado por Bootstrap table-responsive */
    #rotacion-table {
        min-width: 1400px;
    }

    /* Faltante destacado */
    .faltante-alto   { background-color: #f8d7da !important; color: #721c24; font-weight: 700; }
    .faltante-medio  { background-color: #fff3cd !important; color: #856404; font-weight: 600; }
    .faltante-cero   { color: #155724; }

    /* Celdas nulas */
    .mes-null { color: #adb5bd; }

    /* Loading overlay */
    #loading-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(255,255,255,.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    #loading-overlay.active { display: flex; }
</style>
@endsection

@section('content')

{{-- ── Cabecera ── --}}
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-sync-alt text-primary mr-2"></i>
                    Rotación de Medicamentos
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item">API Medcol</li>
                    <li class="breadcrumb-item active">Rotación</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- ── Contenido principal ── --}}
<section class="content">
<div class="container-fluid">

    {{-- ── Filtros ── --}}
    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end">

                {{-- Año --}}
                <div class="col-md-2 col-sm-6">
                    <label for="sel-anio" class="font-weight-bold">Año</label>
                    <select id="sel-anio" class="form-control form-control-sm filtro-auto">
                        <option value="">— Todos los años —</option>
                        @foreach($anios as $anio)
                            <option value="{{ $anio }}" {{ $anio == $anioActual ? 'selected' : '' }}>
                                {{ $anio }}
                            </option>
                        @endforeach
                        @if(empty($anios))
                            <option value="{{ $anioActual }}" selected>{{ $anioActual }}</option>
                        @endif
                    </select>
                </div>

                {{-- Depósito --}}
                <div class="col-md-3 col-sm-6">
                    <label for="sel-deposito" class="font-weight-bold">Depósito / Farmacia</label>
                    <select id="sel-deposito" class="form-control form-control-sm filtro-auto">
                        <option value="">— Todos —</option>
                        @foreach($depositos as $dep)
                            <option value="{{ $dep->deposito }}">
                                {{ $dep->deposito }}{{ $dep->nombre_deposito ? ' – ' . $dep->nombre_deposito : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Agrupador --}}
                <div class="col-md-3 col-sm-6 mt-2 mt-md-0">
                    <label for="sel-agrupador" class="font-weight-bold">Agrupador (Código base)</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="sel-agrupador"
                               class="form-control form-control-sm"
                               placeholder="Ej: M000673"
                               autocomplete="off"
                               maxlength="50">
                        <div class="input-group-append">
                            <button type="button" id="btn-limpiar-agrupador"
                                    class="btn btn-outline-secondary btn-sm"
                                    title="Limpiar agrupador">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">Ingrese el código y presione <kbd>Enter</kbd></small>
                </div>

                {{-- Botones --}}
                <div class="col-md-3 col-sm-6 mt-2 mt-md-0">
                    <button id="btn-cargar" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-bar mr-1"></i> Cargar Datos
                    </button>
                    <button id="btn-limpiar" class="btn btn-secondary btn-sm ml-1">
                        <i class="fas fa-times mr-1"></i> Limpiar
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Tarjetas resumen ── --}}
    <div class="row" id="summary-cards" style="display:none !important;">
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary elevation-1">
                    <i class="fas fa-pills"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Medicamentos</span>
                    <span class="info-box-number" id="stat-total">—</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-boxes"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Con Saldo en Farmacia</span>
                    <span class="info-box-number" id="stat-con-saldo">—</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Con Cantidad Faltante</span>
                    <span class="info-box-number" id="stat-faltantes">—</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger elevation-1">
                    <i class="fas fa-calendar-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Año Analizado</span>
                    <span class="info-box-number" id="stat-anio">—</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabla ── --}}
    <div class="card card-outline card-info" id="card-tabla" style="display:none;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>
                Rotación mensual — <span id="lbl-anio-tabla">—</span>
            </h3>
            <div class="card-tools">
                <span class="badge badge-secondary" id="lbl-deposito-tabla"></span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="rotacion-table" class="table table-bordered table-striped table-hover mb-0"
                       style="width:100%">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>{{-- /container-fluid --}}
</section>

{{-- ── Modal Detalle Agrupador ── --}}
<div class="modal fade" id="modal-detalle" tabindex="-1" role="dialog" aria-labelledby="modal-detalle-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="modal-detalle-label">
                    <i class="fas fa-pills mr-2"></i>
                    Detalle — <span id="modal-agrupador-titulo"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                {{-- Subtítulo con nombre genérico --}}
                <div class="px-3 pt-3 pb-1">
                    <p class="mb-1 text-muted" id="modal-nombre-subtitulo"></p>
                </div>
                <div class="table-responsive px-3 pb-3">
                    <table id="detalle-table"
                           class="table table-bordered table-striped table-hover table-sm mb-0"
                           style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th style="min-width:220px">Medicamento</th>
                                <th>Farmacia</th>
                                <th class="text-right">Total Unidades</th>
                                <th class="text-right">Pacientes Únicos</th>
                            </tr>
                        </thead>
                        <tbody id="detalle-tbody"></tbody>
                        <tfoot>
                            <tr class="font-weight-bold bg-light">
                                <td colspan="3" class="text-right">Totales:</td>
                                <td class="text-right" id="detalle-total-unidades">—</td>
                                <td class="text-right" id="detalle-total-pacientes">—</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- Spinner interno del modal --}}
                <div id="modal-spinner" class="text-center py-4" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
                    <p class="mt-2 text-info">Cargando detalle…</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── Spinner overlay ── --}}
<div id="loading-overlay">
    <div class="text-center">
        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
        <p class="mt-2 font-weight-bold text-primary">Cargando datos…</p>
    </div>
</div>

@endsection

@section('scripts')
<!-- DataTables core + Bootstrap4 skin + Responsive + Buttons -->
<script src="{{ asset('assets/lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<!-- Dependencias para botones Excel y PDF -->
<script src="{{ asset('assets/lte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script>
$(function () {

    /* ── Config ──────────────────────────────────────────────── */
    const MESES_CORTOS   = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    const DATA_URL       = '{{ route("medcol6.rotacion.data") }}';
    const DETALLE_URL    = '{{ route("medcol6.rotacion.detalle") }}';

    let rotacionTable   = null;
    let datosActuales   = [];

    /* ── Construir thead plano y columnas de forma sincronizada ─
       DataTables requiere que cada <th> en el thead corresponda
       exactamente a una columna definida en `columns`. Construir
       ambas cosas juntas elimina cualquier desajuste.           */
    function buildTableConfig(anio) {
        const cols = [];
        const ths  = [];

        // Columnas fijas iniciales
        cols.push({ data: 'codigo',          className: 'font-weight-bold' });
        cols.push({ data: 'nombre_generico', render: (d) => d ? escapeHtml(d) : '<span class="text-muted">—</span>' });
        ths.push('<th>Código</th>');
        ths.push('<th style="min-width:200px">Medicamento</th>');

        // Columnas de meses (1–12)
        for (let m = 1; m <= 12; m++) {
            cols.push({
                data:           'mes_' + m,
                className:      'mes-col',
                defaultContent: '',
                render: function (d) {
                    if (d === null || d === undefined || d === '') return '<span class="mes-null">—</span>';
                    return parseInt(d).toLocaleString('es-CO');
                },
            });
            const anioLabel = anio ? `<br><small class="text-muted font-weight-normal">${anio}</small>` : '';
            ths.push(`<th class="text-center">${MESES_CORTOS[m - 1]}${anioLabel}</th>`);
        }

        // Columnas de métricas
        cols.push({
            data:      'promedio',
            className: 'num-col font-weight-bold text-info',
            render:    (d) => d !== null ? parseFloat(d).toLocaleString('es-CO', {minimumFractionDigits:1, maximumFractionDigits:1}) : '—',
        });
        cols.push({ data: 'rango',    className: 'num-col text-secondary' });
        cols.push({
            data:      'promedio_diario',
            className: 'num-col',
            render:    (d) => d !== null ? parseFloat(d).toLocaleString('es-CO', {minimumFractionDigits:1, maximumFractionDigits:1}) : '—',
        });
        cols.push({
            data:      'saldo_actual',
            className: 'num-col',
            render:    (d) => d !== null ? parseInt(d).toLocaleString('es-CO') : '—',
        });
        cols.push({
            data:      'faltante',
            className: 'num-col',
            render:    function (d) {
                if (d === null || d === undefined) return '—';
                const val = parseInt(d);
                if (val <= 0) return '<span class="faltante-cero">0</span>';
                const cls = val > 500 ? 'faltante-alto' : 'faltante-medio';
                return `<span class="${cls}">▲ ${val.toLocaleString('es-CO')}</span>`;
            },
        });
        ths.push('<th class="text-center">Promedio<br>Mensual</th>');
        ths.push('<th class="text-center">Rango<br>(min–max)</th>');
        ths.push('<th class="text-center">Prom.<br>Diario</th>');
        ths.push('<th class="text-center">Saldo<br>Actual</th>');
        ths.push('<th class="text-center">Cantidad<br>Faltante</th>');

        // Columna Acciones (fija al final)
        cols.push({
            data:       null,
            orderable:  false,
            searchable: false,
            className:  'text-center',
            render: function (d, type, row) {
                const nombre = escapeHtml(row.nombre_generico || '');
                return `<button class="btn btn-info btn-xs btn-detalle"
                                data-agrupador="${escapeHtml(row.agrupador)}"
                                data-nombre="${nombre}"
                                title="Ver detalle del agrupador">
                            <i class="fas fa-eye"></i>
                        </button>`;
            },
        });
        ths.push('<th class="text-center">Acciones</th>');

        return { cols, ths };
    }

    /* ── Inicializar / re-inicializar DataTable ─────────────── */
    function initTable(data, anio) {
        // Destruir instancia previa
        if (rotacionTable) {
            rotacionTable.destroy();
            rotacionTable = null;
        }

        // Reconstruir thead con exactamente los mismos <th> que las columnas
        const { cols, ths } = buildTableConfig(anio);
        $('#rotacion-table thead').html('<tr class="thead-dark">' + ths.join('') + '</tr>');
        $('#rotacion-table tbody').empty();

        rotacionTable = $('#rotacion-table').DataTable({
            data:       data,
            columns:    cols,
            pageLength: 25,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'Todos']],
            order:      [[1, 'asc']],
            language: {
                emptyTable:     'No hay datos para el período seleccionado.',
                info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:      'Mostrando 0 a 0 de 0 registros',
                infoFiltered:   '(filtrado de _MAX_ registros totales)',
                lengthMenu:     'Mostrar _MENU_ registros',
                loadingRecords: 'Cargando...',
                processing:     'Procesando...',
                search:         'Buscar:',
                zeroRecords:    'No se encontraron resultados.',
                paginate: {
                    first:    'Primero',
                    last:     'Último',
                    next:     'Siguiente',
                    previous: 'Anterior',
                },
            },
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            buttons: [
                {
                    extend:        'excelHtml5',
                    text:          '<i class="fas fa-file-excel mr-1"></i>Excel',
                    className:     'btn btn-success btn-sm',
                    exportOptions: { columns: ':visible' },
                    title:         'Rotación de Medicamentos ' + anio,
                },
                {
                    extend:        'pdfHtml5',
                    text:          '<i class="fas fa-file-pdf mr-1"></i>PDF',
                    className:     'btn btn-danger btn-sm',
                    orientation:   'landscape',
                    pageSize:      'A3',
                    exportOptions: { columns: ':visible' },
                    title:         'Rotación de Medicamentos ' + anio,
                },
                {
                    extend:        'csvHtml5',
                    text:          '<i class="fas fa-file-csv mr-1"></i>CSV',
                    className:     'btn btn-secondary btn-sm',
                    exportOptions: { columns: ':visible' },
                },
            ],
            rowCallback: function (row, rowData) {
                const faltante = parseInt(rowData.faltante) || 0;
                if (faltante > 500) {
                    $(row).addClass('table-danger');
                } else if (faltante > 0) {
                    $(row).addClass('table-warning');
                }
            },
        });
    }

    /* ── Actualizar tarjetas de resumen ─────────────────────── */
    function updateSummaryCards(data, anio) {
        const total      = data.length;
        const conSaldo   = data.filter(r => parseInt(r.saldo_actual) > 0).length;
        const faltantes  = data.filter(r => parseInt(r.faltante)     > 0).length;

        $('#stat-total').text(total.toLocaleString('es-CO'));
        $('#stat-con-saldo').text(conSaldo.toLocaleString('es-CO'));
        $('#stat-faltantes').text(faltantes.toLocaleString('es-CO'));
        $('#stat-anio').text(anio || 'Todos');

        $('#summary-cards').css('display', 'flex');
        // Forzar re-display correcto en Bootstrap
        $('#summary-cards').show();
    }

    /* ── Cargar datos desde servidor ────────────────────────── */
    function cargarDatos() {
        const anio      = $('#sel-anio').val();
        const deposito  = $('#sel-deposito').val();
        const agrupador = $('#sel-agrupador').val();

        $('#loading-overlay').addClass('active');
        $('#card-tabla').hide();
        $('#summary-cards').hide();

        $.ajax({
            url:      DATA_URL,
            method:   'GET',
            data:     { anio: anio, deposito: deposito, agrupador: agrupador },
            dataType: 'json',
            success: function (resp) {
                const data = resp.data || [];
                datosActuales = data;

                if (data.length === 0) {
                    toastr.info('No se encontraron datos de dispensación para el período seleccionado.', 'Sin datos');
                    $('#loading-overlay').removeClass('active');
                    return;
                }

                // Etiquetas encabezado
                $('#lbl-anio-tabla').text(anio || 'Todos los años');
                const depLabel = deposito ? deposito : 'Todos los depósitos';
                $('#lbl-deposito-tabla').text(depLabel);

                initTable(data, anio);
                updateSummaryCards(data, anio);

                $('#card-tabla').fadeIn(200);
                $('#loading-overlay').removeClass('active');

                toastr.success(
                    `${data.length} medicamentos cargados correctamente.`,
                    'Datos listos',
                    { timeOut: 3000 }
                );
            },
            error: function (xhr) {
                $('#loading-overlay').removeClass('active');
                const msg = xhr.responseJSON?.message || 'Error al consultar los datos.';
                toastr.error(msg, 'Error');
                console.error('Error cargando rotación:', xhr);
            },
        });
    }

    /* ── Eventos ─────────────────────────────────────────────── */
    $('#btn-cargar').on('click', cargarDatos);

    // Auto-aplicar al cambiar selects (año y depósito)
    $(document).on('change', '.filtro-auto', function () {
        cargarDatos();
    });

    // Agrupador: debounce al escribir + Enter inmediato
    let _debAgrupador = null;
    $('#sel-agrupador').on('input', function () {
        clearTimeout(_debAgrupador);
        _debAgrupador = setTimeout(cargarDatos, 700);
    });
    $('#sel-agrupador').on('keydown', function (e) {
        if (e.key === 'Enter') {
            clearTimeout(_debAgrupador);
            cargarDatos();
        }
    });

    // Botón × del campo agrupador
    $('#btn-limpiar-agrupador').on('click', function () {
        if ($('#sel-agrupador').val() !== '') {
            $('#sel-agrupador').val('').trigger('input');
        }
    });

    $('#btn-limpiar').on('click', function () {
        if (rotacionTable) {
            rotacionTable.destroy();
            rotacionTable = null;
        }
        $('#rotacion-table tbody').empty();
        $('#card-tabla').hide();
        $('#summary-cards').hide();
        datosActuales = [];
        // Resetear filtros
        $('#sel-deposito').val('');
        $('#sel-agrupador').val('');
        // Recargar datos sin filtros
        cargarDatos();
    });

    // Cargar automáticamente al abrir la página con el año actual
    cargarDatos();

    /* ── Modal detalle ───────────────────────────────────────── */
    $(document).on('click', '.btn-detalle', function () {
        const agrupador = $(this).data('agrupador');
        const nombre    = $(this).data('nombre');
        const anio      = $('#sel-anio').val();
        const deposito  = $('#sel-deposito').val();

        // Preparar modal
        $('#modal-agrupador-titulo').text(agrupador);
        $('#modal-nombre-subtitulo').text(nombre);
        $('#detalle-tbody').empty();
        $('#detalle-total-unidades').text('—');
        $('#detalle-total-pacientes').text('—');
        $('#modal-spinner').show();
        $('#detalle-table').hide();

        $('#modal-detalle').modal('show');

        $.ajax({
            url:      DETALLE_URL,
            method:   'GET',
            data:     { agrupador: agrupador, anio: anio, deposito: deposito },
            dataType: 'json',
            success: function (resp) {
                $('#modal-spinner').hide();
                const rows = resp.data || [];

                if (rows.length === 0) {
                    $('#detalle-tbody').html(
                        '<tr><td colspan="5" class="text-center text-muted">Sin registros para este agrupador.</td></tr>'
                    );
                    $('#detalle-table').show();
                    return;
                }

                let sumUnidades  = 0;
                let sumPacientes = 0;
                let html = '';

                rows.forEach(function (r) {
                    const unidades  = parseFloat(r.total_unidades)  || 0;
                    const pacientes = parseInt(r.total_pacientes)    || 0;
                    sumUnidades  += unidades;
                    sumPacientes += pacientes;

                    html += `<tr>
                        <td class="font-weight-bold">${escapeHtml(r.codigo)}</td>
                        <td>${r.nombre_generico ? escapeHtml(r.nombre_generico) : '<span class="text-muted">—</span>'}</td>
                        <td>${r.farmacia ? escapeHtml(r.farmacia) : '<span class="text-muted">—</span>'}</td>
                        <td class="text-right">${unidades.toLocaleString('es-CO', {minimumFractionDigits:0, maximumFractionDigits:2})}</td>
                        <td class="text-right">${pacientes.toLocaleString('es-CO')}</td>
                    </tr>`;
                });

                $('#detalle-tbody').html(html);
                $('#detalle-total-unidades').text(sumUnidades.toLocaleString('es-CO', {minimumFractionDigits:0, maximumFractionDigits:2}));
                $('#detalle-total-pacientes').text(sumPacientes.toLocaleString('es-CO'));
                $('#detalle-table').show();
            },
            error: function (xhr) {
                $('#modal-spinner').hide();
                const msg = xhr.responseJSON?.message || 'Error al cargar el detalle.';
                $('#detalle-tbody').html(
                    `<tr><td colspan="5" class="text-center text-danger">${escapeHtml(msg)}</td></tr>`
                );
                $('#detalle-table').show();
                console.error('Error detalle rotación:', xhr);
            },
        });
    });

    // Limpiar modal al cerrarse
    $('#modal-detalle').on('hidden.bs.modal', function () {
        $('#detalle-tbody').empty();
        $('#modal-spinner').hide();
        $('#detalle-table').show();
    });

    /* ── Utilidad: escapar HTML ──────────────────────────────── */
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

});
</script>
@endsection
