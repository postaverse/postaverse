@props(['post', 'showActions' => true, 'clickable' => true])

<div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md">
    {{-- Post Header --}}
    <div class="p-6 pb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                {{-- User Avatar --}}
                <a href="{{ route('user.profile', $post->user->username) }}" wire:navigate>
                    @if($post->user->avatar)
                        <img class="h-12 w-12 rounded-full object-cover" src="{{ Storage::url($post->user->avatar) }}" alt="{{ $post->user->display_name }}">
                    @else
                        <div class="h-12 w-12 rounded-full bg-zinc-700 flex items-center justify-center">
                            <span class="text-zinc-100 font-medium text-lg">{{ substr($post->user->username, 0, 1) }}</span>
                        </div>
                    @endif
                </a>
                
                <div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('user.profile', $post->user->username) }}" class="font-semibold text-zinc-100 hover:text-blue-400 transition-colors" wire:navigate>
                            {{ $post->user->display_name }}
                        </a>
                        @if($post->user->is_verified)
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        @if($post->user->admin_level > 0)
                            <span class="bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                @if($post->user->admin_level === 1) Super Admin
                                @elseif($post->user->admin_level === 2) Admin
                                @else Moderator @endif
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-zinc-400">
                        <a href="{{ route('user.profile', $post->user->username) }}" class="hover:text-blue-400 transition-colors" wire:navigate>
                            <span>{{ "@" . $post->user->username }}</span>
                        </a>
                        <span>•</span>
                        <time datetime="{{ $post->created_at->toISOString() }}">{{ $post->created_at->diffForHumans() }}</time>
                        @if($post->location)
                            <span>•</span>
                            <span>📍 {{ $post->location }}</span>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Post Menu --}}
            @if($showActions)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-zinc-400 hover:text-zinc-300 p-2 rounded-full hover:bg-zinc-800 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-zinc-800 border border-zinc-700 rounded-md shadow-lg z-10">
                        <div class="py-1">
                            @auth
                                @if($post->user_id === auth()->id())
                                    <button class="flex items-center w-full px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-700 hover:text-zinc-100">
                                        <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Edit post
                                    </button>
                                    <button class="flex items-center w-full px-4 py-2 text-sm text-red-400 hover:bg-zinc-700 hover:text-red-300">
                                        <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Delete post
                                    </button>
                                @else
                                    <button class="flex items-center w-full px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-700 hover:text-zinc-100">
                                        <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Report post
                                    </button>
                                    <button class="flex items-center w-full px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-700 hover:text-zinc-100">
                                        <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Block user
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Shared Post Indicator --}}
    @if($post->shared_post_id)
        <div class="px-6 pb-2">
            <div class="flex items-center text-sm text-zinc-500">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                </svg>
                <span>Shared {{ $post->user->display_name }}'s post</span>
            </div>
        </div>
    @endif

    {{-- Post Content --}}
    <div class="px-6 pb-4 {{ $clickable ? 'cursor-pointer' : '' }}" @if($clickable) onclick="window.Livewire.navigate('{{ route('posts.show', $post) }}')" @endif>
        {{-- Original Post Content --}}
        @if($post->sharedPost)
            {{-- Shared post content --}}
            <div class="border border-zinc-600 rounded-lg p-4 mb-4 bg-zinc-800">
                <div class="flex items-center space-x-3 mb-3">
                    <a href="{{ route('user.profile', $post->sharedPost->user->username) }}" wire:navigate>
                        @if($post->sharedPost->user->avatar)
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url($post->sharedPost->user->avatar) }}" alt="{{ $post->sharedPost->user->display_name }}">
                        @else
                            <div class="h-8 w-8 rounded-full bg-zinc-700 flex items-center justify-center">
                                <span class="text-zinc-100 font-medium text-sm">{{ substr($post->sharedPost->user->username, 0, 1) }}</span>
                            </div>
                        @endif
                    </a>
                    <div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('user.profile', $post->sharedPost->user->username) }}" class="font-medium text-zinc-200 hover:text-blue-400 transition-colors text-sm" wire:navigate>
                                {{ $post->sharedPost->user->display_name }}
                            </a>
                            @if($post->sharedPost->user->is_verified)
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2 text-xs text-zinc-500">
                            <span>{{ "@" . $post->sharedPost->user->username }}</span>
                            <span>•</span>
                            <time datetime="{{ $post->sharedPost->created_at->toISOString() }}">{{ $post->sharedPost->created_at->diffForHumans() }}</time>
                        </div>
                    </div>
                </div>
                
                <div class="text-zinc-300 text-sm">{{ $post->sharedPost->content }}</div>
                
                {{-- Shared post media --}}
                @if($post->sharedPost->media_urls && count($post->sharedPost->media_urls) > 0)
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        @foreach($post->sharedPost->media_urls as $media)
                            <img src="{{ $post->sharedPost->getImageUrl($media) }}" alt="Shared media" class="rounded-lg object-cover w-full h-32">
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Current Post Content --}}
        @if($post->content)
            <div class="text-zinc-100 mb-4">{!! nl2br(e($post->content)) !!}</div>
        @endif

        {{-- Media Display --}}
        @if($post->media_urls && count($post->media_urls) > 0)
            <div class="mb-4">
                @php
                    $mediaCount = count($post->media_urls);
                    $gridClass = match($mediaCount) {
                        1 => 'grid-cols-1',
                        2 => 'grid-cols-2',
                        3 => 'grid-cols-2',
                        4 => 'grid-cols-2',
                        default => 'grid-cols-3'
                    };
                @endphp
                
                <div class="grid {{ $gridClass }} gap-2">
                    @foreach($post->media_urls as $index => $media)
                        @if($mediaCount === 3 && $index === 0)
                            <div class="col-span-2">
                        @elseif($mediaCount > 4 && $index === 4)
                            <div class="relative">
                                <img src="{{ $post->getImageUrl($media) }}" alt="Post media" class="rounded-lg object-cover w-full h-64">
                                @if($mediaCount > 5)
                                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold">+{{ $mediaCount - 5 }}</span>
                                    </div>
                                @endif
                            </div>
                            @break
                        @else
                            <img src="{{ $post->getImageUrl($media) }}" alt="Post media" class="rounded-lg object-cover w-full h-64">
                        @endif
                        
                        @if($mediaCount === 3 && $index === 0)
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Post Actions --}}
    @if($showActions)
        <div class="px-6 py-4 border-t border-zinc-700 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                {{-- Like Button --}}
                <button wire:click="toggleLike({{ $post->id }})" class="flex items-center space-x-2 text-zinc-400 hover:text-red-500 transition-colors group">
                    <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                        @if($post->isLikedBy(auth()->user()))
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        @endif
                    </div>
                    <span class="text-sm">{{ $post->likes_count ?? 0 }}</span>
                </button>

                {{-- Comment Button --}}
                <button class="flex items-center space-x-2 text-zinc-400 hover:text-blue-500 transition-colors group">
                    <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="text-sm">{{ $post->comments_count ?? 0 }}</span>
                </button>

                {{-- Share Button --}}
                <button class="flex items-center space-x-2 text-zinc-400 hover:text-green-500 transition-colors group">
                    <div class="p-2 rounded-full group-hover:bg-green-500/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                    </div>
                    <span class="text-sm">{{ $post->shares_count ?? 0 }}</span>
                </button>
            </div>

            {{-- Views Counter --}}
            <div class="flex items-center space-x-1 text-zinc-500 text-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $post->views_count ?? 0 }}</span>
            </div>
        </div>
    @endif
</div>
