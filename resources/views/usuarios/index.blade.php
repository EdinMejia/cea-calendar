<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Gestión de accesos</h2>
    </x-slot>

    @php
        $boton = 'inline-flex min-h-[44px] items-center justify-center border-2 border-black px-4 py-2 text-center text-sm font-bold uppercase shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-[2px] active:shadow-[2px_2px_0px_rgba(0,0,0,1)]';
        $campo = 'border-2 border-black bg-white px-3 py-2 text-base font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0 sm:text-sm';
        $celda = 'block px-3 py-1 before:block before:text-xs before:font-bold before:uppercase before:text-gray-600 before:content-[attr(data-label)] md:table-cell md:border-r-2 md:border-black md:py-3 md:before:hidden';
    @endphp

    {{-- Lógica del filtro y del panel. Se registra antes de que arranque Alpine --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('accesos', (cfg) => ({
                busqueda: cfg.busqueda || '',
                filtroRol: cfg.filtroRol || '',
                usuarios: cfg.usuarios,
                permisosDeRol: cfg.permisosDeRol,
                urlActualizar: cfg.urlActualizar,

                panelAbierto: false,
                enviando: false,
                usuario: null,
                rol: '',
                permisos: [],

                normalizar(t) {
                    return (t || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                },

                // Ids de los usuarios que coinciden con la búsqueda y el rol elegido
                get visibles() {
                    const q = this.normalizar(this.busqueda.trim());
                    return this.usuarios
                        .filter((u) => (!q || u.busqueda.includes(q))
                            && (!this.filtroRol || (u.rol || '(sin rol)') === this.filtroRol))
                        .map((u) => u.id);
                },

                get accion() {
                    return this.usuario ? this.urlActualizar.replace('__ID__', this.usuario.id) : '#';
                },

                limpiarFiltros() { this.busqueda = ''; this.filtroRol = ''; },

                // Un permiso "por rol" ya lo da el rol elegido: se muestra marcado y bloqueado
                heredado(nombre) { return (this.permisosDeRol[this.rol] || []).includes(nombre); },

                alternar(nombre) {
                    this.permisos = this.permisos.includes(nombre)
                        ? this.permisos.filter((p) => p !== nombre)
                        : [...this.permisos, nombre];
                },

                abrirPanel(id) {
                    const u = this.usuarios.find((x) => x.id === id);
                    if (!u) return;
                    this.usuario = u;
                    this.rol = u.rol || '';
                    this.permisos = [...u.permisos];
                    this.enviando = false;
                    this.panelAbierto = true;
                },

                cerrarPanel() { this.panelAbierto = false; },
            }));
        });
    </script>

    <div x-data="accesos(@js($datos))" @keydown.escape.window="cerrarPanel()" class="w-full">

        {{-- Solicitudes pendientes (solo quien gestiona accesos) --}}
        @can('usuarios.gestionar')
            <div class="mb-4">
                <a href="{{ route('usuarios.solicitudes') }}"
                    class="{{ $boton }} w-full gap-2 bg-green-700 text-white sm:w-auto">
                    Ver solicitudes de acceso
                    @if ($pendientes > 0)
                        <span class="border-2 border-black bg-white px-2 text-xs text-black">{{ $pendientes }}</span>
                    @endif
                </a>
            </div>
        @endcan

        {{-- Búsqueda y filtro (instantáneos, sin recargar) --}}
        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center">
            <input type="search" x-model="busqueda" autocomplete="off"
                    aria-label="Buscar usuario" placeholder="Buscar por nombre, correo o rol..."
                    class="{{ $campo }} w-full md:flex-1">

            <select x-model="filtroRol" aria-label="Filtrar por rol" class="{{ $campo }} w-full md:w-56">
                <option value="">Filtrar por rol</option>
                @foreach ($roles as $rol)
                    <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                @endforeach
                <option value="(sin rol)">Sin rol</option>
            </select>

            <button type="button" @click="limpiarFiltros()" class="{{ $boton }} w-full bg-blue-600 text-white md:w-auto">
                Limpiar
            </button>
        </div>

        {{-- Lista: tabla en computadora, tarjetas en celular --}}
        <section class="border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]" aria-label="Usuarios con acceso">
            <div class="flex items-center justify-between gap-3 border-b-2 border-black bg-[#83C152] px-4 py-3">
                <h3 class="text-lg font-bold uppercase text-black">Usuarios con acceso</h3>
                <span class="border-2 border-black bg-white px-2 py-0.5 text-sm font-bold text-black"
                        x-text="visibles.length + ' de ' + usuarios.length"></span>
            </div>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="hidden border-b-2 border-black bg-gray-200 md:table-header-group">
                    <tr>
                        <th class="w-2/5 border-r-2 border-black p-3 font-bold uppercase">Usuario</th>
                        <th class="w-1/5 border-r-2 border-black p-3 font-bold uppercase">Rol asignado</th>
                        <th class="p-3 text-center font-bold uppercase">Acciones</th>
                    </tr>
                </thead>

                <tbody class="block md:table-row-group">
                    @foreach ($usuarios as $u)
                        @php $rolNombre = $u->roles->first()?->name; @endphp

                        <tr x-show="visibles.includes({{ $u->id }})"
                            class="block border-b-2 border-black p-2 text-black last:border-b-0 hover:bg-gray-50 md:table-row md:p-0">

                            <td data-label="Usuario" class="{{ $celda }}">
                                <span class="font-bold">{{ $u->name }}</span>
                                @if ($u->id === $yoId)
                                    <span class="ml-1 border border-black bg-[#83C152] px-1 text-[10px] font-bold uppercase">Tú</span>
                                @endif
                                <span class="block break-all text-xs text-gray-600">{{ $u->email }}</span>
                            </td>

                            <td data-label="Rol asignado" class="{{ $celda }}">
                                <span class="inline-block border-2 border-black px-2 py-0.5 text-xs font-bold uppercase {{ $rolNombre ? 'bg-white' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $rolNombre ? ucfirst($rolNombre) : 'Sin rol' }}
                                </span>
                            </td>

                            <td data-label="Acciones" class="block px-3 py-2 md:table-cell">
                                @can('usuarios.gestionar')
                                    @if ($u->id === $yoId)
                                        <p class="text-xs font-bold uppercase text-gray-500 md:text-center">Tu cuenta</p>
                                    @else
                                        <div class="flex flex-col gap-2 sm:flex-row md:justify-center">
                                            <button type="button" @click="abrirPanel({{ $u->id }})"
                                                    class="{{ $boton }} bg-white text-black">
                                                Asignar acceso
                                            </button>

                                            <form method="POST" action="{{ route('usuarios.desactivar', $u) }}"
                                                    data-confirmar
                                                    data-titulo="¿Desactivar usuario?"
                                                    data-texto="{{ $u->name }} perderá su acceso al system. Podrás restaurarlo desde el historial de solicitudes."
                                                    data-boton="Sí, desactivar">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="{{ $boton }} w-full bg-white text-red-700 sm:w-auto">
                                                    Desactivar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-xs text-gray-500 md:text-center">—</p>
                                @endcan
                            </td>
                        </tr>
                    @endforeach

                    <tr x-show="visibles.length === 0" x-cloak class="block md:table-row">
                        <td colspan="3" class="block px-3 py-8 text-center font-bold uppercase text-gray-500 md:table-cell">
                            No se encontraron usuarios
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        {{-- ============ PANEL LATERAL: asignar acceso ============ --}}
        @can('usuarios.gestionar')
            <div x-show="panelAbierto" x-cloak x-transition.opacity
                    @click="cerrarPanel()" aria-hidden="true"
                    class="fixed inset-0 z-40 bg-black/60"></div>

            <form method="POST" :action="accion" @submit="enviando = true"
                    x-show="panelAbierto" x-cloak
                    role="dialog" aria-modal="true" aria-label="Asignar acceso"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="fixed inset-y-0 right-0 z-50 flex h-[100dvh] w-full flex-col border-l-2 border-black bg-white sm:max-w-md">
                @csrf
                @method('PUT')

                <div class="shrink-0 border-b-2 border-black bg-gray-100 px-4 pb-3 pt-[max(0.75rem,env(safe-area-inset-top))]">
                    <h3 class="text-sm font-bold uppercase leading-tight text-black">
                        Gestionar accesos<br>
                        <span class="text-blue-700" x-text="usuario ? usuario.nombre : ''"></span>
                    </h3>
                </div>

                <div class="flex-1 space-y-4 overflow-y-auto p-4">

                    {{-- Rol: uno solo por usuario --}}
                    <fieldset class="border-2 border-black bg-white p-3 shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                        <legend class="px-1 text-sm font-bold uppercase text-black">Rol</legend>
                        <div class="flex flex-col">
                            @foreach ($roles as $rol)
                                <label class="flex min-h-[44px] cursor-pointer items-center gap-3 text-sm font-bold text-black">
                                    <input type="radio" name="rol" value="{{ $rol }}" x-model="rol" required
                                            class="h-5 w-5 cursor-pointer border-2 border-black text-blue-600 focus:ring-0">
                                    {{ ucfirst($rol) }}
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    {{-- Permisos directos (excepciones) --}}
                    <fieldset class="border-2 border-black bg-white p-3 shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                        <legend class="px-1 text-sm font-bold uppercase text-black">
                            Permisos directos <span class="text-[10px] font-normal text-gray-500">(excepciones)</span>
                        </legend>

                        @forelse ($gruposPermisos as $grupo)
                            <div class="mt-2 first:mt-0">
                                <h5 class="mb-1 text-xs font-bold uppercase text-gray-700">{{ $grupo['titulo'] }}:</h5>
                                <div class="grid grid-cols-2 gap-x-2">
                                    @foreach ($grupo['permisos'] as $p)
                                        <label class="flex min-h-[44px] cursor-pointer items-center gap-2 text-sm font-bold text-black">
                                            <input type="checkbox" name="permisos[]" value="{{ $p['name'] }}"
                                                    :checked="heredado(@js($p['name'])) || permisos.includes(@js($p['name']))"
                                                    :disabled="heredado(@js($p['name']))"
                                                    @change="alternar(@js($p['name']))"
                                                    class="h-5 w-5 cursor-pointer rounded-none border-2 border-black text-blue-600 focus:ring-0 disabled:cursor-not-allowed disabled:opacity-60">
                                            <span>
                                                {{ $p['label'] }}
                                                <span x-show="heredado(@js($p['name']))" x-cloak class="block text-[10px] font-normal text-gray-500">por rol</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">
                                Aún no hay permisos creados. Ejecuta el seeder de roles y permisos.
                            </p>
                        @endforelse
                    </fieldset>
                </div>

                <div class="flex shrink-0 flex-col-reverse gap-2 border-t-2 border-black bg-gray-100 p-4 pb-[max(1rem,env(safe-area-inset-bottom))] sm:flex-row sm:justify-center sm:gap-3">
                    <button type="button" @click="cerrarPanel()" class="{{ $boton }} w-full bg-white text-black">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="enviando || !rol"
                            class="{{ $boton }} w-full bg-blue-600 text-white disabled:opacity-60">
                        Guardar
                    </button>
                </div>
            </form>
        @endcan
    </div>

    {{-- Confirmaciones con SweetAlert (los textos viajan en atributos data-*) --}}
    <script>
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (!(form instanceof HTMLFormElement) || !('confirmar' in form.dataset)) return;

            e.preventDefault();
            const enviar = () => form.submit();

            if (!window.Swal) {
                if (window.confirm(form.dataset.texto || '¿Continuar?')) enviar();
                return;
            }

            Swal.fire({
                title: form.dataset.titulo || '¿Estás seguro?',
                text: form.dataset.texto || '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: form.dataset.boton || 'Sí, continuar',
                cancelButtonText: 'Cancelar',
            }).then((r) => { if (r.isConfirmed) enviar(); });
        });
    </script>
</x-app-layout>