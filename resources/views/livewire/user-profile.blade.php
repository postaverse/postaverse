<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header Card -->
            <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl mb-8 hover:border-white/20 transition-all duration-300">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        <!-- Profile Image -->
                        <div class="relative">
                            <div class="w-40 h-40 rounded-full overflow-hidden ring-4 ring-indigo-500/30 shadow-lg">
                                <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="w-full h-full object-cover">
                            </div>
                        </div>
                        
                        <!-- Profile Details -->
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl font-bold text-white mb-2 flex items-center">
                                {{ $user->name }}
                                <x-admin-tag :user="$user" />
                            </h1>
                            
                            @if ($site)
                                <div class="text-indigo-400 mb-4">{!! $site !!}</div>
                            @endif
                            
                            <div class="flex flex-wrap justify-center md:justify-start gap-4 items-center mt-4">
                                <div class="bg-gray-800/40 backdrop-blur-sm px-4 py-2 rounded-full text-white">
                                    <span class="font-semibold">{{ $user->followers()->count() }}</span> followers
                                </div>
                                <div class="bg-gray-800/40 backdrop-blur-sm px-4 py-2 rounded-full text-white">
                                    <span class="font-semibold">{{ $user->following()->count() }}</span> following
                                </div>
                                <div class="text-gray-400 text-sm">ID: {{ $user->id }}</div>
                            </div>
                            
                            @if (auth()->check() && $user->id !== auth()->id())
                                <div class="mt-6">
                                    @if ($this->isFollowing())
                                        <button wire:click="unfollowUser"
                                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-full transition-colors">
                                            Unfollow
                                        </button>
                                    @else
                                        <button wire:click="followUser"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-full transition-colors">
                                            Follow
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if ($user->bio !== null)
                        <div class="mt-8 p-6 bg-gray-800/40 backdrop-blur-sm border border-white/10 rounded-lg">
                            <h3 class="text-xl font-semibold text-white mb-4">About</h3>
                            <div class="text-white prose prose-invert">{!! $parsedown->text(e($user->bio())) !!}</div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- User Posts Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-6 ml-1">Posts</h2>
                
                @if ($posts->isEmpty())
                    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-8 hover:border-white/20 transition-all duration-300 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h1 class="text-xl font-bold text-white mb-2">No posts found</h1>
                        <p class="text-gray-400">This user hasn't created any posts yet.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($posts as $post)
                            <x-post :post="$post" />
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
