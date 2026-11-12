<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nombres -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Primer Nombre -->
            <div>
                <label for="name_one" class="block font-bold text-xs text-black uppercase">Primer Nombre</label>
                <input id="name_one" type="text" name="name_one" :value="old('name_one')" required autofocus autocomplete="given-name"
                        class="mt-1 block w-full border-2 border-black p-2 text-sm outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                        placeholder="Ej. Alejandro" />
                <x-input-error :messages="$errors->get('name_one')" class="mt-2 text-red-600 font-bold text-xs" />
            </div>
            
            <!-- Segundo Nombre -->
            <div>
                <label for="name_two" class="block font-bold text-xs text-black uppercase">Segundo Nombre</label>
                <input id="name_two" type="text" name="name_two" :value="old('name_two')" required autofocus autocomplete="additional-name"
                        class="mt-1 block w-full border-2 border-black p-2 text-sm outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                        placeholder="Ej. Andrés" />
                <x-input-error :messages="$errors->get('name_two')" class="mt-2 text-red-600 font-bold text-xs" />
            </div>
        </div>

        <!-- Apellidos -->
        <div class="grid grid-cols-2 gap-4 mt-4">
            <!-- Primer Apellido -->
            <div>
                <label for="last_name_one" class="block font-bold text-xs text-black uppercase">Primer Apellido</label>
                <input id="last_name_one" type="text" name="last_name_one" :value="old('last_name_one')" required autofocus autocomplete="family-name"
                        class="mt-1 block w-full border-2 border-black p-2 text-sm outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                        placeholder="Ej. García" />
                <x-input-error :messages="$errors->get('last_name_one')" class="mt-2 text-red-600 font-bold text-xs" />
            </div>
            
            <!-- Segundo Apellido -->
            <div>
                <label for="last_name_two" class="block font-bold text-xs text-black uppercase">Segundo Apellido</label>
                <input id="last_name_two" type="text" name="last_name_two" :value="old('last_name_two')" required autofocus autocomplete="off"
                        class="mt-1 block w-full border-2 border-black p-2 text-sm outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                        placeholder="Ej. López" />
                <x-input-error :messages="$errors->get('last_name_two')" class="mt-2 text-red-600 font-bold text-xs" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block font-bold text-xs text-black uppercase">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                    class="mt-1 block w-full border-2 border-black p-2 text-sm outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                    placeholder="ej. alejandro12@gmail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 font-bold text-xs" />
        </div>

        <!-- Contraseñas (Ojitos) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4" x-data="{ verP1: false, verP2: false }">
            
            <!-- Password -->
            <div>
                <label for="password" class="block font-bold text-xs text-black uppercase">Contraseña</label>
                <div class="relative mt-1">
                    <input id="password" :type="verP1 ? 'text' : 'password'" name="password" required autocomplete="new-password"
                            class="block w-full border-2 border-black p-2 pr-10 text-sm outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                            placeholder="CEA-2027#" />
                    <button type="button" @click="verP1 = !verP1" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 hover:text-black">
                        <svg x-show="!verP1" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        <svg x-show="verP1" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 font-bold text-xs" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-bold text-xs text-black uppercase">Confirmar</label>
                <div class="relative mt-1">
                    <input id="password_confirmation" :type="verP2 ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                            class="block w-full border-2 border-black p-2 pr-10 text-sm outline-none focus:ring-0 shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                            placeholder="CEA-2027#" />
                    <button type="button" @click="verP2 = !verP2" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 hover:text-black">
                        <svg x-show="!verP2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        <svg x-show="verP2" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 font-bold text-xs" />
            </div>
        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t-2 border-black">
            <a class="text-sm font-bold text-blue-700 hover:text-blue-900 underline uppercase" href="{{ route('login') }}">
                Ya tengo una cuenta
            </a>

            <button type="submit" class="inline-flex min-h-[44px] items-center justify-center border-2 border-black bg-[#83C152] px-6 py-2 text-sm font-bold uppercase text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all hover:translate-y-[2px] hover:shadow-[2px_2px_0px_rgba(0,0,0,1)]">
                REGISTRARME
            </button>
        </div>
    </form>
</x-guest-layout>