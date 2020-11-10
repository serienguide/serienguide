<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <style>
            [x-cloak] { display: none; }
        </style>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
    </head>
    <body class="font-sans antialiased">
        <x-navigation />
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-lg leading-6 font-semibold text-gray-900">
                        {{ $header }}
                    </h1>
                </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto pb-6 sm:px-6 lg:px-8">
                <div class="px-4 py-4 sm:px-0">
                    {{ $slot }}
                </div>
            </div>
        </main>
        <footer class="bg-gray-800">
            <div class="max-w-screen-xl mx-auto py-12 px-4 overflow-hidden space-y-8 sm:px-6 lg:px-8">
                <nav class="-mx-5 -my-2 flex flex-wrap justify-center w-100">
                    <div class="px-5 py-2">
                        <a href="#" class="text-base leading-6 text-gray-300 hover:text-white">
                            About
                        </a>
                    </div>
                    <div class="px-5 py-2">
                        <a href="#" class="text-base leading-6 text-gray-300 hover:text-white">
                            API
                        </a>
                    </div>
                    <div class="px-5 py-2">
                        <a href="https://github.com/serienguide/serienguide" target="_blank" class="text-base leading-6 text-gray-300 hover:text-white">
                            Github
                        </a>
                    </div>
                </nav>
                <p class="mt-8 text-center text-base leading-6 text-gray-400">
                    &copy; 2020 serienguide. All rights reserved.
                </p>
            </div>
        </footer>


        @stack('modals')

        @livewireScripts
    </body>
</html>
