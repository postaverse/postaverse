@props(['user', 'selectMethod'])

<div wire:click="{{ $selectMethod }}({{ $user['id'] }})"
    class="px-4 py-3 hover:bg-gray-700/50 cursor-pointer border-b border-gray-600/50 last:border-b-0 transition-colors">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-indigo-500/30">
                @php
                    $profilePhotoUrl = $user['profile_photo_path'] 
                        ? (filter_var($user['profile_photo_path'], FILTER_VALIDATE_URL) 
                            ? $user['profile_photo_path'] 
                            : asset('storage/' . $user['profile_photo_path']))
                        : 'https://gravatar.com/avatar/'.md5(strtolower($user['email'])).'?s=200&d=mp&d=retro';
                @endphp
                <img src="{{ $profilePhotoUrl }}" 
                     alt="{{ $user['name'] ?: $user['handle'] }}" 
                     class="w-full h-full object-cover">
            </div>
            <div>
                <div class="text-white font-medium">{{ $user['name'] ?: 'No Name' }}</div>
                <div class="text-gray-400 text-sm">{{ $user['handle'] }} • {{ $user['email'] }}</div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-gray-500 text-xs">ID: {{ $user['id'] }}</span>
            @if ($user['admin_rank'] > 0)
                <span class="bg-blue-500/20 text-blue-300 text-xs px-2 py-1 rounded-full">Admin Rank
                    {{ $user['admin_rank'] }}</span>
            @endif
            @if ($user['banned'] ?? false)
                <span class="bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded-full">Already Banned</span>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>
</div>
