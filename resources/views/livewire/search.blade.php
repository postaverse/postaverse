<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-start justify-start main px-6 lg:px-8">
        @if ($insult)
        <div class="bg-gray-500 text-white p-4 rounded-lg mb-6">
            <h1 class="text-xl font-bold">
                {{ $insult }}
            </h1>
        </div>
        @endif
        @if ($users->isEmpty())
        <div class="w-full mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h1 class="text-xl font-bold text-white">
                    No users found.
                </h1>
            </div>
        </div>
        @else
        <h1 class="text-xl font-bold text-white">Users</h1>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            {{ $users->links() }}
        </div>
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
        <h1 class="text-xl font-bold text-white">
            Posts
        </h1>
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
                @if($profanityOption == 'hide_clickable' && $post->hasProfanity)
                <a href="#" onclick="event.preventDefault(); this.nextElementSibling.style.display='block'; this.style.display='none'">
                    <h1 class="text-xl font-bold text-red-500 hyperlink">
                        Content hidden due to profanity. Click to reveal.
                    </h1>
                </a>
                <h1 class="text-xl font-bold text-white" style="display:none;">
                {{ $post->title }}
                </h1>
                @elseif($profanityOption == 'hide' && $post->hasProfanity)
                <h1 class="text-xl font-bold text-red-500">
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
                <p class="text-white" style="display:none;">{!! $parsedown->text(e($post->content)) !!}</p>
                @elseif($profanityOption == 'hide' && $post->hasProfanity)
                <p class="text-red-500">Content hidden due to profanity.</p>
                @else
                <p class="text-white">{!! $parsedown->text(e($post->content)) !!}</p>
                @endif
            </div>
        </div>
        @endforeach
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>