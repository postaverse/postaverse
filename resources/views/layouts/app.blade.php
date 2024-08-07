<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="postaverse-web-verification" content="13c9152f1f696624cda4b6f9e5b03702">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="bg-gray-800 shadow header">
        <x-banner />
    </div>

    <div class="min-h-screen">
        <div class="bg-gray-900">
            @livewire('navigation-menu')
        </div>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-gray-800 shadow header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts
    <div id="stars" class="stars"></div>
    <div id="confetti-container"></div>
    @vite(['resources/js/stars.js'])
</body>

</html>
