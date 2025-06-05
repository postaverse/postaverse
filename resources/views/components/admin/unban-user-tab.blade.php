@props(['selectedUnbanUser'])

<div class="space-y-6">
    <!-- Enhanced Success/Error Messages -->
    <x-admin.session-messages />

    <!-- Unban User Interface -->
    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
        <div class="flex items-center mb-6">
            <div class="bg-green-500/20 rounded-lg p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <x-admin.banned-user-search />

            <!-- Selected User for Unbanning -->
            @if ($selectedUnbanUser)
                <x-admin.selected-unban-user :user="$selectedUnbanUser" />
                
                <!-- Unban Information -->
                <div class="bg-green-900/20 border border-green-500/30 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-green-400 mt-0.5 mr-3 flex-shrink-0"
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
            <x-admin.action-buttons 
                resetAction="$set('selectedUnbanUser', null); $set('unban_user_search', '')"
                confirmAction="confirmUnbanUser"
                confirmText="Unban User"
                confirmLoadingText="Unbanning..."
                confirmColor="green"
                iconType="unban"
                :disabled="!$selectedUnbanUser" />
        </div>
    </div>
</div>
