@props(['user'])

<div class="bg-gray-800/50 rounded-lg p-6 border border-gray-600">
    <div class="flex items-center space-x-4 mb-4">
        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
            <span
                class="text-white font-bold text-xl">{{ strtoupper(substr($user->name ?: $user->handle, 0, 1)) }}</span>
        </div>
        <div class="flex-1">
            <h3 class="text-xl font-semibold text-white">{{ $user->name ?: 'No Name Set' }}</h3>
            <p class="text-gray-400">@{{ $user - > handle }} • {{ $user->email }}</p>
            <p class="text-gray-500 text-sm">User ID: {{ $user->id }}</p>
        </div>
        <div class="bg-red-500/20 text-red-300 px-3 py-2 rounded-lg">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 715.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                Currently Banned
            </div>
        </div>
    </div>
</div>
