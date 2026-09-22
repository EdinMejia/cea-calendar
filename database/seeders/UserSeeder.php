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
        $admin = User::create([
            'name_one' => 'Edin',
            'last_name_one' => 'Admin',
            'email' => 'admin@cea.com',
            'password' => bcrypt('123456789'),
            'status' => 1,
        ]);
        $admin->assignRole('Admin');
    }
}
