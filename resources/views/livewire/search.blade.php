<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            <h1 class="text-xl font-bold text-white mb-4">Users</h1>
            @foreach ($users as $user)
                <div class="w-full mb-6">
                    <div wire:key="{{ $user->id }}" class="bg-gray-800 border border-gray-700 rounded-lg shadow-md p-4 flex">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}'s profile photo"
                                class="w-8 h-8 rounded-full">
                            <h2 class="text-sm font-bold text-white">
                                <a href="{{ route('user-profile', $user->id) }}" class="hover:underline">
                                    {{ $user->name }}
                                </a>
                            </h2>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="w-full mb-6">
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
            <h1 class="text-xl font-bold text-white mb-4">
                Posts
            </h1>
            @foreach ($posts as $post)
                <div class="w-full mb-6">
                    <x-post :post="$post" />
                </div>
            @endforeach
            <div class="w-full mb-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>