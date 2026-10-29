<x-app-layout>
    <div class="py-12">

            @if (session('error'))
                <div class="bg-red-600 text-white font-bold p-4 mb-6 border-2 border-black shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-600 text-white font-bold p-4 mb-6 border-2 border-black shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    {{ session('success') }}
                </div>
            @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Encabezado --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Comisiones creadas
                </h2>

                <a href="{{ route('comisiones.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">
                    + Crear nueva comisión
                </a>
            </div>

            {{-- Contenedor principal --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">

                    {{-- Mensaje de éxito --}}
                    @if (session('success'))
                        <div x-data="{ show: true }"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                            x-transition.duration.500ms
                            class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm"
                            role="alert">
                            <p class="font-bold">¡Éxito!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Mensaje de advertencia --}}
                    @if (session('warning'))
                        <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm"
                            role="alert">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="font-bold text-sm">{{ session('warning') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Tabla de comisiones --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Comisión
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Ciclo
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Docentes Asignados
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($comisiones as $comision)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full border border-gray-300 shadow-sm mr-3" @style(['background-color: ' . ($comision->color_comision ?: '#3B82F6')])></div>
                                            <div class="text-sm font-bold text-gray-800">{{ $comision->name_comision }}</div>
                                        </div>
                                    </td>

                                    {{-- Ciclo --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                            {{ $comision->ciclo_comision }}
                                        </span>
                                    </td>

                                    {{-- Docentes asignados --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2 justify-center">
                                            @forelse ($comision->docentes as $docente)
                                                <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded-md text-xs border border-indigo-100 shadow-sm">
                                                    {{ $docente->name_one }} {{ $docente->last_name_one }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">Sin docentes asignados</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center items-center space-x-4">
                                            <a href="{{ route('comisiones.edit', $comision->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Editar
                                            </a>

                                            <form action="{{ route('comisiones.destroy', $comision->id) }}"
                                                    method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta comisión?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 bg-transparent border-none cursor-pointer p-0 m-0 font-medium">
                                                    Eliminar Comision
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        <span class="block text-lg font-medium">No hay comisiones creadas</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>