# Instalación Rápida - Sistema de Roles y Permisos

## 📋 Resumen de Cambios

Se ha implementado un sistema completo de roles, permisos y menú lateral moderno con las siguientes características:

### ✅ Archivos Creados

#### Migraciones (5 archivos)
- `2025_10_29_000001_create_roles_table.php`
- `2025_10_29_000002_create_permissions_table.php`
- `2025_10_29_000003_create_role_user_table.php`
- `2025_10_29_000004_create_permission_role_table.php`
- `2025_10_29_000005_create_permission_user_table.php`

#### Modelos (2 archivos)
- `app/Models/Role.php` - Modelo de roles
- `app/Models/Permission.php` - Modelo de permisos

#### Middleware (2 archivos)
- `app/Http/Middleware/CheckRole.php` - Verificación de roles
- `app/Http/Middleware/CheckPermission.php` - Verificación de permisos

#### Controladores (3 archivos)
- `app/Http/Controllers/RoleController.php` - Gestión de roles
- `app/Http/Controllers/PermissionController.php` - Gestión de permisos
- `app/Http/Controllers/UserManagementController.php` - Gestión de usuarios

#### Vistas y Componentes
- `resources/views/components/sidebar.blade.php` - Menú lateral moderno
- `public/css/modern-sidebar.css` - Estilos del menú

#### Seeder
- `database/seeds/RolesAndPermissionsSeeder.php` - Datos iniciales (6 roles, 54 permisos)

#### Documentación
- `SISTEMA_ROLES_PERMISOS.md` - Documentación completa
- `INSTALACION_RAPIDA.md` - Este archivo

### ✅ Archivos Modificados

- `app/User.php` - Agregados métodos de roles y permisos
- `app/Http/Kernel.php` - Registrados middleware de roles y permisos
- `routes/web.php` - Agregadas rutas administrativas

## 🚀 Instalación en 5 Pasos

### Paso 1: Ejecutar Migraciones (2 minutos)

```bash
php artisan migrate
```

**Resultado esperado:**
```
Migration table created successfully.
Migrating: 2025_10_29_000001_create_roles_table
Migrated:  2025_10_29_000001_create_roles_table (XX.XX seconds)
Migrating: 2025_10_29_000002_create_permissions_table
Migrated:  2025_10_29_000002_create_permissions_table (XX.XX seconds)
...
```

### Paso 2: Ejecutar Seeder (1 minuto)

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

**Resultado esperado:**
```
Roles y permisos creados exitosamente!
Roles creados: Super Administrador, Administrador, Analista, Auxiliar, Droguería, Reportes
Total de permisos creados: 51
```

### Paso 3: Asignar Rol a Tu Usuario (1 minuto)

Opción A - Usando Tinker:
```bash
php artisan tinker
```
```php
$user = App\User::where('email', 'TU_EMAIL@example.com')->first();
$user->assignRole('super-admin');
exit
```

Opción B - Usando SQL directo:
```sql
-- Reemplaza 1 con tu ID de usuario
INSERT INTO role_user (role_id, user_id, created_at, updated_at)
SELECT id, 1, NOW(), NOW()
FROM roles
WHERE slug = 'super-admin';
```

### Paso 4: Verificar que AdminLTE esté Instalado

Verifica que existan estos archivos:
- `public/assets/lte/dist/css/adminlte.min.css`
- `public/assets/lte/plugins/fontawesome-free/css/all.min.css`
- `public/assets/lte/dist/js/adminlte.min.js`

Si no existen, AdminLTE ya viene con Laravel en el proyecto.

### Paso 5: Actualizar el Layout Principal (5 minutos)

**IMPORTANTE**: Necesitas integrar el sidebar en tu layout actual. Aquí tienes dos opciones:

#### Opción A: Crear un Nuevo Layout (Recomendado)

Crea `resources/views/layouts/admin.blade.php`:

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Medcol SW')</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('assets/lte/dist/css/adminlte.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('dashboard') }}" class="nav-link">Inicio</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                        <span class="d-none d-md-inline ml-1">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Mi Perfil
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
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    @yield('breadcrumb')
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Mensajes de éxito/error -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="icon fas fa-check"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="icon fas fa-ban"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="icon fas fa-ban"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
            <div class="float-right d-none d-sm-inline-block">
                <b>Versión</b> 1.0.0
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/lte/dist/js/adminlte.min.js') }}"></script>

    @yield('scripts')
</body>
</html>
```

#### Opción B: Modificar Layout Existente

Busca `resources/views/layouts/app.blade.php` y:

1. Agrega el CSS del sidebar en el `<head>`:
```blade
<link rel="stylesheet" href="{{ asset('css/modern-sidebar.css') }}">
```

2. Cambia la clase del `<body>`:
```blade
<body class="hold-transition sidebar-mini layout-fixed">
```

3. Incluye el sidebar después del navbar:
```blade
@include('components.sidebar')
```

## ✅ Verificación de Instalación

### 1. Verificar Base de Datos

```sql
-- Debe devolver 6 roles
SELECT * FROM roles;

-- Debe devolver 54 permisos
SELECT * FROM permissions;

-- Verifica que tu usuario tenga un rol
SELECT u.name, u.email, r.name as rol
FROM users u
JOIN role_user ru ON u.id = ru.user_id
JOIN roles r ON r.id = ru.role_id;
```

### 2. Verificar en el Navegador

1. Inicia sesión en la aplicación
2. Deberías ver el menú lateral moderno con las opciones según tus permisos
3. Intenta acceder a: `http://localhost/medicamentos_pendientes/public/admin/users`
4. Si ves la página de gestión de usuarios, ¡todo funciona correctamente!

### 3. Probar Permisos

```bash
php artisan tinker
```

```php
$user = Auth::user();

// Verificar roles
$user->roles->pluck('name');

// Verificar permisos
$user->getAllPermissions()->pluck('name');

// Probar verificación
$user->hasPermission('dashboard.view');  // Debe retornar true
$user->hasRole('super-admin');            // Debe retornar true
```

## 🎨 Personalización Rápida

### Cambiar Colores del Sidebar

Edita `public/css/modern-sidebar.css`, línea 7:

```css
.main-sidebar .brand-link {
    background: linear-gradient(135deg, #TU_COLOR_1 0%, #TU_COLOR_2 100%);
}
```

### Agregar/Quitar Opciones del Menú

Edita `resources/views/components/sidebar.blade.php` y agrega/quita bloques como:

```blade
@if(Auth::user()->hasPermission('tu-permiso'))
<li class="nav-item">
    <a href="{{ route('tu-ruta') }}" class="nav-link">
        <i class="nav-icon fas fa-tu-icono"></i>
        <p>Tu Opción</p>
    </a>
</li>
@endif
```

## 🐛 Solución de Problemas

### Error: "Class 'App\Models\Role' not found"

```bash
composer dump-autoload
```

### Error: "Base table or column not found"

```bash
php artisan migrate:fresh
php artisan db:seed --class=RolesAndPermissionsSeeder
```

⚠️ **ADVERTENCIA**: `migrate:fresh` eliminará todos los datos.

### El menú no se ve correctamente

1. Verifica que el CSS esté cargado (inspecciona con F12)
2. Verifica que AdminLTE esté instalado
3. Limpia la caché del navegador (Ctrl + Shift + R)

### Permisos no funcionan

```bash
# Limpiar caché
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# Verificar middleware en Kernel.php
# Debe existir:
'permission' => \App\Http\Middleware\CheckPermission::class,
```

## 📚 Próximos Pasos

1. **Crear vistas de administración**: Necesitas crear las vistas para gestionar usuarios, roles y permisos (están pendientes)
2. **Personalizar permisos**: Ajusta los permisos según las necesidades específicas de tu proyecto
3. **Agregar auditoría**: Implementa logs para rastrear cambios en roles y permisos
4. **Proteger rutas existentes**: Agrega middleware de permisos a las rutas existentes

## 📞 Soporte

Para más información, consulta:
- `SISTEMA_ROLES_PERMISOS.md` - Documentación completa
- Código fuente en los archivos creados

---

**Tiempo total de instalación**: ~10 minutos
**Última actualización**: 2025-10-29
