<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gray-200 border-2 border-black p-8 shadow-[8px_8px_0px_rgba(0,0,0,1)]">
                
                <h1 class="text-3xl font-bold text-center mb-8 text-black">
                    Información de la actividad
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="block font-bold text-black mb-1">Nombre de la actividad</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ $actividad->nombre }}
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-black mb-1">Lugar</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ $actividad->lugar }}
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-black mb-1">Comisión</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ $actividad->comision->name_comision ?? 'Sin asignar' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="block font-bold text-black mb-1">Fecha y Hora de inicio</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ \Carbon\Carbon::parse($actividad->hora_fecha)->format('d-m-Y, h:i:s A') }}
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-black mb-1">Hora de Finalización</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ \Carbon\Carbon::parse($actividad->hora_finalizacion)->format('h:i:s A') }}
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-black mb-1">Descripción</label>
                            <div class="bg-white border-2 border-black p-2 text-sm min-h-[80px]">
                                {{ $actividad->descripcion ?? 'Sin descripción' }}
                            </div>
                        </div>

                        <!-- Indicador de Color -->
                        <div class="flex flex-col items-center mt-2">
                            <label class="block font-bold text-black mb-1 text-sm">Color</label>
                            <div class="w-8 h-8 rounded-full border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,1)]" 
                                {!! 'style="background-color: ' . ($actividad->comision->color_comision ?? '#ffffff') . ';"' !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Botones de Acción (Volver, Editar, Eliminar) -->
            <div class="mt-8 flex w-full flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <!-- Botón Volver (Verde) -->
                <a href="{{ route('dashboard') }}" class="bg-green-600 text-white font-bold border-2 border-black px-6 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                    Volver
                </a>

                <div class="grid grid-cols-2 gap-3 w-full sm:w-auto sm:flex sm:gap-4 sm:ml-auto">
                    <!-- Botón Editar (Azul) -->
                    <a href="{{ route('actividades.edit', $actividad->id) }}" class="w-full sm:w-auto text-center bg-blue-600 text-white font-bold border-2 border-black px-6 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                        Editar 
                    </a>

                    <!-- Botón Eliminar (Rojo) -->
                    <form action="{{ route('actividades.destroy', $actividad->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta actividad?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto bg-red-600 text-white font-bold border-2 border-black px-6 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                            Eliminar 
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>