<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comision; 

class ComisionSeeder extends Seeder
{
    public function run()
    {
        $comisiones = [
            ['name_comision' => 'Comisión de Civismo y Valores', 'color_comision' => '#7DD3FC', 'ciclo_comision' => '2027-01-01'],
            ['name_comision' => 'Comisión de Primero Auxilios', 'color_comision' => '#FDBA74', 'ciclo_comision' => '2027-01-01'],
            ['name_comision' => 'Comisión de Ciencia y Tecnología', 'color_comision' => '#A5B4FC', 'ciclo_comision' => '2027-01-01'],
            ['name_comision' => 'Comisión de Medio Ambiente', 'color_comision' => '#FCA5A5', 'ciclo_comision' => '2027-01-01'],
            ['name_comision' => 'Comisión de Convivencia', 'color_comision' => '#FDE047', 'ciclo_comision' => '2027-01-01'],
            ['name_comision' => 'Comisión de Cultura y Asuntos Sociales', 'color_comision' => '#D8B4FE', 'ciclo_comision' => '2027-01-01'],
            ['name_comision' => 'Comisión de Deportes', 'color_comision' => '#93C5FD', 'ciclo_comision' => '2027-01-01'],
            ['name_comision' => 'Comisión de Evaluación y Rendimiento Académico', 'color_comision' => '#EF4444', 'ciclo_comision' => '2027-01-01'],
        ];

        foreach ($comisiones as $comision) {
            Comision::updateOrCreate(
                ['name_comision' => $comision['name_comision']], 
                [
                    'color_comision' => $comision['color_comision'],
                    'ciclo_comision' => $comision['ciclo_comision']
                ] 
            );
        }
    }
}