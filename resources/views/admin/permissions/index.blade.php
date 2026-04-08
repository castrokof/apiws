@extends('layouts.admin')

@section('title', 'Gestión de Permisos')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Permisos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Permisos</li>
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
                        <h3 class="card-title">
                            <i class="fas fa-key mr-2"></i>Lista de Permisos
                            <span class="badge badge-secondary ml-2">{{ $permissions->count() }}</span>
                        </h3>
                        <div class="card-tools">
                            @if(Auth::user()->hasPermission('permisos.create'))
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Nuevo Permiso
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Filtro por módulo --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="moduleFilter" class="font-weight-bold small">
                                    <i class="fas fa-filter mr-1 text-primary"></i>Filtrar por Módulo:
                                </label>
                                <select id="moduleFilter" class="form-control form-control-sm">
                                    <option value="">Todos los módulos ({{ $permissions->count() }})</option>
                                    @foreach($modules as $mod)
                                        <option value="{{ $mod }}">
                                            {{ ucfirst($mod) }}
                                            ({{ $permissions->where('module', $mod)->count() }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="statusFilter" class="font-weight-bold small">
                                    <i class="fas fa-toggle-on mr-1 text-primary"></i>Filtrar por Estado:
                                </label>
                                <select id="statusFilter" class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    <option value="1">Activos</option>
                                    <option value="0">Inactivos</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm" id="permissionsTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width:45px;">#</th>
                                        <th>Nombre</th>
                                        <th>Slug</th>
                                        <th style="width:120px;">Módulo</th>
                                        <th>Descripción</th>
                                        <th style="width:75px;" class="text-center">Roles</th>
                                        <th style="width:80px;" class="text-center">Estado</th>
                                        <th style="width:110px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permissions as $permission)
                                    <tr data-module="{{ $permission->module }}"
                                        data-active="{{ $permission->is_active ? '1' : '0' }}">
                                        <td class="text-muted small">{{ $permission->id }}</td>
                                        <td><strong>{{ $permission->name }}</strong></td>
                                        <td><code class="text-primary">{{ $permission->slug }}</code></td>
                                        <td>
                                            <span class="badge badge-light border">
                                                {{ $permission->module ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ $permission->description ?? '—' }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $permission->roles_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($permission->is_active)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(Auth::user()->hasPermission('permisos.edit'))
                                                <a href="{{ route('permissions.edit', $permission->id) }}"
                                                   class="btn btn-info btn-sm" title="Editar permiso">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                @if(Auth::user()->hasPermission('permisos.delete'))
                                                <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                      method="POST" style="display:inline;"
                                                      onsubmit="return confirmarEliminar(this, '{{ $permission->name }}', {{ $permission->roles_count }});">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar permiso">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                            <p class="text-muted mb-0">No hay permisos registrados en el sistema.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Contador de filas visibles --}}
                        <div class="mt-2 text-right">
                            <small class="text-muted" id="contadorFilas"></small>
                        </div>
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

    var table = $('#permissionsTable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 25,
        order: [[3, 'asc'], [1, 'asc']],   // ordenar por módulo, luego nombre
        columnDefs: [
            { orderable: false, targets: [7] }  // columna Acciones no ordenable
        ],
        dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip'
    });

    // Filtro por módulo
    $('#moduleFilter').on('change', function() {
        var mod = $(this).val();
        table.column(3).search(mod ? '^' + mod + '$' : '', true, false).draw();
        actualizarContador();
    });

    // Filtro por estado
    $('#statusFilter').on('change', function() {
        var estado = $(this).val();
        // Buscamos en el atributo data-active de la fila a través de la columna estado (índice 6)
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'permissionsTable') return true;
            if (estado === '') return true;
            var row = table.row(dataIndex).node();
            return $(row).data('active') == estado;
        });
        table.draw();
        $.fn.dataTable.ext.search.pop();
        actualizarContador();
    });

    // Actualizar contador cuando la tabla se re-dibuja
    table.on('draw', actualizarContador);
    actualizarContador();

    function actualizarContador() {
        var info = table.page.info();
        $('#contadorFilas').text('Mostrando ' + info.recordsDisplay + ' de ' + info.recordsTotal + ' permisos');
    }
});

function confirmarEliminar(form, nombre, rolesCount) {
    var msg = '¿Eliminar el permiso "' + nombre + '"?';
    if (rolesCount > 0) {
        msg += '\n\nAtención: este permiso está asignado a ' + rolesCount + ' rol(es). Se desvinculará automáticamente.';
    }
    return confirm(msg);
}
</script>
@endpush
