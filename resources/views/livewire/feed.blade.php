<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>

    <!-- Selector for Feed or Notifications -->
    <div class="flex justify-center mb-4 mt-6">
        <button wire:click="setView('feed')"
            class="px-4 py-2 {{ $view === 'feed' ? 'bg-blue-500 text-white' : 'bg-gray-300' }} rounded-l-lg">
            Feed
        </button>
        <button wire:click="setView('notifications')"
            class="px-4 py-2 {{ $view === 'notifications' ? 'bg-blue-500 text-white' : 'bg-gray-300' }} rounded-r-lg">
            Notifications
        </button>
    </div>

    @if ($view === 'notifications')
        @if (!isset($notifications) || $notifications->isEmpty())
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <h1 class="text-xl font-bold text-white">
                        No notifications found.
                    </h1>
                </div>
            </div>
        @else
            @foreach ($notifications as $notification)
                <a href="{{ $notification->link }}" class="hyperlink">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                        <div wire:key="{{ $notification->id }}"
                            class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $notification->user->profile_photo_url }}"
                                    alt="{{ $notification->user->name }}'s profile photo"
                                    class="w-10 h-10 rounded-full">
                                <h2 class="text-lg font-bold">
                                    {{ $notification->message }}
                                </h2>
                                <h3 class="text-base font-bold">
                                    {{ $notification->created_at->diffForHumans() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    @else
        @if (!isset($posts) || $posts->isEmpty())
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
    @endif

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        @if ($view === 'feed')
            @if (isset($posts))
                {{ $posts->links() }}
            @endif
        @else
            @if (isset($notifications))
                {{ $notifications->links() }}
            @endif
        @endif
    </div>
</div>
