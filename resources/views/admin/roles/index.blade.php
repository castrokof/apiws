@extends('layouts.admin')

@section('title', 'Gestión de Roles')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Roles</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Roles</li>
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
                        <h3 class="card-title">Lista de Roles</h3>
                        <div class="card-tools">
                            @if(Auth::user()->hasPermission('roles.create'))
                            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear Rol
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

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">ID</th>
                                        <th>Nombre</th>
                                        <th>Slug</th>
                                        <th>Descripción</th>
                                        <th style="width: 100px;">Usuarios</th>
                                        <th style="width: 100px;">Permisos</th>
                                        <th style="width: 80px;">Estado</th>
                                        <th style="width: 150px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td><strong>{{ $role->name }}</strong></td>
                                        <td><code>{{ $role->slug }}</code></td>
                                        <td>{{ $role->description }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $role->users_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary">{{ $role->permissions_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($role->is_active)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(Auth::user()->hasPermission('roles.edit'))
                                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-info" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                @if(Auth::user()->hasPermission('roles.delete') && $role->slug !== 'super-administrador')
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de eliminar este rol? Esta acción no se puede deshacer.');">
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
                                        <td colspan="8" class="text-center">
                                            <p class="text-muted my-3">No hay roles registrados en el sistema.</p>
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
@endsection
