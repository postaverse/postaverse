<div>
    @if ($blogs->isEmpty())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h1 class="text-xl font-bold text-white">
                    No blogs found.
                </h1>
            </div>
        </div>
    @else
        @foreach ($blogs as $blog)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div wire:key="{{ $blog->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $blog->user->profile_photo_url }}" alt="{{ $blog->user->name }}'s profile photo"
                            class="w-10 h-10 rounded-full">
                        <h2 class="text-lg font-bold text-white">
                            <a href="{{ route('user-profile', $blog->user->id) }}" class="hyperlink">
                                {{ $blog->user->name }}
                            </a>
                        </h2>
                        @if ($blog->user->isSiteVerified())
                            <img src="{{ asset('images/badges/verified.png') }}" alt="Verified" width="20"
                                height="20">
                        @endif
                    </div>
                    <h1 class="text-xl font-bold text-white">
                        {{ $blog->title }}
                    </h1>
                    <h3 class="text-base font-bold text-white">
                        {{ $blog->created_at->diffForHumans() }}
                    </h3>
                    <p class="text-white">{!! $parsedown->text(e($blog->content)) !!}</p>
                    @if (auth()->user())
                        @if ($blog->user_id == auth()->user()->id || auth()->user()->admin_rank == 4)
                            <button class="text-red-800 font-bold" wire:click="delete({{ $blog->id }})">
                                Delete
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
