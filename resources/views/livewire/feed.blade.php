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
            {{-- Profanity check integration starts here --}}
            @if ($post->hasProfanity)
            @if ($profanityOption === 'hide_clickable')
            <div class="text-white">
                <a class="hyperlink text-red-500" href="#" onclick="event.preventDefault(); this.previousElementSibling.style.display='block'; this.style.display='none'">Content hidden due to profanity. Click to show.</a>
                <div style="display: none;">{!! $parsedown->text(e($post->content)) !!}</div>
            </div>
            @elseif ($profanityOption === 'hide')
            <div class="text-red-500">Content hidden due to profanity.</div>
            @else
            <div class="text-white">{!! $parsedown->text(e($post->content)) !!}</div>
            @endif
            @else
            <div class="text-white">{!! $parsedown->text(e($post->content)) !!}</div>
            @endif
            {{-- Profanity check integration ends here --}}

            <hr>

            <button wire:click="likePost({{ $post->id }})" class="text-white">
                @if ($post->likes->contains('user_id', auth()->id()))
                Unlike
                @else
                Like
                @endif
            </button>
            <br>
            <span class="text-white">{{ $post->likes->count() }} likes</span>
            <br>

            <hr>

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