<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - CEA</title>

        <script src="https://cdn.tailwindcss.com"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased text-black bg-white overflow-hidden">
        
        <!-- 1. CAMBIO: Agregamos x-data para controlar el estado del menú -->
        <div x-data="{ sidebarAbierto: true }" class="flex h-screen w-full border-2 border-black">
            
            <!-- 2. CAMBIO: El ancho (w-64 o w-20) depende de si está abierto o cerrado -->
            <aside :class="sidebarAbierto ? 'w-64' : 'w-20'" class="flex flex-col border-r-2 border-black z-20 transition-all duration-300 flex-shrink-0" style="background-color: #83C152;"> 
                
                <div class="p-4 flex items-center justify-between border-b border-black/30">
                    <h2 x-show="sidebarAbierto" class="text-center text-white font-bold uppercase leading-tight tracking-wide whitespace-nowrap overflow-hidden transition-opacity duration-300">
                        Centro de Estudios <br>Avanzados de <br>TOTONICAPÁN
                    </h2>
                    
                    <!-- 3. CAMBIO: Convertimos el SVG en un botón funcional con @click -->
                    <button @click="sidebarAbierto = !sidebarAbierto" class="text-black hover:text-gray-800 focus:outline-none transition-transform" :class="!sidebarAbierto ? 'mx-auto' : ''">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <nav class="flex-1 py-6 space-y-3 overflow-x-hidden overflow-y-auto">
                    <!-- Opción: Inicio -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-2 transition-all hover:bg-black/10" :class="!sidebarAbierto ? 'justify-center px-0' : ''">
                        <svg class="w-6 h-6 flex-shrink-0 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <!-- Ocultar texto si está cerrado -->
                        <span x-show="sidebarAbierto" class="font-bold text-white text-sm whitespace-nowrap">Inicio</span>
                    </a>

                    <!-- Opción: Docentes y comisión -->
                    <a href="{{ route('comisiones.index') }}" class="flex items-center gap-3 px-6 py-2 transition-all hover:bg-black/10" :class="!sidebarAbierto ? 'justify-center px-0' : ''">
                        <svg class="w-6 h-6 flex-shrink-0 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="sidebarAbierto" class="font-bold text-white text-sm whitespace-nowrap">Docentes y comision</span>
                    </a>

                    <!-- Opción: Solicitudes acceso -->
                    <a href="#" class="flex items-center gap-3 px-6 py-2 transition-all hover:bg-black/10" :class="!sidebarAbierto ? 'justify-center px-0' : ''">
                        <svg class="w-6 h-6 flex-shrink-0 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarAbierto" class="font-bold text-white text-sm whitespace-nowrap">Solicitudes acceso</span>
                    </a>
                </nav>

                <div class="p-4 flex flex-col items-center border-t border-black/30 overflow-hidden" :class="!sidebarAbierto ? 'px-2' : ''">
    
                        <!-- Trae el nombre real de la base de datos. Si está vacío o falla, dice DOCENTE CEA -->
                    <p x-show="sidebarAbierto" class="text-white text-sm font-bold mb-1 whitespace-nowrap">
                        {{ Auth::user()->name_one . ' ' . Auth::user()->last_name_one ?: 'USUARIO CEA' }}
                    </p>
    
                        <!-- Trae el correo real de la base de datos -->
                    <p x-show="sidebarAbierto" class="text-white text-xs mb-3 truncate w-full text-center">
                        {{ Auth::user()->email ?? 'docente@cea.com' }}
                    </p>
    
                    <form method="POST" action="{{ route('logout') }}" class="w-full flex justify-center">
                        @csrf
                        <button type="submit" :class="sidebarAbierto ? 'px-4 w-full' : 'px-0 justify-center w-10 h-10'" class="py-1.5 text-xs font-bold text-white bg-blue-900 border border-black shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all flex items-center justify-center overflow-hidden">
                            <!-- Texto si está abierto -->
                            <span x-show="sidebarAbierto" class="whitespace-nowrap">Cerrar sesión</span>
                            <!-- Ícono de apagado si está cerrado -->
                            <svg x-show="!sidebarAbierto" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ÁREA DE CONTENIDO PRINCIPAL -->
            <div class="flex-1 flex flex-col overflow-hidden relative bg-white">
                
                <header class="border-b-2 border-black z-10 w-full" style="background-color: #83C152;">
                    <div class="py-3 px-6 text-center">
                        <h1 class="text-lg font-aril text-black">
                            Calendario Escolar CEA Totonicapán
                        </h1>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>