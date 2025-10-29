<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Deshabilitar verificación de foreign keys temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar tablas
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        Permission::truncate();
        Role::truncate();

        // Habilitar verificación de foreign keys nuevamente
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crear Permisos por Módulo
        $permissions = [
            // Dashboard
            ['name' => 'Ver Dashboard', 'slug' => 'dashboard.view', 'module' => 'Dashboard', 'description' => 'Ver el dashboard principal con estadísticas'],
            ['name' => 'Ver Análisis NT', 'slug' => 'analisis-nt.view', 'module' => 'Dashboard', 'description' => 'Ver análisis de notas técnicas'],

            // Medcol2
            ['name' => 'Ver Medcol2 Pendientes', 'slug' => 'medcol2.pendientes.view', 'module' => 'Medcol2', 'description' => 'Ver listado de pendientes Medcol2'],
            ['name' => 'Crear Medcol2 Pendientes', 'slug' => 'medcol2.pendientes.create', 'module' => 'Medcol2', 'description' => 'Crear pendientes Medcol2'],
            ['name' => 'Editar Medcol2 Pendientes', 'slug' => 'medcol2.pendientes.edit', 'module' => 'Medcol2', 'description' => 'Editar pendientes Medcol2'],
            ['name' => 'Eliminar Medcol2 Pendientes', 'slug' => 'medcol2.pendientes.delete', 'module' => 'Medcol2', 'description' => 'Eliminar pendientes Medcol2'],
            ['name' => 'Ver Medcol2 Dispensado', 'slug' => 'medcol2.dispensado.view', 'module' => 'Medcol2', 'description' => 'Ver medicamentos dispensados Medcol2'],
            ['name' => 'Gestionar Medcol2 Dispensado', 'slug' => 'medcol2.dispensado.manage', 'module' => 'Medcol2', 'description' => 'Gestionar dispensación Medcol2'],

            // Medcol3
            ['name' => 'Ver Medcol3 Pendientes', 'slug' => 'medcol3.pendientes.view', 'module' => 'Medcol3', 'description' => 'Ver listado de pendientes Medcol3'],
            ['name' => 'Crear Medcol3 Pendientes', 'slug' => 'medcol3.pendientes.create', 'module' => 'Medcol3', 'description' => 'Crear pendientes Medcol3'],
            ['name' => 'Editar Medcol3 Pendientes', 'slug' => 'medcol3.pendientes.edit', 'module' => 'Medcol3', 'description' => 'Editar pendientes Medcol3'],
            ['name' => 'Eliminar Medcol3 Pendientes', 'slug' => 'medcol3.pendientes.delete', 'module' => 'Medcol3', 'description' => 'Eliminar pendientes Medcol3'],
            ['name' => 'Ver Medcol3 Dispensado', 'slug' => 'medcol3.dispensado.view', 'module' => 'Medcol3', 'description' => 'Ver medicamentos dispensados Medcol3'],
            ['name' => 'Gestionar Medcol3 Dispensado', 'slug' => 'medcol3.dispensado.manage', 'module' => 'Medcol3', 'description' => 'Gestionar dispensación Medcol3'],

            // Medcol5 (EMCALI)
            ['name' => 'Ver Medcol5 Pendientes', 'slug' => 'medcol5.pendientes.view', 'module' => 'Medcol5', 'description' => 'Ver listado de pendientes Medcol5/EMCALI'],
            ['name' => 'Crear Medcol5 Pendientes', 'slug' => 'medcol5.pendientes.create', 'module' => 'Medcol5', 'description' => 'Crear pendientes Medcol5/EMCALI'],
            ['name' => 'Editar Medcol5 Pendientes', 'slug' => 'medcol5.pendientes.edit', 'module' => 'Medcol5', 'description' => 'Editar pendientes Medcol5/EMCALI'],
            ['name' => 'Eliminar Medcol5 Pendientes', 'slug' => 'medcol5.pendientes.delete', 'module' => 'Medcol5', 'description' => 'Eliminar pendientes Medcol5/EMCALI'],
            ['name' => 'Ver Medcol5 Dispensado', 'slug' => 'medcol5.dispensado.view', 'module' => 'Medcol5', 'description' => 'Ver medicamentos dispensados Medcol5/EMCALI'],
            ['name' => 'Gestionar Medcol5 Dispensado', 'slug' => 'medcol5.dispensado.manage', 'module' => 'Medcol5', 'description' => 'Gestionar dispensación Medcol5/EMCALI'],

            // Medcol6 (SOS y JAMUNDI)
            ['name' => 'Ver Medcol6 Pendientes', 'slug' => 'medcol6.pendientes.view', 'module' => 'Medcol6', 'description' => 'Ver listado de pendientes Medcol6/SOS/JAMUNDI'],
            ['name' => 'Crear Medcol6 Pendientes', 'slug' => 'medcol6.pendientes.create', 'module' => 'Medcol6', 'description' => 'Crear pendientes Medcol6/SOS/JAMUNDI'],
            ['name' => 'Editar Medcol6 Pendientes', 'slug' => 'medcol6.pendientes.edit', 'module' => 'Medcol6', 'description' => 'Editar pendientes Medcol6/SOS/JAMUNDI'],
            ['name' => 'Eliminar Medcol6 Pendientes', 'slug' => 'medcol6.pendientes.delete', 'module' => 'Medcol6', 'description' => 'Eliminar pendientes Medcol6/SOS/JAMUNDI'],
            ['name' => 'Ver Medcol6 Dispensado', 'slug' => 'medcol6.dispensado.view', 'module' => 'Medcol6', 'description' => 'Ver medicamentos dispensados Medcol6/SOS/JAMUNDI'],
            ['name' => 'Gestionar Medcol6 Dispensado', 'slug' => 'medcol6.dispensado.manage', 'module' => 'Medcol6', 'description' => 'Gestionar dispensación Medcol6/SOS/JAMUNDI'],
            ['name' => 'Ver Medcol6 Analista', 'slug' => 'medcol6.analista.view', 'module' => 'Medcol6', 'description' => 'Ver vista de analista Medcol6'],
            ['name' => 'Ver Informes Medcol6', 'slug' => 'medcol6.informes.view', 'module' => 'Medcol6', 'description' => 'Ver sección de informes Medcol6'],
            ['name' => 'Generar Informe Dispensación', 'slug' => 'medcol6.informes.dispensacion', 'module' => 'Medcol6', 'description' => 'Generar informe de dispensación'],
            ['name' => 'Generar Informe ForGif', 'slug' => 'medcol6.informes.forgif', 'module' => 'Medcol6', 'description' => 'Generar informe FOR_GIF_003'],
            ['name' => 'Generar Informe Medicamentos', 'slug' => 'medcol6.informes.medicamentos', 'module' => 'Medcol6', 'description' => 'Generar informe de medicamentos'],
            ['name' => 'Generar Informe Insumos', 'slug' => 'medcol6.informes.insumos', 'module' => 'Medcol6', 'description' => 'Generar informe de insumos'],
            ['name' => 'Ver Dispensación Múltiple', 'slug' => 'medcol6.informes.multiple', 'module' => 'Medcol6', 'description' => 'Ver informe de dispensación múltiple'],
            ['name' => 'Ver Resumen por Sede', 'slug' => 'medcol6.informes.sede', 'module' => 'Medcol6', 'description' => 'Ver resumen de dispensación por sede'],
            ['name' => 'Exportar Informes', 'slug' => 'medcol6.informes.export', 'module' => 'Medcol6', 'description' => 'Exportar informes a Excel/PDF'],

            // Medcold (Dolor)
            ['name' => 'Ver Medcold Pendientes', 'slug' => 'medcold.pendientes.view', 'module' => 'Medcold', 'description' => 'Ver listado de pendientes Medcold/Dolor'],
            ['name' => 'Crear Medcold Pendientes', 'slug' => 'medcold.pendientes.create', 'module' => 'Medcold', 'description' => 'Crear pendientes Medcold/Dolor'],
            ['name' => 'Editar Medcold Pendientes', 'slug' => 'medcold.pendientes.edit', 'module' => 'Medcold', 'description' => 'Editar pendientes Medcold/Dolor'],
            ['name' => 'Eliminar Medcold Pendientes', 'slug' => 'medcold.pendientes.delete', 'module' => 'Medcold', 'description' => 'Eliminar pendientes Medcold/Dolor'],
            ['name' => 'Ver Medcold Dispensado', 'slug' => 'medcold.dispensado.view', 'module' => 'Medcold', 'description' => 'Ver medicamentos dispensados Medcold/Dolor'],
            ['name' => 'Gestionar Medcold Dispensado', 'slug' => 'medcold.dispensado.manage', 'module' => 'Medcold', 'description' => 'Gestionar dispensación Medcold/Dolor'],

            // Inventario
            ['name' => 'Ver Inventario', 'slug' => 'inventario.view', 'module' => 'Inventario', 'description' => 'Ver inventario de medicamentos'],
            ['name' => 'Gestionar Inventario', 'slug' => 'inventario.manage', 'module' => 'Inventario', 'description' => 'Gestionar inventario (crear, editar, eliminar)'],
            ['name' => 'Ver Compras', 'slug' => 'compras.view', 'module' => 'Inventario', 'description' => 'Ver órdenes de compra'],
            ['name' => 'Gestionar Compras', 'slug' => 'compras.manage', 'module' => 'Inventario', 'description' => 'Gestionar órdenes de compra'],
            ['name' => 'Ver Proveedores', 'slug' => 'proveedores.view', 'module' => 'Inventario', 'description' => 'Ver listado de proveedores'],
            ['name' => 'Gestionar Proveedores', 'slug' => 'proveedores.manage', 'module' => 'Inventario', 'description' => 'Gestionar proveedores'],

            // Reportes
            ['name' => 'Ver Reportes', 'slug' => 'reportes.view', 'module' => 'Reportes', 'description' => 'Ver reportes generales'],
            ['name' => 'Exportar Reportes', 'slug' => 'reportes.export', 'module' => 'Reportes', 'description' => 'Exportar reportes a Excel/PDF'],
            ['name' => 'Ver Reportes Avanzados', 'slug' => 'reportes.advanced', 'module' => 'Reportes', 'description' => 'Ver reportes avanzados y estadísticas'],

            // Administración
            ['name' => 'Ver Usuarios', 'slug' => 'usuarios.view', 'module' => 'Administración', 'description' => 'Ver listado de usuarios'],
            ['name' => 'Crear Usuarios', 'slug' => 'usuarios.create', 'module' => 'Administración', 'description' => 'Crear nuevos usuarios'],
            ['name' => 'Editar Usuarios', 'slug' => 'usuarios.edit', 'module' => 'Administración', 'description' => 'Editar usuarios existentes'],
            ['name' => 'Eliminar Usuarios', 'slug' => 'usuarios.delete', 'module' => 'Administración', 'description' => 'Eliminar usuarios'],
            ['name' => 'Ver Roles', 'slug' => 'roles.view', 'module' => 'Administración', 'description' => 'Ver listado de roles'],
            ['name' => 'Gestionar Roles', 'slug' => 'roles.manage', 'module' => 'Administración', 'description' => 'Crear, editar y eliminar roles'],
            ['name' => 'Ver Permisos', 'slug' => 'permisos.view', 'module' => 'Administración', 'description' => 'Ver listado de permisos'],
            ['name' => 'Asignar Permisos', 'slug' => 'permisos.assign', 'module' => 'Administración', 'description' => 'Asignar permisos a roles y usuarios'],
            ['name' => 'Configuración Sistema', 'slug' => 'configuracion.manage', 'module' => 'Administración', 'description' => 'Configurar parámetros del sistema'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Crear Roles
        $superAdmin = Role::create([
            'name' => 'Super Administrador',
            'slug' => 'super-admin',
            'description' => 'Acceso total al sistema',
        ]);

        $admin = Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Administrador con acceso a la mayoría de funciones',
        ]);

        $analista = Role::create([
            'name' => 'Analista',
            'slug' => 'analista',
            'description' => 'Analista con acceso a gestión de medicamentos',
        ]);

        $auxiliar = Role::create([
            'name' => 'Auxiliar',
            'slug' => 'auxiliar',
            'description' => 'Auxiliar con permisos limitados de visualización',
        ]);

        $drogueria = Role::create([
            'name' => 'Droguería',
            'slug' => 'drogueria',
            'description' => 'Personal de droguería',
        ]);

        $reportes = Role::create([
            'name' => 'Reportes',
            'slug' => 'reportes',
            'description' => 'Usuario con acceso solo a reportes',
        ]);

        // Asignar todos los permisos al Super Admin
        $superAdmin->givePermissionTo(...Permission::pluck('slug')->toArray());

        // Asignar permisos al Admin (todos excepto gestión de roles y permisos)
        $adminPermissions = Permission::whereNotIn('slug', [
            'roles.manage',
            'permisos.assign',
            'configuracion.manage'
        ])->pluck('slug')->toArray();
        $admin->givePermissionTo(...$adminPermissions);

        // Asignar permisos al Analista
        $analistaPermissions = Permission::where(function($query) {
            $query->where('slug', 'like', '%.view')
                  ->orWhere('slug', 'like', '%.create')
                  ->orWhere('slug', 'like', '%.edit')
                  ->orWhere('slug', 'like', '%.manage');
        })->whereNotIn('module', ['Administración'])->pluck('slug')->toArray();
        $analista->givePermissionTo(...$analistaPermissions);

        // Asignar permisos al Auxiliar (solo visualización)
        $auxiliarPermissions = Permission::where('slug', 'like', '%.view')
            ->whereNotIn('module', ['Administración'])
            ->pluck('slug')->toArray();
        $auxiliar->givePermissionTo(...$auxiliarPermissions);

        // Asignar permisos a Droguería
        $drogueriaPermissions = Permission::whereIn('module', [
            'Medcol2', 'Medcol3', 'Medcol5', 'Medcol6', 'Medcold', 'Inventario', 'Dashboard'
        ])->pluck('slug')->toArray();
        $drogueria->givePermissionTo(...$drogueriaPermissions);

        // Asignar permisos a Reportes
        $reportesPermissions = Permission::where(function($query) {
            $query->where('module', 'Reportes')
                  ->orWhere('module', 'Dashboard')
                  ->orWhere('slug', 'like', '%.view');
        })->pluck('slug')->toArray();
        $reportes->givePermissionTo(...$reportesPermissions);

        $this->command->info('Roles y permisos creados exitosamente!');
        $this->command->info('Roles creados: Super Administrador, Administrador, Analista, Auxiliar, Droguería, Reportes');
        $this->command->info('Total de permisos creados: ' . Permission::count());
    }
}
