@props([''])

<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">Search Banned User</label>
    <div class="relative">
        <input type="text" wire:model.debounce.300ms="unban_user_search"
            class="w-full bg-gray-800/50 border border-gray-600 rounded-lg px-4 py-3 pr-12 text-white placeholder-gray-400 focus:border-green-500 focus:ring-green-500/20 focus:ring-2 transition-all"
            placeholder="Search banned users by name, username (@handle), email, or ID..." autocomplete="off" />
        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        @if (strlen($unban_user_search ?? '') > 0)
            <button wire:click="$set('unban_user_search', '')"
                class="absolute inset-y-0 right-8 flex items-center pr-2 text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>

    <!-- Banned Users Search Results -->
    @if (strlen($unban_user_search ?? '') >= 2)
        <div class="mt-3 bg-gray-800/70 border border-gray-600 rounded-lg shadow-lg max-h-80 overflow-y-auto">
            @php $bannedSearchResults = $this->searchBannedUsers(); @endphp
            @if (count($bannedSearchResults) > 0)
                <div class="p-2 bg-gray-700/50 border-b border-gray-600">
                    <p class="text-sm text-gray-300">{{ count($bannedSearchResults) }} banned user(s) found</p>
                </div>
                @foreach ($bannedSearchResults as $user)
                    <x-admin.banned-user-search-result :user="$user" />
                @endforeach
            @else
                <div class="p-4 text-center">
                    <div class="text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 opacity-50" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>No banned users found matching "{{ $unban_user_search }}"</p>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
