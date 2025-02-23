<div>
    @if (auth()->user())
        <nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-700 drdo">
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

                        <!-- Meteor Image and Count -->
                        <div class="pl-5 flex items-center shrink-0">
                            <img src="{{ asset('images/icons/meteor.png') }}" alt="Meteor" class="w-8 h-8">
                            <span
                                class="text-white text-lg font-bold pl-2">{{ auth()->user()->meteorQuantity->quantity }}</span>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <!-- Home Link -->
                            <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="flex flex-col items-center">
                                <img src="{{ asset('images/icons/nav/home.png') }}" alt="Home" class="w-9 h-9">
                                <span>{{ __('Home') }}</span>
                            </x-nav-link>
                            <!-- Feed Link -->
                            <x-nav-link href="{{ route('feed') }}" :active="request()->routeIs('feed')"
                                class="flex flex-col items-center relative">
                                <div class="relative">
                                    <img src="{{ asset('images/icons/nav/feed.png') }}" alt="Feed" class="w-9 h-9">
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-0 right-0 flex h-3 w-3">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                        </span>
                                    @endif
                                </div>
                                <span>{{ __('Feed') }}</span>
                            </x-nav-link>
                            <!-- Shop Link -->
                            <x-nav-link href="{{ route('shop') }}" :active="request()->routeIs('shop')"
                                class="flex flex-col items-center">
                                <img src="{{ asset('images/icons/nav/shop.png') }}" alt="Shop" class="w-9 h-9">
                                <span>{{ __('Shop') }}</span>
                            </x-nav-link>
                            <!-- Blogs Link -->
                            <x-nav-link href="{{ route('blogs') }}" :active="request()->routeIs('blogs')"
                                class="flex flex-col items-center">
                                <img src="{{ asset('images/icons/nav/blog.png') }}" alt="Blogs" class="w-9 h-9">
                                <span>{{ __('Blog') }}</span>
                            </x-nav-link>
                            <!-- Admin Link -->
                            @if (auth()->user()->admin_rank >= 1)
                                <x-nav-link href="{{ route('admin') }}" :active="request()->routeIs('admin')"
                                    class="flex flex-col items-center">
                                    <img src="{{ asset('images/icons/nav/admin.png') }}" alt="Admin" class="w-9 h-9">
                                    <span>{{ __('Admin') }}</span>
                                </x-nav-link>
                            @endif
                            <!-- Search Bar -->
                            <div class="relative mt-2.5">
                                <form action="{{ route('search') }}" method="GET" class="flex items-center">
                                    <input type="text" name="query" placeholder="Search posts/users..."
                                        class="bg-gray-800 text-white rounded-md py-2 pl-4 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                        autocomplete="off">
                                    <button type="submit" class="ml-2">

                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <!-- Teams Dropdown -->
                        @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                            <div class="ms-3 relative">
                                <x-dropdown align="right" width="60">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-800 hover:text-gray-300 focus:bg-gray-700 :active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ Auth::user()->currentTeam->name }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>

                                    <x-slot name="content">
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Manage Team') }}
                                            </div>

                                            <!-- Team Settings -->
                                            <x-dropdown-link
                                                href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                                {{ __('Team Settings') }}
                                            </x-dropdown-link>

                                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                                <x-dropdown-link href="{{ route('teams.create') }}">
                                                    {{ __('Create New Team') }}
                                                </x-dropdown-link>
                                            @endcan

                                            <!-- Team Switcher -->
                                            @if (Auth::user()->allTeams()->count() > 1)
                                                <div class="border-t border-gray-600"></div>

                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{ __('Switch Teams') }}
                                                </div>

                                                @foreach (Auth::user()->allTeams() as $team)
                                                    <x-switchable-team :team="$team" />
                                                @endforeach
                                            @endif
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif

                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative z-50">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <button
                                            class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                                            style="display: flex; align-items: center; padding: 5px;">
                                            <h3 class="text-white pr-4 font-bold text-sm" style="margin: 0;">Logged in
                                                as: {{ Auth::user()->name }}</h3>
                                            <img class="h-8 w-8 rounded-full object-cover"
                                                src="{{ Auth::user()->profile_photo_url }}"
                                                alt="{{ Auth::user()->name }}" />
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-800 hover:text-gray-300 focus:outline-none focus:bg-gray-700 active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ Auth::user()->name }}

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
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-400 hover:bg-gray-900 focus:outline-none focus:bg-gray-900 focus:text-gray-400 transition duration-150 ease-in-out">
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
                        <img src="{{ asset('images/icons/nav/home.png') }}" alt="Home" class="w-10 h-10">
                        <span>{{ __('Home') }}</span>
                    </x-responsive-nav-link>
                    <!-- Feed Link -->
                    <x-responsive-nav-link href="{{ route('feed') }}" :active="request()->routeIs('feed')"
                        class="flex items-center space-x-2 relative">
                        <div class="relative">
                            <img src="{{ asset('images/icons/nav/feed.png') }}" alt="Feed" class="w-10 h-10">
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                </span>
                            @endif
                        </div>
                        <span>{{ __('Feed') }}</span>
                    </x-responsive-nav-link>
                    <!-- Shop Link -->
                    <x-responsive-nav-link href="{{ route('shop') }}" :active="request()->routeIs('shop')"
                        class="flex items-center space-x-2">
                        <img src="{{ asset('images/icons/nav/shop.png') }}" alt="Shop" class="w-10 h-10">
                        <span>{{ __('Shop') }}</span>
                    </x-responsive-nav-link>
                    <!-- Blogs Link -->
                    <x-responsive-nav-link href="{{ route('blogs') }}" :active="request()->routeIs('blogs')"
                        class="flex items-center space-x-2">
                        <img src="{{ asset('images/icons/nav/blog.png') }}" alt="Blogs" class="w-10 h-10">
                        <span>{{ __('Blog') }}</span>
                    </x-responsive-nav-link>
                    <!-- Admin Link -->
                    @if (auth()->user()->admin_rank >= 1)
                        <x-responsive-nav-link href="{{ route('admin') }}" :active="request()->routeIs('admin')"
                            class="flex items-center space-x-2">
                            <img src="{{ asset('images/icons/nav/admin.png') }}" alt="Admin" class="w-10 h-10">
                            <span>{{ __('Admin') }}</span>
                        </x-responsive-nav-link>
                    @endif
                    <!-- Search Bar -->
                    <div class="ml-4 relative mt-5 mr-2">
                        <form action="{{ route('search') }}" method="GET">
                            <input type="text" name="query" placeholder="Search posts/users..."
                                class="bg-gray-800 text-white rounded-md py-2 pl-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                autocomplete="off">
                            <button type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 mt-2 mr-4">

                            </button>
                        </form>
                    </div>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-600">
                    <div class="flex items-center px-4">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </div>
                        @endif

                        <div>
                            <div class="font-medium text-base text-gray-200">
                                {{ Auth::user()->name }}</div>
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

                        <!-- Team Management -->
                        @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                            <div class="border-t border-gray-600"></div>

                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Team') }}
                            </div>

                            <!-- Team Settings -->
                            <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                :active="request()->routeIs('teams.show')">
                                {{ __('Team Settings') }}
                            </x-responsive-nav-link>

                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                    {{ __('Create New Team') }}
                                </x-responsive-nav-link>
                            @endcan

                            <!-- Team Switcher -->
                            @if (Auth::user()->allTeams()->count() > 1)
                                <div class="border-t border-gray-600"></div>

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Switch Teams') }}
                                </div>

                                @foreach (Auth::user()->allTeams() as $team)
                                    <x-switchable-team :team="$team" component="responsive-nav-link" />
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    @endif
</div>
