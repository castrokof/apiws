@extends('layouts.admin')

@section('title', 'DDMRP – Estado de Buffers')

@section('styles')
<link href="{{ asset('assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<style>
/* ── Tabla ── */
#ddmrp-table { min-width: 1400px; }
#ddmrp-table th, #ddmrp-table td {
    white-space: nowrap;
    font-size: .81rem;
    vertical-align: middle;
}
#ddmrp-table td.num-col { text-align: right; }

/* ── Buffer bar ── */
.bbar-wrap  { position: relative; min-width: 140px; }
.bbar-track {
    display: flex;
    height: 18px;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,.12);
}
.bbar-rojo    { background: #dc3545; flex-shrink: 0; }
.bbar-amarillo{ background: #ffc107; flex-shrink: 0; }
.bbar-verde   { background: #28a745; flex: 1; }
.bbar-marker  {
    position: absolute;
    top: -3px; bottom: -3px;
    width: 3px;
    background: #212529;
    border-radius: 2px;
    transform: translateX(-50%);
    z-index: 5;
    box-shadow: 0 0 3px rgba(0,0,0,.4);
}

/* ── Badges ── */
.badge-rojo      { background: #dc3545; color: #fff; }
.badge-amarillo  { background: #ffc107; color: #000; }
.badge-verde     { background: #28a745; color: #fff; }
.badge-sobrestock{ background: #007bff; color: #fff; }
.badge-sin-dem   { background: #6c757d; color: #fff; }

/* ── Filas coloreadas ── */
tr.row-rojo     td { background-color: rgba(220,53,69,.07)  !important; }
tr.row-amarillo td { background-color: rgba(255,193,7,.07)  !important; }
tr.row-sobrestock td { background-color: rgba(0,123,255,.05) !important; }

/* ── Cards resumen ── */
.dd-stat-card {
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 5px rgba(0,0,0,.07);
}
.dd-stat-card .stat-icon { font-size: 1.7rem; width: 42px; text-align: center; flex-shrink: 0; }
.dd-stat-card .stat-label { font-size: .73rem; color: #6c757d; }
.dd-stat-card .stat-value { font-size: 1.45rem; font-weight: 700; line-height: 1; }

/* ── Perfil activo info ── */
.perfil-chip {
    display: inline-block;
    background: #e9ecef;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: .78rem;
    margin: 2px;
}
.perfil-chip b { color: #e67e22; }

/* ── Spinner ── */
#ddmrp-loading {
    display: none;
    position: fixed; inset: 0;
    background: rgba(255,255,255,.65);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
#ddmrp-loading.active { display: flex; }
</style>
@endsection

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-shield-alt text-warning mr-2"></i>
                    DDMRP – Estado de Buffers
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item">API Medcol</li>
                    <li class="breadcrumb-item active">DDMRP Buffers</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

    {{-- ── Filtros ── --}}
    <div class="card card-outline card-warning mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end">

                <div class="col-md-2 col-sm-6">
                    <label class="font-weight-bold">Año</label>
                    <select id="f-anio" class="form-control form-control-sm f-auto">
                        <option value="">— Todos —</option>
                        @foreach($anios as $anio)
                            <option value="{{ $anio }}" {{ $anio == $anioActual ? 'selected' : '' }}>{{ $anio }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="font-weight-bold">Depósito</label>
                    <select id="f-deposito" class="form-control form-control-sm f-auto">
                        <option value="">— Todos —</option>
                        @foreach($depositos as $dep)
                            <option value="{{ $dep->deposito }}">{{ $dep->deposito }}{{ $dep->nombre_deposito ? ' – '.$dep->nombre_deposito : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="font-weight-bold">Agrupador</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                        <input type="text" id="f-agrupador" class="form-control" placeholder="Ej: M000673" autocomplete="off" maxlength="50">
                        <div class="input-group-append">
                            <button type="button" id="btn-clear-ag" class="btn btn-outline-secondary btn-sm" title="Limpiar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <label class="font-weight-bold">Perfil de Buffer</label>
                    <div class="input-group input-group-sm">
                        <select id="f-perfil" class="form-control form-control-sm f-auto">
                            <option value="">— Perfil por defecto —</option>
                            @foreach($perfiles as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <a href="{{ route('medcol6.ddmrp.perfiles.index') }}"
                               class="btn btn-outline-warning btn-sm" title="Gestionar perfiles">
                                <i class="fas fa-cog"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mt-2 mt-md-0">
                    <button id="btn-calcular" class="btn btn-warning btn-sm">
                        <i class="fas fa-calculator mr-1"></i> Calcular Buffers
                    </button>
                    <button id="btn-limpiar" class="btn btn-secondary btn-sm ml-1">
                        <i class="fas fa-times mr-1"></i> Limpiar
                    </button>
                    {{-- Filtro rápido ── --}}
                    <div class="mt-2" id="filtros-estado" style="display:none;">
                        <small class="text-muted d-block mb-1">Filtrar:</small>
                        <button class="btn btn-xs btn-estado btn-outline-secondary active" data-estado="">Todos</button>
                        <button class="btn btn-xs btn-estado" style="color:#dc3545;border-color:#dc3545" data-estado="ROJO">Rojo</button>
                        <button class="btn btn-xs btn-estado" style="color:#e67e22;border-color:#ffc107" data-estado="AMARILLO">Amarillo</button>
                        <button class="btn btn-xs btn-estado" style="color:#28a745;border-color:#28a745" data-estado="VERDE">Verde</button>
                        <button class="btn btn-xs btn-estado" style="color:#007bff;border-color:#007bff" data-estado="SOBRESTOCK">Sobre</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Perfil activo ── --}}
    <div id="perfil-info" class="mb-3" style="display:none;">
        <small class="text-muted mr-1"><i class="fas fa-layer-group mr-1"></i>Perfil aplicado:</small>
        <span id="perfil-nombre" class="font-weight-bold text-warning"></span>
        <span id="perfil-params"></span>
        <a href="{{ route('medcol6.ddmrp.perfiles.index') }}" class="btn btn-xs btn-outline-warning ml-2">
            <i class="fas fa-edit mr-1"></i>Gestionar perfiles
        </a>
    </div>

    {{-- ── Tarjetas resumen ── --}}
    <div class="row mb-3" id="ddmrp-summary" style="display:none !important;">
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-secondary"><i class="fas fa-pills"></i></div>
                <div><div class="stat-label">Total ítems</div><div class="stat-value" id="s-total">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-danger"><i class="fas fa-exclamation-circle"></i></div>
                <div><div class="stat-label">Zona Roja (Crítico)</div><div class="stat-value text-danger" id="s-rojo">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon" style="color:#e67e22"><i class="fas fa-cart-arrow-down"></i></div>
                <div><div class="stat-label">Zona Amarilla (Reordenar)</div><div class="stat-value" style="color:#e67e22" id="s-amarillo">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                <div><div class="stat-label">Zona Verde (Normal)</div><div class="stat-value text-success" id="s-verde">—</div></div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-2">
            <div class="dd-stat-card bg-white border">
                <div class="stat-icon text-info"><i class="fas fa-warehouse"></i></div>
                <div><div class="stat-label">Sobrestock</div><div class="stat-value text-info" id="s-sobre">—</div></div>
            </div>
        </div>
    </div>

    {{-- ── Tabla principal ── --}}
    <div class="card card-outline card-warning" id="ddmrp-card" style="display:none;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i> Buffers DDMRP — <span id="lbl-periodo">—</span></h3>
            <div class="card-tools">
                <span class="badge badge-secondary" id="lbl-deposito"></span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="ddmrp-table"
                       class="table table-bordered table-striped table-hover mb-0"
                       style="width:100%">
                    <thead>
                        <tr class="thead-dark">
                            <th>Código</th>
                            <th style="min-width:200px">Medicamento</th>
                            <th class="text-right">DDP<br><small class="font-weight-normal">prom/día</small></th>
                            <th class="text-right" style="color:#ff6b6b">Z. Roja</th>
                            <th class="text-right" style="color:#ffd93d">Z. Amarilla</th>
                            <th class="text-right" style="color:#6bcb77">Z. Verde</th>
                            <th class="text-right">TOG<br><small class="font-weight-normal">(máx.)</small></th>
                            <th class="text-right">Saldo</th>
                            <th style="min-width:150px">Buffer</th>
                            <th class="text-center">Estado</th>
                            <th class="text-right">Ped.<br>Sugerido</th>
                            <th class="text-right">Días<br>Cob.</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</section>

<div id="ddmrp-loading">
    <div class="text-center">
        <i class="fas fa-spinner fa-spin fa-3x text-warning"></i>
        <p class="mt-2 font-weight-bold text-warning">Calculando buffers DDMRP…</p>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>

<script>
$(function () {

    const URL_DATA = '{{ route("medcol6.ddmrp.buffers.data") }}';
    let ddTable    = null;

    /* ── Formato numérico ─────────────────────────────────────────── */
    const fmt = (v, d = 0) =>
        (v === null || v === undefined) ? '—'
        : parseFloat(v).toLocaleString('es-CO', { minimumFractionDigits: d, maximumFractionDigits: d });

    /* ── Badge de estado ──────────────────────────────────────────── */
    const ESTADO_MAP = {
        ROJO:        ['badge-rojo',       'fa-exclamation-circle', 'Rojo'],
        AMARILLO:    ['badge-amarillo',   'fa-cart-arrow-down',    'Amarillo'],
        VERDE:       ['badge-verde',      'fa-check-circle',       'Verde'],
        SOBRESTOCK:  ['badge-sobrestock', 'fa-warehouse',          'Sobrestock'],
        SIN_DEMANDA: ['badge-sin-dem',    'fa-ban',                'Sin demanda'],
    };
    function badgeEstado(d) {
        const [cls, icon, lbl] = ESTADO_MAP[d] || ['badge-secondary', 'fa-question', d];
        return `<span class="badge ${cls}"><i class="fas ${icon} mr-1"></i>${lbl}</span>`;
    }

    /* ── Buffer bar visual ────────────────────────────────────────── */
    function bufferBar(d, type, row) {
        if (type !== 'display') return row.estado;
        if (!row.tog || row.tog <= 0) {
            return '<span class="text-muted small">Sin datos</span>';
        }
        const tog = row.tog;
        const pR  = Math.min(row.zona_roja     / tog * 100, 100).toFixed(1);
        const pA  = Math.min(row.zona_amarilla / tog * 100, 100).toFixed(1);
        // zona verde ocupa el resto
        const pS  = Math.min(row.saldo_actual  / tog * 100, 102).toFixed(1);

        const title =
            `Z.Roja: ${fmt(row.zona_roja)} | Z.Amarilla: ${fmt(row.zona_amarilla)} ` +
            `| Z.Verde: ${fmt(row.zona_verde)} | Saldo: ${fmt(row.saldo_actual)}`;

        return `<div class="bbar-wrap" title="${title}">
            <div class="bbar-track">
                <div class="bbar-rojo"    style="width:${pR}%"></div>
                <div class="bbar-amarillo" style="width:${pA}%"></div>
                <div class="bbar-verde"></div>
            </div>
            <div class="bbar-marker" style="left:${Math.min(pS, 100)}%"></div>
        </div>`;
    }

    /* ── Inicializar / re-inicializar DataTable ───────────────────── */
    function initTable(data, anio) {
        if (ddTable) { ddTable.destroy(); ddTable = null; }
        $('#ddmrp-table tbody').empty();

        ddTable = $('#ddmrp-table').DataTable({
            data,
            pageLength: 25,
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'Todos']],
            order:      [[0, 'asc']],
            columns: [
                { data: 'codigo',          className: 'font-weight-bold' },
                { data: 'nombre_generico', render: (d) => d ? escHtml(d) : '<span class="text-muted">—</span>' },
                { data: 'promedio_diario', className: 'num-col', render: (d) => fmt(d, 2) },
                { data: 'zona_roja',       className: 'num-col', render: (d) => `<span style="color:#dc3545;font-weight:600">${fmt(d, 1)}</span>` },
                { data: 'zona_amarilla',   className: 'num-col', render: (d) => `<span style="color:#e67e22;font-weight:600">${fmt(d, 1)}</span>` },
                { data: 'zona_verde',      className: 'num-col', render: (d) => `<span style="color:#28a745;font-weight:600">${fmt(d, 1)}</span>` },
                { data: 'tog',             className: 'num-col text-secondary', render: (d) => fmt(d, 1) },
                { data: 'saldo_actual',    className: 'num-col font-weight-bold', render: (d) => fmt(d, 0) },
                { data: null, orderable: false, searchable: false, render: bufferBar },
                {
                    data: 'estado',
                    className: 'text-center',
                    render: function (d, type) {
                        if (type === 'filter' || type === 'sort') return d;
                        return badgeEstado(d);
                    },
                },
                {
                    data: 'pedido_sugerido',
                    className: 'num-col',
                    render: function (d) {
                        if (!d || d <= 0) return '<span class="text-muted">—</span>';
                        return `<span class="font-weight-bold text-danger">${fmt(d, 0)}</span>`;
                    },
                },
                {
                    data: 'dias_cobertura',
                    className: 'num-col',
                    render: function (d) {
                        if (d === null || d === undefined) return '—';
                        const v   = parseFloat(d);
                        const cls = v < 7 ? 'text-danger font-weight-bold'
                                  : v < 14 ? 'text-warning font-weight-bold'
                                  : 'text-success';
                        return `<span class="${cls}">${fmt(d, 1)}</span>`;
                    },
                },
            ],
            language: {
                emptyTable:   'No hay datos para los filtros seleccionados.',
                info:         'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:    'Mostrando 0 registros',
                infoFiltered: '(filtrado de _MAX_ totales)',
                lengthMenu:   'Mostrar _MENU_ registros',
                search:       'Buscar:',
                zeroRecords:  'Sin resultados.',
                paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            },
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            buttons: [
                {
                    extend: 'excelHtml5', text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':not(:nth-child(9))',  // excluir columna Buffer (visual)
                        format: { body: (d) => d ? String(d).replace(/<[^>]+>/g, '') : '' }
                    },
                    title: function () {
                        return 'DDMRP Buffers – ' + ($('#f-anio').val() || 'Todos') + ' – ' + ($('#f-deposito').val() || 'Todos');
                    },
                    filename: function () {
                        return 'ddmrp_buffers_' + ($('#f-anio').val() || 'todos') + '_' + new Date().toISOString().slice(0,10);
                    },
                    messageTop: function () {
                        return 'Perfil: ' + ($('#perfil-nombre').text() || '—');
                    },
                },
                {
                    extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'landscape', pageSize: 'A3',
                    exportOptions: { columns: ':not(:nth-child(9))' },
                    title: function () { return 'DDMRP Buffers – ' + ($('#f-anio').val() || 'Todos'); },
                },
                {
                    extend: 'csvHtml5', text: '<i class="fas fa-file-csv mr-1"></i> CSV',
                    className: 'btn btn-secondary btn-sm',
                    exportOptions: { columns: ':not(:nth-child(9))' },
                },
            ],
            rowCallback: function (row, rowData) {
                const cls = { ROJO:'row-rojo', AMARILLO:'row-amarillo', SOBRESTOCK:'row-sobrestock' }[rowData.estado];
                if (cls) $(row).addClass(cls);
            },
        });
    }

    /* ── Tarjetas resumen ─────────────────────────────────────────── */
    function updateSummary(data) {
        const cnt = (e) => data.filter(r => r.estado === e).length;
        $('#s-total').text(data.length.toLocaleString('es-CO'));
        $('#s-rojo').text(cnt('ROJO').toLocaleString('es-CO'));
        $('#s-amarillo').text(cnt('AMARILLO').toLocaleString('es-CO'));
        $('#s-verde').text(cnt('VERDE').toLocaleString('es-CO'));
        $('#s-sobre').text(cnt('SOBRESTOCK').toLocaleString('es-CO'));
        $('#ddmrp-summary').css('display', 'flex').show();
        $('#filtros-estado').show();
    }

    /* ── Filtro rápido por estado ─────────────────────────────────── */
    $(document).on('click', '.btn-estado', function () {
        const estado = $(this).data('estado');
        $('.btn-estado').removeClass('active');
        $(this).addClass('active');
        if (ddTable) ddTable.column(9).search(estado, false, false).draw();
    });

    /* ── Cargar / calcular ────────────────────────────────────────── */
    function calcular() {
        const anio      = $('#f-anio').val();
        const deposito  = $('#f-deposito').val();
        const agrupador = $('#f-agrupador').val().trim();
        const perfilId  = $('#f-perfil').val();

        // Reset estado filter
        $('.btn-estado').removeClass('active');
        $('.btn-estado[data-estado=""]').addClass('active');

        $('#ddmrp-loading').addClass('active');
        $('#ddmrp-card').hide();
        $('#ddmrp-summary').hide();
        $('#perfil-info').hide();

        $.ajax({
            url: URL_DATA, method: 'GET',
            data: { anio, deposito, agrupador, perfil_id: perfilId },
            dataType: 'json',
            success: function (resp) {
                $('#ddmrp-loading').removeClass('active');
                const data   = resp.data   || [];
                const perfil = resp.perfil || {};

                if (!data.length) {
                    toastr.info('No se encontraron datos para los filtros seleccionados.', 'Sin datos');
                    return;
                }

                // Mostrar perfil aplicado
                $('#perfil-nombre').text(perfil.nombre || '—');
                $('#perfil-params').html(
                    `<span class="perfil-chip"><b>LT</b> ${perfil.lead_time}d</span>` +
                    `<span class="perfil-chip"><b>LTF</b> ${perfil.lead_time_factor}</span>` +
                    `<span class="perfil-chip"><b>VF</b> ${perfil.variability_factor}</span>` +
                    `<span class="perfil-chip"><b>OC</b> ${perfil.order_cycle}d</span>` +
                    `<span class="perfil-chip"><b>MOQ</b> ${perfil.moq}</span>`
                );
                $('#perfil-info').show();

                $('#lbl-periodo').text(anio || 'Todos los años');
                $('#lbl-deposito').text(deposito || 'Todos los depósitos');

                initTable(data, anio);
                updateSummary(data);

                $('#ddmrp-card').fadeIn(200);
                toastr.success(`${data.length} ítems calculados.`, 'Listo', { timeOut: 3000 });
            },
            error: function (xhr) {
                $('#ddmrp-loading').removeClass('active');
                toastr.error(xhr.responseJSON?.message || 'Error al calcular los buffers.', 'Error');
            },
        });
    }

    /* ── Eventos ──────────────────────────────────────────────────── */
    $('#btn-calcular').on('click', calcular);
    $(document).on('change', '.f-auto', calcular);

    // Agrupador: debounce + Enter
    let _deb = null;
    $('#f-agrupador').on('input', function () { clearTimeout(_deb); _deb = setTimeout(calcular, 700); });
    $('#f-agrupador').on('keydown', function (e) { if (e.key === 'Enter') { clearTimeout(_deb); calcular(); } });
    $('#btn-clear-ag').on('click', function () { if ($('#f-agrupador').val()) $('#f-agrupador').val('').trigger('input'); });

    $('#btn-limpiar').on('click', function () {
        if (ddTable) { ddTable.destroy(); ddTable = null; }
        $('#ddmrp-table tbody').empty();
        $('#ddmrp-card, #ddmrp-summary, #perfil-info, #filtros-estado').hide();
        $('#f-deposito').val(''); $('#f-agrupador').val('');
        calcular();
    });

    /* ── Carga inicial ────────────────────────────────────────────── */
    calcular();

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
});
</script>
@endsection
