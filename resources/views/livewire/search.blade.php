<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-start justify-start main px-6 lg:px-8">
        @if ($users->isEmpty())
        <div class="w-full mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h1 class="text-xl font-bold text-white">
                    No users found.
                </h1>
            </div>
        </div>
        @else
        @foreach ($users as $user)
        <div class="w-full mb-6">
            <div wire:key="{{ $user->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}'s profile photo" class="w-10 h-10 rounded-full">
                    <h2 class="text-lg font-bold text-white">
                        <a href="{{ route('user-profile', $user->id) }}" class="hyperlink">
                            {{ $user->name }}
                        </a>
                    </h2>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        @if ($posts->isEmpty())
        <div class="w-full mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h1 class="text-xl font-bold text-white">
                    No posts found.
                </h1>
            </div>
        </div>
        @else
        @foreach ($posts as $post)
        <div class="w-full mb-6">
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
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>