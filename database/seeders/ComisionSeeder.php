<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comision;

class ComisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Comisión 1
        Comision::firstOrCreate(
            ['name_comision' => 'Deportes'],
            ['color_comision' => 'Amarillo', 'ciclo_comision' => '2026-01-01'] 
        );

        // Comisión 2
        Comision::firstOrCreate(
            ['name_comision' => 'Cultura y Civismo'],
            ['color_comision' => 'Verde', 'ciclo_comision' => '2026-01-01']
        );

        // Comisión 3
        Comision::firstOrCreate(
            ['name_comision' => 'Tecnología'],
            ['color_comision' => 'Azul', 'ciclo_comision' => '2026-01-01']
        );

        // Comisión 4
        Comision::firstOrCreate(
            ['name_comision' => 'Evaluacion'],
            ['color_comision' => 'Naranja', 'ciclo_comision' => '2026-01-01']
        );
    }
}