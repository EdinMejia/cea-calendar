<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Comision;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReporteController extends Controller
{
    public function semana(Request $request)
    {
        $fecha = $this->fecha($request);
        $desde = $fecha->startOfWeek();   // semana de lunes a domingo
        $hasta = $fecha->endOfWeek();

        $d = $desde->locale('es');
        $h = $hasta->locale('es');

        return $this->agenda(
            'Reporte semanal de actividades',
            'Del '.$d->translatedFormat('j \d\e F').' al '.$h->translatedFormat('j \d\e F \d\e Y'),
            $this->entre($desde, $hasta),
            'actividades-semana-'.$desde->toDateString()
        );
    }

    public function mes(Request $request)
    {
        $fecha = $this->fecha($request);

        return $this->agenda(
            'Reporte mensual de actividades',
            ucfirst($fecha->locale('es')->translatedFormat('F \d\e Y')),
            $this->entre($fecha->startOfMonth(), $fecha->endOfMonth()),
            'actividades-mes-'.$fecha->format('Y-m')
        );
    }

    public function anio(Request $request)
    {
        $fecha = $this->fecha($request);
        $anio  = $fecha->year;
        $ahora = now();

        $todas = $this->entre($fecha->startOfYear(), $fecha->endOfYear());

        // El análisis cuenta solo lo que ya ocurrió; lo demás aparece como "por realizar"
        $realizadas = $todas->filter(fn ($a) => $a->hora_fecha->lte($ahora))->values();

        $ultimoMes = $anio < $ahora->year ? 12 : ($anio === $ahora->year ? $ahora->month : 0);

        $porMes = collect(range(1, 12))->mapWithKeys(fn ($m) => [
            $this->nombreMes($m) => $realizadas->filter(fn ($a) => $a->hora_fecha->month === $m)->count(),
        ]);

        $total = max(1, $realizadas->count());

        $porComision = $realizadas
            ->groupBy(fn ($a) => $a->comision?->id ?? 0)
            ->map(fn ($g) => [
                'nombre' => trim($g->first()->comision?->name_comision ?? 'Sin comisión'),
                'color'  => $g->first()->comision?->color_css ?? '#9ca3af',
                'total'  => $g->count(),
            ])
            ->sortByDesc('total')
            ->values();

        $repetidas = $realizadas
            ->groupBy(fn ($a) => Str::ascii(mb_strtolower(trim($a->nombre))))
            ->map(fn ($g) => ['nombre' => trim($g->first()->nombre), 'total' => $g->count()])
            ->filter(fn ($x) => $x['total'] > 1)
            ->sortByDesc('total')
            ->take(5)
            ->values();

        return $this->pdf('reportes.anual', [
            'titulo'        => "Resumen anual {$anio}",
            'periodo'       => "Del 1 de enero al 31 de diciembre de {$anio}",
            'programadas'   => $todas->count(),
            'realizadas'    => $realizadas->count(),
            'pendientes'    => $todas->count() - $realizadas->count(),
            'comisiones'    => $porComision->count(),
            'filasMeses'    => $porMes->map(fn ($n, $mes) => ['etiqueta' => $mes, 'valor' => $n])->values(),
            'filasComision' => $porComision->map(fn ($c) => [
                'etiqueta' => $c['nombre'],
                'valor'    => $c['total'],
                'color'    => $c['color'],
                'texto'    => $c['total'].' ('.round($c['total'] / $total * 100).'%)',
            ]),
            'filasRepetidas' => $repetidas->map(fn ($r) => [
                'etiqueta' => $r['nombre'],
                'valor'    => $r['total'],
                'texto'    => $r['total'].' veces',
            ]),
            'observaciones' => $this->observaciones($realizadas, $porMes, $porComision, $repetidas, $ultimoMes),
        ], "resumen-anual-{$anio}");
    }

    public function actividad(Actividad $actividad)
    {
        $actividad->load(['comision.docentes', 'user']);

        return $this->pdf('reportes.actividad', [
            'titulo'  => 'Ficha de actividad',
            'periodo' => ucfirst($actividad->hora_fecha->copy()->locale('es')->translatedFormat('l j \d\e F \d\e Y')),
            'a'       => $actividad,
        ], 'actividad-'.Str::slug($actividad->nombre));
    }

    private function agenda(string $titulo, string $periodo, Collection $actividades, string $archivo)
    {
        return $this->pdf('reportes.agenda', [
            'titulo'  => $titulo,
            'periodo' => $periodo,
            'total'   => $actividades->count(),
            'porDia'  => $actividades->groupBy(fn ($a) => $a->hora_fecha->toDateString()),
            'comisiones'  => $actividades->pluck('comisiones_id')->filter()->unique()->count(),
            'porComision' => $actividades
            ->groupBy(fn ($a) => $a->comision?->id ?? 0)
            ->map(fn ($g) => [
                'etiqueta' => trim($g->first()->comision?->name_comision ?? 'Sin comisión'),
                'valor'    => $g->count(),
                'color'    => $g->first()->comision?->color_css ?? '#9ca3af',
            ])
            ->sortByDesc('valor')
            ->values(),
        ], $archivo);
    }

    private function entre(CarbonImmutable $desde, CarbonImmutable $hasta): Collection
    {
        return Actividad::with('comision')
            ->whereBetween('hora_fecha', [$desde, $hasta])
            ->orderBy('hora_fecha')
            ->get();
    }

    // Fecha de referencia (?fecha=YYYY-MM-DD). Si viene vacía, se usa hoy
    private function fecha(Request $request): CarbonImmutable
    {
        $data = $request->validate(['fecha' => ['nullable', 'date_format:Y-m-d']]);

        return CarbonImmutable::createFromFormat('Y-m-d', $data['fecha'] ?? now()->toDateString())->startOfDay();
    }

    private function nombreMes(int $numero): string
    {
        return ucfirst(CarbonImmutable::create(2000, $numero, 1)->locale('es')->monthName);
    }

    private function pdf(string $vista, array $datos, string $archivo)
    {
        $datos += [
            'generado' => now()->format('d/m/Y h:i A'),
            'usuario'  => Auth::user()->name,
        ];

        return Pdf::loadView($vista, $datos)->setPaper('letter')->stream($archivo.'.pdf');
    }

    // Observaciones por reglas simples sobre los datos (no es inteligencia artificial)
    private function observaciones(Collection $realizadas, Collection $porMes, Collection $porComision, Collection $repetidas, int $ultimoMes): array
    {
        $total = $realizadas->count();
        if ($total === 0) {
            return [];
        }

        $obs = [];

        $max    = $porMes->max();
        $mesTop = $porMes->filter(fn ($n) => $n === $max)->keys()->first();
        $obs[]  = "El mes con más actividades fue {$mesTop} ({$max}).";

        $vacios = $porMes->take($ultimoMes)->filter(fn ($n) => $n === 0)->keys()->map(fn ($m) => mb_strtolower($m));
        if ($vacios->isNotEmpty()) {
            $obs[] = 'No hubo actividades en '.$vacios->join(', ', ' y ').'. Conviene revisar si faltó registrarlas o si se pueden programar.';
        }

        $lider = $porComision->first();
        $pct   = round($lider['total'] / $total * 100);
        if ($porComision->count() > 1 && $pct >= 50) {
            $obs[] = "La comisión {$lider['nombre']} concentra el {$pct}% de las actividades. Se podría repartir la carga entre otras comisiones.";
        }

        $sin = Comision::whereNotIn('id', $realizadas->pluck('comisiones_id')->filter()->unique())
            ->pluck('name_comision')->map(fn ($n) => trim($n));
        if ($sin->isNotEmpty()) {
            $obs[] = 'Sin actividades en el período: '.$sin->join(', ', ' y ').'. Se sugiere invitarlas a participar.';
        }

        if ($repetidas->isNotEmpty()) {
            $r     = $repetidas->first();
            $obs[] = "«{$r['nombre']}» se realizó {$r['total']} veces; podría programarse como actividad recurrente.";
        }

        return $obs;
    }
}