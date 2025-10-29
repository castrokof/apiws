# Sistema de Roles y Permisos - Medcol SW

## Descripción

Sistema completo de gestión de roles, permisos y usuarios con menú lateral moderno e intuitivo, compatible con Laravel 7.x y AdminLTE 3.

## Características Principales

### ✨ Sistema de Permisos
- **Roles jerárquicos**: Super Admin, Admin, Analista, Auxiliar, Droguería, Reportes
- **Permisos granulares**: Control detallado por módulo y acción
- **Permisos directos**: Asignar permisos específicos a usuarios independientemente de su rol
- **Herencia de permisos**: Los usuarios heredan permisos de sus roles asignados
- **Middleware de verificación**: Protección de rutas por rol y permiso

### 🎨 Menú Lateral Moderno
- **Diseño AdminLTE 3**: Interfaz moderna y responsive
- **Navegación intuitiva**: Menús desplegables por módulo
- **Animaciones suaves**: Transiciones y efectos visuales atractivos
- **Permisos dinámicos**: Solo muestra opciones según permisos del usuario
- **Iconos Font Awesome**: Identificación visual clara de cada sección
- **Modo compacto**: Sidebar colapsable para mayor espacio

### 🔒 Seguridad
- **Control de acceso basado en roles (RBAC)**
- **Verificación de permisos en middleware**
- **Protección de rutas administrativas**
- **Prevención de eliminación de usuarios propios**
- **Protección de roles del sistema**

## Instalación

### Paso 1: Ejecutar las Migraciones

```bash
php artisan migrate
```

Esto creará las siguientes tablas:
- `roles` - Almacena los roles del sistema
- `permissions` - Almacena los permisos
- `role_user` - Relación muchos a muchos entre usuarios y roles
- `permission_role` - Relación muchos a muchos entre permisos y roles
- `permission_user` - Relación muchos a muchos entre permisos y usuarios (permisos directos)

### Paso 2: Ejecutar el Seeder

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Este seeder creará:
- **6 roles predefinidos** con sus respectivos permisos
- **51 permisos** organizados por módulos
- Asignaciones automáticas de permisos a roles

#### Roles Creados

| Rol | Slug | Descripción |
|-----|------|-------------|
| Super Administrador | super-admin | Acceso total al sistema |
| Administrador | admin | Acceso a la mayoría de funciones |
| Analista | analista | Gestión de medicamentos y pendientes |
| Auxiliar | auxiliar | Solo visualización |
| Droguería | drogueria | Gestión de droguería e inventario |
| Reportes | reportes | Acceso solo a reportes |

#### Módulos de Permisos

1. **Dashboard** - Vista de estadísticas y análisis
2. **Medcol2** - Gestión de pendientes y dispensado
3. **Medcol3** - Gestión de entidad Medcol3
4. **Medcol5 (EMCALI)** - Gestión de EMCALI
5. **Medcol6 (SOS/JAMUNDI)** - Gestión de SOS y JAMUNDI
6. **Medcold (Dolor)** - Gestión de medicamentos dolor
7. **Inventario** - Control de inventario y compras
8. **Reportes** - Generación y exportación de reportes
9. **Administración** - Gestión de usuarios, roles y permisos

### Paso 3: Asignar Rol a Usuarios Existentes

Puedes asignar roles a usuarios existentes de dos formas:

#### Opción A: Usando Tinker

```bash
php artisan tinker
```

```php
// Obtener un usuario
$user = App\User::find(1);

// Asignar rol
$user->assignRole('super-admin');

// O asignar múltiples roles
$user->assignRole('admin', 'drogueria');

// Verificar roles
$user->roles->pluck('name');
```

#### Opción B: Usando SQL Directo

```sql
-- Asignar rol Super Admin al usuario con ID 1
INSERT INTO role_user (role_id, user_id, created_at, updated_at)
SELECT id, 1, NOW(), NOW()
FROM roles
WHERE slug = 'super-admin';
```

### Paso 4: Incluir el Sidebar en tu Layout

Busca el archivo `resources/views/layouts/app.blade.php` (o el layout principal que uses) y reemplaza el menú actual por el nuevo sidebar:

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medcol SW</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('assets/lte/dist/css/adminlte.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Custom Sidebar CSS -->
    <link rel="stylesheet" href="{{ asset('css/modern-sidebar.css') }}">

    @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    @yield('breadcrumb')
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Medcol SW</a>.</strong>
            Todos los derechos reservados.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/lte/dist/js/adminlte.min.js') }}"></script>

    @yield('scripts')
</body>
</html>
```

## Uso del Sistema

### Verificar Permisos en Controladores

```php
// En cualquier controlador
public function index()
{
    // Verificar si el usuario tiene el permiso
    if (!auth()->user()->hasPermission('medcol2.pendientes.view')) {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }

    // Tu código aquí
}
```

### Verificar Permisos en Vistas Blade

```blade
@if(Auth::user()->hasPermission('usuarios.create'))
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Crear Usuario
    </a>
@endif

@if(Auth::user()->hasAnyPermission(['medcol2.pendientes.view', 'medcol3.pendientes.view']))
    <div class="alert alert-info">
        Tienes acceso a ver pendientes
    </div>
@endif

@if(Auth::user()->hasRole('super-admin'))
    <button class="btn btn-danger">Acción Administrativa</button>
@endif
```

### Proteger Rutas con Middleware

```php
// En routes/web.php

// Proteger por rol
Route::middleware(['role:super-admin|admin'])->group(function () {
    Route::get('/admin/configuracion', 'ConfigController@index');
});

// Proteger por permiso
Route::middleware(['permission:usuarios.create'])->group(function () {
    Route::get('/admin/users/create', 'UserManagementController@create');
});

// Proteger por múltiples permisos (OR)
Route::middleware(['permission:medcol2.pendientes.view|medcol3.pendientes.view'])->group(function () {
    Route::get('/pendientes', 'PendientesController@index');
});
```

### Gestión de Usuarios

```php
$user = App\User::find(1);

// Asignar roles
$user->assignRole('admin');
$user->assignRole('analista', 'drogueria'); // Múltiples

// Remover roles
$user->removeRole('analista');

// Asignar permisos directos
$user->givePermissionTo('medcol2.pendientes.create');

// Remover permisos directos
$user->revokePermissionTo('medcol2.pendientes.create');

// Verificar rol
if ($user->hasRole('admin')) {
    // Hacer algo
}

// Verificar múltiples roles
if ($user->hasAnyRole(['admin', 'super-admin'])) {
    // Hacer algo
}

// Verificar permiso
if ($user->hasPermission('usuarios.create')) {
    // Hacer algo
}

// Obtener todos los permisos (directos + heredados de roles)
$permissions = $user->getAllPermissions();
```

### Gestión de Roles

```php
$role = App\Models\Role::where('slug', 'admin')->first();

// Asignar permisos al rol
$role->givePermissionTo('medcol2.pendientes.view', 'medcol2.pendientes.create');

// Remover permisos del rol
$role->revokePermissionTo('medcol2.pendientes.delete');

// Verificar si el rol tiene un permiso
if ($role->hasPermission('usuarios.create')) {
    // Hacer algo
}

// Obtener usuarios con este rol
$users = $role->users;
```

## Rutas Administrativas

El sistema incluye las siguientes rutas protegidas:

| Ruta | Método | Descripción | Permiso Requerido |
|------|--------|-------------|-------------------|
| `/admin/users` | GET | Listar usuarios | usuarios.view |
| `/admin/users/create` | GET | Crear usuario | usuarios.create |
| `/admin/users/{id}` | GET | Ver usuario | usuarios.view |
| `/admin/users/{id}/edit` | GET | Editar usuario | usuarios.edit |
| `/admin/users/{id}` | PUT | Actualizar usuario | usuarios.edit |
| `/admin/users/{id}` | DELETE | Eliminar usuario | usuarios.delete |
| `/admin/roles` | GET | Listar roles | roles.view |
| `/admin/roles/create` | GET | Crear rol | roles.manage |
| `/admin/roles/{id}/edit` | GET | Editar rol | roles.manage |
| `/admin/permissions` | GET | Listar permisos | permisos.view |
| `/admin/permissions/create` | GET | Crear permiso | permisos.assign |

## Personalización

### Agregar Nuevos Permisos

1. Agrega el permiso a la base de datos:

```php
use App\Models\Permission;

Permission::create([
    'name' => 'Ver Facturación',
    'slug' => 'facturacion.view',
    'module' => 'Facturación',
    'description' => 'Permite ver el módulo de facturación',
]);
```

2. Asigna el permiso a los roles correspondientes:

```php
$role = App\Models\Role::where('slug', 'admin')->first();
$role->givePermissionTo('facturacion.view');
```

3. Agrega la opción al menú en `components/sidebar.blade.php`:

```blade
@if(Auth::user()->hasPermission('facturacion.view'))
<li class="nav-item">
    <a href="{{ route('facturacion.index') }}" class="nav-link">
        <i class="nav-icon fas fa-file-invoice-dollar"></i>
        <p>Facturación</p>
    </a>
</li>
@endif
```

### Personalizar Colores del Sidebar

Edita el archivo `public/css/modern-sidebar.css`:

```css
/* Cambiar color principal */
.main-sidebar .brand-link {
    background: linear-gradient(135deg, #TU_COLOR_1 0%, #TU_COLOR_2 100%);
}

/* Cambiar color de hover */
.nav-sidebar .nav-link:hover {
    background: rgba(TU_COLOR_RGB, 0.1);
}
```

## Solución de Problemas

### Error: "Undefined method hasPermission"

Asegúrate de que el modelo `User` tiene los métodos definidos. Verifica que `app/User.php` incluya:

```php
use App\Models\Role;
use App\Models\Permission;
```

### El menú no se muestra correctamente

1. Verifica que el CSS esté cargado:
```blade
<link rel="stylesheet" href="{{ asset('css/modern-sidebar.css') }}">
```

2. Asegúrate de tener AdminLTE correctamente instalado.

3. Verifica que el usuario tenga roles asignados:
```php
$user = Auth::user();
dd($user->roles); // Debe mostrar al menos un rol
```

### Permisos no funcionan

1. Verifica que el middleware esté registrado en `app/Http/Kernel.php`:
```php
'permission' => \App\Http\Middleware\CheckPermission::class,
```

2. Asegúrate de que el usuario tenga el permiso asignado:
```php
$user = Auth::user();
dd($user->getAllPermissions());
```

3. Limpia la caché de rutas y configuración:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## Mantenimiento

### Actualizar Permisos

Para actualizar los permisos existentes sin perder datos:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Nota: El seeder usa `truncate()`, lo que eliminará todos los datos actuales de roles y permisos. Si necesitas mantener datos personalizados, crea un seeder separado o actualiza manualmente.

### Backup de Roles y Permisos

```bash
# Exportar roles y permisos
php artisan tinker

App\Models\Role::all()->toJson();
App\Models\Permission::all()->toJson();
```

## Seguridad

### Recomendaciones

1. **Nunca** asignes el rol `super-admin` a usuarios no confiables
2. Revisa periódicamente los permisos asignados
3. Usa permisos específicos en lugar de roles para acciones críticas
4. Implementa logs de auditoría para cambios en roles y permisos
5. Aplica el principio de menor privilegio

### Auditoría

Para implementar auditoría básica, puedes agregar eventos en los modelos:

```php
// En app/Models/Role.php
protected static function boot()
{
    parent::boot();

    static::created(function ($role) {
        \Log::info('Rol creado: ' . $role->name, ['user' => auth()->id()]);
    });

    static::updated(function ($role) {
        \Log::info('Rol actualizado: ' . $role->name, ['user' => auth()->id()]);
    });
}
```

## Contribuir

Para agregar nuevas funcionalidades o reportar bugs, contacta al equipo de desarrollo.

## Licencia

Propietario: Medcol SW © 2025

---

**Última actualización**: 2025-10-29
**Versión**: 1.0.0
**Compatible con**: Laravel 7.x, AdminLTE 3, Bootstrap 4
