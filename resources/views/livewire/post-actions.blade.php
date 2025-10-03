<div>
    <!-- Post Stats -->
    <div class="px-6 py-2 border-t border-zinc-700">
        <div class="flex items-center justify-between text-sm text-zinc-400">
            <div class="flex items-center space-x-4">
                @if($post->likes_count > 0)
                    <span>{{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}</span>
                @endif
                @if($post->comments_count > 0)
                    <span>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
                @endif
                @if($post->shares_count > 0)
                    <span>{{ $post->shares_count }} {{ Str::plural('share', $post->shares_count) }}</span>
                @endif
            </div>
            @if($post->views_count > 0)
                <span>{{ $post->views_count }} {{ Str::plural('view', $post->views_count) }}</span>
            @endif
        </div>
    </div>

    <!-- Post Actions -->
    <div class="px-6 py-3 border-t border-zinc-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Like Button -->
                <button 
                    wire:click="toggleLike"
                    class="flex items-center space-x-2 transition-colors duration-200 {{ $isLiked ? 'text-red-500' : 'text-zinc-400 hover:text-red-500' }}"
                >
                    @if($isLiked)
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    @endif
                    <span>Like</span>
                </button>

                <!-- Comment Button -->
                <button 
                    wire:click="openComments"
                    type="button"
                    class="flex items-center space-x-2 text-zinc-400 hover:text-blue-500 transition-colors duration-200 cursor-pointer group relative z-10"
                >
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>Comment</span>
                    @if($post->comments_count > 0)
                        <span class="text-xs bg-zinc-600 text-zinc-300 px-2 py-1 rounded-full">{{ $post->comments_count }}</span>
                    @endif
                </button>

                <!-- Share Button -->
                <button 
                    wire:click="openShareModal"
                    class="flex items-center space-x-2 text-zinc-400 hover:text-green-500 transition-colors duration-200"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    <span>Share</span>
                </button>

                <!-- Report Button -->
                <button
                    type="button"
                    class="flex items-center space-x-2 text-zinc-400 hover:text-red-500 transition-colors duration-200"
                    onclick="window.Livewire.find('{{ $_instance->id }}').emit('openReport', {{ $post->id }})"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8c0-3.866-3.582-7-8-7S2 4.134 2 8c0 4.418 4.03 8 9 8s9-3.582 9-8z"></path>
                    </svg>
                    <span>Report</span>
                </button>
            </div>

            <!-- Save Button -->
            <button 
                wire:click="toggleSave"
                class="transition-colors duration-200 {{ $isSaved ? 'text-yellow-500' : 'text-zinc-400 hover:text-yellow-500' }}"
            >
                @if($isSaved)
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                @endif
            </button>
        </div>
    </div>

    <!-- Share Modal -->
    @if($showShareModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeShareModal">
            <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-zinc-100">Share Post</h3>
                    <button wire:click="closeShareModal" class="text-zinc-400 hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Share with Message -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Add a message (optional)</label>
                        <textarea
                            wire:model="shareMessage"
                            placeholder="What do you think about this post?"
                            class="w-full px-3 py-2 bg-zinc-700 text-zinc-100 rounded-lg border border-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            rows="3"
                        ></textarea>
                        @error('shareMessage') 
                            <span class="text-red-400 text-xs">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Original Post Preview -->
                    <div class="bg-zinc-700 rounded-lg p-3">
                        <div class="flex items-center space-x-2 mb-2">
                            @if($post->user->avatar)
                                <img class="h-6 w-6 rounded-full object-cover" src="{{ Storage::url($post->user->avatar) }}" alt="{{ $post->user->display_name }}">
                            @else
                                <div class="h-6 w-6 rounded-full bg-zinc-600 flex items-center justify-center">
                                    <span class="text-zinc-200 font-medium text-xs">{{ substr($post->user->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="text-sm font-medium text-zinc-200">{{ $post->user->display_name }}</span>
                        </div>
                        <p class="text-sm text-zinc-300 line-clamp-3">{{ $post->content }}</p>
                    </div>

                    <!-- Share Actions -->
                    <div class="flex space-x-3">
                        <button 
                            wire:click="sharePost"
                            class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Share to Feed
                        </button>
                        <button 
                            wire:click="copyLink"
                            class="bg-zinc-600 text-zinc-200 py-2 px-4 rounded-lg hover:bg-zinc-500 transition-colors"
                        >
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        $wire.on('linkCopied', (event) => {
            navigator.clipboard.writeText(event.link);
            alert('Link copied to clipboard!');
        });

        $wire.on('postShared', () => {
            // Optional: Show a toast notification
        });

        $wire.on('scrollToComments', (event) => {
            // Find the comments section for this post
            const commentsSection = document.querySelector(`[data-post-id="${event.postId}"] .comments-section, #comments-${event.postId}, .post-comments`);
            if (commentsSection) {
                commentsSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
                // Focus on comment input if it exists
                const commentInput = commentsSection.querySelector('textarea, input[type="text"]');
                if (commentInput) {
                    setTimeout(() => commentInput.focus(), 500);
                }
            } else {
                // If no specific comments section found, scroll to bottom of post
                const postElement = document.querySelector(`[data-post-id="${event.postId}"]`);
                if (postElement) {
                    postElement.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'end'
                    });
                }
            }
        });
    </script>
    @endscript
</div>
