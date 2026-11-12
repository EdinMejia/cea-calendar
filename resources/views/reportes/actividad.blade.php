@extends('reportes.layout')

@section('contenido')
    @php
        $docentes = $a->comision?->docentes->sortBy('name_one') ?? collect();
        $fin      = \Illuminate\Support\Carbon::parse($a->hora_finalizacion)->format('h:i A');
    @endphp

    <table class="nombre"><tr>
        <td @style([
            'background-color: '.($a->comision?->color_css ?? '#9ca3af'),
            'color: '.($a->comision?->color_texto ?? '#000000'),
        ])>
            <div class="kick">Actividad</div>
            <div class="tit">{{ $a->nombre }}</div>
        </td>
    </tr></table>

    <table class="rejilla">
        <tr>
            <td class="izq">
                <div class="etq">Horario</div>
                <div class="val">{{ $a->hora_fecha->format('h:i A') }} a {{ $fin }}</div>
            </td>
            <td class="der">
                <div class="etq">Lugar</div>
                <div class="val">{{ $a->lugar }}</div>
            </td>
        </tr>
        <tr>
            <td class="izq">
                <div class="etq">Comisión</div>
                <div class="val">{{ trim($a->comision?->name_comision ?? 'Sin asignar') }}</div>
            </td>
            <td class="der">
                <div class="etq">Registrada por</div>
                <div class="val">{{ $a->user?->name ?? '—' }}</div>
            </td>
        </tr>
    </table>

    <div class="etq">Descripción</div>
    <div class="val desc">{!! $a->descripcion ? nl2br(e($a->descripcion)) : 'Sin descripción' !!}</div>

    <h2>Docentes de la comisión ({{ $docentes->count() }})</h2>
    <table class="lista">
        <thead>
            <tr><th class="num">N.º</th><th>Docente</th><th class="firma">Firma de asistencia</th></tr>
        </thead>
        <tbody>
            @forelse ($docentes as $d)
                <tr class="{{ $loop->even ? 'alt' : '' }}">
                    <td class="num">{{ $loop->iteration }}</td>
                    <td>{{ $d->name }}</td>
                    <td></td>
                </tr>
            @empty
                <tr><td colspan="3" class="vacio">La comisión no tiene docentes asignados.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection