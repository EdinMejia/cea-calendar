<x-app-layout>
    @php
        $boton = 'border-2 border-black px-4 py-1.5 text-center text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase cursor-pointer';
        $input = 'border-2 border-black bg-white px-3 py-1.5 text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0';
        
        // Datos simulados (Frontend)
        $solicitudes = [
            ['id' => 1, 'nombre1' => 'Juan', 'nombre2' => 'Alberto', 'apellido1' => 'Martínez', 'apellido2' => 'Vázquez', 'correo' => 'juanito12@gmail.com'],
            ['id' => 2, 'nombre1' => 'Anselmo', 'nombre2' => 'Andrés', 'apellido1' => 'Castro', 'apellido2' => 'Ramirez', 'correo' => 'Andresito@gmail.com'],
        ];
    @endphp

    <div x-data="{ busqueda: '' }" class="w-full">
        
        <!-- Botón para regresar -->
        <div class="mb-4">
            <a href="{{ route('usuarios.index') }}" class="inline-block bg-gray-200 p-2 text-center text-xs font-bold text-black border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase">
                &larr; Volver a Gestión de Accesos
            </a>
        </div>

        <!-- Controles de búsqueda -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center">
            <input type="text" x-model="busqueda" placeholder="Buscar por nombre o apellido..." class="{{ $input }} w-full sm:flex-1 text-black">
            
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="button" class="{{ $boton }} bg-blue-600 text-white w-full sm:w-24">Buscar</button>
                <button type="button" @click="busqueda = ''" class="{{ $boton }} bg-blue-600 text-white w-full sm:w-24">Limpiar</button>
            </div>
        </div>

        <!-- Tabla de Solicitudes -->
        <div class="overflow-x-auto border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]">
            <table class="w-full min-w-[900px] border-collapse text-left text-sm">
                <thead class="bg-gray-200 border-b-2 border-black">
                    <tr>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Primer Nombre</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Segundo Nombre</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Primer Apellido</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Segundo Apellido</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Correo</th>
                        <th class="p-3 text-center font-bold uppercase w-64">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($solicitudes as $s)
                        <!-- Filtrado reactivo en Alpine.js -->
                        <tr x-show="
                            '{{ strtolower($s['nombre1'].' '.$s['nombre2'].' '.$s['apellido1'].' '.$s['apellido2']) }}'.includes(busqueda.toLowerCase()) || busqueda === ''
                        " class="border-b-2 border-black h-12 hover:bg-gray-50">
                            <td class="border-r-2 border-black px-3 font-bold text-black">{{ $s['nombre1'] }}</td>
                            <td class="border-r-2 border-black px-3 font-medium text-black">{{ $s['nombre2'] }}</td>
                            <td class="border-r-2 border-black px-3 font-bold text-black">{{ $s['apellido1'] }}</td>
                            <td class="border-r-2 border-black px-3 font-medium text-black">{{ $s['apellido2'] }}</td>
                            <td class="border-r-2 border-black px-3 font-medium text-blue-700 underline">{{ $s['correo'] }}</td>
                            
                            <td class="px-3 py-2">
                                <!-- x-data independiente para cada fila -->
                                <div x-data="{ rolSeleccionado: '' }" class="flex flex-wrap items-center justify-center gap-2">
        
                                <!-- Select de Asignar -->
                                    <select x-model="rolSeleccionado" class="border-2 border-black bg-white px-2 py-1.5 text-xs font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0 cursor-pointer w-[120px]">
                                        <option value="" disabled>Elegir rol...</option>
                                        <option value="Lector">Lector</option>
                                        <option value="Editor">Editor</option>
                                        <option value="Administrador">Administrador</option>
                                    </select>

                                    <button x-show="rolSeleccionado !== ''" x-cloak
                                            x-transition
                                            @click="
                                                // Aquí irá tu petición AJAX a Laravel en el futuro
                                                Swal.fire({
                                                    title: '¡Acceso Concedido!',
                                                    text: '{{ $s['nombre1'] }} ahora es ' + rolSeleccionado,
                                                    icon: 'success',
                                                    confirmButtonColor: '#15803d',
                                                    confirmButtonText: 'Entendido'
                                                });
                                                // Opcional: Ocultar la fila tras aprobar
                                                // $el.closest('tr').style.display = 'none';
                                            "
                                            class="border-2 border-black bg-green-600 px-3 py-1.5 text-xs font-bold text-white shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase">
                                        Aprobar
                                    </button>

                                    <button type="button"
                                            @click="
                                                Swal.fire({
                                                    title: '¿Rechazar solicitud?',
                                                    text: 'Denegarás el acceso a {{ $s['nombre1'] }}',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#b91c1c',
                                                    cancelButtonColor: '#6b7280',
                                                    confirmButtonText: 'Sí, rechazar',
                                                    cancelButtonText: 'Cancelar'
                                            })
                                        "
                                        class="border-2 border-black bg-white px-3 py-1.5 text-xs font-bold text-red-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase">
                                    Rechazar
                                </button>
                            </div>
                        </tr>
                    @endforeach
                    
                    <!-- Filas vacías para mantener estructura visual -->
                    @for ($i = 0; $i < 3; $i++)
                        <tr class="border-b-2 border-black h-12 last:border-b-0">
                            <td class="border-r-2 border-black px-3"></td>
                            <td class="border-r-2 border-black px-3"></td>
                            <td class="border-r-2 border-black px-3"></td>
                            <td class="border-r-2 border-black px-3"></td>
                            <td class="border-r-2 border-black px-3"></td>
                            <td class="px-3"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>