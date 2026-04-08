@extends('layouts.admin')

@section('title', 'Editar Permiso')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Permiso</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permisos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                {{-- Info de uso del permiso --}}
                <div class="callout callout-info py-2">
                    <small>
                        <i class="fas fa-info-circle mr-1"></i>
                        Este permiso está asignado a
                        <strong>{{ $permission->roles->count() }} rol(es)</strong>
                        @if($permission->roles->count())
                            ({{ $permission->roles->pluck('name')->join(', ') }})
                        @endif
                        y a
                        <strong>{{ $permission->users->count() }} usuario(s)</strong> directamente.
                        Cambiar el <em>slug</em> requiere actualizar las verificaciones en el código.
                    </small>
                </div>

                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i>Editar: <code>{{ $permission->slug }}</code>
                        </h3>
                    </div>

                    <form action="{{ route('permissions.update', $permission->id) }}" method="POST" id="formEditarPermiso">
                        @csrf
                        @method('PUT')
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
                                       value="{{ old('name', $permission->name) }}"
                                       maxlength="255" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Módulo --}}
                            <div class="form-group">
                                <label for="module">
                                    Módulo <span class="text-danger">*</span>
                                </label>
                                <select id="module" name="module"
                                        class="form-control @error('module') is-invalid @enderror" required>
                                    <optgroup label="Módulos existentes">
                                        @foreach($modules->unique()->sort() as $mod)
                                            <option value="{{ $mod }}"
                                                {{ old('module', $permission->module) == $mod ? 'selected' : '' }}>
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

                            {{-- Input módulo nuevo (oculto por defecto) --}}
                            <div class="form-group" id="wrapModuloNuevo" style="display:none;">
                                <label for="moduloNuevoInput">Nombre del nuevo módulo</label>
                                <input type="text" id="moduloNuevoInput" class="form-control"
                                       placeholder="Ej: facturacion" maxlength="100">
                                <small class="text-muted">Sin espacios ni mayúsculas.</small>
                            </div>

                            {{-- Slug --}}
                            <div class="form-group">
                                <label for="slug">
                                    Slug (clave única) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" id="slug" name="slug"
                                           class="form-control @error('slug') is-invalid @enderror"
                                           value="{{ old('slug', $permission->slug) }}"
                                           maxlength="255" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-warning" title="Cambiar el slug puede romper verificaciones en el código">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    </div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Modificar el slug puede romper verificaciones de acceso en rutas y vistas que usen
                                    <code>hasPermission('{{ $permission->slug }}')</code>.
                                </small>
                            </div>

                            {{-- Descripción --}}
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea id="description" name="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="2" maxlength="500">{{ old('description', $permission->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Estado --}}
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                           id="is_active" name="is_active"
                                           {{ old('is_active', $permission->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Permiso activo
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Desactivar este permiso lo excluirá de todas las verificaciones de acceso.
                                </small>
                            </div>

                            {{-- Metadatos de auditoría --}}
                            <div class="row text-muted small border-top pt-2 mt-2">
                                <div class="col-sm-6">
                                    <i class="fas fa-calendar-plus mr-1"></i>
                                    Creado: {{ $permission->created_at ? $permission->created_at->format('d/m/Y H:i') : '—' }}
                                </div>
                                <div class="col-sm-6">
                                    <i class="fas fa-calendar-edit mr-1"></i>
                                    Actualizado: {{ $permission->updated_at ? $permission->updated_at->format('d/m/Y H:i') : '—' }}
                                </div>
                            </div>

                        </div>{{-- /card-body --}}

                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Roles que usan este permiso --}}
                @if($permission->roles->count())
                <div class="card card-light">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-shield mr-1 text-primary"></i>
                            Roles con este permiso
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Rol</th><th>Slug</th><th class="text-center">Estado</th></tr></thead>
                            <tbody>
                                @foreach($permission->roles as $rol)
                                <tr>
                                    <td><strong>{{ $rol->name }}</strong></td>
                                    <td><code>{{ $rol->slug }}</code></td>
                                    <td class="text-center">
                                        @if($rol->is_active)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Mostrar/ocultar módulo nuevo
    $('#module').on('change', function() {
        if ($(this).val() === '_nuevo') {
            $('#wrapModuloNuevo').show();
        } else {
            $('#wrapModuloNuevo').hide();
        }
    });

    // Actualizar slug al cambiar módulo (sólo la parte del módulo)
    $('#module').on('change', function() {
        actualizarSlugModulo();
    });
    $('#moduloNuevoInput').on('input', function() {
        actualizarSlugModulo();
    });

    function obtenerModulo() {
        var sel = $('#module').val();
        if (sel === '_nuevo') return sanitizar($('#moduloNuevoInput').val());
        return sel || '';
    }

    function sanitizar(str) {
        return str.toLowerCase().replace(/[^a-z0-9\-_]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
    }

    function actualizarSlugModulo() {
        var slug = $('#slug').val();
        var partes = slug.split('.');
        var accion = partes.length > 1 ? partes.slice(1).join('.') : partes[0];
        var nuevoModulo = obtenerModulo();
        if (nuevoModulo && accion) {
            $('#slug').val(nuevoModulo + '.' + accion);
        }
    }

    // Sincronizar módulo real antes de enviar
    $('#formEditarPermiso').on('submit', function() {
        if ($('#module').val() === '_nuevo' || !$('#module').val()) {
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

    // Si el módulo actual no está en la lista (datos históricos)
    var modActual = '{{ $permission->module }}';
    if (modActual && $('#module option[value="' + modActual + '"]').length === 0) {
        $('<option>', { value: modActual, selected: true, text: modActual })
            .insertBefore('#module option[value="_nuevo"]');
    }
});
</script>
@endpush
