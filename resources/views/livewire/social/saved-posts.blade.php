<div class="min-h-screen bg-zinc-900 text-zinc-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-100">Saved Posts</h1>
            <p class="text-zinc-400 mt-1">Posts you've bookmarked for later</p>
        </div>

        @if($savedPosts->count() > 0)
            <div class="space-y-6">
                @foreach($savedPosts as $post)
                    <div class="relative">
                        <!-- Post Card Component -->
                        <x-post-card :post="$post" :showActions="true" :clickable="true" />
                        
                        <!-- Saved Post Actions Overlay -->
                        <div class="absolute top-4 right-4 flex items-center space-x-2">
                            <flux:button size="sm" variant="ghost" wire:click="unsavePost({{ $post->id }})" class="text-red-400 hover:text-red-300 hover:bg-red-500/10 bg-zinc-800/90 backdrop-blur-sm border border-zinc-700 flex items-center whitespace-nowrap">
                                <flux:icon.bookmark-slash class="w-4 h-4 mr-2"/>
                                Remove from Saved
                            </flux:button>
                        </div>
                        
                        <!-- Saved Date -->
                        <div class="absolute bottom-4 right-4 text-xs text-zinc-400 bg-zinc-800/90 backdrop-blur-sm border border-zinc-700 px-2 py-1 rounded">
                            Saved {{ $post->pivot->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $savedPosts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <flux:icon.bookmark class="w-16 h-16 text-zinc-600 mx-auto mb-4"/>
                <h3 class="text-lg font-medium text-zinc-300 mb-2">No saved posts yet</h3>
                <p class="text-zinc-500 mb-6">Start saving posts you want to read later by clicking the bookmark icon</p>
                <flux:button href="/dashboard" variant="outline">
                    Explore Posts
                </flux:button>
            </div>
        @endif
    </div>
</div>
