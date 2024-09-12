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
            <!--
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7453651531634667"
                crossorigin="anonymous"></script>
             Horizontal 1 -->
            <!--
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-7453651531634667"
                data-ad-slot="6397338332" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            -->
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
                <div class="text-gray-400 text-sm">
                    <h2 class="text-lg font-bold text-gray-300">Contributors</h2>
                </div>
                <ul class="text-gray-400 text-sm space-y-2 text-center">
                    <li>
                        <a href="/@3" class="hover:text-gray-300">Gorilla - Bananas</a>
                    </li>
                    <li>
                        <a href="/@2" class="hover:text-gray-300">Triston - Logos and Icons</a>
                    </li>
                    <li>
                        <a href="/@9" class="hover:text-gray-300">Liv - Mascot Creation</a>
                    </li>
                    <li>
                        <a href="/@14" class="hover:text-gray-300">Calvare - Outro Creation</a>
                    </li>
                    <li>
                        <a href="/@1" class="hover:text-gray-300">Zander Lewis - Development</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    @livewireScripts
    <div id="stars" class="stars"></div>
    <div id="confetti-container"></div>
    @vite(['resources/js/stars.js'])
</body>

</html>
