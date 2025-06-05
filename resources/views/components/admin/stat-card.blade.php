@props(['title', 'value', 'icon', 'color'])

<div class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-gray-400 text-sm">{{ $title }}</p>
            <h3 class="text-white text-2xl font-bold">{{ $value }}</h3>
        </div>
        <div class="bg-{{ $color }}-500/20 rounded-lg p-2">
            @if($icon === 'users')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $color }}-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            @elseif($icon === 'posts')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $color }}-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            @elseif($icon === 'shield')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $color }}-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            @elseif($icon === 'ban')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $color }}-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            @endif
        </div>
    </div>
</div>
