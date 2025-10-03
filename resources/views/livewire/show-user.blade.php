<div class="max-w-4xl mx-auto">
    {{-- Profile Header --}}
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md mb-6">
        <div class="relative">
            {{-- Cover Photo --}}
            <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 rounded-t-lg"></div>
            
            {{-- Profile Info --}}
            <div class="relative px-6 py-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-5">
                    {{-- Avatar --}}
                    <div class="relative">
                        @if($user->avatar)
                            <img class="h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover shadow-lg shadow-amber-800" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->display_name }}">
                        @else
                            <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-full shadow-lg shadow-amber-800 bg-zinc-700 flex items-center justify-center">
                                <svg class="h-12 w-12 sm:h-16 sm:w-16 text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    {{-- User Info --}}
                    <div class="mt-6 sm:mt-0 sm:flex-1 sm:min-w-0">
                        <div class="flex items-center space-x-3">
                            <h1 class="text-2xl font-bold text-zinc-100 truncate">{{ $user->display_name }}</h1>
                            @if($user->admin_level > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    @if($user->admin_level === 1) Super Admin
                                    @elseif($user->admin_level === 2) Admin
                                    @else Moderator @endif
                                </span>
                            @endif
                            @if($user->is_verified)
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <p class="text-zinc-400">{{ "@" . $user->username }}</p>
                        @if($user->bio)
                            <p class="mt-2 text-zinc-300">{{ $user->bio }}</p>
                        @endif
                        
                        {{-- User Stats --}}
                        <div class="mt-4 flex space-x-6 text-sm text-zinc-400">
                            <div class="flex items-center space-x-1">
                                <flux:icon.book-open-text class="w-4 h-4" />
                                <span>{{ $user->posts()->count() }} posts</span>
                            </div>
                            <a href="{{ route('user.followers', $user->username) }}" wire:navigate class="flex items-center space-x-1 hover:text-blue-400 transition-colors">
                                <flux:icon.users class="w-4 h-4" />
                                <span>{{ $user->followers()->count() }} followers</span>
                            </a>
                            <a href="{{ route('user.following', $user->username) }}" wire:navigate class="flex items-center space-x-1 hover:text-blue-400 transition-colors">
                                <flux:icon.user-plus class="w-4 h-4" />
                                <span>{{ $user->following()->count() }} following</span>
                            </a>
                        </div>
                        
                        {{-- Additional Info --}}
                        <div class="mt-4 flex flex-wrap gap-4 text-sm text-zinc-400">
                            @if($user->location)
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z"/>
                                    </svg>
                                    <span>{{ $user->location }}</span>
                                </div>
                            @endif
                            @if($user->website)
                                <a href="{{ $user->website }}" target="_blank" class="flex items-center space-x-1 hover:text-blue-400 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $user->website }}</span>
                                </a>
                            @endif
                            @if($user->created_at)
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Joined {{ $user->created_at->format('M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    @auth
                        @if(auth()->id() !== $user->id)
                            <div class="mt-6 sm:mt-0 flex space-x-3">
                                @if($isFollowing)
                                    <button wire:click="unfollow" class="flex items-center px-4 py-2 border border-zinc-600 rounded-lg text-sm font-medium text-zinc-300 bg-zinc-800 hover:bg-zinc-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/>
                                        </svg>
                                        Unfollow
                                    </button>
                                @else
                                    <button wire:click="follow" class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-medium text-white transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                        Follow
                                    </button>
                                @endif
                                {{--
                                <button class="flex items-center px-4 py-2 border border-zinc-600 rounded-lg text-sm font-medium text-zinc-300 bg-zinc-800 hover:bg-zinc-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Message
                                </button>
                                --}}
                            </div>
                        @else
                            <div class="mt-6 sm:mt-0 flex space-x-3">
                                <a href="{{ route('settings.profile') }}" class="flex items-center px-4 py-2 border border-zinc-600 rounded-lg text-sm font-medium text-zinc-300 bg-zinc-800 hover:bg-zinc-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Profile
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Navigation --}}
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md mb-6">
        <div class="border-b border-zinc-700">
            <nav class="-mb-px flex space-x-8 ml-8" aria-label="Tabs">
                <a href="#" class="border-blue-500 text-blue-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Posts ({{ $user->posts()->count() }})
                </a>
                <a href="#" class="border-transparent text-zinc-400 hover:text-zinc-300 hover:border-zinc-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Media
                </a>
                <a href="#" class="border-transparent text-zinc-400 hover:text-zinc-300 hover:border-zinc-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Likes
                </a>
            </nav>
        </div>
    </div>

    {{-- Posts Feed --}}
    <div class="space-y-6">
        @if($user->posts()->count() > 0)
            @foreach($posts as $post)
                <livewire:post-feed-item :post="$post" :key="'post-' . $post->id" />
            @endforeach
            
            {{-- Pagination --}}
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @else
            <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-zinc-100">No posts yet</h3>
                <p class="mt-1 text-sm text-zinc-400">
                    @if(auth()->id() === $user->id)
                        You haven't posted anything yet. Share your first post!
                    @else
                        {{ $user->display_name }} hasn't posted anything yet.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
