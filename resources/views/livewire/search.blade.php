<!-- Search Results -->
<div>
    <div class="flex flex-col space-y-4">
        @if ($searchResults->count() > 0)
            @foreach ($searchResults as $result)
                <div class="flex items
                -center space-x-4">
                    <img src="{{ $result->profile_photo_url }}" alt="{{ $result->name }}'s profile photo" class="w-10 h-10 rounded-full">
                    <h2 class="text-lg font-bold text-white">
                        <a href="{{ route('user-profile', $result->id) }}" class="hyperlink">
                            {{ $result->name }}
                        </a>
                    </h2>
                </div>
            @endforeach
        @else
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <h1 class="text-xl font-bold text-white">
                        No users found.
                    </h1>
                </div>
            </div>
        @endif
    </div>
</div>