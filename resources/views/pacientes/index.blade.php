@extends('layouts.admin')

@section('title', 'Gestión de Pacientes')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
    .badge { font-size: .82em; }
    .dt-buttons .btn { margin-right: 3px; }
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Pacientes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Pacientes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-users mr-2"></i>Listado de Pacientes</h3>
                        <div class="card-tools">
                            <button id="btnSyncApi" class="btn btn-success btn-sm mr-1" title="Importar pacientes desde el servidor API">
                                <i class="fas fa-sync-alt"></i> Sincronizar API
                            </button>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCrear">
                                <i class="fas fa-plus"></i> Nuevo Paciente
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaPacientes" class="table table-bordered table-striped table-hover table-sm" style="width:100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Tip. Doc.</th>
                                        <th>Historia</th>
                                        <th>Paciente</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Régimen</th>
                                        <th>Nivel</th>
                                        <th>Edad</th>
                                        <th>Sexo</th>
                                        <th>PQRS</th>
                                        <th>Estado</th>
                                        <th>Programa</th>
                                        <th>Alto Costo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== MODAL CREAR ===== -->
<div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formCrear">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="modalCrearLabel">
                        <i class="fas fa-user-plus mr-2"></i>Registrar Paciente
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Tipo Documento</label>
                            <select name="tipdocum" class="form-control form-control-sm">
                                <option value="">-- Seleccione --</option>
                                <option value="CC">CC - Cédula</option>
                                <option value="TI">TI - Tarjeta Identidad</option>
                                <option value="RC">RC - Registro Civil</option>
                                <option value="CE">CE - Cédula Extranjería</option>
                                <option value="PA">PA - Pasaporte</option>
                                <option value="MS">MS - Menor sin ID</option>
                                <option value="AS">AS - Adulto sin ID</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Historia / Nro. Documento</label>
                            <input type="text" name="historia" class="form-control form-control-sm" placeholder="Número de historia">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nombre del Paciente <span class="text-danger">*</span></label>
                            <input type="text" name="paciente" class="form-control form-control-sm" placeholder="Nombre completo" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" class="form-control form-control-sm" placeholder="Dirección del paciente">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control form-control-sm" placeholder="Número de teléfono">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Régimen</label>
                            <select name="regimen" class="form-control form-control-sm">
                                <option value="">-- Seleccione --</option>
                                <option value="CONTRIBUTIVO">CONTRIBUTIVO</option>
                                <option value="SUBSIDIADO">SUBSIDIADO</option>
                                <option value="VINCULADO">VINCULADO</option>
                                <option value="ESPECIAL">ESPECIAL</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Nivel</label>
                            <select name="nivel" class="form-control form-control-sm">
                                <option value="">--</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Edad</label>
                            <input type="text" name="edad" class="form-control form-control-sm" placeholder="Edad">
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Sexo</label>
                            <select name="sexo" class="form-control form-control-sm">
                                <option value="">--</option>
                                <option value="M">M - Masculino</option>
                                <option value="F">F - Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Programa</label>
                            <select name="programa" class="form-control form-control-sm">
                                <option value="">-- Sin programa --</option>
                                <option value="EMBARAZADA">EMBARAZADA</option>
                                <option value="ERC">ERC</option>
                                <option value="CANCER">CANCER</option>
                                <option value="VIH">VIH</option>
                                <option value="AR">AR</option>
                                <option value="EH">EH</option>
                                <option value="HEPATITIS">HEPATITIS</option>
                                <option value="HIPERTENSION">HIPERTENSION</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>PQRS <span class="text-danger">*</span></label>
                            <select name="pqrs" class="form-control form-control-sm" required>
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-control form-control-sm" required>
                                <option value="VIVO">VIVO</option>
                                <option value="FALLECIDO">FALLECIDO</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Alto Costo <span class="text-danger">*</span></label>
                            <select name="alto_costo" class="form-control form-control-sm" required>
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnGuardar">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDITAR ===== -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formEditar">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditarLabel">
                        <i class="fas fa-user-edit mr-2"></i>Editar Paciente
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Tipo Documento</label>
                            <select name="tipdocum" id="edit_tipdocum" class="form-control form-control-sm">
                                <option value="">-- Seleccione --</option>
                                <option value="CC">CC - Cédula</option>
                                <option value="TI">TI - Tarjeta Identidad</option>
                                <option value="RC">RC - Registro Civil</option>
                                <option value="CE">CE - Cédula Extranjería</option>
                                <option value="PA">PA - Pasaporte</option>
                                <option value="MS">MS - Menor sin ID</option>
                                <option value="AS">AS - Adulto sin ID</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Historia / Nro. Documento</label>
                            <input type="text" name="historia" id="edit_historia" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nombre del Paciente <span class="text-danger">*</span></label>
                            <input type="text" name="paciente" id="edit_paciente" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" id="edit_direccion" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Régimen</label>
                            <select name="regimen" id="edit_regimen" class="form-control form-control-sm">
                                <option value="">-- Seleccione --</option>
                                <option value="CONTRIBUTIVO">CONTRIBUTIVO</option>
                                <option value="SUBSIDIADO">SUBSIDIADO</option>
                                <option value="VINCULADO">VINCULADO</option>
                                <option value="ESPECIAL">ESPECIAL</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Nivel</label>
                            <select name="nivel" id="edit_nivel" class="form-control form-control-sm">
                                <option value="">--</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Edad</label>
                            <input type="text" name="edad" id="edit_edad" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Sexo</label>
                            <select name="sexo" id="edit_sexo" class="form-control form-control-sm">
                                <option value="">--</option>
                                <option value="M">M - Masculino</option>
                                <option value="F">F - Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Programa</label>
                            <select name="programa" id="edit_programa" class="form-control form-control-sm">
                                <option value="">-- Sin programa --</option>
                                <option value="EMBARAZADA">EMBARAZADA</option>
                                <option value="ERC">ERC</option>
                                <option value="CANCER">CANCER</option>
                                <option value="VIH">VIH</option>
                                <option value="AR">AR</option>
                                <option value="EH">EH</option>
                                <option value="HEPATITIS">HEPATITIS</option>
                                <option value="HIPERTENSION">HIPERTENSION</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>PQRS <span class="text-danger">*</span></label>
                            <select name="pqrs" id="edit_pqrs" class="form-control form-control-sm" required>
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="edit_estado" class="form-control form-control-sm" required>
                                <option value="VIVO">VIVO</option>
                                <option value="FALLECIDO">FALLECIDO</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Alto Costo <span class="text-danger">*</span></label>
                            <select name="alto_costo" id="edit_alto_costo" class="form-control form-control-sm" required>
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info btn-sm" id="btnActualizar">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function () {

    // ── Sincronizar desde API ──────────────────────────────────────────────
    $('#btnSyncApi').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sincronizando...');

        $.ajax({
            url: '{{ route("pacientes.syncapi") }}',
            type: 'GET',
            success: function (resp) {
                if (resp && resp.length > 0) {
                    var r = resp[0];
                    var fn = r.icon === 'success' ? toastr.success :
                             r.icon === 'warning'  ? toastr.warning  : toastr.error;
                    fn(r.respuesta, r.titulo);
                    tabla.ajax.reload();
                }
            },
            error: function () {
                toastr.error('No se pudo conectar con el servidor de sincronización.');
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Sincronizar API');
            }
        });
    });

    // ── Inicializar DataTable ──────────────────────────────────────────────
    var tabla = $('#tablaPacientes').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("pacientes.index") }}',
            type: 'GET',
        },
        columns: [
            { data: 'tipdocum',       name: 'tipdocum' },
            { data: 'historia',       name: 'historia' },
            { data: 'paciente',       name: 'paciente' },
            { data: 'direccion',      name: 'direccion' },
            { data: 'telefono',       name: 'telefono' },
            { data: 'regimen',        name: 'regimen' },
            { data: 'nivel',          name: 'nivel' },
            { data: 'edad',           name: 'edad' },
            { data: 'sexo',           name: 'sexo' },
            { data: 'pqrs_badge',     name: 'pqrs',       orderable: false },
            { data: 'estado_badge',   name: 'estado',     orderable: false },
            { data: 'programa',       name: 'programa' },
            { data: 'alto_costo_badge', name: 'alto_costo', orderable: false },
            { data: 'action',         name: 'action',     orderable: false, searchable: false },
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
        },
        responsive: true,
        pageLength: 25,
        order: [[2, 'asc']],
    });

    // ── Crear paciente ─────────────────────────────────────────────────────
    $('#formCrear').on('submit', function (e) {
        e.preventDefault();
        var btn = $('#btnGuardar');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
            url: '{{ route("pacientes.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function (resp) {
                $('#modalCrear').modal('hide');
                $('#formCrear')[0].reset();
                tabla.ajax.reload();
                toastr.success(resp.success);
            },
            error: function (xhr) {
                var errors = xhr.responseJSON && xhr.responseJSON.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                    : 'Error al guardar el paciente.';
                toastr.error(errors);
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
            }
        });
    });

    // ── Abrir modal editar ─────────────────────────────────────────────────
    $('#tablaPacientes').on('click', '.btn-editar', function () {
        var btn = $(this);

        $('#edit_id').val(btn.data('id'));
        $('#edit_tipdocum').val(btn.data('tipdocum'));
        $('#edit_historia').val(btn.data('historia'));
        $('#edit_paciente').val(btn.data('paciente'));
        $('#edit_direccion').val(btn.data('direccion'));
        $('#edit_telefono').val(btn.data('telefono'));
        $('#edit_regimen').val(btn.data('regimen'));
        $('#edit_nivel').val(btn.data('nivel'));
        $('#edit_edad').val(btn.data('edad'));
        $('#edit_sexo').val(btn.data('sexo'));
        $('#edit_pqrs').val(btn.data('pqrs'));
        $('#edit_estado').val(btn.data('estado'));
        $('#edit_programa').val(btn.data('programa'));
        $('#edit_alto_costo').val(btn.data('alto_costo'));

        $('#modalEditar').modal('show');
    });

    // ── Actualizar paciente ────────────────────────────────────────────────
    $('#formEditar').on('submit', function (e) {
        e.preventDefault();
        var btn  = $('#btnActualizar');
        var id   = $('#edit_id').val();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Actualizando...');

        $.ajax({
            url: '/pacientes/' + id,
            type: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            success: function (resp) {
                $('#modalEditar').modal('hide');
                tabla.ajax.reload();
                toastr.success(resp.success);
            },
            error: function (xhr) {
                var errors = xhr.responseJSON && xhr.responseJSON.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                    : 'Error al actualizar el paciente.';
                toastr.error(errors);
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Actualizar');
            }
        });
    });
});
</script>
@endsection
