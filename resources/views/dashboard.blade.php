<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>

    <div class="py-6 w-full px-4 sm:px-6 lg:px-8">
        
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-4 mb-8">

            {{-- Ver como --}}
            <select name="vista" onchange="this.form.submit()" class="w-52 border-2 border-black bg-white px-2 py-1.5 text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none">
                <option value="">Ver como:</option>
                <option value="dias" @selected(request('vista') === 'dias')>Días con actividades</option>
                <option value="mes"  @selected(request('vista') === 'mes')>Mes con actividades</option>
            </select>
            
            {{-- Crear nueva actividad --}}
            <a href="{{ route('actividades.create') }}" class="border-2 border-black bg-green-700 px-3 py-1.5 text-sm font-bold text-white shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                Crear nueva actividad
            </a>

            {{-- Buscador Reactivo --}}
            <div class="flex-grow max-w-md ml-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-black font-bold" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                    </div>
                    <!-- El ID 'buscador-actividades' es el que conecta con el script de abajo -->
                    <input type="text" id="buscador-actividades" autocomplete="off" placeholder="Buscador..." class="w-full border-2 border-black bg-white pl-10 pr-3 py-1.5 text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0">
                </div>
            </div>
            
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6" id="contenedor-tarjetas">
            
            @forelse($actividades as $actividad)
                @php
                    $colorFondo = $actividad->comision->color_comision ?? '#3B82F6';
                    // Creamos un string con el nombre, lugar y comisión para que el buscador encuentre por cualquiera de esos datos
                    $datosBusqueda = strtolower($actividad->nombre . ' ' . $actividad->lugar . ' ' . ($actividad->comision->name_comision ?? ''));
                @endphp
                
                <!-- Tarjeta con la clase 'articulo-tarjeta' y el atributo 'data-busqueda' -->
                <div class="articulo-tarjeta flex flex-col gap-2 w-full max-w-sm mx-auto transition-transform hover:-translate-y-1" data-busqueda="{{ $datosBusqueda }}">
                    
                    <!-- Título -->
                    <div class="border-2 border-black p-3 text-center" style="background-color: {{ $colorFondo }};">
                        <h3 class="text-black font-bold text-base leading-tight uppercase">
                            Nombre:<br>{{ $actividad->nombre }}
                        </h3>
                    </div>

                    <!-- Contenido -->
                    <div class="border-2 border-black p-4 flex flex-col gap-4" style="background-color: {{ $colorFondo }};">
                        
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-bold text-black text-xs">Fecha y Hora de Inicio:</span>
                            </div>
                            <div class="bg-white border-2 border-black p-1 text-xs text-center font-medium">
                                {{ $actividad->hora_fecha }}
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-bold text-black text-xs">Hora Finalización:</span>
                            </div>
                            <div class="bg-white border-2 border-black p-1 text-xs text-center font-medium">
                                {{ $actividad->hora_finalizacion }}
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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

                        <div class="flex justify-end mt-1">
                            <a href="#" class="bg-white border-2 border-black px-3 py-1 text-xs font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscador-actividades');
            const tarjetas = document.querySelectorAll('.articulo-tarjeta');

            buscador.addEventListener('input', function(e) {
                // Obtenemos el texto que el usuario está escribiendo y lo pasamos a minúsculas
                const textoBusqueda = e.target.value.toLowerCase();

                // Recorremos todas las tarjetas
                tarjetas.forEach(tarjeta => {
                    // Obtenemos la información de la tarjeta (nombre, lugar, comisión)
                    const datosTarjeta = tarjeta.getAttribute('data-busqueda');

                    // Si la información de la tarjeta incluye el texto que buscamos, la mostramos, sino la ocultamos
                    if (datosTarjeta.includes(textoBusqueda)) {
                        tarjeta.style.display = 'flex'; // Usamos flex porque la tarjeta tiene flex-col
                    } else {
                        tarjeta.style.display = 'none'; // Oculta la tarjeta
                    }
                });
            });
        });
    </script>
</x-app-layout>