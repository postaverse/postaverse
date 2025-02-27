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
        <div class="flex items-center">
            @php
                $glitchTheme = $user->textThemes->firstWhere('theme_name', 'glitch');
                $waveTheme = $user->textThemes->firstWhere('theme_name', 'wave');
                $flippedTheme = $user->textThemes->firstWhere('theme_name', 'flipped');
                // dd($user->textThemes, $glitchTheme);
            @endphp
            @if ($glitchTheme && $glitchTheme->pivot->equipped == 1)
                <h1 class="glitch text-3xl font-bold text-white" data-text="{{ $user->name }}">{{ $user->name }}</h1>
            @elseif ($waveTheme && $waveTheme->pivot->equipped == 1)
                <div class="wave">
                    <h2 class="text-5xl font-bold">{{ $user->name }}</h2>
                    <h2 class="text-5xl font-bold">{{ $user->name }}</h2>
                </div>
            @elseif ($flippedTheme && $flippedTheme->pivot->equipped == 1)
                <h1 class="flipped text-3xl font-bold text-white">{{ $user->name }}</h1>
            @else
                <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
            @endif
        </div>
        <br>
        <h2 class="text-xl font-bold text-white">{!! $site !!}</h2>
        <br>
        <!-- User Meteor Count -->
        <div class="pl-5 flex items-center shrink-0">
            <img src="{{ asset('images/icons/meteor.png') }}" alt="Meteor" class="w-10 h-10">
            <span class="text-white text-lg font-bold pl-2">{{ $user->meteorQuantity->quantity }}</span>
        </div>
        <br>
        @if ($user->bio !== null)
            <div class="text-white max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">{!! $parsedown->text(e($user->bio())) !!}</div>
        @endif
        <br>
        <div class="flex items-center space-x-4">
            <h2 class="text-lg font-bold text-white">Followers: {{ $user->followers->count() }}</h2>
            <h2 class="text-lg font-bold text-white">Following: {{ $user->following->count() }}</h2>
            <p class="text-gray-600 ml-4">({{ $user->id }})</p>
        </div>
        <br>
        @if (auth()->check() && $user->id !== auth()->id())
            @if ($this->isFollowing())
                <button wire:click="unfollowUser"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Unfollow
                </button>
            @else
                <button wire:click="followUser"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
                        <img style="max-width:64px;" src="{{ asset('images/badges/' . $badge->icon) }}"
                            alt="{{ $badge->name }}" title="{{ $badge->name }}">
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
            <x-post :post="$post" />
        @endforeach
    @endif
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        {{ $posts->links() }}
    </div>
</div>
