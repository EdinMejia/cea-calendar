<x-app-layout>

<x-alerta-cea />

    <h2 class="mb-8 text-2xl font-semibold text-slate-800">
        Crear Actividad: Complete la información.
    </h2>

    <form action="{{ route('actividades.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

            {{-- Columna izquierda --}}
            <div class="space-y-5">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nombre de la actividad</label>
                    <input type="text" name="nombre" maxlength="100"
                        placeholder="Ej: Reunión con coordinadores de carrera"
                        value="{{ old('nombre') }}"
                        class="w-full rounded border px-3 py-2 @error('nombre') border-red-500 @else border-gray-400 @enderror">
                    @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Lugar</label>
                    <input type="text" name="lugar" maxlength="100"
                        placeholder="Ej: Salon de maestros"
                        value="{{ old('lugar') }}"
                        class="w-full rounded border px-3 py-2 @error('lugar') border-red-500 @else border-gray-400 @enderror">
                    @error('lugar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Comisión</label>
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
                    <label class="mb-1 block text-sm font-medium text-slate-700">Fecha y Hora Inicio</label>
                    <input type="datetime-local" name="hora_fecha"
                        value="{{ old('hora_fecha') }}"
                        class="w-full rounded border px-3 py-2 @error('hora_fecha') border-red-500 @else border-gray-400 @enderror">
                    @error('hora_fecha') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Hora de Finalización</label>
                    <input type="time" name="hora_finalizacion"
                        value="{{ old('hora_finalizacion') }}"
                        class="w-full rounded border px-3 py-2 @error('hora_finalizacion') border-red-500 @else border-gray-400 @enderror">
                    @error('hora_finalizacion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Descripción</label>
                    <textarea name="descripcion" rows="4" maxlength="200"
                        placeholder="Ej: Llevar cuadros de asistencia de alumnos y notas "
                        class="w-full rounded border px-3 py-2 @error('descripcion') border-red-500 @else border-gray-400 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Color</label>
                    <span id="colorComision"
                        class="inline-block h-10 w-10 rounded-full border border-gray-500"
                        style="background:#cccccc;"></span>
                </div>
            </div>
        </div>

        <div class="mt-12 flex justify-center gap-16">
            <a href="{{ route('dashboard') }}"
                class="rounded bg-red-600 px-8 py-2 font-bold text-white shadow hover:bg-red-700">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded bg-blue-600 px-8 py-2 font-bold text-white shadow hover:bg-blue-700">
                Guardar Actividad
            </button>
        </div>
    </form>

    <script>
        const colores = {
            amarillo: '#f1c40f',
            verde:    '#2ecc71',
            azul:     '#3498db',
            naranja:  '#e67e22',
            rojo:     '#e74c3c',
            morado:   '#9b59b6',
            rosa:     '#ff8fd0',
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
    </script>

</x-app-layout>