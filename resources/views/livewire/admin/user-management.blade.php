<div class="min-h-screen bg-zinc-900 text-zinc-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-zinc-100">User Management</h1>
                <p class="text-zinc-400 mt-1">Manage user accounts, permissions, and verification status</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Total Users</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ \App\Models\User::count() }}</p>
                    </div>
                    <flux:icon.users class="w-8 h-8 text-blue-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Admins</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ \App\Models\User::where('admin_level', '>', 0)->count() }}</p>
                    </div>
                    <flux:icon.shield-check class="w-8 h-8 text-purple-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Verified Users</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ \App\Models\User::where('is_verified', true)->count() }}</p>
                    </div>
                    <flux:icon.check-badge class="w-8 h-8 text-green-500"/>
                </div>
            </div>
            
            <div class="bg-zinc-800 rounded-lg p-6 border border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-400 text-sm">Suspended</p>
                        <p class="text-2xl font-bold text-zinc-100">{{ \App\Models\User::where('is_suspended', true)->count() }}</p>
                    </div>
                    <flux:icon.no-symbol class="w-8 h-8 text-red-500"/>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex space-x-1 bg-zinc-800 p-1 rounded-lg w-fit">
                <button 
                    wire:click="$set('filter', 'all')"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'all' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
                >
                    All Users
                </button>
                <button 
                    wire:click="$set('filter', 'admins')"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'admins' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
                >
                    Admins
                </button>
                <button 
                    wire:click="$set('filter', 'verified')"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'verified' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
                >
                    Verified
                </button>
                <button 
                    wire:click="$set('filter', 'suspended')"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'suspended' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
                >
                    Suspended
                </button>
            </div>
            
            <div class="flex-1 max-w-md">
                <flux:input 
                    wire:model.live="search" 
                    placeholder="Search users by username or email..." 
                    class="bg-zinc-800 border-zinc-700 text-zinc-100"
                >
                    <x-slot name="iconTrailing">
                        <flux:icon.magnifying-glass class="w-4 h-4 text-zinc-400"/>
                    </x-slot>
                </flux:input>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-zinc-800 rounded-lg border border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-zinc-900 border-b border-zinc-700">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-zinc-300">User</th>
                            <th class="text-left py-3 px-4 font-medium text-zinc-300">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-zinc-300">Stats</th>
                            <th class="text-left py-3 px-4 font-medium text-zinc-300">Joined</th>
                            <th class="text-left py-3 px-4 font-medium text-zinc-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-700">
                        @foreach($users as $user)
                            <tr class="hover:bg-zinc-700/50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-zinc-600 rounded-full flex items-center justify-center">
                                            <flux:icon.user class="w-5 h-5 text-zinc-300"/>
                                        </div>
                                        <div>
                                            <div class="font-medium text-zinc-100 flex items-center space-x-2">
                                                <span>{{ $user->username }}</span>
                                                @if($user->is_verified)
                                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                                @if($user->admin_level)
                                                    <span class="text-xs bg-purple-800 text-purple-200 px-2 py-1 rounded-full">
                                                        {{ $user->admin_level_name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-zinc-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-col space-y-1">
                                        @if($user->is_suspended)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-800 text-red-200">
                                                Suspended
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-800 text-green-200">
                                                Active
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm text-zinc-300">
                                        <div>{{ $user->posts_count }} posts</div>
                                        <div class="text-zinc-500">{{ $user->followers_count }} followers</div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm text-zinc-400">{{ $user->created_at->format('M j, Y') }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-col gap-1">
                                        <flux:button size="sm" variant="ghost" wire:click="verifyUser({{ $user->id }})" class="text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 text-xs">
                                            {{ $user->is_verified ? 'Remove Verification' : 'Verify User' }}
                                        </flux:button>
                                        <flux:button size="sm" variant="ghost" wire:click="suspendUser({{ $user->id }})" class="text-orange-400 hover:text-orange-300 hover:bg-orange-500/10 text-xs">
                                            {{ $user->is_suspended ? 'Unsuspend User' : 'Suspend User' }}
                                        </flux:button>
                                        @if(!$user->admin_level)
                                            <flux:button size="sm" variant="ghost" wire:click="promoteUser({{ $user->id }}, 1)" class="text-green-400 hover:text-green-300 hover:bg-green-500/10 text-xs">
                                                Make Moderator
                                            </flux:button>
                                            <flux:button size="sm" variant="ghost" wire:click="promoteUser({{ $user->id }}, 3)" class="text-purple-400 hover:text-purple-300 hover:bg-purple-500/10 text-xs">
                                                Make Admin
                                            </flux:button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
</div>
