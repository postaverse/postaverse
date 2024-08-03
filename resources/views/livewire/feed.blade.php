<div>
    <br>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>
    <br>
    @if ($posts->isEmpty())
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
            <h1 class="text-xl font-bold text-white">
                No posts found.
            </h1>
        </div>
    </div>
    @else
    @foreach ($posts as $post)
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div wire:key="{{ $post->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
            <div class="flex items-center space-x-4">
                <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}'s profile photo" class="w-10 h-10 rounded-full">
                <h2 class="text-lg font-bold text-white">
                    <a href="{{ route('user-profile', $post->user->id) }}" class="hyperlink">
                        {{ $post->user->name }}
                    </a>
                </h2>
                @if ($post->user->isSiteVerified())
                <img src="{{ asset('images/badges/verified.png') }}" alt="Verified" width="20" height="20">
                @endif
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">
                        {{ $post->title }}
                    </h1>
                    <h3 class="text-base font-bold text-white">
                        {{ $post->created_at->diffForHumans() }}
                    </h3>
                </div>
                <a href="{{ route('post', $post->id) }}" class="text-white">
                    <img src="{{ asset('images/external-link.png') }}" alt="Go to post" width="20" height="20" style="filter: invert(1);">
                </a>
            </div>
            @if (auth()->user())
            <div class="flex items-center space-x-4 pt-3 pb-3">
                <button wire:click="likePost({{ $post->id }})" class="text-white" id="likeButton">
                    @if (!$post->likes->contains('user_id', auth()->id()))
                    <img src="{{ asset('images/unliked.png') }}" alt="Unlike" width="35" height="35" class="p-1">
                    @else
                    <img src="{{ asset('images/liked.png') }}" alt="Like" width="35" height="35" class="p-1">
                    @endif
                </button>
                <div class="flex -space-x-4">
                    @foreach ($post->likes->take(5) as $like)
                    <a href="{{ route('user-profile', $like->user->id) }}" class="hyperlink">
                        <img src="{{ $like->user->profile_photo_url }}" alt="{{ $like->user->name }}'s profile photo" class="w-10 h-10 rounded-full border-2 border-gray-800">
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

            @if ($post->user_id == auth()->user()->id)
            <button class="text-red-800 font-bold" wire:click="delete({{ $post->id }})">
                Delete
            </button>
            @endif
        </div>
    </div>
    @endforeach
    @endif

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        {{ $posts->links() }}
    </div>
</div>