<x-app-layout>
    <x-alerta-cea />

    <div class="mx-auto max-w-5xl border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]">

        <div class="bg-gray-100 border-b-2 border-black px-6 py-4">
            <h2 class="text-xl font-bold text-black uppercase">Editar actividad: actualice la información</h2>
        </div>

        <div class="p-8">

            <form action="{{ route('actividades.update', $actividad->id) }}" method="POST"
                onsubmit="this.querySelector('[type=submit]').disabled = true;">
                
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

                    {{-- Columna izquierda --}}
                    <div class="space-y-5">
                        <div>
                            <label for="nombre" class="mb-1 block text-sm font-medium text-slate-700">Nombre de la actividad</label>
                            <input type="text" id="nombre" name="nombre" maxlength="100"
                                placeholder="Ej: Reunión con coordinadores de carrera"
                                value="{{ old('nombre', $actividad->nombre) }}"
                                class="w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 @error('nombre') border-red-600 @else border-black @enderror">
                            @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="lugar" class="mb-1 block text-sm font-medium text-slate-700">Lugar</label>
                            <input type="text" id="lugar" name="lugar" maxlength="100"
                                placeholder="Ej: Salon de maestros"
                                value="{{ old('lugar', $actividad->lugar) }}"
                                class="w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 @error('lugar') border-red-600 @else border-black @enderror">
                            @error('lugar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="comision" class="mb-1 block text-sm font-medium text-slate-700">Comisión</label>
                            <select name="comisiones_id" id="comision"
                                    class="w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 @error('comisiones_id') border-red-600 @else border-black @enderror">
                                <option value="" data-color="#cccccc">Seleccione la comisión encargada</option>
                                @foreach ($comisiones as $comision)
                                    <option value="{{ $comision->id }}"
                                            data-color="{{ $comision->color_comision }}"
                                            @selected(old('comisiones_id', $actividad->comisiones_id) == $comision->id)>
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
                                <input type="text" id="hora_fecha" name="hora_fecha"
                                    value="{{ old('hora_fecha') }}" placeholder="Seleccione fecha y hora"
                                    class="w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 @error('hora_fecha') border-red-600 @else border-black @enderror">
                                @error('hora_fecha') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="hora_finalizacion" class="mb-1 block text-sm font-medium text-slate-700">Hora de Finalización</label>
                            <input type="text" id="hora_finalizacion" name="hora_finalizacion"
                                    value="{{ old('hora_finalizacion') }}" placeholder="Seleccione la hora"
                                    class="w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 @error('hora_finalizacion') border-red-600 @else border-black @enderror">
                            @error('hora_finalizacion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="descripcion" class="mb-1 block text-sm font-medium text-slate-700">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="4" maxlength="500"
                                placeholder="Ej: Llevar cuadros de asistencia de alumnos y notas "
                                class="w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 @error('descripcion') border-red-600 @else border-black @enderror">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                            @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <span class="mb-1 block text-sm font-medium text-slate-700">Color</span>
                            <span id="colorComision"
                                class="inline-block h-10 w-10 rounded-full border border-gray-500"
                                {!! 'style="background:' . ($actividad->comision->color_comision ?? '#cccccc') . ';"' !!}>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex flex-wrap justify-center gap-4 sm:gap-16">
                    <a href="{{ route('actividades.show', $actividad->id) }}"
                        class="bg-red-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-blue-600 text-white font-bold border-2 border-black px-4 sm:px-6 py-2 text-sm sm:text-base shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                        Actualizar Actividad
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" integrity="sha256-GzWFajsRx4nJ6i6O73xLzaE+sK37X/V/O2T9FmH1aP0=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js" integrity="sha256-Hu/Q2U6R5T7y9a2q8aYnmtmD7R6w0W3PZ4dI2gK9R5E=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/es.js" integrity="sha256-2qB/7lF4b8b8X9N+m0h8z2r5GzWFajsRx4nJ6i6O73x=" crossorigin="anonymous"></script>

    <script>
        flatpickr.localize(flatpickr.l10ns.es);

        flatpickr('#hora_fecha', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            altInput: true,
            altFormat: 'l j \\d\\e F \\d\\e Y, h:i K',
            altInputClass: 'w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 {{ $errors->has('hora_fecha') ? "border-red-600" : "border-black" }}',
            disableMobile: true,
        });

        flatpickr('#hora_finalizacion', {
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            altInput: true,
            altFormat: 'h:i K',
            altInputClass: 'w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 {{ $errors->has('hora_finalizacion') ? "border-red-600" : "border-black" }}',
            minuteIncrement: 5,
            disableMobile: true,
        });
    </script>
        <link rel="stylesheet" href="...flatpickr.min.css">
        <script src="...flatpickr.min.js"></script>
</x-app-layout>