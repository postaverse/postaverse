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
</head>

<body>
    <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <div id="stars1" class="stars"></div>
        <div id="stars2" class="stars"></div>
        <div id="stars3" class="stars"></div>
        {{ $slot }}
    </div>

    @livewireScripts
    <script>
        function createStars(id, count) {
            for (let i = 0; i < count; i++) {
                let star = document.createElement('div');
                star.className = 'star';
                star.style.top = Math.random() * window.innerHeight + 'px';
                star.style.left = Math.random() * window.innerWidth + 'px';
                let size = Math.random() * 3; // Change this value to adjust the range of sizes
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                document.getElementById(id).appendChild(star);
            }
        }

        window.onload = function() {
            createStars('stars1', 400);
            createStars('stars2', 300);
            createStars('stars3', 200);
        };
    </script>
</body>

</html>