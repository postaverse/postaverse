<div class="min-h-screen bg-zinc-900 text-zinc-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-zinc-100">Analytics Dashboard</h1>
                <p class="text-zinc-400 mt-1">Platform insights and performance metrics</p>
            </div>
            
            <flux:select wire:model.live="timeframe" class="bg-zinc-800 border-zinc-700">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
            </flux:select>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Total Users</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ number_format($totalUsers) }}</p>
                        <p class="text-sm text-green-400">+{{ $newUsers }} new</p>
                    </div>
                    <flux:icon.users class="w-8 h-8 text-blue-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Total Posts</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ number_format($totalPosts) }}</p>
                        <p class="text-sm text-green-400">+{{ $newPosts }} new</p>
                    </div>
                    <flux:icon.document-text class="w-8 h-8 text-green-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Total Engagement</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ number_format($totalLikes + $totalComments) }}</p>
                        <p class="text-sm text-green-400">+{{ $newLikes + $newComments }} new</p>
                    </div>
                    <flux:icon.heart class="w-8 h-8 text-red-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Active Users</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ number_format($activeUsers) }}</p>
                        <p class="text-sm text-zinc-400">{{ round(($activeUsers / $totalUsers) * 100, 1) }}% of total</p>
                    </div>
                    <flux:icon.chart-bar class="w-8 h-8 text-purple-500"/>
                </div>
            </div>
        </div>

        <!-- Activity Chart -->
        <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700 mb-8">
            <h3 class="text-lg font-semibold text-zinc-100 mb-4">Daily Activity (Last 7 Days)</h3>
            <div class="grid grid-cols-7 gap-4">
                @foreach($dailyActivity as $day)
                    <div class="text-center">
                        <div class="text-xs text-zinc-400 mb-2">{{ $day['date'] }}</div>
                        <div class="space-y-2">
                            <div class="bg-blue-600 h-8 rounded flex items-end justify-center relative" style="height: {{ max(($day['posts'] / 10) * 32, 8) }}px;">
                                <span class="text-xs text-white">{{ $day['posts'] }}</span>
                            </div>
                            <div class="text-xs text-zinc-400">Posts</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Top Users by Posts -->
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <h3 class="text-lg font-semibold text-zinc-100 mb-4">Top Content Creators</h3>
                <div class="space-y-4">
                    @foreach($topUsersByPosts as $index => $user)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 rounded-full bg-zinc-600 flex items-center justify-center text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div class="w-8 h-8 bg-zinc-600 rounded-full flex items-center justify-center">
                                    <flux:icon.user class="w-4 h-4 text-zinc-300"/>
                                </div>
                                <div>
                                    <div class="font-medium text-zinc-100">{{ $user->username }}</div>
                                    @if($user->admin_level)
                                        <div class="text-xs text-purple-400">{{ $user->admin_level_name }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-zinc-400">{{ $user->posts_count }} posts</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Users by Followers -->
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <h3 class="text-lg font-semibold text-zinc-100 mb-4">Most Followed Users</h3>
                <div class="space-y-4">
                    @foreach($topUsersByFollowers as $index => $user)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 rounded-full bg-zinc-600 flex items-center justify-center text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div class="w-8 h-8 bg-zinc-600 rounded-full flex items-center justify-center">
                                    <flux:icon.user class="w-4 h-4 text-zinc-300"/>
                                </div>
                                <div>
                                    <div class="font-medium text-zinc-100">{{ $user->username }}</div>
                                    @if($user->admin_level)
                                        <div class="text-xs text-purple-400">{{ $user->admin_level_name }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-zinc-400">{{ $user->followers_count }} followers</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Popular Posts -->
        <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-100 mb-4">Most Popular Posts</h3>
            <div class="space-y-4">
                @foreach($popularPosts as $post)
                    <div class="flex items-start justify-between p-4 bg-zinc-700/50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-6 h-6 bg-zinc-600 rounded-full flex items-center justify-center">
                                    <flux:icon.user class="w-3 h-3 text-zinc-300"/>
                                </div>
                                <span class="font-medium text-zinc-100">{{ $post->user->username }}</span>
                                <span class="text-zinc-500 text-sm">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-zinc-300 text-sm line-clamp-2">{{ Str::limit($post->content, 100) }}</p>
                        </div>
                        <div class="flex space-x-4 text-sm text-zinc-400 ml-4">
                            <div class="flex items-center space-x-1">
                                <flux:icon.heart class="w-4 h-4"/>
                                <span>{{ $post->likes_count }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <flux:icon.chat-bubble-left class="w-4 h-4"/>
                                <span>{{ $post->comments_count }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700 text-center">
                <flux:icon.chat-bubble-left class="w-8 h-8 text-blue-500 mx-auto mb-2"/>
                <p class="text-2xl font-bold text-zinc-100">{{ number_format($totalComments) }}</p>
                <p class="text-zinc-400 text-sm">Total Comments</p>
                <p class="text-green-400 text-xs">+{{ $newComments }} this period</p>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700 text-center">
                <flux:icon.users class="w-8 h-8 text-green-500 mx-auto mb-2"/>
                <p class="text-2xl font-bold text-zinc-100">{{ number_format($totalGroups) }}</p>
                <p class="text-zinc-400 text-sm">Total Groups</p>
                <p class="text-green-400 text-xs">+{{ $newGroups }} this period</p>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700 text-center">
                <flux:icon.heart class="w-8 h-8 text-red-500 mx-auto mb-2"/>
                <p class="text-2xl font-bold text-zinc-100">{{ number_format($totalLikes) }}</p>
                <p class="text-zinc-400 text-sm">Total Likes</p>
                <p class="text-green-400 text-xs">+{{ $newLikes }} this period</p>
            </div>
        </div>
    </div>
</div>
