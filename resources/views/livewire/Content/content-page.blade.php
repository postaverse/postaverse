<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ $contentType === 'blog' || $contentType === 'blogs' ? __('Blog') : __('Post') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-center justify-center main py-8">
        <!-- Main Content Card -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 w-full">
            <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                <!-- User Info and Content Metadata -->
                <div class="flex items-center space-x-4 mb-6 pb-4 border-b border-white/10">
                    <img src="{{ $content->user->profile_photo_url }}" alt="{{ $content->user->name ?: $content->user->handle }}'s profile photo"
                        class="w-12 h-12 rounded-full ring-2 ring-indigo-500/50">
                    <div>
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <a href="{{ route('user-profile', $content->user->id) }}" class="hover:text-indigo-400 transition-colors">
                                {{ $content->user->name ?: $content->user->handle }}
                            </a>
                            <x-admin-tag :user="$content->user" />
                        </h2>
                        <p class="text-sm text-gray-400">{{ $content->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                <!-- Content Title -->
                <h1 class="text-2xl font-bold text-white mb-4">
                    {{ $content->title }}
                </h1>
                
                <!-- Content Body -->
                <div class="text-white prose prose-invert max-w-none bg-gray-800/40 backdrop-blur-sm rounded-lg mb-6">
                    <div class="p-6">
                        {!! $formattedContent !!}
                    </div>
                </div>

                <!-- Images Grid -->
                @if ($images && $images->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        @foreach ($images as $image)
                            <a href="{{ Storage::url($image->path) }}" target="_blank" class="block overflow-hidden rounded-lg hover:opacity-90 transition-opacity">
                                <img src="{{ Storage::url($image->path) }}" alt="" loading="lazy"
                                    class="w-full h-auto object-cover" decoding="async" />
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Actions Bar -->
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-white/10">
                    <!-- Like Count -->
                    <span class="text-white" id="likeCount-{{ $content->id }}">{{ $content->likes->count() }} likes</span>
                    
                    <!-- Delete Button -->
                    @if (auth()->user() && ($content->user_id == auth()->user()->id || auth()->user()->admin_rank >= 3))
                        <button class="text-red-500 hover:text-red-400 font-semibold transition-colors flex items-center gap-1" 
                                wire:click="delete({{ $content->id }})">
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 w-full">
                <h2 class="text-2xl font-bold text-white mb-4 ml-1">Likes</h2>
                <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                    <div class="flex items-center gap-6">
                        <!-- Like Button -->
                        <button wire:click="likeContent({{ $content->id }})" class="text-white hover:scale-110 transition-transform likeButton" data-content-id="{{ $content->id }}">
                            @if ($contentType === 'blog' || $contentType === 'blogs')
                                @if (!$content->likes->contains('user_id', auth()->id()))
                                    <img src="{{ asset('images/icons/like/unliked.png') }}" alt="Unlike" width="40"
                                        height="40" class="p-1 filter hover:brightness-125 transition-all">
                                @else
                                    <img src="{{ asset('images/icons/like/liked.png') }}" alt="Like" width="40"
                                        height="40" class="p-1 filter hover:brightness-125 transition-all">
                                @endif
                            @else
                                @if (!$content->likes->contains('user_id', auth()->id()))
                                    <img src="{{ asset('images/icons/like/unliked.png') }}" alt="Unlike" width="40"
                                        height="40" class="p-1 filter hover:brightness-125 transition-all">
                                @else
                                    <img src="{{ asset('images/icons/like/liked.png') }}" alt="Like" width="40"
                                        height="40" class="p-1 filter hover:brightness-125 transition-all">
                                @endif
                            @endif
                        </button>
                        
                        <!-- User Avatars -->
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach ($content->likes->take(10) as $like)
                                <a href="{{ route('user-profile', $like->user->id) }}" class="relative hover:z-10 transition-all">
                                    <img src="{{ $like->user->profile_photo_url }}"
                                        alt="{{ $like->user->name ?: $like->user->handle }}'s profile photo"
                                        class="w-10 h-10 rounded-full border-2 border-gray-800 bg-gray-800 hover:border-indigo-500 transition-all">
                                </a>
                            @endforeach
                            @if ($content->likes->count() > 10)
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white border-2 border-gray-800 relative hover:z-10 font-semibold text-sm">
                                    +{{ $content->likes->count() - 10 }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Comments Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 w-full mt-6">
            <h2 class="text-2xl font-bold text-white mb-4 ml-1">Comments</h2>
            <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                <!-- Comment Form -->
                @if (auth()->user())
                    <div class="pb-6 mb-6 border-b border-white/10">
                        <form wire:submit.prevent="submit" class="max-w-7xl w-full">
                            <div class="mb-4 max-w-7xl w-full">
                                <x-label for="comment" value="{{ __('Comment') }}" class="text-gray-300 mb-2" />
                                <textarea 
                                    id="comment" 
                                    class="w-full p-3 rounded-lg bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" 
                                    wire:model="commentText"
                                    placeholder="Write your comment here..."></textarea>
                                @error('commentText')
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
                                            alt="{{ $comment->user->name ?: $comment->user->handle }}'s profile photo" 
                                            class="w-8 h-8 rounded-full">
                                        <div>
                                            <h2 class="text-sm font-bold text-white flex items-center">
                                                <a href="{{ route('user-profile', $comment->user->id) }}" class="hover:text-indigo-400 transition-colors">
                                                    {{ $comment->user->name ?: $comment->user->handle }}
                                                </a>
                                                <x-admin-tag :user="$comment->user" />
                                            </h2>
                                            <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <!-- Reply Button -->
                                        @if (auth()->user())
                                            <button wire:click="startReply({{ $comment->id }})" class="text-indigo-400 hover:text-indigo-300 transition-colors text-sm flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                Reply
                                            </button>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        @if (auth()->user() && 
                                            ($comment->user_id == auth()->user()->id ||
                                             ($contentType === 'blog' ? $comment->blog->user_id : $comment->post->user_id) == auth()->user()->id ||
                                             auth()->user()->admin_rank > 2))
                                            <button wire:click="deleteComment({{ $comment->id }})"
                                                class="text-red-500 hover:text-red-400 transition-colors text-sm">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Comment Content -->
                                @if (isset($comment->has_profanity) && $comment->has_profanity)
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
                                
                                <!-- Reply Form -->
                                @if (auth()->user() && $replyingTo === $comment->id)
                                    <div class="mt-4 pl-4 border-l-2 border-indigo-500/30">
                                        <form wire:submit.prevent="submitReply" class="w-full">
                                            <div class="mb-3">
                                                <textarea 
                                                    id="replyContent-{{ $comment->id }}" 
                                                    class="w-full p-3 rounded-lg bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all text-sm" 
                                                    wire:model="replyContent"
                                                    placeholder="Write your reply here..."></textarea>
                                                @error('replyContent')
                                                    <span class="error text-red-500 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="flex space-x-2">
                                                <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 transition-colors py-1 px-3 text-sm">
                                                    {{ __('Post Reply') }}
                                                </x-button>
                                                <button type="button" wire:click="cancelReply" class="text-gray-400 hover:text-gray-300 transition-colors text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                                
                                <!-- Comment Replies -->
                                @if ($comment->replies && $comment->replies->count() > 0)
                                    <div class="mt-4 pl-4 border-l border-indigo-500/20">
                                        @foreach ($comment->replies as $reply)
                                            @include('partials.content-comment-replies', ['reply' => $reply, 'replyingTo' => $replyingTo, 'contentType' => $contentType])
                                        @endforeach
                                    </div>
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
</div>

<script>
    document.querySelectorAll('.likeButton').forEach(button => {
        const contentId = button.dataset.contentId;
        const countText = document.getElementById(`likeCount-${contentId}`);

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
    
    // For reply forms
    document.addEventListener('livewire:load', function() {
        Livewire.on('replyAdded', () => {
            // This will be triggered after a reply is submitted
        });
        
        Livewire.on('commentAdded', () => {
            document.getElementById('comment').value = '';
        });
    });
</script>
