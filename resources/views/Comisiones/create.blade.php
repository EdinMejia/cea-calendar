<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Comisión') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h1 class="text-2xl font-bold text-center mb-6">CREAR NUEVA COMISIÓN</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Columna Izquierda: Checkboxes -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Nombres</h3>
                        <p class="text-sm text-gray-600 mb-4">Seleccione el nombre de los docentes que conformarán la comisión</p>
                        
                        <div class="space-y-2 border p-4 rounded bg-gray-50">
                            @foreach($docentes as $docente)
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="docentes[]" value="{{ $docente->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2">{{ $docente->name_one }} {{ $docente->last_name_one }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Columna Derecha: Formulario -->
                    <div>
                        <div class="border p-4 rounded bg-gray-50 space-y-4">
                            <!-- Nombre de la Comisión -->
                            <div>
                                <x-input-label for="nombre" :value="__('Ingrese el nombre de la nueva comisión')" />
                                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" placeholder="ej. Festividades" required />
                            </div>

                            <!-- Color -->
                            <div>
                                <x-input-label for="color" :value="__('Seleccione el color')" />
                                <select name="color" id="color" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="#FCD34D">Deportes (Amarillo )</option>
                                    <option value="#22C55E">Ecologia (verde)</option>
                                    <option value="#3B82F6">Azul (Tecnología)</option>
                                    <option value="#F97316">Anaranjado (Cultura)</option>
                                    <option value="#0EA5E9">Celeste (Académico)</option>
                                </select>
                            </div>

                            <!-- Ciclo -->
                            <div>
                                <x-input-label for="ciclo" :value="__('Ciclo')" />
                                <x-text-input id="ciclo" class="block mt-1 w-full" type="date" name="ciclo" required />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-center mt-8 gap-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        CREAR COMISIÓN
                    </button>
                    <a href="#" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        CANCELAR
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>