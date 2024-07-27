<div>
    <br>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-center justify-center main">
        <br>
        <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="w-40 h-40 rounded-full">
        <br>
        <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
        <br>
        <!-- User Meteor Count -->
        <div class="pl-5 flex items-center shrink-0">
            <img src="{{ asset('images/meteor.png') }}" alt="Meteor" class="w-10 h-10">
            <span class="text-white text-lg font-bold pl-2">{{ $user->meteorQuantity->quantity }}</span>
        </div>
        <br>
        @if ($user->bio !== null)
        <div class="text-white max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">{!! $parsedown->text(e($user->bio)) !!}</div>
        @endif
        <br>
        <div class="flex items-center space-x-4">
            <h2 class="text-lg font-bold text-white">Followers: {{ $user->followers->count() }}</h2>
            <h2 class="text-lg font-bold text-white">Following: {{ $user->following->count() }}</h2>
        </div>
        <br>
        @if (auth()->check() && $user->id !== auth()->id())
        @if ($this->isFollowing())
        <button wire:click="unfollowUser" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Unfollow
        </button>
        @else
        <button wire:click="followUser" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Follow
        </button>
        @endif
        @endif
        <br>
        @if ($user->badges->isNotEmpty())
        <h2 class="text-2xl font-bold text-white pb-2">Patches:</h2>
        <div class="flex flex-wrap justify-center items-center space-x-4">
            @foreach ($user->badges as $badge)
            <div class="text-center pr-5 mb-4">
                <img style="max-width:64px;" src="{{ asset('images/badges/' . $badge->icon) }}" alt="{{ $badge->name }}" title="{{ $badge->name }}">
                <div class="text-white">{{ ucfirst($badge->name) }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
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
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 main">
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
            <p class="text-white bio-img">{!! $parsedown->text(e($post->content)) !!}</p>

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