<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CEA Calendario') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-2 sm:pt-0 pb-20 px-4">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-32 sm:h-32 fill-current text-gray-500 transition-all" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 sm:mt-6 px-4 sm:px-6 py-6 bg-white border-4 border-black shadow-[6px_6px_0px_rgba(0,0,0,1)] sm:shadow-[8px_8px_0px_rgba(0,0,0,1)] overflow-hidden">
                {{ $slot }}
            </div>
        </div>
</body>
</html>
