@extends('layouts.admin')

@section('title', 'Demand Driven – Medicamentos')

@section('styles')
<link href="{{ asset('assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<style>
/* ── Tabla ── */
#dd-table { min-width: 1300px; }
#dd-table th, #dd-table td {
    white-space: nowrap;
    font-size: 0.82rem;
    vertical-align: middle;
}
#dd-table td.num-col { text-align: right; }

/* ── Badges de estado ── */
.badge-critico    { background:#dc3545; color:#fff; }
.badge-reordenar  { background:#fd7e14; color:#fff; }
.badge-normal     { background:#28a745; color:#fff; }
.badge-sobrestock { background:#007bff; color:#fff; }
.badge-sin-dem    { background:#6c757d; color:#fff; }

/* ── Filas de tabla coloreadas ── */
tr.row-critico    td { background-color: rgba(220,53,69,.08)  !important; }
tr.row-reordenar  td { background-color: rgba(253,126,20,.08) !important; }
tr.row-sobrestock td { background-color: rgba(0,123,255,.05)  !important; }

/* ── Cards de resumen ── */
.dd-stat-card {
    border-radius: 8px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,.07);
}
.dd-stat-card .stat-icon {
    font-size: 1.8rem;
    width: 44px;
    text-align: center;
    flex-shrink: 0;
}
.dd-stat-card .stat-label { font-size: .75rem; color: #6c757d; }
.dd-stat-card .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1; }

/* ── Panel gráfico histórico ── */
#panel-historico {
    display: none;
    border-top: 3px solid #4e73df;
}
#panel-historico .metric-mini {
    border-radius: 6px;
    padding: 10px 14px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    text-align: center;
}
#panel-historico .metric-mini .metric-label { font-size: .72rem; color: #6c757d; }
#panel-historico .metric-mini .metric-value { font-size: 1.1rem; font-weight: 700; }

/* ── Spinner overlay ── */
#dd-loading {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,.65);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
#dd-loading.active { display: flex; }

/* ── Params card ── */
.param-chip {
    display: inline-block;
    background: #e9ecef;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: .8rem;
    margin: 2px;
}
.param-chip strong { color: #4e73df; }
</style>
@endsection

@section('content')

{{-- ── Cabecera ── --}}
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-brain text-primary mr-2"></i>
                    Demand Driven
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item">API Medcol</li>
                    <li class="breadcrumb-item active">Demand Driven</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

    {{-- ── Parámetros del modelo ── --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-header py-2">
            <h3 class="card-title" style="font-size:.85rem">
                <i class="fas fa-sliders-h mr-1 text-secondary"></i>
                Parámetros del modelo
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body py-2">
            <span class="param-chip"><strong>Lead Time (LT)</strong> {{ $params['lt'] }} días</span>
            <span class="param-chip"><strong>Factor Z</strong> {{ $params['z'] }} (95 %)</span>
            <span class="param-chip"><strong>Costo pedido (S)</strong> ${{ $params['s'] }}</span>
            <span class="param-chip"><strong>Costo holding (H)</strong> ${{ $params['h'] }} / unidad</span>
            <span class="param-chip text-muted">
                <strong>SS</strong> = Z × σ × √LT &nbsp;|&nbsp;
                <strong>ROP</strong> = d̄ × LT + SS &nbsp;|&nbsp;
                <strong>EOQ</strong> = √(2DS/H)
            </span>
        </div>
    </div>

    {{-- ── Filtros ── --}}
    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end">

                {{-- Año --}}
                <div class="col-md-2 col-sm-6">
                    <label for="dd-anio" class="font-weight-bold">Año</label>
                    <select id="dd-anio" class="form-control form-control-sm dd-filtro">
                        <option value="">— Todos los años —</option>
                        @foreach($anios as $anio)
                            <option value="{{ $anio }}" {{ $anio == $anioActual ? 'selected' : '' }}>
                                {{ $anio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Depósito --}}
                <div class="col-md-3 col-sm-6">
                    <label for="dd-deposito" class="font-weight-bold">Depósito / Bodega</label>
                    <select id="dd-deposito" class="form-control form-control-sm dd-filtro">
                        <option value="">— Todos —</option>
                        @foreach($depositos as $dep)
                            <option value="{{ $dep->deposito }}">
                                {{ $dep->deposito }}{{ $dep->nombre_deposito ? ' – '.$dep->nombre_deposito : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Agrupador --}}
                <div class="col-md-3 col-sm-6 mt-2 mt-md-0">
                    <label for="dd-agrupador" class="font-weight-bold">Agrupador (Código base)</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="dd-agrupador"
                               class="form-control form-control-sm"
                               placeholder="Ej: M000673"
                               autocomplete="off" maxlength="50">
                        <div class="input-group-append">
                            <button type="button" id="dd-limpiar-agrupador"
                                    class="btn btn-outline-secondary btn-sm" title="Limpiar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">Ingrese el código y presione <kbd>Enter</kbd></small>
                </div>

                {{-- Botones --}}
                <div class="col-md-3 col-sm-6 mt-2 mt-md-0">
                    <button id="dd-btn-cargar" class="btn btn-primary btn-sm">
                        <i class="fas fa-calculator mr-1"></i> Calcular
                    </button>
                    <button id="dd-btn-limpiar" class="btn btn-secondary btn-sm ml-1">
                        <i class="fas fa-times mr-1"></i> Limpiar
                    </button>
                    {{-- Filtro rápido por estado --}}
                    <div class="mt-2" id="filtros-estado" style="display:none;">
                        <small class="text-muted d-block mb-1">Filtrar por estado:</small>
                        <button class="btn btn-xs btn-estado btn-outline-secondary active" data-estado="">Todos</button>
                        <button class="btn btn-xs btn-estado" style="color:#dc3545;border-color:#dc3545" data-estado="CRITICO">Crítico</button>
                        <button class="btn btn-xs btn-estado" style="color:#fd7e14;border-color:#fd7e14" data-estado="REORDENAR">Reordenar</button>
                        <button class="btn btn-xs btn-estado" style="color:#28a745;border-color:#28a745" data-estado="NORMAL">Normal</button>
                        <button class="btn btn-xs btn-estado" style="color:#007bff;border-color:#007bff" data-estado="SOBRESTOCK">Sobrestock</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Tarjetas resumen ── --}}
    <div class="row mb-3" id="dd-summary" style="display:none !important;">
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-primary"><i class="fas fa-pills"></i></div>
                <div><div class="stat-label">Total ítems</div><div class="stat-value" id="s-total">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-danger"><i class="fas fa-exclamation-circle"></i></div>
                <div><div class="stat-label">Crítico</div><div class="stat-value text-danger" id="s-critico">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-warning"><i class="fas fa-cart-arrow-down"></i></div>
                <div><div class="stat-label">Reordenar</div><div class="stat-value text-warning" id="s-reordenar">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                <div><div class="stat-label">Normal</div><div class="stat-value text-success" id="s-normal">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-info"><i class="fas fa-warehouse"></i></div>
                <div><div class="stat-label">Sobrestock</div><div class="stat-value text-info" id="s-sobrestock">—</div></div>
            </div>
        </div>
    </div>

    {{-- ── Panel gráfico histórico ── --}}
    <div class="card card-outline mb-3" id="panel-historico">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-line text-primary mr-1"></i>
                Histórico: <span id="hist-titulo" class="font-weight-bold text-primary"></span>
            </h3>
            <div class="card-tools">
                <button type="button" id="hist-cerrar" class="btn btn-tool" title="Cerrar gráfico">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            {{-- Métricas del ítem seleccionado --}}
            <div class="row mb-3" id="hist-metrics">
                <div class="col-6 col-md-3 mb-2">
                    <div class="metric-mini">
                        <div class="metric-label">Stock de Seguridad (SS)</div>
                        <div class="metric-value text-warning" id="hm-ss">—</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="metric-mini">
                        <div class="metric-label">Punto de Reorden (ROP)</div>
                        <div class="metric-value text-danger" id="hm-rop">—</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="metric-mini">
                        <div class="metric-label">EOQ</div>
                        <div class="metric-value text-primary" id="hm-eoq">—</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="metric-mini">
                        <div class="metric-label">Saldo actual / Días cob.</div>
                        <div class="metric-value text-success" id="hm-saldo">—</div>
                    </div>
                </div>
            </div>
            {{-- Gráfico --}}
            <div style="position:relative; height:280px;">
                <canvas id="chart-historico"></canvas>
                <div id="hist-spinner" class="text-center py-5" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2 text-primary">Cargando histórico…</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabla Demand Driven ── --}}
    <div class="card card-outline card-info" id="dd-card-tabla" style="display:none;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>
                Resultados Demand Driven — <span id="dd-lbl-periodo">—</span>
            </h3>
            <div class="card-tools">
                <span class="badge badge-secondary" id="dd-lbl-deposito"></span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dd-table"
                       class="table table-bordered table-striped table-hover mb-0"
                       style="width:100%">
                    <thead>
                        <tr class="thead-dark">
                            <th>Código</th>
                            <th style="min-width:210px">Medicamento</th>
                            <th class="text-right">Prom.<br>Diario</th>
                            <th class="text-right">σ Desv.</th>
                            <th class="text-right">Stock<br>Seg. (SS)</th>
                            <th class="text-right">Pto.<br>Reorden</th>
                            <th class="text-right">EOQ</th>
                            <th class="text-right">Saldo<br>Actual</th>
                            <th class="text-right">Días<br>Cob.</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Gráfico</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>{{-- /container-fluid --}}
</section>

{{-- ── Spinner overlay ── --}}
<div id="dd-loading">
    <div class="text-center">
        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
        <p class="mt-2 font-weight-bold text-primary">Calculando métricas Demand Driven…</p>
    </div>
</div>

@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{ asset('assets/lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<!-- Dependencias para botones Excel y PDF (deben cargarse ANTES de buttons.html5) -->
<script src="{{ asset('assets/lte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
$(function () {

    /* ── URLs ──────────────────────────────────────────────────── */
    const URL_DATA     = '{{ route("medcol6.dd.data") }}';
    const URL_HIST     = '{{ route("medcol6.dd.historico") }}';
    const MESES        = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

    /* ── Estado ────────────────────────────────────────────────── */
    let ddTable       = null;
    let chartHist     = null;
    let datosActuales = [];
    let filtroEstado  = '';

    /* ── Badges de estado ──────────────────────────────────────── */
    function badgeEstado(estado) {
        const mapa = {
            CRITICO:     ['badge-critico',    'fa-exclamation-circle', 'Crítico'],
            REORDENAR:   ['badge-reordenar',  'fa-cart-arrow-down',    'Reordenar'],
            NORMAL:      ['badge-normal',     'fa-check-circle',       'Normal'],
            SOBRESTOCK:  ['badge-sobrestock', 'fa-warehouse',          'Sobrestock'],
            SIN_DEMANDA: ['badge-sin-dem',    'fa-ban',                'Sin demanda'],
        };
        const [cls, icon, label] = mapa[estado] || ['badge-secondary', 'fa-question', estado];
        return `<span class="badge ${cls}"><i class="fas ${icon} mr-1"></i>${label}</span>`;
    }

    /* ── Clase de fila ─────────────────────────────────────────── */
    function rowClass(estado) {
        return { CRITICO:'row-critico', REORDENAR:'row-reordenar', SOBRESTOCK:'row-sobrestock' }[estado] || '';
    }

    /* ── Formateo numérico ─────────────────────────────────────── */
    function fmt(v, dec) {
        if (v === null || v === undefined) return '—';
        return parseFloat(v).toLocaleString('es-CO', { minimumFractionDigits: dec, maximumFractionDigits: dec });
    }
    function fmtInt(v) {
        if (v === null || v === undefined) return '—';
        return parseInt(v).toLocaleString('es-CO');
    }

    /* ── Inicializar DataTable ──────────────────────────────────── */
    function initTable(data) {
        if (ddTable) { ddTable.destroy(); ddTable = null; }
        $('#dd-table tbody').empty();

        ddTable = $('#dd-table').DataTable({
            data:       data,
            pageLength: 25,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'Todos']],
            order:      [[1, 'asc']],
            columns: [
                { data: 'codigo',          className: 'font-weight-bold' },
                { data: 'nombre_generico', render: (d) => d ? escHtml(d) : '<span class="text-muted">—</span>' },
                { data: 'promedio_diario', className: 'num-col', render: (d) => fmt(d, 2) },
                { data: 'std_dev',         className: 'num-col', render: (d) => fmt(d, 2) },
                { data: 'stock_seguridad', className: 'num-col font-weight-bold text-warning', render: (d) => fmt(d, 1) },
                { data: 'punto_reorden',   className: 'num-col font-weight-bold text-danger',  render: (d) => fmt(d, 1) },
                { data: 'eoq',             className: 'num-col text-primary', render: (d) => fmtInt(d) },
                { data: 'saldo_actual',    className: 'num-col', render: (d) => fmtInt(d) },
                {
                    data: 'dias_cobertura',
                    className: 'num-col',
                    render: function (d, type, row) {
                        if (d === null || d === undefined) return '<span class="text-muted">—</span>';
                        const v   = parseFloat(d);
                        const cls = v < 7 ? 'text-danger font-weight-bold'
                                  : v < 14 ? 'text-warning font-weight-bold'
                                  : 'text-success';
                        return `<span class="${cls}">${fmt(d, 1)}</span>`;
                    },
                },
                {
                    data: 'estado',
                    className: 'text-center',
                    render: function (d, type) {
                        // Para filtrado y ordenación devolver el valor crudo
                        if (type === 'filter' || type === 'sort') return d;
                        return badgeEstado(d);
                    },
                },
                {
                    data: null,
                    orderable: false, searchable: false,
                    className: 'text-center',
                    render: function (d, type, row) {
                        return `<button class="btn btn-outline-primary btn-xs btn-hist"
                                        data-codigo="${escHtml(row.codigo)}"
                                        data-nombre="${escHtml(row.nombre_generico || '')}"
                                        data-ss="${row.stock_seguridad}"
                                        data-rop="${row.punto_reorden}"
                                        data-eoq="${row.eoq}"
                                        data-saldo="${row.saldo_actual}"
                                        data-dias="${row.dias_cobertura ?? ''}"
                                        title="Ver histórico">
                                    <i class="fas fa-chart-line"></i>
                                </button>`;
                    },
                },
            ],
            language: {
                emptyTable:   'No hay datos para los filtros seleccionados.',
                info:         'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:    'Mostrando 0 a 0 de 0 registros',
                infoFiltered: '(filtrado de _MAX_ totales)',
                lengthMenu:   'Mostrar _MENU_ registros',
                search:       'Buscar:',
                zeroRecords:  'No se encontraron resultados.',
                paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            },
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            buttons: [
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fas fa-file-excel mr-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':not(:last-child)',   // excluir columna "Gráfico"
                        format: {
                            // Exportar el valor crudo del estado (sin HTML del badge)
                            body: function (data, row, column) {
                                // Quitar etiquetas HTML si quedara alguna
                                return data ? String(data).replace(/<[^>]+>/g, '') : '';
                            }
                        }
                    },
                    title: function () {
                        const anio = $('#dd-anio').val() || 'Todos los años';
                        const dep  = $('#dd-deposito').val() || 'Todos los depósitos';
                        return 'Demand Driven – ' + anio + ' – ' + dep;
                    },
                    filename: function () {
                        const anio = $('#dd-anio').val() || 'todos';
                        return 'demand_driven_' + anio + '_' + new Date().toISOString().slice(0, 10);
                    },
                    messageTop: function () {
                        return 'Parámetros: LT={{ $params['lt'] }} días | Z={{ $params['z'] }} | S=${{ $params['s'] }} | H=${{ $params['h'] }}';
                    },
                },
                {
                    extend:    'pdfHtml5',
                    text:      '<i class="fas fa-file-pdf mr-1"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'landscape',
                    pageSize:    'A3',
                    exportOptions: { columns: ':not(:last-child)' },
                    title: function () {
                        return 'Demand Driven – ' + ($('#dd-anio').val() || 'Todos los años');
                    },
                },
                {
                    extend:    'csvHtml5',
                    text:      '<i class="fas fa-file-csv mr-1"></i> CSV',
                    className: 'btn btn-secondary btn-sm',
                    exportOptions: { columns: ':not(:last-child)' },
                },
            ],
            rowCallback: function (row, rowData) {
                const cls = rowClass(rowData.estado);
                if (cls) $(row).addClass(cls);
            },
        });
    }

    /* ── Tarjetas de resumen ────────────────────────────────────── */
    function updateSummary(data) {
        const cnt = (e) => data.filter(r => r.estado === e).length;
        $('#s-total').text(data.length.toLocaleString('es-CO'));
        $('#s-critico').text(cnt('CRITICO').toLocaleString('es-CO'));
        $('#s-reordenar').text(cnt('REORDENAR').toLocaleString('es-CO'));
        $('#s-normal').text(cnt('NORMAL').toLocaleString('es-CO'));
        $('#s-sobrestock').text(cnt('SOBRESTOCK').toLocaleString('es-CO'));
        $('#dd-summary').css('display', 'flex').show();
        $('#filtros-estado').show();
    }

    /* ── Cargar datos ───────────────────────────────────────────── */
    function cargarDatos() {
        const anio      = $('#dd-anio').val();
        const deposito  = $('#dd-deposito').val();
        const agrupador = $('#dd-agrupador').val().trim();

        filtroEstado = '';
        $('.btn-estado').removeClass('active');
        $('.btn-estado[data-estado=""]').addClass('active');

        $('#dd-loading').addClass('active');
        $('#dd-card-tabla').hide();
        $('#dd-summary').hide();
        $('#panel-historico').hide();

        $.ajax({
            url: URL_DATA, method: 'GET',
            data: { anio, deposito, agrupador },
            dataType: 'json',
            success: function (resp) {
                datosActuales = resp.data || [];
                $('#dd-loading').removeClass('active');

                if (datosActuales.length === 0) {
                    toastr.info('No se encontraron datos para los filtros seleccionados.', 'Sin datos');
                    return;
                }

                const periodo  = anio || 'Todos los años';
                const depLabel = deposito || 'Todos los depósitos';
                $('#dd-lbl-periodo').text(periodo);
                $('#dd-lbl-deposito').text(depLabel);

                initTable(datosActuales);
                updateSummary(datosActuales);

                $('#dd-card-tabla').fadeIn(200);
                toastr.success(`${datosActuales.length} medicamentos calculados.`, 'Listo', { timeOut: 3000 });
            },
            error: function (xhr) {
                $('#dd-loading').removeClass('active');
                toastr.error(xhr.responseJSON?.message || 'Error al calcular los datos.', 'Error');
            },
        });
    }

    /* ── Filtro rápido por estado (client-side) ─────────────────── */
    $(document).on('click', '.btn-estado', function () {
        filtroEstado = $(this).data('estado');
        $('.btn-estado').removeClass('active');
        $(this).addClass('active');

        if (!ddTable) return;
        // Columna 9 = estado. search() usa el valor devuelto por render(type='filter')
        ddTable.column(9).search(filtroEstado, false, false).draw();
    });

    /* ── Gráfico histórico ──────────────────────────────────────── */
    $(document).on('click', '.btn-hist', function () {
        const codigo   = $(this).data('codigo');
        const nombre   = $(this).data('nombre');
        const anio     = $('#dd-anio').val();
        const deposito = $('#dd-deposito').val();

        // Llenar métricas del panel
        $('#hist-titulo').text(`${codigo} — ${nombre}`);
        $('#hm-ss').text(fmt($(this).data('ss'), 1));
        $('#hm-rop').text(fmt($(this).data('rop'), 1));
        $('#hm-eoq').text(fmtInt($(this).data('eoq')));
        const saldo = $(this).data('saldo');
        const dias  = $(this).data('dias');
        $('#hm-saldo').text(fmtInt(saldo) + (dias !== '' ? ' / ' + fmt(dias, 1) + ' días' : ''));

        // Mostrar panel y spinner, ocultar canvas
        $('#panel-historico').fadeIn(200);
        $('#chart-historico').hide();
        $('#hist-spinner').show();
        $('html, body').animate({ scrollTop: $('#panel-historico').offset().top - 70 }, 400);

        // Destruir gráfico anterior
        if (chartHist) { chartHist.destroy(); chartHist = null; }

        $.ajax({
            url: URL_HIST, method: 'GET',
            data: { codigo, anio, deposito },
            dataType: 'json',
            success: function (resp) {
                $('#hist-spinner').hide();
                const data = resp.data || [];

                if (data.length === 0) {
                    $('#chart-historico').hide();
                    toastr.info('Sin datos históricos para este medicamento.', 'Info');
                    return;
                }

                renderChart(data, parseFloat($(document.querySelector('.btn-hist[data-codigo="'+codigo+'"]')).data('rop') || 0));
                $('#chart-historico').show();
            },
            error: function () {
                $('#hist-spinner').hide();
                toastr.error('Error al cargar el histórico.', 'Error');
            },
        });
    });

    /* ── Renderizar gráfico de líneas ───────────────────────────── */
    function renderChart(data, rop) {
        const ctx = document.getElementById('chart-historico');
        if (!ctx) return;

        const labels   = data.map(d => {
            const [y, m] = d.periodo.split('-');
            return MESES[parseInt(m) - 1] + ' ' + y;
        });
        const valores  = data.map(d => parseFloat(d.total) || 0);
        const promMes  = valores.reduce((a, b) => a + b, 0) / valores.length;

        chartHist = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Unidades dispensadas',
                        data: valores,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,0.10)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#4e73df',
                    },
                    {
                        label: 'Promedio mensual',
                        data: new Array(data.length).fill(parseFloat(promMes.toFixed(1))),
                        borderColor: '#1cc88a',
                        borderWidth: 1.8,
                        borderDash: [6, 4],
                        fill: false,
                        pointRadius: 0,
                        tension: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 12 } } },
                    tooltip: {
                        callbacks: {
                            title: (ctx) => ctx[0].label,
                            label: function (ctx) {
                                if (ctx.datasetIndex === 0) {
                                    const item = data[ctx.dataIndex];
                                    return [
                                        ' Unidades: ' + ctx.parsed.y.toLocaleString('es-CO'),
                                        ' Días activos: ' + item.dias_activos,
                                        ' Pacientes únicos: ' + item.pacientes,
                                    ];
                                }
                                return ' Promedio: ' + ctx.parsed.y.toLocaleString('es-CO', { minimumFractionDigits: 1 });
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            font: { size: 11 },
                            maxRotation: 45,
                            // Mostrar máximo 18 etiquetas para no saturar
                            maxTicksLimit: 18,
                        },
                        grid: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Unidades dispensadas', font: { size: 11 } },
                        ticks: {
                            font: { size: 11 },
                            callback: (v) => v.toLocaleString('es-CO'),
                        },
                    },
                },
            },
        });
    }

    /* ── Cerrar panel histórico ─────────────────────────────────── */
    $('#hist-cerrar').on('click', function () {
        $('#panel-historico').fadeOut(150);
        if (chartHist) { chartHist.destroy(); chartHist = null; }
    });

    /* ── Eventos filtros ─────────────────────────────────────────── */
    $('#dd-btn-cargar').on('click', cargarDatos);
    $(document).on('change', '.dd-filtro', cargarDatos);

    // Agrupador: debounce + Enter
    let _deb = null;
    $('#dd-agrupador').on('input', function () {
        clearTimeout(_deb);
        _deb = setTimeout(cargarDatos, 700);
    });
    $('#dd-agrupador').on('keydown', function (e) {
        if (e.key === 'Enter') { clearTimeout(_deb); cargarDatos(); }
    });
    $('#dd-limpiar-agrupador').on('click', function () {
        if ($('#dd-agrupador').val() !== '') { $('#dd-agrupador').val('').trigger('input'); }
    });

    // Limpiar todo
    $('#dd-btn-limpiar').on('click', function () {
        if (ddTable) { ddTable.destroy(); ddTable = null; }
        $('#dd-table tbody').empty();
        $('#dd-card-tabla').hide();
        $('#dd-summary').hide();
        $('#panel-historico').hide();
        $('#filtros-estado').hide();
        if (chartHist) { chartHist.destroy(); chartHist = null; }
        datosActuales = [];
        $('#dd-deposito').val('');
        $('#dd-agrupador').val('');
        cargarDatos();
    });

    /* ── Carga inicial ───────────────────────────────────────────── */
    cargarDatos();

    /* ── Utilidad ────────────────────────────────────────────────── */
    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

});
</script>
@endsection
