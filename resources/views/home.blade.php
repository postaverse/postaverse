<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12 main">
        <livewire:create-post />
        <livewire:all-posts />
    </div>
    <div>
        <div id="stars1" class="stars"></div>
        <div id="stars2" class="stars"></div>
        <div id="stars3" class="stars"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            createStars('stars1', 400);
            createStars('stars2', 300);
            createStars('stars3', 200);

            const stars = document.querySelectorAll('.star');

            stars.forEach(star => {
                // Random duration between 0.5s and 2.5s
                const duration = Math.random() * 2 + 0.5;
                // Random delay up to 5s
                const delay = Math.random() * 5;

                star.style.animationDuration = `${duration}s`;
                star.style.animationDelay = `-${delay}s`;
            });
        });
    </script>
</x-app-layout>