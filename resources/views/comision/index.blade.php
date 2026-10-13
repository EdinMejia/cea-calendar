<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-2xl font-semibold text-gray-800">
                Comisiones creadas
            </h2>
            <a href="{{ route('comisiones.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md">
                + Crear nueva comisión
            </a>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-32">
        </div>
    </div>
</x-app-layout>