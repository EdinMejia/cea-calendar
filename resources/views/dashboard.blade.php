<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>
    {{-- para poner el las demas acciones  --}}

    {{-- esto ee para mover la distancia entre acciones  --}}
    <form method="GET" action="{{ route('dashboard') }}"
          class="flex flex-wrap items-center gap-4">

        {{-- Ver como --}}
            <select name="vista"
                    class="w-52 border border-black bg-white px-2 py-1.5 text-sm">
                <option value="">Ver como:</option>
                <option value="dias" @selected(request('vista') === 'dias')>Días con actividades</option>
                <option value="mes"  @selected(request('vista') === 'mes')>Mes con actividades</option>
            </select>
        
        {{-- Crear nueva actividad --}}
            <a href="{{ route('actividades.create') }}"
            class="border border-black bg-green-700 px-3 py-1.5 text-xs font-bold text-white shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                Crear nueva actividad
            </a>
    </form>


</x-app-layout>

