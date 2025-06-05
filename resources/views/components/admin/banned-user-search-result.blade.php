@props(['user'])

<div wire:click="selectUserForUnban({{ $user->id }})"
    class="px-4 py-3 hover:bg-gray-700/50 cursor-pointer border-b border-gray-600/50 last:border-b-0 transition-colors">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
                <span
                    class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name ?: $user->handle, 0, 1)) }}</span>
            </div>
            <div>
                <div class="text-white font-medium">{{ $user->name ?: 'No Name' }}</div>
                <div class="text-gray-400 text-sm">@{{ $user - > handle }} • {{ $user->email }}</div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-gray-500 text-xs">ID: {{ $user->id }}</span>
            <span class="bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded-full">Banned</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>
</div>
