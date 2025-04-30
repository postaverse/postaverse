<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Post') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-center justify-center main py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 main w-full">
            <!-- Main Post Content Card -->
            <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                <!-- User Info and Post Metadata -->
                <div class="flex items-center space-x-4 mb-6 pb-4 border-b border-white/10">
                    <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}'s profile photo"
                        class="w-12 h-12 rounded-full ring-2 ring-indigo-500/50">
                    <div>
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <a href="{{ route('user-profile', $post->user->id) }}" class="hover:text-indigo-400 transition-colors">
                                {{ $post->user->name }}
                            </a>
                            <x-admin-tag :user="$post->user" />
                        </h2>
                        <p class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                <!-- Post Title -->
                <h1 class="text-2xl font-bold text-white mb-4">
                    {{ $post->title }}
                </h1>
                
                <!-- Post Content -->
                <div class="text-white prose prose-invert max-w-none bg-gray-800/40 backdrop-blur-sm rounded-lg mb-6">
                    <div class="p-6">
                        {!! $postContent !!}
                    </div>
                </div>

                <!-- Photos Grid -->
                @if (!empty($photos))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        @foreach ($photos as $photo)
                            <a href="{{ Storage::url($photo->path) }}" target="_blank" class="block overflow-hidden rounded-lg hover:opacity-90 transition-opacity">
                                <img src="{{ Storage::url($photo->path) }}" alt="" loading="lazy"
                                    class="w-full h-auto object-cover" decoding="async" />
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Actions Bar -->
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-white/10">
                    <!-- Like Count -->
                    <span class="text-white" id="likeCount-{{ $post->id }}">{{ $post->likes->count() }} likes</span>
                    
                    <!-- Delete Button -->
                    @if (auth()->user() && ($post->user_id == auth()->user()->id))
                        <button class="text-red-500 hover:text-red-400 font-semibold transition-colors flex items-center gap-1" 
                                wire:click="delete({{ $post->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        @if (auth()->user())
            <!-- Likes Section -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 main w-full">
                <h2 class="text-2xl font-bold text-white mb-4 ml-1">Likes</h2>
                <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                    <div class="flex items-center gap-6">
                        <!-- Like Button -->
                        <button wire:click="likePost({{ $post->id }})" class="text-white hover:scale-110 transition-transform" id="likeButton">
                            @if (!$post->likes->contains('user_id', auth()->id()))
                                <img src="{{ asset('images/icons/like/unliked.png') }}" alt="Unlike" width="40"
                                    height="40" class="p-1 filter hover:brightness-125 transition-all">
                            @else
                                <img src="{{ asset('images/icons/like/liked.png') }}" alt="Like" width="40"
                                    height="40" class="p-1 filter hover:brightness-125 transition-all">
                            @endif
                        </button>
                        
                        <!-- User Avatars -->
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach ($post->likes->take(10) as $like)
                                <a href="{{ route('user-profile', $like->user->id) }}" class="relative hover:z-10 transition-all">
                                    <img src="{{ $like->user->profile_photo_url }}"
                                        alt="{{ $like->user->name }}'s profile photo"
                                        class="w-10 h-10 rounded-full border-2 border-gray-800 bg-gray-800 hover:border-indigo-500 transition-all">
                                </a>
                            @endforeach
                            @if ($post->likes->count() > 10)
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white border-2 border-gray-800 relative hover:z-10 font-semibold text-sm">
                                    +{{ $post->likes->count() - 10 }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Comments Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 main w-full mt-6">
            <h2 class="text-2xl font-bold text-white mb-4 ml-1">Comments</h2>
            <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                <!-- Comment Form -->
                @if (auth()->user())
                    <div class="pb-6 mb-6 border-b border-white/10">
                        <form wire:submit.prevent="submit" class="max-w-7xl w-full">
                            <div class="mb-4 max-w-7xl w-full">
                                <x-label for="comment" value="{{ __('Comment') }}" class="text-gray-300 mb-2" />
                                <textarea 
                                    id="comment" 
                                    class="w-full p-3 rounded-lg bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" 
                                    wire:model="content"
                                    placeholder="Write your comment here..."></textarea>
                                @error('content')
                                    <span class="error text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                <img src="{{ asset('images/icons/blastoff.png') }}" alt="Submit" width="30" height="30" class="p-1 pr-2">
                                {{ __('Post') }}
                            </x-button>
                            <script>
                                document.addEventListener('livewire:load', function() {
                                    Livewire.on('commentAdded', () => {
                                        document.getElementById('comment').value = '';
                                    });
                                });
                            </script>
                        </form>
                    </div>
                @endif
                
                <!-- Comments List -->
                <div class="space-y-4">
                    @if ($comments && $comments->count() > 0)
                        @foreach ($comments as $comment)
                            <div class="bg-gray-800/40 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-all">
                                <!-- Comment User Info -->
                                <div class="flex justify-between mb-3">
                                    <div class="flex space-x-3">
                                        <img src="{{ $comment->user->profile_photo_url }}"
                                            alt="{{ $comment->user->name }}'s profile photo" 
                                            class="w-8 h-8 rounded-full">
                                        <div>
                                            <h2 class="text-sm font-bold text-white flex items-center">
                                                <a href="{{ route('user-profile', $comment->user->id) }}" class="hover:text-indigo-400 transition-colors">
                                                    {{ $comment->user->name }}
                                                </a>
                                                <x-admin-tag :user="$comment->user" />
                                            </h2>
                                            <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Comment Actions -->
                                    @if (auth()->user())
                                        @if (
                                            $comment->user_id == auth()->user()->id ||
                                                $comment->post->user_id == auth()->user()->id ||
                                                auth()->user()->admin_rank > 2)
                                            <button wire:click="deleteComment({{ $comment->id }})"
                                                class="text-red-500 hover:text-red-400 transition-colors text-sm">
                                                Delete
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                
                                <!-- Comment Content -->
                                @if ($comment->has_profanity)
                                    @if (auth()->user()->profanity_block_type == 'hide_clickable')
                                        <div>
                                            <a href="#"
                                                onclick="event.preventDefault(); this.nextElementSibling.style.display='block'; this.style.display='none'">
                                                <p class="text-red-500 hover:underline text-sm">
                                                    Content hidden due to profanity. Click to reveal.
                                                </p>
                                            </a>
                                            <div class="text-white prose prose-invert prose-sm mt-2" style="display:none;">
                                                {!! $comment->content !!}
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-red-500 text-sm">
                                            Content hidden due to profanity.
                                        </p>
                                    @endif
                                @else
                                    <div class="text-white prose prose-invert prose-sm mt-2">{!! $comment->content !!}</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-400">No comments yet. Be the first to comment!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.querySelectorAll('.likeButton').forEach(button => {
            const postId = button.dataset.postId;
            const countText = document.getElementById(`likeCount-${postId}`);

            button.addEventListener('click', function() {
                if (button.innerHTML.includes('unliked.png')) {
                    button.innerHTML =
                        '<img src="{{ asset('images/icons/like/liked.png') }}" alt="Like" width="40" height="40" class="p-1 filter hover:brightness-125 transition-all">';
                    countText.innerHTML = parseInt(countText.innerHTML) - 1 + ' likes';
                } else {
                    button.innerHTML =
                        '<img src="{{ asset('images/icons/like/unliked.png') }}" alt="Unlike" width="40" height="40" class="p-1 filter hover:brightness-125 transition-all">';
                    countText.innerHTML = parseInt(countText.innerHTML) + 1 + ' likes';
                }
            });
        });
    </script>
</div>
