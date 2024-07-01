<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <livewire:create-post />
        <livewire:all-posts />
    </div>
    <div>
        <div id="stars1" class="stars"></div>
        <div id="stars2" class="stars"></div>
        <div id="stars3" class="stars"></div>
    </div>
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
                star.style.transform = `rotate(${Math.random() * 360}deg)`;
                document.getElementById(id).appendChild(star);
            }
        }

        window.onload = function() {
            createStars('stars1', 400);
            createStars('stars2', 300);
            createStars('stars3', 200);
        };
    </script>
</x-app-layout>
