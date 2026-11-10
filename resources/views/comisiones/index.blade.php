<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div x-data="modalComisiones()" class="w-full relative">

                {{-- Encabezado --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">
                        Comisiones creadas
                    </h2>

                    <button type="button" @click="abrirCrear()"
                        class="bg-green-600 text-white font-bold border-2 border-black px-4 py-2 shadow-[4px_4px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                        + Crear nueva comisión
                    </button>
                </div>

                {{-- Contenedor de la tabla --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Comisión</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Ciclo</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Docentes Asignados</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                                {{ \Carbon\Carbon::parse($comision->ciclo_comision)->format('Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2 justify-center">
                                                @forelse ($comision->docentes as $docente)
                                                    @php $esLider = $comision->lider_id == $docente->id; @endphp
                                                    <span class="px-2 py-1 rounded-md text-xs border shadow-sm {{ $esLider ? 'bg-yellow-300 text-black border-black font-bold' : 'bg-indigo-50 text-indigo-700 border-indigo-100' }}">
                                                        {{ $docente->name_one }} {{ $docente->last_name_one }}
                                                        @if ($esLider) · Líder @endif
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-400 italic">Sin docentes asignados</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center items-center space-x-4">
                                                <button type="button" @click="abrirEditar({{ $comision->load('docentes')->toJson() }})"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3 font-medium cursor-pointer bg-transparent border-none">
                                                    Editar
                                                </button>

                                                <form action="{{ route('comisiones.destroy', $comision->id) }}" method="POST" class="form-eliminar" data-mensaje="¿Desea eliminar esta comisión permanentemente?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none cursor-pointer p-0 m-0 font-medium">
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

                <!-- ================= MODAL UNIFICADO ================= -->
                <div x-show="panelAbierto" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/70 sm:p-4 transition-opacity">
                    <form :action="urlAccion" method="POST" @submit="enviando = true"
                        class="border-2 border-black bg-white w-full max-w-4xl shadow-[8px_8px_0px_rgba(0,0,0,1)] flex flex-col max-h-[90dvh] sm:max-h-[90vh] animate-slide-up sm:animate-none">
                        @csrf
                        <input type="hidden" name="_method" :value="modo === 'editar' ? 'PUT' : 'POST'">
                        
                        <!-- Campos ocultos para evitar errores si están bloqueados visualmente -->
                        <input type="hidden" name="name_comision" :value="form.nombre">
                        <input type="hidden" name="ciclo_comision" :value="form.ciclo">
                        <input type="hidden" name="color_comision" :value="form.color">

                        <!-- HEADER MODAL -->
                        <div class="shrink-0 flex justify-between items-center border-b-2 border-black bg-gray-100 p-4 sm:px-6">
                            <h2 class="text-base sm:text-lg font-bold uppercase text-black line-clamp-1" x-text="modo === 'crear' ? 'CREAR NUEVA COMISIÓN' : 'EDITAR COMISIÓN: ' + form.nombre"></h2>
                            <button type="button" @click="panelAbierto = false" class="text-black font-bold text-xl leading-none hover:text-red-600 px-2">&times;</button>
                        </div>

                        <!-- CUERPO MODAL -->
                        <div class="flex-1 overflow-y-auto p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                            
                            <!-- DOCENTES -->
                            <div class="flex flex-col">
                                <label class="font-bold text-sm sm:text-base uppercase block mb-1">Docentes Asignados</label>
                                <p class="text-xs text-gray-600 mb-3">Marque o desmarque a los docentes que conformarán la comisión.</p>
                                
                                <div class="border-2 border-black h-48 md:h-72 overflow-y-auto p-2 sm:p-3 bg-gray-50 flex flex-col gap-1 sm:gap-2">
                                    @foreach($todosLosDocentes as $docente)
                                        <label class="flex items-center gap-3 cursor-pointer font-bold text-sm hover:bg-gray-200 p-2">
                                            <input type="checkbox" name="docentes[]" value="{{ $docente->id }}" 
                                                x-model="form.docentes" @change="verificarLider()"
                                                class="w-5 h-5 border-2 border-black cursor-pointer focus:ring-0 text-blue-600">
                                            <span class="line-clamp-1">{{ $docente->name_one }} {{ $docente->last_name_one }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- DATOS Y LÍDER -->
                            <div class="flex flex-col gap-4 sm:gap-5">
                                <div x-show="modo === 'editar'" class="border-2 border-yellow-500 bg-yellow-50 p-3">
                                    <label class="flex items-center gap-2 cursor-pointer text-[11px] sm:text-xs font-bold text-yellow-800 uppercase">
                                        <input type="checkbox" x-model="desbloquearInfo" class="w-4 h-4 text-yellow-600 border-black focus:ring-0 shrink-0">
                                        Editar nombre y color (solo si existen errores)
                                    </label>
                                </div>

                                <div>
                                    <label class="font-bold text-sm uppercase block mb-1">Nombre de la Comisión</label>
                                    <input type="text" x-model="form.nombre" required 
                                        :disabled="modo === 'editar' && !desbloquearInfo"
                                        class="border-2 border-black w-full p-3 sm:p-2 outline-none font-bold disabled:bg-gray-200 disabled:text-gray-500 text-sm sm:text-base transition-colors">
                                </div>

                                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                    <div>
                                        <label class="font-bold text-sm uppercase block mb-1">Ciclo (Año)</label>
                                        <!-- Vuelve a ser Number para que sea cómodo de usar y no aparezca en blanco -->
                                        <input type="number" x-model="form.ciclo" min="2020" max="2100" step="1" required 
                                            :disabled="modo === 'editar' && !desbloquearInfo"
                                            class="border-2 border-black w-full p-3 sm:p-2 outline-none disabled:bg-gray-200 disabled:text-gray-500 text-sm sm:text-base">
                                    </div>
                                    <div>
                                        <label class="font-bold text-sm uppercase block mb-1">Color</label>
                                        <input type="color" x-model="form.color" required 
                                            :disabled="modo === 'editar' && !desbloquearInfo"
                                            class="border-2 border-black w-full h-[46px] sm:h-[42px] p-1 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                    </div>
                                </div>

                                <div class="mt-auto border-t-2 border-black pt-4">
                                    <label class="font-bold text-sm uppercase block mb-2">Seleccione el líder</label>
                                    <select name="lider_id" x-model="form.lider" required 
                                            class="border-2 border-black w-full p-3 sm:p-2 outline-none cursor-pointer bg-white text-sm sm:text-base">
                                        <option value="" disabled selected>Elija un líder (requiere docentes)</option>
                                        @foreach($todosLosDocentes as $docente)
                                            <option value="{{ $docente->id }}" 
                                                    x-show="form.docentes.includes('{{ $docente->id }}') || form.docentes.includes({{ $docente->id }})">
                                                {{ $docente->name_one }} {{ $docente->last_name_one }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p x-show="form.docentes.length === 0" class="text-xs text-red-600 mt-1 font-bold">⚠️ Seleccione docentes primero.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER MODAL -->
                        <div class="shrink-0 border-t-2 border-black bg-gray-100 p-4 sm:px-6 flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-4 pb-[max(1rem,env(safe-area-inset-bottom))]">
                            <button type="button" @click="panelAbierto = false" 
                                    class="w-full sm:w-auto border-2 border-black bg-white px-4 py-3 sm:py-2 font-bold uppercase hover:bg-gray-200 transition-all shadow-[2px_2px_0px_rgba(0,0,0,1)] text-sm">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="enviando || form.docentes.length === 0 || !form.lider"
                                    class="w-full sm:w-auto border-2 border-black bg-blue-600 text-white px-6 py-3 sm:py-2 font-bold uppercase shadow-[2px_2px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_rgba(0,0,0,1)] disabled:opacity-60 transition-all text-sm">
                                <span x-text="modo === 'crear' ? 'Guardar' : 'Actualizar'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div> <!-- FIN ALPINE -->
        </div>
    </div>

    <!-- SCRIPT ALPINE -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalComisiones', () => ({
            panelAbierto: false,
            modo: 'crear',
            urlAccion: '',
            enviando: false,
            desbloquearInfo: false,
            
            form: {
                nombre: '',
                color: '#000000',
                ciclo: new Date().getFullYear(), // Inicia con el año actual
                docentes: [], 
                lider: ''     
            },

            abrirCrear() {
                this.modo = 'crear';
                this.urlAccion = "{{ route('comisiones.store') }}";
                this.desbloquearInfo = true; 
                
                let anioActual = new Date().getFullYear(); // Solo el año, ej: 2026
                
                this.form = { nombre: '', color: '#ff0000', ciclo: anioActual, docentes: [], lider: '' };
                this.enviando = false;
                this.panelAbierto = true;
            },

            abrirEditar(comision) {
                this.modo = 'editar';
                this.urlAccion = `/comisiones/${comision.id}`; 
                this.desbloquearInfo = false; 
                
                // Extraemos solo el AÑO de la base de datos para mostrarlo en el input
                let anioGuardado = new Date().getFullYear();
                if (comision.ciclo_comision) {
                    anioGuardado = comision.ciclo_comision.split('-')[0]; 
                }
                
                this.form = {
                    nombre: comision.name_comision,
                    color: comision.color_comision,
                    ciclo: anioGuardado,
                    docentes: comision.docentes ? comision.docentes.map(d => d.id.toString()) : [],
                    lider: comision.lider_id ? comision.lider_id.toString() : ''
                };
                
                this.enviando = false;
                this.panelAbierto = true;
            },

            verificarLider() {
                if (this.form.lider && !this.form.docentes.includes(this.form.lider.toString())) {
                    this.form.lider = '';
                }
            }
        }));
    });
    </script>

    <!-- SCRIPT DE CONFIRMACIÓN PARA ELIMINAR -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.form-eliminar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const mensaje = this.getAttribute('data-mensaje') || '¿Estás seguro?';
                    
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'warning',
                            title: '¡Atención!',
                            text: mensaje,
                            showCancelButton: true,
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar',
                            animation: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    } else {
                        if (confirm(mensaje)) this.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>