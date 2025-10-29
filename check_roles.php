<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\Permission;

echo "\n✅ VERIFICACIÓN DEL SISTEMA DE ROLES Y PERMISOS\n";
echo str_repeat("=", 60) . "\n\n";

// Verificar Roles
echo "📋 ROLES CREADOS (" . Role::count() . "):\n";
echo str_repeat("-", 60) . "\n";
foreach (Role::all() as $role) {
    $permissionsCount = $role->permissions()->count();
    echo "  • {$role->name} ({$role->slug})\n";
    echo "    Permisos asignados: {$permissionsCount}\n";
}

echo "\n🔐 PERMISOS CREADOS (" . Permission::count() . "):\n";
echo str_repeat("-", 60) . "\n";

$permissionsByModule = Permission::all()->groupBy('module');
foreach ($permissionsByModule as $module => $permissions) {
    echo "  • {$module}: {$permissions->count()} permisos\n";
}

echo "\n✨ ¡Sistema instalado correctamente!\n\n";
