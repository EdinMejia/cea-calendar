<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; 

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin']); // este es el rol del administrador 
        Role::create(['name' => 'Editor']); // este es el rol del editor 
        Role::create(['name' => 'Reader']); // este es el rol del lector
    }
}
