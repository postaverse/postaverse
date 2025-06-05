@props([
    '            <div class="bg-red-500/20 rounded-lg p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m4.9 4.9 14.2 14.2"/>
                </svg>
            </div>Results',
    'selectedUser',
])

<div class="space-y-6">
    <!-- Enhanced Success/Error Messages -->
    <x-admin.session-messages />

    <!-- Ban User Interface -->
    <div
        class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
        <div class="flex items-center mb-6">
            <div class="bg-red-500/20 rounded-lg p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Ban User</h2>
                <p class="text-gray-400 mt-1">Search and ban users from accessing the platform</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Enhanced User Search -->
            <x-admin.user-search model="ban_user_search"
                placeholder="Search by name, username (@handle), email, or ID..." focus-color="red" :searchResults="$searchResults ?? []"
                searchMethod="searchUsers" selectMethod="selectUserForBan" />

            <!-- Selected User Display -->
            @if ($selectedUser)
                <x-admin.selected-user-display :user="$selectedUser" />
            @endif

            <!-- Ban Reason -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Ban Reason *</label>
                <textarea wire:model="reason"
                    class="w-full bg-gray-800/50 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-red-500 focus:ring-red-500/20 focus:ring-2 transition-all resize-none"
                    rows="4" placeholder="Provide a detailed reason for the ban. This will be visible to the user." required></textarea>
                @error('reason')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warning Notice -->
            @if ($selectedUser)
                <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h4 class="text-yellow-300 font-semibold">Warning: Ban Action</h4>
                            <p class="text-yellow-200 text-sm mt-1">
                                This action will immediately ban the user
                                <strong>{{ $selectedUser->name ?: $selectedUser->handle }}</strong>
                                and all their associated IP addresses.
                                The user will be logged out and unable to access the platform. This
                                action can be reversed by unbanning the user later.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <x-admin.action-buttons
                resetAction="$set('selectedUser', null); $set('reason', ''); $set('ban_user_search', '')"
                confirmAction="confirmBanUser" confirmText="Ban User" confirmLoadingText="Banning..." confirmColor="red"
                iconType="ban" :disabled="!$selectedUser || !isset($reason)" />
        </div>
    </div>
</div>
