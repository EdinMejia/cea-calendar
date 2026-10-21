<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Cea Calendario - Plataforma de Gestión</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="antialiased bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center">
    
    <div class="max-w-2xl w-full text-center px-6">
        
        <!-- Título principal -->
        <h1 class="text-5xl font-bold mb-4 tracking-tight">Gestión de Calendario Escolar </h1>
        <p class="text-gray-400 mb-12 text-lg">Sistema de administración y control Anual.</p>

        <!-- Botones de Acceso -->
        @if (Route::has('login'))
            <div class="flex justify-center gap-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg shadow-lg transition-transform transform hover:-translate-y-1">
                        Ir al Panel Principal
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg shadow-lg transition-transform transform hover:-translate-y-1">
                        Iniciar Sesión
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-transparent border-2 border-gray-600 hover:border-gray-300 text-gray-300 hover:text-white font-bold rounded-lg transition-transform transform hover:-translate-y-1">
                            Registrarse
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>

</body>
</html>