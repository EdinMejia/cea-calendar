<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    private const ADMIN = 'Admin';
    private const EDITOR = 'Editor';
    private const LECTOR = 'Reader';
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permisos = [
            'actividades.ver', 'actividades.crear', 'actividades.editar', 'actividades.borrar',
            'comisiones.ver',  'comisiones.crear',  'comisiones.editar',  'comisiones.borrar',
            'usuarios.ver', 'usuarios.gestionar',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        $lectorPermisos = ['actividades.ver', 'comisiones.ver'];
        $editorPermisos = array_merge($lectorPermisos, ['actividades.crear', 'actividades.editar']);

        Role::firstOrCreate(['name' => self::LECTOR, 'guard_name' => 'web'])
            ->syncPermissions($lectorPermisos);
            
        Role::firstOrCreate(['name' => self::EDITOR, 'guard_name' => 'web'])
            ->syncPermissions($editorPermisos);
            
        Role::firstOrCreate(['name' => self::ADMIN, 'guard_name' => 'web'])
            ->syncPermissions(Permission::all());

    }
}
