<x-app-layout>
    @php
        $boton = 'border-2 border-black px-4 py-1.5 text-center text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase cursor-pointer';
        $input = 'border-2 border-black bg-white px-3 py-1.5 text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0';
    @endphp

    <div x-data="{ busqueda: '{{ $busqueda }}' }" class="w-full">
        
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
                <button type="button" @click="busqueda = ''" class="{{ $boton }} bg-blue-600 text-white w-full sm:w-32">Limpiar filtro</button>
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
                <tbody x-data="{
                    async procesarSolicitud(url, metodo, datos, filaElemento) {
                        try {
                            const respuesta = await fetch(url, {
                                method: metodo,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: datos ? JSON.stringify(datos) : null
                            });
                            const resultado = await respuesta.json();
                            
                            if (resultado.success) {
                                filaElemento.style.opacity = '0';
                                setTimeout(() => filaElemento.style.display = 'none', 300);
                                return resultado.message;
                            }
                            throw new Error('Error en la petición');
                        } catch (error) {
                            Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
                            return false;
                        }
                    }
                }">
                    @forelse ($solicitudes as $s)
                        <tr x-show="
                                '{{ strtolower($s->name_one.' '.$s->name_two.' '.$s->last_name_one.' '.$s->last_name_two) }}'.includes(busqueda.toLowerCase()) || busqueda === ''
                            " class="border-b-2 border-black h-12 hover:bg-gray-50 transition-opacity duration-300">
                            
                            <td class="border-r-2 border-black px-3 font-bold text-black">{{ $s->name_one }}</td>
                            <td class="border-r-2 border-black px-3 font-medium text-black">{{ $s->name_two }}</td>
                            <td class="border-r-2 border-black px-3 font-bold text-black">{{ $s->last_name_one }}</td>
                            <td class="border-r-2 border-black px-3 font-medium text-black">{{ $s->last_name_two }}</td>
                            <td class="border-r-2 border-black px-3 font-medium text-blue-700 underline">{{ $s->email }}</td>
                            
                            <td class="px-3 py-2">
                                <div x-data="{ rolSeleccionado: '' }" class="flex flex-wrap items-center justify-center gap-2">
                                    
                                    <select x-model="rolSeleccionado" class="border-2 border-black bg-white px-2 py-1.5 text-xs font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0 cursor-pointer w-[120px]">
                                        <option value="" disabled>Elegir rol...</option>
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <!-- Botón Aprobar -->
                                    <button x-show="rolSeleccionado !== ''" x-cloak
                                            @click="
                                                let mensaje = await procesarSolicitud(
                                                    '/usuarios/solicitudes/{{ $s->id }}/aprobar', 
                                                    'POST', 
                                                    { rol: rolSeleccionado }, 
                                                    $el.closest('tr')
                                                );
                                                if(mensaje) {
                                                    Swal.fire({
                                                        title: '¡Acceso Concedido!',
                                                        text: mensaje,
                                                        icon: 'success',
                                                        confirmButtonColor: '#15803d'
                                                    });
                                                }
                                            "
                                            class="border-2 border-black bg-green-600 px-3 py-1.5 text-xs font-bold text-white shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase">
                                        Aprobar
                                    </button>
                                    
                                    <!-- Botón Rechazar -->
                                    <button type="button"
                                            @click="
                                                Swal.fire({
                                                    title: '¿Rechazar solicitud?',
                                                    text: 'Denegarás el acceso a {{ $s->name_one }}',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#b91c1c',
                                                    cancelButtonColor: '#6b7280',
                                                    confirmButtonText: 'Sí, rechazar',
                                                    cancelButtonText: 'Cancelar'
                                                }).then(async (result) => {
                                                    if (result.isConfirmed) {
                                                        let mensaje = await procesarSolicitud(
                                                            '/usuarios/solicitudes/{{ $s->id }}/rechazar', 
                                                            'PATCH', 
                                                            null, 
                                                            $el.closest('tr')
                                                        );
                                                        if(mensaje) Swal.fire('Rechazado', mensaje, 'info');
                                                    }
                                                });
                                            "
                                            class="border-2 border-black bg-white px-3 py-1.5 text-xs font-bold text-red-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase">
                                        Rechazar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500 font-bold uppercase border-b-2 border-black">
                                No hay solicitudes pendientes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>