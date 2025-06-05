@props(['searchTerm'])

<div class="relative w-80">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
    <input wire:model.debounce.300ms="searchTerm" type="text"
        class="w-full border-white/20 bg-gray-800/10 backdrop-blur-sm text-gray-300 pl-10 pr-10 py-2 focus:border-white/30 focus:ring-indigo-600 rounded-md shadow-sm"
        placeholder="Search by admin name, ID, or action..." wire:loading.attr="disabled" wire:target="searchTerm">

    <!-- Loading indicator -->
    <div wire:loading wire:target="searchTerm" class="absolute inset-y-0 right-8 flex items-center pr-2">
        <svg class="animate-spin h-4 w-4 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    </div>

    @if (strlen($searchTerm ?? '') > 0)
        <button wire:click="$set('searchTerm', '')"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-white transition-colors"
            wire:loading.class="hidden" wire:target="searchTerm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
