<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Solicitudes de acceso</h2>
    </x-slot>

    @php
        $boton = 'inline-flex min-h-[44px] items-center justify-center border-2 border-black px-4 py-2 text-center text-sm font-bold uppercase shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-[2px] active:shadow-[2px_2px_0px_rgba(0,0,0,1)]';
        $campo = 'border-2 border-black bg-white px-3 py-2 text-base font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0 sm:text-sm';
        $celdaFinal = 'block px-3 py-1 before:block before:text-xs before:font-bold before:uppercase before:text-gray-600 before:content-[attr(data-label)] md:table-cell md:py-3 md:before:hidden';
        $celda = $celdaFinal.' md:border-r-2 md:border-black';
        $th = 'border-r-2 border-black p-3 font-bold uppercase';
        $seccion = 'mb-8 border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]';
        $cabecera = 'flex items-center justify-between gap-3 border-b-2 border-black px-4 py-3';
        $contador = 'border-2 border-black bg-white px-2 py-0.5 text-sm font-bold text-black';
        $fila = 'block border-b-2 border-black p-2 text-black last:border-b-0 hover:bg-gray-50 md:table-row md:p-0';
        $vacio = 'block px-3 py-8 text-center font-bold uppercase text-gray-500 md:table-cell';

        // acción de la bitácora => [texto, color]
        $acciones = [
            'APROBADO'      => ['Aprobado',      'bg-green-300'],
            'RESTAURADO'    => ['Restaurado',    'bg-green-300'],
            'RECHAZADO'     => ['Rechazado',     'bg-red-300'],
            'DESACTIVADO'   => ['Desactivado',   'bg-red-300'],
            'ELIMINADO'     => ['Eliminado',     'bg-red-300'],
            'ASIGNAR ROL'   => ['Nuevo Rol',     'bg-yellow-300'],
            'ACTUALIZADO'   => ['Actualizado',   'bg-yellow-300'],
        ];
    @endphp

    <div class="w-full">

        <div class="mb-4">
            <a href="{{ route('usuarios.index') }}" class="{{ $boton }} bg-gray-200 text-xs text-black">&larr; Volver a gestión de accesos</a>
        </div>

        <form method="GET" action="{{ route('usuarios.solicitudes') }}" class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center">
            <input type="search" name="buscar" value="{{ $buscar }}" autocomplete="off" aria-label="Buscar solicitudes"
                placeholder="Buscar por nombre, apellido o correo..." class="{{ $campo }} w-full sm:flex-1">
            <div class="flex gap-2">
                <button type="submit" class="{{ $boton }} flex-1 bg-blue-600 text-white sm:flex-none">Buscar</button>
                <a href="{{ route('usuarios.solicitudes') }}" class="{{ $boton }} flex-1 bg-white text-black sm:flex-none">Limpiar</a>
            </div>
        </form>

        {{-- ============ PENDIENTES ============ --}}
        <section class="{{ $seccion }}" aria-labelledby="t-pendientes">
            <div class="{{ $cabecera }} bg-[#83C152]">
                <h3 id="t-pendientes" class="text-lg font-bold uppercase text-black">Solicitudes pendientes</h3>
                <span class="{{ $contador }}">{{ $solicitudes->total() }}</span>
            </div>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="hidden border-b-2 border-black bg-gray-200 md:table-header-group">
                    <tr>
                        <th class="{{ $th }}">Nombre completo</th>
                        <th class="{{ $th }}">Correo</th>
                        <th class="p-3 text-center font-bold uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group">
                    @forelse ($solicitudes as $s)
                        <tr class="{{ $fila }}">
                            <td data-label="Nombre completo" class="{{ $celda }} font-bold">{{ $s->name }}</td>
                            <td data-label="Correo" class="{{ $celda }} break-all">
                                <a href="mailto:{{ $s->email }}" class="text-blue-700 underline">{{ $s->email }}</a>
                            </td>
                            <td data-label="Acciones" class="{{ $celdaFinal }}">
                                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-center">

                                    <form method="POST" action="{{ route('usuarios.solicitudes.aprobar', $s) }}"
                                            x-data="{ rol: '', enviando: false }" @submit="enviando = true"
                                            class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        @csrf
                                        <select name="rol" x-model="rol" required aria-label="Aceptar a {{ $s->name }} como"
                                                class="{{ $campo }} w-full cursor-pointer sm:w-44">
                                            <option value="" disabled selected>Aceptar como...</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" x-show="rol !== ''" x-cloak :disabled="enviando"
                                                class="{{ $boton }} bg-green-700 text-white disabled:opacity-60">Aprobar</button>
                                    </form>

                                    <form method="POST" action="{{ route('usuarios.solicitudes.rechazar', $s) }}" data-confirmar
                                            data-titulo="¿Rechazar solicitud?"
                                            data-texto="Denegarás el acceso a {{ $s->name }}. Podrás restaurarlo desde el historial."
                                            data-boton="Sí, rechazar">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $boton }} w-full bg-white text-red-700 lg:w-auto">Rechazar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="block md:table-row">
                            <td colspan="3" class="{{ $vacio }}">
                                {{ $buscar !== '' ? 'Ninguna solicitud coincide con «'.$buscar.'»' : 'No hay solicitudes pendientes' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($solicitudes->hasPages())
                <div class="border-t-2 border-black bg-gray-100 p-2">{{ $solicitudes->links() }}</div>
            @endif
        </section>

        {{-- ============ RECHAZADOS ============ --}}
        <section class="{{ $seccion }}" aria-labelledby="t-historial">
            <div class="{{ $cabecera }} bg-gray-200">
                <h3 id="t-historial" class="text-lg font-bold uppercase text-black">Usuarios rechazados</h3>
                <span class="{{ $contador }}">{{ $historial->total() }}</span>
            </div>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="hidden border-b-2 border-black bg-gray-100 md:table-header-group">
                    <tr>
                        <th class="{{ $th }}">Nombre completo</th>
                        <th class="{{ $th }}">Correo</th>
                        <th class="p-3 text-center font-bold uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group">
                    @forelse ($historial as $u)
                        <tr class="{{ $fila }}">
                            <td data-label="Nombre completo" class="{{ $celda }} font-bold">{{ $u->name }}</td>
                            <td data-label="Correo" class="{{ $celda }} break-all">
                                <a href="mailto:{{ $u->email }}" class="text-blue-700 underline">{{ $u->email }}</a>
                            </td>
                            <td data-label="Acciones" class="{{ $celdaFinal }}">
                                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-center">

                                    <form method="POST" action="{{ route('usuarios.solicitudes.aprobar', $u) }}"
                                            x-data="{ rol: '', enviando: false }" @submit="enviando = true"
                                            class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        @csrf
                                        <select name="rol" x-model="rol" required aria-label="Restaurar a {{ $u->name }} como"
                                                class="{{ $campo }} w-full cursor-pointer sm:w-44">
                                            <option value="" disabled selected>Restaurar como...</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" x-show="rol !== ''" x-cloak :disabled="enviando"
                                                class="{{ $boton }} bg-blue-600 text-white disabled:opacity-60">Restaurar</button>
                                    </form>

                                    <form method="POST" action="{{ route('usuarios.solicitudes.eliminar', $u) }}" data-confirmar
                                            data-titulo="¿Eliminar definitivamente?"
                                            data-texto="Se borrará la cuenta de {{ $u->name }} del sistema. Esta acción no se puede deshacer."
                                            data-boton="Sí, eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="{{ $boton }} w-full bg-red-600 text-white lg:w-auto">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="block md:table-row">
                            <td colspan="3" class="{{ $vacio }}">
                                {{ $buscar !== '' ? 'Ningún rechazado coincide con «'.$buscar.'»' : 'No hay usuarios rechazados' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($historial->hasPages())
                <div class="border-t-2 border-black bg-gray-100 p-2">{{ $historial->links() }}</div>
            @endif
        </section>

        {{-- ============ BITÁCORA ============ --}}
        <section class="{{ $seccion }} mb-0" aria-labelledby="t-bitacora">
            <div class="{{ $cabecera }} bg-gray-200">
                <h3 id="t-bitacora" class="text-lg font-bold uppercase text-black">Bitácora de accesos</h3>
                <span class="{{ $contador }}">{{ $bitacora->total() }}</span>
            </div>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="hidden border-b-2 border-black bg-gray-100 md:table-header-group">
                    <tr>
                        <th class="{{ $th }}">Fecha y hora</th>
                        <th class="{{ $th }}">Usuario</th>
                        <th class="{{ $th }}">Acción</th>
                        <th class="{{ $th }}">Detalle</th>
                        <th class="p-3 font-bold uppercase">Realizado por</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group">
                    @forelse ($bitacora as $b)
                        @php [$texto, $color] = $acciones[$b->accion] ?? [$b->accion, 'bg-white']; @endphp
                        <tr class="{{ $fila }}">
                            <td data-label="Fecha y hora" class="{{ $celda }} whitespace-nowrap">{{ $b->created_at?->format('d/m/Y h:i A') }}</td>
                            <td data-label="Usuario" class="{{ $celda }}">
                                <span class="font-bold">{{ $b->usuario }}</span>
                                <span class="block break-all text-xs text-gray-600">{{ $b->correo }}</span>
                            </td>
                            <td data-label="Acción" class="{{ $celda }}">
                                <span class="inline-block border-2 border-black px-2 py-0.5 text-xs font-bold uppercase {{ $color }}">{{ $texto }}</span>
                            </td>
                            <td data-label="Detalle" class="{{ $celda }} break-words">{{ $b->detalle ?: '—' }}</td>
                            <td data-label="Realizado por" class="{{ $celdaFinal }}">{{ $b->realizado_por }}</td>
                        </tr>
                    @empty
                        <tr class="block md:table-row">
                            <td colspan="5" class="{{ $vacio }}">Aún no hay movimientos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($bitacora->hasPages())
                <div class="border-t-2 border-black bg-gray-100 p-2">{{ $bitacora->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>