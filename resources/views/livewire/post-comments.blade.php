<div>
    <!-- Comments Section -->
    <div id="post-{{ $post->id }}-comments" class="border-t border-zinc-700 bg-zinc-800">
        @if($post->comments_count > 0 || auth()->check())
            <!-- Existing Comments -->
            @if($comments->count() > 0)
                <div class="px-6 py-4 space-y-4">
                    @foreach($comments as $comment)
                        <div class="flex items-start space-x-3">
                            <!-- Comment Author Avatar -->
                            @if($comment->user->avatar)
                                <img class="h-8 w-8 rounded-full object-cover flex-shrink-0" 
                                     src="{{ Storage::url($comment->user->avatar) }}" 
                                     alt="{{ $comment->user->display_name }}">
                            @else
                                <div class="h-8 w-8 rounded-full bg-zinc-700 flex items-center justify-center flex-shrink-0">
                                    <span class="text-zinc-200 font-medium text-sm">{{ substr($comment->user->first_name, 0, 1) }}</span>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <!-- Comment Content -->
                                <div class="bg-zinc-700 rounded-lg px-3 py-2">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="font-medium text-sm text-zinc-100">{{ $comment->user->display_name }}</span>
                                        @if($comment->user->is_verified)
                                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="text-sm text-zinc-200">{{ $comment->content }}</p>
                                </div>

                                <!-- Comment Actions -->
                                <div class="flex items-center space-x-4 mt-1 text-xs text-zinc-400">
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    @auth
                                        <button 
                                            wire:click="toggleCommentLike({{ $comment->id }})"
                                            class="hover:text-zinc-300 {{ $comment->likes()->where('user_id', auth()->id())->exists() ? 'text-red-400' : '' }}"
                                        >
                                            Like{{ $comment->likes_count > 0 ? ' (' . $comment->likes_count . ')' : '' }}
                                        </button>
                                        <button 
                                            wire:click="setReplyTo({{ $comment->id }})"
                                            class="hover:text-zinc-300"
                                        >
                                            Reply
                                        </button>
                                    @endauth
                                </div>

                                <!-- Reply Form -->
                                @auth
                                    @if($replyTo === $comment->id)
                                        <div class="mt-2">
                                            <div class="flex space-x-2">
                                                @if(auth()->user()->avatar)
                                                    <img class="h-6 w-6 rounded-full object-cover" 
                                                         src="{{ Storage::url(auth()->user()->avatar) }}" 
                                                         alt="{{ auth()->user()->display_name }}">
                                                @else
                                                    <div class="h-6 w-6 rounded-full bg-zinc-700 flex items-center justify-center">
                                                        <span class="text-zinc-200 font-medium text-xs">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex-1">
                                                    <textarea
                                                        wire:model="replyContent"
                                                        placeholder="Write a reply..."
                                                        class="w-full px-3 py-2 bg-zinc-600 text-zinc-100 text-sm rounded-lg border border-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                                        rows="2"
                                                    ></textarea>
                                                    <div class="flex justify-end space-x-2 mt-2">
                                                        <button 
                                                            wire:click="setReplyTo(null)"
                                                            class="px-3 py-1 text-xs bg-zinc-600 text-zinc-300 rounded hover:bg-zinc-500"
                                                        >
                                                            Cancel
                                                        </button>
                                                        <button 
                                                            wire:click="addReply"
                                                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700"
                                                        >
                                                            Reply
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endauth

                                <!-- Replies -->
                                @if($comment->replies->count() > 0)
                                    <div class="mt-3 space-y-2 pl-4 border-l-2 border-zinc-600">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex items-start space-x-2">
                                                @if($reply->user->avatar)
                                                    <img class="h-6 w-6 rounded-full object-cover" 
                                                         src="{{ Storage::url($reply->user->avatar) }}" 
                                                         alt="{{ $reply->user->display_name }}">
                                                @else
                                                    <div class="h-6 w-6 rounded-full bg-zinc-700 flex items-center justify-center">
                                                        <span class="text-zinc-200 font-medium text-xs">{{ substr($reply->user->first_name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex-1">
                                                    <div class="bg-zinc-600 rounded px-2 py-1">
                                                        <span class="font-medium text-xs text-zinc-100">{{ $reply->user->display_name }}</span>
                                                        <p class="text-xs text-zinc-200">{{ $reply->content }}</p>
                                                    </div>
                                                    <div class="flex items-center space-x-2 mt-1 text-xs text-zinc-400">
                                                        <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                        @auth
                                                            <button 
                                                                wire:click="toggleCommentLike({{ $reply->id }})"
                                                                class="hover:text-zinc-300"
                                                            >
                                                                Like
                                                            </button>
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Show More Comments Button -->
                    @if($post->comments_count > 3 && !$showAllComments)
                        <button 
                            wire:click="toggleShowAllComments"
                            class="text-sm text-zinc-400 hover:text-zinc-300 font-medium"
                        >
                            View all {{ $post->comments_count }} comments
                        </button>
                    @elseif($showAllComments && $post->comments_count > 3)
                        <button 
                            wire:click="toggleShowAllComments"
                            class="text-sm text-zinc-400 hover:text-zinc-300 font-medium"
                        >
                            Show fewer comments
                        </button>
                    @endif
                </div>
            @endif

            <!-- Add Comment Form -->
            @auth
                <div class="px-6 py-4 border-t border-zinc-700">
                    <div class="flex space-x-3">
                        @if(auth()->user()->avatar)
                            <img class="h-8 w-8 rounded-full object-cover" 
                                 src="{{ Storage::url(auth()->user()->avatar) }}" 
                                 alt="{{ auth()->user()->display_name }}">
                        @else
                            <div class="h-8 w-8 rounded-full bg-zinc-700 flex items-center justify-center">
                                <span class="text-zinc-200 font-medium text-sm">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <textarea
                                wire:model="newComment"
                                id="comment-input-{{ $post->id }}"
                                placeholder="Write a comment..."
                                class="w-full px-3 py-2 bg-zinc-700 text-zinc-100 rounded-lg border border-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                rows="2"
                            ></textarea>
                            @error('newComment') 
                                <span class="text-red-400 text-xs">{{ $message }}</span> 
                            @enderror
                            
                            <div class="flex justify-end mt-2">
                                <button 
                                    wire:click="addComment"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    {{ empty($newComment) ? 'disabled' : '' }}
                                >
                                    Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="px-6 py-4 border-t border-zinc-700 text-center">
                    <p class="text-zinc-400 text-sm">
                        <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Log in</a> 
                        to leave a comment
                    </p>
                </div>
            @endauth
        @else
            <!-- Show message when no comments and user not logged in -->
            <div class="px-6 py-4 text-center">
                <p class="text-zinc-400 text-sm">No comments yet. 
                    <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Log in</a> 
                    to be the first to comment!
                </p>
            </div>
        @endif
    </div>
</div>

@script
<script>
    $wire.on('focusCommentInput', (event) => {
        const postId = event.postId;
        const commentInput = document.querySelector('#comment-input-' + postId);
        
        if (commentInput) {
            commentInput.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            setTimeout(() => {
                commentInput.focus();
                commentInput.style.transition = 'box-shadow 0.3s ease-in-out';
                commentInput.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.5)';
                
                setTimeout(() => {
                    commentInput.style.boxShadow = '';
                }, 1000);
            }, 500);
        }
    });
</script>
@endscript
