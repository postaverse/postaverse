@props(['bannedUsers'])

<div class="space-y-6">
    <!-- Success/Error Messages -->
    <x-admin.session-messages />

    <!-- Banned Users List -->
    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
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
                                                <span class="inline-block bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded">
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
