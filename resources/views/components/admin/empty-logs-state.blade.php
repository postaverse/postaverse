@props(['searchTerm'])

<div class="flex flex-col items-center text-gray-400">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    @if (strlen($searchTerm ?? '') > 0)
        <p class="text-lg">No logs found matching "{{ $searchTerm }}"</p>
        <button wire:click="$set('searchTerm', '')" class="mt-2 text-indigo-400 hover:text-indigo-300 transition-colors">
            Clear search to view all logs
        </button>
    @else
        <p class="text-lg">No admin activity logs found</p>
        <p class="text-sm mt-1">Admin actions will appear here once performed</p>
    @endif
</div>
