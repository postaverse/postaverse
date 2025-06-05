@props(['resetAction', 'confirmAction', 'confirmText', 'confirmLoadingText', 'confirmColor', 'disabled' => false, 'iconType' => 'default'])

<div class="flex items-center justify-between pt-4 border-t border-gray-600">
    <button wire:click="{{ $resetAction }}"
        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Reset
    </button>

    <button wire:click="{{ $confirmAction }}" wire:loading.attr="disabled"
        @if($disabled) disabled @endif
        class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-{{ $confirmColor }}-600 to-{{ $confirmColor }}-700 hover:from-{{ $confirmColor }}-700 hover:to-{{ $confirmColor }}-800 disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-lg transition-all duration-200 disabled:opacity-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            @if($iconType === 'ban')
                <circle cx="12" cy="12" r="10"/>
                <path d="m4.9 4.9 14.2 14.2"/>
            @elseif($iconType === 'unban')
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            @endif
        </svg>
        <span wire:loading.remove>{{ $confirmText }}</span>
        <span wire:loading>{{ $confirmLoadingText }}</span>
    </button>
</div>
