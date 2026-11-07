<x-app-layout>
    @php
        $boton = 'border-2 border-black px-4 py-1.5 text-center text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all uppercase';
        $input = 'border-2 border-black bg-white px-3 py-1.5 text-sm font-bold shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0';

        $usuariosJs = $usuarios->map(fn ($u) => [
            'id'     => $u->id,
            'nombre' => trim(($u->name_one ?? '') . ' ' . ($u->last_name_one ?? '')),
            'rol'    => $u->roles->first() ? ucfirst($u->roles->first()->name) : 'Sin rol',
        ])->values();
    @endphp

    <div x-data="{
            panelAbierto: false,
            usuarioActivo: '',
            rolActivo: '',
            busqueda: @js($busqueda),
            filtroRol: @js($filtroRol),
            usuarios: @js($usuariosJs),
            rolesSeleccionados: [],
            permisosDirectos: { actividades: [], comisiones: [], usuarios: [] },

            get usuariosFiltrados() {
                const q = this.busqueda.trim().toLowerCase();
                return this.usuarios.filter(u => {
                    const coincideNombre = !q || u.nombre.toLowerCase().includes(q);
                    const coincideRol    = !this.filtroRol || u.rol === this.filtroRol;
                    return coincideNombre && coincideRol;
                });
            },

            abrirPanel(nombre, rol) {
                this.usuarioActivo = nombre;
                this.rolActivo = rol;
                this.panelAbierto = true;
            },

            cerrarPanel() { this.panelAbierto = false; },

            limpiarFiltros() {
                this.busqueda = '';
                this.filtroRol = '';
            }
        }"
        class="w-full max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">

        <!-- Botón superior: Se ajusta el texto para que no haga salto de línea en móvil -->
        <div class="mb-4">
            <a href="{{ route('usuarios.solicitudes') }}" class="block w-full bg-[#15803d] px-2 py-2.5 text-center text-xs sm:text-sm font-bold text-white border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,1)] uppercase hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all whitespace-nowrap overflow-hidden text-ellipsis">
                Ver Solicitudes de Acceso
            </a>
        </div>

        <!-- Controles de búsqueda -->
        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center">
            <input
                type="text"
                x-model="busqueda"
                placeholder="Buscar por nombre..."
                autocomplete="off"
                aria-label="Buscar usuario por nombre"
                class="{{ $input }} w-full md:w-1/3 text-black text-xs sm:text-sm">

            <select
                x-model="filtroRol"
                aria-label="Filtrar por rol"
                class="{{ $input }} w-full md:w-1/3 text-gray-700 text-xs sm:text-sm">
                <option value="">Filtrar por:</option>
                @foreach($roles as $rol)
                    <option value="{{ ucfirst($rol) }}">{{ ucfirst($rol) }}</option>
                @endforeach
            </select>

            <button type="button" @click="limpiarFiltros()"
                    class="{{ $boton }} bg-blue-600 text-white w-full md:w-32 text-xs sm:text-sm">
                Limpiar
            </button>
        </div>

        <!-- Tabla Responsiva -->
        <div class="w-full overflow-x-auto border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]">
            <!-- Reduje el min-w a 550px para que en celulares no haya tanto scroll -->
            <table class="w-full min-w-[550px] border-collapse text-left text-xs sm:text-sm">
                <thead class="bg-gray-200 border-b-2 border-black">
                    <tr>
                        <th class="w-1/3 border-r-2 border-black p-2 sm:p-3 font-bold uppercase">Usuario</th>
                        <th class="w-1/3 border-r-2 border-black p-2 sm:p-3 font-bold uppercase">Roles Asignados</th>
                        <th class="w-1/3 p-2 sm:p-3 text-center font-bold uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="u in usuariosFiltrados" :key="u.id">
                        <tr class="border-b-2 border-black h-12 hover:bg-gray-50">
                            <td class="border-r-2 border-black px-2 sm:px-3 font-bold text-black" x-text="u.nombre"></td>
                            <td class="border-r-2 border-black px-2 sm:px-3 font-medium text-black" x-text="u.rol"></td>
                            <td class="px-2 sm:px-3 py-2">
                                <!-- En móvil los botones se mantienen en horizontal para no hacer la fila tan alta -->
                                <div class="flex flex-row justify-center gap-1 sm:gap-2">
                                    <button type="button"
                                            @click="abrirPanel(u.nombre, u.rol)"
                                            class="{{ $boton }} bg-white text-black text-[10px] sm:text-xs py-1 px-2 sm:px-4">
                                        Asignar
                                    </button>
                                    <button type="button"
                                            class="{{ $boton }} bg-white text-red-700 text-[10px] sm:text-xs py-1 px-2 sm:px-4">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Estado vacío -->
                    <tr x-show="usuariosFiltrados.length === 0" x-cloak>
                        <td colspan="3" class="p-6 text-center text-xs sm:text-sm font-bold uppercase text-gray-500">
                            No se encontraron usuarios
                        </td>
                    </tr>

                    <!-- Filas de relleno -->
                    <template x-for="i in Math.max(0, 3 - usuariosFiltrados.length)" :key="'filler-' + i">
                        <tr class="border-b-2 border-black h-12 last:border-b-0">
                            <td class="border-r-2 border-black px-2 sm:px-3"></td>
                            <td class="border-r-2 border-black px-2 sm:px-3"></td>
                            <td class="px-2 sm:px-3"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Fondo oscuro -->
        <div x-show="panelAbierto"
            x-cloak
            aria-hidden="true"
            class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity"
            @click="cerrarPanel()"></div>

        <!-- Panel Lateral Derecha -->
        <div x-show="panelAbierto"
            x-cloak
            role="dialog"
            aria-modal="true"
            aria-label="Gestionar accesos de usuario"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 z-50 flex w-full sm:max-w-md flex-col border-l-4 border-black bg-white shadow-[-5px_0_15px_rgba(0,0,0,0.3)]">
            
            <!-- Header del Panel -->
            <div class="bg-gray-100 p-3 sm:p-4 border-b-2 border-black shrink-0">
                <h3 class="text-xs sm:text-sm font-bold uppercase leading-tight text-black">
                    Gestionar Accesos:<br>
                    <span x-text="usuarioActivo" class="text-blue-700"></span> 
                    <span class="hidden sm:inline">|</span> 
                    <span x-text="rolActivo" class="text-gray-600 block sm:inline mt-1 sm:mt-0"></span>
                </h3>
            </div>

            <!-- Contenido con Scroll -->
            <div class="flex-1 p-3 sm:p-4 flex flex-col gap-4 sm:gap-5 overflow-y-auto">
                <!-- Roles Disponibles -->
                <div class="border-2 border-black p-3 shadow-[2px_2px_0px_rgba(0,0,0,1)] bg-white">
                    <h4 class="mb-2 sm:mb-3 font-bold uppercase border-b-2 border-black pb-1 text-black text-xs sm:text-sm">Roles Disponibles:</h4>
                    <div class="flex flex-col gap-2 sm:gap-3 text-xs sm:text-sm font-bold text-black">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" value="lector" x-model="rolesSeleccionados" class="h-4 w-4 border-2 border-black text-blue-600 focus:ring-0 rounded-none cursor-pointer">
                            Lector (base)
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" value="administrador" x-model="rolesSeleccionados" class="h-4 w-4 border-2 border-black text-blue-600 focus:ring-0 rounded-none cursor-pointer">
                            Administrador
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" value="editor" x-model="rolesSeleccionados" class="h-4 w-4 border-2 border-black text-blue-600 focus:ring-0 rounded-none cursor-pointer">
                            Editor
                        </label>
                    </div>
                </div>

                <!-- Permisos Directos -->
                <div class="border-2 border-black p-3 shadow-[2px_2px_0px_rgba(0,0,0,1)] bg-white">
                    <h4 class="mb-2 sm:mb-3 font-bold uppercase leading-tight border-b-2 border-black pb-1 text-black text-xs sm:text-sm">
                        Permisos directos<br>
                        <span class="text-[10px] text-gray-500 font-normal">(EXCEPCIONES)</span>
                    </h4>
                    
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <h5 class="text-[11px] sm:text-xs font-bold uppercase text-gray-700 mb-1.5 sm:mb-2">Actividades:</h5>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-[11px] sm:text-sm font-bold text-black">
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" value="editar" x-model="permisosDirectos.actividades" class="h-3.5 w-3.5 sm:h-4 sm:w-4 border-2 border-black text-blue-600 rounded-none cursor-pointer"> Editar</label>
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" value="crear" x-model="permisosDirectos.actividades" class="h-3.5 w-3.5 sm:h-4 sm:w-4 border-2 border-black text-blue-600 rounded-none cursor-pointer"> Crear</label>
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" value="borrar" x-model="permisosDirectos.actividades" class="h-3.5 w-3.5 sm:h-4 sm:w-4 border-2 border-black text-blue-600 rounded-none cursor-pointer"> Borrar</label>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-[11px] sm:text-xs font-bold uppercase text-gray-700 mb-1.5 sm:mb-2">Comisiones:</h5>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-[11px] sm:text-sm font-bold text-black">
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" value="editar" x-model="permisosDirectos.comisiones" class="h-3.5 w-3.5 sm:h-4 sm:w-4 border-2 border-black text-blue-600 rounded-none cursor-pointer"> Editar</label>
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" value="crear" x-model="permisosDirectos.comisiones" class="h-3.5 w-3.5 sm:h-4 sm:w-4 border-2 border-black text-blue-600 rounded-none cursor-pointer"> Crear</label>
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" value="borrar" x-model="permisosDirectos.comisiones" class="h-3.5 w-3.5 sm:h-4 sm:w-4 border-2 border-black text-blue-600 rounded-none cursor-pointer"> Borrar</label>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-[11px] sm:text-xs font-bold uppercase text-gray-700 mb-1.5 sm:mb-2">Usuarios:</h5>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-[11px] sm:text-sm font-bold text-black">
                                <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" value="ver" x-model="permisosDirectos.usuarios" class="h-3.5 w-3.5 sm:h-4 sm:w-4 border-2 border-black text-blue-600 rounded-none cursor-pointer"> Ver usuario</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones Panel -->
            <div class="p-3 sm:p-4 flex flex-col-reverse sm:flex-row gap-2 sm:gap-3 justify-center border-t-2 border-black bg-gray-100 shrink-0">
                <button type="button"
                        @click="cerrarPanel()"
                        class="{{ $boton }} bg-white text-gray-500 border-black w-full text-xs py-2">
                    Cancelar
                </button>
                <button type="button"
                        class="{{ $boton }} bg-blue-600 text-white border-black w-full text-xs py-2">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</x-app-layout>