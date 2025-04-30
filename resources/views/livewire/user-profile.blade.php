<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-center justify-center main py-6 space-y-6">
        <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="w-40 h-40 rounded-full">
        
        <div class="flex items-center">
            <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
        </div>
        
        <h2 class="text-xl font-bold text-white">{!! $site !!}</h2>
        
        @if ($user->bio !== null)
            <div class="text-white max-w-7xl mx-auto sm:px-6 lg:px-8">{!! $parsedown->text(e($user->bio())) !!}</div>
        @endif
        
        <div class="flex items-center space-x-4">
            <h2 class="text-lg font-bold text-white">Followers: {{ $user->followers()->count() }}</h2>
            <h2 class="text-lg font-bold text-white">Following: {{ $user->following()->count() }}</h2>
            <p class="text-gray-600 ml-4">({{ $user->id }})</p>
        </div>
        
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
    </div>
    
    <div class="mt-8">
        @if ($posts->isEmpty())
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-lg shadow-sm p-4 hover:border-white/30 transition-colors duration-200">
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
</div>
