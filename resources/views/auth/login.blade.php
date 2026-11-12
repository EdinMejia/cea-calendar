<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold text-sm text-black uppercase">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    class="mt-1 block w-full border-2 border-black p-3 outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                    placeholder="ej. alejandro12@gmail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 font-bold text-sm" />
        </div>

        <!-- Password  -->
        <div class="mt-4" x-data="{ verPass: false }">
            <label for="password" class="block font-bold text-sm text-black uppercase">Contraseña</label>
            
            <div class="relative mt-1">
                <input id="password" :type="verPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                        class="block w-full border-2 border-black p-3 pr-12 outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                        placeholder="CEA-2027#" />
                
                <button type="button" @click="verPass = !verPass" tabindex="-1"
                        class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-600 hover:text-black focus:outline-none">
                    
                    <svg x-show="!verPass" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                    </svg>

                    <svg x-show="verPass" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 font-bold text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="border-2 border-black text-blue-600 focus:ring-0 w-5 h-5 cursor-pointer shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                <span class="ms-2 text-sm font-bold text-gray-700">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Botones inferiores -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t-2 border-black">
            <a class="text-sm font-bold text-blue-700 hover:text-blue-900 underline uppercase" href="{{ route('register') }}">
                Crear una cuenta nueva
            </a>

            <button type="submit" class="inline-flex min-h-[44px] items-center justify-center border-2 border-black bg-[#83C152] px-6 py-2 text-sm font-bold uppercase text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                INGRESAR
            </button>
        </div>
    </form>
</x-guest-layout>