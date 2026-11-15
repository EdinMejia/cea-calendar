<x-guest-layout>
    <div class="text-center">

        @if ((int) Auth::user()->status === 0) 
            <div class="mb-6 flex justify-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-black bg-yellow-400 shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    <svg class="h-10 w-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <h2 class="mb-2 text-3xl font-black uppercase text-black">Cuenta en Revisión</h2>
            
            <p class="mb-6 text-base font-medium text-gray-800 leading-relaxed">
                ¡Hola, <span class="text-lg font-black text-black">{{ mb_strtoupper(Auth::user()->name_one) }}</span>! Hemos recibido tu registro. 
                <br><br>
                Actualmente tu cuenta está en estado <span class="bg-yellow-300 px-2 py-1 border-2 border-black uppercase text-sm font-black text-black shadow-[2px_2px_0px_rgba(0,0,0,1)]">Pendiente</span>. 
                <br><br>
                Un administrador debe <span class="font-black text-black">aprobar tu acceso</span> y asignarte un rol antes de que puedas entrar al calendario.
            </p>

        @elseif ((int) Auth::user()->status === 2)
            <div class="mb-6 flex justify-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-black bg-[#FF3B30] shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    <svg class="h-10 w-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>

            <h2 class="mb-2 text-3xl font-black uppercase text-black">Acceso Denegado</h2>
            
            <p class="mb-6 text-base font-medium text-gray-800 leading-relaxed">
                <span class="text-lg font-black text-black">{{ mb_strtoupper(Auth::user()->name_one) }}</span>, por el momento no tienes acceso al sistema. 
                <br><br>
                Tu solicitud de cuenta ha sido <span class="bg-[#FF3B30] px-2 py-1 border-2 border-black uppercase text-sm font-black text-white shadow-[2px_2px_0px_rgba(0,0,0,1)]">Rechazada</span>. 
                <br><br>
                Si consideras que esto es un error, por favor <span class="font-black text-black">comunícate con la administración</span> del Centro de Estudios Avanzados.
            </p>

        @elseif ((int) Auth::user()->status === 3)
            <div class="mb-6 flex justify-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-black bg-orange-500 shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    <svg class="h-10 w-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <h2 class="mb-2 text-3xl font-black uppercase text-black">Cuenta Suspendida</h2>
            
            <p class="mb-6 text-base font-medium text-gray-800 leading-relaxed">
                <span class="text-lg font-black text-black">{{ mb_strtoupper(Auth::user()->name_one) }}</span>, tu usuario ha sido pausado. 
                <br><br>
                Tu perfil se encuentra temporalmente <span class="bg-orange-500 px-2 py-1 border-2 border-black uppercase text-sm font-black text-white shadow-[2px_2px_0px_rgba(0,0,0,1)]">Inactivo</span>. 
                <br><br>
                Para reactivar tus permisos y volver a ver el calendario, <span class="font-black text-black">contacta a un administrador</span>.
            </p>
        @endif

        <div class="border-t-2 border-black pt-6">
            <p class="mb-4 text-sm font-black text-black uppercase tracking-wider">
                @if ((int) Auth::user()->status === 0)
                    Vuelve a intentar más tarde por favor
                @else
                    Por favor, cierra tu sesión
                @endif
            </p>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex w-full min-h-[44px] items-center justify-center border-2 border-black bg-white px-6 py-2 text-base font-black uppercase text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-[2px] active:shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                    Salir
                </button>
            </form>
        </div>

    </div>
</x-guest-layout>