<div>
    <!-- Feed Selector -->
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md mb-6">
        <div class="flex border-b border-gray-200">
            <button 
                wire:click="switchFeed('following')"
                class="flex-1 py-4 px-6 text-center font-medium {{ $feedType === 'following' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-zinc-400 hover:text-zinc-300' }}"
            >
                Following
            </button>
            <button 
                wire:click="switchFeed('discover')"
                class="flex-1 py-4 px-6 text-center font-medium {{ $feedType === 'discover' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-zinc-400 hover:text-zinc-300' }}"
            >
                Discover
            </button>
            <button 
                wire:click="switchFeed('trending')"
                class="flex-1 py-4 px-6 text-center font-medium {{ $feedType === 'trending' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-zinc-400 hover:text-zinc-300' }}"
            >
                Trending 🔥
            </button>
        </div>
    </div>

    <!-- Posts Feed -->
    <div class="space-y-6">
        @forelse($posts as $post)
            <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md cursor-pointer hover:bg-zinc-800/50 transition-colors duration-200" 
                 wire:init="incrementViews({{ $post->id }})"
                 onclick="window.location.href='{{ route('posts.show', $post) }}'">
                <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md cursor-pointer hover:bg-zinc-800/50 transition-colors duration-200" 
                     wire:init="incrementViews({{ $post->id }})"
                     onclick="window.location.href='{{ route('posts.show', $post) }}'">
                <!-- Post Header -->
                <div class="p-6 pb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <!-- User Avatar -->
                            @if($post->user->avatar)
                                <img class="h-12 w-12 rounded-full object-cover" src="{{ Storage::url($post->user->avatar) }}" alt="{{ $post->user->display_name }}">
                            @else
                                <div class="h-12 w-12 rounded-full bg-zinc-700 flex items-center justify-center">
                                    <span class="text-zinc-100 font-medium text-lg">{{ substr($post->user->first_name, 0, 1) }}</span>
                                </div>
                            @endif

                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="font-semibold text-zinc-100">{{ $post->user->display_name }}</h3>
                                    @if($post->user->is_verified)
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                    @if($post->user->admin_level)
                                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $post->user->admin_level_name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 text-sm text-zinc-400">
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                    @if($post->location)
                                        <span>•</span>
                                        <span>📍 {{ $post->location }}</span>
                                    @endif
                                    <span class="inline-flex items-center">
                                        • 
                                        @switch($post->visibility)
                                            @case('public')
                                                🌐 Public
                                                @break
                                            @case('friends')
                                                👥 Friends
                                                @break
                                            @case('private')
                                                🔒 Private
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Post Options -->
                        <div class="relative">
                            <button class="text-zinc-400 hover:text-zinc-300" onclick="event.stopPropagation();">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Post Content -->
                <div class="px-6 pb-4">
                    @if($post->isSharedPost() && $post->sharedPost)
                        <!-- Shared Post Indicator -->
                        <div class="flex items-center space-x-2 mb-3 text-sm text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            <span>{{ $post->user->display_name }} shared this post</span>
                        </div>
                        
                        @if($post->content)
                            <p class="text-zinc-100 whitespace-pre-wrap mb-3">{{ $post->content }}</p>
                        @endif

                        <!-- Shared Post Content -->
                        <div class="bg-zinc-800 border border-zinc-600 rounded-lg p-4">
                            <div class="flex items-center space-x-2 mb-2">
                                @if($post->sharedPost->user->avatar)
                                    <img class="h-6 w-6 rounded-full object-cover" src="{{ Storage::url($post->sharedPost->user->avatar) }}" alt="{{ $post->sharedPost->user->display_name }}">
                                @else
                                    <div class="h-6 w-6 rounded-full bg-zinc-700 flex items-center justify-center">
                                        <span class="text-zinc-200 font-medium text-xs">{{ substr($post->sharedPost->user->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-zinc-200">{{ $post->sharedPost->user->display_name }}</span>
                                <span class="text-xs text-zinc-400">{{ $post->sharedPost->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-zinc-200 whitespace-pre-wrap">{{ $post->sharedPost->content }}</p>
                            
                            <!-- Shared Post Media -->
                            @if($post->sharedPost->media_urls && count($post->sharedPost->media_urls) > 0)
                                <div class="mt-3">
                                    @if(count($post->sharedPost->media_urls) === 1)
                                        <img 
                                            src="{{ $post->sharedPost->getImageUrl($post->sharedPost->media_urls[0]) }}" 
                                            alt="Shared post media" 
                                            class="w-full rounded-lg max-h-64 object-cover cursor-pointer"
                                            onclick="event.stopPropagation(); openImageModal('{{ $post->sharedPost->getImageUrl($post->sharedPost->media_urls[0]) }}')"
                                        >
                                    @else
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($post->sharedPost->media_urls as $index => $media)
                                                @if($index < 4)
                                                    <div class="relative">
                                                        <img 
                                                            src="{{ $post->sharedPost->getImageUrl($media) }}" 
                                                            alt="Shared post media {{ $index + 1 }}" 
                                                            class="w-full h-32 object-cover rounded-lg cursor-pointer"
                                                            onclick="event.stopPropagation(); openImageModal('{{ $post->sharedPost->getImageUrl($media) }}')"
                                                        >
                                                        @if(count($post->sharedPost->media_urls) > 4 && $index === 3)
                                                            <div class="absolute inset-0 bg-black bg-opacity-60 rounded-lg flex items-center justify-center">
                                                                <span class="text-white text-xl font-semibold">+{{ count($post->sharedPost->media_urls) - 4 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Regular Post Content -->
                        <p class="text-zinc-100 whitespace-pre-wrap">{{ $post->content }}</p>
                    @endif

                    <!-- Hashtags -->
                    @if($post->hashtags && count($post->hashtags) > 0)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($post->hashtags as $hashtag)
                                <span class="text-blue-400 hover:text-blue-300 cursor-pointer font-medium">#{{ $hashtag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Post Media (only for non-shared posts) -->
                @if(!$post->isSharedPost() && $post->media_urls && count($post->media_urls) > 0)
                    <div class="px-6 pb-4">
                        @php $mediaUrls = $post->getMediaUrls(); @endphp
                        @if(count($mediaUrls) === 1)
                            <img 
                                src="{{ $mediaUrls[0] }}" 
                                alt="Post media" 
                                class="w-full rounded-lg max-h-96 object-cover cursor-pointer"
                                onclick="event.stopPropagation(); openImageModal('{{ $mediaUrls[0] }}')"
                            >
                        @else
                            <div class="grid {{ count($mediaUrls) === 2 ? 'grid-cols-2' : 'grid-cols-2' }} gap-2">
                                @foreach($mediaUrls as $index => $media)
                                    @if($index < 4)
                                        <div class="relative">
                                            <img 
                                                src="{{ $media }}" 
                                                alt="Post media {{ $index + 1 }}" 
                                                class="w-full h-48 object-cover rounded-lg cursor-pointer"
                                                onclick="event.stopPropagation(); openImageModal('{{ $media }}')"
                                            >
                                            @if(count($mediaUrls) > 4 && $index === 3)
                                                <div class="absolute inset-0 bg-black bg-opacity-60 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-xl font-semibold">+{{ count($mediaUrls) - 4 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Post Actions and Comments -->
                <div onclick="event.stopPropagation();">
                    <livewire:post-actions :post="$post" :key="'actions-'.$post->id" />
                    <livewire:post-comments :post="$post" :key="'comments-'.$post->id" />
                </div>
            </div>
        @empty
            <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                <p class="text-gray-500">
                    @if($feedType === 'following')
                        Follow some users to see their posts in your feed.
                    @else
                        Be the first to share something with the community!
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $posts->links() }}
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 items-center justify-center z-50 hidden">
        <div class="relative max-w-4xl max-h-full p-4">
            <button 
                onclick="closeImageModal()" 
                class="absolute top-2 right-2 text-white hover:text-gray-300 z-10"
            >
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    @script
    <script>
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            document.getElementById('modalImage').src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
    @endscript
</div>
