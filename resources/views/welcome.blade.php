<x-guest-layout>
    <div class="py-12">
        <header class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            @if (!auth()->check())
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-t-lg rounded-b-lg p-6">
                    <h1 class="text-2xl font-bold text-white">
                        <a href="{{ route('login') }}" class="text-blue-500 underline">Login</a>
                        @if (Route::has('register'))
                            or
                            <a href="{{ route('register') }}" class="text-blue-500 underline">Register</a> to get started!
                        @endif
                    </h1>
                    <p class="text-lg text-gray-300 mt-4 space-x-4">
                        <a href="/blog" class="text-blue-400 underline hover:text-blue-300">Blog</a>
                        <a href="/home" class="text-blue-400 underline hover:text-blue-300">Browse</a>
                    </p>
                </div>
            @else
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-t-lg rounded-b-lg p-6">
                    <h1 class="text-2xl font-bold text-white">
                        <a href="{{ route('home') }}" class="text-blue-500 underline">Home</a>
                    </h1>
                </div>
            @endif
        </header>
        <main class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-3xl font-extrabold text-white mb-6">
                    Welcome to Postaverse!
                </h1>
                <p class="text-lg text-gray-300 mb-6">
                    Postaverse is a social media platform designed for sharing thoughts and connecting with others.
                    Unlike other platforms, we do not use algorithms, ensuring a chronological feed based solely on the
                    people you follow.
                </p>
                <p class="text-lg text-gray-300 mb-6">
                    Postaverse was founded by
                    <a href="https://zanderlewis.dev" class="text-blue-400 underline hover:text-blue-300">Zander
                        Lewis.</a>
                <h2 class="text-2xl font-bold text-white mb-4">
                    Our Story:
                </h2>
                <p class="text-lg text-gray-300 mb-6">
                    In mid-December 2023, Zander completed CS50x and learned how to create websites using Python.
                    Postaverse was launched on January 4, 2024, built entirely from scratch. Due to hosting issues, the
                    platform was temporarily taken down in mid-February. On August 7, 2024, Postaverse was relaunched in
                    its development phase as version 2, utilizing PHP and the Laravel framework. On April 28, 2025, development
                    for version 3 began, introducing a new design, features, and a more user-friendly experience.
                </p>
                <h2 class="text-2xl font-bold text-white mb-4">
                    Our Mission:
                </h2>
                <p class="text-lg text-gray-300 mb-6">
                    At Postaverse, our mission is to create a transparent and user-centric social media experience. We
                    believe in empowering users by providing a platform free from algorithmic manipulation, where
                    content is displayed in a natural, chronological order.
                </p>
                <h2 class="text-2xl font-bold text-white mb-4">
                    Features:
                </h2>
                <ul class="list-disc list-inside text-lg text-green-500 mb-6">
                    <li>Chronological Feed: Enjoy a timeline that shows posts in the order they were made.</li>
                    <li>Privacy Focused: Your data is yours. We prioritize user privacy and data security.</li>
                    <li>Community Driven: Engage with a community that values genuine interactions and connections.</li>
                    <li>Zero Algorithm: No algorithms means no manipulation. See content from the people you follow, in
                        order.</li>
                </ul>
                <h2 class="text-2xl font-bold text-white mb-4">
                    Join Us:
                </h2>
                @if (Route::has('register'))
                    <p class="text-lg text-gray-300">
                        Become a part of the Postaverse community today. <a href="{{ route('register') }}"
                            class="text-blue-400 underline hover:text-blue-300">Register now</a> and start sharing your
                        thoughts with the world.
                    </p>
                @endif
            </div>
        </main>
    </div>
</x-guest-layout>
