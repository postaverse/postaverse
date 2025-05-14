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

    <!-- Google Adsense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7453651531634667"
        crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css'])

    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <div class="font-sans text-gray-100 antialiased">
        <!-- Guest Navigation Bar -->
        @guest
            <x-guest-nav />
        @endguest

        {{ $slot }}
    </div>

    @livewireScripts
    <div id="stars" class="stars"></div>
    @vite(['resources/js/app.js'])
</body>

</html>
