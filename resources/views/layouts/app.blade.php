<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="postaverse-web-verification" content="13c9152f1f696624cda4b6f9e5b03702">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Google Adsense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7453651531634667"
        crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="bg-gray-800/10 backdrop-blur-sm border-b border-white/20 shadow header">
        <x-banner />
    </div>

    <div class="min-h-screen">
        <div class="bg-gray-900">
            @livewire('navigation-menu')
        </div>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-gray-800/10 backdrop-blur-sm border-b border-white/20 shadow header" style="position: relative !important; z-index: 9999 !important;">
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

    <footer class="bg-gray-800/10 backdrop-blur-sm border-t border-white/20">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div class="flex flex-col space-y-3">
                    <h3 class="text-lg font-semibold text-gray-200">{{ config('app.name') }}</h3>
                    <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    <div class="flex space-x-4 mt-2">
                        <!-- Github -->
                        <a href="https://github.com/postaverse/postaverse">
                            <svg class="h-5 w-5" fill="white" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="flex flex-col space-y-3">
                    <h3 class="text-lg font-semibold text-gray-200">Navigation</h3>
                    <a href="/" class="text-sm text-gray-400 hover:text-white">Home</a>
                    <a href="/welcome" class="text-sm text-gray-400 hover:text-white">About</a>
                    <a href="/privacy-policy" class="text-sm text-gray-400 hover:text-white">Privacy Policy</a>
                </div>
                
                <!-- Contributors -->
                <div class="flex flex-col space-y-3">
                    <h3 class="text-lg font-semibold text-gray-200">Contributors</h3>
                    <a href="/@1" class="text-sm text-gray-400 hover:text-white transition">Zander Lewis - Development</a>
                    <a href="/@2" class="text-sm text-gray-400 hover:text-white transition">Liv - Mascot Creation</a>
                    <a href="/@3" class="text-sm text-gray-400 hover:text-white transition">Calvare - Outro Creation</a>
                </div>
            </div>
        </div>
    </footer>    

    @livewireScripts
    <div id="stars" class="stars"></div>
    <div id="confetti-container"></div>
    @vite(['resources/js/app.js'])
</body>

</html>
