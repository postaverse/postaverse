<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <title>Welcome to Postaverse</title>
    </head>
    <body class="min-h-screen text-[#1C1C1A] dark:text-white font-[family-name:var(--font-geist-sans)] bg-white dark:bg-zinc-900">
        <header class="grid grid-cols-2 gap-2 py-10 lg:grid-cols-3 items-center border-b">
            <div>
                <div class="flex lg:justify-center lg:col-start-2">
                    <a href="/" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Postaverse</a>
                </div>
            </div>
            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="max-w-6xl w-full mx-auto px-6 lg:px-8">
                {{-- Hero Section --}}
                <div class="text-center py-20 lg:py-32">
                    <h1 class="text-5xl lg:text-7xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">
                        Welcome to <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Postaverse</span>
                    </h1>
                    
                    <p class="text-xl lg:text-2xl text-zinc-600 dark:text-zinc-400 mb-8 max-w-4xl mx-auto leading-relaxed">
                        The next generation social media platform designed for meaningful connections, authentic conversations, and building vibrant communities around the world.
                    </p>
                    
                    <div class="flex flex-wrap justify-center gap-3 mb-12 text-sm">
                        <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">✨ Privacy First</span>
                        <span class="px-4 py-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">🛡️ Safe Community</span>
                        <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full">🚀 Modern Features</span>
                        <span class="px-4 py-2 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded-full">🌍 Global Network</span>
                    </div>
                    
                    @auth
                        <div class="space-y-4">
                            <p class="text-lg text-zinc-600 dark:text-zinc-400">Welcome back! Ready to see what's happening in your community?</p>
                            <a href="{{ route('dashboard') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 rounded-lg text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                                Go to Your Feed
                            </a>
                        </div>
                    @else
                        <div class="space-y-6">
                            <p class="text-lg text-zinc-600 dark:text-zinc-400">Join over 10,000+ users who are already part of our growing community.</p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 rounded-lg text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                                    Join Postaverse Free
                                </a>
                                <a href="{{ route('login') }}" class="inline-block px-8 py-4 border-2 border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-lg text-lg font-semibold transition-colors">
                                    Sign In
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
                
                {{-- Features Grid --}}
                <div class="py-20">
                    <h2 class="text-3xl lg:text-4xl font-bold text-center text-zinc-900 dark:text-zinc-100 mb-4">
                        Everything you need to connect
                    </h2>
                    <p class="text-lg text-center text-zinc-600 dark:text-zinc-400 mb-16 max-w-2xl mx-auto">
                        Discover powerful features designed to enhance your social media experience and help you build lasting relationships.
                    </p>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                        <div class="bg-white dark:bg-zinc-800 p-8 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mb-6 flex items-center justify-center">
                                <div class="w-8 h-8 bg-white rounded-full"></div>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Smart Connections</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Our intelligent algorithm helps you discover like-minded people based on your interests, location, and mutual connections. Build meaningful relationships that matter.
                            </p>
                        </div>
                        
                        <div class="bg-white dark:bg-zinc-800 p-8 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mb-6 flex items-center justify-center">
                                <div class="w-8 h-8 bg-white rounded-full"></div>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Rich Media Sharing</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Share photos, videos, stories, and thoughts with beautiful formatting options. Express yourself with polls, live streams, and interactive content.
                            </p>
                        </div>
                        
                        <div class="bg-white dark:bg-zinc-800 p-8 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl mb-6 flex items-center justify-center">
                                <div class="w-8 h-8 bg-white rounded-full"></div>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Community Groups</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Join or create groups around your passions. From hobbyist communities to professional networks, find your tribe and engage in meaningful discussions.
                            </p>
                        </div>
                        
                        <div class="bg-white dark:bg-zinc-800 p-8 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl mb-6 flex items-center justify-center">
                                <div class="w-8 h-8 bg-white rounded-full"></div>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Real-time Messaging</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Stay connected with instant messaging, voice notes, and video calls. Create group chats and share moments with your closest friends and family.
                            </p>
                        </div>
                        
                        <div class="bg-white dark:bg-zinc-800 p-8 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl mb-6 flex items-center justify-center">
                                <div class="w-8 h-8 bg-white rounded-full"></div>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Privacy Controls</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Take full control of your data and privacy. Granular settings let you decide who sees your content, how you're discovered, and what data is shared.
                            </p>
                        </div>
                        
                        <div class="bg-white dark:bg-zinc-800 p-8 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl mb-6 flex items-center justify-center">
                                <div class="w-8 h-8 bg-white rounded-full"></div>
                            </div>
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Content Discovery</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Explore trending topics, discover new creators, and stay updated with personalized feeds. Our algorithm learns your preferences to show you relevant content.
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Why Choose Section --}}
                <div class="py-20 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 rounded-3xl px-8 lg:px-16">
                    <div class="max-w-4xl mx-auto text-center">
                        <h2 class="text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-6">
                            Why Choose Postaverse?
                        </h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-12">
                            We're not just another social platform. We're building the future of digital communities with your needs at the center.
                        </p>
                        
                        <div class="grid md:grid-cols-2 gap-8 text-left">
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <div>
                                        <h4 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Privacy by Design</h4>
                                        <p class="text-zinc-600 dark:text-zinc-300">Your data belongs to you. We use end-to-end encryption and give you complete control over your information.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-green-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <div>
                                        <h4 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Professional Moderation</h4>
                                        <p class="text-zinc-600 dark:text-zinc-300">Our 5-tier admin system ensures a safe, respectful environment with 24/7 content moderation.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-purple-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <div>
                                        <h4 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Ad-Free Experience</h4>
                                        <p class="text-zinc-600 dark:text-zinc-300">No intrusive ads or sponsored content. Your feed shows what matters to you, not what advertisers want.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-orange-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <div>
                                        <h4 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Cross-Platform Sync</h4>
                                        <p class="text-zinc-600 dark:text-zinc-300">Seamlessly switch between devices with real-time synchronization across web, mobile, and desktop.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-red-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <div>
                                        <h4 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Open Source</h4>
                                        <p class="text-zinc-600 dark:text-zinc-300">Built transparently with community contributions. Audit our code and suggest improvements on GitHub.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-teal-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <div>
                                        <h4 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Global Community</h4>
                                        <p class="text-zinc-600 dark:text-zinc-300">Connect with users from 50+ countries and communicate with built-in translation features.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Statistics Section --}}
                <div class="py-20">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl lg:text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">
                            Join Our Growing Community
                        </h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">
                            See what makes Postaverse the fastest-growing social platform of 2025
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                        <div class="p-6">
                            <div class="text-4xl lg:text-5xl font-bold text-blue-600 mb-2">10K+</div>
                            <div class="text-zinc-600 dark:text-zinc-400 font-medium">Active Users</div>
                        </div>
                        
                        <div class="p-6">
                            <div class="text-4xl lg:text-5xl font-bold text-green-600 mb-2">500+</div>
                            <div class="text-zinc-600 dark:text-zinc-400 font-medium">Communities</div>
                        </div>
                        
                        <div class="p-6">
                            <div class="text-4xl lg:text-5xl font-bold text-purple-600 mb-2">1M+</div>
                            <div class="text-zinc-600 dark:text-zinc-400 font-medium">Posts Shared</div>
                        </div>
                        
                        <div class="p-6">
                            <div class="text-4xl lg:text-5xl font-bold text-orange-600 mb-2">50+</div>
                            <div class="text-zinc-600 dark:text-zinc-400 font-medium">Countries</div>
                        </div>
                    </div>
                </div>
                
                {{-- Final CTA --}}
                <div class="py-20 text-center">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-12 lg:p-16 text-white">
                        <h2 class="text-3xl lg:text-4xl font-bold mb-6">
                            Ready to Start Your Journey?
                        </h2>
                        <p class="text-xl mb-8 text-blue-100 max-w-2xl mx-auto">
                            Join thousands of users who have already discovered a better way to connect, share, and build communities online.
                        </p>
                        
                        @guest
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-blue-600 hover:bg-blue-50 rounded-lg text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                                    Get Started Free
                                </a>
                                <a href="{{ route('login') }}" class="inline-block px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-blue-600 rounded-lg text-lg font-semibold transition-all duration-200">
                                    Sign In
                                </a>
                            </div>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-block px-8 py-4 bg-white text-blue-600 hover:bg-blue-50 rounded-lg text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                                Continue to Your Feed
                            </a>
                        @endguest
                    </div>
                </div>
            </main>
        </div>
        @fluxScripts
    </body>
</html>
