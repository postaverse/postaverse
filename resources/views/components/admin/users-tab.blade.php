@props(['bannedUsers', 'searchResults', 'selectedUser', 'selectedUnbanUser', 'bannedSearchResults'])

<div class="space-y-6">
    <!-- Success/Error Messages -->
    <x-admin.session-messages />

    <!-- User Management (R3+) -->
    @if (auth()->user()->admin_rank >= 3)
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
                    placeholder="Search by name, username handle, email, or ID..." focus-color="red" :searchResults="$searchResults ?? []"
                    selectMethod="selectUserForBan" />

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
                    resetAction="resetBanForm"
                    confirmAction="confirmBanUser" confirmText="Ban User" confirmLoadingText="Banning..." confirmColor="red"
                    iconType="ban" />
            </div>
        </div>

        <!-- Unban User Section (R3+) -->
        <div
            class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="bg-green-500/20 rounded-lg p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Unban User</h2>
                    <p class="text-gray-400 mt-1">Search and unban users to restore their platform access</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Enhanced Banned User Search -->
                <x-admin.banned-user-search :bannedSearchResults="$bannedSearchResults ?? []" />

                <!-- Selected User for Unbanning -->
                @if ($selectedUnbanUser)
                    <x-admin.selected-unban-user :user="$selectedUnbanUser" />

                    <!-- Unban Information -->
                    <div class="bg-green-900/20 border border-green-500/30 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 mt-0.5 mr-3 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="text-green-300 font-semibold">Unban Action</h4>
                                <p class="text-green-200 text-sm mt-1">
                                    This will restore the user's access and remove all associated IP bans.
                                    The user will be able to log in and use the platform normally again.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <x-admin.action-buttons resetAction="resetUnbanForm"
                    confirmAction="confirmUnbanUser" confirmText="Unban User" confirmLoadingText="Unbanning..."
                    confirmColor="green" iconType="unban" />
            </div>
        </div>
    @endif

    <!-- Banned Users List -->
    <div
        class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
        <h2 class="text-2xl font-bold text-white mb-4">Banned Users</h2>

        @if ($bannedUsers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-300">
                    <thead class="text-xs uppercase text-gray-400 bg-gray-800/40">
                        <tr>
                            <th scope="col" class="px-6 py-3 rounded-l-lg">ID</th>
                            <th scope="col" class="px-6 py-3">Username</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Banned on</th>
                            <th scope="col" class="px-6 py-3">Reason</th>
                            <th scope="col" class="px-6 py-3">Banned IPs</th>
                            <th scope="col" class="px-6 py-3 rounded-r-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bannedUsers as $banned)
                            <tr class="bg-gray-800/20 border-b border-gray-700">
                                <td class="px-6 py-4 font-medium">{{ $banned->user->id }}</td>
                                <td class="px-6 py-4">{{ $banned->user->name ?: $banned->user->handle }}</td>
                                <td class="px-6 py-4">{{ $banned->user->email }}</td>
                                <td class="px-6 py-4">{{ $banned->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 max-w-xs truncate">{{ $banned->reason }}</td>
                                <td class="px-6 py-4">
                                    @if ($banned->bannedIps->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($banned->bannedIps as $bannedIp)
                                                <span
                                                    class="inline-block bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded">
                                                    {{ $bannedIp->ip_address }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-500 text-xs">No IPs banned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <button wire:click="openUnbanModal({{ $banned->user_id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600/80 hover:bg-green-600 text-white text-xs font-medium rounded-lg transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Unban
                                        </button>
                                        <div class="flex items-center text-xs text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            ID: {{ $banned->user->id }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $bannedUsers->links() }}
                </div>
            </div>
        @else
            <p class="text-gray-400 text-center py-4">No banned users found.</p>
        @endif
    </div>
</div>
