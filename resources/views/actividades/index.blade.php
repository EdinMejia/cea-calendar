<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8">
                
                <h1 class="text-3xl font-bold text-center mb-10 text-black uppercase">
                    ACTIVIDADES PROGRAMADAS
                </h1>

                <!-- CONTENEDOR GRID: Acomoda las tarjetas (1 columna en móviles, 2 en tablets, 3 en pantallas grandes) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <!-- ======================================================== -->
                    <!-- INICIO DE TARJETA ESTILO BOCETO -->
                    <!-- ======================================================== -->
                    <!-- NOTA: Para hacerlo dinámico, envuelve esta tarjeta en un @foreach($actividades as $actividad) -->
                    
                    <div class="flex flex-col gap-2 w-full max-w-sm mx-auto">
                        
                        <!-- 1. BLOQUE DEL TÍTULO -->
                        <!-- Aquí puedes usar el color de la comisión: style="background-color: {{ $actividad->comision->color_comision }};" -->
                        <div class="border-2 border-black p-3 text-center" style="background-color: #FCD34D;">
                            <h3 class="text-black font-bold text-lg leading-tight uppercase">
                                Nombre:<br>Tarde deportiva
                            </h3>
                        </div>

                        <!-- 2. BLOQUE DEL CONTENIDO -->
                        <div class="border-2 border-black p-5 flex flex-col gap-5" style="background-color: #FCD34D;">

                            <!-- Dato: Fecha y Hora de Inicio -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <!-- Ícono Calendario -->
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="font-bold text-black text-sm">Fecha y Hora de Inicio:</span>
                                </div>
                                <!-- Caja blanca interior -->
                                <div class="bg-white border-2 border-black p-1 text-sm text-center font-medium">
                                    12-11-2026 14:30 PM
                                </div>
                            </div>

                            <!-- Dato: Hora Finalización -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <!-- Ícono Reloj -->
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-bold text-black text-sm">Hora Finalización:</span>
                                </div>
                                <div class="bg-white border-2 border-black p-1 text-sm text-center font-medium">
                                    17:00 PM
                                </div>
                            </div>

                            <!-- Dato: Lugar -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <!-- Ícono Ubicación (Pin) -->
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="font-bold text-black text-sm">Lugar de la Actividad:</span>
                                </div>
                                <div class="bg-white border-2 border-black p-2 text-sm font-medium">
                                    Salón de usos múltiples de Totonicapán a un costado del parque San Miguel
                                </div>
                            </div>

                            <!-- Dato: Encargado / Comisión -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <!-- Ícono Usuario -->
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="font-bold text-black text-sm">Encargado - comisión:</span>
                                </div>
                                <div class="bg-white border-2 border-black p-2 text-sm font-medium">
                                    comisión encargada DEPORTIVA formada por Lic. Luna, Ing. Pedro, Seño Caren
                                </div>
                            </div>

                            <!-- Botón "Más..." -->
                            <div class="flex justify-end mt-2">
                                <a href="#" class="bg-white border-2 border-black px-4 py-1 text-sm font-bold text-black hover:bg-gray-200 transition-colors shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)]">
                                    Más....
                                </a>
                            </div>

                        </div>
                    </div>
                    <!-- ======================================================== -->
                    <!-- FIN DE LA TARJETA -->
                    <!-- ======================================================== -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>