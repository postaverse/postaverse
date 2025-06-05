@props(['user'])

<div class="bg-gray-800/50 rounded-lg p-6 border border-gray-600">
    <div class="flex items-center space-x-4 mb-4">
        <div class="w-16 h-16 rounded-full overflow-hidden ring-4 ring-indigo-500/30">
            <img src="{{ $user->profile_photo_url }}" 
                 alt="{{ $user->name ?: $user->handle }}" 
                 class="w-full h-full object-cover">
        </div>
        <div class="flex-1">
            <h3 class="text-xl font-semibold text-white">{{ $user->name ?: 'No Name Set' }}</h3>
            <p class="text-gray-400">{{ $user->handle }} • {{ $user->email }}</p>
            <p class="text-gray-500 text-sm">User ID: {{ $user->id }}</p>
        </div>
        @if ($user->admin_rank > 0)
            <div class="bg-red-500/20 text-red-300 px-3 py-2 rounded-lg">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Admin Rank {{ $user->admin_rank }}
                </div>
            </div>
        @endif
    </div>
</div>
