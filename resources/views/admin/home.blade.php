@extends('layouts.admin')

@section('title', 'Inicio - Sistema de Gestión')

@section('page-title', 'Panel de Administración')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Usuarios</span>
                <span class="info-box-number">
                    {{ App\User::count() }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-shield"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Roles</span>
                <span class="info-box-number">{{ App\Models\Role::count() }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-key"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Permisos</span>
                <span class="info-box-number">{{ App\Models\Permission::count() }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-pills"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Módulos</span>
                <span class="info-box-number">9</span>
            </div>
        </div>
    </div>
</div>

<!-- Bienvenida -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-home mr-1"></i>
                    Bienvenido al Sistema de Gestión Medcol SW
                </h3>
            </div>
            <div class="card-body">
                <p class="lead">
                    ¡Hola <strong>{{ Auth::user()->name }}</strong>!
                </p>
                <p>
                    Has iniciado sesión exitosamente en el sistema de gestión de medicamentos pendientes.
                    Ahora tienes acceso al nuevo menú lateral con control de permisos.
                </p>

                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Información de tu cuenta</h5>
                    <ul class="mb-0">
                        <li><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        <li><strong>Roles:</strong>
                            @if(Auth::user()->roles->count() > 0)
                                {{ Auth::user()->roles->pluck('name')->join(', ') }}
                            @else
                                <span class="badge badge-warning">Sin rol asignado</span>
                            @endif
                        </li>
                        <li><strong>Permisos directos:</strong> {{ Auth::user()->permissions->count() }}</li>
                        <li><strong>Total de permisos:</strong> {{ Auth::user()->getAllPermissions()->count() }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información del Sistema -->
<div class="row">
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-check-circle mr-1"></i>
                    Características Implementadas
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        Sistema de roles y permisos (RBAC)
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        Menú lateral moderno con AdminLTE 3
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        Control de acceso por permisos
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        6 roles predefinidos con permisos asignados
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        51 permisos organizados por módulos
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-2"></i>
                        Blade directives personalizados
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Acceso Rápido
                </h3>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @if(Auth::user()->hasPermission('usuarios.view'))
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users mr-2"></i> Gestionar Usuarios
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('roles.view'))
                    <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-shield mr-2"></i> Gestionar Roles
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('permisos.view'))
                    <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-key mr-2"></i> Gestionar Permisos
                    </a>
                    @endif

                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-line mr-2"></i> Dashboard Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test de Verificación de Permisos -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary collapsed-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-code mr-1"></i>
                    Test de Verificación de Permisos (Para Desarrolladores)
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Verificación de Roles</h5>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Resultado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>super-admin</td>
                                    <td>
                                        @if(Auth::user()->hasRole('super-admin'))
                                            <span class="badge badge-success">✓ Sí</span>
                                        @else
                                            <span class="badge badge-danger">✗ No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>admin</td>
                                    <td>
                                        @if(Auth::user()->hasRole('admin'))
                                            <span class="badge badge-success">✓ Sí</span>
                                        @else
                                            <span class="badge badge-danger">✗ No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>analista</td>
                                    <td>
                                        @if(Auth::user()->hasRole('analista'))
                                            <span class="badge badge-success">✓ Sí</span>
                                        @else
                                            <span class="badge badge-danger">✗ No</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Verificación de Permisos</h5>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Permiso</th>
                                    <th>Resultado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>dashboard.view</td>
                                    <td>
                                        @if(Auth::user()->hasPermission('dashboard.view'))
                                            <span class="badge badge-success">✓ Sí</span>
                                        @else
                                            <span class="badge badge-danger">✗ No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>usuarios.view</td>
                                    <td>
                                        @if(Auth::user()->hasPermission('usuarios.view'))
                                            <span class="badge badge-success">✓ Sí</span>
                                        @else
                                            <span class="badge badge-danger">✗ No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>usuarios.create</td>
                                    <td>
                                        @if(Auth::user()->hasPermission('usuarios.create'))
                                            <span class="badge badge-success">✓ Sí</span>
                                        @else
                                            <span class="badge badge-danger">✗ No</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <h5>Tus Roles y Permisos Actuales</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Roles Asignados</h5>
                            </div>
                            <div class="card-body">
                                @if(Auth::user()->roles->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach(Auth::user()->roles as $role)
                                        <li>
                                            <span class="badge badge-primary">{{ $role->name }}</span>
                                            <small class="text-muted">({{ $role->permissions->count() }} permisos)</small>
                                        </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">No tienes roles asignados</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Total de Permisos</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <strong>Permisos directos:</strong>
                                    <span class="badge badge-info">{{ Auth::user()->permissions->count() }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Permisos heredados:</strong>
                                    <span class="badge badge-success">
                                        {{ Auth::user()->getAllPermissions()->count() - Auth::user()->permissions->count() }}
                                    </span>
                                </p>
                                <p class="mb-0">
                                    <strong>Total:</strong>
                                    <span class="badge badge-primary">{{ Auth::user()->getAllPermissions()->count() }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    console.log('Sistema de Roles y Permisos cargado correctamente');
    console.log('Usuario:', '{{ Auth::user()->name }}');
    console.log('Roles:', @json(Auth::user()->roles->pluck('name')));
    console.log('Total permisos:', {{ Auth::user()->getAllPermissions()->count() }});
</script>
@endsection
