<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Search Form -->
        <div class="w-full mb-8">
            <div
                class="bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-lg shadow-sm p-6 hover:border-white/30 transition-colors duration-200">
                <form action="{{ route('search') }}" method="GET">
                    <div class="relative">
                        <div class="flex items-center">
                            <input type="text" name="query" value="{{ $query }}"
                                placeholder="Search for posts or users..."
                                class="bg-gray-900/50 border border-white/20 text-white rounded-lg py-3 pl-5 pr-12 w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                autocomplete="off">
                            <button type="submit"
                                class="absolute right-3 p-2 bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-gray-400 text-sm mt-2">Search for users by name or posts by title/content</p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div class="space-y-8">
            <!-- Users Results -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-white mb-4 pb-2 border-b border-gray-700">Users</h3>
                @if ($users->isEmpty())
                    <div class="bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-lg p-6 text-gray-300">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>No users found matching "{{ $query }}"</span>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($users as $user)
                            <div wire:key="{{ $user->id }}"
                                class="bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-lg shadow-sm p-4 flex items-center hover:border-white/30 transition-colors duration-200">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}'s profile photo"
                                    class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="font-bold text-white flex items-center">
                                        <a href="{{ route('user-profile', $user->id) }}"
                                            class="hover:text-indigo-400 transition-colors duration-200">
                                            {{ $user->name }}
                                        </a>
                                        <x-admin-tag :user="$user" />
                                    </h4>
                                    <p class="text-sm text-gray-400">User ID: {{ $user->id }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

            <!-- Posts Results -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4 pb-2 border-b border-gray-700">Posts</h3>
                @if ($posts->isEmpty())
                    <div class="bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-lg p-6 text-gray-300">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>No posts found matching "{{ $query }}"</span>
                        </div>
                    </div>
                @else
                    @foreach ($posts as $post)
                        <div class="w-full mb-6">
                            <x-post :post="$post" />
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
