@extends('layouts.admin')

@section('title', 'Perfiles de Buffer DDMRP')

@section('styles')
<link href="{{ asset('assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<style>
.param-tag {
    display: inline-block;
    background: #f0f0f0;
    border-radius: 4px;
    padding: 2px 8px;
    font-size: .78rem;
    margin: 1px;
    white-space: nowrap;
}
.param-tag b { color: #4e73df; }
.zone-chip {
    display: inline-block;
    border-radius: 3px;
    padding: 1px 7px;
    font-size: .75rem;
    font-weight: 600;
    color: #fff;
}
</style>
@endsection

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-layer-group text-warning mr-2"></i>
                    Perfiles de Buffer DDMRP
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item">API Medcol</li>
                    <li class="breadcrumb-item"><a href="{{ route('medcol6.ddmrp.buffers') }}">DDMRP</a></li>
                    <li class="breadcrumb-item active">Perfiles</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

    {{-- Info DDMRP --}}
    <div class="callout callout-info mb-3">
        <p class="mb-0" style="font-size:.85rem">
            Los <strong>perfiles de buffer</strong> definen los factores que controlan el tamaño de las tres zonas DDMRP para cada conjunto de ítems.
            <br>
            <span class="zone-chip" style="background:#dc3545">Zona Roja</span> = DDP × LT × LTF × (1 + VF) &nbsp;|&nbsp;
            <span class="zone-chip" style="background:#ffc107;color:#000">Zona Amarilla</span> = DDP × LT &nbsp;|&nbsp;
            <span class="zone-chip" style="background:#28a745">Zona Verde</span> = MAX(DDP×OC, DDP×LT×LTF, MOQ)
        </p>
    </div>

    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-layer-group mr-1"></i> Perfiles definidos</h3>
            <div class="card-tools">
                <button class="btn btn-warning btn-sm" id="btn-nuevo">
                    <i class="fas fa-plus mr-1"></i> Nuevo Perfil
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="perfiles-table" class="table table-bordered table-hover mb-0" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center">LT (días)</th>
                            <th class="text-center">LTF</th>
                            <th class="text-center">VF</th>
                            <th class="text-center">OC (días)</th>
                            <th class="text-center">MOQ</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="min-width:120px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perfiles as $p)
                        <tr id="row-{{ $p->id }}">
                            <td>
                                <strong>{{ $p->nombre }}</strong>
                                @if($p->descripcion)
                                    <br><small class="text-muted">{{ $p->descripcion }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $p->lead_time }}</td>
                            <td class="text-center">{{ number_format($p->lead_time_factor, 2) }}</td>
                            <td class="text-center">{{ number_format($p->variability_factor, 2) }}</td>
                            <td class="text-center">{{ $p->order_cycle }}</td>
                            <td class="text-center">{{ number_format($p->moq, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($p->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-xs btn-info btn-editar"
                                        data-id="{{ $p->id }}"
                                        title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-xs {{ $p->is_active ? 'btn-warning' : 'btn-success' }} btn-toggle"
                                        data-id="{{ $p->id }}"
                                        title="{{ $p->is_active ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas {{ $p->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                                <button class="btn btn-xs btn-danger btn-eliminar"
                                        data-id="{{ $p->id }}"
                                        data-nombre="{{ $p->nombre }}"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-layer-group fa-2x mb-2 d-block"></i>
                                No hay perfiles definidos. Cree el primero con el botón <strong>Nuevo Perfil</strong>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</section>

{{-- ── Modal Crear / Editar ── --}}
<div class="modal fade" id="modal-perfil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-layer-group mr-2"></i><span id="modal-titulo">Nuevo Perfil</span></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="form-perfil" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="perfil-id">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="font-weight-bold">Nombre del perfil <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="f-nombre"
                                       placeholder="Ej: Alta variabilidad – Lead Time corto" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Estado</label>
                                <select class="form-control form-control-sm" id="f-activo">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Descripción</label>
                        <textarea class="form-control form-control-sm" id="f-descripcion" rows="2"
                                  placeholder="Descripción opcional del perfil…" maxlength="500"></textarea>
                    </div>

                    <hr class="mt-2 mb-3">
                    <h6 class="text-muted mb-3"><i class="fas fa-sliders-h mr-1"></i> Parámetros de cálculo</h6>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lead Time – LT <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control" id="f-lt" min="1" max="180" value="7">
                                    <div class="input-group-append"><span class="input-group-text">días</span></div>
                                </div>
                                <small class="text-muted">Tiempo real de reposición</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Factor LT – LTF <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="f-ltf"
                                       min="0.1" max="3.0" step="0.05" value="1.00">
                                <small class="text-muted">Multiplicador LT para zonas roja/verde</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Factor Variabilidad – VF <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="f-vf"
                                       min="0.00" max="1.00" step="0.05" value="0.50">
                                <small class="text-muted">Seguridad zona roja (0.0 – 1.0)</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Ciclo de Pedido – OC <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control" id="f-oc" min="1" max="180" value="14">
                                    <div class="input-group-append"><span class="input-group-text">días</span></div>
                                </div>
                                <small class="text-muted">Frecuencia de pedido (zona verde min.)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Cant. Mín. Pedido – MOQ <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="f-moq" min="1" value="1">
                                <small class="text-muted">Unidades mínimas por orden</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            {{-- Preview zona verde mínima --}}
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Vista previa (DDP=10)</label>
                                <div class="p-2 bg-light rounded" style="font-size:.8rem" id="preview-zonas">
                                    <div><span style="color:#dc3545">■</span> Zona Roja: <strong id="pv-roja">—</strong></div>
                                    <div><span style="color:#ffc107">■</span> Zona Amarilla: <strong id="pv-amarilla">—</strong></div>
                                    <div><span style="color:#28a745">■</span> Zona Verde: <strong id="pv-verde">—</strong></div>
                                    <div class="mt-1 text-muted">TOG: <strong id="pv-tog">—</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning btn-sm" id="btn-guardar">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(function () {

    const URL_BASE = '{{ route("medcol6.ddmrp.perfiles.index") }}';

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ── Preview en tiempo real ────────────────────────────────────────────────
    function actualizarPreview() {
        const ddp = 10;
        const lt  = parseFloat($('#f-lt').val())  || 7;
        const ltf = parseFloat($('#f-ltf').val()) || 1;
        const vf  = parseFloat($('#f-vf').val())  || 0.5;
        const oc  = parseFloat($('#f-oc').val())  || 14;
        const moq = parseFloat($('#f-moq').val()) || 1;

        const zonaRojaBase = ddp * lt * ltf;
        const zonaRoja     = zonaRojaBase * (1 + vf);
        const zonaAmarilla = ddp * lt;
        const zonaVerde    = Math.max(ddp * oc, ddp * lt * ltf, moq);
        const tog          = zonaRoja + zonaAmarilla + zonaVerde;

        $('#pv-roja').text(zonaRoja.toFixed(1));
        $('#pv-amarilla').text(zonaAmarilla.toFixed(1));
        $('#pv-verde').text(zonaVerde.toFixed(1));
        $('#pv-tog').text(tog.toFixed(1));
    }
    $('#f-lt, #f-ltf, #f-vf, #f-oc, #f-moq').on('input', actualizarPreview);

    // ── Abrir modal Nuevo ─────────────────────────────────────────────────────
    $('#btn-nuevo').on('click', function () {
        $('#perfil-id').val('');
        $('#modal-titulo').text('Nuevo Perfil');
        $('#form-perfil')[0].reset();
        $('#f-lt').val(7); $('#f-ltf').val(1.00); $('#f-vf').val(0.50);
        $('#f-oc').val(14); $('#f-moq').val(1); $('#f-activo').val(1);
        actualizarPreview();
        $('#modal-perfil').modal('show');
    });

    // ── Abrir modal Editar ────────────────────────────────────────────────────
    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('id');
        $.get(URL_BASE + '/' + id, function (p) {
            $('#perfil-id').val(p.id);
            $('#modal-titulo').text('Editar Perfil');
            $('#f-nombre').val(p.nombre);
            $('#f-descripcion').val(p.descripcion || '');
            $('#f-lt').val(p.lead_time);
            $('#f-ltf').val(p.lead_time_factor);
            $('#f-vf').val(p.variability_factor);
            $('#f-oc').val(p.order_cycle);
            $('#f-moq').val(p.moq);
            $('#f-activo').val(p.is_active ? 1 : 0);
            actualizarPreview();
            $('#modal-perfil').modal('show');
        });
    });

    // ── Guardar (crear o actualizar) ──────────────────────────────────────────
    $('#form-perfil').on('submit', function (e) {
        e.preventDefault();

        const id     = $('#perfil-id').val();
        const method = id ? 'PUT' : 'POST';
        const url    = id ? URL_BASE + '/' + id : URL_BASE;

        const payload = {
            nombre:             $('#f-nombre').val().trim(),
            descripcion:        $('#f-descripcion').val().trim(),
            lead_time:          parseInt($('#f-lt').val()),
            lead_time_factor:   parseFloat($('#f-ltf').val()),
            variability_factor: parseFloat($('#f-vf').val()),
            order_cycle:        parseInt($('#f-oc').val()),
            moq:                parseInt($('#f-moq').val()),
            is_active:          $('#f-activo').val() === '1',
        };

        if (!payload.nombre) {
            toastr.warning('El nombre del perfil es obligatorio.', 'Aviso');
            $('#f-nombre').focus();
            return;
        }

        $('#btn-guardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando…');

        $.ajax({
            url, method,
            data: JSON.stringify(payload),
            contentType: 'application/json',
            success: function (resp) {
                $('#modal-perfil').modal('hide');
                toastr.success(resp.msg, 'Guardado');
                setTimeout(() => location.reload(), 800);
            },
            error: function (xhr) {
                const err = xhr.responseJSON?.message || 'Error al guardar.';
                toastr.error(err, 'Error');
            },
            complete: function () {
                $('#btn-guardar').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar');
            },
        });
    });

    // ── Toggle activo/inactivo ────────────────────────────────────────────────
    $(document).on('click', '.btn-toggle', function () {
        const id  = $(this).data('id');
        const btn = $(this);
        $.ajax({
            url: URL_BASE + '/' + id + '/toggle',
            method: 'PATCH',
            success: function (resp) {
                toastr.success(resp.msg);
                setTimeout(() => location.reload(), 600);
            },
            error: () => toastr.error('No se pudo cambiar el estado.'),
        });
    });

    // ── Eliminar ──────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-eliminar', function () {
        const id     = $(this).data('id');
        const nombre = $(this).data('nombre');
        if (!confirm(`¿Eliminar el perfil "${nombre}"?\nEsta acción no se puede deshacer.`)) return;

        $.ajax({
            url:    URL_BASE + '/' + id,
            method: 'DELETE',
            success: function (resp) {
                toastr.success(resp.msg);
                $('#row-' + id).fadeOut(400, function () { $(this).remove(); });
            },
            error: () => toastr.error('No se pudo eliminar el perfil.'),
        });
    });

    actualizarPreview();
});
</script>
@endsection
