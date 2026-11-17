<x-app-layout>
    <x-alerta-cea />

    <div class="mx-auto max-w-5xl border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]">

        <div class="bg-gray-100 border-b-2 border-black px-6 py-4">
            <h2 class="text-xl font-bold text-black uppercase">Editar actividad: actualice la información</h2>
        </div>

        <div class="p-8">

            <form action="{{ route('actividades.update', $actividad) }}" method="POST"
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
                                    value="{{ old('hora_fecha', $actividad->hora_fecha->format('Y-m-d H:i')) }}"
                                    class="w-full border-2 px-3 py-2 bg-white focus:outline-none focus:ring-0 @error('hora_fecha') border-red-600 @else border-black @enderror">
                                @error('hora_fecha') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="hora_finalizacion" class="mb-1 block text-sm font-medium text-slate-700">Hora de Finalización</label>
                            <input type="text" id="hora_finalizacion" name="hora_finalizacion"
                                    value="{{ old('hora_finalizacion', substr($actividad->hora_finalizacion, 0, 5)) }}"
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
                                class="inline-block h-10 w-10 rounded-full border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                                style="background:#cccccc;">
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex flex-wrap justify-center gap-4 sm:gap-16">
                    <a href="{{ route('actividades.show', $actividad) }}"
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/es.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        flatpickr.localize(flatpickr.l10ns.es);

        flatpickr('input[name="hora_fecha"]', {
            enableTime: true,
            time_24hr: false,
            dateFormat: 'Y-m-d H:i',
            altInput: true,
            altFormat: 'l j \\d\\e F \\d\\e Y, h:i K',
            altInputClass: "w-full border-2 border-black p-2 {{ $errors->has('hora_fecha') ? 'border-red-600' : '' }}",
            minuteIncrement: 5,
            disableMobile: true
        });

        flatpickr('input[name="hora_finalizacion"]', {
            enableTime: true,
            noCalendar: true,
            time_24hr: false,
            dateFormat: 'H:i',
            altInput: true,
            altFormat: 'h:i K',
            altInputClass: "w-full border-2 border-black p-2 {{ $errors->has('hora_finalizacion') ? 'border-red-600' : '' }}",
            minuteIncrement: 5,
            disableMobile: true
        });

        const select  = document.querySelector('select[name="comisiones_id"]');
        const circulo = document.getElementById('colorComision');
        if (select && circulo) {
            const nombres = {
                amarillo: '#facc15', verde: '#22c55e', azul: '#3b82f6',
                naranja: '#f97316', rojo: '#ef4444', morado: '#a855f7', rosa: '#ec4899'
            };
            function pintar() {
                const opcion = select.options[select.selectedIndex];
                const valor  = ((opcion && opcion.dataset.color) || '').trim();
                circulo.style.backgroundColor = nombres[valor.toLowerCase()] || valor || '#cccccc';
            }
            select.addEventListener('change', pintar);
            pintar();
        }
    });
    </script>

    <style>
        .flatpickr-calendar {
            border: 3px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: 6px 6px 0px rgba(0,0,0,1) !important;
            background-color: #ffffff !important;
            font-family: inherit !important;
            padding: 10px !important;
            box-sizing: border-box !important; 
            width: auto !important; 
            min-width: 320px !important; 
        }

        .flatpickr-months .flatpickr-month {
            background-color: #83C152 !important; 
            color: #000 !important;
            border: 2px solid #000 !important;
            margin-bottom: 10px;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 900 !important;
            text-transform: uppercase !important;
        }
        .flatpickr-current-month .numInputWrapper, 
        .flatpickr-current-month input.cur-year {
            background: transparent !important;
            color: #000 !important;
        }
        .flatpickr-current-month input.cur-year:hover,
        .flatpickr-current-month .numInputWrapper:hover {
            background: rgba(255, 255, 255, 0.2) !important; 
        }
        .flatpickr-prev-month, .flatpickr-next-month {
            fill: #000 !important;
            color: #000 !important;
            background-color: transparent !important; 
        }
        .flatpickr-prev-month:hover, .flatpickr-next-month:hover {
            background-color: #000 !important;
            fill: #fff !important;
        }
        span.flatpickr-weekday {
            color: #000 !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
        }
        .flatpickr-day {
            border: 2px solid transparent !important;
            border-radius: 0 !important;
            color: #000 !important;
            font-weight: bold !important;
        }
        .flatpickr-day:hover, .flatpickr-day:focus {
            background-color: #fde047 !important; 
            border-color: #000 !important;
            color: #000 !important;
            box-shadow: 2px 2px 0px rgba(0,0,0,1) !important;
        }
        .flatpickr-day.selected {
            background-color: #2563eb !important; 
            color: #fff !important;
            border: 2px solid #000 !important;
            box-shadow: 2px 2px 0px rgba(0,0,0,1) !important;
        }
        .flatpickr-day.flatpickr-disabled {
            color: #94a3b8 !important; 
        }
        .flatpickr-time {
            border: 2px solid #000 !important;
            margin-top: 10px !important;
            background-color: #f3f4f6 !important;
        }
        .flatpickr-time .numInputWrapper input, 
        .flatpickr-time .flatpickr-am-pm {
            font-weight: 900 !important;
            color: #000 !important;
            background: transparent !important; 
        }
        .flatpickr-time input:hover, .flatpickr-time .flatpickr-am-pm:hover, 
        .flatpickr-time input:focus, .flatpickr-time .flatpickr-am-pm:focus {
            background: #fde047 !important; 
            color: #000 !important;
        }
        .flatpickr-time .numInputWrapper input::selection,
        .flatpickr-time .flatpickr-am-pm::selection {
            background-color: #fde047 !important; 
            color: #000 !important;
        }
        .flatpickr-time .numInputWrapper input:focus-visible,
        .flatpickr-time .flatpickr-am-pm:focus-visible {
            outline: none !important;
        }
        .flatpickr-calendar.noCalendar {
            border: 3px solid #000 !important;
            border-radius: 0 !important;
            box-shadow: 4px 4px 0px rgba(0,0,0,1) !important;
            padding: 0 !important;
            background-color: #fff !important;
        }
        .flatpickr-calendar.noCalendar .flatpickr-time {
            border: none !important;
            margin-top: 0 !important;
        }
        .flatpickr-time .numInputWrapper input, 
        .flatpickr-time .flatpickr-am-pm {
            font-size: 1.1rem !important;
        }
        .flatpickr-time .flatpickr-time-separator {
            color: #000 !important;
            font-weight: 900 !important;
        }
        .flatpickr-time .numInputWrapper span.arrowUp,
        .flatpickr-time .numInputWrapper span.arrowDown {
            border-left: 2px solid #000 !important;
        }
        .flatpickr-time .numInputWrapper span.arrowUp {
            border-bottom: 2px solid #000 !important;
        }
        .flatpickr-time .numInputWrapper span.arrowUp:hover,
        .flatpickr-time .numInputWrapper span.arrowDown:hover {
            background-color: #000 !important;
        }
        .flatpickr-time .numInputWrapper span.arrowUp:hover:after {
            border-bottom-color: #fff !important;
        }
        .flatpickr-time .numInputWrapper span.arrowDown:hover:after {
            border-top-color: #fff !important;
        }
    </style>
</x-app-layout>