<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@cea.com'],
            [
                'name_one' => 'Edin',
                'name_two' => 'Moises',
                'last_name_one' => 'Mejia',
                'last_name_two' => 'tahay',
                'password' => bcrypt('123456789'),
                'status' => 1,
            ]
        );
        $admin->assignRole('Admin');

        $nuevoUsuario = User::updateOrCreate(
            ['email' => 'docente@cea.com'],
            [
                'name_one' => 'Carlos',
                'name_two' => 'Alberto',
                'last_name_one' => 'perez', 
                'last_name_two' => 'tahay',
                'password' => bcrypt('123456789'),
                'status' => 1,
            ]
        );
        $nuevoUsuario->assignRole('reader');
    }
}
