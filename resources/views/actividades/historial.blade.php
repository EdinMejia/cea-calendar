<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial de Actividades') }}
        </h2>
    </x-slot>

    <div class="py-6 w-full px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-wrap items-center gap-4 mb-8">
            <div class="flex-grow max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-black font-bold" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                    </div>
                    <input type="text" id="buscador-historial" autocomplete="off" placeholder="Buscar en el historial..." class="w-full border-2 border-black bg-white pl-10 pr-3 py-1.5 text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0">
                </div>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-center mb-10 text-black uppercase">
            HISTORIAL DE ACTIVIDADES PASADAS
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6" id="contenedor-tarjetas">
            
            @forelse($actividades as $actividad)
                @php
                    $colorFondo = $actividad->comision->color_comision ?? '#3B82F6';
                    $datosBusqueda = strtolower($actividad->nombre . ' ' . $actividad->lugar . ' ' . ($actividad->comision->name_comision ?? ''));
                @endphp
                
                <div class="articulo-tarjeta flex flex-col gap-2 w-full max-w-sm mx-auto transition-transform hover:-translate-y-1" data-busqueda="{{ $datosBusqueda }}">
                    
                    <div class="border-2 border-black p-3 text-center" {!! 'style="background-color: ' . $colorFondo . ';"' !!}>
                        <h3 class="text-black font-bold text-base leading-tight uppercase">
                            Nombre:<br>{{ $actividad->nombre }}
                        </h3>
                    </div>

                    <div class="border-2 border-black p-4 flex flex-col gap-4" {!! 'style="background-color: ' . $colorFondo . ';"' !!}>
                        
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-bold text-black text-xs">Fecha y Hora de Inicio:</span>
                            </div>
                            <div class="bg-white border-2 border-black p-1 text-xs text-center font-medium text-red-600">
                                {{ $actividad->hora_fecha }} (Finalizada)
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span class="font-bold text-black text-xs">Lugar de la Actividad:</span>
                            </div>
                            <div class="bg-white border-2 border-black p-1.5 text-xs font-medium">
                                {{ $actividad->lugar }}
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="font-bold text-black text-xs">Encargado - comisión:</span>
                            </div>
                            <div class="bg-white border-2 border-black p-1.5 text-xs font-medium uppercase">
                                {{ $actividad->comision->name_comision ?? 'Sin asignar' }}
                            </div>
                        </div>

                        <div class="mt-2">
                            <a href="{{ route('actividades.edit', $actividad->id) }}" class="block w-full text-center bg-yellow-400 border-2 border-black px-4 py-2 text-sm font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                                EDITAR ACTIVIDAD
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-xl font-bold text-gray-500">No hay actividades en el historial aún.</p>
                </div>
            @endforelse
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="bg-green-600 text-white font-bold border-2 border-black px-6 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                    Volver
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscador-historial');
            const tarjetas = document.querySelectorAll('.articulo-tarjeta');

            buscador.addEventListener('input', function(e) {
                const textoBusqueda = e.target.value.toLowerCase();
                tarjetas.forEach(tarjeta => {
                    const datosTarjeta = tarjeta.getAttribute('data-busqueda');
                    if (datosTarjeta.includes(textoBusqueda)) {
                        tarjeta.style.display = 'flex';
                    } else {
                        tarjeta.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>