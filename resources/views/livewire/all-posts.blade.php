<div>
    @foreach ($posts as $post)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div wire:key="{{ $post->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h1 class="text-xl font-bold text-white">
                    {{ $post->title }}
                </h1>
                <h2 class="text-xl font-bold text-white">
                    {{ $post->user->name  }}
                </h2>
                <h3 class="text-xl font-bold text-white">
                    {{ $post->created_at->diffForHumans() }} -
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

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        {{ $posts->links() }}
    </div>
</div>
