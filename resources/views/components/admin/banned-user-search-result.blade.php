@props(['user'])

<div wire:click="selectUserForUnban({{ is_array($user) ? $user['id'] : $user->id }})"
    class="px-4 py-3 hover:bg-gray-700/50 cursor-pointer border-b border-gray-600/50 last:border-b-0 transition-colors">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-red-500/30">
                @if(is_array($user))
                    <img src="{{ $user['profile_photo_url'] ?? asset('images/default-avatar.png') }}" 
                         alt="{{ $user['name'] ?: $user['handle'] }}" 
                         class="w-full h-full object-cover">
                @else
                    <img src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->name ?: $user->handle }}" 
                         class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                @if(is_array($user))
                    <div class="text-white font-medium">{{ $user['name'] ?: 'No Name' }}</div>
                    <div class="text-gray-400 text-sm">{{ $user['handle'] }} • {{ $user['email'] }}</div>
                @else
                    <div class="text-white font-medium">{{ $user->name ?: 'No Name' }}</div>
                    <div class="text-gray-400 text-sm">{{ $user->handle }} • {{ $user->email }}</div>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-gray-500 text-xs">ID: {{ is_array($user) ? $user['id'] : $user->id }}</span>
            <span class="bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded-full">Banned</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>
</div>
