@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Usuarios</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
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
                        <h3 class="card-title">Lista de Usuarios</h3>
                        <div class="card-tools">
                            @if(Auth::user()->hasPermission('usuarios.create'))
                            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear Usuario
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

                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="roleFilter">Filtrar por Rol:</label>
                                    <select id="roleFilter" class="form-control">
                                        <option value="">Todos los roles</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="statusFilter">Filtrar por Estado:</label>
                                    <select id="statusFilter" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="1">Activos</option>
                                        <option value="0">Inactivos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="searchUser">Buscar:</label>
                                    <input type="text" id="searchUser" class="form-control" placeholder="Nombre o email...">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="usersTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th style="width: 100px;">Permisos</th>
                                        <th style="width: 80px;">Estado</th>
                                        <th style="width: 120px;">Último acceso</th>
                                        <th style="width: 150px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr data-user-id="{{ $user->id }}" data-status="{{ $user->status ?? 1 }}">
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->id === Auth::id())
                                                <span class="badge badge-warning ml-1">Tú</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->roles->count() > 0)
                                                @foreach($user->roles as $role)
                                                    <span class="badge badge-primary">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Sin rol asignado</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $totalPermissions = $user->getAllPermissions()->count();
                                            @endphp
                                            <span class="badge badge-info" title="{{ $totalPermissions }} permisos totales">{{ $totalPermissions }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if(isset($user->status) && $user->status == 1)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($user->last_login)
                                                <small>{{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }}</small>
                                            @else
                                                <small class="text-muted">Nunca</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(Auth::user()->hasPermission('usuarios.edit'))
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                @if(Auth::user()->hasPermission('usuarios.delete') && $user->id !== Auth::id())
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
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
                                            <p class="text-muted my-3">No hay usuarios registrados en el sistema.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($users->hasPages())
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter by role
        $('#roleFilter').on('change', function() {
            filterUsers();
        });

        // Filter by status
        $('#statusFilter').on('change', function() {
            filterUsers();
        });

        // Search by name or email
        $('#searchUser').on('keyup', function() {
            filterUsers();
        });

        function filterUsers() {
            var selectedRole = $('#roleFilter').val();
            var selectedStatus = $('#statusFilter').val();
            var searchTerm = $('#searchUser').val().toLowerCase();

            $('#usersTable tbody tr').each(function() {
                var row = $(this);
                var showRow = true;

                // Filter by role
                if (selectedRole !== '') {
                    var userRoles = row.find('td:eq(3)').text();
                    if (!userRoles.includes(selectedRole)) {
                        showRow = false;
                    }
                }

                // Filter by status
                if (selectedStatus !== '') {
                    var userStatus = row.data('status');
                    if (userStatus != selectedStatus) {
                        showRow = false;
                    }
                }

                // Filter by search term
                if (searchTerm !== '') {
                    var userName = row.find('td:eq(1)').text().toLowerCase();
                    var userEmail = row.find('td:eq(2)').text().toLowerCase();
                    if (!userName.includes(searchTerm) && !userEmail.includes(searchTerm)) {
                        showRow = false;
                    }
                }

                if (showRow) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }
    });
</script>
@endpush
@endsection
