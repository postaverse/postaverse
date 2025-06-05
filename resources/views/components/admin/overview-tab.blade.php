@props(['adminRanks'])

<div
    class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
    <h2 class="text-2xl font-bold text-white mb-4">Admin Ranks & Permissions</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs uppercase text-gray-400 bg-gray-800/40">
                <tr>
                    <th scope="col" class="px-6 py-3 rounded-l-lg">Rank</th>
                    <th scope="col" class="px-6 py-3">Title</th>
                    <th scope="col" class="px-6 py-3 rounded-r-lg">Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adminRanks as $rank => $details)
                    @if ($rank > 0)
                        <tr class="bg-gray-800/20 border-b border-gray-700">
                            <td class="px-6 py-4 font-medium">{{ $rank }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($rank == 1) bg-blue-500/20 text-blue-300
                                    @elseif($rank == 2) bg-green-500/20 text-green-300
                                    @elseif($rank == 3) bg-purple-500/20 text-purple-300
                                    @elseif($rank == 4) bg-indigo-500/20 text-indigo-300
                                    @elseif($rank == 5) bg-red-500/20 text-red-300 @endif">
                                    {{ $details['title'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($rank == 1)
                                    View dashboard, manage reports, view logs, delete comments
                                @elseif($rank == 2)
                                    All previous + manage users, delete posts
                                @elseif($rank == 3)
                                    All previous + permanent bans, unbans
                                @elseif($rank == 4)
                                    All previous + whitelist emails, manage admins + blog posting
                                @elseif($rank == 5)
                                    All previous + overlord status
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
