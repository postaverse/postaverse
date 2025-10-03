<div class="min-h-screen bg-zinc-900 text-zinc-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-100">Content Moderation</h1>
            <p class="text-zinc-400 mt-1">Manage reported content and user behavior</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Pending Reports</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ $reports->count() }}</p>
                    </div>
                    <flux:icon.flag class="w-8 h-8 text-red-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Total Posts</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ \App\Models\Post::count() }}</p>
                    </div>
                    <flux:icon.document-text class="w-8 h-8 text-blue-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Active Users</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ \App\Models\User::where('is_suspended', false)->count() }}</p>
                    </div>
                    <flux:icon.users class="w-8 h-8 text-green-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Suspended Users</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ \App\Models\User::where('is_suspended', true)->count() }}</p>
                    </div>
                    <flux:icon.no-symbol class="w-8 h-8 text-red-500"/>
                </div>
            </div>
        </div>

        <!-- Pending Reports Section -->
        @if($reports->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-zinc-100 mb-4">Pending Reports</h2>
                <div class="space-y-4">
                    @foreach($reports as $report)
                        <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <flux:icon.flag class="w-4 h-4 text-red-500"/>
                                        <span class="font-medium text-zinc-100">{{ $report->type }}</span>
                                        <span class="text-zinc-500">•</span>
                                        <span class="text-zinc-400 text-sm">{{ $report->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-zinc-300 mb-2">{{ $report->reason }}</p>
                                    <div class="text-sm text-zinc-500">
                                        Reported by: <span class="text-zinc-300">{{ $report->user->username }}</span>
                                        @if($report->reportedUser)
                                            • Against: <span class="text-zinc-300">{{ $report->reportedUser->username }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <flux:button size="sm" variant="outline">Review</flux:button>
                                    <flux:button size="sm" variant="danger">Remove</flux:button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="flex space-x-1 mb-6 bg-zinc-800 p-1 rounded-lg w-fit">
            <button 
                wire:click="$set('filter', 'all')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'all' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
            >
                All Posts
            </button>
            <button 
                wire:click="$set('filter', 'reported')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'reported' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
            >
                Reported Posts
            </button>
            <button 
                wire:click="$set('filter', 'flagged')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'flagged' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
            >
                Flagged Content
            </button>
        </div>

        <!-- Posts List -->
        <div class="space-y-4">
            @foreach($posts as $post)
                <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-zinc-600 rounded-full flex items-center justify-center">
                                <flux:icon.user class="w-5 h-5 text-zinc-300"/>
                            </div>
                            <div>
                                <h3 class="font-medium text-zinc-100">{{ $post->user->username }}</h3>
                                <p class="text-sm text-zinc-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            @if($post->reports->count() > 0)
                                <span class="bg-red-800 text-red-200 text-xs px-2 py-1 rounded-full">
                                    {{ $post->reports->count() }} reports
                                </span>
                            @endif
                            
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" variant="ghost" wire:click="removePost({{ $post->id }})" class="text-red-400 hover:text-red-300 hover:bg-red-500/10">
                                    Remove Post
                                </flux:button>
                                <flux:button size="sm" variant="ghost" wire:click="suspendUser({{ $post->user->id }})" class="text-orange-400 hover:text-orange-300 hover:bg-orange-500/10">
                                    Suspend User
                                </flux:button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-zinc-100 mb-4">
                        {{ $post->content }}
                    </div>
                    
                    @if($post->image_path)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $post->image_path) }}" 
                                 alt="Post image" 
                                 class="w-full h-auto rounded-lg max-h-64 object-cover">
                        </div>
                    @endif
                    
                    <div class="flex items-center text-sm text-zinc-500 space-x-4">
                        <span>{{ $post->likes()->count() }} likes</span>
                        <span>{{ $post->comments()->count() }} comments</span>
                        @if($post->reports->count() > 0)
                            <span class="text-red-400">{{ $post->reports->count() }} reports</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>
