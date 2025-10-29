<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\User;

echo "\n📋 USUARIOS EN EL SISTEMA\n";
echo str_repeat("=", 80) . "\n\n";

$users = User::orderBy('id')->take(10)->get();

if ($users->count() === 0) {
    echo "❌ No hay usuarios en el sistema\n\n";
    exit(1);
}

echo sprintf("%-5s %-30s %-35s %s\n", "ID", "Nombre", "Email", "Roles");
echo str_repeat("-", 80) . "\n";

foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->join(', ');
    $rolesDisplay = $roles ?: 'Sin rol';

    echo sprintf(
        "%-5s %-30s %-35s %s\n",
        $user->id,
        substr($user->name, 0, 29),
        substr($user->email, 0, 34),
        substr($rolesDisplay, 0, 20)
    );
}

echo "\n";
echo "Total de usuarios mostrados: " . $users->count() . "\n";
echo "Total de usuarios en el sistema: " . User::count() . "\n\n";

echo "Para asignar el rol super-admin a un usuario, ejecuta:\n";
echo "  php assign_superadmin.php [ID o EMAIL]\n\n";
echo "Ejemplo:\n";
echo "  php assign_superadmin.php 1\n";
echo "  php assign_superadmin.php admin@medcol.com\n\n";
