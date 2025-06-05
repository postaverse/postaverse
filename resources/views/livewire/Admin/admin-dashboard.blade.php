<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Header with Stats -->
            <x-admin.header-stats :statistics="$statistics" :adminRanks="$adminRanks" />

            <!-- Tab Navigation -->
            <x-admin.tab-navigation :activeTab="$activeTab" />

            <!-- Tab Content -->
            <div>
                <!-- Overview Tab -->
                @if ($activeTab === 'overview')
                    <x-admin.overview-tab :adminRanks="$adminRanks" />
                @endif

                <!-- Admins Tab -->
                @if ($activeTab === 'admins')
                    <x-admin.admins-tab :admins="$admins" :adminRanks="$adminRanks" />
                @endif

                <!-- Users Tab -->
                @if ($activeTab === 'users')
                    <x-admin.users-tab :bannedUsers="$bannedUsers" />
                @endif

                <!-- Ban User Tab (R3+) -->
                @if ($activeTab === 'ban' && auth()->user()->admin_rank >= 3)
                    <x-admin.ban-user-tab :searchResults="$searchResults" :selectedUser="$selectedUser" />
                @endif

                <!-- Unban User Tab (R3+) -->
                @if ($activeTab === 'unban' && auth()->user()->admin_rank >= 3)
                    <x-admin.unban-user-tab :selectedUnbanUser="$selectedUnbanUser" />
                @endif

                <!-- Logs Tab -->
                @if ($activeTab === 'logs')
                    <x-admin.logs-tab :logs="$logs" :searchTerm="$searchTerm" />
                @endif

                <!-- Whitelist Tab (R4+) -->
                @if ($activeTab === 'whitelist' && auth()->user()->admin_rank >= 4 && config('whitelisting.enabled'))
                    <x-admin.whitelist-tab />
                @endif
            </div>
        </div>
    </div>
</div>
