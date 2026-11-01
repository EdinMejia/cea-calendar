<x-app-layout>
<x-alerta-cea />

    <h2 class="mb-8 text-2xl font-semibold text-slate-800">
        Crear Actividad: Complete la información.
    </h2>

    <form action="{{ route('actividades.store') }}" method="POST"
        onsubmit="this.querySelector('[type=submit]').disabled = true;">
        
        @csrf

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

            {{-- Columna izquierda --}}
            <div class="space-y-5">
                <div>
                    <label for="nombre" class="mb-1 block text-sm font-medium text-slate-700">Nombre de la actividad</label>
                    <input type="text" id="nombre" name="nombre" maxlength="100"
                        placeholder="Ej: Reunión con coordinadores de carrera"
                        value="{{ old('nombre') }}"
                        class="w-full rounded border px-3 py-2 @error('nombre') border-red-500 @else border-gray-400 @enderror">
                    @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="lugar" class="mb-1 block text-sm font-medium text-slate-700">Lugar</label>
                    <input type="text" id="lugar" name="lugar" maxlength="100"
                        placeholder="Ej: Salon de maestros"
                        value="{{ old('lugar') }}"
                        class="w-full rounded border px-3 py-2 @error('lugar') border-red-500 @else border-gray-400 @enderror">
                    @error('lugar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="comision" class="mb-1 block text-sm font-medium text-slate-700">Comisión</label>
                    <select name="comisiones_id" id="comision"
                            class="w-full rounded border bg-white px-3 py-2 @error('comisiones_id') border-red-500 @else border-gray-400 @enderror">
                        <option value="" data-color="#cccccc">Seleccione la comisión encargada</option>
                        @foreach ($comisiones as $comision)
                            <option value="{{ $comision->id }}"
                                    data-color="{{ $comision->color_comision }}"
                                    @selected(old('comisiones_id') == $comision->id)>
                                {{ $comision->name_comision }}
                            </option>
                        @endforeach
                    </select>
                    @error('comisiones_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Columna derecha --}}
            <div class="space-y-5">
                <div>
                    <label for="hora_fecha" class="mb-1 block text-sm font-medium text-slate-700">Fecha y Hora Inicio</label>
                    <input type="datetime-local" id="hora_fecha" name="hora_fecha"
                        value="{{ old('hora_fecha') }}"
                        class="w-full rounded border px-3 py-2 @error('hora_fecha') border-red-500 @else border-gray-400 @enderror">
                    @error('hora_fecha') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="hora_finalizacion" class="mb-1 block text-sm font-medium text-slate-700">Hora de Finalización</label>
                    <input type="time" id="hora_finalizacion" name="hora_finalizacion"
                        value="{{ old('hora_finalizacion') }}"
                        class="w-full rounded border px-3 py-2 @error('hora_finalizacion') border-red-500 @else border-gray-400 @enderror">
                    @error('hora_finalizacion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="descripcion" class="mb-1 block text-sm font-medium text-slate-700">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" maxlength="200"
                        placeholder="Ej: Llevar cuadros de asistencia de alumnos y notas "
                        class="w-full rounded border px-3 py-2 @error('descripcion') border-red-500 @else border-gray-400 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <span class="mb-1 block text-sm font-medium text-slate-700">Color</span>
                    <span id="colorComision"
                        class="inline-block h-10 w-10 rounded-full border border-gray-500"
                        style="background:#cccccc;">
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-wrap justify-center gap-4 sm:gap-16">
            <a href="{{ route('dashboard') }}"
                class="bg-red-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                Cancelar
            </a>
            <button type="submit"
                class="bg-blue-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                Guardar Actividad
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const colores = {
                amarillo: '#f1c40f', verde: '#2ecc71', azul: '#3498db',
                naranja: '#e67e22', rojo: '#e74c3c', morado: '#9b59b6', rosa: '#ff8fd0',
            };

        const select  = document.getElementById('comision');
        const circulo = document.getElementById('colorComision');

        const pintar = () => {
            const valor = (select.selectedOptions[0].dataset.color || '').trim();
            circulo.style.background = valor.startsWith('#')
                ? valor
                : (colores[valor.toLowerCase()] || '#cccccc');
        };

        select.addEventListener('change', pintar);
        pintar();
    });
    </script>
</x-app-layout>