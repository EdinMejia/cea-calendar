<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Inicio</h2>
    </x-slot>

    @php
        $boton = 'border-2 border-black px-3 py-2 text-center text-sm font-bold text-white shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] whitespace-nowrap';
        $vistaActual = $vista;
    @endphp

    <form method="GET" action="{{ route('dashboard') }}" class="mt-6 mb-10 flex flex-col gap-5 sm:flex-row sm:flex-wrap sm:items-center">

        <div class="relative order-first w-full sm:order-none sm:ml-auto sm:max-w-xs sm:flex-1">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-black" aria-hidden="true" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
            </div>
            <input type="search" id="buscador-actividades" name="buscar" value="{{ request('buscar') }}"
                    aria-label="Buscar actividades" autocomplete="off" placeholder="Buscador..."
                    class="w-full border-2 border-black bg-white py-2 pl-10 pr-3 text-base font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0 sm:text-sm">
        </div>

        <select name="vista" aria-label="Seleccionar vista" onchange="this.form.submit()"
                class="w-full border-2 border-black bg-white px-2 py-2 text-base font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none sm:w-52 sm:text-sm">
            <option value="dias" @selected($vistaActual === 'dias')>Días con actividades</option>
            <option value="mes"  @selected($vistaActual === 'mes')>Mes con actividades</option>
        </select>

        <a href="{{ route('actividades.create') }}" class="{{ $boton }} bg-green-700">
            Crear nueva actividad
        </a>

        @if (Route::has('actividades.historial'))
            <a href="{{ route('actividades.historial') }}" class="{{ $boton }} bg-orange-700">
                Historial de actividades
            </a>
        @endif
    </form>

    @if ($vistaActual === 'mes')
        {{-- ========== VISTA DE CALENDARIO ========== --}}

        @if ($leyenda->isNotEmpty())
            <ul class="mb-3 flex flex-wrap gap-2" aria-label="Leyenda de comisiones">
                @foreach ($leyenda as $c)
                    <li class="flex items-center gap-2 border-2 border-black bg-white px-2 py-1 text-xs font-bold">
                        <span class="h-4 w-4 border border-black" @style(['background-color: ' . $c->color_css])></span>
                        {{ $c->name_comision }}
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="border-2 border-black bg-white p-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] sm:p-4">
            <div id="calendario-cea" data-eventos="{{ json_encode($eventos, JSON_UNESCAPED_UNICODE) }}"></div>
        </div>

        @vite('resources/js/calendario.js')

    @else
        {{-- ========== VISTA DE TARJETAS ========== --}}

        {{-- Tarjetas en una fila con scroll horizontal --}}
        <div id="contenedor-tarjetas" class="flex snap-x snap-proximity gap-6 overflow-x-auto pb-4 pt-2">
            @forelse ($actividades as $actividad)

                @php
                    $color = $actividad->comision?->color_css ?? '#3B82F6';
                    $textoColor = $actividad->comision?->color_texto ?? '#000000';
                    $busqueda = \Illuminate\Support\Str::ascii(mb_strtolower(
                        $actividad->nombre.' '.$actividad->lugar.' '.($actividad->comision?->name_comision ?? '')
                    ));

                    $filas = [
                        ['Fecha y hora de inicio:', \Carbon\Carbon::parse($actividad->hora_fecha)->format('d/m/Y h:i A'), 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['Hora de finalización:', \Carbon\Carbon::parse($actividad->hora_finalizacion)->format('h:i A'), 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['Lugar de la actividad:', $actividad->lugar, 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['Encargado - comisión:', $actividad->comision?->name_comision ?? 'Sin asignar', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                @endphp

                {{-- Mismo ancho para todas y sin encogerse: se deslizan en vez de aplastarse --}}
                <div class="articulo-tarjeta flex w-64 shrink-0 snap-start flex-col gap-2 transition-transform hover:-translate-y-1" data-busqueda="{{ $busqueda }}">

                    <div class="break-words border-2 border-black p-3 text-center" @style(['background-color: ' . $color])>
                        <h3 class="text-base font-bold uppercase leading-tight" @style(['color: ' . $textoColor])>
                            Nombre:<br>{{ $actividad->nombre }}
                        </h3>
                    </div>

                    <div class="flex flex-col gap-4 border-2 border-black p-4" @style(['background-color: ' . $color])>
                        @foreach ($filas as [$etiqueta, $valor, $icono])
                            <div>
                                <div class="mb-1 flex items-center gap-2">
                                    <svg class="h-5 w-5 shrink-0" @style(['color: ' . $textoColor]) fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icono }}"/>
                                    </svg>
                                    <span class="text-sm font-bold" @style(['color: ' . $textoColor])>{{ $etiqueta }}</span>
                                </div>
                                <div class="break-words border-2 border-black bg-white p-1.5 text-center text-sm font-medium">
                                    {{ $valor ?: '—' }}
                                </div>
                            </div>
                        @endforeach

                        @if (Route::has('actividades.show'))
                            <div class="flex justify-end">
                                <a href="{{ route('actividades.show', $actividad) }}"
                                    class="border-2 border-black bg-white px-4 py-2 text-sm font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                                    Más...
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="w-full py-10 text-center">
                    <p class="text-xl font-bold text-gray-500">No hay actividades registradas aún.</p>
                </div>
            @endforelse

            <p id="sin-resultados" class="hidden w-full shrink-0 py-10 text-center text-xl font-bold text-gray-500">
                Ninguna actividad coincide con tu búsqueda.
            </p>
        </div>

        {{-- Buscador de la vista de tarjetas --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const buscador = document.getElementById('buscador-actividades');
                const tarjetas = document.querySelectorAll('.articulo-tarjeta');
                const vacio    = document.getElementById('sin-resultados');
                if (!buscador) return;

                const limpiar = (t) => t.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

                const filtrar = () => {
                    const texto = limpiar(buscador.value.trim());
                    let visibles = 0;

                    tarjetas.forEach((t) => {
                        const coincide = t.dataset.busqueda.includes(texto);
                        t.style.display = coincide ? '' : 'none';
                        if (coincide) visibles++;
                    });

                    if (vacio) vacio.classList.toggle('hidden', visibles > 0 || tarjetas.length === 0);
                };

                buscador.addEventListener('keydown', (e) => { if (e.key === 'Enter') e.preventDefault(); });
                buscador.addEventListener('input', filtrar);
                filtrar();
            });
        </script>
    @endif
</x-app-layout>