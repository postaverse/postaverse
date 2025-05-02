<nav class="bg-gray-900/70 backdrop-blur-md border-b border-gray-700/50 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}" class="text-xl font-bold text-white">
                        Postaverse
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                <a href="{{ route('welcome') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Landing
                </a>
            
                <a href="/home" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Home
                </a>
                
                @if(Route::has('blogs'))
                <a href="{{ route('blogs') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Blog
                </a>
                @endif
                
                @if(Route::has('search'))
                <a href="{{ route('search') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Search
                </a>
                @endif
                
                @guest
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-indigo-400 hover:bg-gray-700 hover:text-indigo-300 transition duration-150 ease-in-out">
                    Login
                </a>
                @if(Route::has('register'))
                <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition duration-150 ease-in-out">
                    Register
                </a>
                @endif
                @endguest
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button id="mobileMenuButton" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path id="menuOpenIcon" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path id="menuCloseIcon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, toggle classes based on menu state -->
    <div id="mobileMenu" class="hidden sm:hidden border-t border-gray-700">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('welcome') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                Landing
            </a>
        
            <a href="/home" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                Home
            </a>
            
            @if(Route::has('blogs'))
            <a href="{{ route('blogs') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                Blog
            </a>
            @endif
            
            @if(Route::has('search'))
            <a href="{{ route('search') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                Search
            </a>
            @endif
            
            @guest
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-400 hover:bg-gray-700 hover:text-indigo-300 transition duration-150 ease-in-out">
                Login
            </a>
            @if(Route::has('register'))
            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition duration-150 ease-in-out mb-1">
                Register
            </a>
            @endif
            @endguest
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOpenIcon = document.getElementById('menuOpenIcon');
        const menuCloseIcon = document.getElementById('menuCloseIcon');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                menuOpenIcon.classList.toggle('hidden');
                menuOpenIcon.classList.toggle('inline-flex');
                menuCloseIcon.classList.toggle('hidden');
                menuCloseIcon.classList.toggle('inline-flex');
            });
        }
    });
</script>