<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h1 class="text-2xl font-bold text-center mb-6 uppercase">EDITAR COMISIÓN: {{ $comision->name_comision }}</h1>

                @if ($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm" role="alert">
                        <div class ="flex items-center mb-2">
                            <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-bold text-sm">Por favor corrige los siguientes errores:</p>
                        </div>
                        <ul class="list-disc list-inside text-sm ml-7">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                
                <form action="{{ route('comisiones.update', $comision->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- OBLIGATORIO PARA EDITAR -->
                    
                    <!-- Alpine.js inicia leyendo el color actual de la base de datos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8" x-data="{ colorSeleccionado: '{{ old('color_comision', $comision->color_comision) }}', desbloquearEdicion: false }">
                        
                        <div>
                            <h2 class="text-lg font-bold mb-4">Docentes Asignados</h2>
                            <p class="text-sm text-gray-600 mb-4">Marque o desmarque a los docentes que conformarán la comisión</p>
                            
                            <div class="space-y-2 border p-4 rounded bg-gray-50 max-h-64 overflow-y-auto">
                                @foreach($docentes as $docente)
                                    <div>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="docentes[]" value="{{ $docente->id }}" 
                                                {{ $comision->docentes->contains($docente->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ml-2">{{ $docente->name_one }} {{ $docente->name_two}} {{ $docente->last_name_one }} </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        
                        <div>
                            <div class="mb-4 border p-4 rounded bg-gray-50">

                                <div class="mb-5 bg-yellow-50 border-yellow-200 p-3 rounded text-sm shadow-sm">
                                    <label class="flex items-center cursor-pointer text-yellow-800 font-bold">
                                        <input type="checkbox" x-model="desbloquearEdicion" class="rounded border-yellow-400 text-yellow-600 shadow-sm focus:ring-yellow-500 mr-2 w-5 h-5">
                                        Editar nombre y color (solo si existen errores)
                                    </label>
                                </div>
                                
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Comisión</label>
                                <input type="text" name="name_comision" value="{{ old('name_comision', $comision->name_comision) }}" 
                                    :readonly="!desbloquearEdicion"
                                    :class="!desbloquearEdicion ? 'bg-gray-200 cursor-not-allowed text-gray-500' : 'bg-white focus:border-indigo-500 focus:ring-indigo-500'"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none mb-4 transition-colors duration-300">
                                
                                <label class="block text-gray-700 text-sm font-bold mb-2">Ciclo</label>
                                <input type="date" name="ciclo" value="{{ old('ciclo', $comision->ciclo_comision) }}" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 mb-6">

                                <label class="block text-gray-700 text-sm font-bold mb-2">Color de la comisión</label>
                                <input type="hidden" name="color_comision" x-model="colorSeleccionado"> 

                                <div x-show="!desbloquearEdicion" class="flex gap-3 mt-2 items-center">
                                    <div class="w-8 h-8 rounded-full shadow-sm border-2 border-gray-400 opacity-70" :style="`background-color: ${colorSeleccionado};`"></div>
                                    <span class="text-xs text-gray-500 italic">Bloqueado</span>
                                </div>

                                <div x-show="desbloquearEdicion" style="display: none;" class="flex gap-3 mt-2">
                                    <button type="button" @click="colorSeleccionado = '#3B82F6'" class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform" :style="colorSeleccionado === '#3B82F6' ? 'background-color: #3B82F6; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #3B82F6; border: 2px solid transparent;'"></button>
                                    <button type="button" @click="colorSeleccionado = '#EF4444'" class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform" :style="colorSeleccionado === '#EF4444' ? 'background-color: #EF4444; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #EF4444; border: 2px solid transparent;'"></button>
                                    <button type="button" @click="colorSeleccionado = '#8B5CF6'" class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform" :style="colorSeleccionado === '#8B5CF6' ? 'background-color: #8B5CF6; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #8B5CF6; border: 2px solid transparent;'"></button>
                                    <button type="button" @click="colorSeleccionado = '#F97316'" class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform" :style="colorSeleccionado === '#F97316' ? 'background-color: #F97316; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #F97316; border: 2px solid transparent;'"></button>
                                    <button type="button" @click="colorSeleccionado = '#14B8A6'" class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform" :style="colorSeleccionado === '#14B8A6' ? 'background-color: #14B8A6; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #14B8A6; border: 2px solid transparent;'"></button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex items-center justify-center gap-5 mt-6">
                        <button type="submit" class="bg-green-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">
                            ACTUALIZAR COMISIÓN
                        </button>
                        <a href="{{ route('comisiones.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">
                            CANCELAR
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>