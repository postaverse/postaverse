@props(['admins', 'adminRanks'])

<div class="space-y-6">
    <!-- List of admins -->
    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
        <h2 class="text-2xl font-bold text-white mb-4">Current Admin Team</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="text-xs uppercase text-gray-400 bg-gray-800/40">
                    <tr>
                        <th scope="col" class="px-6 py-3 rounded-l-lg">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Rank</th>
                        <th scope="col" class="px-6 py-3 rounded-r-lg">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr class="bg-gray-800/20 border-b border-gray-700">
                            <td class="px-6 py-4 font-medium">{{ $admin->id }}</td>
                            <td class="px-6 py-4">{{ $admin->name ?: $admin->handle }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($admin->admin_rank == 1) bg-blue-500/20 text-blue-300
                                    @elseif($admin->admin_rank == 2) bg-green-500/20 text-green-300
                                    @elseif($admin->admin_rank == 3) bg-purple-500/20 text-purple-300
                                    @elseif($admin->admin_rank == 4) bg-indigo-500/20 text-indigo-300
                                    @elseif($admin->admin_rank == 5) bg-red-500/20 text-red-300 @endif">
                                    {{ $adminRanks[$admin->admin_rank]['title'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('user-profile', $admin->id) }}"
                                        class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                        View
                                    </a>
                                    @if (auth()->user()->admin_rank == 4 && $admin->id != auth()->id() && $admin->admin_rank < auth()->user()->admin_rank)
                                        <button wire:click="confirmAction('demote', {{ $admin->id }})"
                                            class="text-red-400 hover:text-red-300 transition-colors">
                                            Demote
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Admin (R4 only) -->
    @if (auth()->user()->admin_rank >= 4)
        <x-admin.add-admin-form :adminRanks="$adminRanks" />
    @endif
</div>
