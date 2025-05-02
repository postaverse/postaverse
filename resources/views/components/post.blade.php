<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6" wire:key="{{ $post->id }}">
    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
        <!-- Post Header: User Info -->
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}'s profile photo"
                    class="w-10 h-10 rounded-full ring-2 ring-indigo-500/30">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center">
                        <a href="{{ route('user-profile', $post->user->id) }}" class="hover:text-indigo-400 transition-colors">
                            {{ $post->user->name }}
                        </a>
                        <x-admin-tag :user="$post->user" />
                    </h2>
                    <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Post Stats -->
            <div class="text-xs text-gray-400 flex items-center">
                <div class="flex items-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    {{ $post->comments->count() }}
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    {{ $post->likes->count() }}
                </div>
            </div>
        </div>

        <!-- Post Title and Content -->
        <div class="mb-6">
            @if (auth()->user())
                @if ($post->has_profanity)
                    @if (auth()->user()->profanity_block_type == 'hide_clickable')
                        <div>
                            <a href="#"
                                onclick="event.preventDefault(); this.nextElementSibling.style.display='block'; this.style.display='none'"
                                class="block p-3 bg-red-900/20 border border-red-900/30 rounded-lg mb-4 hover:bg-red-900/30 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span class="text-red-500 font-medium">Content hidden due to profanity. Click to reveal.</span>
                                </div>
                            </a>
                            <div style="display:none;">
                                <a href="{{ route('post', $post->id) }}" class="block">
                                    <h1 class="text-xl font-bold text-white mb-2 hover:text-indigo-400 transition-colors">
                                        {{ $post->title }}
                                    </h1>
                                </a>
                            </div>
                        </div>
                    @elseif(auth()->user()->profanity_block_type == 'hide')
                        <div class="p-3 bg-red-900/20 border border-red-900/30 rounded-lg mb-4">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-red-500 font-medium">Content hidden due to profanity.</span>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('post', $post->id) }}" class="block">
                            <h1 class="text-xl font-bold text-white mb-2 hover:text-indigo-400 transition-colors">
                                {{ $post->title }}
                            </h1>
                        </a>
                    @endif
                @else
                    <a href="{{ route('post', $post->id) }}" class="block">
                        <h1 class="text-xl font-bold text-white mb-2 hover:text-indigo-400 transition-colors">
                            {{ $post->title }}
                        </h1>
                    </a>
                @endif
            @else
                <a href="{{ route('post', $post->id) }}" class="block">
                    <h1 class="text-xl font-bold text-white mb-2 hover:text-indigo-400 transition-colors">
                        {{ $post->title }}
                    </h1>
                </a>
            @endif
        </div>

        <!-- Post Actions -->
        <div class="flex items-center justify-between">
            <!-- Like Button and Avatars -->
            @if (auth()->user())
                <div class="flex items-center space-x-4">
                    <button wire:click="likePost({{ $post->id }})" class="focus:outline-none hover:scale-110 transition-transform">
                        @if (!$post->likes->contains('user_id', auth()->id()))
                            <img src="{{ asset('images/icons/like/unliked.png') }}" alt="Like" class="w-8 h-8 filter hover:brightness-125 transition-all">
                        @else
                            <img src="{{ asset('images/icons/like/liked.png') }}" alt="Unlike" class="w-8 h-8 filter hover:brightness-125 transition-all">
                        @endif
                    </button>
                    
                    @if ($post->likes->count() > 0)
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach ($post->likes->take(3) as $like)
                                <a href="{{ route('user-profile', $like->user->id) }}" class="relative hover:z-10 transition-all">
                                    <img src="{{ $like->user->profile_photo_url }}"
                                        alt="{{ $like->user->name }}'s profile photo"
                                        class="w-6 h-6 rounded-full border-2 border-gray-800 hover:border-indigo-500 transition-colors">
                                </a>
                            @endforeach
                            @if ($post->likes->count() > 3)
                                <a href="{{ route('post', $post->id) }}" class="relative hover:z-10 flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs border-2 border-gray-800 hover:bg-indigo-500 transition-colors">
                                    +{{ $post->likes->count() - 3 }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                
                <!-- Delete Button -->
                @if ($post->user_id == auth()->user()->id || auth()->user()->admin_rank >= 3)
                    <button wire:click="delete({{ $post->id }})" class="text-red-500 hover:text-red-400 transition-colors text-sm flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                @endif
            @endif
        </div>
    </div>
</div>
