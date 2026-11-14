<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CEA - Calendario Escolar</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-black selection:bg-[#83C152] selection:text-black">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        
        <div class="w-full max-w-2xl bg-white border-4 border-black shadow-[12px_12px_0px_rgba(0,0,0,1)] p-8 md:p-12 text-center">
            
            <div class="flex justify-center mb-8">
                <x-application-logo class="w-32 h-32 md:w-40 md:h-40" />
            </div>

            <!-- Textos principales -->
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-4 border-b-4 border-black pb-4">
                Gestión de Calendario Escolar
            </h1>
            <p class="text-lg md:text-xl font-bold text-gray-700 mb-10">
                Sistema de Administración y Control Anual del CEA
            </p>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <!-- Si ya está logueado -->
                    <a href="{{ url('/dashboard') }}" 
                        class="inline-flex min-h-[50px] w-full sm:w-auto items-center justify-center border-2 border-black bg-[#83C152] px-8 py-3 text-base font-bold uppercase text-black shadow-[6px_6px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                        Ir al Calendario
                    </a>
                @else
                    <!-- Si no está logueado -->
                    <a href="{{ route('login') }}" 
                        class="inline-flex min-h-[50px] w-full sm:w-auto items-center justify-center border-2 border-black bg-[#83C152] px-8 py-3 text-base font-bold uppercase text-black shadow-[6px_6px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                        Iniciar Sesión
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" 
                            class="inline-flex min-h-[50px] w-full sm:w-auto items-center justify-center border-2 border-black bg-white px-8 py-3 text-base font-bold uppercase text-black shadow-[6px_6px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                            Registrarse
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        
        <div class="mt-12 text-center text-sm font-bold text-gray-600">
            © {{ date('Y') }} Centro de Estudios Avanzados Totonicapán Guatemala C.A | Todos los derechos reservados
        </div>
    </div>
</body>
</html>