<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>
    {{-- para poner el las demas acciones  --}}

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-8 border-2 border-black shadow-[4px_4px_0px_rgba(0,0,0,1)]">
            
            {{-- esto ee para mover la distancia entre acciones  --}}
            <!-- Se agregó border-b-2 y pb-6 para separar el menú de las tarjetas visualmente -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-4 mb-8 pb-6 border-b-2 border-black">

                {{-- Ver como --}}
                    <!-- Se agregó onchange para que el formulario se envíe solo al elegir una opción -->
                    <select name="vista" onchange="this.form.submit()" class="w-52 border border-black bg-white px-2 py-1.5 text-sm">
                        <option value="">Ver como:</option>
                        <option value="dias" @selected(request('vista') === 'dias')>Días con actividades</option>
                        <option value="mes"  @selected(request('vista') === 'mes')>Mes con actividades</option>
                    </select>
                
                {{-- Crear nueva actividad --}}
                    <a href="{{ route('actividades.create') }}" class="border border-black bg-green-700 px-3 py-1.5 text-xs font-bold text-white shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                        Crear nueva actividad
                    </a>
            </form>

            <h2 class="text-3xl font-bold text-center mb-10 text-black uppercase">
                ACTIVIDADES PROGRAMADAS
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @forelse($actividades as $actividad)
                    @php
                        $colorFondo = $actividad->comision->color_comision ?? '#3B82F6';
                    @endphp
                    
                    <!-- Molde de la Tarjeta -->
                    <div class="flex flex-col gap-2 w-full max-w-sm mx-auto transition-transform hover:-translate-y-1">
                        
                        <!-- Título -->
                        <div class="border-2 border-black p-3 text-center" style="background-color: {{ $colorFondo }};">
                            <h3 class="text-black font-bold text-lg leading-tight uppercase">
                                {{ $actividad->nombre }}
                            </h3>
                        </div>

                        <!-- Contenido -->
                        <div class="border-2 border-black p-5 flex flex-col gap-5" style="background-color: {{ $colorFondo }};">
                            
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="font-bold text-black text-sm">Fecha y Hora de Inicio:</span>
                                </div>
                                <div class="bg-white border-2 border-black p-1 text-sm text-center font-medium">
                                    {{ $actividad->hora_fecha }}
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-bold text-black text-sm">Hora Finalización:</span>
                                </div>
                                <div class="bg-white border-2 border-black p-1 text-sm text-center font-medium">
                                    {{ $actividad->hora_finalizacion }}
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="font-bold text-black text-sm">Lugar de la Actividad:</span>
                                </div>
                                <div class="bg-white border-2 border-black p-2 text-sm font-medium">
                                    {{ $actividad->lugar }}
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="font-bold text-black text-sm">Encargado - comisión:</span>
                                </div>
                                <div class="bg-white border-2 border-black p-2 text-sm font-medium uppercase">
                                    {{ $actividad->comision->name_comision ?? 'Sin asignar' }}
                                </div>
                            </div>

                            <div class="flex justify-end mt-2">
                                <a href="#" class="bg-white border-2 border-black px-4 py-1 text-sm font-bold text-black hover:bg-gray-200 transition-colors shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                                    Más....
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-xl font-bold text-gray-500">No hay actividades registradas aún.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>