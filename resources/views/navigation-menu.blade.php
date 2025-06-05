<div>
    @if (auth()->user())
        <nav x-data="{ open: false }" class="bg-gray-800/10 backdrop-blur-sm border-b border-white/20 dropdown">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mmain">
                <div class="flex justify-between h-17">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('images/icons/logo/logo.png') }}" class="block w-auto h-12"
                                    alt="Logo">
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <!-- Home Link -->
                            <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span>{{ __('Home') }}</span>
                            </x-nav-link>
                            <!-- Feed Link -->
                            <x-nav-link href="{{ route('feed') }}" :active="request()->routeIs('feed')"
                                class="flex flex-col items-center relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                                <span>{{ __('Feed') }}</span>
                            </x-nav-link>

                            <!-- Notifications Link -->
                            <x-nav-link href="{{ route('notifications') }}" :active="request()->routeIs('notifications')"
                                class="flex flex-col items-center relative">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-gray-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-0 right-0 flex h-3 w-3">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                        </span>
                                    @endif
                                </div>
                                <span>{{ __('Notifications') }}</span>
                            </x-nav-link>

                            <!-- Blogs Link -->
                            <x-nav-link href="{{ route('blogs') }}" :active="request()->routeIs('blogs')"
                                class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <span>{{ __('Blog') }}</span>
                            </x-nav-link>
                            <!-- Search Link -->
                            <x-nav-link href="{{ route('search') }}" :active="request()->routeIs('search')"
                                class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span>{{ __('Search') }}</span>
                            </x-nav-link>
                            <!-- Admin Link -->
                            @if (auth()->user()->admin_rank >= 1)
                                <x-nav-link href="{{ route('admin') }}" :active="request()->routeIs('admin')"
                                    class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-gray-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ __('Admin') }}</span>
                                </x-nav-link>
                            @endif
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <button
                                            class="flex items-center text-sm border-2 border-white/20 rounded-full focus:outline-none focus:border-white/30 transition bg-gray-800/10 backdrop-blur-sm p-1.75">
                                            <h3 class="text-white pr-4 font-bold text-sm" style="margin: 0;">
                                                {{ Auth::user()->name ?: Auth::user()->handle }}</h3>
                                            <img class="h-8 w-8 rounded-full object-cover"
                                                src="{{ Auth::user()->profile_photo_url }}"
                                                alt="{{ Auth::user()->name ?: Auth::user()->handle }}" />
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-800/10 backdrop-blur-sm hover:text-gray-300 hover:border-white/30 focus:outline-none focus:bg-gray-800/20 active:bg-gray-800/20 transition ease-in-out duration-150 p-1.75">
                                                {{ Auth::user()->name ?: Auth::user()->handle }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                </x-slot>

                                <x-slot name="content">
                                    <!-- Settings -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Settings') }}
                                    </div>
                                    <x-dropdown-link href="{{ url('/@' . Auth::user()->id) }}">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('settings.show') }}">
                                        {{ __('Account') }}
                                    </x-dropdown-link>

                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                            {{ __('API Tokens') }}
                                        </x-dropdown-link>
                                    @endif

                                    <div class="border-t border-gray-600"></div>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf

                                        <x-dropdown-link href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-400 border border-white/10 bg-gray-800/10 backdrop-blur-sm hover:border-white/20 focus:outline-none focus:bg-gray-800/20 focus:text-gray-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <!-- Home Link -->
                    <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')"
                        class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>{{ __('Home') }}</span>
                    </x-responsive-nav-link>
                    <!-- Feed Link -->
                    <x-responsive-nav-link href="{{ route('feed') }}" :active="request()->routeIs('feed')"
                        class="flex items-center space-x-2 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                        <span>{{ __('Feed') }}</span>
                    </x-responsive-nav-link>

                    <!-- Notifications Link -->
                    <x-responsive-nav-link href="{{ route('notifications') }}" :active="request()->routeIs('notifications')"
                        class="flex items-center space-x-2 relative">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                </span>
                            @endif
                        </div>
                        <span>{{ __('Notifications') }}</span>
                    </x-responsive-nav-link>

                    <!-- Blogs Link -->
                    <x-responsive-nav-link href="{{ route('blogs') }}" :active="request()->routeIs('blogs')"
                        class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>{{ __('Blog') }}</span>
                    </x-responsive-nav-link>
                    <!-- Search Link -->
                    <x-responsive-nav-link href="{{ route('search') }}" :active="request()->routeIs('search')"
                        class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>{{ __('Search') }}</span>
                    </x-responsive-nav-link>
                    <!-- Admin Link -->
                    @if (auth()->user()->admin_rank >= 1)
                        <x-responsive-nav-link href="{{ route('admin') }}" :active="request()->routeIs('admin')"
                            class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ __('Admin') }}</span>
                        </x-responsive-nav-link>
                    @endif
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-600">
                    <div class="flex items-center px-4">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}"
                                    alt="{{ Auth::user()->name ?: Auth::user()->handle }}" />
                            </div>
                        @endif

                        <div>
                            <div class="font-medium text-base text-gray-200">
                                {{ Auth::user()->name ?: Auth::user()->handle }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <!-- Settings -->
                        <x-responsive-nav-link href="{{ url('/@' . Auth::user()->id) }}" :active="request()->url() == url('/@' . Auth::user()->handle)">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link href="{{ route('settings.show') }}" :active="request()->routeIs('settings.show')">
                            {{ __('Settings') }}
                        </x-responsive-nav-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                                {{ __('API Tokens') }}
                            </x-responsive-nav-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @else
        <x-guest-nav />
    @endif
</div>
