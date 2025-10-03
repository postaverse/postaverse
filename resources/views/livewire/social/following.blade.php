<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                {{-- Back Button --}}
                <button onclick="window.history.back()" class="text-zinc-400 hover:text-zinc-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                {{-- User Avatar --}}
                @if($user->avatar)
                    <img class="h-16 w-16 rounded-full object-cover" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->display_name }}">
                @else
                    <div class="h-16 w-16 rounded-full bg-zinc-700 flex items-center justify-center">
                        <span class="text-zinc-100 font-medium text-xl">{{ substr($user->username, 0, 1) }}</span>
                    </div>
                @endif
                
                <div>
                    <h1 class="text-2xl font-bold text-zinc-100">{{ $user->display_name }}</h1>
                    <p class="text-zinc-400">{{ "@" . $user->username }}</p>
                </div>
            </div>
        </div>
        
        {{-- Navigation Tabs --}}
        <div class="flex space-x-1 bg-zinc-800 rounded-lg p-1">
            <a href="{{ route('user.followers', $user->username) }}" 
               class="flex-1 text-center py-2 px-4 rounded-md text-sm font-medium text-zinc-400 hover:text-zinc-300 hover:bg-zinc-700 transition-colors">
                Followers
            </a>
            <a href="{{ route('user.following', $user->username) }}" 
               class="flex-1 text-center py-2 px-4 rounded-md text-sm font-medium bg-blue-600 text-white">
                Following
            </a>
        </div>
    </div>

    {{-- Following List --}}
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg shadow-md">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-zinc-100 mb-4">
                Following ({{ $user->following_count }})
            </h2>
            
            @if($following->count() > 0)
                <div class="space-y-4">
                    @foreach($following as $followedUser)
                        <div class="flex items-center justify-between p-4 bg-zinc-800 rounded-lg">
                            <div class="flex items-center space-x-3">
                                {{-- Avatar --}}
                                <a href="{{ route('user.profile', $followedUser->username) }}" wire:navigate>
                                    @if($followedUser->avatar)
                                        <img class="h-12 w-12 rounded-full object-cover" src="{{ Storage::url($followedUser->avatar) }}" alt="{{ $followedUser->display_name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-zinc-700 flex items-center justify-center">
                                            <span class="text-zinc-100 font-medium">{{ substr($followedUser->username, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </a>
                                
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('user.profile', $followedUser->username) }}" 
                                           class="font-semibold text-zinc-100 hover:text-blue-400 transition-colors" 
                                           wire:navigate>
                                            {{ $followedUser->display_name }}
                                        </a>
                                        @if($followedUser->is_verified)
                                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        @if($followedUser->admin_level > 0)
                                            <span class="bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                                @if($followedUser->admin_level === 1) Super Admin
                                                @elseif($followedUser->admin_level === 2) Admin
                                                @else Moderator @endif
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-zinc-400 text-sm">{{ "@" . $followedUser->username }}</p>
                                    @if($followedUser->bio)
                                        <p class="text-zinc-300 text-sm mt-1">{{ Str::limit($followedUser->bio, 60) }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Follow/Unfollow Button --}}
                            @auth
                                @if($followedUser->id !== auth()->id())
                                    @if(auth()->user()->following()->where('following_id', $followedUser->id)->exists())
                                        <button wire:click="toggleFollow({{ $followedUser->id }})" 
                                                class="px-4 py-2 bg-zinc-600 text-zinc-200 rounded-lg hover:bg-zinc-700 transition-colors text-sm font-medium">
                                            Following
                                        </button>
                                    @else
                                        <button wire:click="toggleFollow({{ $followedUser->id }})" 
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                            Follow
                                        </button>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $following->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-zinc-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-zinc-300 mb-2">Not following anyone yet</h3>
                    <p class="text-zinc-400">{{ $user->display_name }} isn't following anyone yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
