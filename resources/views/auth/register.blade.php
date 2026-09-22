<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name_one -->
        <div>
            <x-input-label for="name_one" :value="__('Primer Nombre')" />
            <x-text-input id="name_one" class="block mt-1 w-full" type="text" name="name_one" :value="old('name_one')" required autofocus autocomplete="name_one" placeholder="Ej. Alejandro"/>
            <x-input-error :messages="$errors->get('name_one')" class="mt-2" />
        </div>

        <!-- Name_two -->
        <div class ="mt-3">
            <x-input-label for="name_two" :value="__('Segundo Nombre')" />
            <x-text-input id="name_two" class="block mt-1 w-full" type="text" name="name_two" :value="old('name_two')" required autofocus autocomplete="name_two" placeholder="Ej. Andrés" />
            <x-input-error :messages="$errors->get('name_two')" class="mt-2" />
        </div>

        <!-- last_name_one -->
        <div class ="mt-3">
            <x-input-label for="last_name_one" :value="__('Primer Apellido')" />
            <x-text-input id="last_name_one" class="block mt-1 w-full" type="text" name="last_name_one" :value="old('last_name_one')" required autofocus autocomplete="last_name_one" placeholder="Ej. García"/>
            <x-input-error :messages="$errors->get('last_name_one')" class="mt-2" />
        </div>

        <!-- last_name_two -->
        <div class ="mt-3">
            <x-input-label for="last_name_two" :value="__('Segundo Apellido')" />
            <x-text-input id="last_name_two" class="block mt-1 w-full" type="text" name="last_name_two" :value="old('last_name_two')" required autofocus autocomplete="last_name_two" placeholder="Ej. López" />
            <x-input-error :messages="$errors->get('last_name_two')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ej. alejandro12@gmail.com"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-3">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" 
                            placeholder="CEA-2027#"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="CEA-2027#" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Ya tengo una cuenta') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarme') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
