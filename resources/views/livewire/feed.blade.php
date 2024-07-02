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
            </div>
            <h1 class="text-xl font-bold text-white">
                {{ $post->title }}
            </h1>
            <h3 class="text-base font-bold text-white">
                {{ $post->created_at->diffForHumans() }}
            </h3>
            <p class="text-white">{{ $post->content }}</p>

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