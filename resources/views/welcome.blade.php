<x-guest-layout>
    <div class="py-12">
        <header class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            @if (!auth()->check())
                <div class="bg-opacity-10 backdrop-blur-sm border border-white/20 overflow-hidden shadow-sm sm:rounded-lg p-6 fade-in">
                    <!-- Modified header for better mobile responsiveness -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h1 class="text-3xl font-bold text-white">Postaverse</h1>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-4">
                        <a href="/blog" class="text-blue-400 hover:text-blue-300 font-medium">Blog</a>
                        <a href="/home" class="text-blue-400 hover:text-blue-300 font-medium">Browse</a>
                    </div>
                </div>
            @else
                <div class="bg-opacity-10 backdrop-blur-sm border border-white/20 overflow-hidden shadow-sm sm:rounded-lg p-6 fade-in">
                    <!-- Modified header for authenticated users -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h1 class="text-3xl font-bold text-white">Postaverse</h1>
                        <a href="{{ route('home') }}" class="btn inline-flex justify-center min-w-[100px]">Home</a>
                    </div>
                </div>
            @endif
        </header>
        
        <main class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-opacity-10 backdrop-blur-sm border border-white/20 overflow-hidden shadow-sm sm:rounded-lg p-8 fade-in" style="animation-delay: 0.2s">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-3/5 pr-0 md:pr-8">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 bg-clip-text text-transparent bg-linear-to-r from-blue-400 to-indigo-500">
                            Welcome to Postaverse!
                        </h1>
                        <p class="text-lg text-gray-200 mb-6 leading-relaxed">
                            Postaverse is a social media platform designed for sharing thoughts and connecting with others.
                            Unlike other platforms, we do not use algorithms, ensuring a chronological feed based solely on the
                            people you follow.
                        </p>
                        
                        <p class="text-lg text-gray-300 mb-6">
                            Founded by
                            <a href="https://zanderlewis.dev" class="hyperlink">Zander Lewis</a>
                        </p>
                        
                        @if (Route::has('register') && !auth()->check())
                            <div class="mt-8 mb-10">
                                <a href="{{ route('register') }}" class="btn inline-flex items-center">
                                    <span>Join Postaverse Today</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <div class="md:w-2/5 mt-8 md:mt-0">
                        <div class="bg-opacity-10 backdrop-blur-sm border border-white/20 p-6 h-full rounded-lg shadow-sm hover:border-white/30 transition-colors duration-200">
                            <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Features
                            </h2>
                            <ul class="space-y-3 text-lg">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-400 shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-200">Chronological Feed: Enjoy a timeline that shows posts in the order they were made.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-400 shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-200">Privacy Focused: Your data is yours. We prioritize user privacy and data security.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-400 shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-200">Community Driven: Engage with a community that values genuine interactions and connections.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-400 shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-200">Zero Algorithm: No algorithms means no manipulation. See content from the people you follow, in order.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-700/30">
                    <h2 class="text-2xl font-bold text-white mb-4">Our Story</h2>
                    <p class="text-lg text-gray-300 mb-6">
                        In mid-December 2023, Zander completed CS50x and learned how to create websites using Python.
                        Postaverse was launched on January 4, 2024, built entirely from scratch. Due to hosting issues, the
                        platform was temporarily taken down in mid-February. On August 7, 2024, Postaverse was relaunched in
                        its development phase as version 2, utilizing PHP and the Laravel framework. The development of version 2 started on May 25, 2024, and onn April 28, 2025, development
                        for version 3 began, introducing a new design, features, and a more user-friendly experience.
                    </p>
                    
                    <h2 class="text-2xl font-bold text-white mb-4 mt-8">Our Mission</h2>
                    <p class="text-lg text-gray-300 mb-6">
                        At Postaverse, our mission is to create a transparent and user-centric social media experience. We
                        believe in empowering users by providing a platform free from algorithmic manipulation, where
                        content is displayed in a natural, chronological order.
                    </p>
                </div>
            </div>
        </main>
    </div>
</x-guest-layout>
