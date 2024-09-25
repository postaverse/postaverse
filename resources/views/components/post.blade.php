<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6" wire:key="{{ $post->id }}">
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-md p-4 flex">
        <!-- Post Content -->
        <div class="flex-1">
            <div class="flex items-center space-x-4 mb-2">
                <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}'s profile photo"
                    class="w-8 h-8 rounded-full">
                <h2 class="text-sm font-bold text-white">
                    <a href="{{ route('user-profile', $post->user->id) }}" class="hover:underline">
                        {{ $post->user->name }}
                    </a>
                </h2>
                @if ($post->user->isSiteVerified())
                    <img src="{{ asset('images/badges/verified.png') }}" alt="Verified" class="w-4 h-4">
                @endif
                <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
            </div>

            @if (auth()->user())
                @if (isset($profanity))
                    @if ($profanity == 'hide_clickable' && $checker->hasProfanity($post->title))
                        <div>
                            <a href="#"
                                onclick="event.preventDefault(); this.nextElementSibling.style.display='block'; this.style.display='none'">
                                <h1 class="text-lg font-bold text-red-500 hover:underline">
                                    Content hidden due to profanity. Click to reveal.
                                </h1>
                            </a>
                            <h1 class="text-lg font-bold text-white" style="display:none;">
                                {{ $post->title }}
                            </h1>
                        </div>
                    @elseif($profanity == 'hide' && $checker->hasProfanity($post->title))
                        <h1 class="text-lg font-bold text-red-500 mb-2">
                            Content hidden due to profanity.
                        </h1>
                    @else
                        <h1 class="text-xl font-bold text-white mb-2">
                            <a href="{{ route('post', $post->id) }}" class="hover:underline">
                                {{ $post->title }}
                            </a>
                        </h1>
                    @endif
                @else
                    <h1 class="text-xl font-bold text-white mb-2">
                        <a href="{{ route('post', $post->id) }}" class="hover:underline">
                            {{ $post->title }}
                        </a>
                    </h1>
                @endif
            @else
                <h1 class="text-xl font-bold text-white mb-2">
                    <a href="{{ route('post', $post->id) }}" class="hover:underline">
                        {{ $post->title }}
                    </a>
                </h1>
            @endif

            <div class="flex items-center space-x-4 mb-2">
                <p class="text-white text-sm font-bold">
                    {{ $post->comments->count() }} comments
                </p>
            </div>

            @if (auth()->user())
                <div class="flex items-center space-x-4 mb-4">
                    <button wire:click="likePost({{ $post->id }})" class="focus:outline-none">
                        @if (!$post->likes->contains('user_id', auth()->id()))
                            <img src="{{ asset('images/unliked.png') }}" alt="Like" class="w-10 h-10">
                        @else
                            <img src="{{ asset('images/liked.png') }}" alt="Unlike" class="w-10 h-10">
                        @endif
                    </button>
                    <div class="flex -space-x-2">
                        @foreach ($post->likes->take(5) as $like)
                            <a href="{{ route('user-profile', $like->user->id) }}" class="hover:underline">
                                <img src="{{ $like->user->profile_photo_url }}" alt="{{ $like->user->name }}'s profile photo"
                                    class="w-10 h-10 rounded-full border-2 border-gray-800 bg-gray-800">
                            </a>
                        @endforeach
                        @if ($post->likes->count() > 5)
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700 text-white border-2 border-gray-800">
                                +{{ $post->likes->count() - 5 }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if (auth()->user())
                @if ($post->user_id == auth()->user()->id || auth()->user()->admin_rank >= 3)
                    <x-danger-button wire:click="delete({{ $post->id }})">
                        Delete
                    </x-danger-button>
                @endif
            @endif
        </div>
    </div>
</div>