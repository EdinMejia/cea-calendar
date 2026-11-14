<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str; 

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Solo acepta "mes"; cualquier otro valor cae en "dias"
        $vista = $request->query('vista') === 'mes' ? 'mes' : 'dias';

        $consulta = Actividad::with('comision')->orderBy('hora_fecha');

        if ($vista === 'dias') {
            $consulta->proximas();                                   // tarjetas: solo las próximas
        } else {
            $consulta->where('hora_fecha', '>=', now()->subYear());  // calendario: último año en adelante
        }

        $actividades = $consulta->get();
        $eventos     = [];
        $leyenda     = collect();

        if ($vista === 'mes') {
            $eventos = $actividades->map(function (Actividad $a) {
                $inicio   = $a->hora_fecha;
                // Mismo día del inicio + la hora de finalización
                $fin      = $inicio->copy()->setTimeFromTimeString($a->hora_finalizacion);
                $comision = $a->comision;

                return [
                    'id'              => $a->getRouteKey(),
                    'title'           => $a->nombre,
                    'start'           => $inicio->format('Y-m-d\TH:i:s'),
                    'end'             => $fin->gt($inicio) ? $fin->format('Y-m-d\TH:i:s') : null,
                    'backgroundColor' => $comision?->color_css ?? '#3B82F6',
                    'borderColor'     => '#000000',
                    'textColor'       => $comision?->color_texto ?? '#ffffff',
                    'extendedProps'   => [
                        'fecha'       => $inicio->copy()->locale('es')->translatedFormat('l d \d\e F \d\e Y'),
                        'horario'     => $inicio->format('h:i A').' a '.$fin->format('h:i A'),
                        'lugar'       => $a->lugar,
                        'comision'    => $comision?->name_comision ?? 'Sin asignar',
                        'descripcion' => $a->descripcion,
                        'url'         => Route::has('actividades.show') ? route('actividades.show', $a) : null,
                        'busqueda'    => Str::ascii(mb_strtolower(
                            $a->nombre.' '.$a->lugar.' '.($comision?->name_comision ?? '')
                        )),
                    ],
                ];
            })->values()->all();

            $leyenda = $actividades->pluck('comision')->filter()->unique('id')->values();
        }

        return view('dashboard', compact('actividades', 'vista', 'eventos', 'leyenda'));
    }
}
