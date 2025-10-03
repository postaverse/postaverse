<x-layouts.app :title="__('Home Feed')">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-zinc-100">Home Feed</h1>
                    
                    <!-- Header Stats -->
                    <div class="flex items-center space-x-6 text-sm text-zinc-400">
                        @auth
                            <span>Welcome back, {{ auth()->user()->username }}! 👋</span>
                            @if(auth()->user()->admin_level)
                                <span class="bg-purple-800 text-purple-200 px-3 py-1 rounded-full font-medium">
                                    {{ auth()->user()->admin_level_name }}
                                </span>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- User Profile Card -->
                        @auth
                            <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg p-6">
                                <div class="text-center">
                                    <a href="{{ route('user.profile', auth()->user()->username) }}" wire:navigate>
                                        @if(auth()->user()->avatar)
                                            <img class="h-20 w-20 rounded-full mx-auto object-cover" src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->username }}">
                                        @else
                                            <div class="h-20 w-20 rounded-full bg-zinc-700 flex items-center justify-center mx-auto">
                                                <span class="text-zinc-300 font-medium text-2xl">{{ substr(auth()->user()->username, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <div class="mt-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('user.profile', auth()->user()->username) }}" wire:navigate class="font-semibold text-zinc-100 hover:text-blue-400 transition-colors">{{ auth()->user()->display_name }}</a>
                                            @if(auth()->user()->is_verified)
                                                <flux:icon.check-badge class="w-5 h-5 text-blue-400"/>
                                            @endif
                                        </div>
                                        <p class="text-sm text-zinc-400">{{ '@' . auth()->user()->username }}</p>
                                        @if(auth()->user()->bio)
                                            <p class="text-sm text-zinc-300 mt-2">{{ auth()->user()->bio }}</p>
                                        @endif
                                    </div>
                                    
                                    <!-- User Stats -->
                                    <div class="grid grid-cols-3 gap-4 mt-6 text-center">
                                        <div>
                                            <div class="font-semibold text-zinc-100">{{ auth()->user()->posts()->count() }}</div>
                                            <div class="text-xs text-zinc-400">Posts</div>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-zinc-100">{{ auth()->user()->followers()->count() }}</div>
                                            <div class="text-xs text-zinc-400">Followers</div>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-zinc-100">{{ auth()->user()->following()->count() }}</div>
                                            <div class="text-xs text-zinc-400">Following</div>
                                        </div>
                                    </div>
                                    
                                    <!-- View Profile Button -->
                                    <div class="mt-6">
                                        <a href="{{ route('user.profile', auth()->user()->username) }}" wire:navigate class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors text-center block">
                                            View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endauth

                        <!-- Quick Actions -->
                        <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg p-6">
                            <h4 class="font-semibold text-zinc-100 mb-4">Quick Actions</h4>
                            <div class="space-y-3">
                                <a href="{{ route('discover-people') }}" class="flex items-center space-x-3 text-zinc-300 hover:text-blue-400 transition-colors" wire:navigate>
                                    <flux:icon.users class="w-5 h-5"/>
                                    <span>Find Friends</span>
                                </a>
                                <a href="{{ route('groups') }}" class="flex items-center space-x-3 text-zinc-300 hover:text-blue-400 transition-colors" wire:navigate>
                                    <flux:icon.user-group class="w-5 h-5"/>
                                    <span>Browse Groups</span>
                                </a>
                                {{--
                                <a href="{{ route('messages') }}" class="flex items-center space-x-3 text-zinc-300 hover:text-blue-400 transition-colors" wire:navigate>
                                    <flux:icon.chat-bubble-left-right class="w-5 h-5"/>
                                    <span>Messages</span>
                                </a>
                                --}}
                                <a href="{{ route('settings.profile') }}" class="flex items-center space-x-3 text-zinc-300 hover:text-blue-400 transition-colors" wire:navigate>
                                    <flux:icon.cog-6-tooth class="w-5 h-5"/>
                                    <span>Settings</span>
                                </a>
                            </div>
                        </div>

                        <!-- Trending Hashtags -->
                        <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg p-6">
                            <h4 class="font-semibold text-zinc-100 mb-4">Trending Hashtags</h4>
                            <div class="space-y-2">
                                @php
                                    $trendingHashtags = \App\Models\Hashtag::trending()->take(5)->get();
                                @endphp
                                @forelse($trendingHashtags as $hashtag)
                                    <div class="flex items-center justify-between">
                                        <span class="text-blue-400 font-medium cursor-pointer hover:text-blue-300">#{{ $hashtag->name }}</span>
                                        <span class="text-xs text-zinc-500">{{ $hashtag->posts_count }} posts</span>
                                    </div>
                                @empty
                                    <p class="text-zinc-400 text-sm">No trending hashtags yet</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="space-y-6">
                        <!-- Create Post Component -->
                        @auth
                            <livewire:create-post />
                        @endauth

                        <!-- Post Feed Component -->
                        <livewire:post-feed />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
