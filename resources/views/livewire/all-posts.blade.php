<div>
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
            @if($profanityOption == 'hide_clickable' && $post->hasProfanity)
            <a href="#" onclick="this.nextElementSibling.style.display='block'; this.style.display='none'">
                <h1 class="text-xl font-bold text-white hyperlink">
                    Content hidden due to profanity. Click to reveal.
                </h1>
            </a>
            <h1 class="text-xl font-bold text-white" style="display:none;">
                {{ $post->title }}
            </h1>
            @elseif($profanityOption == 'hide' && $post->hasProfanity)
            <h1 class="text-xl font-bold text-white">
                Content hidden due to profanity.
            </h1>
            @else
            <h1 class="text-xl font-bold text-white">
                {{ $post->title }}
            </h1>
            @endif
            <h3 class="text-base font-bold text-white">
                {{ $post->created_at->diffForHumans() }}
            </h3>
            @if($profanityOption == 'hide_clickable' && $post->hasProfanity)
            <p class="text-white cursor-pointer hyperlink" onclick="this.nextElementSibling.style.display='block'; this.style.display='none'">Content hidden due to profanity. Click to reveal.</p>
            <p class="text-white" style="display:none;">{{ $post->content }}</p>
            @elseif($profanityOption == 'hide' && $post->hasProfanity)
            <p class="text-white">Content hidden due to profanity.</p>
            @else
            <p class="text-white">{{ $post->content }}</p>
            @endif

            @if ($post->user_id == auth()->user()->id)
            <button class="text-red-800 font-bold" wire:click="delete({{ $post->id }})">
                Delete
            </button>
            @endif
        </div>
    </div>
    @endforeach

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        {{ $posts->links() }}
    </div>
</div>