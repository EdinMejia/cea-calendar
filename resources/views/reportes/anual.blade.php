@extends('reportes.layout')

@section('contenido')
    @include('reportes._stats', ['cifras' => [
    [$programadas, 'Programadas'],
    [$realizadas, 'Realizadas'],
    [$pendientes, 'Por realizar'],
    [$comisiones, 'Comisiones participantes'],
]])

    @if ($realizadas === 0)
        <p class="vacio">Aún no hay actividades realizadas en este período.</p>
    @else
        <div class="bloque">
            <h2>Actividades realizadas por mes</h2>
            @include('reportes._barras', ['filas' => $filasMeses])
        </div>

        <div class="bloque">
            <h2>Participación por comisión</h2>
            @include('reportes._barras', ['filas' => $filasComision])
        </div>

        <div class="bloque">
            <h2>Actividades que más se repitieron</h2>
            @if ($filasRepetidas->isEmpty())
                <p>Ninguna actividad se repitió con el mismo nombre en este período.</p>
            @else
                @include('reportes._barras', ['filas' => $filasRepetidas])
            @endif
        </div>

        <div class="bloque">
            <h2>Observaciones y sugerencias</h2>
            <ul>
                @foreach ($observaciones as $o)
                    <li style="margin-bottom:4px">{{ $o }}</li>
                @endforeach
            </ul>
            <p style="font-size:8px;color:#555">Generadas automáticamente a partir de los datos del período. Son una guía, no una evaluación.</p>
        </div>
    @endif
@endsection