    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Group Header (branded like dashboard header) -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        @if ($group->avatar)
                            <img src="{{ Storage::url($group->avatar) }}" alt="{{ $group->name }}"
                                class="h-16 w-16 rounded-full object-cover">
                        @else
                            <div class="h-16 w-16 rounded-full bg-zinc-700 flex items-center justify-center">
                                <span class="text-zinc-300 font-bold text-2xl">{{ substr($group->name, 0, 2) }}</span>
                            </div>
                        @endif

                        <div>
                            <h1 class="text-2xl font-bold text-zinc-100">r/{{ $group->name }}</h1>
                            <p class="text-sm text-zinc-400">{{ $group->description }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="text-sm text-zinc-400 text-right">
                            <div>{{ number_format($group->members_count) }} members</div>
                            <div class="text-xs">Created {{ $group->created_at->diffForHumans() }}</div>
                        </div>

                        @auth
                            @if ($isMember)
                                <flux:button wire:click="leaveGroup" variant="outline"
                                    class="bg-zinc-700 hover:bg-zinc-600">
                                    Joined
                                </flux:button>
                            @else
                                <flux:button wire:click="joinGroup" variant="primary">
                                    Join Group
                                </flux:button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Group Info Card -->
                        <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg p-6">
                            <h3 class="font-semibold text-zinc-100 mb-4">About r/{{ $group->name }}</h3>
                            <div class="space-y-3 text-sm text-zinc-400">
                                <div>
                                    <div class="text-zinc-400">Members</div>
                                    <div class="font-medium">{{ number_format($group->members_count) }}</div>
                                </div>
                                <div>
                                    <div class="text-zinc-400">Posts</div>
                                    <div class="font-medium">{{ number_format($group->posts_count) }}</div>
                                </div>
                                @if ($group->category)
                                    <div>
                                        <div class="text-zinc-400">Category</div>
                                        <div class="font-medium">{{ $group->category }}</div>
                                    </div>
                                @endif
                            </div>

                            @if ($group->rules)
                                <div class="mt-6">
                                    <h4 class="font-medium text-zinc-100 mb-2">Rules</h4>
                                    <ol class="space-y-2 text-sm text-zinc-400">
                                        @foreach ($group->rules as $index => $rule)
                                            <li>{{ $index + 1 }}. {{ $rule }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif

                            @if (!$isMember)
                                <div class="mt-6">
                                    <flux:button wire:click="joinGroup" variant="primary" class="w-full">
                                        Join Group
                                    </flux:button>
                                </div>
                            @endif
                        </div>

                        <!-- Quick Links -->
                        <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg p-6">
                            <h4 class="font-semibold text-zinc-100 mb-3">Quick Links</h4>
                            <div class="space-y-2 text-sm">
                                <a href="{{ route('groups') }}"
                                    class="flex items-center space-x-3 text-zinc-300 hover:text-blue-400 transition-colors"
                                    wire:navigate>
                                    <flux:icon.user-group class="w-5 h-5" />
                                    <span>Browse Groups</span>
                                </a>
                                <a href="{{ route('discover-people') }}"
                                    class="flex items-center space-x-3 text-zinc-300 hover:text-blue-400 transition-colors"
                                    wire:navigate>
                                    <flux:icon.users class="w-5 h-5" />
                                    <span>Find Friends</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="space-y-6">
                        <!-- Sorting + Posts -->
                        <div class="bg-zinc-800 border border-zinc-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <button wire:click="$set('sortBy', 'hot')"
                                        class="px-3 py-1 rounded text-sm font-medium transition-colors @if ($sortBy === 'hot') bg-blue-600 text-white @else text-zinc-400 hover:text-zinc-200 @endif">🔥
                                        Hot</button>
                                    <button wire:click="$set('sortBy', 'new')"
                                        class="px-3 py-1 rounded text-sm font-medium transition-colors @if ($sortBy === 'new') bg-blue-600 text-white @else text-zinc-400 hover:text-zinc-200 @endif">🆕
                                        New</button>
                                    <button wire:click="$set('sortBy', 'top')"
                                        class="px-3 py-1 rounded text-sm font-medium transition-colors @if ($sortBy === 'top') bg-blue-600 text-white @else text-zinc-400 hover:text-zinc-200 @endif">⬆️
                                        Top</button>
                                </div>

                                <div class="text-sm text-zinc-400">r/{{ $group->name }} ·
                                    {{ number_format($group->members_count) }} members</div>
                            </div>

                            <div class="space-y-4">
                                @forelse($posts as $post)
                                    @include('components.post-card', ['post' => $post])
                                @empty
                                    <div class="p-6 text-center text-zinc-400">No posts yet in this community.</div>
                                @endforelse
                            </div>

                            @if ($posts->hasPages())
                                <div class="mt-6">
                                    {{ $posts->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
