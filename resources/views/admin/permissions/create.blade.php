@extends('layouts.admin')

@section('title', 'Crear Permiso')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Crear Permiso</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permisos</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus-circle mr-2"></i>Nuevo Permiso
                        </h3>
                    </div>

                    <form action="{{ route('permissions.store') }}" method="POST" id="formCrearPermiso">
                        @csrf
                        <div class="card-body">

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <h6><i class="fas fa-exclamation-triangle mr-2"></i>Corrige los siguientes errores:</h6>
                                    <ul class="mb-0 pl-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Nombre --}}
                            <div class="form-group">
                                <label for="name">
                                    Nombre del permiso <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Ej: Ver Panel de Medcol6"
                                       maxlength="255" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Nombre descriptivo legible para el administrador.</small>
                            </div>

                            {{-- Módulo + Acción → Slug --}}
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="module">
                                        Módulo <span class="text-danger">*</span>
                                    </label>
                                    <select id="module" name="module"
                                            class="form-control @error('module') is-invalid @enderror" required>
                                        <option value="">— Selecciona o escribe —</option>
                                        <optgroup label="Módulos existentes">
                                            @foreach($modules->unique()->sort() as $mod)
                                                <option value="{{ $mod }}" {{ old('module') == $mod ? 'selected' : '' }}>
                                                    {{ $mod }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <option value="_nuevo">+ Ingresar módulo nuevo…</option>
                                    </select>
                                    @error('module')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3" id="wrapModuloNuevo" style="display:none;">
                                    <label for="moduloNuevoInput">Nombre del módulo</label>
                                    <input type="text" id="moduloNuevoInput"
                                           class="form-control"
                                           placeholder="Ej: inventario"
                                           maxlength="100">
                                    <small class="text-muted">Sin espacios ni mayúsculas.</small>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="accion">
                                        Acción <span class="text-danger">*</span>
                                    </label>
                                    <select id="accion" class="form-control">
                                        <option value="view">view — Ver</option>
                                        <option value="create">create — Crear</option>
                                        <option value="edit">edit — Editar</option>
                                        <option value="delete">delete — Eliminar</option>
                                        <option value="export">export — Exportar</option>
                                        <option value="assign">assign — Asignar</option>
                                        <option value="_custom">Acción personalizada…</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="wrapAccionCustom" style="display:none;">
                                <label for="accionCustomInput">Acción personalizada</label>
                                <input type="text" id="accionCustomInput" class="form-control"
                                       placeholder="Ej: sync, report, approve"
                                       maxlength="100">
                            </div>

                            {{-- Slug (generado automáticamente, editable) --}}
                            <div class="form-group">
                                <label for="slug">
                                    Slug (clave única) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" id="slug" name="slug"
                                           class="form-control @error('slug') is-invalid @enderror"
                                           value="{{ old('slug') }}"
                                           placeholder="Se genera automáticamente"
                                           maxlength="255" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                id="btnGenerarSlug" title="Regenerar slug">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">
                                    Formato: <code>modulo.accion</code> — Ej: <code>medcol6.view</code>.
                                    Se usa en el código para verificar accesos.
                                </small>
                            </div>

                            {{-- Descripción --}}
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea id="description" name="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="2" maxlength="500"
                                          placeholder="Breve descripción de qué permite hacer este permiso">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Estado --}}
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           id="is_active" name="is_active"
                                           {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Permiso activo
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Los permisos inactivos no son evaluados en las verificaciones de acceso.
                                </small>
                            </div>

                        </div>{{-- /card-body --}}

                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Crear Permiso
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Ayuda rápida --}}
                <div class="card card-light">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1 text-info"></i>Convención de slugs</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Módulo</th><th>Acciones disponibles</th></tr></thead>
                            <tbody>
                                @foreach($modules->unique()->sort() as $mod)
                                <tr>
                                    <td><code>{{ $mod }}</code></td>
                                    <td class="text-muted small">
                                        {{ $mod }}.view &nbsp;·&nbsp;
                                        {{ $mod }}.create &nbsp;·&nbsp;
                                        {{ $mod }}.edit &nbsp;·&nbsp;
                                        {{ $mod }}.delete
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Mostrar/ocultar input de módulo nuevo
    $('#module').on('change', function() {
        if ($(this).val() === '_nuevo') {
            $('#wrapModuloNuevo').show();
        } else {
            $('#wrapModuloNuevo').hide();
        }
        generarSlug();
    });

    // Mostrar/ocultar input de acción personalizada
    $('#accion').on('change', function() {
        if ($(this).val() === '_custom') {
            $('#wrapAccionCustom').show();
        } else {
            $('#wrapAccionCustom').hide();
        }
        generarSlug();
    });

    $('#moduloNuevoInput, #accionCustomInput').on('input', generarSlug);
    $('#btnGenerarSlug').on('click', generarSlug);

    function obtenerModulo() {
        var sel = $('#module').val();
        if (sel === '_nuevo') return sanitizar($('#moduloNuevoInput').val());
        return sel || '';
    }

    function obtenerAccion() {
        var sel = $('#accion').val();
        if (sel === '_custom') return sanitizar($('#accionCustomInput').val());
        return sel || '';
    }

    function sanitizar(str) {
        return str.toLowerCase().replace(/[^a-z0-9\-_]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
    }

    function generarSlug() {
        var modulo = obtenerModulo();
        var accion = obtenerAccion();
        // Sólo auto-genera si el campo está vacío o fue generado automáticamente antes
        if (modulo && accion) {
            $('#slug').val(modulo + '.' + accion);
        }

        // Actualizar el campo hidden module si se escoge módulo nuevo
        if ($('#module').val() === '_nuevo') {
            $('#module').find('option[value="_nuevo"]').val($('#moduloNuevoInput').val() || '_nuevo');
        }
    }

    // Sincronizar módulo real antes de enviar
    $('#formCrearPermiso').on('submit', function() {
        if ($('#module').val() === '_nuevo' || !$('#module').val()) {
            // Crear option con el valor nuevo y seleccionarlo
            var nuevoMod = sanitizar($('#moduloNuevoInput').val());
            if (!nuevoMod) {
                alert('Ingresa el nombre del módulo nuevo.');
                return false;
            }
            $('<option>', { value: nuevoMod, selected: true }).appendTo('#module');
            $('#module').val(nuevoMod);
        }
        return true;
    });

    // Si viene con old() pre-cargado, detectar si el módulo no está en la lista
    var oldModule = '{{ old('module') }}';
    if (oldModule && $('#module option[value="' + oldModule + '"]').length === 0) {
        $('#module').val('_nuevo');
        $('#moduloNuevoInput').val(oldModule);
        $('#wrapModuloNuevo').show();
    }
});
</script>
@endpush
