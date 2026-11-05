<x-app-layout>
    @php
        $boton = 'border-2 border-black px-4 py-1.5 text-center text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase';
        $input = 'border-2 border-black bg-white px-3 py-1.5 text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0';
        
        $usuarios = [
            ['id' => 1, 'nombre' => 'Alonso Vazquez', 'rol' => 'Lector'],
            ['id' => 2, 'nombre' => 'Edin Mejia', 'rol' => 'Administrador'],
            ['id' => 3, 'nombre' => 'Pablo Perez', 'rol' => 'Editor'],
        ];
    @endphp

    <div x-data="{ 
            panelAbierto: false, 
            usuarioActivo: '', 
            rolActivo: '',
            busqueda: '',
            filtroRol: ''
        }" class="w-full">
        
        <!-- Botón superior -->
        <div class="mb-4 flex gap-2">
            <a href="{{ route('usuarios.solicitudes') }}" class="w-full bg-[#15803d] p-2 text-center text-sm font-bold text-white border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase block">
                Solicitudes de Acceso
            </a>
        </div>

        <!-- Controles de búsqueda -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center">
            <input type="text" x-model="busqueda" placeholder="Buscar por nombre..." class="{{ $input }} w-full sm:w-1/3 text-black">
            
            <select x-model="filtroRol" class="{{ $input }} w-full sm:w-1/3 text-gray-700">
                <option value="">Filtrar por:</option>
                <option value="Administrador">Administrador</option>
                <option value="Editor">Editor</option>
                <option value="Lector">Lector</option>
            </select>

            <button type="button" @click="busqueda = ''; filtroRol = ''" class="{{ $boton }} bg-blue-600 text-white w-24">Limpiar</button>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]">
            <table class="w-full min-w-[700px] border-collapse text-left text-sm">
                <thead class="bg-gray-200 border-b-2 border-black">
                    <tr>
                        <th class="w-1/3 border-r-2 border-black p-3 font-bold uppercase">Usuario</th>
                        <th class="w-1/3 border-r-2 border-black p-3 font-bold uppercase">Roles Asignados</th>
                        <th class="w-1/3 p-3 text-center font-bold uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $u)
                        <!-- La fila se oculta si no coincide con la búsqueda o el filtro -->
                        <tr x-show="
                            ('{{ strtolower($u['nombre']) }}'.includes(busqueda.toLowerCase()) || busqueda === '') &&
                            ('{{ $u['rol'] }}' === filtroRol || filtroRol === '')
                        " class="border-b-2 border-black h-12 hover:bg-gray-50">
                            <td class="border-r-2 border-black px-3 font-bold text-black">{{ $u['nombre'] }}</td>
                            <td class="border-r-2 border-black px-3 font-medium text-black">{{ $u['rol'] }}</td>
                            <td class="px-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <button @click="panelAbierto = true; usuarioActivo = '{{ $u['nombre'] }}'; rolActivo = '{{ $u['rol'] }}'" 
                                            class="{{ $boton }} bg-white text-black text-xs py-1">
                                        Asignar Acceso
                                    </button>
                                    <button class="{{ $boton }} bg-white text-red-700 text-xs py-1">
                                        Eliminar usuario
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @for ($i = 0; $i < 3; $i++)
                        <tr class="border-b-2 border-black h-12 last:border-b-0">
                            <td class="border-r-2 border-black px-3"></td>
                            <td class="border-r-2 border-black px-3"></td>
                            <td class="px-3"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Fondo oscuro -->
        <div x-show="panelAbierto" x-cloak class="fixed inset-0 z-40 bg-black/50 transition-opacity" @click="panelAbierto = false"></div>

        <!-- Panel Lateral Derecha -->
        <div x-show="panelAbierto" x-cloak 
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 z-50 flex w-full max-w-sm flex-col border-l-4 border-black bg-white shadow-[0_0_15px_rgba(0,0,0,0.5)] overflow-y-auto">
            
            <div class="bg-gray-100 p-4 pb-2 border-b-2 border-black">
                <h3 class="text-sm font-bold uppercase leading-tight text-black">
                    Gestionar Accesos:<br>
                    <span x-text="usuarioActivo"></span> | <span x-text="rolActivo"></span>
                </h3>
            </div>

            <div class="flex-1 p-4 flex flex-col gap-6">
                <!-- Roles Disponibles -->
                <div class="border-2 border-black p-3 shadow-[2px_2px_0px_rgba(0,0,0,1)] bg-white">
                    <h4 class="mb-3 font-bold uppercase border-b-2 border-black pb-1 text-black">Roles Disponibles:</h4>
                    <div class="flex flex-col gap-2 text-sm font-bold text-black">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="h-4 w-4 border-2 border-black text-blue-600 focus:ring-0 rounded-none"> Lector (permiso base)</label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="h-4 w-4 border-2 border-black text-blue-600 focus:ring-0 rounded-none"> Administrador</label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="h-4 w-4 border-2 border-black text-blue-600 focus:ring-0 rounded-none"> Editor</label>
                    </div>
                </div>

                <!-- Permisos Directos -->
                <div class="border-2 border-black p-3 shadow-[2px_2px_0px_rgba(0,0,0,1)] bg-white">
                    <h4 class="mb-3 font-bold uppercase leading-tight border-b-2 border-black pb-1 text-black">Permisos directos<br><span class="text-[10px] text-gray-500 font-normal">(EXCEPCIONES)</span></h4>
                    
                    <div class="mb-4">
                        <h5 class="text-xs font-bold uppercase text-gray-700 mb-2">Actividades:</h5>
                        <div class="flex flex-wrap gap-2 text-sm font-bold text-black">
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" class="h-3 w-3 border-2 border-black text-blue-600 rounded-none"> Editar</label>
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" class="h-3 w-3 border-2 border-black text-blue-600 rounded-none"> Crear</label>
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" class="h-3 w-3 border-2 border-black text-blue-600 rounded-none"> Borrar</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-xs font-bold uppercase text-gray-700 mb-2">Comisiones:</h5>
                        <div class="flex flex-wrap gap-2 text-sm font-bold text-black">
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" class="h-3 w-3 border-2 border-black text-blue-600 rounded-none"> Editar</label>
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" class="h-3 w-3 border-2 border-black text-blue-600 rounded-none"> Crear</label>
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" class="h-3 w-3 border-2 border-black text-blue-600 rounded-none"> Borrar</label>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-xs font-bold uppercase text-gray-700 mb-2">Usuarios:</h5>
                        <div class="flex flex-wrap gap-2 text-sm font-bold text-black">
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" class="h-3 w-3 border-2 border-black text-blue-600 rounded-none"> Ver usuario</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones Panel -->
            <div class="p-4 flex gap-3 justify-center mb-4 border-t-2 border-black bg-gray-100">
                <button type="button" @click="panelAbierto = false" class="{{ $boton }} bg-white text-gray-500 border-black shadow-[2px_2px_0px_rgba(0,0,0,1)] w-full text-xs">
                    Cancelar
                </button>
                <button type="button" class="{{ $boton }} bg-blue-600 text-white border-black shadow-[2px_2px_0px_rgba(0,0,0,1)] w-full text-xs">
                    Guardar
                </button>
            </div>
        </div>

    </div>
</x-app-layout>