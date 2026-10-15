<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h1 class="text-2xl font-bold text-center mb-6">CREAR NUEVA COMISIÓN</h1>

                <form action="{{ route('comisiones.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8" x-data="{ mostrarModal: false, comisionSeleccionada: '', colorSeleccionado: 'transparent', comisionTexto: '-- Seleccione una --', dropdownAbierto: false, nuevoColor: '#3B82F6', nuevaComisionNombre: '' }">
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
                            
                            <select name="comision_id" x-model="comisionSeleccionada" x-on:change="if($event.target.value === 'nueva') { mostrarModal = true; } colorSeleccionado = $event.target.options[$event.target.selectedIndex].dataset.color;" 
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 mb-4">
                                
                                <option value="" disabled selected data-color="transparent"> Elija la comision </option>
                                @foreach ($comisiones as $comision)
                                    <option value="{{ $comision->id }}" data-color="{{ $comision->color ?? '#4B5563' }}">
                                        {{ $comision->nombre }}
                                    </option>
                                @endforeach
                                <option value="nueva" data-color="transparent" class="font-bold text-green-600">+ Añadir nueva comisión...</option>
                            </select>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ciclo</label>
                            <input type="date" name="ciclo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 mb-6">

                            <div class="flex items-center mt-4 gap-3">
                                <label class="block text-gray-700 text-sm font-bold">Color: </label>
                                <div style="width: 35px; height: 35px; border-radius: 70%;" 
                                    class="border border-gray-400 shadow-md transition-colors duration-200" 
                                    :style="{ backgroundColor: colorSeleccionado }">
                                </div>
                        </div>
                    </div>

                    <div x-show="mostrarModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60" style="display: none;">
                        <div @click.away="mostrarModal = false; comisionSeleccionada = ''" class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md mx-4 transform transition-all">
                            
                            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Crear nueva comisión</h3>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Nombre de la nueva comisión
                                </label>
                                <input type="text" name="nueva_comision_nombre" placeholder="Ej. Festividades" x-model="nuevaComisionNombre"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-indigo-500 focus:shadow-outline">

                                <label class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Color de la nueva comisión
                                </label>
                                <input type="hidden" name="color_nueva_comision" x-model="nuevoColor"> 

                                <div class="flex gap-3 mt-2">
                                    <button type="button"
                                        @click="nuevoColor = '#3B82F6'"
                                        class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform"
                                        :style="nuevoColor === '#3B82F6' ? 'background-color: #3B82F6; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #3B82F6; border: 2px solid transparent;'"></button>

                                    <button type="button"
                                        @click="nuevoColor = '#EF4444'" 
                                        class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform"
                                        :style="nuevoColor === '#EF4444' ? 'background-color: #EF4444; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #EF4444; border: 2px solid transparent;'">
                                        
                                    <button type="button" 
                                        @click="nuevoColor = '#8B5CF6'" 
                                        class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform"
                                        :style="nuevoColor === '#8B5CF6' ? 'background-color: #8B5CF6; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #8B5CF6; border: 2px solid transparent;'">
                                    
                                    <button type="button" 
                                        @click="nuevoColor = '#F97316'" 
                                        class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform"
                                        :style="nuevoColor === '#F97316' ? 'background-color: #F97316; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #F97316; border: 2px solid transparent;'">
                                    
                                    <button type="button" 
                                        @click="nuevoColor = '#14B8A6'" 
                                        class="w-8 h-8 rounded-full shadow-sm focus:outline-none transition-transform"
                                        :style="nuevoColor === '#14B8A6' ? 'background-color: #14B8A6; border: 3px solid #1f2937; transform: scale(1.15);' : 'background-color: #14B8A6; border: 2px solid transparent;'">
                                        
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="mostrarModal = false; comisionSeleccionada = ''" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded shadow">
                                    Cancelar
                                </button>
                                <button type="button"
                                    @click="
                                        fetch('{{ route('comisiones.ajax') }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({ nombre: nuevaComisionNombre, color: nuevoColor })
                                        })
                                        .then(async response => {
                                            const data = await response.json();
                                            if (response.ok && data.success) {
                                                let select = document.querySelector ('select[name=\'comision_id\']');
                                                let option = new Option(data.comision.name_comision, data.comision.id);
                                                option.setAttribute('data-color', data.comision.color_comision);
                                                select.insertBefore(option, select.lastElementChild);

                                                comisionSeleccionada = data.comision.id;
                                                colorSeleccionado = data.comision.color;

                                                nuevaComisionNombre = '';
                                                mostrarModal = false;
                                            } else {
                                                console.error('Error del servidor:', data);
                                                alert('Laravel dice: faltaban datos o hay un error. revisa la consola para más detalles.');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error de red:', error);
                                            alert('Error de red. revisa la consola para más detalles.');
                                        });
                                    "
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                                    Guardar Comisión
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-center gap-4 mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">
                        CREAR COMISIÓN
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