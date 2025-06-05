@props(['logs', 'searchTerm'])

<div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold text-white">Admin Activity Logs</h2>
            @if (strlen($searchTerm ?? '') > 0)
                <p class="text-sm text-gray-400 mt-1">
                    Showing search results for "<span class="text-indigo-300">{{ $searchTerm }}</span>"
                    @if ($logs->total() > 0)
                        - {{ $logs->total() }} {{ $logs->total() === 1 ? 'result' : 'results' }} found
                    @endif
                </p>
            @endif
        </div>

        <x-admin.logs-search :searchTerm="$searchTerm" />
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs uppercase text-gray-400 bg-gray-800/40">
                <tr>
                    <th scope="col" class="px-6 py-3 rounded-l-lg">Admin</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                    <th scope="col" class="px-6 py-3 rounded-r-lg">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr class="bg-gray-800/20 border-b border-gray-700">
                        <td class="px-6 py-4 font-medium">
                            <div class="flex flex-col">
                                @if ($log->admin)
                                    <span class="text-white font-medium">{!! $this->highlightSearchTerm($log->admin->name ?: $log->admin->handle) !!}</span>
                                    <span class="text-gray-400 text-xs">ID: {!! $this->highlightSearchTerm($log->admin_id) !!}</span>
                                @else
                                    <span class="text-red-400">Admin ID: {!! $this->highlightSearchTerm($log->admin_id) !!}</span>
                                    <span class="text-gray-500 text-xs">(User deleted)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="break-words">{!! $this->highlightSearchTerm($log->action) !!}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-300">{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="text-gray-500 text-xs">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center">
                            <x-admin.empty-logs-state :searchTerm="$searchTerm" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
