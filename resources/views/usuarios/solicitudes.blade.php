<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Solicitudes de acceso</h2>
    </x-slot>

    @php
        $boton = 'inline-flex min-h-[44px] items-center justify-center border-2 border-black px-4 py-2 text-center text-sm font-bold uppercase shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-[2px] active:shadow-[2px_2px_0px_rgba(0,0,0,1)]';
        $campo = 'border-2 border-black bg-white px-3 py-2 text-base font-bold text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] outline-none focus:ring-0 sm:text-sm';
        $celda = 'block px-3 py-1 before:block before:text-xs before:font-bold before:uppercase before:text-gray-600 before:content-[attr(data-label)] md:table-cell md:border-r-2 md:border-black md:py-3 md:before:hidden';
    @endphp

    <div class="w-full">

        <div class="mb-4">
            <a href="{{ route('usuarios.index') }}"
                class="inline-flex min-h-[44px] items-center border-2 border-black bg-gray-200 px-3 py-2 text-xs font-bold uppercase text-black shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                &larr; Volver a Gestión de Accesos
            </a>
        </div>

        {{-- Buscador (consulta toda la base de datos, no solo la página visible) --}}
        <form method="GET" action="{{ route('usuarios.solicitudes') }}"
                class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center">
            <input type="search" name="buscar" value="{{ $buscar }}" autocomplete="off"
                    aria-label="Buscar solicitudes" placeholder="Buscar por nombre, apellido o correo..."
                    class="{{ $campo }} w-full sm:flex-1">

            <div class="flex gap-2">
                <button type="submit" class="{{ $boton }} flex-1 bg-blue-600 text-white sm:flex-none">Buscar</button>
                <a href="{{ route('usuarios.solicitudes') }}" class="{{ $boton }} flex-1 bg-white text-black sm:flex-none">Limpiar filtro</a>
            </div>
        </form>

        {{-- ================= SOLICITUDES PENDIENTES ================= --}}
        <section class="mb-8 border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]" aria-labelledby="titulo-pendientes">
            <div class="flex items-center justify-between gap-3 border-b-2 border-black bg-[#83C152] px-4 py-3">
                <h3 id="titulo-pendientes" class="text-lg font-bold uppercase text-black">Solicitudes pendientes</h3>
                <span class="border-2 border-black bg-white px-2 py-0.5 text-sm font-bold text-black">{{ $solicitudes->total() }}</span>
            </div>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="hidden border-b-2 border-black bg-gray-200 md:table-header-group">
                    <tr>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Primer nombre</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Segundo nombre</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Primer apellido</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Segundo apellido</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Correo</th>
                        <th class="p-3 text-center font-bold uppercase">Acciones</th>
                    </tr>
                </thead>

                <tbody class="block md:table-row-group">
                    @forelse ($solicitudes as $s)
                        <tr class="block border-b-2 border-black p-2 text-black last:border-b-0 hover:bg-gray-50 md:table-row md:p-0">
                            <td data-label="Primer nombre"    class="{{ $celda }} font-bold">{{ $s->name_one }}</td>
                            <td data-label="Segundo nombre"   class="{{ $celda }}">{{ $s->name_two ?: '—' }}</td>
                            <td data-label="Primer apellido"  class="{{ $celda }} font-bold">{{ $s->last_name_one }}</td>
                            <td data-label="Segundo apellido" class="{{ $celda }}">{{ $s->last_name_two ?: '—' }}</td>
                            <td data-label="Correo" class="{{ $celda }} break-all">
                                <a href="mailto:{{ $s->email }}" class="text-blue-700 underline">{{ $s->email }}</a>
                            </td>

                            <td data-label="Acciones" class="block px-3 py-2 md:table-cell md:w-80">
                                <div class="flex flex-col gap-3 md:items-center">

                                    {{-- Aprobar: el botón aparece al elegir un rol --}}
                                    <form method="POST" action="{{ route('usuarios.solicitudes.aprobar', $s) }}"
                                            x-data="{ rol: '', enviando: false }" @submit="enviando = true"
                                            class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                                        @csrf
                                        <select name="rol" x-model="rol" required
                                                aria-label="Aceptar a {{ $s->name }} como"
                                                class="{{ $campo }} w-full cursor-pointer sm:w-40">
                                            <option value="" disabled selected>Aceptar como...</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol }}">{{ $rol }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" x-show="rol !== ''" x-cloak :disabled="enviando"
                                                class="{{ $boton }} bg-green-700 text-white disabled:opacity-60">
                                            Aprobar
                                        </button>
                                    </form>

                                    {{-- Rechazar: pide confirmación (ver script al final) --}}
                                    <form method="POST" action="{{ route('usuarios.solicitudes.rechazar', $s) }}"
                                            data-confirmar
                                            data-titulo="¿Rechazar solicitud?"
                                            data-texto="Denegarás el acceso a {{ $s->name }}. Podrás restaurarla desde el historial."
                                            data-boton="Sí, rechazar">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $boton }} w-full bg-white text-red-700 md:w-auto">
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="block md:table-row">
                            <td colspan="6" class="block px-3 py-8 text-center font-bold uppercase text-gray-500 md:table-cell">
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

        {{-- ================= HISTORIAL DE RECHAZADOS ================= --}}
        <section class="border-2 border-black bg-white shadow-[4px_4px_0px_rgba(0,0,0,1)]" aria-labelledby="titulo-historial">
            <div class="flex items-center justify-between gap-3 border-b-2 border-black bg-gray-200 px-4 py-3">
                <h3 id="titulo-historial" class="text-lg font-bold uppercase text-black">Historial de usuarios rechazados</h3>
                <span class="border-2 border-black bg-white px-2 py-0.5 text-sm font-bold text-black">{{ $historial->total() }}</span>
            </div>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="hidden border-b-2 border-black bg-gray-100 md:table-header-group">
                    <tr>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Nombre completo</th>
                        <th class="border-r-2 border-black p-3 font-bold uppercase">Correo</th>
                        <th class="border-r-2 border-black p-3 text-center font-bold uppercase">Fecha de rechazo</th>
                        <th class="p-3 text-center font-bold uppercase">Acciones</th>
                    </tr>
                </thead>

                <tbody class="block md:table-row-group">
                    @forelse ($historial as $u)
                        <tr class="block border-b-2 border-black p-2 text-black last:border-b-0 hover:bg-gray-50 md:table-row md:p-0">
                            <td data-label="Nombre completo" class="{{ $celda }} font-bold">{{ $u->name }}</td>
                            <td data-label="Correo" class="{{ $celda }} break-all">
                                <a href="mailto:{{ $u->email }}" class="text-blue-700 underline">{{ $u->email }}</a>
                            </td>
                            <td data-label="Fecha de rechazo" class="{{ $celda }} md:text-center">
                                {{ $u->updated_at?->format('d/m/Y h:i A') }}
                            </td>

                            <td data-label="Acciones" class="block px-3 py-2 md:table-cell md:w-96">
                                <div class="flex flex-col gap-3 md:items-center">

                                    {{-- Restaurar --}}
                                    <form method="POST" action="{{ route('usuarios.solicitudes.aprobar', $u) }}"
                                            x-data="{ rol: '', enviando: false }" @submit="enviando = true"
                                            class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                                        @csrf
                                        <select name="rol" x-model="rol" required
                                                aria-label="Restaurar a {{ $u->name }} como"
                                                class="{{ $campo }} w-full cursor-pointer sm:w-40">
                                            <option value="" disabled selected>Restaurar como...</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol }}">{{ $rol }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" x-show="rol !== ''" x-cloak :disabled="enviando"
                                                class="{{ $boton }} bg-blue-600 text-white disabled:opacity-60">
                                            Restaurar
                                        </button>
                                    </form>

                                    {{-- Eliminar definitivamente --}}
                                    <form method="POST" action="{{ route('usuarios.solicitudes.eliminar', $u) }}"
                                            data-confirmar
                                            data-titulo="¿Eliminar definitivamente?"
                                            data-texto="Se borrará la cuenta de {{ $u->name }} de los registros. Esta acción no se puede deshacer."
                                            data-boton="Sí, eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="{{ $boton }} w-full bg-red-600 text-white md:w-auto">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="block md:table-row">
                            <td colspan="4" class="block px-3 py-8 text-center font-bold uppercase text-gray-500 md:table-cell">
                                {{ $buscar !== '' ? 'Ningún rechazado coincide con «'.$buscar.'»' : 'El historial está vacío' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($historial->hasPages())
                <div class="border-t-2 border-black bg-gray-100 p-2">{{ $historial->links() }}</div>
            @endif
        </section>
    </div>

    {{-- Un solo script para todas las confirmaciones. Los textos viajan en atributos data-*,
        por eso un nombre con comillas o apóstrofes no rompe nada --}}
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