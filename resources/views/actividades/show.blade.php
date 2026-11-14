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
                            <label for="nombre" class="block font-bold text-black mb-1">Nombre de la actividad</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ $actividad->nombre }}
                            </div>
                        </div>

                        <div>
                            <label for="lugar" class="block font-bold text-black mb-1">Lugar</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ $actividad->lugar }}
                            </div>
                        </div>

                        <div>
                            <label for="comision" class="block font-bold text-black mb-1">Comisión</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ $actividad->comision->name_comision ?? 'Sin asignar' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div>
                            <label for="fecha_inicio" class="block font-bold text-black mb-1">Fecha y Hora de inicio</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ \Carbon\Carbon::parse($actividad->hora_fecha)->format('d-m-Y, h:i:s A') }}
                            </div>
                        </div>

                        <div>
                            <label for="fecha_finalizacion" class="block font-bold text-black mb-1">Hora de Finalización</label>
                            <div class="bg-white border-2 border-black p-2 text-center text-sm">
                                {{ \Carbon\Carbon::parse($actividad->hora_finalizacion)->format('h:i:s A') }}
                            </div>
                        </div>

                        <div>
                            <label for="descripcion" class="block font-bold text-black mb-1">Descripción</label>
                            <div class="border-2 border-black bg-white px-3 py-2 text-sm font-medium text-black break-words break-all h-auto min-h-[80px]">
                                {{ $actividad->descripcion ?? 'Sin descripción' }}
                            </div>
                        </div>

                        <!-- Indicador de Color -->
                        <div class="flex flex-col items-center mt-2">
                            <label for="color" class="block font-bold text-black mb-1 text-sm">Color</label>
                            <div class="h-8 w-8 rounded-full border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                                @style(['background-color: ' . ($actividad->comision?->color_css ?? '#ffffff')])>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:flex sm:flex-wrap sm:items-center sm:justify-between">

                <a href="{{ route('dashboard') }}"
                    class="block text-center bg-green-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                    Volver
                </a>

                <a href="{{ route('reportes.actividad', $actividad) }}"
                    class="block text-center bg-blue-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                    Exportar PDF
                </a>

                @can('actividades.gestionar')
                    <div class="contents sm:flex sm:gap-4">
                        <a href="{{ route('actividades.edit', $actividad) }}"
                            class="block text-center bg-blue-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                            Editar
                        </a>

                        <form action="{{ route('actividades.destroy', $actividad) }}" method="POST"
                            class="form-eliminar" data-mensaje="¿Desea eliminar esta actividad?">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full sm:w-auto bg-red-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                                Eliminar
                            </button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>