<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-100 mb-4">Discover People</h1>
            <p class="text-zinc-400">Find and connect with new people in the Postaverse community</p>
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <div class="max-w-md">
                <flux:input 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search people..."
                    icon="search"
                    class="w-full"
                />
            </div>
        </div>

        <!-- Users Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @forelse($users as $user)
                <div class="bg-zinc-900 border border-zinc-700 rounded-lg p-6 text-center hover:border-zinc-600 transition-colors">
                    <!-- Avatar (clickable) -->
                    <a href="{{ route('user.profile', $user->username) }}" class="block mb-4">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->username }}" class="w-20 h-20 rounded-full mx-auto object-cover hover:opacity-80 transition-opacity">
                        @else
                            <div class="w-20 h-20 rounded-full bg-zinc-700 flex items-center justify-center mx-auto hover:opacity-80 transition-opacity">
                                <span class="text-zinc-300 font-bold text-xl">{{ substr($user->username, 0, 2) }}</span>
                            </div>
                        @endif
                    </a>

                    <!-- User Info -->
                    <div class="mb-4">
                        <a href="{{ route('user.profile', $user->username) }}" class="block hover:opacity-80 transition-opacity">
                            <h3 class="font-semibold text-zinc-100 mb-1 flex items-center justify-center gap-2">
                                {{ $user->username }}
                            @if($user->is_verified)
                                <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            @if($user->admin_level)
                                <span class="text-xs bg-purple-800 text-purple-200 px-2 py-1 rounded-full">
                                    {{ $user->admin_level_name }}
                                </span>
                            @endif
                        </h3>
                        <p class="text-zinc-400 text-sm">{{ '@' . $user->username }}</p>
                        </a>
                        @if($user->bio)
                            <p class="text-zinc-300 text-sm mt-2 line-clamp-2">{{ $user->bio }}</p>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="flex justify-center space-x-4 mb-4 text-xs text-zinc-400">
                        <div class="text-center">
                            <div class="font-semibold text-zinc-200">{{ $user->posts_count }}</div>
                            <div>Posts</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-zinc-200">{{ $user->followers_count }}</div>
                            <div>Followers</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-zinc-200">{{ $user->following_count }}</div>
                            <div>Following</div>
                        </div>
                    </div>

                    <!-- Follow Button -->
                    @if(auth()->user()->following()->where('following_id', $user->id)->exists())
                        <flux:button 
                            wire:click="unfollowUser({{ $user->id }})"
                            variant="outline"
                            size="sm"
                            class="w-full"
                        >
                            Following
                        </flux:button>
                    @else
                        <flux:button 
                            wire:click="followUser({{ $user->id }})"
                            variant="primary"
                            size="sm"
                            class="w-full"
                        >
                            Follow
                        </flux:button>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-zinc-400 text-lg mb-2">No users found</div>
                    @if($search)
                        <p class="text-zinc-500">Try adjusting your search terms</p>
                    @else
                        <p class="text-zinc-500">Be the first to invite people to join!</p>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="flex justify-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
