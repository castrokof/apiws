@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Usuario</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
                        <h3 class="card-title">Editar información del usuario: <strong>{{ $user->name }}</strong></h3>
                    </div>
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <h5><i class="icon fas fa-ban"></i> Errores en el formulario:</h5>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <!-- Información básica -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Información Básica</h5>

                                    <div class="form-group">
                                        <label for="name">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Nueva Contraseña <small class="text-muted">(Dejar en blanco para mantener actual)</small></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                                    </div>

                                    <div class="form-group">
                                        <label for="drogueria">Droguería</label>
                                        <input type="text" class="form-control @error('drogueria') is-invalid @enderror"
                                               id="drogueria" name="drogueria" value="{{ old('drogueria', $user->drogueria) }}">
                                        @error('drogueria')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="rol">Rol Antiguo <small class="text-muted">(Sistema legacy)</small></label>
                                        <select class="form-control" id="rol" name="rol">
                                            <option value="">Sin rol antiguo</option>
                                            <option value="1" {{ old('rol', $user->rol) == '1' ? 'selected' : '' }}>Administrador (1)</option>
                                            <option value="2" {{ old('rol', $user->rol) == '2' ? 'selected' : '' }}>Usuario (2)</option>
                                            <option value="3" {{ old('rol', $user->rol) == '3' ? 'selected' : '' }}>Auxiliar (3)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Roles -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Roles</h5>
                                    <div class="form-group">
                                        <label>Asignar roles al usuario:</label>
                                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                            @foreach($roles as $role)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}"
                                                       {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="role_{{ $role->id }}">
                                                    <strong>{{ $role->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $role->description }}</small>
                                                </label>
                                            </div>
                                            <hr class="my-2">
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permisos directos -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3">Permisos Directos <small class="text-muted">(Además de los heredados por roles)</small></h5>

                                    <div class="accordion" id="permissionsAccordion">
                                        @foreach($allPermissions as $module => $permissions)
                                        <div class="card">
                                            <div class="card-header p-2" id="heading{{ $loop->index }}">
                                                <h6 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button"
                                                            data-toggle="collapse" data-target="#collapse{{ $loop->index }}"
                                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                                        <i class="fas fa-folder mr-2"></i> {{ ucfirst($module) }}
                                                        <span class="badge badge-info float-right">{{ $permissions->count() }} permisos</span>
                                                    </button>
                                                </h6>
                                            </div>
                                            <div id="collapse{{ $loop->index }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                                                 data-parent="#permissionsAccordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach($permissions as $permission)
                                                        <div class="col-md-6">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="permission_{{ $permission->id }}"
                                                                       name="permissions[]" value="{{ $permission->id }}"
                                                                       {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                    <br>
                                                                    <small class="text-muted"><code>{{ $permission->slug }}</code></small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
