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
                        <h3 class="card-title">Lista de Permisos</h3>
                        <div class="card-tools">
                            @if(Auth::user()->hasPermission('permisos.create'))
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear Permiso
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Filter by Module -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="moduleFilter">Filtrar por Módulo:</label>
                                    <select id="moduleFilter" class="form-control">
                                        <option value="">Todos los módulos</option>
                                        <option value="dashboard">Dashboard</option>
                                        <option value="medcol2">Medcol2</option>
                                        <option value="medcol3">Medcol3</option>
                                        <option value="medcol5">Medcol5</option>
                                        <option value="medcol6">Medcol6</option>
                                        <option value="medcold">Medcold</option>
                                        <option value="inventario">Inventario</option>
                                        <option value="reportes">Reportes</option>
                                        <option value="usuarios">Usuarios</option>
                                        <option value="roles">Roles</option>
                                        <option value="permisos">Permisos</option>
                                        <option value="configuracion">Configuración</option>
                                        <option value="analisis-nt">Análisis NT</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="permissionsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">ID</th>
                                        <th>Nombre</th>
                                        <th>Slug</th>
                                        <th>Descripción</th>
                                        <th style="width: 100px;">Roles</th>
                                        <th style="width: 80px;">Estado</th>
                                        <th style="width: 150px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permissions as $permission)
                                    <tr data-module="{{ explode('.', $permission->slug)[0] ?? '' }}">
                                        <td>{{ $permission->id }}</td>
                                        <td><strong>{{ $permission->name }}</strong></td>
                                        <td><code>{{ $permission->slug }}</code></td>
                                        <td>{{ $permission->description }}</td>
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
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(Auth::user()->hasPermission('permisos.edit'))
                                                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                @if(Auth::user()->hasPermission('permisos.delete'))
                                                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de eliminar este permiso? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <p class="text-muted my-3">No hay permisos registrados en el sistema.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter permissions by module
        $('#moduleFilter').on('change', function() {
            var selectedModule = $(this).val();

            if (selectedModule === '') {
                $('#permissionsTable tbody tr').show();
            } else {
                $('#permissionsTable tbody tr').each(function() {
                    var rowModule = $(this).data('module');
                    if (rowModule === selectedModule) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection
