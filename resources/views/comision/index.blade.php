<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Comisiones creadas
                </h2>
                <a href="{{ route('comisiones.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">
                    + Crear nueva comisión
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                            <p class="font-bold">¡Éxito!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="font-bold text-sm">{{ session('warning') }}</p>
                            </div>
                        </div>
                    @endif
                                

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Comisión
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Ciclo
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Docentes Asignados
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            
                            @forelse ($comisiones as $comision)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 rounded-full mr-3 border border-gray-300 shadow-sm" 
                                                @style(['background-color: ' . ($comision->color_comision ?? '#4B5563')])>
                                            </div>
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ $comision->name_comision ?? 'Sin Nombre' }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $comision->ciclo_comision ?? 'N/A' }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($comision->docentes && $comision->docentes->count() > 0)
                                            <ul class="list-disc list-inside text-sm text-gray-600">
                                                @foreach($comision->docentes as $docente)
                                                    <li>{{ $docente->name_one }} {{ $docente->last_name_one }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-xs text-gray-400 italic bg-gray-100 px-2 py-1 rounded">Sin docentes</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        <a href="#" class="text-red-600 hover:text-red-900">Eliminar</a>
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