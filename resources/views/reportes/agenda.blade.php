@extends('reportes.layout')

@section('contenido')
    @include('reportes._stats', ['cifras' => [
        [$total, 'Actividades'],
        [$porDia->count(), 'Días con actividades'],
        [$comisiones, 'Comisiones'],
    ]])

    @forelse ($porDia as $dia => $lista)
        @php $f = \Illuminate\Support\Carbon::parse($dia)->locale('es'); @endphp

        <table class="dia">
            @foreach ($lista as $a)
                <tr class="{{ $loop->even ? 'alt' : '' }} {{ $loop->last ? 'ult' : '' }}">
                    @if ($loop->first)
                        <td class="fecha" rowspan="{{ $lista->count() }}">
                            <div class="sem">{{ $f->translatedFormat('D') }}</div>
                            <div class="num">{{ $f->format('d') }}</div>
                            <div class="mes">{{ $f->translatedFormat('M') }}</div>
                        </td>
                    @endif

                    <td class="hora">
                        <div class="ini">{{ $a->hora_fecha->format('h:i A') }}</div>
                        <div class="fin">a {{ \Illuminate\Support\Carbon::parse($a->hora_finalizacion)->format('h:i A') }}</div>
                    </td>

                    <td class="act">
                        <div class="nom">{{ $a->nombre }}</div>
                        <div class="lug">Lugar: {{ $a->lugar }}</div>
                    </td>

                    <td class="com">
                        <span class="chip" @style([
                            'background-color: '.($a->comision?->color_css ?? '#9ca3af'),
                            'color: '.($a->comision?->color_texto ?? '#000000'),
                        ])>{{ trim($a->comision?->name_comision ?? 'Sin comisión') }}</span>
                    </td>
                </tr>
            @endforeach
        </table>
    @empty
        <p class="vacio">No hay actividades en este período.</p>
    @endforelse

    @if ($total > 0)
        <div class="bloque">
            <h2>Actividades por comisión</h2>
            @include('reportes._barras', ['filas' => $porComision])
        </div>
    @endif
@endsection