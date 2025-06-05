@props(['statistics', 'adminRanks'])

<!-- Admin Header with Stats -->
<div
    class="mb-8 bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Welcome,
                @if (!auth()->user()->name)
                    {{ auth()->user()->handle }}
                @else
                    {{ auth()->user()->name }}
                @endif
            </h1>
            <div
                class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if (auth()->user()->admin_rank == 1) bg-blue-500/20 text-blue-300
                @elseif(auth()->user()->admin_rank == 2) bg-green-500/20 text-green-300
                @elseif(auth()->user()->admin_rank == 3) bg-purple-500/20 text-purple-300
                @elseif(auth()->user()->admin_rank == 4) bg-indigo-500/20 text-indigo-300
                @elseif(auth()->user()->admin_rank == 5) bg-red-500/20 text-red-300 @endif">
                {{ $adminRanks[auth()->user()->admin_rank]['title'] }}
            </div>
        </div>
        <a href="/pulse"
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 !text-white rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Pulse Dashboard
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <x-admin.stat-card title="Total Users" value="{{ $statistics['users']['total'] }}" icon="users"
            color="indigo" />

        <x-admin.stat-card title="Total Posts" value="{{ $statistics['content']['posts'] }}" icon="posts"
            color="blue" />

        <x-admin.stat-card title="Admins" value="{{ $statistics['users']['admins'] }}" icon="shield" color="purple" />

        <x-admin.stat-card title="Banned Users" value="{{ $statistics['users']['banned'] }}" icon="ban"
            color="red" />
    </div>
</div>
