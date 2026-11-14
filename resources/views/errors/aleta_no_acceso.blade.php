<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sin acceso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white flex items-center justify-center p-6">
    <div class="w-full max-w-md border-2 border-black bg-white shadow-[8px_8px_0px_rgba(0,0,0,1)]">
        <div class="bg-gray-100 border-b-2 border-black px-6 py-4">
            <h1 class="text-xl font-bold text-black uppercase">Acceso denegado</h1>
        </div>
        <div class="p-8 text-center">
            <p class="text-lg font-bold text-black">No tienes acceso a esta acción.</p>
            <p class="mt-2 text-sm text-gray-600">Si crees que es un error, contacta a un administrador.</p>

            @auth
                @can('actividades.ver')
                    <a href="{{ route('dashboard') }}"
                        class="mt-6 inline-block bg-green-600 text-white font-bold border-2 border-black px-6 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                        Regresar al inicio
                    </a>
                @else
                    <form method="POST" action="{{ route('logout') }}" class="mt-6">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 text-white font-bold border-2 border-black px-6 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                            Cerrar sesión
                        </button>
                    </form>
                @endcan
            @else
                <a href="{{ route('login') }}"
                    class="mt-6 inline-block bg-blue-600 text-white font-bold border-2 border-black px-6 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    Iniciar sesión
                </a>
            @endauth
        </div>
    </div>
</body>
</html>