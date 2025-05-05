<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>
    
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Header with Stats -->
            <div class="mb-8 bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Welcome,
                            @if (!auth()->user()->name)
                                {{ auth()->user()->handle }}
                            @else
                                {{ auth()->user()->name }}
                            @endif
                        </h1>
                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if(auth()->user()->admin_rank == 1) bg-blue-500/20 text-blue-300
                            @elseif(auth()->user()->admin_rank == 2) bg-green-500/20 text-green-300
                            @elseif(auth()->user()->admin_rank == 3) bg-purple-500/20 text-purple-300
                            @elseif(auth()->user()->admin_rank == 4) bg-indigo-500/20 text-indigo-300
                            @elseif(auth()->user()->admin_rank == 5) bg-red-500/20 text-red-300
                            @endif">
                            {{ $adminRanks[auth()->user()->admin_rank]['title'] }}
                        </div>
                    </div>
                    <a href="/pulse" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 !text-white rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Pulse Dashboard
                    </a>
                </div>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    <div class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Total Users</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['users']['total'] }}</h3>
                            </div>
                            <div class="bg-indigo-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Total Posts</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['content']['posts'] }}</h3>
                            </div>
                            <div class="bg-blue-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Admins</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['users']['admins'] }}</h3>
                            </div>
                            <div class="bg-purple-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800/30 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:border-white/20 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Banned Users</p>
                                <h3 class="text-white text-2xl font-bold">{{ $statistics['users']['banned'] }}</h3>
                            </div>
                            <div class="bg-red-500/20 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-700 mb-6">
                <nav class="flex flex-wrap -mb-px">
                    <button wire:click="setActiveTab('overview')" class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        Overview
                    </button>
                    <button wire:click="setActiveTab('admins')" class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'admins' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        Admin Management
                    </button>
                    <button wire:click="setActiveTab('users')" class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'users' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        User Management
                    </button>
                    <button wire:click="setActiveTab('logs')" class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'logs' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        Logs
                    </button>
                    @if (auth()->user()->admin_rank >= 4 && config('whitelisting.enabled'))
                    <button wire:click="setActiveTab('whitelist')" class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'whitelist' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                        Email Whitelist
                    </button>
                    @endif
                </nav>
            </div>
            
            <!-- Tab Content -->
            <div>
                <!-- Overview Tab -->
                @if ($activeTab === 'overview')
                <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($rank == 1) bg-blue-500/20 text-blue-300
                                            @elseif($rank == 2) bg-green-500/20 text-green-300
                                            @elseif($rank == 3) bg-purple-500/20 text-purple-300
                                            @elseif($rank == 4) bg-indigo-500/20 text-indigo-300
                                            @elseif($rank == 5) bg-red-500/20 text-red-300
                                            @endif">
                                            {{ $details['title'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($rank == 1)
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
                                        <td class="px-6 py-4">{{ $admin->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($admin->admin_rank == 1) bg-blue-500/20 text-blue-300
                                                @elseif($admin->admin_rank == 2) bg-green-500/20 text-green-300
                                                @elseif($admin->admin_rank == 3) bg-purple-500/20 text-purple-300
                                                @elseif($admin->admin_rank == 4) bg-indigo-500/20 text-indigo-300
                                                @elseif($admin->admin_rank == 5) bg-red-500/20 text-red-300
                                                @endif">
                                                {{ $adminRanks[$admin->admin_rank]['title'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('user-profile', $admin->id) }}" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                                    View
                                                </a>
                                                @if(auth()->user()->admin_rank == 4 && $admin->id != auth()->id() && $admin->admin_rank < auth()->user()->admin_rank)
                                                <button wire:click="confirmAction('demote', {{ $admin->id }})" class="text-red-400 hover:text-red-300 transition-colors">
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
                    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Add or Modify Admin</h2>
                        
                        @if (session()->has('addmessage'))
                        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('addmessage') }}</span>
                        </div>
                        @endif
                        
                        @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
                            <strong class="font-bold">Error:</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                        @endif
                        
                        <form wire:submit.prevent="addAdmin" class="max-w-lg mx-auto">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <x-label for="admin_id" :value="__('User ID')" />
                                    <x-input id="admin_id" class="block mt-1 w-full" type="text" name="admin_id"
                                        wire:model="admin_id" required />
                                    @error('admin_id')
                                        <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <x-label for="admin_rank" :value="__('Admin Rank')" />
                                    <select id="admin_rank" wire:model="admin_rank" class="border-white/20 bg-gray-800/10 backdrop-blur-sm text-gray-300 focus:border-white/30 focus:ring-indigo-600 rounded-md shadow-sm w-full mt-1">
                                        <option value="">Select a rank</option>
                                        @foreach($adminRanks as $rank => $details)
                                            @if($rank > 0 && $rank < auth()->user()->admin_rank)
                                                <option value="{{ $rank }}">{{ $rank }} - {{ $details['title'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
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
                    <!-- Ban User (R3+) -->
                    @if (auth()->user()->admin_rank >= 3)
                    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Ban User</h2>
                        
                        @if (session()->has('banmessage'))
                        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('banmessage') }}</span>
                        </div>
                        @endif
                        
                        <form wire:submit.prevent="banUser" class="max-w-lg mx-auto">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <x-label for="user_id" :value="__('User ID')" />
                                    <x-input id="user_id" class="block mt-1 w-full" type="text" name="user_id"
                                        wire:model="user_id" required />
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
                    
                    <!-- Unban User (R3+) -->
                    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Unban User</h2>
                        
                        @if (session()->has('unbanmessage'))
                        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('unbanmessage') }}</span>
                        </div>
                        @endif
                        
                        <form wire:submit.prevent="unbanUser" class="max-w-lg mx-auto">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <x-label for="uid" :value="__('User ID')" />
                                    <x-input id="uid" class="block mt-1 w-full" type="text" name="uid"
                                        wire:model="uid" required />
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
                    <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Banned Users</h2>
                        
                        @if($bannedUsers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-300">
                                <thead class="text-xs uppercase text-gray-400 bg-gray-800/40">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 rounded-l-lg">ID</th>
                                        <th scope="col" class="px-6 py-3">Username</th>
                                        <th scope="col" class="px-6 py-3">Email</th>
                                        <th scope="col" class="px-6 py-3">Banned on</th>
                                        <th scope="col" class="px-6 py-3">Reason</th>
                                        <th scope="col" class="px-6 py-3 rounded-r-lg">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bannedUsers as $banned)
                                    <tr class="bg-gray-800/20 border-b border-gray-700">
                                        <td class="px-6 py-4 font-medium">{{ $banned->user->id }}</td>
                                        <td class="px-6 py-4">{{ $banned->user->name }}</td>
                                        <td class="px-6 py-4">{{ $banned->user->email }}</td>
                                        <td class="px-6 py-4">{{ $banned->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 max-w-xs truncate">{{ $banned->reason }}</td>
                                        <td class="px-6 py-4">
                                            <button wire:click="confirmAction('unban', {{ $banned->user_id }})" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                                Unban
                                            </button>
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
                    @endif
                </div>
                @endif
                
                <!-- Logs Tab -->
                @if ($activeTab === 'logs')
                <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-white">Admin Activity Logs</h2>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input wire:model.debounce.300ms="searchTerm" type="text" class="border-white/20 bg-gray-800/10 backdrop-blur-sm text-gray-300 pl-10 focus:border-white/30 focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Search logs...">
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 bg-gray-800/40">
                                <tr>
                                    <th scope="col" class="px-6 py-3 rounded-l-lg">Admin ID</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                    <th scope="col" class="px-6 py-3 rounded-r-lg">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                <tr class="bg-gray-800/20 border-b border-gray-700">
                                    <td class="px-6 py-4 font-medium">{{ $log->admin_id }}</td>
                                    <td class="px-6 py-4">{{ $log->action }}</td>
                                    <td class="px-6 py-4">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                @endforeach
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
                <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
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

    <!-- Confirmation Modal -->
    <x-confirmation-modal wire:model="confirmingAction">
        <x-slot name="title">
            @if ($actionType === 'demote')
                {{ __('Demote Admin') }}
            @elseif ($actionType === 'unban')
                {{ __('Unban User') }}
            @else
                {{ __('Confirm Action') }}
            @endif
        </x-slot>

        <x-slot name="content">
            @if ($actionType === 'demote')
                {{ __('Are you sure you want to demote this admin? They will lose all admin privileges.') }}
            @elseif ($actionType === 'unban')
                {{ __('Are you sure you want to unban this user? They will regain access to the platform.') }}
            @else
                {{ __('Are you sure you want to perform this action?') }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="cancelAction" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-button>

            @if ($actionType === 'demote')
                <x-button class="ml-3 bg-red-600 hover:bg-red-700" wire:click="demoteAdmin({{ $targetUser }})" wire:loading.attr="disabled">
                    {{ __('Demote Admin') }}
                </x-button>
            @elseif ($actionType === 'unban')
                <x-button class="ml-3 bg-indigo-600 hover:bg-indigo-700" wire:click="unbanUser" wire:loading.attr="disabled">
                    {{ __('Unban User') }}
                </x-button>
            @endif
        </x-slot>
    </x-confirmation-modal>
    
    <style>
        .fixed-height-alert {
            height: 50px;
            overflow: hidden;
        }
    </style>
</div>
