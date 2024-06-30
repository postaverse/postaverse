<div>
    @foreach ($posts as $post)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div wire:key="{{ $post->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h1 class="text-xl font-bold text-gray-900">
                    {{ $post->title }} -
                    {{ $post->created_at->diffForHumans() }} -
                    {{ $post->user->name  }}
                </h1>

                <p class="text-gray-900">{{ $post->content }}</p>

                @if ($post->user_id == auth()->user()->id)
                    <button class="text-gray-900" wire:click="delete({{ $post->id }})">
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
