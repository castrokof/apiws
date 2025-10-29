<?php

/**
 * Script de ayuda para asignar el rol Super Admin a un usuario
 *
 * Uso:
 *   php assign_superadmin.php user_id
 *   php assign_superadmin.php user@email.com
 *
 * Ejemplos:
 *   php assign_superadmin.php 1
 *   php assign_superadmin.php admin@medcol.com
 */

// Verificar que se proporcionó un argumento
if ($argc < 2) {
    echo "❌ Error: Debes proporcionar el ID o email del usuario\n\n";
    echo "Uso:\n";
    echo "  php assign_superadmin.php [user_id o email]\n\n";
    echo "Ejemplos:\n";
    echo "  php assign_superadmin.php 1\n";
    echo "  php assign_superadmin.php admin@medcol.com\n\n";
    exit(1);
}

$identifier = $argv[1];

// Cargar Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\User;
use App\Models\Role;

try {
    // Buscar usuario por ID o email
    if (is_numeric($identifier)) {
        $user = User::find($identifier);
        $searchType = "ID: $identifier";
    } else {
        $user = User::where('email', $identifier)->first();
        $searchType = "Email: $identifier";
    }

    if (!$user) {
        echo "❌ Error: No se encontró un usuario con $searchType\n";
        exit(1);
    }

    // Buscar rol super-admin
    $role = Role::where('slug', 'super-admin')->first();

    if (!$role) {
        echo "❌ Error: El rol 'super-admin' no existe en la base de datos.\n";
        echo "   Por favor, ejecuta primero: php artisan db:seed --class=RolesAndPermissionsSeeder\n";
        exit(1);
    }

    // Verificar si ya tiene el rol
    if ($user->hasRole('super-admin')) {
        echo "ℹ️  El usuario '{$user->name}' ({$user->email}) ya tiene el rol Super Administrador\n";

        // Mostrar todos sus roles
        $roles = $user->roles->pluck('name')->toArray();
        echo "\n📋 Roles actuales: " . implode(', ', $roles) . "\n";

        exit(0);
    }

    // Asignar el rol
    $user->assignRole('super-admin');

    echo "✅ ¡Éxito! Rol Super Administrador asignado correctamente\n\n";
    echo "👤 Usuario: {$user->name}\n";
    echo "📧 Email: {$user->email}\n";
    echo "🔑 Rol: Super Administrador\n\n";

    // Mostrar todos los roles del usuario
    $allRoles = $user->roles->pluck('name')->toArray();
    echo "📋 Todos los roles del usuario: " . implode(', ', $allRoles) . "\n\n";

    // Mostrar estadísticas
    $permissionsCount = $user->getAllPermissions()->count();
    echo "✨ El usuario ahora tiene acceso a {$permissionsCount} permisos\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n📍 Trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
