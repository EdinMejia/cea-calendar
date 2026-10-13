<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h1 class="text-2xl font-bold text-center mb-6">CREAR NUEVA COMISIÓN</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8" x-data="{ mostrarModal: false, comisionSeleccionada: '' }">
                    
                    <div>
                        <h2 class="text-lg font-bold mb-4">Nombres</h2>
                        <p class="text-sm text-gray-600 mb-4">Seleccione el nombre de los docentes que conformarán la comisión</p>
                        
                        <div class="space-y-2 border p-4 rounded bg-gray-50 max-h-64 overflow-y-auto">
                            @foreach($docentes as $docente)
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="docentes[]" value="{{ $docente->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2">{{ $docente->name_one }} {{ $docente->name_two}} {{ $docente->last_name_one }} </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="mb-4 border p-4 rounded bg-gray-50">
                            
                            <label class="block text-gray-700 text-sm font-bold mb-2">Seleccione la Comisión</label>
                            
                            <select name="comision_id" 
                                x-model="comisionSeleccionada"
                                    x-on:change="if($event.target.value === 'nueva') { mostrarModal = true; }" 
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                                
                                <option value="" disabled selected> Elija la comision </option>
                                <option value="1">Deportes (Amarillo)</option>
                                <option value="2">Cultura y Civismo (Verde)</option>
                                <option value="nueva" class="font-bold text-green-600">+ Añadir nueva comisión...</option>
                                
                            </select>
                        </div>
                    </div>

                    <div x-show="mostrarModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60" style="display: none;">
                        <div @click.away="mostrarModal = false; comisionSeleccionada = ''" class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md mx-4 transform transition-all">
                            
                            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Crear nueva comisión</h3>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Nombre de la nueva comisión
                                </label>
                                <input type="text" name="nueva_comision_nombre" placeholder="Ej. Festividades" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="mostrarModal = false; comisionSeleccionada = ''" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded shadow">
                                    Cancelar
                                </button>
                                <button type="button" @click="mostrarModal = false" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                    Guardar Comisión
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-center mt-8 gap-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">
                        ASIGNAR COMISIÓN
                    </button>
                    <a href="{{ route('comisiones.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">
                        CANCELAR
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>