<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen">
            @livewire('navigation-menu')

            <div class="grid sm:grid-cols-5 grid-cols-3 min-h-screen">
                <!-- Sidebar -->
                @livewire('sidebar')
               

                <!-- Page Content -->
                <div class="sm:col-span-4 col-span-2 flex flex-col text-center">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
                    <main class="min-h-screen">
                        {{ $slot }}
                    </main>
                    <footer class="w-full flex items-center justify-center self-end mb-5 mt-5">&#169; 2021</footer>
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
