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

    private const PERMISOS = [
        'actividades.ver',
        'actividades.gestionar',
        'actividades.historial',
        'comisiones.gestionar',
        'usuarios.ver',
        'usuarios.gestionar',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISOS as $permiso) {
            Permission::findOrCreate($permiso, 'web');
        }

        Permission::where('guard_name', 'web')
            ->whereNotIn('name', self::PERMISOS)
            ->get()
            ->each(function ($permiso) {
                $permiso->roles()->detach();
                $permiso->users()->detach();
                $permiso->delete();
            });

        Role::findOrCreate(self::LECTOR, 'web')->syncPermissions(['actividades.ver']);
        Role::findOrCreate(self::EDITOR, 'web')->syncPermissions(['actividades.ver', 'actividades.gestionar']);
        Role::findOrCreate(self::ADMIN, 'web')->syncPermissions(Permission::all());

        app(PermissionRegistrar::class)->forgetCachedPermissions();

    }
}
