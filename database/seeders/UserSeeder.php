<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@cea.com'],
            [
                'name_one'      => 'Administrador',
                'name_two'      => 'Maestro',
                'last_name_one' => 'Sistema',
                'last_name_two' => '',
                'password'      => bcrypt('123456789'),
                'status'        => 1, 
            ]
        );
        $admin->assignRole('Admin');

        $editor = User::updateOrCreate(
            ['email' => 'editor@cea.com'],
            [
                'name_one'      => 'Editor',
                'name_two'      => 'De',
                'last_name_one' => 'Prueba',
                'last_name_two' => '',
                'password'      => bcrypt('123456789'),
                'status'        => 1,
            ]
        );
        $editor->assignRole('editor');

        $lector = User::updateOrCreate(
            ['email' => 'lector@cea.com'],
            [
                'name_one'      => 'Lector',
                'name_two'      => 'De',
                'last_name_one' => 'Prueba', 
                'last_name_two' => '',
                'password'      => bcrypt('123456789'),
                'status'        => 1,
            ]
        );
        $lector->assignRole('reader');
    }
}