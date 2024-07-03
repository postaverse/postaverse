<div>
    <br>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
            @foreach ($blogPosts as $blogPost)
            <div wire:key="{{ $blogPost->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <div class="flex items center space-x-4">
                    <img src="{{ $blogPost->user->profile_photo_url }}" alt="{{ $blogPost->user->name }}'s profile photo" class="w-10 h-10 rounded-full">
                    <h2 class="text-lg font-bold text-white">
                        <a href="{{ route('user-profile', $blogPost->user->id) }}" class="hyperlink">
                            {{ $blogPost->user->name }}
                        </a>
                    </h2>
                </div>
                <h1 class="text-xl font-bold text-white">
                    {{ $blogPost->title }}
                </h1>
                <h3 class="text-base font-bold text-white">
                    {{ $blogPost->created_at->diffForHumans() }}
                </h3>
                <div class="text-white">{{ $blogPost->content }}</div>
                @if ($blogPost->user_id == auth()->user()->id)
                <button class="text-red-800 font-bold" wire:click="delete({{ $blogPost->id }})">
                    Delete
                </button>
                @endif
            </div>
        </div>
    </div>
</div>