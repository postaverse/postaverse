<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <div
                        class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Total Users</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['users']['total'] }}</h3>
                            </div>
                            <div class="bg-indigo-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Total Posts</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['content']['posts'] }}</h3>
                            </div>
                            <div class="bg-blue-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Admins</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['users']['admins'] }}</h3>
                            </div>
                            <div class="bg-purple-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Banned Users</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['users']['banned'] }}</h3>
                            </div>
                            <div class="bg-red-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-700 mb-6">
                <nav class="flex flex-wrap -mb-px">
                    <button wire:click="setActiveTab('overview')"
                        class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        Overview
                    </button>
                    <button wire:click="setActiveTab('admins')"
                        class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'admins' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        Admin Management
                    </button>
                    <button wire:click="setActiveTab('users')"
                        class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'users' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        User Management
                    </button>
                    @if (auth()->user()->admin_rank >= 3)
                        <button wire:click="setActiveTab('ban')"
                            class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'ban' ? 'border-red-500 text-red-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Ban User
                        </button>
                        <button wire:click="setActiveTab('unban')"
                            class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'unban' ? 'border-green-500 text-green-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Unban User
                        </button>
                    @endif
                    <button wire:click="setActiveTab('logs')"
                        class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'logs' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        Logs
                    </button>
                    @if (auth()->user()->admin_rank >= 4 && config('whitelisting.enabled'))
                        <button wire:click="setActiveTab('whitelist')"
                            class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'whitelist' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                            Email Whitelist
                        </button>
                    @endif
                </nav>
            </div>

            <!-- Tab Content -->
            <div>
                <!-- Overview Tab -->
                @if ($activeTab === 'overview')
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
                @endif

                <!-- Admins Tab -->
                @if ($activeTab === 'admins')
                    <div class="space-y-6">
                        <!-- List of admins -->
                        <div
                            class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
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
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                                            <button
                                                                wire:click="confirmAction('demote', {{ $admin->id }})"
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
                            <div
                                class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                                <h2 class="text-2xl font-bold text-white mb-4">Add or Modify Admin</h2>

                                @if (session()->has('addmessage'))
                                    <div
                                        class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                                        <strong class="font-bold">Success!</strong>
                                        <span class="block sm:inline">{{ session('addmessage') }}</span>
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div
                                        class="mb-4 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
                                        <strong class="font-bold">Error:</strong>
                                        <span class="block sm:inline">{{ session('error') }}</span>
                                    </div>
                                @endif

                                <form wire:submit.prevent="addAdmin" class="max-w-lg mx-auto">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <x-label for="admin_id" :value="__('User ID')" />
                                            <x-input id="admin_id" class="block mt-1 w-full" type="text"
                                                name="admin_id" wire:model="admin_id" required />
                                            @error('admin_id')
                                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <x-label for="admin_rank" :value="__('Admin Rank')" />
                                            <x-select id="admin_rank" wire:model="admin_rank">
                                                <option value="">Select a rank</option>
                                                @foreach ($adminRanks as $rank => $details)
                                                    @if ($rank > 0 && $rank < auth()->user()->admin_rank)
                                                        <option value="{{ $rank }}">{{ $rank }} -
                                                            {{ $details['title'] }}</option>
                                                    @endif
                                                @endforeach
                                            </x-select>
                                            @error('admin_rank')
                                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <x-button class="w-full justify-center">
                                                {{ __('Update Admin Status') }}
                                            </x-button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Users Tab -->
                @if ($activeTab === 'users')
                    <div class="space-y-6">
                        <!-- Success/Error Messages -->
                        @if (session()->has('banmessage'))
                            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-green-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">User Banned Successfully!</strong>
                                        <p class="text-sm mt-1">{{ session('banmessage') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session()->has('unbanmessage'))
                            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-green-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">User Unbanned Successfully!</strong>
                                        <p class="text-sm mt-1">{{ session('unbanmessage') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 text-red-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">Error!</strong>
                                        <p class="text-sm mt-1">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Enhanced Success/Error Messages -->
                        @if (session()->has('banmessage'))
                            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-green-400 mb-6">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">User Banned Successfully!</strong>
                                        <p class="text-sm mt-1">{{ session('banmessage') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Legacy Ban User Form (Hidden by default, keeping for fallback) -->
                        <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300"
                            style="display: none;">
                            <h2 class="text-2xl font-bold text-white mb-4">Ban User (Legacy)</h2>

                            @if (session()->has('banmessage'))
                                <div
                                    class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                                    <strong class="font-bold">Success!</strong>
                                    <span class="block sm:inline">{{ session('banmessage') }}</span>
                                </div>
                            @endif

                            <form wire:submit.prevent="banUser" class="max-w-lg mx-auto">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <x-label for="user_id" :value="__('User ID')" />
                                        <x-input id="user_id" class="block mt-1 w-full" type="text"
                                            name="user_id" wire:model="user_id" required />
                                        @error('user_id')
                                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-label for="reason" :value="__('Reason')" />
                                        <textarea id="reason" name="reason" wire:model="reason" required
                                            class="border-white/20 bg-gray-800/10 backdrop-blur-sm text-gray-300 focus:border-white/30 focus:ring-indigo-600 rounded-md shadow-sm w-full mt-1"
                                            rows="3"></textarea>
                                        @error('reason')
                                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-button class="w-full justify-center bg-red-500 hover:bg-red-600">
                                            {{ __('Ban User') }}
                                        </x-button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Legacy Unban User (Hidden by default, keeping for fallback) -->
                        <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300"
                            style="display: none;">
                            <h2 class="text-2xl font-bold text-white mb-4">Unban User (Legacy)</h2>

                            @if (session()->has('unbanmessage'))
                                <div
                                    class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                                    <strong class="font-bold">Success!</strong>
                                    <span class="block sm:inline">{{ session('unbanmessage') }}</span>
                                </div>
                            @endif

                            <form wire:submit.prevent="unbanUser" class="max-w-lg mx-auto">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <x-label for="uid" :value="__('User ID')" />
                                        <x-input id="uid" class="block mt-1 w-full" type="text"
                                            name="uid" wire:model="uid" required />
                                        @error('uid')
                                            <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700">
                                            {{ __('Unban User') }}
                                        </x-button>
                                    </div>
                                </div>
                            </form>
                        </div>

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
                                                    <td class="px-6 py-4">
                                                        {{ $banned->user->name ?: $banned->user->handle }}</td>
                                                    <td class="px-6 py-4">{{ $banned->user->email }}</td>
                                                    <td class="px-6 py-4">{{ $banned->created_at->format('M d, Y') }}
                                                    </td>
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
                                                            <button
                                                                wire:click="openUnbanModal({{ $banned->user_id }})"
                                                                class="inline-flex items-center px-3 py-1.5 bg-green-600/80 hover:bg-green-600 text-white text-xs font-medium rounded-lg transition-colors duration-200">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Unban
                                                            </button>
                                                            <div class="flex items-center text-xs text-gray-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-4 w-4 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
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
                @endif

                <!-- Ban User Tab (R3+) -->
                @if ($activeTab === 'ban' && auth()->user()->admin_rank >= 3)
                    <div class="space-y-6">
                        <!-- Enhanced Success/Error Messages -->
                        @if (session()->has('banmessage'))
                            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-green-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">User Banned Successfully!</strong>
                                        <p class="text-sm mt-1">{{ session('banmessage') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 text-red-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">Error!</strong>
                                        <p class="text-sm mt-1">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Ban User Interface -->
                        <div
                            class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-center mb-6">
                                <div class="bg-red-500/20 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Search User</label>
                                    <div class="relative">
                                        <input type="text" wire:model.debounce.300ms="ban_user_search"
                                            wire:keyup="searchUsers"
                                            class="w-full bg-gray-800/50 border border-gray-600 rounded-lg px-4 py-3 pr-12 text-white placeholder-gray-400 focus:border-red-500 focus:ring-red-500/20 focus:ring-2 transition-all"
                                            placeholder="Search by name, username (@handle), email, or ID..."
                                            autocomplete="off" />
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        @if (strlen($ban_user_search ?? '') > 0)
                                            <button wire:click="$set('ban_user_search', '')"
                                                class="absolute inset-y-0 right-8 flex items-center pr-2 text-gray-400 hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Search Results -->
                                    @if (count($searchResults ?? []) > 0)
                                        <div
                                            class="mt-3 bg-gray-800/70 border border-gray-600 rounded-lg shadow-lg max-h-80 overflow-y-auto">
                                            <div class="p-2 bg-gray-700/50 border-b border-gray-600">
                                                <p class="text-sm text-gray-300">{{ count($searchResults) }} user(s)
                                                    found</p>
                                            </div>
                                            @foreach ($searchResults as $user)
                                                <div wire:click="selectUserForBan({{ $user['id'] }})"
                                                    class="px-4 py-3 hover:bg-gray-700/50 cursor-pointer border-b border-gray-600/50 last:border-b-0 transition-colors">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-3">
                                                            <div
                                                                class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                                                <span
                                                                    class="text-white font-semibold text-sm">{{ strtoupper(substr($user['name'] ?: $user['handle'], 0, 1)) }}</span>
                                                            </div>
                                                            <div>
                                                                <div class="text-white font-medium">
                                                                    {{ $user['name'] ?: 'No Name' }}</div>
                                                                <div class="text-gray-400 text-sm">
                                                                    {{ $user['handle'] }} • {{ $user['email'] }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-gray-500 text-xs">ID:
                                                                {{ $user['id'] }}</span>
                                                            @if ($user['admin_rank'] > 0)
                                                                <span
                                                                    class="bg-blue-500/20 text-blue-300 text-xs px-2 py-1 rounded-full">Admin
                                                                    Rank {{ $user['admin_rank'] }}</span>
                                                            @endif
                                                            @if ($user['banned'] ?? false)
                                                                <span
                                                                    class="bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded-full">Already
                                                                    Banned</span>
                                                            @endif
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(strlen($ban_user_search ?? '') >= 2)
                                        <div
                                            class="mt-3 bg-gray-800/70 border border-gray-600 rounded-lg p-4 text-center">
                                            <div class="text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-8 w-8 mx-auto mb-2 opacity-50" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p>No users found matching "{{ $ban_user_search }}"</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Selected User Display -->
                                @if ($selectedUser)
                                    <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-600">
                                        <div class="flex items-center space-x-4 mb-4">
                                            <div
                                                class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-white font-bold text-xl">{{ strtoupper(substr($selectedUser->name ?: $selectedUser->handle, 0, 1)) }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-xl font-semibold text-white">
                                                    {{ $selectedUser->name ?: 'No Name Set' }}</h3>
                                                <p class="text-gray-400">@{{ $selectedUser - > handle }} •
                                                    {{ $selectedUser->email }}</p>
                                                <p class="text-gray-500 text-sm">User ID: {{ $selectedUser->id }}</p>
                                            </div>
                                            @if ($selectedUser->admin_rank > 0)
                                                <div class="bg-red-500/20 text-red-300 px-3 py-2 rounded-lg">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        Admin Rank {{ $selectedUser->admin_rank }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
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
                                                class="h-6 w-6 text-yellow-400 mt-0.5 mr-3 flex-shrink-0"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                <div class="flex items-center justify-between pt-4 border-t border-gray-600">
                                    <button
                                        wire:click="$set('selectedUser', null); $set('reason', ''); $set('ban_user_search', '')"
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </button>

                                    <button wire:click="confirmBanUser" wire:loading.attr="disabled"
                                        :disabled="!$selectedUser || !$reason"
                                        class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-lg transition-all duration-200 disabled:opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span wire:loading.remove>Ban User</span>
                                        <span wire:loading>Banning...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Unban User Tab (R3+) -->
                @if ($activeTab === 'unban' && auth()->user()->admin_rank >= 3)
                    <div class="space-y-6">
                        <!-- Enhanced Success/Error Messages -->
                        @if (session()->has('unbanmessage'))
                            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-green-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">User Unbanned Successfully!</strong>
                                        <p class="text-sm mt-1">{{ session('unbanmessage') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 text-red-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div>
                                        <strong class="font-bold">Error!</strong>
                                        <p class="text-sm mt-1">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Unban User Interface -->
                        <div
                            class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-center mb-6">
                                <div class="bg-green-500/20 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Unban User</h2>
                                    <p class="text-gray-400 mt-1">Search and unban users to restore their platform
                                        access</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Enhanced Banned User Search -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Search Banned
                                        User</label>
                                    <div class="relative">
                                        <input type="text" wire:model.debounce.300ms="unban_user_search"
                                            class="w-full bg-gray-800/50 border border-gray-600 rounded-lg px-4 py-3 pr-12 text-white placeholder-gray-400 focus:border-green-500 focus:ring-green-500/20 focus:ring-2 transition-all"
                                            placeholder="Search banned users by name, username (@handle), email, or ID..."
                                            autocomplete="off" />
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        @if (strlen($unban_user_search ?? '') > 0)
                                            <button wire:click="$set('unban_user_search', '')"
                                                class="absolute inset-y-0 right-8 flex items-center pr-2 text-gray-400 hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Banned Users Search Results -->
                                    @if (strlen($unban_user_search ?? '') >= 2)
                                        <div
                                            class="mt-3 bg-gray-800/70 border border-gray-600 rounded-lg shadow-lg max-h-80 overflow-y-auto">
                                            @php $bannedSearchResults = $this->searchBannedUsers(); @endphp
                                            @if (count($bannedSearchResults) > 0)
                                                <div class="p-2 bg-gray-700/50 border-b border-gray-600">
                                                    <p class="text-sm text-gray-300">{{ count($bannedSearchResults) }}
                                                        banned user(s) found</p>
                                                </div>
                                                @foreach ($bannedSearchResults as $user)
                                                    <div wire:click="selectUserForUnban({{ $user->id }})"
                                                        class="px-4 py-3 hover:bg-gray-700/50 cursor-pointer border-b border-gray-600/50 last:border-b-0 transition-colors">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center space-x-3">
                                                                <div
                                                                    class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                                                    <span
                                                                        class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name ?: $user->handle, 0, 1)) }}</span>
                                                                </div>
                                                                <div>
                                                                    <div class="text-white font-medium">
                                                                        {{ $user->name ?: 'No Name' }}</div>
                                                                    <div class="text-gray-400 text-sm">
                                                                        @{{ $user - > handle }} • {{ $user->email }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="text-gray-500 text-xs">ID:
                                                                    {{ $user->id }}</span>
                                                                <span
                                                                    class="bg-red-500/20 text-red-300 text-xs px-2 py-1 rounded-full">Banned</span>
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-4 w-4 text-gray-400" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 5l7 7-7 7" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="p-4 text-center">
                                                    <div class="text-gray-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-8 w-8 mx-auto mb-2 opacity-50" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <p>No banned users found matching "{{ $unban_user_search }}"
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Selected User for Unbanning -->
                                @if ($selectedUnbanUser)
                                    <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-600">
                                        <div class="flex items-center space-x-4 mb-4">
                                            <div
                                                class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-white font-bold text-xl">{{ strtoupper(substr($selectedUnbanUser->name ?: $selectedUnbanUser->handle, 0, 1)) }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-xl font-semibold text-white">
                                                    {{ $selectedUnbanUser->name ?: 'No Name Set' }}</h3>
                                                <p class="text-gray-400">@{{ $selectedUnbanUser - > handle }} •
                                                    {{ $selectedUnbanUser->email }}</p>
                                                <p class="text-gray-500 text-sm">User ID: {{ $selectedUnbanUser->id }}
                                                </p>
                                            </div>
                                            <div class="bg-red-500/20 text-red-300 px-3 py-2 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                    Currently Banned
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                    This will restore the user's access and remove all associated IP
                                                    bans.
                                                    The user will be able to log in and use the platform normally again.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-600">
                                    <button wire:click="$set('selectedUnbanUser', null); $set('unban_user_search', '')"
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </button>

                                    <button wire:click="confirmUnbanUser" wire:loading.attr="disabled"
                                        :disabled="!$selectedUnbanUser"
                                        class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-lg transition-all duration-200 disabled:opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span wire:loading.remove>Unban User</span>
                                        <span wire:loading>Unbanning...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Logs Tab -->
                @if ($activeTab === 'logs')
                    <div
                        class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
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

                            <div class="relative w-80">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input wire:model.debounce.300ms="searchTerm" type="text"
                                    class="w-full border-white/20 bg-gray-800/10 backdrop-blur-sm text-gray-300 pl-10 pr-10 py-2 focus:border-white/30 focus:ring-indigo-600 rounded-md shadow-sm"
                                    placeholder="Search by admin name, ID, or action..."
                                    wire:loading.attr="disabled"
                                    wire:target="searchTerm">
                                
                                <!-- Loading indicator -->
                                <div wire:loading wire:target="searchTerm" class="absolute inset-y-0 right-8 flex items-center pr-2">
                                    <svg class="animate-spin h-4 w-4 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                
                                @if (strlen($searchTerm ?? '') > 0)
                                    <button wire:click="$set('searchTerm', '')"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-white transition-colors"
                                        wire:loading.class="hidden"
                                        wire:target="searchTerm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
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
                                                <div class="flex flex-col items-center text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    @if (strlen($searchTerm ?? '') > 0)
                                                        <p class="text-lg">No logs found matching "{{ $searchTerm }}"</p>
                                                        <button wire:click="$set('searchTerm', '')" class="mt-2 text-indigo-400 hover:text-indigo-300 transition-colors">
                                                            Clear search to view all logs
                                                        </button>
                                                    @else
                                                        <p class="text-lg">No admin activity logs found</p>
                                                        <p class="text-sm mt-1">Admin actions will appear here once performed</p>
                                                    @endif
                                                </div>
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
                @endif

                <!-- Whitelist Tab (R4+) -->
                @if ($activeTab === 'whitelist' && auth()->user()->admin_rank >= 4 && config('whitelisting.enabled'))
                    <div
                        class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Email Whitelist Management</h2>

                        @if (session()->has('emailmessage'))
                            <div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                                <strong class="font-bold">Success!</strong>
                                <span class="block sm:inline">{{ session('emailmessage') }}</span>
                            </div>
                        @endif

                        <form wire:submit.prevent="addEmail" class="max-w-lg mx-auto">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <x-label for="email" :value="__('Email Address')" />
                                    <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        wire:model="email" required />
                                    @error('email')
                                        <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <x-button class="w-full justify-center">
                                        {{ __('Add to Whitelist') }}
                                    </x-button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
