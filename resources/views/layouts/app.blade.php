<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <wireui:scripts />

    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen">
            {{-- @livewire('navigation-menu') --}}
            @livewire('navbar')
            @livewire('sidebar')
            <!-- Page Content -->
            <main>
                <div class="p-4 sm:ml-64">
                    <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
                        {{ $slot }}
                    </div>
                 </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
