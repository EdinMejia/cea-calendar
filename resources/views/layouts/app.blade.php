<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - CEA</title>

        <style>[x-cloak] { display: none !important; }</style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="bg-white font-sans text-black antialiased">
        
        <!-- 1. MANTENIDO: Tu x-data, solo le añadimos que detecte si es PC o Celular al cargar para abrirse o cerrarse solo -->
        <div x-data="{ sidebarAbierto: window.innerWidth >= 768 }" @resize.window="sidebarAbierto = window.innerWidth >= 768" class="flex h-screen w-full border-2 border-black overflow-hidden relative">
            
            <!-- NUEVO: Fondo oscuro que aparece en celulares al abrir el menú. Si lo tocas, se cierra. -->
            <div x-show="sidebarAbierto" x-cloak x-transition.opacity.duration.300ms @click="sidebarAbierto = false" class="fixed inset-0 bg-black/50 z-20 md:hidden"></div>

            <!-- 2. MANTENIDO Y MEJORADO: El ancho cambia, pero en celular se oculta a la izquierda (-translate-x-full) y en PC se queda en w-20 -->
            <aside :class="sidebarAbierto ? 'w-64 translate-x-0' : '-translate-x-full md:translate-x-0 md:w-20'" class="fixed md:relative inset-y-0 left-0 h-full w-64 -translate-x-full md:translate-x-0 flex flex-col border-r-2 border-black z-30 transition-all duration-300 ease-in-out flex-shrink-0" style="background-color: #83C152;">
                
                <div class="p-4 flex items-center justify-between border-b border-black/30">
                    <h2 x-show="sidebarAbierto" class="text-center text-white font-bold uppercase leading-tight tracking-wide whitespace-nowrap overflow-hidden transition-opacity duration-300">
                        Centro de Estudios <br>Avanzados de <br>TOTONICAPÁN
                    </h2>
                    
                    <!-- 3. MANTENIDO: Tu botón para cerrar/abrir -->
                    <button @click="sidebarAbierto = !sidebarAbierto" class="text-black hover:text-gray-800 focus:outline-none transition-transform" :class="!sidebarAbierto ? 'mx-auto' : ''">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <nav class="flex-1 py-6 space-y-3 overflow-x-hidden overflow-y-auto">
                    <!-- Opción: Inicio -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-2 transition-all hover:bg-black/10" :class="!sidebarAbierto ? 'justify-center px-0' : ''">
                        <svg class="w-6 h-6 flex-shrink-0 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span x-show="sidebarAbierto" x-cloak x-transition.opacity.duration.200ms class="font-bold text-white text-sm whitespace-nowrap">Inicio</span>
                    </a>

                    <!-- Opción: Docentes y comisión -->
                    <a href="{{ route('comisiones.index') }}" class="flex items-center gap-3 px-6 py-2 transition-all hover:bg-black/10" :class="!sidebarAbierto ? 'justify-center px-0' : ''">
                        <svg class="w-6 h-6 flex-shrink-0 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="sidebarAbierto" x-cloak x-transition.opacity.duration.200ms class="font-bold text-white text-sm whitespace-nowrap">Docentes y comision</span>
                    </a>

                    <!-- Opción: Solicitudes acceso -->
                    <a href="#" class="flex items-center gap-3 px-6 py-2 transition-all hover:bg-black/10" :class="!sidebarAbierto ? 'justify-center px-0' : ''">
                        <svg class="w-6 h-6 flex-shrink-0 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarAbierto" x-cloak x-transition.opacity.duration.200ms class="font-bold text-white text-sm whitespace-nowrap">Solicitudes acceso</span>
                    </a>
                </nav>

                <div class="p-4 flex flex-col items-center border-t border-black/30 overflow-hidden" :class="!sidebarAbierto ? 'px-2' : ''">
    
                    <p x-show="sidebarAbierto" class="text-white text-sm font-bold mb-1 whitespace-nowrap">
                        {{ Auth::user()->name_one . ' ' . Auth::user()->last_name_one ?: 'USUARIO CEA' }}
                    </p>
    
                    <p x-show="sidebarAbierto" class="text-white text-xs mb-3 truncate w-full text-center">
                        {{ Auth::user()->email ?? 'docente@cea.com' }}
                    </p>
    
                    <form method="POST" action="{{ route('logout') }}" class="w-full flex justify-center">
                        @csrf
                        <button type="submit" :class="sidebarAbierto ? 'px-4 w-full' : 'px-0 justify-center w-10 h-10'" class="py-1.5 text-xs font-bold text-white bg-blue-900 border border-black shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] transition-all flex items-center justify-center overflow-hidden flex-shrink-0">
                            <!-- Texto si está abierto -->
                            <span x-show="sidebarAbierto" x-cloak x-transition.opacity.duration.200ms class="whitespace-nowrap">Cerrar sesión</span>
                            <!-- Ícono de apagado si está cerrado -->
                            <svg x-show="!sidebarAbierto" x-cloak x-transition.opacity.duration.200ms class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ÁREA DE CONTENIDO PRINCIPAL: Agregamos min-w-0 para evitar que el contenido rompa el diseño (flex blow-out) -->
            <div class="flex-1 min-w-0 flex flex-col overflow-hidden relative bg-white">
                
                <header class="border-b-2 border-black z-10 w-full flex items-center justify-between px-4 md:px-6" style="background-color: #83C152;">
                    
                    <!-- NUEVO: Botón hamburguesa solo para móviles (se oculta en PC) -->
                    <button @click="sidebarAbierto = !sidebarAbierto" class="md:hidden text-black focus:outline-none">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <div class="py-3 text-center flex-1">
                        <h1 class="text-sm md:text-lg font-bold text-black truncate">
                            Calendario Escolar CEA Totonicapán
                        </h1>
                    </div>
                    
                    <!-- Div vacío para mantener centrado el título en el celular -->
                    <div class="w-8 md:hidden"></div>
                </header>

                <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 pb-[max(1rem,env(safe-area-inset-bottom))] sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>